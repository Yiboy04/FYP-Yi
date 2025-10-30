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

// Ensure reports table exists
$mysqli->query("CREATE TABLE IF NOT EXISTS reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  car_id INT NOT NULL,
  reporter_id INT NULL,
  reporter_role ENUM('buyer','seller','admin','guest') NULL,
  reasons TEXT NULL,
  details TEXT NULL,
  status ENUM('new','reviewed','dismissed','resolved') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reports_car (car_id),
  CONSTRAINT fk_reports_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Update status handler
$updateMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
  $rid = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
  $newStatus = isset($_POST['status']) ? $_POST['status'] : '';
  if ($rid > 0 && in_array($newStatus, ['new','reviewed','dismissed','resolved'], true)) {
    if ($st = $mysqli->prepare("UPDATE reports SET status=? WHERE report_id=?")) {
      $st->bind_param('si', $newStatus, $rid);
      if ($st->execute()) { $updateMsg = 'Status updated.'; }
      $st->close();
    }
  }
}

// Filters
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? trim((string)$_GET['search']) : '';

$where = [];
if (in_array($status, ['new','reviewed','dismissed','resolved'], true)) {
  $where[] = "r.status='".$mysqli->real_escape_string($status)."'";
}
if ($search !== '') {
  $s = $mysqli->real_escape_string($search);
  $where[] = "(c.make LIKE '%$s%' OR c.model LIKE '%$s%' OR r.reasons LIKE '%$s%' OR r.details LIKE '%$s%')";
}

$sql = "SELECT r.report_id, r.created_at, r.status, r.reasons, r.details, r.reporter_role, r.reporter_id,
               c.car_id, c.make, c.model, c.year
        FROM reports r
        JOIN cars c ON c.car_id = r.car_id";
if (!empty($where)) { $sql .= ' WHERE '.implode(' AND ', $where); }
$sql .= ' ORDER BY r.report_id DESC';

$res = $mysqli->query($sql);
$reports = [];
if ($res) { while ($row = $res->fetch_assoc()) { $reports[] = $row; } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Reports</title>
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
        <a href="admin_cars.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Cars</a>
        <a href="admin_bookings.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Bookings</a>
        <a href="admin_certified.php" class="block px-3 py-2 rounded bg-red-500 text-white font-medium">Certified Requests</a>
        <a href="admin_reports.php" class="block px-3 py-2 rounded bg-red-500 font-medium text-white">Reports</a>
        <a href="admin_logout.php" class="block px-3 py-2 rounded hover:bg-red-500 text-white">Logout</a>
      </nav>
    </aside>

    <!-- Main content -->
    <main class="flex-1 p-6">
      <h2 class="text-xl font-bold mb-4">Reports</h2>
      <?php if ($updateMsg): ?>
        <div class="mb-3 bg-green-100 text-green-700 px-4 py-2 rounded"><?php echo htmlspecialchars($updateMsg); ?></div>
      <?php endif; ?>
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <form method="get" class="border-b">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 p-4">
            <div class="flex items-center gap-2">
              <label class="text-sm text-gray-600">Status</label>
              <select name="status" class="px-2 py-2 border rounded" onchange="this.form.submit()">
                <option value="all" <?php echo $status==='all'?'selected':''; ?>>All</option>
                <option value="new" <?php echo $status==='new'?'selected':''; ?>>New</option>
                <option value="reviewed" <?php echo $status==='reviewed'?'selected':''; ?>>Reviewed</option>
                <option value="dismissed" <?php echo $status==='dismissed'?'selected':''; ?>>Dismissed</option>
                <option value="resolved" <?php echo $status==='resolved'?'selected':''; ?>>Resolved</option>
              </select>
            </div>
            <div class="flex items-center gap-2">
              <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search car, reasons or details" class="px-3 py-2 border rounded w-80">
              <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Apply</button>
              <a href="admin_reports.php" class="px-3 py-2 border rounded text-gray-800 hover:bg-gray-50">Reset</a>
            </div>
          </div>
        </form>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reasons</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php if (empty($reports)): ?>
                <tr>
                  <td colspan="7" class="px-4 py-6 text-center text-gray-500">No reports found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($reports as $r): ?>
                  <tr>
                    <td class="px-4 py-3 text-gray-800 whitespace-nowrap"><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($r['created_at']))); ?></td>
                    <td class="px-4 py-3 text-gray-800">
                      <div class="font-medium"><?php echo htmlspecialchars(($r['make'] ?? '').' '.($r['model'] ?? '')); ?></div>
                      <div class="text-xs text-gray-500">Year: <?php echo htmlspecialchars($r['year'] ?? ''); ?> · ID: #<?php echo (int)$r['car_id']; ?></div>
                    </td>
                    <td class="px-4 py-3 text-gray-800">
                      <div class="capitalize"><?php echo htmlspecialchars($r['reporter_role'] ?? ''); ?></div>
                      <?php if (!empty($r['reporter_id'])): ?>
                        <div class="text-xs text-gray-500">User ID: <?php echo (int)$r['reporter_id']; ?></div>
                      <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-gray-800 max-w-xs">
                      <div class="text-sm"><?php echo htmlspecialchars($r['reasons'] ?? ''); ?></div>
                    </td>
                    <td class="px-4 py-3 text-gray-800 max-w-md">
                      <div class="text-sm truncate" title="<?php echo htmlspecialchars($r['details'] ?? ''); ?>"><?php echo htmlspecialchars($r['details'] ?? ''); ?></div>
                    </td>
                    <td class="px-4 py-3 text-gray-800">
                      <form method="post" class="flex items-center gap-2">
                        <input type="hidden" name="report_id" value="<?php echo (int)$r['report_id']; ?>">
                        <select name="status" class="px-2 py-1 border rounded text-sm">
                          <?php $statuses = ['new'=>'New','reviewed'=>'Reviewed','dismissed'=>'Dismissed','resolved'=>'Resolved'];
                            foreach ($statuses as $val=>$lab): ?>
                              <option value="<?php echo $val; ?>" <?php echo ($r['status']===$val?'selected':''); ?>><?php echo $lab; ?></option>
                          <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_status" value="1" class="px-3 py-1 bg-gray-800 text-white rounded text-sm">Save</button>
                      </form>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                      <a href="admin_car_view.php?car_id=<?php echo (int)$r['car_id']; ?>" class="text-blue-600 hover:underline">View Car</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
