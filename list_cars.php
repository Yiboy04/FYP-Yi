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

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Sort option (whitelist to avoid SQL injection)
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'random';
$allowedSorts = ['price_desc','price_asc','year_desc','year_asc','mileage_desc','mileage_asc','random'];
if (!in_array($sort, $allowedSorts, true)) { $sort = 'random'; }

// Build option lists (scoped by current make/model when provided)
$baseScope = [];
if ($make) $baseScope[] = "make='$make'";
if ($model) $baseScope[] = "model='$model'";
// Only include listings visible publicly: NULL, empty, 'open', or 'negotiating'
$baseScope[] = "((listing_status IS NULL) OR TRIM(listing_status)='' OR LOWER(TRIM(listing_status)) IN ('open','negotiating'))";

// Variants from cars
$variantOptions = [];
{
  $where = $baseScope;
  $where[] = "variant IS NOT NULL AND variant<>''";
  $sqlOpt = "SELECT DISTINCT variant FROM cars" . (count($where) ? (" WHERE " . implode(' AND ', $where)) : "") . " ORDER BY variant ASC";
  $varRes = $mysqli->query($sqlOpt);
  if ($varRes) { while($row=$varRes->fetch_assoc()) { $variantOptions[] = $row['variant']; } }
}

// Engine capacity will be filtered via numeric range inputs (0.5L–8.0L)

// Colours from car_details joined to cars (so scope applies)
$colorOptions = [];
{
  $where = [];
  if ($make) $where[] = "c.make='$make'";
  if ($model) $where[] = "c.model='$model'";
  $where[] = "((c.listing_status IS NULL) OR TRIM(c.listing_status)='' OR LOWER(TRIM(c.listing_status)) IN ('open','negotiating'))";
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
// Only show publicly visible listings (treat blank as open)
$where[] = "((cars.listing_status IS NULL) OR TRIM(cars.listing_status)='' OR LOWER(TRIM(cars.listing_status)) IN ('open','negotiating'))";
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
// Build ORDER BY from sort selection
switch ($sort) {
  case 'price_asc':    $orderBy = "cars.price ASC, cars.car_id ASC"; break;
  case 'price_desc':   $orderBy = "cars.price DESC, cars.car_id ASC"; break;
  case 'year_asc':     $orderBy = "cars.year ASC, cars.car_id ASC"; break;
  case 'year_desc':    $orderBy = "cars.year DESC, cars.car_id ASC"; break;
  case 'mileage_asc':  $orderBy = "cars.mileage ASC, cars.car_id ASC"; break;
  case 'mileage_desc': $orderBy = "cars.mileage DESC, cars.car_id ASC"; break;
  case 'random':
  default:             $orderBy = "RAND()"; break;
}

// Total count for pagination
$total = 0; $totalPages = 1;
$countSql = "SELECT COUNT(DISTINCT cars.car_id) AS total FROM cars LEFT JOIN car_details cd ON cars.car_id = cd.car_id $whereSQL";
if ($cnt = $mysqli->query($countSql)) { $row = $cnt->fetch_assoc(); $total = (int)($row['total'] ?? 0); $cnt->close(); }
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) { $page = $totalPages; $offset = ($page - 1) * $perPage; }

