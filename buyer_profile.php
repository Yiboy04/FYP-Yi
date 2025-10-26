<?php
// buyer_profile.php
session_start();
if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: login.php');
    exit();
}
$mysqli = new mysqli('localhost', 'root', '', 'fyp');
if ($mysqli->connect_errno) { die('DB error: ' . $mysqli->connect_error); }
$buyerId = intval($_SESSION['user_id']);

// Helper to normalize phone numbers to digits-only
function normalize_phone($p) {
  return preg_replace('/\D+/', '', trim((string)$p));
}
$stmt = $mysqli->prepare('SELECT id, name, email, phone, password FROM buyers WHERE id = ?');
$stmt->bind_param('i', $buyerId);
$stmt->execute();
$res = $stmt->get_result();
$buyer = $res->fetch_assoc();
$stmt->close();
if (!$buyer) { die('Buyer not found.'); }

// Handle profile update (name/password)
$successMsg = $errorMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newName = isset($_POST['name']) ? trim($_POST['name']) : '';
  $newPhone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
  $normalizedPhone = normalize_phone($newPhone);
  $newPass = isset($_POST['new_password']) ? $_POST['new_password'] : '';
  $newPass2 = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

  if ($newName === '') {
    $errorMsg = 'Name cannot be empty.';
  } elseif ($newPhone === '') {
    $errorMsg = 'Phone cannot be empty.';
  } else {
    // Update name first if changed
    if ($newName !== $buyer['name']) {
      $u = $mysqli->prepare('UPDATE buyers SET name=? WHERE id=?');
      $u->bind_param('si', $newName, $buyerId);
      if (!$u->execute()) { $errorMsg = 'Failed to update name.'; }
      $u->close();
      if ($errorMsg === '') {
        $buyer['name'] = $newName;
        $_SESSION['name'] = $newName; // keep session in sync
      }
    }

    // Phone uniqueness check across buyers and sellers (always validate)
    if ($errorMsg === '') {
      $existsInBuyers = false; $existsInSellers = false;
      // Check in buyers excluding self
      if ($chk = $mysqli->prepare("SELECT 1 FROM buyers WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone,' ',''),'-',''),'+',''),'(',''),')','') = ? AND id <> ? LIMIT 1")) {
        $chk->bind_param('si', $normalizedPhone, $buyerId);
        $chk->execute();
        $chk->store_result();
        $existsInBuyers = $chk->num_rows > 0;
        $chk->close();
      } else {
        $errorMsg = 'Failed to validate phone (buyers).';
      }

      // Check in sellers
      if ($errorMsg === '') {
        if ($chk2 = $mysqli->prepare("SELECT 1 FROM sellers WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone,' ',''),'-',''),'+',''),'(',''),')','') = ? LIMIT 1")) {
          $chk2->bind_param('s', $normalizedPhone);
          $chk2->execute();
          $chk2->store_result();
          $existsInSellers = $chk2->num_rows > 0;
          $chk2->close();
        } else {
          $errorMsg = 'Failed to validate phone (sellers).';
        }
      }

      if ($errorMsg === '') {
        if ($existsInBuyers || $existsInSellers) {
          $errorMsg = 'Phone number already in use.';
        } else {
          // Update phone only if different after normalization
          if (normalize_phone($buyer['phone']) !== $normalizedPhone) {
            $up = $mysqli->prepare('UPDATE buyers SET phone=? WHERE id=?');
            $up->bind_param('si', $normalizedPhone, $buyerId);
            if ($up->execute()) {
              $buyer['phone'] = $normalizedPhone;
              if ($successMsg === '') { $successMsg = 'Profile updated.'; }
            } else {
              $errorMsg = 'Failed to update phone.';
            }
            $up->close();
          }
        }
      }
    }

    // Handle password change if fields provided (no current password required)
    if ($errorMsg === '' && ($newPass !== '' || $newPass2 !== '')) {
      if ($newPass === '' || $newPass2 === '') {
        $errorMsg = 'Please fill both password fields to change password.';
      } elseif (strlen($newPass) < 1) {
        $errorMsg = 'New password must be at least 1 character.';
      } elseif ($newPass !== $newPass2) {
        $errorMsg = 'New password and confirmation do not match.';
      } else {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $p = $mysqli->prepare('UPDATE buyers SET password=? WHERE id=?');
        $p->bind_param('si', $hashed, $buyerId);
        if ($p->execute()) {
          $successMsg = 'Password updated successfully.';
          // refresh buyer row password hash
          $buyer['password'] = $hashed;
        } else {
          $errorMsg = 'Failed to update password.';
        }
        $p->close();
      }
    }

    if ($errorMsg === '' && $successMsg === '') {
      $successMsg = 'Profile updated.';
    }
  }
}

