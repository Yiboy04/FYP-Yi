<?php
// unlist_car.php - show seller's unlisted cars and allow listing back
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$mysqli = new mysqli('localhost','root','','fyp');
if ($mysqli->connect_errno) {
    die('DB error: ' . $mysqli->connect_error);
}
$seller_id = intval($_SESSION['user_id']);

// Load Malaysia RHD makes/models dataset
$makesModelsPath = __DIR__ . '/data/makes_models_my.json';
$makes = [];
$modelsByMake = [];
if (file_exists($makesModelsPath)) {
  $json = json_decode(file_get_contents($makesModelsPath), true);
  if ($json) {
    $makes = $json['makes'] ?? [];
    // Deduplicate and sort makes alphabetically (case-insensitive)
    $makes = array_values(array_unique($makes));
    natcasesort($makes);
    $makes = array_values($makes);

    $modelsByMake = $json['modelsByMake'] ?? [];
    // Sort model lists alphabetically as well
    foreach ($modelsByMake as $mk => $arr) {
      if (is_array($arr)) {
        natcasesort($arr);
        $modelsByMake[$mk] = array_values($arr);
      }
    }
  }
}

// Read filter params for unlisted list
$f_q            = isset($_GET['q']) ? trim($_GET['q']) : '';
$f_make         = isset($_GET['make']) ? trim($_GET['make']) : '';
$f_model        = isset($_GET['model']) ? trim($_GET['model']) : '';
$f_status       = isset($_GET['status']) ? trim($_GET['status']) : 'all'; // all|sold|considering
$f_transmission = isset($_GET['transmission']) ? trim($_GET['transmission']) : '';
$f_min_year     = isset($_GET['min_year']) && $_GET['min_year'] !== '' ? intval($_GET['min_year']) : null;
$f_max_year     = isset($_GET['max_year']) && $_GET['max_year'] !== '' ? intval($_GET['max_year']) : null;
$f_min_price    = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? floatval($_GET['min_price']) : null;
$f_max_price    = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? floatval($_GET['max_price']) : null;
$f_sort         = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

$sortWhitelist = [
  'newest'       => 'car_id DESC',
  'price_low'    => 'price ASC',
  'price_high'   => 'price DESC',
  'year_new'     => 'year DESC',
  'year_old'     => 'year ASC',
  'mileage_low'  => 'mileage ASC',
  'mileage_high' => 'mileage DESC'
];
$orderBy = isset($sortWhitelist[$f_sort]) ? $sortWhitelist[$f_sort] : $sortWhitelist['newest'];

// Handle actions: set listing_status
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['action'], $_POST['car_id'])) {
    $car_id = intval($_POST['car_id']);
    if ($_POST['action']==='list_back') {
      $stmt = $mysqli->prepare("UPDATE cars SET listing_status='open' WHERE car_id=? AND seller_id=?");
      $stmt->bind_param('ii', $car_id, $seller_id);
      $stmt->execute();
      $stmt->close();
    } elseif ($_POST['action']==='unlist_set' && isset($_POST['status'])) {
      $status = $_POST['status'];
      $allowed = ['sold','considering'];
      if (in_array($status, $allowed, true)) {
        $stmt = $mysqli->prepare("UPDATE cars SET listing_status=? WHERE car_id=? AND seller_id=?");
        $stmt->bind_param('sii', $status, $car_id, $seller_id);
        $stmt->execute();
        $stmt->close();
      }
    }
  }
  header('Location: unlist_car.php');
  exit();
}

// If arriving with car_id via GET from seller_main, show a small form to select status to unlist
$selectedCar = null;
if (isset($_GET['car_id'])) {
  $cid = intval($_GET['car_id']);
  $q = $mysqli->prepare("SELECT car_id, make, model, price, listing_status FROM cars WHERE car_id=? AND seller_id=?");
  $q->bind_param('ii', $cid, $seller_id);
  $q->execute();
  $selectedCar = $q->get_result()->fetch_assoc();
  $q->close();
}

// Load all non-open cars for this seller
// Build dynamic WHERE for unlisted
$where = ["seller_id = ?"];
$types = 'i';
$params = [$seller_id];
if ($f_status === 'sold' || $f_status === 'considering') {
  $where[] = "listing_status = ?"; $types .= 's'; $params[] = $f_status;
} else {
  $where[] = "listing_status IN ('sold','considering')";
}
if ($f_q !== '') {
  $like = '%' . $mysqli->real_escape_string($f_q) . '%';
  $where[] = "(make LIKE ? OR model LIKE ? OR variant LIKE ?)"; $types .= 'sss'; $params[] = $like; $params[] = $like; $params[] = $like;
}
if ($f_make !== '') { $where[] = "make = ?"; $types .= 's'; $params[] = $f_make; }
if ($f_model !== '') { $where[] = "model = ?"; $types .= 's'; $params[] = $f_model; }
if ($f_transmission !== '') { $where[] = "transmission = ?"; $types .= 's'; $params[] = $f_transmission; }
if (!is_null($f_min_year)) { $where[] = "year >= ?"; $types .= 'i'; $params[] = $f_min_year; }
if (!is_null($f_max_year)) { $where[] = "year <= ?"; $types .= 'i'; $params[] = $f_max_year; }
if (!is_null($f_min_price)) { $where[] = "price >= ?"; $types .= 'd'; $params[] = $f_min_price; }
if (!is_null($f_max_price)) { $where[] = "price <= ?"; $types .= 'd'; $params[] = $f_max_price; }

