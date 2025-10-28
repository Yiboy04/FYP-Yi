<?php
// car_details_view.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}
if (!isset($_GET['car_id'])) {
    die("No car selected.");
}
$car_id = intval($_GET['car_id']);
// fetch car info
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die("Car not found.");
// track recently viewed cars in session (latest first, max 5)
if (!isset($_SESSION['recently_viewed'])) $_SESSION['recently_viewed'] = [];
// remove if exists to reinsert at front
$_SESSION['recently_viewed'] = array_values(array_filter(
  $_SESSION['recently_viewed'],
  function($id) use ($car_id){ return intval($id) !== $car_id; }
));
array_unshift($_SESSION['recently_viewed'], $car_id);
if (count($_SESSION['recently_viewed']) > 5) {
  $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 5);
}
// fetch seller info (by seller_id on car)
$seller = null;
if (!empty($car['seller_id'])) {
  $s = $mysqli->prepare("SELECT id, name, email, phone FROM sellers WHERE id=?");
  $s->bind_param("i", $car['seller_id']);
  $s->execute();
  $seller = $s->get_result()->fetch_assoc();
  $s->close();
}
// Build WhatsApp link from seller phone (assumes MY country code 60 for local numbers starting with 0)
$waPhoneLink = null;
if (!empty($seller['phone'])) {
  $digits = preg_replace('/\D+/', '', (string)$seller['phone']);
  if ($digits !== '') {
    if (strpos($digits, '0') === 0) {
      // If number starts with 0 and no country code, assume Malaysia (60). Adjust if needed.
      $digits = '60' . ltrim($digits, '0');
    }
    $waText = rawurlencode("Hi, I'm interested in your {$car['make']} {$car['model']}.");
    $waPhoneLink = "https://wa.me/{$digits}?text={$waText}";
  }
}
// fetch all images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=?");
$imgQ->bind_param("i", $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while ($row = $resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();
// fetch car_details (include new wheel sizes, torque, car_type, and condition)
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param("i", $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();

// Ensure car_more_detail exists and fetch additional specs (view-only)
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
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS zero_to_hundred_s DECIMAL(5,2) NULL AFTER driver_assistance");
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS top_speed_kmh INT NULL AFTER zero_to_hundred_s");
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS other_features TEXT NULL AFTER cooling_seat");

$moreStmt = $mysqli->prepare("SELECT speaker_brand, speaker_quantity, length_mm, height_mm, width_mm, wheel_base_mm, turning_circle, fuel_consumption, front_suspension, rear_suspension, driver_assistance, zero_to_hundred_s, top_speed_kmh, heated_seat, cooling_seat, other_features FROM car_more_detail WHERE car_id=?");
$moreStmt->bind_param("i", $car_id);
$moreStmt->execute();
$car_more = $moreStmt->get_result()->fetch_assoc();
$moreStmt->close();

// Check if this car has a 3D (360) view set in the same DB
$has360 = false;
if ($chk = $mysqli->prepare("SELECT 1 FROM car_360_set WHERE car_id=? LIMIT 1")) {
  $chk->bind_param('i', $car_id);
  if ($chk->execute()) {
    $chk->store_result();
    $has360 = $chk->num_rows > 0;
  }
  $chk->close();
}

// ===== Booking feature =====
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

// Handle buyer booking request
$bookingMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_car']) && !empty($_SESSION['user_id']) && !empty($_SESSION['role']) && $_SESSION['role']==='buyer') {
  $buyerId = intval($_SESSION['user_id']);
  // Allow booking only for open listings
  $isOpen = (!isset($car['listing_status']) || $car['listing_status']===null || $car['listing_status']==='open');
  if ($isOpen) {
    // Check if an active booking exists in context of current car status
    // Active means: pending while car is open, or accepted while car is negotiating
    $sqlActive = "SELECT b.booking_id FROM bookings b JOIN cars c ON c.car_id=b.car_id
                  WHERE b.car_id=? AND (
                    (b.status='pending' AND (c.listing_status IS NULL OR c.listing_status='open')) OR
                    (b.status='accepted' AND c.listing_status='negotiating')
                  ) LIMIT 1";
    if ($chk = $mysqli->prepare($sqlActive)) {
      $chk->bind_param('i', $car_id);
      $chk->execute();
      $exists = $chk->get_result()->fetch_assoc();
      $chk->close();
      if ($exists) {
        $bookingMsg = 'This car already has an active booking.';
      } else {
        if ($ins = $mysqli->prepare("INSERT INTO bookings (car_id, buyer_id, seller_id, status) VALUES (?,?,?, 'pending')")) {
          $sellerIdForCar = intval($car['seller_id'] ?? 0);
          $ins->bind_param('iii', $car_id, $buyerId, $sellerIdForCar);
          if ($ins->execute()) {
            $bookingMsg = 'Booking request sent to the seller.';
          } else {
            $bookingMsg = 'Failed to create booking.';
          }
          $ins->close();
        }
      }
    }
  } else {
    $bookingMsg = 'This listing is not open for booking.';
  }
}

// Compute current buyer's booking status for this car (if any)
$myBooking = null;
$hasActiveBooking = false;
if (!empty($_SESSION['user_id']) && !empty($_SESSION['role']) && $_SESSION['role']==='buyer') {
  $buyerId = intval($_SESSION['user_id']);
  if ($st = $mysqli->prepare("SELECT booking_id, status, created_at, decision_at FROM bookings WHERE car_id=? AND buyer_id=? ORDER BY booking_id DESC LIMIT 1")) {
    $st->bind_param('ii', $car_id, $buyerId);
    $st->execute();
    $myBooking = $st->get_result()->fetch_assoc();
    $st->close();
  }
}
// Check if any active booking exists (context-aware)
$sqlHas = "SELECT 1 FROM bookings b JOIN cars c ON c.car_id=b.car_id
           WHERE b.car_id=? AND (
             (b.status='pending' AND (c.listing_status IS NULL OR c.listing_status='open')) OR
             (b.status='accepted' AND c.listing_status='negotiating')
           ) LIMIT 1";
if ($st = $mysqli->prepare($sqlHas)) {
  $st->bind_param('i', $car_id);
  $st->execute();
  $st->store_result();
  $hasActiveBooking = $st->num_rows > 0;
  $st->close();
}

// Determine if the current user's last booking is active under current car status
$listingStatus = $car['listing_status'] ?? null;
$isOpen = (!isset($listingStatus) || $listingStatus===null || $listingStatus==='open');
$myBookingActive = false;
if ($myBooking) {
  if ($myBooking['status'] === 'pending' && $isOpen) {
    $myBookingActive = true;
  } elseif ($myBooking['status'] === 'accepted' && $listingStatus === 'negotiating') {
    $myBookingActive = true;
  }
}

// Allow buyer to cancel their pending booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking']) && !empty($_SESSION['user_id']) && !empty($_SESSION['role']) && $_SESSION['role']==='buyer') {
  $buyerId = intval($_SESSION['user_id']);
  $bookingId = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
  if ($bookingId > 0) {
    if ($st = $mysqli->prepare("UPDATE bookings SET status='cancelled', decision_at=NOW() WHERE booking_id=? AND buyer_id=? AND status='pending'")) {
      $st->bind_param('ii', $bookingId, $buyerId);
      if ($st->execute()) {
        $bookingMsg = 'Booking cancelled.';
      }
      $st->close();
    }
  }
  // refresh myBooking after cancellation
  if ($st = $mysqli->prepare("SELECT booking_id, status, created_at, decision_at FROM bookings WHERE car_id=? AND buyer_id=? ORDER BY booking_id DESC LIMIT 1")) {
    $st->bind_param('ii', $car_id, $buyerId);
    $st->execute();
    $myBooking = $st->get_result()->fetch_assoc();
    $st->close();
  }
}

// ===== Reports feature =====
// Ensure reports table exists (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  reporter_id INT NULL,
  reporter_role ENUM('buyer','seller','admin','guest') NULL,
  reasons TEXT NULL,
  details TEXT NULL,
  status ENUM('new','reviewed','dismissed','resolved') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reports_car (car_id),
  CONSTRAINT fk_reports_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$reportMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_report'])) {
  // require login (buyer or seller)
  if (empty($_SESSION['user_id']) || empty($_SESSION['role'])) {
    $reportMsg = 'Please log in to report this listing.';
  } else {
    $reasons = isset($_POST['reasons']) && is_array($_POST['reasons']) ? $_POST['reasons'] : [];
    $details = trim((string)($_POST['details'] ?? ''));
    // sanitize reasons to simple safe tokens/words
    $cleanReasons = [];
    foreach ($reasons as $r) {
      $r = preg_replace('/[^a-z0-9_\- ]/i', '', (string)$r);
      $r = trim($r);
      if ($r !== '') { $cleanReasons[] = $r; }
    }
    $reasonsStr = implode(', ', array_slice($cleanReasons, 0, 20));
    if ($reasonsStr === '' && $details === '') {
      $reportMsg = 'Please select at least one reason or provide details.';
    } else {
      $reporterId = intval($_SESSION['user_id']);
      $reporterRole = in_array($_SESSION['role'], ['buyer','seller','admin','guest'], true) ? $_SESSION['role'] : 'guest';
      if ($st = $mysqli->prepare("INSERT INTO reports (car_id, reporter_id, reporter_role, reasons, details) VALUES (?,?,?,?,?)")) {
        $st->bind_param('iisss', $car_id, $reporterId, $reporterRole, $reasonsStr, $details);
        if ($st->execute()) {
          $reportMsg = 'Thanks for your report. Our team will review it shortly.';
        } else {
          $reportMsg = 'Failed to submit report.';
        }
        $st->close();
      } else {
        $reportMsg = 'Failed to prepare report submission.';
      }
    }
  }
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
</head> <!-- HEADER -->
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
    <nav>
      <ul class="flex gap-6 items-center">
        <li>
          <a href="saved_search.php" class="inline-flex items-center" title="Saved Searches">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white hover:text-gray-200">
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
<main class="container mx-auto mt-8">
  <div class="mb-4">
    <a href="list_cars.php?make=<?php echo urlencode($car['make']); ?>&model=<?php echo urlencode($car['model']); ?>"
       class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
      <span>←</span>
      <span>Back to Listings</span>
    </a>
  </div>
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
        <div class="mt-6">
          <label class="block font-semibold mb-2 text-blue-600" for="seller_note">Seller's Note</label>
          <div class="border p-3 rounded w-full h-32 bg-gray-50 text-gray-700"><?php echo nl2br(htmlspecialchars($car_details['seller_note'] ?? '')); ?></div>
          <?php
          // Saved/Unsave button (buyers only) — ensure table exists and compute state
          if (!empty($_SESSION['user_id']) && !empty($_SESSION['role']) && $_SESSION['role'] === 'buyer') {
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
            $isSaved = false;
            if ($st = $mysqli->prepare('SELECT 1 FROM saved_cars WHERE buyer_id=? AND car_id=? LIMIT 1')) {
              $st->bind_param('ii', $buyerId, $car_id);
              $st->execute();
              $st->store_result();
              $isSaved = $st->num_rows > 0;
              $st->close();
            }
            $toggleUrl = $isSaved ? ('saved_search.php?unsave='.(int)$car_id) : ('saved_search.php?save='.(int)$car_id);
            $btnText = $isSaved ? 'Unsave' : 'Save';
            $btnClass = $isSaved ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700';
            echo '<div class="mt-3">';
            echo '<a href="'.htmlspecialchars($toggleUrl).'" class="inline-flex items-center '.$btnClass.' text-white px-4 py-2 rounded-lg font-semibold">'.$btnText.'</a>';
            echo '</div>';
          }
          ?>
          <!-- 3D View button (visible for all; half-transparent when not available) -->
          <div class="mt-3">
            <?php if ($has360): ?>
              <a href="car_view.php?car_id=<?php echo (int)$car_id; ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold">
                <span>3D View</span>
              </a>
            <?php else: ?>
              <a href="#" aria-disabled="true" title="3D view not available" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold opacity-50 cursor-not-allowed pointer-events-none">
                <span>3D View</span>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- Right: details -->
      <div>
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h2>
        <div class="text-red-600 text-xl font-bold mb-4">RM<?php echo number_format($car['price'],2); ?></div>
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
          </div>
        </div>
        <?php if (!empty($seller)): ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-green-700">Seller Information</h3>
          <div class="grid grid-cols-1 gap-2 text-gray-800">
            <div><span class="font-semibold">Name:</span> <?php echo htmlspecialchars($seller['name'] ?? ''); ?></div>
            <?php if (!empty($seller['phone'])): ?>
              <div><span class="font-semibold">Phone:</span> <a class="text-blue-600 hover:underline" href="tel:<?php echo htmlspecialchars($seller['phone']); ?>"><?php echo htmlspecialchars($seller['phone']); ?></a></div>
            <?php endif; ?>
            <?php if (!empty($seller['email'])): ?>
              <div><span class="font-semibold">Email:</span> <a class="text-blue-600 hover:underline" href="mailto:<?php echo htmlspecialchars($seller['email']); ?>"><?php echo htmlspecialchars($seller['email']); ?></a></div>
            <?php endif; ?>
          </div>
          <?php if (!empty($waPhoneLink) || (!empty($_SESSION['role']) && $_SESSION['role']==='buyer')): ?>
            <div class="mt-3 flex gap-3 flex-wrap">
              <?php if (!empty($waPhoneLink)): ?>
                <a href="<?php echo htmlspecialchars($waPhoneLink); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                  <span>WhatsApp</span>
                </a>
              <?php endif; ?>
              <?php
                // Booking controls for buyers (context-aware)
                $isOpen = (!isset($car['listing_status']) || $car['listing_status']===null || $car['listing_status']==='open');
                if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'):
              ?>
                <?php if (!empty($bookingMsg)): ?>
                  <div class="text-sm text-gray-700 bg-gray-100 px-3 py-2 rounded"><?php echo htmlspecialchars($bookingMsg); ?></div>
                <?php endif; ?>
                <?php if ($myBookingActive): ?>
                  <?php if ($myBooking['status']==='pending'): ?>
                    <div class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold">Booking Pending</div>
                    <form method="post" class="inline">
                      <input type="hidden" name="cancel_booking" value="1">
                      <input type="hidden" name="booking_id" value="<?php echo (int)$myBooking['booking_id']; ?>">
                      <button type="submit" class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold">Cancel</button>
                    </form>
                  <?php else: ?>
                    <div class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">Booking Accepted</div>
                  <?php endif; ?>
                <?php elseif ($isOpen && !$hasActiveBooking): ?>
                  <form method="post" class="inline">
                    <input type="hidden" name="book_car" value="1">
                    <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">Booking</button>
                  </form>
                <?php else: ?>
                  <div class="inline-flex items-center gap-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold">Booking Unavailable</div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-purple-700">Loan Calculator</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcPrice">Price (RM)</label>
              <input id="lcPrice" type="number" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars(number_format((float)$car['price'], 2, '.', '')); ?>" readonly>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcDown">Down Payment (RM)</label>
              <input id="lcDown" type="number" class="w-full p-2 border rounded" placeholder="0" min="0" step="100">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcYears">Loan Tenure (years)</label>
              <select id="lcYears" class="w-full p-2 border rounded">
                <?php for ($y=1; $y<=9; $y++): ?>
                  <option value="<?php echo $y; ?>" <?php echo ($y===9?'selected':''); ?>><?php echo $y; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcRate">Bank Rate (% p.a.)</label>
              <input id="lcRate" type="number" class="w-full p-2 border rounded" value="3.50" step="0.01" min="0" max="20">
            </div>
          </div>
          <div class="mt-3 flex flex-wrap gap-6 text-sm">
            <div>Monthly Payment: <span id="lcMonthly" class="font-bold text-red-600">RM 0.00</span></div>
            <div>Total Payable: <span id="lcTotal" class="font-semibold">RM 0.00</span></div>
            <div>Total Interest: <span id="lcInterest" class="font-semibold">RM 0.00</span></div>
          </div>
        </div>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-blue-600">Car Details</h3>
            <button type="button" id="openMoreDetailsView" class="bg-white border border-blue-600 text-blue-600 px-3 py-1.5 rounded hover:bg-blue-50">More Details</button>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-gray-700"><span class="font-semibold">Colour:</span> <?php echo htmlspecialchars($car_details['color'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Horsepower:</span> <?php echo htmlspecialchars($car_details['horsepower'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Engine Code:</span> <?php echo htmlspecialchars($car_details['engine_code'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Gear Numbers:</span> <?php echo htmlspecialchars($car_details['gear_numbers'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Front Wheel Size:</span> <?php echo htmlspecialchars($car_details['front_wheel_size'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Rear Wheel Size:</span> <?php echo htmlspecialchars($car_details['rear_wheel_size'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Torque:</span> <?php echo htmlspecialchars($car_details['torque'] ?? ''); ?></div>
            <?php if (!empty($car_details['car_type'])): ?>
              <div class="text-gray-700"><span class="font-semibold">Car Type:</span> <?php echo htmlspecialchars($car_details['car_type']); ?></div>
            <?php endif; ?>
            <?php if (!empty($car_details['car_condition'])): ?>
              <div class="text-gray-700"><span class="font-semibold">Condition:</span> <?php echo htmlspecialchars($car_details['car_condition']); ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- Report section at the very bottom -->
<div class="container mx-auto mt-6 mb-10">
  <div class="bg-white rounded shadow p-4 flex items-center justify-between">
    <div class="text-sm text-gray-600">See something wrong with this listing?</div>
    <div class="flex items-center gap-3">
      <?php if (!empty($reportMsg)): ?>
        <div class="text-sm px-3 py-1.5 rounded <?php echo strpos($reportMsg,'Thanks')===0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-800'; ?>"><?php echo htmlspecialchars($reportMsg); ?></div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['role'])): ?>
        <button type="button" id="openReportModal" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">Report this listing</button>
      <?php else: ?>
        <a href="login.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">Log in to report</a>
      <?php endif; ?>
    </div>
  </div>
  <p class="text-xs text-gray-500 mt-2">Abuse of this feature may lead to account restrictions.</p>
  </div>

