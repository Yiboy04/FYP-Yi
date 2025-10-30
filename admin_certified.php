<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header('Location: admin_login.php'); exit(); }

$mysqli = new mysqli('localhost','root','', 'fyp');
if ($mysqli->connect_errno) { die('DB error: '.$mysqli->connect_error); }

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function fmtRM($n){ return 'RM '.number_format((float)$n, 2); }

// Ensure table exists
$mysqli->query("CREATE TABLE IF NOT EXISTS certified_requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  seller_id INT NOT NULL,
  status ENUM('requested','approved','declined','cancelled') NOT NULL DEFAULT 'requested',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  decided_at TIMESTAMP NULL DEFAULT NULL,
  decided_by INT NULL,
  INDEX idx_cert_car_status (car_id, status),
  CONSTRAINT fk_cert_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE,
  CONSTRAINT fk_cert_seller FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$flash = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  $rid = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;
  if ($rid>0){
    // Load request
    $req = null;
    if ($st = $mysqli->prepare("SELECT cr.*, c.make, c.model, c.year FROM certified_requests cr JOIN cars c ON c.car_id=cr.car_id WHERE request_id=?")){
      $st->bind_param('i', $rid); $st->execute(); $req = $st->get_result()->fetch_assoc(); $st->close();
    }
    if ($req && $req['status']==='requested'){
      if ($action==='approve'){
        // Approve: set request status + set car_details.car_condition='Certified'
        $mysqli->begin_transaction();
        $ok = false;
        if ($up = $mysqli->prepare("UPDATE certified_requests SET status='approved', decided_at=NOW(), decided_by=NULL WHERE request_id=?")){
          $up->bind_param('i', $rid); $ok = $up->execute(); $up->close();
        }
        if ($ok){
          $cid = (int)$req['car_id'];
          // Update first; if no row affected, insert a new row
          if ($st2 = $mysqli->prepare("UPDATE car_details SET car_condition='Certified' WHERE car_id=?")){
            $st2->bind_param('i', $cid); $st2->execute();
            $affected = $mysqli->affected_rows; $st2->close();
            if ($affected === 0) {
              if ($ins = $mysqli->prepare("INSERT INTO car_details (car_id, car_condition) VALUES (?, 'Certified')")){
                $ins->bind_param('i', $cid); $ins->execute(); $ins->close();
              }
            }
          }
        }
        $mysqli->commit();
        $flash = 'Certification approved.';
      } elseif ($action==='decline'){
        if ($up = $mysqli->prepare("UPDATE certified_requests SET status='declined', decided_at=NOW(), decided_by=NULL WHERE request_id=?")){
          $up->bind_param('i', $rid); $up->execute(); $up->close();
          $flash = 'Certification declined.';
        }
      }
    }
  }
}

// Filters
$status = isset($_GET['status']) ? $_GET['status'] : 'requested';
$where = '';$types='';$params=[];
if (in_array($status,['requested','approved','declined','cancelled','all'],true) && $status!=='all'){
  $where = 'WHERE cr.status=?'; $types='s'; $params[]=$status;
}
$sql = "SELECT cr.request_id, cr.car_id, cr.seller_id, cr.status, cr.created_at, cr.decided_at,
               c.make, c.model, c.year, c.price,
               s.name AS seller_name
        FROM certified_requests cr
        JOIN cars c ON c.car_id=cr.car_id
        JOIN sellers s ON s.id=cr.seller_id
        $where
        ORDER BY FIELD(cr.status,'requested','approved','declined','cancelled'), cr.created_at DESC LIMIT 500";
