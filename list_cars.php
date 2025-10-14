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
// New filters
$color = isset($_GET['color']) ? $mysqli->real_escape_string($_GET['color']) : '';
$car_condition = isset($_GET['car_condition']) ? $mysqli->real_escape_string($_GET['car_condition']) : '';
$doors = isset($_GET['doors']) && $_GET['doors'] !== '' ? intval($_GET['doors']) : 0;
$engine_capacity_min = isset($_GET['engine_capacity_min']) && $_GET['engine_capacity_min'] !== '' ? floatval($_GET['engine_capacity_min']) : null; // legacy support
$engine_capacity_max = isset($_GET['engine_capacity_max']) && $_GET['engine_capacity_max'] !== '' ? floatval($_GET['engine_capacity_max']) : null; // legacy support
$engine_capacity = isset($_GET['engine_capacity']) ? $mysqli->real_escape_string($_GET['engine_capacity']) : '';
$variant = isset($_GET['variant']) ? $mysqli->real_escape_string($_GET['variant']) : '';
$transmissionFilter = isset($_GET['transmission']) ? $mysqli->real_escape_string($_GET['transmission']) : '';

// Build option lists (scoped by current make/model when provided)
$baseScope = [];
if ($make) $baseScope[] = "make='$make'";
if ($model) $baseScope[] = "model='$model'";

// Variants from cars
$variantOptions = [];
{
  $where = $baseScope;
  $where[] = "variant IS NOT NULL AND variant<>''";
  $sqlOpt = "SELECT DISTINCT variant FROM cars" . (count($where) ? (" WHERE " . implode(' AND ', $where)) : "") . " ORDER BY variant ASC";
  $varRes = $mysqli->query($sqlOpt);
  if ($varRes) { while($row=$varRes->fetch_assoc()) { $variantOptions[] = $row['variant']; } }
}

// Engine capacities from cars, ordered numerically
$engineCapOptions = [];
{
  $where = $baseScope;
  $where[] = "engine_capacity IS NOT NULL AND engine_capacity<>''";
  $sqlOpt = "SELECT DISTINCT engine_capacity AS ec FROM cars" . (count($where) ? (" WHERE " . implode(' AND ', $where)) : "") . " ORDER BY (engine_capacity+0) ASC";
  $ecRes = $mysqli->query($sqlOpt);
  if ($ecRes) { while($row=$ecRes->fetch_assoc()) { $engineCapOptions[] = $row['ec']; } }
}

// Colours from car_details joined to cars (so scope applies)
$colorOptions = [];
{
  $where = [];
  if ($make) $where[] = "c.make='$make'";
  if ($model) $where[] = "c.model='$model'";
  $where[] = "cd.color IS NOT NULL AND cd.color<>''";
  $sqlOpt = "SELECT DISTINCT cd.color AS color FROM car_details cd JOIN cars c ON cd.car_id=c.car_id" . (count($where) ? (" WHERE " . implode(' AND ', $where)) : "") . " ORDER BY cd.color ASC";
  $colorRes = $mysqli->query($sqlOpt);
  if ($colorRes) { while($row=$colorRes->fetch_assoc()) { $colorOptions[] = $row['color']; } }
}
// Build query
$where = [];
if ($make) $where[] = "make='$make'";
if ($model) $where[] = "model='$model'";
$where[] = "year>=$minYear AND year<=$maxYear";
$where[] = "price>=$minPrice AND price<=$maxPrice";
// apply new filters
if ($variant) $where[] = "variant='$variant'";
if ($transmissionFilter) $where[] = "transmission='$transmissionFilter'";
if ($doors) $where[] = "doors=$doors";
if ($engine_capacity !== '') {
  $where[] = "engine_capacity='$engine_capacity'";
} else {
  // legacy support for range
  if ($engine_capacity_min !== null) $where[] = "(engine_capacity+0) >= $engine_capacity_min";
  if ($engine_capacity_max !== null) $where[] = "(engine_capacity+0) <= $engine_capacity_max";
}
// Filters from car_details (LEFT JOIN below)
if ($color) $where[] = "cd.color='$color'";
if ($car_condition) $where[] = "cd.car_condition='$car_condition'";
$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';
// LEFT JOIN car_details as cd to filter by color/condition while still showing cars with no details when those filters are empty
$sql = "SELECT cars.* FROM cars LEFT JOIN car_details cd ON cars.car_id = cd.car_id $whereSQL ORDER BY price DESC";
$res = $mysqli->query($sql);
// Preload thumbnails for cars in result to avoid N+1 queries
$rows = [];
$carIds = [];
if ($res instanceof mysqli_result) {
  while ($r = $res->fetch_assoc()) {
    $carIds[] = $r['car_id'];
    $rows[] = $r; // keep rows in memory to iterate later
  }
}