<!-- Read-only More Details Slide-over -->
<div id="moreDetailsModalView" class="hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
  <div id="moreDetailsBackdropView" class="absolute inset-0 bg-black bg-opacity-50 opacity-0 transition-opacity duration-300"></div>
  <div class="absolute inset-y-0 right-0 max-w-full flex">
    <div id="moreDetailsPanelView" class="w-screen max-w-xl transform translate-x-full transition-transform duration-300 ease-out">
      <div class="h-full flex flex-col bg-white shadow-xl">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h3 class="text-xl font-semibold">More Details</h3>
          <button id="closeMoreDetailsView" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
        </div>
        <div class="p-5 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 7rem);">
          <h4 class="text-xl font-semibold">Car features and spec</h4>
          <div>
            <label for="featureSearchView" class="sr-only">Search features</label>
            <div class="relative">
              <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
              <input id="featureSearchView" type="text" placeholder="Search for features and spec" class="w-full border rounded pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>
          <div>
            <h5 class="text-lg font-semibold mb-2">All features</h5>
            <?php
              $fmtPriceV = isset($car['price']) ? 'RM'.number_format($car['price'], 2) : null;
              $sec_basic_v = [
                'Make / Model' => trim(($car['make'] ?? '').' '.($car['model'] ?? '')),
                'Variant' => $car['variant'] ?? null,
                'Year' => $car['year'] ?? null,
                'Price' => $fmtPriceV,
                'Mileage' => isset($car['mileage']) ? $car['mileage'].' km' : null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
                'Fuel' => $car['fuel'] ?? null,
                'Drive System' => $car['drive_system'] ?? null,
                'Doors' => isset($car['doors']) ? $car['doors'].'D' : null,
                'Condition' => $car_details['car_condition'] ?? null,
                'Type' => $car_details['car_type'] ?? null,
              ];
              $sec_perf_v = [
                'Horsepower' => $car_details['horsepower'] ?? null,
                'Torque' => $car_details['torque'] ?? null,
                '0-100 km/h (s)' => $car_more['zero_to_hundred_s'] ?? null,
                'Top Speed (km/h)' => $car_more['top_speed_kmh'] ?? null,
                'Engine Code' => $car_details['engine_code'] ?? null,
                'Gear Numbers' => $car_details['gear_numbers'] ?? null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
              ];
              $sec_wheels_v = [
                'Front Wheel Size' => $car_details['front_wheel_size'] ?? null,
                'Rear Wheel Size' => $car_details['rear_wheel_size'] ?? null,
              ];
              $sec_audio_v = [
                'Speaker Brand' => $car_more['speaker_brand'] ?? null,
                'Speaker Quantity' => isset($car_more['speaker_quantity']) ? $car_more['speaker_quantity'] : null,
              ];
              $sec_drivers_v = [];
              if (!empty($car_more['driver_assistance'])) {
                $items = preg_split("/(\r?\n)|,\s*/", $car_more['driver_assistance']);
                foreach ($items as $it) { $it = trim((string)$it); if ($it !== '') { $sec_drivers_v[] = $it; } }
              }
              $sec_interior_v = [
                'Heated Seat' => (isset($car_more['heated_seat']) && (int)$car_more['heated_seat'] === 1) ? 'Yes' : null,
                'Cooling Seat' => (isset($car_more['cooling_seat']) && (int)$car_more['cooling_seat'] === 1) ? 'Yes' : null,
              ];
              $sec_dimensions_v = [
                'Length (mm)' => $car_more['length_mm'] ?? null,
                'Width (mm)' => $car_more['width_mm'] ?? null,
                'Height (mm)' => $car_more['height_mm'] ?? null,
                'Wheel Base (mm)' => $car_more['wheel_base_mm'] ?? null,
                'Turning Circle' => $car_more['turning_circle'] ?? null,
              ];
              $sec_fuel_v = [
                'Fuel Consumption (L/100km)' => $car_more['fuel_consumption'] ?? null,
              ];
              $sec_suspension_v = [
                'Front Suspension' => $car_more['front_suspension'] ?? null,
                'Rear Suspension' => $car_more['rear_suspension'] ?? null,
              ];
              $sec_other_v = [];
              if (!empty($car_more['other_features'])) {
                $oitems = preg_split("/(\r?\n)|,\s*/", $car_more['other_features']);
                foreach ($oitems as $it) { $it = trim((string)$it); if ($it !== '') { $sec_other_v[] = $it; } }
              }

              $sections_v = [
                'Audio and Communications' => $sec_audio_v,
                'Drivers Assistance' => $sec_drivers_v,
                'Dimensions' => $sec_dimensions_v,
                'Fuel Economy' => $sec_fuel_v,
                'Suspension' => $sec_suspension_v,
                'Interior' => $sec_interior_v,
                'Other' => $sec_other_v,
                'Performance' => $sec_perf_v,
                'Wheels & Tyres' => $sec_wheels_v,
                'Basic Specs' => $sec_basic_v,
              ];
            ?>
            <div id="featureAccordionView" class="divide-y border rounded">
              <?php $idx=0; foreach ($sections_v as $secName => $items): $idx++; $clean = array_filter($items, function($v){ return !is_null($v) && $v !== ''; }); $cnt = count($clean); $open = ($secName === 'Basic Specs'); ?>
                <div class="acc-section-v" data-acc-section>
                  <button type="button" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 focus:outline-none acc-toggle-v" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
                    <div class="flex items-center gap-3">
                      <span class="font-semibold"><?php echo htmlspecialchars($secName); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="inline-flex items-center justify-center text-xs font-semibold text-gray-700 bg-gray-100 rounded px-2 py-0.5 acc-count-v"><?php echo (int)$cnt; ?></span>
                      <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200 acc-caret-v <?php echo $open ? 'rotate-180' : ''; ?>" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </div>
                  </button>
                  <div class="acc-panel-v <?php echo $open ? '' : 'hidden'; ?> px-6 pb-4">
                    <?php if ($cnt > 0): ?>
                      <?php $isBullets = array_values($clean) === $clean; ?>
                      <ul class="list-disc pl-5 space-y-1 acc-list-v">
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
                      <div class="text-sm text-gray-500 italic acc-empty-v">No items yet.</div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="px-5 py-3 border-t flex justify-end">
          <button id="closeMoreDetailsBottomView" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Report Modal -->
