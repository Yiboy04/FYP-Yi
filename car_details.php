<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}

if (!isset($_GET['car_id'])) {
    die("No car selected.");
}
$car_id = intval($_GET['car_id']);

// fetch car info (join seller if you like)
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die("Car not found.");

// fetch all images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=?");
$imgQ->bind_param("i", $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while ($row = $resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();

// fetch car_details (includes optional fields)
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param("i", $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();

// If no car_details row exists, insert one
if (!$car_details) {
    $ins = $mysqli->prepare("INSERT INTO car_details (car_id) VALUES (?)");
    $ins->bind_param("i", $car_id);
    $ins->execute();
    $ins->close();
    // Re-fetch after insert
  $details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
    $details->bind_param("i", $car_id);
    $details->execute();
    $car_details = $details->get_result()->fetch_assoc();
    $details->close();
}

// Fetch car_more_detail (additional optional fields)
$moreStmt = $mysqli->prepare("SELECT speaker_brand, speaker_quantity, length_mm, height_mm, width_mm, wheel_base_mm, turning_circle, fuel_consumption, front_suspension, rear_suspension, driver_assistance, zero_to_hundred_s, top_speed_kmh, heated_seat, cooling_seat, other_features FROM car_more_detail WHERE car_id=?");
$moreStmt->bind_param("i", $car_id);
$moreStmt->execute();
$car_more = $moreStmt->get_result()->fetch_assoc();
$moreStmt->close();

// If no car_more_detail row exists, insert one
if (!$car_more) {
  $insMore = $mysqli->prepare("INSERT INTO car_more_detail (car_id) VALUES (?)");
  $insMore->bind_param("i", $car_id);
  $insMore->execute();
  $insMore->close();
  // Re-fetch after insert
  $moreStmt = $mysqli->prepare("SELECT speaker_brand, speaker_quantity, length_mm, height_mm, width_mm, wheel_base_mm, turning_circle, fuel_consumption, front_suspension, rear_suspension, driver_assistance, zero_to_hundred_s, top_speed_kmh, heated_seat, cooling_seat, other_features FROM car_more_detail WHERE car_id=?");
  $moreStmt->bind_param("i", $car_id);
  $moreStmt->execute();
  $car_more = $moreStmt->get_result()->fetch_assoc();
  $moreStmt->close();
}

// ===== Booking feature for seller notifications =====
// Ensure bookings table exists (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  buyer_id INT NOT NULL,
  seller_id INT NOT NULL,
  status ENUM('pending','accepted','rejected','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  decision_at TIMESTAMP NULL DEFAULT NULL,
  INDEX idx_booking_car_status (car_id, status),
  CONSTRAINT fk_b_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE,
  CONSTRAINT fk_b_buyer FOREIGN KEY (buyer_id) REFERENCES buyers(id) ON DELETE CASCADE,
  CONSTRAINT fk_b_seller FOREIGN KEY (seller_id) REFERENCES sellers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Ensure car_more_detail table exists (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS car_more_detail (
  car_id INT NOT NULL PRIMARY KEY,
  speaker_brand VARCHAR(255) NULL,
  speaker_quantity INT NULL,
  length_mm INT NULL,
  height_mm INT NULL,
  width_mm INT NULL,
  wheel_base_mm INT NULL,
  turning_circle VARCHAR(50) NULL,
  fuel_consumption DECIMAL(5,2) NULL,
  front_suspension VARCHAR(255) NULL,
  rear_suspension VARCHAR(255) NULL,
  driver_assistance TEXT NULL,
  zero_to_hundred_s DECIMAL(5,2) NULL,
  top_speed_kmh INT NULL,
  heated_seat TINYINT(1) NULL,
  cooling_seat TINYINT(1) NULL,
  other_features TEXT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cmd_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Add zero_to_hundred_s column if table already exists (idempotent)
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS zero_to_hundred_s DECIMAL(5,2) NULL AFTER driver_assistance");
// Add top_speed_kmh column if missing
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS top_speed_kmh INT NULL AFTER zero_to_hundred_s");
// Add other_features column if missing
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS other_features TEXT NULL AFTER cooling_seat");

// Identify logged-in seller
$seller_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$ownsCar = ($seller_id > 0 && isset($car['seller_id']) && intval($car['seller_id']) === $seller_id);

// Handle accept/reject from this page (seller only)
if ($ownsCar && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_action'], $_POST['booking_id'])) {
  $action = $_POST['booking_action'];
  $booking_id = intval($_POST['booking_id']);
  if ($action === 'accept') {
    $mysqli->begin_transaction();
    if ($st = $mysqli->prepare("UPDATE bookings SET status='accepted', decision_at=NOW() WHERE booking_id=? AND seller_id=? AND status='pending'")) {
      $st->bind_param('ii', $booking_id, $seller_id);
      $st->execute();
      $st->close();
    }
    if ($st2 = $mysqli->prepare("UPDATE cars SET listing_status='negotiating' WHERE car_id=? AND seller_id=?")) {
      $st2->bind_param('ii', $car_id, $seller_id);
      $st2->execute();
      $st2->close();
    }
    $mysqli->commit();
  } elseif ($action === 'reject') {
    if ($st = $mysqli->prepare("UPDATE bookings SET status='rejected', decision_at=NOW() WHERE booking_id=? AND seller_id=? AND status='pending'")) {
      $st->bind_param('ii', $booking_id, $seller_id);
      $st->execute();
      $st->close();
    }
  }
  header("Location: car_details.php?car_id=".$car_id);
  exit();
}

// Load pending bookings list for this car (show multiple buyers)
$pendingBookings = [];
if ($ownsCar) {
  $q = $mysqli->prepare("SELECT b.booking_id, b.buyer_id, b.created_at, u.name, u.email, u.phone
               FROM bookings b JOIN buyers u ON u.id=b.buyer_id
               WHERE b.car_id=? AND b.status='pending' ORDER BY b.created_at ASC");
  $q->bind_param('i', $car_id);
  $q->execute();
  $res = $q->get_result();
  while ($row = $res->fetch_assoc()) { $pendingBookings[] = $row; }
  $q->close();
}

// handle car_details update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $color = $mysqli->real_escape_string($_POST['color']);
    $horsepower = intval($_POST['horsepower']);
    $engine_code = $mysqli->real_escape_string($_POST['engine_code']);
    $gear_numbers = intval($_POST['gear_numbers']);
    $front_wheel_size = $mysqli->real_escape_string($_POST['front_wheel_size']);
    $rear_wheel_size = $mysqli->real_escape_string($_POST['rear_wheel_size']);
    $torque = $mysqli->real_escape_string($_POST['torque']);
    // Validate condition (nullable)
    $allowed_conditions = ['New','Reconditioned','Used','Certified'];
    $car_condition = isset($_POST['car_condition']) && in_array($_POST['car_condition'], $allowed_conditions)
      ? $_POST['car_condition']
      : null;
    if ($car_condition !== null) {
      $car_condition = $mysqli->real_escape_string($car_condition);
    }
    // Validate car type (nullable)
    $allowed_types = ['Sedan','SUV','Pickup','Coupe','Hatchback','Wagon','Convertible','Van','MPV','Crossover','Sports','Other'];
    $car_type = isset($_POST['car_type']) && in_array($_POST['car_type'], $allowed_types) ? $_POST['car_type'] : null;
    if ($car_type !== null) {
      $car_type = $mysqli->real_escape_string($car_type);
    }

    $stmt = $mysqli->prepare("UPDATE car_details SET color=?, horsepower=?, engine_code=?, gear_numbers=?, front_wheel_size=?, rear_wheel_size=?, torque=?, car_type=?, car_condition=? WHERE car_id=?");
    $stmt->bind_param("sisisssssi", $color, $horsepower, $engine_code, $gear_numbers, $front_wheel_size, $rear_wheel_size, $torque, $car_type, $car_condition, $car_id);
    $stmt->execute();
    $stmt->close();
    header("Location: car_details.php?car_id=$car_id");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
    $seller_note = $mysqli->real_escape_string($_POST['seller_note']);
    $stmt = $mysqli->prepare("UPDATE car_details SET seller_note=? WHERE car_id=?");
    $stmt->bind_param("si", $seller_note, $car_id);
    $stmt->execute();
    $stmt->close();
    header("Location: car_details.php?car_id=$car_id");
    exit();
}

// handle car_more_detail update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_more_detail'])) {
    // Helper to normalize empty string to null
    function null_if_empty($v) { return isset($v) && trim((string)$v) !== '' ? $v : null; }

    $speaker_brand = null_if_empty($_POST['speaker_brand'] ?? null);
    $speaker_quantity = null_if_empty($_POST['speaker_quantity'] ?? null);
    $length_mm = null_if_empty($_POST['length_mm'] ?? null);
    $height_mm = null_if_empty($_POST['height_mm'] ?? null);
    $width_mm = null_if_empty($_POST['width_mm'] ?? null);
    $wheel_base_mm = null_if_empty($_POST['wheel_base_mm'] ?? null);
    $turning_circle = null_if_empty($_POST['turning_circle'] ?? null);
    $fuel_consumption = null_if_empty($_POST['fuel_consumption'] ?? null);
    $front_suspension = null_if_empty($_POST['front_suspension'] ?? null);
    $rear_suspension = null_if_empty($_POST['rear_suspension'] ?? null);
  $driver_assistance = null_if_empty($_POST['driver_assistance'] ?? null);
  $zero_to_hundred_s = null_if_empty($_POST['zero_to_hundred_s'] ?? null);
  $top_speed_kmh = null_if_empty($_POST['top_speed_kmh'] ?? null);
  $other_features = null_if_empty($_POST['other_features'] ?? null);
    // Checkboxes: set 1 if on, 0 if present and off? We'll set null if not set
    $heated_seat = isset($_POST['heated_seat']) ? 1 : (isset($_POST['heated_seat_present']) ? 0 : null);
    $cooling_seat = isset($_POST['cooling_seat']) ? 1 : (isset($_POST['cooling_seat_present']) ? 0 : null);

    // Cast numeric types appropriately (null remains null)
    $speaker_quantity = is_null($speaker_quantity) ? null : intval($speaker_quantity);
    $length_mm = is_null($length_mm) ? null : intval($length_mm);
    $height_mm = is_null($height_mm) ? null : intval($height_mm);
    $width_mm = is_null($width_mm) ? null : intval($width_mm);
    $wheel_base_mm = is_null($wheel_base_mm) ? null : intval($wheel_base_mm);
    $fuel_consumption = is_null($fuel_consumption) ? null : floatval($fuel_consumption);

    $stmt = $mysqli->prepare("UPDATE car_more_detail SET speaker_brand=?, speaker_quantity=?, length_mm=?, height_mm=?, width_mm=?, wheel_base_mm=?, turning_circle=?, fuel_consumption=?, front_suspension=?, rear_suspension=?, driver_assistance=?, zero_to_hundred_s=?, top_speed_kmh=?, heated_seat=?, cooling_seat=?, other_features=? WHERE car_id=?");
    $types = "siiiiisdsssdiiisi"; // s i i i i i s d s s s d i i i s i
    $stmt->bind_param($types,
      $speaker_brand, $speaker_quantity, $length_mm, $height_mm, $width_mm, $wheel_base_mm,
      $turning_circle, $fuel_consumption, $front_suspension, $rear_suspension, $driver_assistance, $zero_to_hundred_s, $top_speed_kmh,
      $heated_seat, $cooling_seat, $other_features, $car_id);
    $stmt->execute();
    $stmt->close();
    header("Location: car_details.php?car_id=$car_id");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?> Details</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script>
function changeMain(src){
  document.getElementById('mainImage').src=src;
}
</script>
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Car Details</h1>
    <a href="seller_main.php" class="underline">Back</a>
  </div>
</header>

<main class="container mx-auto mt-8">
  <div class="bg-white rounded shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left: images -->
      <div>
        <?php if(count($imgs)>0): ?>
          <img id="mainImage" src="<?php echo htmlspecialchars($imgs[0]); ?>" alt="Main Image" class="w-full h-80 object-cover rounded">
          <div class="flex gap-2 mt-2 overflow-x-auto">
            <?php foreach($imgs as $img): ?>
              <img onclick="changeMain('<?php echo htmlspecialchars($img); ?>')" src="<?php echo htmlspecialchars($img); ?>" class="w-20 h-20 object-cover rounded cursor-pointer border">
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400 rounded">No images</div>
        <?php endif; ?>
        <form method="post" class="mt-6">
          <label class="block font-semibold mb-2 text-blue-600" for="seller_note">Seller's Note</label>
          <textarea name="seller_note" id="seller_note" placeholder="Seller's Note" class="border p-3 rounded w-full h-32 resize-none mb-2"><?php echo htmlspecialchars($car_details['seller_note'] ?? ''); ?></textarea>
          <div class="flex justify-end">
            <button type="submit" name="update_note" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Note</button>
          </div>
        </form>
        <!-- 3D View manage button for seller -->
        <div class="mt-3">
          <a href="car_360_manage.php?car_id=<?php echo (int)$car_id; ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold">Manage 3D View</a>
        </div>
      </div>
      <!-- Right: details -->
      <div>
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h2>
        <div class="text-red-600 text-xl font-bold mb-4">RM<?php echo number_format($car['price'],2); ?></div>
        <?php if ($ownsCar): ?>
        <!-- Booking requests box -->
        <div class="bg-white border border-yellow-300 rounded-lg p-4 mb-4">
          <h3 class="text-lg font-semibold text-yellow-700 mb-2">Booking Requests</h3>
          <?php if (!empty($pendingBookings)): ?>
            <div class="space-y-3">
              <?php foreach($pendingBookings as $bk): ?>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 p-3 bg-yellow-50 rounded">
                  <div class="text-sm">
                    <div class="font-semibold">Buyer: <?php echo htmlspecialchars($bk['name']); ?></div>
                    <div class="text-gray-700">Email: <?php echo htmlspecialchars($bk['email'] ?? ''); ?><?php if(!empty($bk['phone'])): ?> • Phone: <?php echo htmlspecialchars($bk['phone']); ?><?php endif; ?></div>
                    <div class="text-gray-500 text-xs">Requested: <?php echo htmlspecialchars($bk['created_at']); ?></div>
                  </div>
                  <div class="flex gap-2">
                    <form method="post">
                      <input type="hidden" name="booking_action" value="accept">
                      <input type="hidden" name="booking_id" value="<?php echo (int)$bk['booking_id']; ?>">
                      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Accept</button>
                    </form>
                    <form method="post">
                      <input type="hidden" name="booking_action" value="reject">
                      <input type="hidden" name="booking_id" value="<?php echo (int)$bk['booking_id']; ?>">
                      <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">Reject</button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="text-sm text-gray-600">No pending bookings.</div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-red-600">Overview</h3>
          <div class="grid grid-cols-2 gap-x-6 gap-y-2">
            <div><span class="font-semibold">Year:</span> <?php echo htmlspecialchars($car['year']); ?></div>
            <div><span class="font-semibold">Variant:</span> <?php echo htmlspecialchars($car['variant']); ?></div>
            <div><span class="font-semibold">Mileage:</span> <?php echo htmlspecialchars($car['mileage']); ?> km</div>
            <div><span class="font-semibold">Transmission:</span> <?php echo htmlspecialchars($car['transmission']); ?></div>
            <div><span class="font-semibold">Engine Capacity:</span> <?php echo htmlspecialchars($car['engine_capacity']); ?> L</div>
            <div><span class="font-semibold">Fuel:</span> <?php echo htmlspecialchars($car['fuel']); ?></div>
            <div><span class="font-semibold">Drive System:</span> <?php echo htmlspecialchars($car['drive_system']); ?></div>
            <div><span class="font-semibold">Doors:</span> <?php echo htmlspecialchars($car['doors']); ?>D</div>
                <?php if (!empty($car_details['car_condition'])): ?>
                  <div><span class="font-semibold">Condition:</span> <?php echo htmlspecialchars($car_details['car_condition']); ?></div>
                <?php endif; ?>
          </div>
        </div>
        <!-- Seller guidance notice -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 mb-4 rounded" role="alert">
          <div class="font-semibold mb-1">Tip for better visibility</div>
          <p class="text-sm">Car details can be left blank, but completing them (for example Colour, Condition, Wheel Size, etc.) helps buyers find your listing using filters and improves trust in your ad.</p>
        </div>

        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-blue-600">Car Details</h3>
          <form method="post" class="grid grid-cols-2 gap-4">
            <input type="text" name="color" value="<?php echo htmlspecialchars($car_details['color'] ?? ''); ?>" placeholder="Color" class="border p-2 rounded">
            <input type="number" name="horsepower" value="<?php echo htmlspecialchars($car_details['horsepower'] ?? ''); ?>" placeholder="Horsepower" class="border p-2 rounded">
            <input type="text" name="engine_code" value="<?php echo htmlspecialchars($car_details['engine_code'] ?? ''); ?>" placeholder="Engine Code" class="border p-2 rounded">
            <input type="number" name="gear_numbers" value="<?php echo htmlspecialchars($car_details['gear_numbers'] ?? ''); ?>" placeholder="Gear Numbers" class="border p-2 rounded">
            <input type="text" name="front_wheel_size" value="<?php echo htmlspecialchars($car_details['front_wheel_size'] ?? ''); ?>" placeholder="Front Wheel Size" class="border p-2 rounded">
            <input type="text" name="rear_wheel_size" value="<?php echo htmlspecialchars($car_details['rear_wheel_size'] ?? ''); ?>" placeholder="Rear Wheel Size" class="border p-2 rounded">
            <input type="text" name="torque" value="<?php echo htmlspecialchars($car_details['torque'] ?? ''); ?>" placeholder="Torque (e.g. 250 Nm)" class="border p-2 rounded">
            <select name="car_type" class="border p-2 rounded">
              <?php $type = $car_details['car_type'] ?? ''; ?>
              <option value="" <?php echo $type==='' ? 'selected' : ''; ?>>Car Type (optional)</option>
              <option value="Sedan" <?php echo $type==='Sedan' ? 'selected' : ''; ?>>Sedan</option>
              <option value="SUV" <?php echo $type==='SUV' ? 'selected' : ''; ?>>SUV</option>
              <option value="Pickup" <?php echo $type==='Pickup' ? 'selected' : ''; ?>>Pickup</option>
              <option value="Coupe" <?php echo $type==='Coupe' ? 'selected' : ''; ?>>Coupe</option>
              <option value="Hatchback" <?php echo $type==='Hatchback' ? 'selected' : ''; ?>>Hatchback</option>
              <option value="Wagon" <?php echo $type==='Wagon' ? 'selected' : ''; ?>>Wagon</option>
              <option value="Convertible" <?php echo $type==='Convertible' ? 'selected' : ''; ?>>Convertible</option>
              <option value="Van" <?php echo $type==='Van' ? 'selected' : ''; ?>>Van</option>
              <option value="MPV" <?php echo $type==='MPV' ? 'selected' : ''; ?>>MPV</option>
              <option value="Crossover" <?php echo $type==='Crossover' ? 'selected' : ''; ?>>Crossover</option>
              <option value="Sports" <?php echo $type==='Sports' ? 'selected' : ''; ?>>Sports</option>
              <option value="Other" <?php echo $type==='Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <select name="car_condition" class="border p-2 rounded">
              <?php $cond = $car_details['car_condition'] ?? ''; ?>
              <option value="" <?php echo $cond==='' ? 'selected' : ''; ?>>Condition (optional)</option>
              <option value="New" <?php echo $cond==='New' ? 'selected' : ''; ?>>New</option>
              <option value="Reconditioned" <?php echo $cond==='Reconditioned' ? 'selected' : ''; ?>>Reconditioned</option>
              <option value="Used" <?php echo $cond==='Used' ? 'selected' : ''; ?>>Used</option>
              <option value="Certified" <?php echo $cond==='Certified' ? 'selected' : ''; ?>>Certified</option>
            </select>
            <div class="col-span-2 flex justify-end gap-2">
              <button type="button" id="openMoreDetails" class="bg-white border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-50">More Details</button>
              <button type="submit" name="update_details" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Details</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- More Details Slide-over (Right) -->
<div id="moreDetailsModal" class="hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
  <!-- Backdrop -->
  <div id="moreDetailsBackdrop" class="absolute inset-0 bg-black bg-opacity-50 opacity-0 transition-opacity duration-300"></div>
  <!-- Right panel container -->
  <div class="absolute inset-y-0 right-0 max-w-full flex">
    <!-- Panel -->
    <div id="moreDetailsPanel" class="w-screen max-w-xl transform translate-x-full transition-transform duration-300 ease-out">
      <div class="h-full flex flex-col bg-white shadow-xl">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h3 class="text-xl font-semibold">More Details</h3>
          <button id="closeMoreDetails" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
        </div>
        <div class="p-5 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 7rem);">
          <h4 class="text-xl font-semibold">Car features and spec</h4>
          <?php if ($ownsCar): ?>
          <!-- Edit Additional Specs Form -->
          <div class="border rounded p-4 bg-gray-50">
            <h5 class="text-lg font-semibold mb-3">Edit additional specs</h5>
            <form method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <input type="hidden" name="update_more_detail" value="1">
              <div>
                <label class="block text-sm font-medium mb-1">Speaker Brand</label>
                <input type="text" name="speaker_brand" value="<?php echo htmlspecialchars($car_more['speaker_brand'] ?? ''); ?>" class="w-full border rounded px-3 py-2" placeholder="e.g. Bose, Burmester">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Speaker Quantity</label>
                <input type="number" name="speaker_quantity" value="<?php echo htmlspecialchars($car_more['speaker_quantity'] ?? ''); ?>" class="w-full border rounded px-3 py-2" min="0">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Length (mm)</label>
                <input type="number" name="length_mm" value="<?php echo htmlspecialchars($car_more['length_mm'] ?? ''); ?>" class="w-full border rounded px-3 py-2" min="0">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Height (mm)</label>
                <input type="number" name="height_mm" value="<?php echo htmlspecialchars($car_more['height_mm'] ?? ''); ?>" class="w-full border rounded px-3 py-2" min="0">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Width (mm)</label>
                <input type="number" name="width_mm" value="<?php echo htmlspecialchars($car_more['width_mm'] ?? ''); ?>" class="w-full border rounded px-3 py-2" min="0">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Wheel Base (mm)</label>
                <input type="number" name="wheel_base_mm" value="<?php echo htmlspecialchars($car_more['wheel_base_mm'] ?? ''); ?>" class="w-full border rounded px-3 py-2" min="0">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Turning Circle</label>
                <input type="text" name="turning_circle" value="<?php echo htmlspecialchars($car_more['turning_circle'] ?? ''); ?>" class="w-full border rounded px-3 py-2" placeholder="e.g. 10.5 m">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Fuel Consumption (L/100km)</label>
                <input type="number" step="0.1" min="0" name="fuel_consumption" value="<?php echo htmlspecialchars($car_more['fuel_consumption'] ?? ''); ?>" class="w-full border rounded px-3 py-2">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Front Suspension</label>
                <input type="text" name="front_suspension" value="<?php echo htmlspecialchars($car_more['front_suspension'] ?? ''); ?>" class="w-full border rounded px-3 py-2" placeholder="e.g. MacPherson strut">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Rear Suspension</label>
                <input type="text" name="rear_suspension" value="<?php echo htmlspecialchars($car_more['rear_suspension'] ?? ''); ?>" class="w-full border rounded px-3 py-2" placeholder="e.g. Multi-link">
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Driver Assistance</label>
                <textarea name="driver_assistance" class="w-full border rounded px-3 py-2 h-24" placeholder="Enter items separated by commas or new lines&#10;e.g. ACC, Lane Assist, Blind Spot, AEB"><?php echo htmlspecialchars($car_more['driver_assistance'] ?? ''); ?></textarea>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">0-100 km/h (s)</label>
                <input type="number" step="0.01" min="0" name="zero_to_hundred_s" value="<?php echo htmlspecialchars($car_more['zero_to_hundred_s'] ?? ''); ?>" class="w-full border rounded px-3 py-2">
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Top Speed (km/h)</label>
                <input type="number" step="1" min="0" name="top_speed_kmh" value="<?php echo htmlspecialchars($car_more['top_speed_kmh'] ?? ''); ?>" class="w-full border rounded px-3 py-2">
              </div>
              <div class="flex items-center gap-6 sm:col-span-2">
                <label class="inline-flex items-center gap-2"><input type="hidden" name="heated_seat_present" value="1"><input type="checkbox" name="heated_seat" <?php echo (isset($car_more['heated_seat']) && (int)$car_more['heated_seat']===1)?'checked':''; ?> class="h-4 w-4"> <span class="text-sm">Heated Seat</span></label>
                <label class="inline-flex items-center gap-2"><input type="hidden" name="cooling_seat_present" value="1"><input type="checkbox" name="cooling_seat" <?php echo (isset($car_more['cooling_seat']) && (int)$car_more['cooling_seat']===1)?'checked':''; ?> class="h-4 w-4"> <span class="text-sm">Cooling Seat</span></label>
              </div>
              <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1">Other features</label>
                <textarea name="other_features" class="w-full border rounded px-3 py-2 h-24" placeholder="Comma or new line separated items&#10;e.g. Sunroof, Ambient lighting, Wireless charger"><?php echo htmlspecialchars($car_more['other_features'] ?? ''); ?></textarea>
              </div>
              <div class="sm:col-span-2 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Additional Specs</button>
              </div>
            </form>
          </div>
          <?php endif; ?>
          <!-- Search -->
          <div>
            <label for="featureSearch" class="sr-only">Search features</label>
            <div class="relative">
              <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
              <input id="featureSearch" type="text" placeholder="Search for features and spec" class="w-full border rounded pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>
          <div>
            <h5 class="text-lg font-semibold mb-2">All features</h5>
            <?php
              $fmtPrice = isset($car['price']) ? 'RM'.number_format($car['price'], 2) : null;
              $sec_basic = [
                'Make / Model' => trim(($car['make'] ?? '').' '.($car['model'] ?? '')),
                'Variant' => $car['variant'] ?? null,
                'Year' => $car['year'] ?? null,
                'Price' => $fmtPrice,
                'Mileage' => isset($car['mileage']) ? $car['mileage'].' km' : null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
                'Fuel' => $car['fuel'] ?? null,
                'Drive System' => $car['drive_system'] ?? null,
                'Doors' => isset($car['doors']) ? $car['doors'].'D' : null,
                'Condition' => $car_details['car_condition'] ?? null,
                'Type' => $car_details['car_type'] ?? null,
              ];
              $sec_perf = [
                'Horsepower' => $car_details['horsepower'] ?? null,
                'Torque' => $car_details['torque'] ?? null,
                '0-100 km/h (s)' => $car_more['zero_to_hundred_s'] ?? null,
                'Engine Code' => $car_details['engine_code'] ?? null,
                'Gear Numbers' => $car_details['gear_numbers'] ?? null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
              ];
              $sec_wheels = [
                'Front Wheel Size' => $car_details['front_wheel_size'] ?? null,
                'Rear Wheel Size' => $car_details['rear_wheel_size'] ?? null,
              ];
              // Additional sections from car_more_detail
              $sec_audio = [
                'Speaker Brand' => $car_more['speaker_brand'] ?? null,
                'Speaker Quantity' => isset($car_more['speaker_quantity']) ? $car_more['speaker_quantity'] : null,
              ];
              // Drivers assistance as bullet list (comma or newline separated)
              $sec_drivers = [];
              if (!empty($car_more['driver_assistance'])) {
                $items = preg_split("/(\r?\n)|,\s*/", $car_more['driver_assistance']);
                foreach ($items as $it) { $it = trim((string)$it); if ($it !== '') { $sec_drivers[] = $it; } }
              }
              $sec_interior = [
                'Heated Seat' => (isset($car_more['heated_seat']) && (int)$car_more['heated_seat'] === 1) ? 'Yes' : null,
                'Cooling Seat' => (isset($car_more['cooling_seat']) && (int)$car_more['cooling_seat'] === 1) ? 'Yes' : null,
              ];
              $sec_exterior = [];
              $sec_illumination = [];
              $sec_paint = [];
              // Other features as bullet list
              $sec_other = [];
              if (!empty($car_more['other_features'])) {
                $oitems = preg_split("/(\r?\n)|,\s*/", $car_more['other_features']);
                foreach ($oitems as $it) { $it = trim((string)$it); if ($it !== '') { $sec_other[] = $it; } }
              }
              $sec_dimensions = [
                'Length (mm)' => $car_more['length_mm'] ?? null,
                'Width (mm)' => $car_more['width_mm'] ?? null,
                'Height (mm)' => $car_more['height_mm'] ?? null,
                'Wheel Base (mm)' => $car_more['wheel_base_mm'] ?? null,
                'Turning Circle' => $car_more['turning_circle'] ?? null,
              ];
              $sec_fuel = [
                'Fuel Consumption (L/100km)' => $car_more['fuel_consumption'] ?? null,
              ];
              $sec_suspension = [
                'Front Suspension' => $car_more['front_suspension'] ?? null,
                'Rear Suspension' => $car_more['rear_suspension'] ?? null,
              ];

              $sections = [
                'Audio and Communications' => $sec_audio,
                'Drivers Assistance' => $sec_drivers,
                'Dimensions' => $sec_dimensions,
                'Fuel Economy' => $sec_fuel,
                'Suspension' => $sec_suspension,
                'Interior' => $sec_interior,
                'Other' => $sec_other,
                'Performance' => $sec_perf,
                'Wheels & Tyres' => $sec_wheels,
                'Basic Specs' => $sec_basic,
              ];
            ?>
            <div id="featureAccordion" class="divide-y border rounded">
              <?php $idx=0; foreach ($sections as $secName => $items): $idx++; $clean = array_filter($items, function($v){ return !is_null($v) && $v !== ''; }); $cnt = count($clean); $open = ($secName === 'Basic Specs'); ?>
                <div class="acc-section" data-acc-section>
                  <button type="button" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 focus:outline-none acc-toggle" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
                    <div class="flex items-center gap-3">
                      <span class="font-semibold"><?php echo htmlspecialchars($secName); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="inline-flex items-center justify-center text-xs font-semibold text-gray-700 bg-gray-100 rounded px-2 py-0.5 acc-count"><?php echo (int)$cnt; ?></span>
                      <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200 acc-caret <?php echo $open ? 'rotate-180' : ''; ?>" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </div>
                  </button>
                  <div class="acc-panel <?php echo $open ? '' : 'hidden'; ?> px-6 pb-4">
                    <?php if ($cnt > 0): ?>
                      <?php $isBullets = array_values($clean) === $clean; ?>
                      <ul class="list-disc pl-5 space-y-1 acc-list">
                        <?php if ($isBullets): ?>
                          <?php foreach ($clean as $text): ?>
                            <li class="text-sm text-gray-700"><span class="acc-item-text"><?php echo htmlspecialchars((string)$text); ?></span></li>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <?php foreach ($clean as $label => $val): ?>
                            <li class="text-sm text-gray-700"><span class="font-medium"><?php echo htmlspecialchars($label); ?>:</span> <span class="acc-item-text"><?php echo htmlspecialchars((string)$val); ?></span></li>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </ul>
                    <?php else: ?>
                      <div class="text-sm text-gray-500 italic acc-empty">No items yet.</div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Seller Note -->
          <?php $note = trim((string)($car_details['seller_note'] ?? '')); ?>
          <?php if ($note !== ''): ?>
          <section>
            <h4 class="text-lg font-semibold text-gray-800 mb-2">Seller's Note</h4>
            <p class="text-sm text-gray-700 whitespace-pre-line"><?php echo htmlspecialchars($note); ?></p>
          </section>
          <?php endif; ?>
        </div>
        <div class="px-5 py-3 border-t flex justify-end">
          <button id="closeMoreDetailsBottom" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function(){
    var openBtn = document.getElementById('openMoreDetails');
    var modal = document.getElementById('moreDetailsModal');
    var closeTop = document.getElementById('closeMoreDetails');
    var closeBottom = document.getElementById('closeMoreDetailsBottom');
    var backdrop = document.getElementById('moreDetailsBackdrop');
    var panel = document.getElementById('moreDetailsPanel');
  // Accordion + search
  var accRoot = document.getElementById('featureAccordion');
  var searchInput = document.getElementById('featureSearch');

    function openModal(){
      if(!modal) return;
      modal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      // Animate in on next frame
      requestAnimationFrame(function(){
        if (backdrop) backdrop.classList.add('opacity-100');
        if (panel) panel.classList.remove('translate-x-full');
        if (closeTop) closeTop.focus();
      });
    }
    function closeModal(){
      if(!modal) return;
      if (backdrop) backdrop.classList.remove('opacity-100'); // fades to 0
      if (panel) panel.classList.add('translate-x-full'); // slides out
      // After panel transition end, hide the modal
      var onEnd = function(e){
        if (e.target === panel) {
          modal.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
          panel.removeEventListener('transitionend', onEnd);
        }
      };
      if (panel) panel.addEventListener('transitionend', onEnd);
    }
    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeTop) closeTop.addEventListener('click', closeModal);
    if (closeBottom) closeBottom.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ closeModal(); }});

    // Accordion toggle handler
    if (accRoot){
      accRoot.addEventListener('click', function(e){
        var btn = e.target.closest('.acc-toggle');
        if (!btn) return;
        var section = btn.closest('[data-acc-section]');
        var panel = section.querySelector('.acc-panel');
        var caret = section.querySelector('.acc-caret');
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        if (expanded){
          panel.classList.add('hidden');
          caret && caret.classList.remove('rotate-180');
          btn.setAttribute('aria-expanded','false');
        } else {
          panel.classList.remove('hidden');
          caret && caret.classList.add('rotate-180');
          btn.setAttribute('aria-expanded','true');
        }
      });
    }

    // Search filtering across sections
    function normalize(s){ return (s||'').toString().toLowerCase(); }
    function updateCounts(section){
      var countEl = section.querySelector('.acc-count');
      var list = section.querySelectorAll('.acc-list > li');
      var emptyEl = section.querySelector('.acc-empty');
      var visible = 0;
      list.forEach(function(li){ if (!li.classList.contains('hidden')) visible++; });
      if (countEl) countEl.textContent = visible;
      if (emptyEl){ emptyEl.classList.toggle('hidden', visible !== 0); }
    }
    function applySearch(term){
      var q = normalize(term);
      var sections = accRoot ? accRoot.querySelectorAll('[data-acc-section]') : [];
      sections.forEach(function(sec){
        var items = sec.querySelectorAll('.acc-list > li');
        var any = 0;
        items.forEach(function(li){
          var text = normalize(li.textContent);
          var show = q === '' ? true : text.indexOf(q) !== -1;
          li.classList.toggle('hidden', !show);
          if (show) any++;
        });
        // If searching, auto open sections with matches, close others
        var btn = sec.querySelector('.acc-toggle');
        var panelEl = sec.querySelector('.acc-panel');
        var caret = sec.querySelector('.acc-caret');
        if (q !== ''){
          if (any > 0){
            panelEl.classList.remove('hidden');
            btn && btn.setAttribute('aria-expanded','true');
            caret && caret.classList.add('rotate-180');
          } else {
            panelEl.classList.add('hidden');
            btn && btn.setAttribute('aria-expanded','false');
            caret && caret.classList.remove('rotate-180');
          }
        } else {
          // When clearing, keep current open/closed state (don't change)
        }
        updateCounts(sec);
      });
    }
    if (searchInput){
      searchInput.addEventListener('input', function(){ applySearch(searchInput.value); });
    }
  })();
</script>
</body>
</html>
