<?php
// compare_search.php - returns JSON suggestions for compare selectors
header('Content-Type: application/json');

// Basic rate limit guard (very light)
if ($_SERVER['REQUEST_METHOD'] !== 'GET') { echo json_encode([]); exit; }

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if ($q === '') { echo json_encode([]); exit; }

$mysqli = new mysqli('localhost', 'root', '', 'fyp');
if ($mysqli->connect_errno) { echo json_encode([]); exit; }

// Helper: build display label consistent with compare.php (without car_id)
function fmtRM($n){ return 'RM ' . number_format((float)$n, 2); }
function buildLabel($row){
  $mk = $row['make'] ?? '';
  $md = $row['model'] ?? '';
  $vr = $row['variant'] ?? '';
  $yr = $row['year'] ?? '';
  $pr = fmtRM($row['price'] ?? 0);
  $name = trim($mk . ' ' . $md . ' ' . ($vr ?: ''));
  return "{$name} {$yr} ({$pr})";
}

$results = [];

// If query looks like an ID (#123 or 123), try exact ID first
if (preg_match('/^#?(\d{1,10})$/', $q, $m)) {
  $id = (int)$m[1];
  if ($id > 0) {
    if ($stmt = $mysqli->prepare('SELECT car_id, make, model, variant, year, price FROM cars WHERE car_id = ? LIMIT 1')) {
      $stmt->bind_param('i', $id);
      if ($stmt->execute() && ($res = $stmt->get_result())) {
        if ($row = $res->fetch_assoc()) { $results[] = $row; }
        $res->free();
      }
      $stmt->close();
    }
  }
}

// If not enough results, do token-based LIKE search across make/model/variant/year
if (count($results) < 10) {
  $tokens = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);
  $tokens = array_slice($tokens, 0, 5); // cap tokens

  // Build concatenated searchable string
  $likeSqlParts = [];
  $params = [];
  $types = '';
  foreach ($tokens as $t) {
    $likeSqlParts[] = "CONCAT_WS(' ', cars.car_id, cars.make, cars.model, IFNULL(cars.variant,''), cars.year, cars.price) LIKE ?";
    $params[] = '%' . $t . '%';
    $types .= 's';
  }

  $sql = 'SELECT car_id, make, model, variant, year, price FROM cars';
  if ($likeSqlParts) {
    $sql .= ' WHERE ' . implode(' AND ', $likeSqlParts);
  }
  $sql .= ' ORDER BY car_id DESC LIMIT 50';

  if ($stmt = $mysqli->prepare($sql)) {
    if ($types !== '') { $stmt->bind_param($types, ...$params); }
    if ($stmt->execute() && ($res = $stmt->get_result())) {
      while ($row = $res->fetch_assoc()) { $results[] = $row; }
      $res->free();
    }
    $stmt->close();
  }
}

// Deduplicate by car_id while preserving order
$seen = [];
$out = [];
foreach ($results as $r) {
  $id = (int)$r['car_id'];
  if (isset($seen[$id])) continue;
  $seen[$id] = true;
  $out[] = ['id' => $id, 'label' => buildLabel($r)];
  if (count($out) >= 50) break;
}

echo json_encode($out);
