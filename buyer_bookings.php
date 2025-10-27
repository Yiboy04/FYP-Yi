<?php
// buyer_bookings.php - Buyer view of their bookings (pending and confirmed)
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}
// Optional role check if your app uses roles
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'buyer') {
  header('Location: main.php');
  exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'fyp');
if ($mysqli->connect_errno) {
  die('DB error: ' . $mysqli->connect_error);
}

$buyer_id = (int)$_SESSION['user_id'];

// Allow buyer to cancel their own pending booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] === 'cancel_pending' && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    // Ensure this booking belongs to the logged-in buyer and is pending
    $stmt = $mysqli->prepare("UPDATE bookings SET status='cancelled', decision_at=NOW() WHERE booking_id=? AND buyer_id=? AND status='pending'");
    if ($stmt) {
      $stmt->bind_param('ii', $booking_id, $buyer_id);
      $stmt->execute();
      $stmt->close();
    }
    header('Location: buyer_bookings.php');
    exit();
  }
}

// Fetch buyer bookings: Pending (car still open) and Accepted (car negotiating)
$sql = "SELECT b.booking_id, b.car_id, b.status, b.created_at, b.decision_at,
               c.make, c.model, c.variant, c.year, c.price, c.mileage, c.transmission
        FROM bookings b
        JOIN cars c ON b.car_id = c.car_id
        WHERE b.buyer_id = ?
          AND (
            (b.status = 'pending' AND (c.listing_status IS NULL OR c.listing_status = '' OR c.listing_status = 'open'))
            OR
            (b.status = 'accepted' AND c.listing_status = 'negotiating')
          )
        ORDER BY FIELD(b.status,'pending','accepted'), COALESCE(b.decision_at, b.created_at) DESC";
$stmt = $mysqli->prepare($sql);
if (!$stmt) { die('Query error: ' . $mysqli->error); }
$stmt->bind_param('i', $buyer_id);
$stmt->execute();
$res = $stmt->get_result();

$pending = [];
$accepted = [];
$carIds = [];
if ($res) {
  while ($row = $res->fetch_assoc()) {
    $carIds[] = (int)$row['car_id'];
    if ($row['status'] === 'pending') $pending[] = $row; else if ($row['status']==='accepted') $accepted[] = $row;
  }
}
$stmt->close();

// Preload thumbnails for these cars
$thumbnails = [];
if (!empty($carIds)) {
  $ids = implode(',', array_map('intval', $carIds));
  $imgQ = $mysqli->query("SELECT car_id, image_path, is_thumbnail FROM car_images WHERE car_id IN ($ids) ORDER BY is_thumbnail DESC, image_id ASC");
  if ($imgQ) {
    while ($img = $imgQ->fetch_assoc()) {
      $cid = (int)$img['car_id'];
      if (!isset($thumbnails[$cid])) {
        $thumbnails[$cid] = $img['image_path'];
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    .chip { font-size: 0.75rem; padding: 0.15rem 0.5rem; border-radius: 9999px; }
  </style>
  </head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">My Bookings</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li><a href="saved_search.php" title="Saved" class="hover:underline">Saved</a></li>
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="list_cars.php" class="hover:underline">Listings</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
            <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
          <?php endif; ?>
          <li><a href="logout.php" class="hover:underline">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container mx-auto mt-8 flex flex-col gap-8">
    <!-- Pending section -->
    <section class="bg-white rounded-xl shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Pending Requests</h2>
        <span class="text-sm text-gray-500"><?php echo count($pending); ?> item(s)</span>
      </div>
      <?php if (!empty($pending)): ?>
        <div class="grid grid-cols-1 gap-4">
          <?php foreach ($pending as $b): ?>
            <?php $thumb = $thumbnails[$b['car_id']] ?? 'https://via.placeholder.com/480x320?text=No+Image'; ?>
            <div class="bg-gray-50 rounded-lg p-4 shadow flex flex-col md:flex-row gap-4">
              <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full md:w-52 h-40 object-cover rounded" alt="Car">
              <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <h3 class="text-lg font-bold truncate"><?php echo htmlspecialchars($b['make'].' '.$b['model']); ?></h3>
                    <?php if (!empty($b['variant'])): ?><div class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($b['variant']); ?></div><?php endif; ?>
                    <div class="text-xs text-gray-500 mt-1">Requested on: <?php echo htmlspecialchars($b['created_at']); ?></div>
                  </div>
                  <div class="text-right">
                    <div class="text-red-600 font-extrabold text-xl whitespace-nowrap">RM <?php echo number_format((float)$b['price'], 0); ?></div>
                    <div class="mt-1 inline-block bg-yellow-100 text-yellow-800 chip">Pending</div>
                  </div>
                </div>
                <div class="mt-3 flex gap-2">
                  <a href="car_details_view.php?car_id=<?php echo (int)$b['car_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">View Details</a>
                  <form method="post" onsubmit="return confirm('Cancel this booking request?');">
                    <input type="hidden" name="action" value="cancel_pending">
                    <input type="hidden" name="booking_id" value="<?php echo (int)$b['booking_id']; ?>">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-gray-500 text-center py-8">No pending bookings.</div>
      <?php endif; ?>
    </section>

    <!-- Negotiating section (accepted by seller) -->
    <section class="bg-white rounded-xl shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Negotiating</h2>
        <span class="text-sm text-gray-500"><?php echo count($accepted); ?> item(s)</span>
      </div>
      <?php if (!empty($accepted)): ?>
        <div class="grid grid-cols-1 gap-4">
          <?php foreach ($accepted as $b): ?>
            <?php $thumb = $thumbnails[$b['car_id']] ?? 'https://via.placeholder.com/480x320?text=No+Image'; ?>
            <div class="bg-gray-50 rounded-lg p-4 shadow flex flex-col md:flex-row gap-4">
              <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full md:w-52 h-40 object-cover rounded" alt="Car">
              <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <h3 class="text-lg font-bold truncate"><?php echo htmlspecialchars($b['make'].' '.$b['model']); ?></h3>
                    <?php if (!empty($b['variant'])): ?><div class="text-sm text-gray-600 truncate"><?php echo htmlspecialchars($b['variant']); ?></div><?php endif; ?>
                    <div class="text-xs text-gray-500 mt-1">Accepted on: <?php echo htmlspecialchars($b['decision_at'] ?? $b['created_at']); ?></div>
                  </div>
                  <div class="text-right">
                    <div class="text-red-600 font-extrabold text-xl whitespace-nowrap">RM <?php echo number_format((float)$b['price'], 0); ?></div>
                    <div class="mt-1 inline-block bg-blue-100 text-blue-800 chip">Negotiating</div>
                  </div>
                </div>
                <div class="mt-3 flex gap-2">
                  <a href="car_details_view.php?car_id=<?php echo (int)$b['car_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-gray-500 text-center py-8">No negotiating bookings.</div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
