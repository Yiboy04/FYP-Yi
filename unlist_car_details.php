<?php
// unlist_car_details.php - View-only details for unlisted car (sold/considering/negotiating) owned by the seller
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$mysqli = new mysqli('localhost','root','','fyp');
if ($mysqli->connect_errno) {
    die('DB error: ' . $mysqli->connect_error);
}
if (!isset($_GET['car_id'])) die('No car selected.');
$car_id = intval($_GET['car_id']);
$seller_id = intval($_SESSION['user_id']);

// Fetch car and ensure it belongs to this seller and is not open
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=? AND seller_id=? AND (listing_status IN ('sold','considering','negotiating') OR listing_status IS NULL AND 1=0)");
$stmt->bind_param('ii', $car_id, $seller_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die('Car not found or not unlisted.');

// Images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=? ORDER BY is_thumbnail DESC, image_id ASC");
$imgQ->bind_param('i', $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while($row=$resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();

// car_details
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param('i', $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Unlisted Car Details</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script>
function changeMain(src){ document.getElementById('mainImage').src=src; }
</script>
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Unlisted Car</h1>
    <a href="unlist_car.php" class="underline">Back</a>
  </div>
</header>
<main class="container mx-auto mt-8">
  <div class="bg-white rounded shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
        </div>
      </div>
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
            <div><span class="font-semibold">Status:</span> <?php echo htmlspecialchars($car['listing_status']); ?></div>
          </div>
        </div>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-blue-600">Car Details</h3>
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
</body>
</html>
