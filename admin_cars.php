<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
    header("Location: admin_login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Filters and sorting
$search = isset($_GET['search']) ? trim((string)$_GET['search']) : '';
$makeFilter = isset($_GET['make']) ? trim((string)$_GET['make']) : '';
$carStatus = isset($_GET['car_status']) ? trim((string)$_GET['car_status']) : '';
// Normalize car_status to allowed values only
$carStatus = in_array($carStatus, ['open','sold'], true) ? $carStatus : '';
$yearFrom = isset($_GET['year_from']) && $_GET['year_from'] !== '' ? intval($_GET['year_from']) : null;
$yearTo = isset($_GET['year_to']) && $_GET['year_to'] !== '' ? intval($_GET['year_to']) : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
$limit = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$conditions = [];
if ($search !== '') {
  $s = $mysqli->real_escape_string($search);
  $conditions[] = "(c.make LIKE '%$s%' OR c.model LIKE '%$s%' OR c.variant LIKE '%$s%')";
}
if ($makeFilter !== '') {
  $m = $mysqli->real_escape_string($makeFilter);
  $conditions[] = "c.make LIKE '%$m%'";
}
if (!is_null($yearFrom)) { $conditions[] = "c.year >= ".intval($yearFrom); }
if (!is_null($yearTo)) { $conditions[] = "c.year <= ".intval($yearTo); }
if ($carStatus === 'sold') {
  $conditions[] = "COALESCE(c.listing_status,'') = 'sold'";
} else if ($carStatus === 'open') {
  // Treat NULL/''/open/negotiating as open listings
  $conditions[] = "(c.listing_status IS NULL OR c.listing_status = '' OR c.listing_status IN ('open','negotiating'))";
}

switch ($sort) {
  case 'year_desc': $orderBy = 'c.year DESC, c.car_id DESC'; break;
  case 'year_asc': $orderBy = 'c.year ASC, c.car_id DESC'; break;
  case 'make_asc': $orderBy = 'c.make ASC, c.model ASC'; break;
  case 'make_desc': $orderBy = 'c.make DESC, c.model DESC'; break;
  default: $orderBy = 'c.car_id DESC'; // latest
}

// Count total for pagination
$countSql = "SELECT COUNT(*) AS total FROM cars c";
if (!empty($conditions)) { $countSql .= ' WHERE '.implode(' AND ', $conditions); }
$total = 0;
if ($rs = $mysqli->query($countSql)) { $r = $rs->fetch_assoc(); $total = intval($r['total'] ?? 0); }
$totalPages = max(1, (int)ceil($total / $limit));
if ($page > $totalPages) { $page = $totalPages; }
$offset = ($page - 1) * $limit;

// Build SQL with pagination
$sql = "SELECT c.car_id, c.make, c.model, c.variant, c.year,
        (SELECT ci.image_path FROM car_images ci WHERE ci.car_id = c.car_id LIMIT 1) AS thumb
        FROM cars c";
if (!empty($conditions)) { $sql .= ' WHERE '.implode(' AND ', $conditions); }
$sql .= ' ORDER BY '.$orderBy;
$sql .= ' LIMIT '.intval($limit).' OFFSET '.intval($offset);

$res = $mysqli->query($sql);
$cars = [];
if ($res) {
  while ($row = $res->fetch_assoc()) { $cars[] = $row; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Cars</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="min-h-screen flex">
    <!-- Left sidebar navigation -->
    <aside class="w-64 bg-red-600 shadow-lg">
      <div class="p-5 border-b border-red-500">
        <h1 class="text-2xl font-bold text-white">Admin</h1>
        <div class="text-sm text-white mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</div>
      </div>
      <nav class="p-3 space-y-1">
        <a href="admin_dashboard.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Dashboard</a>
        <a href="admin_users.php?type=buyers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Buyers</a>
        <a href="admin_users.php?type=sellers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Sellers</a>
        <a href="admin_cars.php" class="block px-3 py-2 rounded bg-red-500 font-medium text-white">Cars</a>
        <a href="admin_bookings.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Bookings</a>
        <a href="admin_certified.php" class="block px-3 py-2 rounded bg-red-500 text-white font-medium">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-4">Cars</h2>
      <?php if (!empty($_GET['msg']) && $_GET['msg']==='deleted'): ?>
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">Listing deleted.</div>
      <?php endif; ?>
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <form method="get" class="border-b">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4">
            <div class="flex items-center gap-2">
              <button type="button" id="toggleFilter" class="px-3 py-2 border rounded text-gray-800 hover:bg-gray-50">Filter</button>
              <label class="text-sm text-gray-600">Sort</label>
              <select name="sort" class="px-2 py-2 border rounded" onchange="this.form.submit()">
                <option value="latest" <?php echo $sort==='latest'?'selected':''; ?>>Latest</option>
                <option value="year_desc" <?php echo $sort==='year_desc'?'selected':''; ?>>Year: High to Low</option>
                <option value="year_asc" <?php echo $sort==='year_asc'?'selected':''; ?>>Year: Low to High</option>
                <option value="make_asc" <?php echo $sort==='make_asc'?'selected':''; ?>>Make: A → Z</option>
                <option value="make_desc" <?php echo $sort==='make_desc'?'selected':''; ?>>Make: Z → A</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search make, model or variant" class="px-3 py-2 border rounded w-64">
              <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Apply</button>
              <a href="admin_cars.php" class="px-3 py-2 border rounded text-gray-800 hover:bg-gray-50">Reset</a>
            </div>
          </div>
          <?php $filterOpen = ($makeFilter!=='' || !is_null($yearFrom) || !is_null($yearTo) || $carStatus !== ''); ?>
          <div id="filterPanel" class="<?php echo $filterOpen ? '' : 'hidden'; ?> p-4 bg-gray-50 border-t grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
              <label class="block text-sm text-gray-600 mb-1">Make</label>
              <input type="text" name="make" value="<?php echo htmlspecialchars($makeFilter); ?>" class="w-full px-3 py-2 border rounded" placeholder="e.g. Toyota">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Year From</label>
              <input type="number" name="year_from" value="<?php echo htmlspecialchars($yearFrom ?? ''); ?>" class="w-full px-3 py-2 border rounded" min="1900" max="2100">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Year To</label>
              <input type="number" name="year_to" value="<?php echo htmlspecialchars($yearTo ?? ''); ?>" class="w-full px-3 py-2 border rounded" min="1900" max="2100">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Car Status</label>
              <select name="car_status" class="w-full px-3 py-2 border rounded">
                <option value="" <?php echo $carStatus===''?'selected':''; ?>>Any</option>
                <option value="open" <?php echo $carStatus==='open'?'selected':''; ?>>Open</option>
                <option value="sold" <?php echo $carStatus==='sold'?'selected':''; ?>>Sold</option>
              </select>
            </div>
            <div class="flex items-end">
              <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded">Apply Filters</button>
            </div>
          </div>
        </form>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thumbnail</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
              <th class="px-4 py-3"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($cars)): ?>
              <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No cars found.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($cars as $car): ?>
                <tr>
                  <td class="px-4 py-3">
                    <?php if (!empty($car['thumb'])): ?>
                      <img src="<?php echo htmlspecialchars($car['thumb']); ?>" class="w-20 h-14 object-cover rounded border" alt="thumb">
                    <?php else: ?>
                      <div class="w-20 h-14 bg-gray-200 rounded border flex items-center justify-center text-xs text-gray-500">No image</div>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($car['make'] ?? ''); ?></td>
                  <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($car['model'] ?? ''); ?></td>
                  <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($car['variant'] ?? ''); ?></td>
                  <td class="px-4 py-3 text-gray-800"><?php echo htmlspecialchars($car['year'] ?? ''); ?></td>
                  <td class="px-4 py-3 text-right">
                    <a href="admin_car_view.php?car_id=<?php echo (int)$car['car_id']; ?>" class="text-blue-600 hover:underline">View</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
        <?php
          // Pagination controls
          if ($total > 0) {
            $start = ($page - 1) * $limit + 1;
            $end = min($total, $page * $limit);
            $buildLink = function($p) {
              $params = $_GET;
              $params['page'] = $p;
              return 'admin_cars.php?' . htmlspecialchars(http_build_query($params));
            };
        ?>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4 border-t bg-gray-50">
          <div class="text-sm text-gray-600">Showing <?php echo (int)$start; ?>–<?php echo (int)$end; ?> of <?php echo (int)$total; ?></div>
          <div class="flex items-center gap-2">
            <a class="px-3 py-1 border rounded <?php echo $page<=1?'opacity-50 pointer-events-none':''; ?>" href="<?php echo $page>1 ? $buildLink($page-1) : '#'; ?>">Prev</a>
            <span class="text-sm text-gray-600">Page <?php echo (int)$page; ?> of <?php echo (int)$totalPages; ?></span>
            <a class="px-3 py-1 border rounded <?php echo $page>=$totalPages?'opacity-50 pointer-events-none':''; ?>" href="<?php echo $page<$totalPages ? $buildLink($page+1) : '#'; ?>">Next</a>
          </div>
        </div>
        <?php } ?>
      </div>
    </main>
  </div>
</body>
<script>
  (function(){
    var btn = document.getElementById('toggleFilter');
    var panel = document.getElementById('filterPanel');
    if (btn && panel){
      btn.addEventListener('click', function(){ panel.classList.toggle('hidden'); });
    }
  })();
</script>
</html>
