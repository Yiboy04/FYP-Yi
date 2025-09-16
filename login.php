<?php
// login.php
session_start();

// --- Database connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fyp"; // your database

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Handle login ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $redirect = ""; // store where to redirect
    $userFound = false;

    // 1️⃣ Try buyers table first
    $stmt = $conn->prepare("SELECT id, password, name FROM buyers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword, $name);
        $stmt->fetch();
        if (password_verify($pass, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email']   = $email;
            $_SESSION['name']    = $name;
            $_SESSION['role']    = 'buyer';
            $userFound = true;
            $redirect = "main.php"; // buyers go here
        }
    }
    $stmt->close();

    // 2️⃣ If not buyer, try sellers table
    if (!$userFound) {
        $stmt = $conn->prepare("SELECT id, password, name FROM sellers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword, $name);
            $stmt->fetch();
            if (password_verify($pass, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['email']   = $email;
                $_SESSION['name']    = $name;
                $_SESSION['role']    = 'seller';
                $userFound = true;
                $redirect = "seller_main.php"; // sellers go here
            }
        }
        $stmt->close();
    }

    if ($userFound) {
        header("Location: $redirect");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Used Car Website</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
    }
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
        <a href="#" class="text-sm text-gray-600 hover:text-red-600">Help</a>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex flex-1 items-center justify-center">
    <div class="w-full max-w-md bg-white border rounded-xl shadow-md p-8">
      <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Member Login</h2>

      <?php if (!empty($error)) echo "<p class='text-red-600 text-sm mb-4 text-center'>$error</p>"; ?>

      <form method="POST" class="space-y-4">
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
        <button type="submit" 
          class="w-full bg-red-600 text-white py-2 rounded-md font-semibold hover:bg-red-700 transition">
          Login
        </button>
      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Don’t have an account?
        <a href="register.php" class="text-red-600 hover:underline">Sign Up</a>
      </p>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-200 text-center py-4 text-sm text-gray-600">
    &copy; 2025 Great Value Car. All Rights Reserved.
  </footer>

</body>
</html>