<div id="reportModal" class="hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
  <div id="reportBackdrop" class="absolute inset-0 bg-black bg-opacity-50 opacity-0 transition-opacity duration-200"></div>
  <div class="absolute inset-0 flex items-center justify-center p-4">
    <div id="reportPanel" class="bg-white w-full max-w-xl rounded-lg shadow-xl transform scale-95 opacity-0 transition-all duration-200">
      <form method="post">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h3 class="text-lg font-semibold">Report this listing</h3>
          <button type="button" id="closeReportModalX" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
        </div>
  <div class="p-5 space-y-4 overflow-y-auto" style="max-height:70vh;">
          <p class="text-sm text-gray-600">Select one or more reasons below. Add any details to help us review.</p>
          <fieldset class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php
              $reasonOptions = [
                'car_info_incorrect' => 'Car info incorrect',
                'car_unavailable' => 'Car unavailable / already sold',
                'seller_ghosting' => 'Seller ghosting / unresponsive',
                'price_misleading' => 'Price misleading',
                'duplicate_listing' => 'Duplicate listing',
                'offensive_content' => 'Offensive/inappropriate content',
                'scam_suspected' => 'Scam suspected',
                'images_misleading' => 'Images are misleading',
                'view_360_issue' => '3D/360 view issue',
              ];
              foreach ($reasonOptions as $val => $label): ?>
                <label class="inline-flex items-start gap-2 text-sm text-gray-800">
                  <input type="checkbox" name="reasons[]" value="<?php echo htmlspecialchars($val); ?>" class="mt-0.5">
                  <span><?php echo htmlspecialchars($label); ?></span>
                </label>
            <?php endforeach; ?>
          </fieldset>
          <div>
            <label for="reportDetails" class="block text-sm font-medium text-gray-700 mb-1">Additional details (optional)</label>
            <textarea id="reportDetails" name="details" rows="4" class="w-full border rounded p-2" placeholder="Describe the issue..."></textarea>
          </div>
        </div>
        <div class="px-5 py-3 border-t flex justify-end gap-3">
          <button type="button" id="closeReportModal" class="px-4 py-2 border rounded text-gray-800 hover:bg-gray-50">Cancel</button>
          <button type="submit" name="submit_report" value="1" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Submit Report</button>
        </div>
      </form>
    </div>
  </div>
  <div class="sr-only" aria-live="polite" aria-atomic="true"></div>
  </div>
