<?php
// saved_search.php — Saved cars for buyers
session_start();

$mysqli = new mysqli('localhost', 'root', '', 'fyp');
if ($mysqli->connect_errno) { die('DB error: ' . $mysqli->connect_error); }

// Only buyers can have saved cars for now
if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
  header('Location: login.php');
  exit();
}
$buyerId = intval($_SESSION['user_id']);

// Ensure table exists (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS saved_cars (
  buyer_id INT NOT NULL,
  car_id INT NOT NULL,
  saved_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (buyer_id, car_id),
  CONSTRAINT fk_saved_buyer FOREIGN KEY (buyer_id) REFERENCES buyers(id) ON DELETE CASCADE,
  CONSTRAINT fk_saved_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Handle save/unsave actions
if (isset($_GET['save'])) {
  $carId = intval($_GET['save']);
  if ($carId > 0) {
    // Insert if not exists
    if ($st = $mysqli->prepare('INSERT IGNORE INTO saved_cars (buyer_id, car_id) VALUES (?, ?)')) {
      $st->bind_param('ii', $buyerId, $carId);
      $st->execute();
      $st->close();
    }
    header('Location: saved_search.php');
    exit();
  }
}
if (isset($_GET['unsave'])) {
  $carId = intval($_GET['unsave']);
  if ($carId > 0) {
    if ($st = $mysqli->prepare('DELETE FROM saved_cars WHERE buyer_id=? AND car_id=?')) {
      $st->bind_param('ii', $buyerId, $carId);
      $st->execute();
      $st->close();
    }
    header('Location: saved_search.php');
    exit();
  }
}

// Fetch saved cars with thumbnail-first logic
$saved = [];
$sql = "SELECT c.car_id, c.make, c.model, c.year, c.variant, c.price,
               COALESCE(ci1.image_path, ci2.image_path) AS thumb,
               sc.saved_at
        FROM saved_cars sc
        JOIN cars c ON c.car_id = sc.car_id
        LEFT JOIN (
          SELECT ci.car_id, ci.image_path
          FROM car_images ci
          JOIN (
            SELECT car_id, MIN(image_id) AS min_id
            FROM car_images
            WHERE is_thumbnail = 1 OR is_thumbnail = '1'
            GROUP BY car_id
          ) t ON t.car_id = ci.car_id AND t.min_id = ci.image_id
        ) ci1 ON ci1.car_id = c.car_id
        LEFT JOIN (
          SELECT ci.car_id, ci.image_path
          FROM car_images ci
          JOIN (
            SELECT car_id, MIN(image_id) AS min_id
            FROM car_images
            GROUP BY car_id
          ) t ON t.car_id = ci.car_id AND t.min_id = ci.image_id
        ) ci2 ON ci2.car_id = c.car_id
         WHERE sc.buyer_id = ? 
        ORDER BY sc.saved_at DESC";
if ($st = $mysqli->prepare($sql)) {
  $st->bind_param('i', $buyerId);
  if ($st->execute()) {
    $r = $st->get_result();
    while ($row = $r->fetch_assoc()) { $saved[] = $row; }
  }
  $st->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Saved Cars</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    a.icon:hover svg { color: #e5e7eb; }
  </style>
  </head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <!-- HEADER (match main.php) -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li>
            <a href="saved_search.php" class="icon inline-flex items-center" title="Saved Searches">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                <path d="M6 2a2 2 0 0 0-2 2v18l8-4 8 4V4a2 2 0 0 0-2-2H6z"/>
              </svg>
            </a>
          </li>
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="list_cars.php" class="hover:underline">Listings</a></li>
          <li><a href="#" class="hover:underline">About</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
            <li><a href="buyer_bookings.php" class="hover:underline">Bookings</a></li>
            <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
          <?php endif; ?>
          <li><a href="logout.php" class="hover:underline">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- MAIN -->
  <main class="container mx-auto flex-1 p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-2xl font-bold">Saved Cars</h2>
      <?php if (!empty($saved)): ?>
        <div class="text-sm text-gray-600">Total: <?php echo count($saved); ?></div>
      <?php endif; ?>
    </div>

    <?php if (!empty($saved)): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach ($saved as $car): ?>
          <div class="bg-white rounded-xl shadow overflow-hidden">
            <a href="car_details_view.php?car_id=<?php echo (int)$car['car_id']; ?>" class="block bg-gray-200 w-full aspect-[4/3] overflow-hidden">
              <?php if (!empty($car['thumb'])): ?>
                <img src="<?php echo htmlspecialchars($car['thumb']); ?>" alt="<?php echo htmlspecialchars($car['make'].' '.$car['model']); ?>" class="w-full h-full object-cover" />
              <?php else: ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400">No image</div>
              <?php endif; ?>
            </a>
            <div class="p-3">
              <div class="font-semibold truncate"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></div>
                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($car['year']); ?><?php if(!empty($car['variant'])): ?> · <?php echo htmlspecialchars($car['variant']); ?><?php endif; ?></div>
              <div class="text-red-600 font-bold">RM <?php echo number_format((float)$car['price'], 2); ?></div>
              <div class="mt-2 flex justify-between items-center">
                <a href="car_details_view.php?car_id=<?php echo (int)$car['car_id']; ?>" class="text-blue-600 hover:underline text-sm">View details</a>
                <a href="saved_search.php?unsave=<?php echo (int)$car['car_id']; ?>" class="text-gray-600 hover:text-red-600 text-sm">Unsave</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-gray-600">You have no saved cars yet.
        <a href="car_view.php" class="text-blue-600 hover:underline">Browse listings</a> and click Save to add cars here.
      </div>
    <?php endif; ?>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white p-4">
    <div class="container mx-auto text-center">
      <p>&copy; <?php echo date('Y'); ?> MyCar (FYP). All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
