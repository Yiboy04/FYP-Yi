<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header('Location: admin_login.php'); exit(); }

$mysqli = new mysqli('localhost','root','', 'fyp');
if ($mysqli->connect_errno) { die('DB error: '.$mysqli->connect_error); }

// Helpers
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function fmtRM($n){ return 'RM '.number_format((float)$n, 2); }

// Handle actions: update status or reschedule
$flash = '';
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
  if ($bookingId>0){
    if ($action === 'set_status'){
      $newStatus = $_POST['new_status'] ?? '';
      if (in_array($newStatus, ['accepted','rejected','cancelled','pending'], true)){
        // Get booking + car id
        $bk = null;
        if ($st = $mysqli->prepare('SELECT car_id, status FROM bookings WHERE booking_id=?')){
          $st->bind_param('i', $bookingId); $st->execute(); $bk = $st->get_result()->fetch_assoc(); $st->close();
        }
        if ($bk){
          if ($st = $mysqli->prepare("UPDATE bookings SET status=?, decision_at=NOW() WHERE booking_id=?")){
            $st->bind_param('si', $newStatus, $bookingId); $ok = $st->execute(); $st->close();
            if ($ok){
              // Update car listing_status depending on accepted vs others
              $carId = (int)$bk['car_id'];
              if ($newStatus === 'accepted'){
                $mysqli->query("UPDATE cars SET listing_status='negotiating' WHERE car_id=".$carId);
              } else {
                // If no other accepted bookings exist for this car, set open
                if ($chk = $mysqli->prepare("SELECT 1 FROM bookings WHERE car_id=? AND status='accepted' LIMIT 1")){
                  $chk->bind_param('i', $carId); $chk->execute(); $chk->store_result();
                  $hasAccepted = $chk->num_rows > 0; $chk->close();
                  if (!$hasAccepted){ $mysqli->query("UPDATE cars SET listing_status='open' WHERE car_id=".$carId); }
                }
              }
              $flash = 'Booking status updated.';
            }
          }
        }
      }
    } elseif ($action === 'reschedule') {
      // Validate new date: >= today, not conflict with other active bookings
      $newDateRaw = trim((string)($_POST['new_date'] ?? ''));
      $dt = DateTime::createFromFormat('Y-m-d', $newDateRaw);
      $valid = $dt && $dt->format('Y-m-d') === $newDateRaw;
      if ($valid){
        $today = new DateTime('today');
        if ($dt < $today){ $flash = 'New date cannot be in the past.'; }
        else {
          // Get booking info
          $bk = null;
          if ($st = $mysqli->prepare('SELECT car_id FROM bookings WHERE booking_id=?')){
            $st->bind_param('i', $bookingId); $st->execute(); $bk = $st->get_result()->fetch_assoc(); $st->close();
          }
          if ($bk){
            $carId = (int)$bk['car_id']; $newDate = $dt->format('Y-m-d');
            // Check conflict with other active bookings on same date
            if ($st = $mysqli->prepare("SELECT 1 FROM bookings WHERE car_id=? AND booking_date=? AND status IN ('pending','accepted') AND booking_id<>? LIMIT 1")){
              $st->bind_param('isi', $carId, $newDate, $bookingId); $st->execute(); $conf = $st->get_result()->fetch_row(); $st->close();
              if ($conf){ $flash = 'Another active booking already exists on that date for this car.'; }
              else {
                if ($up = $mysqli->prepare('UPDATE bookings SET booking_date=? WHERE booking_id=?')){
                  $up->bind_param('si', $newDate, $bookingId);
                  if ($up->execute()) { $flash = 'Booking rescheduled.'; } else { $flash = 'Failed to reschedule.'; }
                  $up->close();
                }
              }
            }
          }
        }
      } else { $flash = 'Invalid date format.'; }
    }
  }
}

// Filters
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = [];
$params = [];
$types = '';
// Always exclude cancelled bookings from the list
$where[] = "b.status<>'cancelled'";
// Allow filtering only by non-cancelled statuses
if (in_array($status, ['pending','accepted','rejected'], true)) { $where[] = 'b.status=?'; $types.='s'; $params[]=$status; }
if ($date !== '') { $where[] = 'b.booking_date=?'; $types.='s'; $params[]=$date; }
if ($q !== '') { $w = "(c.make LIKE CONCAT('%',?,'%') OR c.model LIKE CONCAT('%',?,'%') OR s.name LIKE CONCAT('%',?,'%') OR byu.name LIKE CONCAT('%',?,'%'))"; $where[]=$w; $types.='ssss'; $params[]= $q; $params[]=$q; $params[]=$q; $params[]=$q; }
$wsql = $where ? ('WHERE '.implode(' AND ', $where)) : '';

$sql = "SELECT b.booking_id, b.car_id, b.buyer_id, b.seller_id, b.status, b.booking_date, b.created_at,
               c.make, c.model, c.year, c.price, s.name AS seller_name, byu.name AS buyer_name
        FROM bookings b
        JOIN cars c ON c.car_id=b.car_id
        JOIN sellers s ON s.id=b.seller_id
        JOIN buyers byu ON byu.id=b.buyer_id
        $wsql
        ORDER BY b.booking_date IS NULL, b.booking_date ASC, b.booking_id DESC
        LIMIT 500";