<script>
(function(){
  function fmtRM(num){
    try { return 'RM ' + Number(num).toLocaleString('en-MY', {minimumFractionDigits:2, maximumFractionDigits:2}); }
    catch(e){
      var n = Math.round(Number(num)*100)/100;
      return 'RM ' + (''+n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
  }
  function recalc(){
    var price = parseFloat(document.getElementById('lcPrice').value) || 0;
    var down = parseFloat(document.getElementById('lcDown').value) || 0;
    if (down < 0) down = 0;
    if (down > price) down = price;
    var years = parseInt(document.getElementById('lcYears').value, 10) || 0;
    var rate = parseFloat(document.getElementById('lcRate').value) || 0;
    var n = Math.max(1, years) * 12;
    var principal = Math.max(0, price - down);
    var r = (rate/100) / 12;
    var monthly = 0;
    if (principal === 0) {
      monthly = 0;
    } else if (r === 0) {
      monthly = principal / n;
    } else {
      var pow = Math.pow(1+r, n);
      monthly = principal * r * pow / (pow - 1);
    }
    var total = monthly * n;
    var interest = Math.max(0, total - principal);
    document.getElementById('lcMonthly').textContent = fmtRM(monthly);
    document.getElementById('lcTotal').textContent = fmtRM(total);
    document.getElementById('lcInterest').textContent = fmtRM(interest);
  }
  function hook(){
    ['lcDown','lcYears','lcRate'].forEach(function(id){
      var el = document.getElementById(id);
      if (!el) return;
      el.addEventListener('input', recalc);
      el.addEventListener('change', recalc);
    });
    recalc();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hook);
  } else { hook(); }
})();
</script>
<script>
(function(){
  var openBtn = document.getElementById('openMoreDetailsView');
  var modal = document.getElementById('moreDetailsModalView');
  var closeTop = document.getElementById('closeMoreDetailsView');
  var closeBottom = document.getElementById('closeMoreDetailsBottomView');
  var backdrop = document.getElementById('moreDetailsBackdropView');
  var panel = document.getElementById('moreDetailsPanelView');
  var accRoot = document.getElementById('featureAccordionView');
  var searchInput = document.getElementById('featureSearchView');

  function openModal(){
    if(!modal) return;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(function(){
      backdrop && backdrop.classList.add('opacity-100');
      panel && panel.classList.remove('translate-x-full');
      closeTop && closeTop.focus();
    });
  }
  function closeModal(){
    if(!modal) return;
    backdrop && backdrop.classList.remove('opacity-100');
    panel && panel.classList.add('translate-x-full');
    var onEnd = function(e){ if(e.target===panel){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); panel.removeEventListener('transitionend', onEnd);} };
    panel && panel.addEventListener('transitionend', onEnd);
  }
  if (openBtn) openBtn.addEventListener('click', openModal);
  if (closeTop) closeTop.addEventListener('click', closeModal);
  if (closeBottom) closeBottom.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeModal(); }});

  // Accordion
  if (accRoot){
    accRoot.addEventListener('click', function(e){
      var btn = e.target.closest('.acc-toggle-v');
      if (!btn) return;
      var section = btn.closest('[data-acc-section]');
      var panelEl = section.querySelector('.acc-panel-v');
      var caret = section.querySelector('.acc-caret-v');
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      if (expanded){
        panelEl.classList.add('hidden');
        caret && caret.classList.remove('rotate-180');
        btn.setAttribute('aria-expanded','false');
      } else {
        panelEl.classList.remove('hidden');
        caret && caret.classList.add('rotate-180');
        btn.setAttribute('aria-expanded','true');
      }
    });
  }

  function normalize(s){ return (s||'').toString().toLowerCase(); }
  function updateCounts(section){
    var countEl = section.querySelector('.acc-count-v');
    var list = section.querySelectorAll('.acc-list-v > li');
    var emptyEl = section.querySelector('.acc-empty-v');
    var visible = 0;
    list.forEach(function(li){ if (!li.classList.contains('hidden')) visible++; });
    if (countEl) countEl.textContent = visible;
    if (emptyEl){ emptyEl.classList.toggle('hidden', visible !== 0); }
  }
  function applySearch(term){
    var q = normalize(term);
    var sections = accRoot ? accRoot.querySelectorAll('[data-acc-section]') : [];
    sections.forEach(function(sec){
      var items = sec.querySelectorAll('.acc-list-v > li');
      var any = 0;
      items.forEach(function(li){
        var text = normalize(li.textContent);
        var show = q === '' ? true : text.indexOf(q) !== -1;
        li.classList.toggle('hidden', !show);
        if (show) any++;
      });
      var btn = sec.querySelector('.acc-toggle-v');
      var panelEl = sec.querySelector('.acc-panel-v');
      var caret = sec.querySelector('.acc-caret-v');
      if (q !== ''){
        if (any > 0){ panelEl.classList.remove('hidden'); btn && btn.setAttribute('aria-expanded','true'); caret && caret.classList.add('rotate-180'); }
        else { panelEl.classList.add('hidden'); btn && btn.setAttribute('aria-expanded','false'); caret && caret.classList.remove('rotate-180'); }
      }
      updateCounts(sec);
    });
  }
  if (searchInput){ searchInput.addEventListener('input', function(){ applySearch(searchInput.value); }); }
})();
</script>
<script>
(function(){
  var openBtn = document.getElementById('openReportModal');
  var modal = document.getElementById('reportModal');
  var backdrop = document.getElementById('reportBackdrop');
  var panel = document.getElementById('reportPanel');
  var closeX = document.getElementById('closeReportModalX');
  var closeBtn = document.getElementById('closeReportModal');

  function openModal(){
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(function(){
      backdrop && backdrop.classList.add('opacity-100');
      panel && (panel.classList.remove('opacity-0'), panel.classList.remove('scale-95'));
    });
  }
  function closeModal(){
    if (!modal) return;
    backdrop && backdrop.classList.remove('opacity-100');
    panel && (panel.classList.add('opacity-0'), panel.classList.add('scale-95'));
    setTimeout(function(){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }, 180);
  }
  if (openBtn) openBtn.addEventListener('click', openModal);
  if (closeX) closeX.addEventListener('click', closeModal);
  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });
})();
</script>
</body>
</html>
