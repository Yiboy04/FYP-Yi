<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

$seller_id = $_SESSION['user_id']; // logged-in seller ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $make            = $mysqli->real_escape_string($_POST['make']);
    $model           = $mysqli->real_escape_string($_POST['model']);
    $year            = intval($_POST['year']);
    $engine_capacity = $mysqli->real_escape_string($_POST['engine_capacity']);
    $transmission    = $mysqli->real_escape_string($_POST['transmission']);
    $color           = $mysqli->real_escape_string($_POST['color']);
    $price           = floatval($_POST['price']);

    $stmt = $mysqli->prepare("INSERT INTO cars (seller_id, make, model, year, engine_capacity, transmission, color, price) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ississss", $seller_id, $make, $model, $year, $engine_capacity, $transmission, $color, $price);
    if ($stmt->execute()) {
        $success = "Car successfully added!";
    } else {
        $error = "Failed to add car.";
    }
    $stmt->close();
}

$cars = $mysqli->prepare("SELECT make, model, year, engine_capacity, transmission, color, price FROM cars WHERE seller_id = ?");
$cars->bind_param("i", $seller_id);
$cars->execute();
$carsResult = $cars->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seller Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<script>
function toggleModal() {
    const modal = document.getElementById('addCarModal');
    modal.classList.toggle('hidden');
}

// models for each make
const makeModels = {
  "Toyota": ["Corolla", "Camry", "Yaris"],
  "Honda": ["Civic", "Accord", "City"],
  "Mazda": ["CX-5", "Mazda3", "Mazda6"],
  "BMW": ["320i", "X5", "M3"]
};

function updateModels() {
  const makeSelect = document.getElementById('make');
  const modelSelect = document.getElementById('model');
  const selectedMake = makeSelect.value;

  // clear old options
  modelSelect.innerHTML = '<option value="">Select Model</option>';

  if (makeModels[selectedMake]) {
    makeModels[selectedMake].forEach(function(model) {
      const opt = document.createElement('option');
      opt.value = model;
      opt.textContent = model;
      modelSelect.appendChild(opt);
    });
  }
}
</script>
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Seller Dashboard</h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="seller_main.php" class="hover:underline">Dashboard</a></li>
        <li><a href="logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>

<div class="container mx-auto mt-8">
  <?php if(!empty($success)) echo "<p class='text-green-600 mb-4'>$success</p>"; ?>
  <?php if(!empty($error)) echo "<p class='text-red-600 mb-4'>$error</p>"; ?>

  <!-- Add Car Button -->
  <button onclick="toggleModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 mb-4">
    + Add Car
  </button>

  <!-- Table of seller's cars -->
  <div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-bold mb-4">My Cars</h2>
    <table class="w-full border">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 border">Make</th>
          <th class="p-2 border">Model</th>
          <th class="p-2 border">Year</th>
          <th class="p-2 border">Engine Capacity</th>
          <th class="p-2 border">Transmission</th>
          <th class="p-2 border">Color</th>
          <th class="p-2 border">Price</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $carsResult->fetch_assoc()): ?>
          <tr>
            <td class="p-2 border"><?php echo htmlspecialchars($row['make']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['model']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['year']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['engine_capacity']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['transmission']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['color']); ?></td>
            <td class="p-2 border"><?php echo htmlspecialchars($row['price']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal -->
<div id="addCarModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
  <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">
    <h2 class="text-xl font-bold mb-4">Add a New Car</h2>
    <form method="post" class="grid grid-cols-1 gap-4">
      <div>
        <label class="block text-sm font-medium">Make</label>
        <select name="make" id="make" onchange="updateModels()" required class="w-full border p-2 rounded">
          <option value="">Select Make</option>
          <option value="Toyota">Toyota</option>
          <option value="Honda">Honda</option>
          <option value="Mazda">Mazda</option>
          <option value="BMW">BMW</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Model</label>
        <select name="model" id="model" required class="w-full border p-2 rounded">
          <option value="">Select Model</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Year</label>
        <select name="year" required class="w-full border p-2 rounded">
          <option value="">Select Year</option>
          <?php for($y=date("Y"); $y>=2000; $y--): ?>
            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Engine Capacity</label>
        <input type="text" name="engine_capacity" required class="w-full border p-2 rounded" placeholder="e.g. 1.8L">
      </div>
      <div>
        <label class="block text-sm font-medium">Transmission</label>
        <select name="transmission" required class="w-full border p-2 rounded">
          <option value="">Select Transmission</option>
          <option value="Automatic">Automatic</option>
          <option value="Manual">Manual</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Color</label>
        <select name="color" required class="w-full border p-2 rounded">
          <option value="">Select Color</option>
          <option value="White">White</option>
          <option value="Black">Black</option>
          <option value="Red">Red</option>
          <option value="Blue">Blue</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium">Price</label>
        <input type="number" step="0.01" name="price" required class="w-full border p-2 rounded">
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="toggleModal()" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Add Car</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