$sql = "SELECT car_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors, listing_status FROM cars WHERE " . implode(' AND ', $where) . " ORDER BY $orderBy";
$res = $mysqli->prepare($sql);
if (!$res) { die('Query error: ' . $mysqli->error); }
if ($types !== '') {
  $bindParams = [$types]; foreach($params as $k=>$_) { $bindParams[] = &$params[$k]; }
  call_user_func_array([$res, 'bind_param'], $bindParams);
}
$res->execute();
$rowsRes = $res->get_result();
// Collect rows and preload thumbnails (prefer is_thumbnail=1 else first image)
$cars = [];
$carIds = [];
if ($rowsRes) {
  while ($r = $rowsRes->fetch_assoc()) {
    $cars[] = $r;
    $carIds[] = (int)$r['car_id'];
  }
}
$thumbnails = [];
if (count($carIds) > 0) {
  $ids = implode(',', array_map('intval', $carIds));
  $imgQ = $mysqli->query("SELECT car_id, image_path, is_thumbnail FROM car_images WHERE car_id IN ($ids) ORDER BY is_thumbnail DESC, image_id ASC");
  if ($imgQ) {
    while ($img = $imgQ->fetch_assoc()) {
      if (!isset($thumbnails[$img['car_id']])) {
        $thumbnails[$img['car_id']] = $img['image_path'];
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Unlisted Cars</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Unlisted Cars</h1>
    <nav class="flex gap-3">
      <a href="seller_main.php" class="underline">Back to Dashboard</a>
    </nav>
  </div>
</header>
<main class="container mx-auto mt-8">
  <div class="flex gap-3 mb-4">
    <button type="button" onclick="toggleModal('filterModal')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Filter</button>
  </div>

  <!-- Filter Modal -->
  <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-4xl">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Filter & Sort</h2>
        <button type="button" onclick="toggleModal('filterModal')" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>
      <form method="get" class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
        <div class="md:col-span-2">
          <label class="block text-sm text-gray-700">Search</label>
          <input type="text" name="q" value="<?php echo htmlspecialchars($f_q); ?>" placeholder="Make / Model / Variant" class="border p-2 rounded w-full">
        </div>
        <div>
          <label class="block text-sm text-gray-700">Make</label>
          <select name="make" id="filterMake" onchange="updateModelOptions(this,'filterModel','<?php echo htmlspecialchars($f_model); ?>')" class="border p-2 rounded w-full">
            <option value="">All</option>
            <?php foreach($makes as $m): ?>
              <option value="<?php echo $m; ?>" <?php if($f_make===$m) echo 'selected'; ?>><?php echo $m; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Model</label>
          <select name="model" id="filterModel" class="border p-2 rounded w-full">
            <option value="">All</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Status</label>
          <select name="status" class="border p-2 rounded w-full">
            <option value="all" <?php if($f_status==='all') echo 'selected'; ?>>All</option>
            <option value="sold" <?php if($f_status==='sold') echo 'selected'; ?>>Sold</option>
            <option value="considering" <?php if($f_status==='considering') echo 'selected'; ?>>Considering</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Transmission</label>
          <select name="transmission" class="border p-2 rounded w-full">
            <option value="">All</option>
            <option value="AT" <?php if($f_transmission==='AT') echo 'selected'; ?>>AT</option>
            <option value="Manual" <?php if($f_transmission==='Manual') echo 'selected'; ?>>Manual</option>
            <option value="CVT" <?php if($f_transmission==='CVT') echo 'selected'; ?>>CVT</option>
            <option value="DCT" <?php if($f_transmission==='DCT') echo 'selected'; ?>>DCT</option>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700">Year Min</label>
          <input type="number" name="min_year" value="<?php echo htmlspecialchars($f_min_year ?? ''); ?>" class="border p-2 rounded w-full">
        </div>
        <div>
          <label class="block text-sm text-gray-700">Year Max</label>
          <input type="number" name="max_year" value="<?php echo htmlspecialchars($f_max_year ?? ''); ?>" class="border p-2 rounded w-full">
        </div>
        <div>
          <label class="block text-sm text-gray-700">Price Min (RM)</label>
          <input type="number" step="0.01" name="min_price" value="<?php echo htmlspecialchars($f_min_price ?? ''); ?>" class="border p-2 rounded w-full">
        </div>
        <div>
          <label class="block text-sm text-gray-700">Price Max (RM)</label>
          <input type="number" step="0.01" name="max_price" value="<?php echo htmlspecialchars($f_max_price ?? ''); ?>" class="border p-2 rounded w-full">
        </div>
        <div>
          <label class="block text-sm text-gray-700">Sort by</label>
          <select name="sort" class="border p-2 rounded w-full">
            <option value="newest" <?php if($f_sort==='newest') echo 'selected'; ?>>Newest</option>
            <option value="price_low" <?php if($f_sort==='price_low') echo 'selected'; ?>>Price: Low to High</option>
            <option value="price_high" <?php if($f_sort==='price_high') echo 'selected'; ?>>Price: High to Low</option>
            <option value="year_new" <?php if($f_sort==='year_new') echo 'selected'; ?>>Year: New to Old</option>
            <option value="year_old" <?php if($f_sort==='year_old') echo 'selected'; ?>>Year: Old to New</option>
            <option value="mileage_low" <?php if($f_sort==='mileage_low') echo 'selected'; ?>>Mileage: Low to High</option>
            <option value="mileage_high" <?php if($f_sort==='mileage_high') echo 'selected'; ?>>Mileage: High to Low</option>
          </select>
        </div>
        <div class="md:col-span-6 flex gap-2 justify-end mt-2">
          <a href="unlist_car.php" class="px-4 py-2 bg-gray-200 rounded">Reset</a>
          <button type="button" onclick="toggleModal('filterModal')" class="px-4 py-2 bg-gray-400 text-white rounded">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Apply</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    // JS for dynamic models specific to filter usage (includes 'All')
    const modelsByMake = <?php echo json_encode($modelsByMake); ?>;
    function updateModelOptions(makeSelect, modelSelectId, selectedModel='') {
      const make = makeSelect.value;
      const modelSelect = document.getElementById(modelSelectId);
      modelSelect.innerHTML = '';
      const allOpt = document.createElement('option');
      allOpt.value = '';
      allOpt.text = 'All';
      modelSelect.appendChild(allOpt);
      if (modelsByMake[make]) {
        modelsByMake[make].forEach(m => {
          const opt = document.createElement('option');
          opt.value = m; opt.text = m;
          if (m === selectedModel) opt.selected = true;
          modelSelect.appendChild(opt);
        });
      }
    }
    document.addEventListener('DOMContentLoaded', function(){
      const makeSel = document.getElementById('filterMake');
      if (makeSel) {
        updateModelOptions(makeSel, 'filterModel', '<?php echo htmlspecialchars($f_model); ?>');
      }
    });
  </script>
  <?php if ($selectedCar): ?>
    <div class="bg-white rounded-xl shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-2">Unlist Car: <?php echo htmlspecialchars($selectedCar['make'].' '.$selectedCar['model'].' (#'.$selectedCar['car_id'].')'); ?></h2>
      <p class="text-gray-600 mb-4">Choose a reason/status for unlisting. You can list it back later.</p>
      <form method="post" class="flex flex-wrap items-end gap-3">
        <input type="hidden" name="car_id" value="<?php echo $selectedCar['car_id']; ?>">
        <input type="hidden" name="action" value="unlist_set">
        <label class="block">
          <span class="block text-sm text-gray-700 mb-1">Status</span>
          <select name="status" class="border p-2 rounded">
            <option value="sold">Sold</option>
            <option value="considering">Considering</option>
          </select>
        </label>
        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Confirm Unlist</button>
      </form>
    </div>
  <?php endif; ?>

  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Your Unlisted Cars</h2>
    <?php if (!empty($cars)): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($cars as $car): ?>
          <?php $thumb = $thumbnails[$car['car_id']] ?? 'https://via.placeholder.com/300x200?text=No+Image'; ?>
          <div class="bg-gray-50 rounded p-4 shadow flex flex-col">
            <img src="<?php echo htmlspecialchars($thumb); ?>" class="w-full h-40 object-cover rounded mb-2" alt="Car">
            <div class="font-semibold mb-1"><?php echo htmlspecialchars($car['make'].' '.$car['model'].' '.$car['variant']); ?></div>
            <div class="text-gray-700 text-sm mb-1">Year: <?php echo htmlspecialchars($car['year']); ?> | Engine: <?php echo htmlspecialchars($car['engine_capacity']); ?> L</div>
            <div class="text-red-600 font-bold mb-2">RM <?php echo number_format($car['price'],2); ?></div>
            <div class="text-xs text-gray-500 mb-2">Status: <?php echo htmlspecialchars($car['listing_status']); ?></div>
            <div class="mt-auto flex justify-between items-center gap-2">
              <a href="unlist_car_details.php?car_id=<?php echo $car['car_id']; ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">View Details</a>
              <form method="post">
                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                <input type="hidden" name="action" value="list_back">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">List back</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center text-gray-500">No unlisted cars.</div>
    <?php endif; ?>
  </div>
</main>
</body>
</html>
