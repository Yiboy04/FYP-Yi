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
      </div>

      <!-- Right: details -->
      <div>
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h2>
        <div class="text-red-600 text-xl font-bold mb-4">RM<?php echo number_format($car['price'],2); ?></div>
        <table class="w-full text-left border-collapse">
          <tr><th class="py-1">Year</th><td class="py-1"><?php echo htmlspecialchars($car['year']); ?></td></tr>
          <tr><th class="py-1">Color</th><td class="py-1"><?php echo htmlspecialchars($car['color']); ?></td></tr>
          <tr><th class="py-1">Engine Capacity</th><td class="py-1"><?php echo htmlspecialchars($car['engine_capacity']); ?> cc</td></tr>
          <tr><th class="py-1">Transmission</th><td class="py-1"><?php echo htmlspecialchars($car['transmission']); ?></td></tr>
          <!-- add more rows if you add more columns like mileage etc. -->
        </table>
      </div>
    </div>
  </div>
</main>
</body>
</html>