// Build Recently Viewed cars (up to 5)
$recentCars = [];
if (!empty($_SESSION['recently_viewed']) && is_array($_SESSION['recently_viewed'])) {
  $ids = array_values(array_unique(array_map('intval', $_SESSION['recently_viewed'])));
  $ids = array_slice($ids, 0, 5);
  if (!empty($ids)) {
    $idList = implode(',', $ids);
    $orderField = implode(',', $ids); // Thumbnail selection logic updated below
    $sql = "SELECT c.car_id, c.make, c.model, c.year, c.price,
                   COALESCE(ci1.image_path, ci2.image_path) AS thumb
            FROM cars c
            LEFT JOIN (
              SELECT ci.car_id, ci.image_path
              FROM car_images ci
              JOIN (
                SELECT car_id, MIN(image_id) AS min_id
                FROM car_images
                WHERE car_id IN ($idList) AND (is_thumbnail = 1 OR is_thumbnail = '1')
                GROUP BY car_id
              ) t ON t.car_id = ci.car_id AND t.min_id = ci.image_id
            ) ci1 ON ci1.car_id = c.car_id
            LEFT JOIN (
              SELECT ci.car_id, ci.image_path
              FROM car_images ci
              JOIN (
                SELECT car_id, MIN(image_id) AS min_id
                FROM car_images
                WHERE car_id IN ($idList)
                GROUP BY car_id
              ) t ON t.car_id = ci.car_id AND t.min_id = ci.image_id
            ) ci2 ON ci2.car_id = c.car_id
            WHERE c.car_id IN ($idList)
            ORDER BY FIELD(c.car_id, $orderField)";
    if ($res = $mysqli->query($sql)) {
      while ($row = $res->fetch_assoc()) { $recentCars[] = $row; }
      $res->free();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <!-- HEADER -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">My Profile</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="car_view.php" class="hover:underline">Listings</a></li>
          <li><a href="buyer_profile.php" class="underline font-semibold">Profile</a></li>
          <!-- Fold-down menu -->
          <li class="relative" id="moreMenu">
            <button id="moreBtn" class="inline-flex items-center gap-1 px-3 py-1 bg-white bg-opacity-10 hover:bg-opacity-20 rounded">
              <span>More</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div id="morePanel" class="hidden absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
              <a href="analysis.php" class="block px-4 py-2 hover:bg-gray-100">Analysis</a>
              <a href="saved_search.php" class="block px-4 py-2 hover:bg-gray-100">Saved</a>
              <a href="buyer_bookings.php" class="block px-4 py-2 hover:bg-gray-100">Bookings</a>
              <a href="#" class="block px-4 py-2 hover:bg-gray-100">About</a>
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

  <!-- MAIN -->
  <main class="container mx-auto flex-1 py-8 px-4">
    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
      <h2 class="text-2xl font-bold mb-4">Account Details</h2>
      <?php if ($errorMsg): ?>
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700 border border-red-200"><?php echo htmlspecialchars($errorMsg); ?></div>
      <?php endif; ?>
      <?php if ($successMsg): ?>
        <div class="mb-4 p-3 rounded bg-green-50 text-green-700 border border-green-200"><?php echo htmlspecialchars($successMsg); ?></div>
      <?php endif; ?>
      <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="md:col-span-2">
          <label class="block text-sm text-gray-600 mb-1">Name</label>
          <input name="name" type="text" value="<?php echo htmlspecialchars($buyer['name'] ?? ''); ?>" class="w-full p-2 border rounded" required />
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">Email</label>
          <input type="email" value="<?php echo htmlspecialchars($buyer['email'] ?? ''); ?>" class="w-full p-2 border rounded bg-gray-100" readonly />
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">Phone</label>
          <input name="phone" type="tel" value="<?php echo htmlspecialchars($buyer['phone'] ?? ''); ?>" class="w-full p-2 border rounded" required />
        </div>
        <div class="md:col-span-2 mt-2">
          <h3 class="font-semibold text-gray-800 mb-2">Change Password</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm text-gray-600 mb-1">New Password</label>
              <input name="new_password" type="password" class="w-full p-2 border rounded" placeholder="At least 6 chars" />
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Confirm Password</label>
              <input name="confirm_password" type="password" class="w-full p-2 border rounded" placeholder="Repeat new password" />
            </div>
          </div>
        </div>
        <div class="md:col-span-2 mt-4">
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Save Changes</button>
        </div>
      </form>
      <div class="mt-6">
        <a href="main.php" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Back to Home</a>
      </div>
    </div>

    <!-- RECENTLY VIEWED SECTION -->
    <div class="max-w-6xl mx-auto mt-8 px-1">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold">Recently Viewed</h3>
        <a href="car_view.php" class="text-blue-600 hover:underline">Browse all</a>
      </div>
      <?php if (!empty($recentCars)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
          <?php foreach ($recentCars as $rc): ?>
            <a href="car_details_view.php?car_id=<?php echo (int)$rc['car_id']; ?>" class="block bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
              <div class="bg-gray-200 w-full aspect-[4/3] overflow-hidden">
                <?php if (!empty($rc['thumb'])): ?>
                  <img src="<?php echo htmlspecialchars($rc['thumb']); ?>" alt="<?php echo htmlspecialchars($rc['make'].' '.$rc['model']); ?>" class="w-full h-full object-cover" />
                <?php else: ?>
                  <div class="w-full h-full flex items-center justify-center text-gray-400">No image</div>
                <?php endif; ?>
              </div>
              <div class="p-3">
                <div class="font-semibold truncate"><?php echo htmlspecialchars($rc['make'].' '.$rc['model']); ?></div>
                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($rc['year']); ?></div>
                <div class="text-red-600 font-bold">RM <?php echo number_format((float)$rc['price'], 2); ?></div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="text-gray-600">No cars viewed yet. Explore our <a class="text-blue-600 hover:underline" href="car_view.php">listings</a>.</div>
      <?php endif; ?>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white p-4">
    <div class="container mx-auto text-center">
      <p>&copy; <?php echo date('Y'); ?> MyCar (FYP). All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
