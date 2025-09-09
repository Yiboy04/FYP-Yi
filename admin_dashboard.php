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

// count users
$countResult = $mysqli->query("SELECT COUNT(*) as total FROM users");
$row = $countResult->fetch_assoc();
$totalUsers = $row['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="admin_dashboard.php" class="hover:underline">Dashboard</a></li>
        <li><a href="admin_logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="container mx-auto mt-10">
  <h2 class="text-xl font-bold mb-6">Welcome, <?php echo $_SESSION['admin_name']; ?>!</h2>

  <!-- Users count box -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <a href="admin_users.php" class="bg-white rounded-xl shadow hover:shadow-lg p-6 flex flex-col items-center">
      <div class="text-4xl font-bold text-red-600"><?php echo $totalUsers; ?></div>
      <div class="mt-2 text-gray-600">Total Users</div>
    </a>
    <!-- you can add more boxes later (eg: total cars, etc.) -->
  </div>
</div>
</body>
</html>
