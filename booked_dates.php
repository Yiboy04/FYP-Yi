<?php
// booked_dates.php - return booked/blocked dates for a car as JSON
header('Content-Type: application/json');

// Basic input
$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
if ($car_id <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'invalid_car_id']);
  exit;
}

$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
  http_response_code(500);
  echo json_encode(['error' => 'db_error']);
  exit;
}

// Collect dates with active bookings (pending/accepted)
$dates = [];
$sql = "SELECT DATE_FORMAT(booking_date, '%Y-%m-%d') AS d
        FROM bookings
        WHERE car_id=? AND booking_date IS NOT NULL AND status IN ('pending','accepted')
        GROUP BY booking_date
        ORDER BY booking_date ASC";
if ($st = $mysqli->prepare($sql)) {
  $st->bind_param('i', $car_id);
  if ($st->execute()) {
    $res = $st->get_result();
    while ($row = $res->fetch_assoc()) {
      if (!empty($row['d'])) { $dates[] = $row['d']; }
    }
  }
  $st->close();
}

// Response
echo json_encode([
  'car_id' => $car_id,
  'dates' => $dates,
]);
