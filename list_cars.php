<?php
// list_cars.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}
// Get filters from GET
$make = isset($_GET['make']) ? $mysqli->real_escape_string($_GET['make']) : '';
$model = isset($_GET['model']) ? $mysqli->real_escape_string($_GET['model']) : '';
$minYear = isset($_GET['minYear']) ? intval($_GET['minYear']) : 1957;
$maxYear = isset($_GET['maxYear']) ? intval($_GET['maxYear']) : 2025;
$minPrice = isset($_GET['minPrice']) ? floatval($_GET['minPrice']) : 1;
$maxPrice = isset($_GET['maxPrice']) ? floatval($_GET['maxPrice']) : 100000000;
// Build query
$where = [];
if ($make) $where[] = "make='$make'";
if ($model) $where[] = "model='$model'";
$where[] = "year>=$minYear AND year<=$maxYear";
$where[] = "price>=$minPrice AND price<=$maxPrice";
$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$sql = "SELECT * FROM cars $whereSQL ORDER BY price DESC";
$res = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Car Listings</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Car Listings</h1>
    <a href="main.php" class="underline">Back</a>
  </div>
</header>
<main class="container mx-auto mt-8 flex gap-8">
  <!-- Filter Sidebar -->
  <aside class="w-80 bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Filter</h2>
    <form method="get" action="list_cars.php">
      <div class="mb-4">
        <label class="block mb-1">Make</label>
        <select name="make" class="w-full p-2 border rounded" onchange="this.form.submit()">
          <option value="">All Makes</option>
          <?php $makesRes = $mysqli->query("SELECT DISTINCT make FROM cars ORDER BY make ASC");
          while($row = $makesRes->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['make']); ?>" <?php if($make==$row['make']) echo 'selected'; ?>><?php echo htmlspecialchars($row['make']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Model</label>
        <select name="model" class="w-full p-2 border rounded" onchange="this.form.submit()">
          <option value="">All Models</option>
          <?php if($make): $modelsRes = $mysqli->query("SELECT DISTINCT model FROM cars WHERE make='$make' ORDER BY model ASC");
          while($row = $modelsRes->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['model']); ?>" <?php if($model==$row['model']) echo 'selected'; ?>><?php echo htmlspecialchars($row['model']); ?></option>
          <?php endwhile; endif; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Year</label>
        <input type="number" name="minYear" value="<?php echo $minYear; ?>" class="w-1/2 p-2 border rounded" min="1957" max="2025"> -
        <input type="number" name="maxYear" value="<?php echo $maxYear; ?>" class="w-1/2 p-2 border rounded" min="1957" max="2025">
      </div>
      <div class="mb-4">
        <label class="block mb-1">Price (RM)</label>
        <input type="number" name="minPrice" value="<?php echo $minPrice; ?>" class="w-1/2 p-2 border rounded" min="1" max="100000000"> -
        <input type="number" name="maxPrice" value="<?php echo $maxPrice; ?>" class="w-1/2 p-2 border rounded" min="1" max="100000000">
      </div>
      <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold">Apply Filter</button>
    </form>
  </aside>
  <!-- Car List -->
  <section class="flex-1">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php while($car = $res->fetch_assoc()): ?>
        <div class="bg-white rounded-xl shadow hover:shadow-lg p-4 flex flex-col">
          <img src="<?php echo htmlspecialchars($car['car_id'] ? ($mysqli->query("SELECT image_path FROM car_images WHERE car_id=".$car['car_id']." LIMIT 1")->fetch_assoc()['image_path'] ?? 'https://via.placeholder.com/300x200?text=No+Image') : 'https://via.placeholder.com/300x200?text=No+Image'); ?>" class="w-full h-40 object-cover rounded mb-2">
          <h3 class="text-lg font-bold mb-1"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h3>
          <div class="text-red-600 font-bold mb-2">RM <?php echo number_format($car['price'],2); ?></div>
          <div class="text-sm text-gray-600 mb-2">
            Year: <?php echo htmlspecialchars($car['year']); ?> | Mileage: <?php echo htmlspecialchars($car['mileage']); ?> km
          </div>
          <div class="flex-1"></div>
          <a href="car_details_view.php?car_id=<?php echo $car['car_id']; ?>" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-lg text-center font-semibold">View Details</a>
        </div>
      <?php endwhile; ?>
      <?php if($res->num_rows==0): ?>
        <div class="col-span-3 text-center text-gray-500 py-12">No cars found matching your criteria.</div>
      <?php endif; ?>
    </div>
  </section>
</main>
</body>
</html>