// fetch thumbnails: prefer is_thumbnail=1, otherwise first image
$thumbnails = [];
if (count($carIds) > 0) {
  $ids = implode(',', array_map('intval', $carIds));
  $q = $mysqli->query("SELECT car_id, image_path, is_thumbnail FROM car_images WHERE car_id IN ($ids) ORDER BY is_thumbnail DESC, image_id ASC");
  while ($img = $q->fetch_assoc()) {
    if (!isset($thumbnails[$img['car_id']])) {
      $thumbnails[$img['car_id']] = $img['image_path'];
    }
  }
}
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
      <div class="mb-4">
        <label class="block mb-1">Variant</label>
        <select name="variant" class="w-full p-2 border rounded">
          <option value="">All Variants</option>
          <?php foreach($variantOptions as $opt): ?>
            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo ($variant===$opt ? 'selected' : ''); ?>><?php echo htmlspecialchars($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Transmission</label>
        <select name="transmission" class="w-full p-2 border rounded">
          <?php $transOptions = ['', 'AT','Manual','CVT','DCT']; ?>
          <?php foreach($transOptions as $opt): ?>
            <option value="<?php echo $opt; ?>" <?php echo ($transmissionFilter===$opt?'selected':''); ?>><?php echo $opt===''?'All':htmlspecialchars($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Doors</label>
        <select name="doors" class="w-full p-2 border rounded">
          <?php $doorOptions = [''=> 'All', 2=>'2D',3=>'3D',4=>'4D',5=>'5D']; ?>
          <?php foreach($doorOptions as $val=>$label): ?>
            <option value="<?php echo htmlspecialchars((string)$val); ?>" <?php echo ((string)$doors === (string)$val ? 'selected' : ''); ?>><?php echo htmlspecialchars($label); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Engine Capacity (L)</label>
        <select name="engine_capacity" class="w-full p-2 border rounded">
          <option value="">All Capacities</option>
          <?php foreach($engineCapOptions as $opt): ?>
            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo ($engine_capacity===$opt ? 'selected' : ''); ?>><?php echo htmlspecialchars($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Colour</label>
        <select name="color" class="w-full p-2 border rounded">
          <option value="">All Colours</option>
          <?php foreach($colorOptions as $opt): ?>
            <option value="<?php echo htmlspecialchars($opt); ?>" <?php echo ($color===$opt ? 'selected' : ''); ?>><?php echo htmlspecialchars($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-6">
        <label class="block mb-1">Condition</label>
        <select name="car_condition" class="w-full p-2 border rounded">
          <?php $condOptions = ['', 'New','Reconditioned','Used','Certified']; ?>
          <?php foreach($condOptions as $opt): ?>
            <option value="<?php echo $opt; ?>" <?php echo ($car_condition===$opt?'selected':''); ?>><?php echo $opt===''?'All':htmlspecialchars($opt); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold">Apply Filter</button>
    </form>
  </aside>
  <!-- Car List -->
  <section class="flex-1">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (!empty($rows)): ?>
      <?php foreach($rows as $car): ?>
        <?php $thumb = $thumbnails[$car['car_id']] ?? 'https://via.placeholder.com/300x200?text=No+Image'; ?>
        <div class="bg-white rounded-xl shadow hover:shadow-lg p-4 flex flex-col">
          <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full h-40 object-cover rounded mb-2">
          <h3 class="text-lg font-bold mb-1"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h3>
          <div class="text-red-600 font-bold mb-2">RM <?php echo number_format($car['price'],2); ?></div>
          <div class="text-sm text-gray-600 mb-2">
            Year: <?php echo htmlspecialchars($car['year']); ?> | Mileage: <?php echo htmlspecialchars($car['mileage']); ?> km
          </div>
          <div class="flex-1"></div>
          <a href="car_details_view.php?car_id=<?php echo $car['car_id']; ?><?php if(!empty($_SERVER['QUERY_STRING'])) echo '&'.htmlspecialchars($_SERVER['QUERY_STRING']); ?>" class="mt-2 w-full bg-blue-600 text-white py-2 rounded-lg text-center font-semibold">View Details</a>
        </div>
      <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-3 text-center text-gray-500 py-12">No results.</div>
      <?php endif; ?>
    </div>
  </section>
</main>
</body>
</html>
