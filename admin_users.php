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

// Decide which table to work with
$type = isset($_GET['type']) ? $_GET['type'] : 'buyers';
$table = $type === 'sellers' ? 'sellers' : 'buyers'; // default buyers

// Handle update user (no role)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id    = intval($_POST['id']);
    $name  = $mysqli->real_escape_string($_POST['name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $phone = $mysqli->real_escape_string($_POST['phone']);

    // sellers might also have car_id column – we do not touch it here
    $mysqli->query("UPDATE `$table` SET name='$name', email='$email', phone='$phone' WHERE id=$id");
}

// Handle delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM `$table` WHERE id=$id");
    header("Location: admin_users.php?type=$type");
    exit();
}

// Fetch all from correct table
$result = $mysqli->query("SELECT * FROM `$table`");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage <?php echo ucfirst($type); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Manage <?php echo ucfirst($type); ?></h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="admin_dashboard.php" class="hover:underline">Dashboard</a></li>
        <li><a href="admin_logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="container mx-auto mt-8">
  <h2 class="text-xl font-bold mb-4"><?php echo ucfirst($type); ?> List</h2>

  <table class="table-auto w-full bg-white shadow">
    <thead>
      <tr class="bg-gray-200">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Name</th>
        <th class="px-4 py-2">Email</th>
        <th class="px-4 py-2">Phone</th>
        <?php if($type === 'sellers'): ?>
          <th class="px-4 py-2">Car ID</th>
        <?php endif; ?>
        <th class="px-4 py-2">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php while($user = $result->fetch_assoc()): ?>
      <tr>
        <form method="post" action="admin_users.php?type=<?php echo $type; ?>">
          <td class="border px-4 py-2">
            <?php echo $user['id']; ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
          </td>
          <td class="border px-4 py-2"><input type="text" name="name" value="<?php echo $user['name']; ?>" class="border p-1 w-full"></td>
          <td class="border px-4 py-2"><input type="email" name="email" value="<?php echo $user['email']; ?>" class="border p-1 w-full"></td>
          <td class="border px-4 py-2"><input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="border p-1 w-full"></td>
          <?php if($type === 'sellers'): ?>
            <td class="border px-4 py-2 text-center"><?php echo $user['car_id']; ?></td>
          <?php endif; ?>
          <td class="border px-4 py-2 flex gap-2">
            <button type="submit" name="update_user" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Save</button>
            <a href="admin_users.php?type=<?php echo $type; ?>&delete=<?php echo $user['id']; ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" onclick="return confirm('Delete this user?');">Delete</a>
          </td>
        </form>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
