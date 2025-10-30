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

// count buyers
$countBuyersResult = $mysqli->query("SELECT COUNT(*) as total FROM buyers");
$rowBuyers = $countBuyersResult->fetch_assoc();
$totalBuyers = $rowBuyers['total'];

// count sellers
$countSellersResult = $mysqli->query("SELECT COUNT(*) as total FROM sellers");
$rowSellers = $countSellersResult->fetch_assoc();
$totalSellers = $rowSellers['total'];

// count new reports
$totalNewReports = 0;
if ($rs = $mysqli->query("SELECT COUNT(*) AS total FROM reports WHERE status='new'")) {
  $r = $rs->fetch_assoc();
  $totalNewReports = (int)($r['total'] ?? 0);
}

// cars status counts
$openCars = 0; // consider NULL or 'open' as open
if ($rs = $mysqli->query("SELECT COUNT(*) AS total FROM cars WHERE listing_status IS NULL OR listing_status='open'")) {
  $r = $rs->fetch_assoc();
  $openCars = (int)($r['total'] ?? 0);
}
$soldCars = 0;
if ($rs = $mysqli->query("SELECT COUNT(*) AS total FROM cars WHERE listing_status='sold'")) {
  $r = $rs->fetch_assoc();
  $soldCars = (int)($r['total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="min-h-screen flex">
    <!-- Left sidebar navigation  -->
    <aside class="w-64 bg-red-600 shadow-lg">
      <div class="p-5 border-b border-red-500">
        <h1 class="text-2xl font-bold text-white">Admin</h1>
        <div class="text-sm text-white mt-1">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</div>
      </div>
      <nav class="p-3 space-y-1">
        <a href="admin_dashboard.php" class="block px-3 py-2 rounded hover:bg-red-500 font-medium text-white">Dashboard</a>
        <a href="admin_users.php?type=buyers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Buyers</a>
        <a href="admin_users.php?type=sellers" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Sellers</a>
        <a href="admin_cars.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Cars</a>
        <a href="admin_bookings.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Bookings</a>
        <a href="admin_certified.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-6">Dashboard Overview</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="admin_users.php?type=buyers" class="bg-white rounded-xl shadow hover:shadow-lg p-6 flex flex-col items-center">
          <div class="text-4xl font-bold text-red-600"><?php echo $totalBuyers; ?></div>
          <div class="mt-2 text-gray-600">Total Buyers</div>
        </a>
        <a href="admin_users.php?type=sellers" class="bg-white rounded-xl shadow hover:shadow-lg p-6 flex flex-col items-center">
          <div class="text-4xl font-bold text-red-600"><?php echo $totalSellers; ?></div>
          <div class="mt-2 text-gray-600">Total Sellers</div>
        </a>
        <a href="admin_reports.php?status=new" class="bg-white rounded-xl shadow hover:shadow-lg p-6 flex flex-col items-center">
          <div class="text-4xl font-bold text-red-600"><?php echo $totalNewReports; ?></div>
          <div class="mt-2 text-gray-600">New Reports</div>
        </a>
      </div>

      <!-- Chart Card -->
      <div class="mt-6 bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between gap-3 mb-3">
          <h3 class="text-lg font-semibold text-gray-800">Overview Chart</h3>
          <div class="inline-flex rounded overflow-hidden border border-gray-300">
            <button id="btnUsers" type="button" class="px-3 py-1.5 text-sm bg-red-600 text-white">Users</button>
            <button id="btnCars" type="button" class="px-3 py-1.5 text-sm bg-white text-gray-800 hover:bg-gray-50">Cars</button>
          </div>
        </div>
        <div class="relative h-96">
          <canvas id="overviewChart"></canvas>
        </div>
      </div>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    (function(){
      var ctx = document.getElementById('overviewChart');
      if (!ctx) return;
      var buyers = <?php echo (int)$totalBuyers; ?>;
      var sellers = <?php echo (int)$totalSellers; ?>;
      var openCars = <?php echo (int)$openCars; ?>;
      var soldCars = <?php echo (int)$soldCars; ?>;

      var usersCfg = {
        labels: ['Buyers','Sellers'],
        datasets: [{
          label: 'Users',
          data: [buyers, sellers],
          backgroundColor: ['#ef4444', '#111827']
        }]
      };
      var carsCfg = {
        labels: ['Open','Sold'],
        datasets: [{
          label: 'Cars',
          data: [openCars, soldCars],
          backgroundColor: ['#10b981', '#6b7280']
        }]
      };

      var chart = new Chart(ctx, {
        type: 'bar',
        data: usersCfg,
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true, precision: 0 } }
        }
      });

      var btnUsers = document.getElementById('btnUsers');
      var btnCars = document.getElementById('btnCars');
      function setActive(btnA, btnB){
        btnA.className = 'px-3 py-1.5 text-sm bg-red-600 text-white';
        btnB.className = 'px-3 py-1.5 text-sm bg-white text-gray-800 hover:bg-gray-50';
      }
      if (btnUsers && btnCars){
        btnUsers.addEventListener('click', function(){
          chart.data = usersCfg; chart.update(); setActive(btnUsers, btnCars);
        });
        btnCars.addEventListener('click', function(){
          chart.data = carsCfg; chart.update(); setActive(btnCars, btnUsers);
        });
      }
    })();
  </script>
</body>
</html>
