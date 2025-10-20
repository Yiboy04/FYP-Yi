<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}

if (!isset($_GET['car_id'])) {
    die("No car selected.");
}
$car_id = intval($_GET['car_id']);

// fetch car info (join seller if you like)
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die("Car not found.");

// fetch all images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=?");
$imgQ->bind_param("i", $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while ($row = $resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();

// fetch car_details (includes optional fields)
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param("i", $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();

// If no car_details row exists, insert one
if (!$car_details) {
    $ins = $mysqli->prepare("INSERT INTO car_details (car_id) VALUES (?)");
    $ins->bind_param("i", $car_id);
    $ins->execute();
    $ins->close();
    // Re-fetch after insert
  $details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
    $details->bind_param("i", $car_id);
    $details->execute();
    $car_details = $details->get_result()->fetch_assoc();
    $details->close();
}

// handle car_details update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_details'])) {
    $color = $mysqli->real_escape_string($_POST['color']);
    $horsepower = intval($_POST['horsepower']);
    $engine_code = $mysqli->real_escape_string($_POST['engine_code']);
    $gear_numbers = intval($_POST['gear_numbers']);
    $front_wheel_size = $mysqli->real_escape_string($_POST['front_wheel_size']);
    $rear_wheel_size = $mysqli->real_escape_string($_POST['rear_wheel_size']);
    $torque = $mysqli->real_escape_string($_POST['torque']);
    // Validate condition (nullable)
    $allowed_conditions = ['New','Reconditioned','Used','Certified'];
    $car_condition = isset($_POST['car_condition']) && in_array($_POST['car_condition'], $allowed_conditions)
      ? $_POST['car_condition']
      : null;
    if ($car_condition !== null) {
      $car_condition = $mysqli->real_escape_string($car_condition);
    }
    // Validate car type (nullable)
    $allowed_types = ['Sedan','SUV','Pickup','Coupe','Hatchback','Wagon','Convertible','Van','MPV','Crossover','Sports','Other'];
    $car_type = isset($_POST['car_type']) && in_array($_POST['car_type'], $allowed_types) ? $_POST['car_type'] : null;
    if ($car_type !== null) {
      $car_type = $mysqli->real_escape_string($car_type);
    }

    $stmt = $mysqli->prepare("UPDATE car_details SET color=?, horsepower=?, engine_code=?, gear_numbers=?, front_wheel_size=?, rear_wheel_size=?, torque=?, car_type=?, car_condition=? WHERE car_id=?");
    $stmt->bind_param("sisisssssi", $color, $horsepower, $engine_code, $gear_numbers, $front_wheel_size, $rear_wheel_size, $torque, $car_type, $car_condition, $car_id);
    $stmt->execute();
    $stmt->close();
    header("Location: car_details.php?car_id=$car_id");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
    $seller_note = $mysqli->real_escape_string($_POST['seller_note']);
    $stmt = $mysqli->prepare("UPDATE car_details SET seller_note=? WHERE car_id=?");
    $stmt->bind_param("si", $seller_note, $car_id);
    $stmt->execute();
    $stmt->close();
    header("Location: car_details.php?car_id=$car_id");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?> Details</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script>
function changeMain(src){
  document.getElementById('mainImage').src=src;
}
</script>
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Car Details</h1>
    <a href="seller_main.php" class="underline">Back</a>
  </div>
</header>

<main class="container mx-auto mt-8">
  <div class="bg-white rounded shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Left: images -->
      <div>
        <?php if(count($imgs)>0): ?>
          <img id="mainImage" src="<?php echo htmlspecialchars($imgs[0]); ?>" alt="Main Image" class="w-full h-80 object-cover rounded">
          <div class="flex gap-2 mt-2 overflow-x-auto">
            <?php foreach($imgs as $img): ?>
              <img onclick="changeMain('<?php echo htmlspecialchars($img); ?>')" src="<?php echo htmlspecialchars($img); ?>" class="w-20 h-20 object-cover rounded cursor-pointer border">
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400 rounded">No images</div>
        <?php endif; ?>
        <form method="post" class="mt-6">
          <label class="block font-semibold mb-2 text-blue-600" for="seller_note">Seller's Note</label>
          <textarea name="seller_note" id="seller_note" placeholder="Seller's Note" class="border p-3 rounded w-full h-32 resize-none mb-2"><?php echo htmlspecialchars($car_details['seller_note'] ?? ''); ?></textarea>
          <div class="flex justify-end">
            <button type="submit" name="update_note" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Note</button>
          </div>
        </form>
      </div>
      <!-- Right: details -->
      <div>
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h2>
        <div class="text-red-600 text-xl font-bold mb-4">RM<?php echo number_format($car['price'],2); ?></div>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-red-600">Overview</h3>
          <div class="grid grid-cols-2 gap-x-6 gap-y-2">
            <div><span class="font-semibold">Year:</span> <?php echo htmlspecialchars($car['year']); ?></div>
            <div><span class="font-semibold">Variant:</span> <?php echo htmlspecialchars($car['variant']); ?></div>
            <div><span class="font-semibold">Mileage:</span> <?php echo htmlspecialchars($car['mileage']); ?> km</div>
            <div><span class="font-semibold">Transmission:</span> <?php echo htmlspecialchars($car['transmission']); ?></div>
            <div><span class="font-semibold">Engine Capacity:</span> <?php echo htmlspecialchars($car['engine_capacity']); ?> L</div>
            <div><span class="font-semibold">Fuel:</span> <?php echo htmlspecialchars($car['fuel']); ?></div>
            <div><span class="font-semibold">Drive System:</span> <?php echo htmlspecialchars($car['drive_system']); ?></div>
            <div><span class="font-semibold">Doors:</span> <?php echo htmlspecialchars($car['doors']); ?>D</div>
                <?php if (!empty($car_details['car_condition'])): ?>
                  <div><span class="font-semibold">Condition:</span> <?php echo htmlspecialchars($car_details['car_condition']); ?></div>
                <?php endif; ?>
          </div>
        </div>
        <!-- Seller guidance notice -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 mb-4 rounded" role="alert">
          <div class="font-semibold mb-1">Tip for better visibility</div>
          <p class="text-sm">Car details can be left blank, but completing them (for example Colour, Condition, Wheel Size, etc.) helps buyers find your listing using filters and improves trust in your ad.</p>
        </div>

        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-blue-600">Car Details</h3>
          <form method="post" class="grid grid-cols-2 gap-4">
            <input type="text" name="color" value="<?php echo htmlspecialchars($car_details['color'] ?? ''); ?>" placeholder="Color" class="border p-2 rounded">
            <input type="number" name="horsepower" value="<?php echo htmlspecialchars($car_details['horsepower'] ?? ''); ?>" placeholder="Horsepower" class="border p-2 rounded">
            <input type="text" name="engine_code" value="<?php echo htmlspecialchars($car_details['engine_code'] ?? ''); ?>" placeholder="Engine Code" class="border p-2 rounded">
            <input type="number" name="gear_numbers" value="<?php echo htmlspecialchars($car_details['gear_numbers'] ?? ''); ?>" placeholder="Gear Numbers" class="border p-2 rounded">
            <input type="text" name="front_wheel_size" value="<?php echo htmlspecialchars($car_details['front_wheel_size'] ?? ''); ?>" placeholder="Front Wheel Size" class="border p-2 rounded">
            <input type="text" name="rear_wheel_size" value="<?php echo htmlspecialchars($car_details['rear_wheel_size'] ?? ''); ?>" placeholder="Rear Wheel Size" class="border p-2 rounded">
            <input type="text" name="torque" value="<?php echo htmlspecialchars($car_details['torque'] ?? ''); ?>" placeholder="Torque (e.g. 250 Nm)" class="border p-2 rounded">
            <select name="car_type" class="border p-2 rounded">
              <?php $type = $car_details['car_type'] ?? ''; ?>
              <option value="" <?php echo $type==='' ? 'selected' : ''; ?>>Car Type (optional)</option>
              <option value="Sedan" <?php echo $type==='Sedan' ? 'selected' : ''; ?>>Sedan</option>
              <option value="SUV" <?php echo $type==='SUV' ? 'selected' : ''; ?>>SUV</option>
              <option value="Pickup" <?php echo $type==='Pickup' ? 'selected' : ''; ?>>Pickup</option>
              <option value="Coupe" <?php echo $type==='Coupe' ? 'selected' : ''; ?>>Coupe</option>
              <option value="Hatchback" <?php echo $type==='Hatchback' ? 'selected' : ''; ?>>Hatchback</option>
              <option value="Wagon" <?php echo $type==='Wagon' ? 'selected' : ''; ?>>Wagon</option>
              <option value="Convertible" <?php echo $type==='Convertible' ? 'selected' : ''; ?>>Convertible</option>
              <option value="Van" <?php echo $type==='Van' ? 'selected' : ''; ?>>Van</option>
              <option value="MPV" <?php echo $type==='MPV' ? 'selected' : ''; ?>>MPV</option>
              <option value="Crossover" <?php echo $type==='Crossover' ? 'selected' : ''; ?>>Crossover</option>
              <option value="Sports" <?php echo $type==='Sports' ? 'selected' : ''; ?>>Sports</option>
              <option value="Other" <?php echo $type==='Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <select name="car_condition" class="border p-2 rounded">
              <?php $cond = $car_details['car_condition'] ?? ''; ?>
              <option value="" <?php echo $cond==='' ? 'selected' : ''; ?>>Condition (optional)</option>
              <option value="New" <?php echo $cond==='New' ? 'selected' : ''; ?>>New</option>
              <option value="Reconditioned" <?php echo $cond==='Reconditioned' ? 'selected' : ''; ?>>Reconditioned</option>
              <option value="Used" <?php echo $cond==='Used' ? 'selected' : ''; ?>>Used</option>
              <option value="Certified" <?php echo $cond==='Certified' ? 'selected' : ''; ?>>Certified</option>
            </select>
            <div class="col-span-2 flex justify-end">
              <button type="submit" name="update_details" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Details</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
</body>
</html>