$rows = [];
if ($st = $mysqli->prepare($sql)){
  if ($types !== '') { $st->bind_param($types, ...$params); }
  $st->execute(); $res = $st->get_result();
  while ($r = $res->fetch_assoc()){ $rows[] = $r; }
  $st->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Bookings</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="min-h-screen flex">
    <!-- Left sidebar -->
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
        <a href="admin_bookings.php" class="block px-3 py-2 rounded bg-red-500 text-white font-medium">Bookings</a>
  <a href="admin_certified.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-4">Bookings</h2>
      <?php if ($flash): ?>
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded"><?php echo h($flash); ?></div>
      <?php endif; ?>
      <form method="get" class="bg-white rounded shadow p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Status</label>
          <select name="status" class="w-full border rounded p-2">
            <?php $opts=['all'=>'All','pending'=>'Pending','accepted'=>'Accepted','rejected'=>'Rejected']; foreach($opts as $k=>$v): ?>
              <option value="<?php echo $k; ?>" <?php echo ($status===$k?'selected':''); ?>><?php echo $v; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Date</label>
          <input type="date" name="date" value="<?php echo h($date); ?>" class="w-full border rounded p-2" />
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm text-gray-700 mb-1">Search (make/model/seller/buyer)</label>
          <input type="text" name="q" value="<?php echo h($q); ?>" placeholder="e.g. Perodua Myvi or John" class="w-full border rounded p-2" />
        </div>
        <div class="md:col-span-4">
          <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Filter</button>
          <a href="admin_bookings.php" class="ml-2 px-4 py-2 rounded border">Clear</a>
        </div>
      </form>

      <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-3 py-2 text-left">Car</th>
              <th class="px-3 py-2">Year</th>
              <th class="px-3 py-2 text-right">Price</th>
              <th class="px-3 py-2 text-left">Buyer</th>
              <th class="px-3 py-2 text-left">Seller</th>
              <th class="px-3 py-2 text-center">Date</th>
              <th class="px-3 py-2 text-center">Status</th>
              <th class="px-3 py-2 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$rows): ?>
              <tr><td colspan="8" class="px-3 py-6 text-center text-gray-500">No bookings found.</td></tr>
            <?php else: foreach($rows as $r): ?>
              <tr class="border-t">
                <td class="px-3 py-2 align-top">
                  <div class="font-medium"><?php echo h($r['make'].' '.$r['model']); ?></div>
                  <a class="text-xs text-blue-600 hover:underline" target="_blank" href="car_details_view.php?car_id=<?php echo (int)$r['car_id']; ?>">View listing</a>
                </td>
                <td class="px-3 py-2 align-top text-center"><?php echo (int)$r['year']; ?></td>
                <td class="px-3 py-2 align-top text-right"><?php echo fmtRM($r['price']); ?></td>
                <td class="px-3 py-2 align-top"><?php echo h($r['buyer_name']); ?></td>
                <td class="px-3 py-2 align-top"><?php echo h($r['seller_name']); ?></td>
                <td class="px-3 py-2 align-top text-center"><?php echo h($r['booking_date'] ?: '-'); ?></td>
                <td class="px-3 py-2 align-top text-center">
                  <span class="px-2 py-0.5 rounded text-xs <?php 
                    echo $r['status']==='pending' ? 'bg-yellow-100 text-yellow-800' : ($r['status']==='accepted' ? 'bg-green-100 text-green-800' : ($r['status']==='rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')); 
                  ?>"><?php echo h(ucfirst($r['status'])); ?></span>
                </td>
                <td class="px-3 py-2 align-top text-center">
                  <div class="flex flex-col gap-1 items-center">
                    <form method="post" class="inline-flex gap-1">
                      <input type="hidden" name="booking_id" value="<?php echo (int)$r['booking_id']; ?>">
                      <input type="hidden" name="action" value="set_status">
                      <?php if ($r['status']==='pending'): ?>
                        <button name="new_status" value="accepted" class="px-2 py-1 text-xs bg-green-600 text-white rounded">Accept</button>
                        <button name="new_status" value="rejected" class="px-2 py-1 text-xs bg-red-600 text-white rounded">Reject</button>
                      <?php elseif ($r['status']==='accepted' || $r['status']==='rejected'): ?>
                        <button name="new_status" value="cancelled" class="px-2 py-1 text-xs bg-gray-600 text-white rounded">Cancel</button>
                      <?php endif; ?>
                    </form>
                    <form method="post" class="inline-flex gap-1 items-center mt-1">
                      <input type="hidden" name="booking_id" value="<?php echo (int)$r['booking_id']; ?>">
                      <input type="hidden" name="action" value="reschedule">
                      <input type="date" name="new_date" class="border rounded px-2 py-1 text-xs" value="<?php echo h($r['booking_date']); ?>">
                      <button class="px-2 py-1 text-xs border rounded">Reschedule</button>
                    </form>
                  </div>
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
