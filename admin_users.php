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

// Handle update user (no role)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = intval($_POST['id']);
    $name = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $mysqli->query("UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$id");
}

// Handle delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM users WHERE id=$id");
    header("Location: admin_users.php");
    exit();
}

/* ---- Filtering & Sorting ---- */
$roleFilter = isset($_GET['role']) ? $mysqli->real_escape_string($_GET['role']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // default sort column
$order = isset($_GET['order']) ? strtoupper($_GET['order']) : 'ASC'; // ASC or DESC

$validSortCols = ['id','name'];
if (!in_array($sort,$validSortCols)) $sort='id';
if (!in_array($order,['ASC','DESC'])) $order='ASC';

// Build query
$query = "SELECT * FROM users";
if ($roleFilter) {
    $query .= " WHERE role='$roleFilter'";
}
$query .= " ORDER BY $sort $order";

$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Manage Users</h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="admin_dashboard.php" class="hover:underline">Dashboard</a></li>
        <li><a href="logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="container mx-auto mt-8">
  <h2 class="text-xl font-bold mb-4">Users List</h2>

  <!-- Filter & Sort Controls -->
  <div class="flex gap-4 mb-4">
    <!-- Filter by Role -->
    <form method="get" class="flex gap-2">
      <select name="role" class="border px-2 py-1">
        <option value="">All Roles</option>
        <option value="buyer" <?php if($roleFilter==='buyer') echo 'selected'; ?>>Buyer</option>
        <option value="seller" <?php if($roleFilter==='seller') echo 'selected'; ?>>Seller</option>
      </select>
      <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Filter</button>
    </form>

    <!-- Sort -->
    <form method="get" class="flex gap-2">
      <?php if($roleFilter): ?>
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($roleFilter); ?>">
      <?php endif; ?>
      <select name="sort" class="border px-2 py-1">
        <option value="id" <?php if($sort==='id') echo 'selected'; ?>>User ID</option>
        <option value="name" <?php if($sort==='name') echo 'selected'; ?>>Name</option>
      </select>
      <select name="order" class="border px-2 py-1">
        <option value="ASC" <?php if($order==='ASC') echo 'selected'; ?>>Ascending</option>
        <option value="DESC" <?php if($order==='DESC') echo 'selected'; ?>>Descending</option>
      </select>
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Sort</button>
    </form>
  </div>

  <table class="table-auto w-full bg-white shadow">
    <thead>
      <tr class="bg-gray-200">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Name</th>
        <th class="px-4 py-2">Email</th>
        <th class="px-4 py-2">Phone</th>
        <th class="px-4 py-2">Role</th>
        <th class="px-4 py-2">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php while($user = $result->fetch_assoc()): ?>
      <tr>
        <form method="post" action="admin_users.php<?php echo ($roleFilter || $sort || $order) ? '?role='.$roleFilter.'&sort='.$sort.'&order='.$order : ''; ?>">
          <td class="border px-4 py-2"><?php echo $user['id']; ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
          </td>
          <td class="border px-4 py-2"><input type="text" name="name" value="<?php echo $user['name']; ?>" class="border p-1 w-full"></td>
          <td class="border px-4 py-2"><input type="email" name="email" value="<?php echo $user['email']; ?>" class="border p-1 w-full"></td>
          <td class="border px-4 py-2"><input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="border p-1 w-full"></td>
          <td class="border px-4 py-2 text-center"><?php echo $user['role']; ?></td>
          <td class="border px-4 py-2 flex gap-2">
            <button type="submit" name="update_user" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Save</button>
            <a href="admin_users.php?delete=<?php echo $user['id']; ?>&role=<?php echo $roleFilter; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Delete this user?');">Delete</a>
          </td>
        </form>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