$rows = [];
if ($st = $mysqli->prepare($sql)){
  if ($types!==''){ $st->bind_param($types, ...$params); }
  $st->execute(); $res=$st->get_result();
  while ($r=$res->fetch_assoc()){ $rows[]=$r; }
  $st->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certified Requests</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="min-h-screen flex">
    <aside class="w-64 bg-red-600 shadow-lg">
      <div class="p-5 border-b border-red-500">
        <h1 class="text-2xl font-bold text-white">Admin</h1>
        <div class="text-sm text-white mt-1">Welcome, <?php echo h($_SESSION['admin_name']); ?>!</div>
      </div>
      <nav class="p-3 space-y-1">
        <a href="admin_dashboard.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Dashboard</a>
        <a href="admin_users.php?type=buyers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Buyers</a>
        <a href="admin_users.php?type=sellers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Sellers</a>
        <a href="admin_cars.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Cars</a>
        <a href="admin_bookings.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Bookings</a>
        <a href="admin_certified.php" class="block px-3 py-2 rounded bg-red-500 text-white font-medium">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-4">Certified Requests</h2>
      <?php if ($flash): ?>
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded"><?php echo h($flash); ?></div>
      <?php endif; ?>

      <form method="get" class="bg-white rounded shadow p-4 mb-4 inline-flex items-end gap-3">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Status</label>
          <select name="status" class="border rounded p-2">
            <?php $opts=['requested'=>'Requested','approved'=>'Approved','declined'=>'Declined','cancelled'=>'Cancelled','all'=>'All']; foreach($opts as $k=>$v): ?>
              <option value="<?php echo $k; ?>" <?php echo ($status===$k?'selected':''); ?>><?php echo $v; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Apply</button>
          <a href="admin_certified.php" class="ml-2 px-4 py-2 rounded border">Clear</a>
        </div>
      </form>

      <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-3 py-2 text-left">Request</th>
              <th class="px-3 py-2 text-left">Car</th>
              <th class="px-3 py-2 text-center">Year</th>
              <th class="px-3 py-2 text-right">Price</th>
              <th class="px-3 py-2 text-left">Seller</th>
              <th class="px-3 py-2 text-left">Requested At</th>
              <th class="px-3 py-2 text-center">Status</th>
              <th class="px-3 py-2 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr><td colspan="8" class="px-3 py-6 text-center text-gray-500">No requests found.</td></tr>
            <?php else: foreach($rows as $r): ?>
              <tr class="border-t">
                <td class="px-3 py-2">#<?php echo (int)$r['request_id']; ?></td>
                <td class="px-3 py-2">
                  <div class="font-medium"><?php echo h($r['make'].' '.$r['model']); ?></div>
                  <a class="text-xs text-blue-600 hover:underline" target="_blank" href="admin_car_view.php?car_id=<?php echo (int)$r['car_id']; ?>">View in admin</a>
                </td>
                <td class="px-3 py-2 text-center"><?php echo (int)$r['year']; ?></td>
                <td class="px-3 py-2 text-right"><?php echo fmtRM($r['price']); ?></td>
                <td class="px-3 py-2"><?php echo h($r['seller_name']); ?></td>
                <td class="px-3 py-2"><?php echo h($r['created_at']); ?></td>
                <td class="px-3 py-2 text-center">
                  <span class="px-2 py-0.5 rounded text-xs <?php 
                    echo $r['status']==='requested' ? 'bg-yellow-100 text-yellow-800' : ($r['status']==='approved' ? 'bg-green-100 text-green-800' : ($r['status']==='declined' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')); 
                  ?>"><?php echo h(ucfirst($r['status'])); ?></span>
                </td>
                <td class="px-3 py-2 text-center">
                  <?php if ($r['status']==='requested'): ?>
                  <form method="post" class="inline-flex gap-1">
                    <input type="hidden" name="request_id" value="<?php echo (int)$r['request_id']; ?>">
                    <button name="action" value="approve" class="px-2 py-1 text-xs bg-green-600 text-white rounded">Approve</button>
                    <button name="action" value="decline" class="px-2 py-1 text-xs bg-red-600 text-white rounded">Decline</button>
                  </form>
                  <?php else: ?>
                    <span class="text-xs text-gray-500">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</body>
</html>
