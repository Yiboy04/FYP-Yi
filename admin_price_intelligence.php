<?php
session_start();
if (!isset($_SESSION['admin_name'])) { header('Location: admin_login.php'); exit(); }

$mysqli = new mysqli('localhost','root','', 'fyp');
if ($mysqli->connect_errno) { die('DB error: '.$mysqli->connect_error); }

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function fmtRM($n){ return 'RM '.number_format((float)$n, 2); }

// Distinct makes and models
$makes = [];
if ($rs = $mysqli->query("SELECT DISTINCT make FROM cars WHERE make IS NOT NULL AND make<>'' ORDER BY make")){
  while ($r=$rs->fetch_row()){ $makes[] = $r[0]; }
}
$make = isset($_GET['make']) ? trim($_GET['make']) : '';
$model = isset($_GET['model']) ? trim($_GET['model']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'open'; // open|sold|any

$models = [];
if ($make !== ''){
  $st = $mysqli->prepare("SELECT DISTINCT model FROM cars WHERE make=? AND model IS NOT NULL AND model<>'' ORDER BY model");
  $st->bind_param('s', $make); $st->execute(); $res=$st->get_result();
  while ($r=$res->fetch_row()){ $models[]=$r[0]; }
  $st->close();
}

$filterWhere = [];$types='';$params=[];
if ($make!==''){ $filterWhere[]='c.make=?'; $types.='s'; $params[]=$make; }
if ($model!==''){ $filterWhere[]='c.model=?'; $types.='s'; $params[]=$model; }
if (in_array($status,['open','sold'],true)){
  if ($status==='open'){ $filterWhere[]="(c.listing_status IS NULL OR c.listing_status='' OR c.listing_status='open' OR c.listing_status='negotiating')"; }
  else { $filterWhere[]="c.listing_status='sold'"; }
}
$whereSql = $filterWhere ? ('WHERE '.implode(' AND ', $filterWhere)) : '';

// Fetch relevant cars
$cars = [];
$sql = "SELECT c.car_id, c.make, c.model, c.year, c.price, c.listing_status FROM cars c $whereSql ORDER BY c.year DESC, c.price ASC LIMIT 1000";
if ($st = $mysqli->prepare($sql)){
  if ($types!==''){ $st->bind_param($types, ...$params); }
  $st->execute(); $res = $st->get_result();
  while ($row=$res->fetch_assoc()){ $cars[]=$row; }
  $st->close();
}

// Compute stats
$prices = array_map(function($r){ return (float)$r['price']; }, $cars);
sort($prices);
$count = count($prices);
$min = $count? min($prices):0; $max = $count? max($prices):0; $avg = $count? array_sum($prices)/$count:0;
$median = 0; if ($count){ $mid=floor(($count-1)/2); $median = ($count%2)? $prices[$mid] : (($prices[$mid]+$prices[$mid+1])/2); }

// Compute deviations for listing table
foreach ($cars as &$row){
  $row['_dev'] = ($median>0) ? (($row['price'] - $median)/$median*100) : 0;
}
unset($row);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Price Intelligence</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="min-h-screen flex">
    <aside class="w-64 bg-red-600 shadow-lg">
      <div class="p-5 border-b border-red-500">
        <h1 class="text-2xl font-bold text-white">Admin</h1>
        <div class="text-sm text-white mt-1">Welcome, <?php echo h($_SESSION['admin_name']); ?>!</div>
      </div>
      <nav class="p-3 space-y-1">
        <a href="admin_dashboard.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Dashboard</a>
        <a href="admin_users.php?type=buyers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Buyers</a>
        <a href="admin_users.php?type=sellers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Sellers</a>
        <a href="admin_cars.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Cars</a>
        <a href="admin_bookings.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Bookings</a>
  <a href="admin_price_intelligence.php" class="block px-3 py-2 rounded bg-red-500 text-white font-medium">Price Intelligence</a>
  <a href="admin_certified.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-4">Price Intelligence</h2>

      <form method="get" class="bg-white rounded shadow p-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Make</label>
          <select name="make" class="w-full border rounded p-2" onchange="this.form.submit()">
            <option value="">All</option>
            <?php foreach($makes as $m): ?>
              <option value="<?php echo h($m); ?>" <?php echo ($make===$m?'selected':''); ?>><?php echo h($m); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Model</label>
          <select name="model" class="w-full border rounded p-2">
            <option value="">All</option>
            <?php foreach($models as $md): ?>
              <option value="<?php echo h($md); ?>" <?php echo ($model===$md?'selected':''); ?>><?php echo h($md); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Listing Status</label>
          <select name="status" class="w-full border rounded p-2">
            <option value="any" <?php echo ($status==='any'?'selected':''); ?>>Any</option>
            <option value="open" <?php echo ($status==='open'?'selected':''); ?>>Open</option>
            <option value="sold" <?php echo ($status==='sold'?'selected':''); ?>>Sold</option>
          </select>
        </div>
        <div>
          <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Apply</button>
          <a href="admin_price_intelligence.php" class="ml-2 px-4 py-2 rounded border">Clear</a>
        </div>
      </form>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded shadow p-4">
          <h3 class="font-semibold mb-2">Summary</h3>
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div class="text-gray-500">Count</div><div class="text-right font-medium"><?php echo (int)$count; ?></div>
            <div class="text-gray-500">Min</div><div class="text-right font-medium"><?php echo fmtRM($min); ?></div>
            <div class="text-gray-500">Median</div><div class="text-right font-medium"><?php echo fmtRM($median); ?></div>
            <div class="text-gray-500">Average</div><div class="text-right font-medium"><?php echo fmtRM($avg); ?></div>
            <div class="text-gray-500">Max</div><div class="text-right font-medium"><?php echo fmtRM($max); ?></div>
          </div>
        </div>
        <div class="bg-white rounded shadow p-4 lg:col-span-2">
          <h3 class="font-semibold mb-2">Price vs Year (open and selected status)</h3>
          <div class="relative h-64">
            <canvas id="scatter"></canvas>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-3 py-2 text-left">Car</th>
              <th class="px-3 py-2 text-center">Year</th>
              <th class="px-3 py-2 text-right">Price</th>
              <th class="px-3 py-2 text-center">Deviation vs Median</th>
              <th class="px-3 py-2 text-left">Status</th>
              <th class="px-3 py-2 text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$cars): ?>
              <tr><td colspan="6" class="px-3 py-6 text-center text-gray-500">No cars found for current filters.</td></tr>
            <?php else: foreach($cars as $c): $dev = $c['_dev']; ?>
              <tr class="border-t">
                <td class="px-3 py-2">
                  <div class="font-medium"><?php echo h($c['make'].' '.$c['model']); ?></div>
                </td>
                <td class="px-3 py-2 text-center"><?php echo (int)$c['year']; ?></td>
                <td class="px-3 py-2 text-right"><?php echo fmtRM($c['price']); ?></td>
                <td class="px-3 py-2 text-center">
                  <span class="font-medium <?php echo ($dev<=-10?'text-green-600':($dev>=10?'text-red-600':'text-gray-700')); ?>"><?php echo sprintf('%+.1f%%', $dev); ?></span>
                </td>
                <td class="px-3 py-2"><?php echo h($c['listing_status'] ?: 'open'); ?></td>
                <td class="px-3 py-2"><a class="text-blue-600 hover:underline" target="_blank" href="car_details_view.php?car_id=<?php echo (int)$c['car_id']; ?>">View</a></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script>
    (function(){
      var el = document.getElementById('scatter'); if (!el) return;
      var pts = <?php echo json_encode(array_map(function($r){ return ['x'=>(int)$r['year'], 'y'=>(float)$r['price']]; }, $cars)); ?>;
      var median = <?php echo json_encode($median); ?>;
      var chart = new Chart(el, {
        type: 'scatter',
        data: { datasets: [{ label: 'Cars', data: pts, backgroundColor: 'rgba(239,68,68,0.7)'}] },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { x: { title: { display: true, text: 'Year' }, ticks: { precision: 0 } }, y: { title: { display: true, text: 'Price (RM)' }, beginAtZero: false } }
        }
      });
    })();
  </script>
</body>
</html>
