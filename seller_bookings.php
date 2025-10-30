<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
  header('Location: login.php');
  exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'fyp');
if ($mysqli->connect_errno) {
  die('Connection failed: ' . $mysqli->connect_error);
}

$seller_id = (int)$_SESSION['user_id'];

// Ensure bookings schema bits exist (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  buyer_id INT NOT NULL,
  seller_id INT NOT NULL,
  status ENUM('pending','accepted','rejected','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  decision_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_b_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE,
  CONSTRAINT fk_b_buyer FOREIGN KEY (buyer_id) REFERENCES buyers(id) ON DELETE CASCADE,
  CONSTRAINT fk_b_seller FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("ALTER TABLE bookings ADD COLUMN IF NOT EXISTS booking_date DATE NULL AFTER status");
$mysqli->query("CREATE INDEX IF NOT EXISTS idx_booking_seller_status ON bookings(seller_id, status)");
$mysqli->query("CREATE INDEX IF NOT EXISTS idx_booking_car_date ON bookings(car_id, booking_date)");

// Filters (optional: simple)
$f_status = isset($_GET['status']) ? trim($_GET['status']) : 'accepted';
$validStatuses = ['accepted','pending','rejected','cancelled','all'];
if (!in_array($f_status, $validStatuses, true)) { $f_status = 'accepted'; }

$where = ['b.seller_id = ?'];
$types = 'i';
$params = [$seller_id];
if ($f_status !== 'all') { $where[] = 'b.status = ?'; $types .= 's'; $params[] = $f_status; }

$sql = "SELECT b.booking_id, b.car_id, b.status, b.booking_date, b.created_at, b.decision_at,
               u.name AS buyer_name, u.email AS buyer_email, u.phone AS buyer_phone,
               c.make, c.model, c.year, c.price
        FROM bookings b
        JOIN buyers u ON u.id = b.buyer_id
        JOIN cars c ON c.car_id = b.car_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY 
          CASE WHEN b.booking_date IS NULL THEN 1 ELSE 0 END ASC,
          COALESCE(b.booking_date, DATE(b.created_at)) ASC,
          b.booking_id DESC";
$st = $mysqli->prepare($sql);
if ($types !== '') {
  $bind = [$types];
  foreach ($params as $k => $v) { $bind[] = &$params[$k]; }
  call_user_func_array([$st, 'bind_param'], $bind);
}
$st->execute();
$res = $st->get_result();
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$st->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seller Bookings</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Seller Bookings</h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="seller_main.php" class="hover:underline">Dashboard</a></li>
        <li><a href="seller_bookings.php" class="hover:underline font-semibold underline">Bookings</a></li>
        <li><a href="seller_profile.php" class="hover:underline">Profile</a></li>
        <li><a href="logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="container mx-auto mt-8">
  <div class="mb-4 flex items-center justify-between">
    <a href="seller_main.php" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded">← Back to Dashboard</a>
    <form method="get" class="flex items-center gap-2">
      <label for="status" class="text-sm text-gray-700">Status</label>
      <select id="status" name="status" class="border p-2 rounded">
        <option value="accepted" <?php if($f_status==='accepted') echo 'selected'; ?>>Accepted</option>
        <option value="pending" <?php if($f_status==='pending') echo 'selected'; ?>>Pending</option>
        <option value="rejected" <?php if($f_status==='rejected') echo 'selected'; ?>>Rejected</option>
        <option value="cancelled" <?php if($f_status==='cancelled') echo 'selected'; ?>>Cancelled</option>
        <option value="all" <?php if($f_status==='all') echo 'selected'; ?>>All</option>
      </select>
      <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Apply</button>
    </form>
  </div>

  <div class="bg-white rounded-xl shadow p-4">
    <div class="flex items-center justify-between mb-2">
      <h2 class="text-xl font-bold">Bookings (<?php echo htmlspecialchars(ucfirst($f_status)); ?>)</h2>
      <span class="text-sm text-gray-600"><?php echo count($rows); ?> total</span>
    </div>
    <?php if (empty($rows)): ?>
      <div class="text-sm text-gray-600">No bookings found.</div>
    <?php else: ?>
      <div class="divide-y">
        <?php foreach ($rows as $bk): ?>
          <div class="py-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
              <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($bk['make'].' '.$bk['model'].' ('.$bk['year'].')'); ?></div>
              <div class="text-sm text-gray-600">Status: <span class="font-medium capitalize"><?php echo htmlspecialchars($bk['status']); ?></span></div>
              <div class="text-sm text-gray-600">Date: <span class="font-medium"><?php echo htmlspecialchars($bk['booking_date'] ?: substr($bk['created_at'],0,10)); ?></span></div>
              <div class="text-sm text-gray-600">Buyer: <?php echo htmlspecialchars($bk['buyer_name']); ?><?php if(!empty($bk['buyer_phone'])) echo ' • '.htmlspecialchars($bk['buyer_phone']); ?><?php if(!empty($bk['buyer_email'])) echo ' • '.htmlspecialchars($bk['buyer_email']); ?></div>
            </div>
            <div class="flex gap-2">
              <a href="car_details.php?car_id=<?php echo (int)$bk['car_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">View Car</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
