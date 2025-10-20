<?php
// register.php
session_start();

// --- Database connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fyp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper to normalize phone numbers to digits-only
function normalize_phone($p) {
  return preg_replace('/\D+/', '', trim((string)$p));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
  $phone    = $_POST['phone'];
  $phoneNorm = normalize_phone($phone);
    $role     = $_POST['role']; // buyer or seller

    // --- Validate email format ---
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "This is not a valid email address.";
  } else {
        // Check duplicate email in BOTH tables
        if ($role == 'buyer') {
            $check = $conn->prepare("SELECT id FROM buyers WHERE email = ?");
        } else {
            $check = $conn->prepare("SELECT id FROM sellers WHERE email = ?");
        }
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "This email has already been taken.";
        }
        $check->close();
    }

  // --- Validate phone (not empty) and check duplicates across buyers & sellers ---
  if (empty($error)) {
    if ($phoneNorm === '') {
      $error = 'Phone cannot be empty.';
    } else {
      // Check in buyers
      if ($stmtChk = $conn->prepare("SELECT 1 FROM buyers WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone,' ',''),'-',''),'+',''),'(',''),')','') = ? LIMIT 1")) {
        $stmtChk->bind_param('s', $phoneNorm);
        $stmtChk->execute();
        $stmtChk->store_result();
        $existsInBuyers = $stmtChk->num_rows > 0;
        $stmtChk->close();
      } else {
        $error = 'Failed to validate phone (buyers).';
      }

      // Check in sellers
      if (empty($error)) {
        if ($stmtChk2 = $conn->prepare("SELECT 1 FROM sellers WHERE REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(phone,' ',''),'-',''),'+',''),'(',''),')','') = ? LIMIT 1")) {
          $stmtChk2->bind_param('s', $phoneNorm);
          $stmtChk2->execute();
          $stmtChk2->store_result();
          $existsInSellers = $stmtChk2->num_rows > 0;
          $stmtChk2->close();
        } else {
          $error = 'Failed to validate phone (sellers).';
        }
      }

      if (empty($error) && (($existsInBuyers ?? false) || ($existsInSellers ?? false))) {
        $error = 'This phone number has already been taken.';
      }
    }
  }

    // --- Check password confirmation ---
    if (empty($error) && $password !== $confirm) {
        $error = "Passwords do not match!";
    }

    // --- Register user if no error ---
    if (empty($error)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($role == 'buyer') {
      $stmt = $conn->prepare("INSERT INTO buyers (name, email, password, phone) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phoneNorm);
        } else {
            // seller registration: car_id will be NULL initially
      $stmt = $conn->prepare("INSERT INTO sellers (name, email, password, phone, car_id) VALUES (?, ?, ?, ?, NULL)");
      $stmt->bind_param("ssss", $name, $email, $hashedPassword, $phoneNorm);
        }

        if ($stmt->execute()) {
            echo "<script>
                alert('✅ Registration successful! Please log in.');
                window.location.href='login.php';
            </script>";
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Used Car Website</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Helvetica Neue', Arial, sans-serif; }
  </style>
</head>
<body class="min-h-screen bg-gray-100 flex flex-col">

  <!-- Header -->
  <header class="bg-white shadow-md">
    <div class="max-w-6xl mx-auto flex items-center justify-between py-4 px-6">
      <div class="flex items-center space-x-2">
        <img src="logo.png" alt="Logo" class="h-10">
        <h1 class="text-2xl font-bold text-red-600">GVC</h1>
      </div>
      <nav>
        <a href="login.php" class="text-sm text-gray-600 hover:text-red-600">Login</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex flex-1 items-center justify-center">
    <div class="w-full max-w-lg bg-white border rounded-xl shadow-md p-8">
      <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Create an Account</h2>

      <?php if (!empty($error)) echo "<p class='text-red-600 text-sm mb-4 text-center'>$error</p>"; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" id="name" name="name" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="email" name="email" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" name="password" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>
        <div>
          <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
          <input type="text" id="phone" name="phone" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Account Type</label>
          <select name="role" required 
            class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-400 focus:outline-none">
            <option value="buyer">Buyer</option>
            <option value="seller">Seller</option>
          </select>
        </div>

        <button type="submit" 
          class="w-full bg-red-600 text-white py-2 rounded-md font-semibold hover:bg-red-700 transition">
          Sign Up
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <a href="login.php" class="text-red-600 hover:underline">Login</a>
      </p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-200 text-center py-4 text-sm text-gray-600">
    &copy; 2025 Great Value Car. All Rights Reserved.
  </footer>

</body>
</html>