// Page of results
$sql = "SELECT cars.*, cd.color AS cd_color, cd.car_condition AS cd_condition FROM cars LEFT JOIN car_details cd ON cars.car_id = cd.car_id $whereSQL ORDER BY $orderBy LIMIT $perPage OFFSET $offset";
$res = $mysqli->query($sql);
// Preload thumbnails for cars in result to avoid N+1 queries
$rows = [];
$carIds = [];
$seen = [];
if ($res instanceof mysqli_result) {
  while ($r = $res->fetch_assoc()) {
    $cid = (int)$r['car_id'];
    if (isset($seen[$cid])) { continue; } // de-duplicate by car_id in case of unintended joins
    $seen[$cid] = true;
    $carIds[] = $cid;
    $rows[] = $r; // keep unique rows in memory to iterate later
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
    <h1 class="text-2xl font-bold">Great Value Car (GVC)</h1>
    <nav>
      <ul class="flex gap-6 items-center">
        <li><a href="main.php" class="hover:underline">Home</a></li>
        <li><a href="list_cars.php" class="hover:underline">Listings</a></li>
        <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
          <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
        <?php endif; ?>
        <!-- Fold-down menu -->
        <li class="relative" id="moreMenu">
          <button id="moreBtn" class="inline-flex items-center gap-1 px-3 py-1 bg-white bg-opacity-10 hover:bg-opacity-20 rounded">
            <span>More</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div id="morePanel" class="hidden absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
            <a href="analysis.php" class="block px-4 py-2 hover:bg-gray-100">Analysis</a>
            <a href="saved_search.php" class="block px-4 py-2 hover:bg-gray-100">Saved</a>
            <a href="compare.php" class="block px-4 py-2 hover:bg-gray-100">Compare</a>
            <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
              <a href="buyer_bookings.php" class="block px-4 py-2 hover:bg-gray-100">Bookings</a>
            <?php endif; ?>
            <a href="about.php" class="block px-4 py-2 hover:bg-gray-100">About</a>
          </div>
        </li>
        <li><a href="logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>
<script>
  // Simple dropdown toggle for header "More" menu
  (function(){
    const menu = document.getElementById('moreMenu');
    const btn = document.getElementById('moreBtn');
    const panel = document.getElementById('morePanel');
    if (!menu || !btn || !panel) return;
    btn.addEventListener('click', (e) => { e.preventDefault(); panel.classList.toggle('hidden'); });
    document.addEventListener('click', (e) => { if (!menu.contains(e.target)) panel.classList.add('hidden'); });
  })();
</script>
<main class="container mx-auto mt-8 flex gap-8">
  <!-- Filter Sidebar -->
  <aside class="w-80 bg-white rounded-xl shadow p-6 mb-8">
    <h2 class="text-xl font-bold mb-4">Filter</h2>
    <form method="get" action="list_cars.php">
      <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>" />
      <div class="mb-4">
        <label class="block mb-1">Make</label>
        <select name="make" class="w-full p-2 border rounded" onchange="this.form.submit()">
          <option value="">All Makes</option>
          <?php $makesRes = $mysqli->query("SELECT DISTINCT make FROM cars WHERE ((listing_status IS NULL) OR TRIM(listing_status)='' OR LOWER(TRIM(listing_status)) IN ('open','negotiating')) ORDER BY make ASC");
          while($row = $makesRes->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['make']); ?>" <?php if($make==$row['make']) echo 'selected'; ?>><?php echo htmlspecialchars($row['make']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="block mb-1">Model</label>
        <select name="model" class="w-full p-2 border rounded" onchange="this.form.submit()">
          <option value="">All Models</option>
          <?php if($make): $modelsRes = $mysqli->query("SELECT DISTINCT model FROM cars WHERE make='$make' AND ((listing_status IS NULL) OR TRIM(listing_status)='' OR LOWER(TRIM(listing_status)) IN ('open','negotiating')) ORDER BY model ASC");
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
          <?php $transOptions = ['', 'AT','Manual','CVT','DCT','DHT']; ?>
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
        <div class="flex items-center gap-2">
          <input type="number" name="engine_capacity_min" class="w-1/2 p-2 border rounded" min="0.5" max="8.0" step="0.1" placeholder="Min (0.5)" value="<?php echo $engine_capacity_min !== null ? htmlspecialchars((string)$engine_capacity_min) : ''; ?>">
          <span class="text-gray-500">-</span>
          <input type="number" name="engine_capacity_max" class="w-1/2 p-2 border rounded" min="0.5" max="8.0" step="0.1" placeholder="Max (8.0)" value="<?php echo $engine_capacity_max !== null ? htmlspecialchars((string)$engine_capacity_max) : ''; ?>">
        </div>
        <p class="mt-1 text-xs text-gray-500">Enter a range between 0.5L and 8.0L. Leave blank for no limit.</p>
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
    <!-- Top-right sort control -->
    <div class="flex justify-end items-center mb-4">
      <form method="get" action="list_cars.php" class="flex items-center gap-2">
        <!-- Preserve current filters -->
        <input type="hidden" name="make" value="<?php echo htmlspecialchars($make); ?>" />
        <input type="hidden" name="model" value="<?php echo htmlspecialchars($model); ?>" />
        <input type="hidden" name="minYear" value="<?php echo htmlspecialchars($minYear); ?>" />
        <input type="hidden" name="maxYear" value="<?php echo htmlspecialchars($maxYear); ?>" />
        <input type="hidden" name="minPrice" value="<?php echo htmlspecialchars($minPrice); ?>" />
        <input type="hidden" name="maxPrice" value="<?php echo htmlspecialchars($maxPrice); ?>" />
        <input type="hidden" name="variant" value="<?php echo htmlspecialchars($variant); ?>" />
        <input type="hidden" name="transmission" value="<?php echo htmlspecialchars($transmissionFilter); ?>" />
        <input type="hidden" name="doors" value="<?php echo htmlspecialchars((string)$doors); ?>" />
        <input type="hidden" name="engine_capacity" value="<?php echo htmlspecialchars($engine_capacity); ?>" />
        <input type="hidden" name="engine_capacity_min" value="<?php echo htmlspecialchars((string)$engine_capacity_min); ?>" />
        <input type="hidden" name="engine_capacity_max" value="<?php echo htmlspecialchars((string)$engine_capacity_max); ?>" />
        <input type="hidden" name="color" value="<?php echo htmlspecialchars($color); ?>" />
        <input type="hidden" name="car_condition" value="<?php echo htmlspecialchars($car_condition); ?>" />
        <label class="mr-2 text-gray-700">Sort by</label>
        <select name="sort" class="p-2 border rounded" onchange="this.form.submit()">
          <option value="random" <?php if($sort==='random') echo 'selected'; ?>>Default</option>
          <option value="price_desc" <?php if($sort==='price_desc') echo 'selected'; ?>>Price: High to Low</option>
          <option value="price_asc" <?php if($sort==='price_asc') echo 'selected'; ?>>Price: Low to High</option>
          <option value="year_desc" <?php if($sort==='year_desc') echo 'selected'; ?>>Year: New to Old</option>
          <option value="year_asc" <?php if($sort==='year_asc') echo 'selected'; ?>>Year: Old to New</option>
          <option value="mileage_desc" <?php if($sort==='mileage_desc') echo 'selected'; ?>>Mileage: High to Low</option>
          <option value="mileage_asc" <?php if($sort==='mileage_asc') echo 'selected'; ?>>Mileage: Low to High</option>
        </select>
      </form>
    </div>
  <div class="grid grid-cols-1 gap-4">
      <?php if (!empty($rows)): ?>
      <?php foreach($rows as $car): ?>
        <?php
          $thumb = $thumbnails[$car['car_id']] ?? 'https://via.placeholder.com/480x320?text=No+Image';
          $isNew = is_numeric($car['year']) ? ((int)$car['year'] >= ((int)date('Y') - 1)) : false;
          $isRecommended = isset($car['cd_condition']) && in_array($car['cd_condition'], ['Certified','Reconditioned']);
          $condText = isset($car['cd_condition']) ? trim((string)$car['cd_condition']) : '';
          $showCondOverlay = ($condText !== '' && strcasecmp($condText, 'New') !== 0);
          $colorDisp = $car['cd_color'] ?? '';
          $colorDisp = $colorDisp !== '' ? $colorDisp : '—';
          $mileageDisp = is_numeric($car['mileage']) ? number_format((int)$car['mileage']) . ' km' : '—';
          // engine_capacity can be number in liters; show cc if numeric
          $engineCC = '—';
          if (isset($car['engine_capacity']) && $car['engine_capacity'] !== '' && is_numeric($car['engine_capacity'])) {
            $engineCC = number_format((float)$car['engine_capacity'] * 1000) . ' cc';
          } elseif (isset($car['engine_capacity']) && $car['engine_capacity'] !== '') {
            $engineCC = htmlspecialchars($car['engine_capacity']);
          }
          $trans = $car['transmission'] ?? '';
          $transShort = $trans === 'Manual' ? 'MT' : ($trans ?: '—');
          $monthly = is_numeric($car['price']) ? ((float)$car['price'] / 108.0) : null;
        ?>
        <div class="bg-white rounded-xl shadow hover:shadow-lg p-3 flex flex-col md:flex-row gap-3">
          <div class="relative w-full md:w-56">
            <?php if ($isRecommended): ?>
              <div class="absolute -top-3 -left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-md shadow">Recommended</div>
            <?php endif; ?>
            <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full h-40 md:h-36 object-cover rounded-lg">
            <?php if ($showCondOverlay): ?>
              <div class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white text-xs font-semibold px-2 py-0.5 rounded"><?php echo htmlspecialchars($condText); ?></div>
            <?php endif; ?>
            <?php if ($isNew): ?>
              <?php $newPos = $showCondOverlay ? 'bottom-9 left-2' : 'bottom-2 left-2'; ?>
              <div class="absolute <?php echo $newPos; ?> bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">NEW</div>
            <?php endif; ?>
            <!-- Play icon overlay placeholder -->
            <div class="hidden absolute inset-0 items-center justify-center">
              <div class="w-12 h-12 bg-white bg-opacity-90 rounded-full flex items-center justify-center text-red-600">▶</div>
            </div>
          </div>
          <div class="flex-1 flex flex-col">
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0">
                <h3 class="text-base md:text-lg font-bold leading-tight truncate"><?php echo htmlspecialchars(strtoupper($car['make'].' '.$car['model'])); ?></h3>
                <?php if (!empty($car['variant'])): ?>
                  <div class="text-xs md:text-sm text-gray-600 truncate"><?php echo htmlspecialchars($car['variant']); ?></div>
                <?php endif; ?>
              </div>
              <div class="text-right">
                <div class="text-red-600 font-extrabold text-lg md:text-xl whitespace-nowrap">RM <?php echo number_format((float)$car['price'], 0); ?></div>
                <?php if ($monthly !== null): ?>
                  <div class="text-gray-600 opacity-60 text-xs md:text-sm whitespace-nowrap">RM <?php echo number_format($monthly, 0); ?>/month</div>
                <?php endif; ?>
              </div>
            </div>
            <div class="mt-2 grid grid-cols-2 gap-x-6 gap-y-1 text-xs md:text-sm">
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Year</span><span class="text-gray-800 font-medium"><?php echo htmlspecialchars((string)$car['year']); ?></span></div>
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Color</span><span class="text-gray-800 font-medium"><?php echo htmlspecialchars($colorDisp); ?></span></div>
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Mileage</span><span class="text-gray-800 font-medium"><?php echo htmlspecialchars($mileageDisp); ?></span></div>
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Engine</span><span class="text-gray-800 font-medium"><?php echo $engineCC; ?></span></div>
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Steering</span><span class="text-gray-800 font-medium">Right</span></div>
              <div class="flex items-baseline gap-2"><span class="text-gray-500 w-20">Trans</span><span class="text-gray-800 font-medium"><?php echo htmlspecialchars($transShort); ?></span></div>
            </div>
            <div class="mt-3 flex justify-end">
              <a href="car_details_view.php?car_id=<?php echo $car['car_id']; ?><?php if(!empty($_SERVER['QUERY_STRING'])) echo '&'.htmlspecialchars($_SERVER['QUERY_STRING']); ?>" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <!-- Pagination Controls -->
      <?php
        // Build base query string without page
        $qs = $_GET; unset($qs['page']);
        $baseQs = http_build_query($qs);
        $start = $total > 0 ? ($offset + 1) : 0;
        $end = min($offset + count($rows), $total);
        $prevLink = 'list_cars.php?' . htmlspecialchars($baseQs . ($baseQs ? '&' : '') . 'page=' . max(1, $page-1));
        $nextLink = 'list_cars.php?' . htmlspecialchars($baseQs . ($baseQs ? '&' : '') . 'page=' . min($totalPages, $page+1));
      ?>
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">
        <div class="text-sm text-gray-600">Showing <?php echo $start; ?>–<?php echo $end; ?> of <?php echo $total; ?></div>
        <div class="flex items-center gap-2">
          <a href="<?php echo $prevLink; ?>" class="px-3 py-1 border rounded <?php echo $page<=1?'opacity-50 pointer-events-none':''; ?>">Prev</a>
          <span class="text-sm">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
          <a href="<?php echo $nextLink; ?>" class="px-3 py-1 border rounded <?php echo $page>=$totalPages?'opacity-50 pointer-events-none':''; ?>">Next</a>
        </div>
      </div>
      <?php else: ?>
        <div class="col-span-3 text-center text-gray-500 py-12">No results.</div>
      <?php endif; ?>
    </div>
  </section>
</main>
</body>
</html>
