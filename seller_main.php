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

// ===== Predefined dropdown options =====
$fuels = ['Gasoline','Diesel','Hybrid','Electric'];
$driveSystems = ['FWD','RWD','AWD','4WD'];
// Expanded makes and models
$makes = ['Toyota','Honda','Mazda','BMW','Mercedes','Nissan','Ford','Volkswagen','Hyundai','Kia','Audi','Chevrolet','Subaru','Mitsubishi','Peugeot','Renault','Volvo','Suzuki','Lexus','Jeep'];
$modelsByMake = [
  'Toyota'      => ['Corolla','Camry','Yaris','Vios','Hilux','Fortuner','RAV4'],
  'Honda'       => ['Civic','City','Accord','CR-V','Jazz','HR-V'],
  'Mazda'       => ['CX-5','Mazda3','Mazda6','CX-3','BT-50'],
  'BMW'         => ['X5','3 Series','5 Series','X3','1 Series'],
  'Mercedes'    => ['C-Class','E-Class','GLC','A-Class','S-Class'],
  'Nissan'      => ['Almera','X-Trail','Navara','Serena','Sylphy'],
  'Ford'        => ['Focus','Ranger','Fiesta','Everest','Mustang'],
  'Volkswagen'  => ['Golf','Polo','Passat','Tiguan','Jetta'],
  'Hyundai'     => ['Elantra','Tucson','Santa Fe','Accent','Ioniq'],
  'Kia'         => ['Cerato','Sportage','Sorento','Picanto','Optima'],
  'Audi'        => ['A3','A4','A6','Q5','Q7'],
  'Chevrolet'   => ['Cruze','Captiva','Colorado','Spark','Malibu'],
  'Subaru'      => ['Forester','XV','Impreza','Outback','Legacy'],
  'Mitsubishi'  => ['Triton','Outlander','Lancer','Xpander','ASX'],
  'Peugeot'     => ['208','3008','5008','2008','508'],
  'Renault'     => ['Captur','Koleos','Megane','Fluence','Clio'],
  'Volvo'       => ['XC40','XC60','XC90','S60','V40'],
  'Suzuki'      => ['Swift','Vitara','Ertiga','Jimny','Ciaz'],
  'Lexus'       => ['RX','NX','ES','IS','UX'],
  'Jeep'        => ['Wrangler','Cherokee','Compass','Renegade','Grand Cherokee']
];
$transmissions = ['AT','Manual','CVT','DCT'];

// ===== ADD CAR =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
  // Car details fields (can be null)
  $color        = !empty($_POST['color']) ? $mysqli->real_escape_string($_POST['color']) : null;
  $horsepower   = !empty($_POST['horsepower']) ? intval($_POST['horsepower']) : null;
  $engine_code  = !empty($_POST['engine_code']) ? $mysqli->real_escape_string($_POST['engine_code']) : null;
  $gear_numbers = !empty($_POST['gear_numbers']) ? intval($_POST['gear_numbers']) : null;
  $wheel_size   = !empty($_POST['wheel_size']) ? $mysqli->real_escape_string($_POST['wheel_size']) : null;
  $seller_note  = !empty($_POST['seller_note']) ? $mysqli->real_escape_string($_POST['seller_note']) : null;
  $variant         = $mysqli->real_escape_string($_POST['variant']);
  $make            = $mysqli->real_escape_string($_POST['make']);
  $model           = $mysqli->real_escape_string($_POST['model']);
  $year            = intval($_POST['year']);
  $engine_capacity = $mysqli->real_escape_string($_POST['engine_capacity']);
  $mileage         = intval($_POST['mileage']);
  $transmission    = $mysqli->real_escape_string($_POST['transmission']);
  $price           = floatval($_POST['price']);
  $fuel            = $mysqli->real_escape_string($_POST['fuel']);
  $drive_system    = $mysqli->real_escape_string($_POST['drive_system']);
  $doors           = intval($_POST['doors']);

  $stmt = $mysqli->prepare("INSERT INTO cars 
    (seller_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
  $stmt->bind_param("isssisisdssi",
   $seller_id, $make, $model, $variant, $year, $engine_capacity, $mileage, $transmission, $price, $fuel, $drive_system, $doors);
    if ($stmt->execute()) {
        $car_id = $stmt->insert_id;
        // handle multiple image upload
        if (!empty($_FILES['car_images']['name'][0])) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            foreach ($_FILES['car_images']['tmp_name'] as $idx => $tmpName) {
                $name = basename($_FILES['car_images']['name'][$idx]);
                $target = $uploadDir . time() . "_" . $name;
                if (move_uploaded_file($tmpName, $target)) {
                    $relativePath = 'uploads/' . basename($target);
                    $mysqli->query("INSERT INTO car_images (car_id, image_path) VALUES ($car_id, '$relativePath')");
                }
            }
        }
        $success = "Car successfully added!";
    } else {
        $error = "Failed to add car.";
    }
    $stmt->close();
}

// ===== DELETE CAR =====
if (isset($_GET['delete'])) {
    $car_id = intval($_GET['delete']);
    $res = $mysqli->query("SELECT image_path FROM car_images WHERE car_id=$car_id");
    while ($img = $res->fetch_assoc()) {
        $file = __DIR__ . '/' . $img['image_path'];
        if (file_exists($file)) unlink($file);
    }
    $mysqli->query("DELETE FROM car_images WHERE car_id=$car_id");
    $mysqli->query("DELETE FROM cars WHERE car_id=$car_id AND seller_id=$seller_id");
    header("Location: seller_main.php");
    exit();
}

// ===== EDIT CAR =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_car'])) {
  // Car details fields (can be null)
  $color        = !empty($_POST['color']) ? $mysqli->real_escape_string($_POST['color']) : null;
  $horsepower   = !empty($_POST['horsepower']) ? intval($_POST['horsepower']) : null;
  $engine_code  = !empty($_POST['engine_code']) ? $mysqli->real_escape_string($_POST['engine_code']) : null;
  $gear_numbers = !empty($_POST['gear_numbers']) ? intval($_POST['gear_numbers']) : null;
  $wheel_size   = !empty($_POST['wheel_size']) ? $mysqli->real_escape_string($_POST['wheel_size']) : null;
  $seller_note  = !empty($_POST['seller_note']) ? $mysqli->real_escape_string($_POST['seller_note']) : null;
  $variant         = $mysqli->real_escape_string($_POST['variant']);
  $car_id          = intval($_POST['car_id']);
  $make            = $mysqli->real_escape_string($_POST['make']);
  $model           = $mysqli->real_escape_string($_POST['model']);
  $year            = intval($_POST['year']);
  $engine_capacity = $mysqli->real_escape_string($_POST['engine_capacity']);
  $mileage         = intval($_POST['mileage']);
  $transmission    = $mysqli->real_escape_string($_POST['transmission']);
  $price           = floatval($_POST['price']);
  $fuel            = $mysqli->real_escape_string($_POST['fuel']);
  $drive_system    = $mysqli->real_escape_string($_POST['drive_system']);
  $doors           = intval($_POST['doors']);

  $stmt = $mysqli->prepare("UPDATE cars 
    SET make=?, model=?, variant=?, year=?, engine_capacity=?, mileage=?, transmission=?, price=?, fuel=?, drive_system=?, doors=? 
    WHERE car_id=? AND seller_id=?");
  $stmt->bind_param("sssisisdssiii", 
    $make, $model, $variant, $year, $engine_capacity, $mileage, $transmission, $price, $fuel, $drive_system, $doors, $car_id, $seller_id);
  $stmt->execute();
  // Update thumbnail selection
  if (isset($_POST['thumbnail_image_id'])) {
    $thumb_id = intval($_POST['thumbnail_image_id']);
    $mysqli->query("UPDATE car_images SET is_thumbnail=0 WHERE car_id=$car_id");
    $mysqli->query("UPDATE car_images SET is_thumbnail=1 WHERE image_id=$thumb_id AND car_id=$car_id");
  }
  $stmt->close();
  header("Location: seller_main.php");
  exit();
}

// ===== Fetch cars =====
// Remove color from SELECT and variable list
$cars = $mysqli->prepare("SELECT car_id, make, model, variant, year, engine_capacity, mileage, transmission, price, fuel, drive_system, doors 
  FROM cars WHERE seller_id = ?");
$cars->bind_param("i", $seller_id);
$cars->execute();
$carsResult = $cars->get_result();

// helper to fetch first image
function getFirstImage($mysqli, $car_id) {
  // Try to get thumbnail first
  $res = $mysqli->query("SELECT image_path FROM car_images WHERE car_id=$car_id AND is_thumbnail=1 LIMIT 1");
  if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    return $row['image_path'];
  }
  // fallback to first image
  $res = $mysqli->query("SELECT image_path FROM car_images WHERE car_id=$car_id LIMIT 1");
  if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    return $row['image_path'];
  }
  return 'https://via.placeholder.com/200x150?text=No+Image';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seller Dashboard</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<script>
// JS for modal toggling
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}

// JS for dynamic models
const modelsByMake = <?php echo json_encode($modelsByMake); ?>;
function updateModelOptions(makeSelect, modelSelectId, selectedModel='') {
    const make = makeSelect.value;
    const modelSelect = document.getElementById(modelSelectId);
    modelSelect.innerHTML = '';
    if(modelsByMake[make]) {
        modelsByMake[make].forEach(m=>{
            const opt=document.createElement('option');
            opt.value=m; opt.text=m;
            if(m===selectedModel) opt.selected=true;
            modelSelect.appendChild(opt);
        });
    } else {
        const opt=document.createElement('option');
        opt.text='Select Model'; opt.value='';
        modelSelect.appendChild(opt);
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

  <button onclick="toggleModal('addCarModal')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 mb-4">
    + Add Car
  </button>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php while($row = $carsResult->fetch_assoc()): ?>
      <div class="bg-white rounded-xl shadow hover:shadow-lg p-4">
        <img src="<?php echo htmlspecialchars(getFirstImage($mysqli, $row['car_id'])); ?>" class="w-full h-40 object-cover rounded" alt="Car">
        <h2 class="text-lg font-bold mt-2"><?php echo htmlspecialchars($row['make']." ".$row['model']); ?></h2>
        <p class="text-sm text-gray-600"><?php echo $row['year']." | ".$row['engine_capacity']." | ".$row['mileage']." km"; ?></p>
        <p class="text-red-600 font-bold">RM <?php echo number_format($row['price'],2); ?></p>
        <div class="flex gap-2 mt-2">
          <a href="car_details.php?car_id=<?php echo $row['car_id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded">View</a>
          <button onclick="toggleModal('editModal<?php echo $row['car_id']; ?>')" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
          <a href="seller_main.php?delete=<?php echo $row['car_id']; ?>" onclick="return confirm('Delete this car?')" class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
        </div>
      </div>

      <!-- Edit Modal -->
      <div id="editModal<?php echo $row['car_id']; ?>" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">
          <h2 class="text-xl font-bold mb-4">Edit Car</h2>
          <form method="post" class="grid grid-cols-1 gap-4">
            <input type="text" name="variant" value="<?php echo isset($row['variant']) ? htmlspecialchars($row['variant']) : ''; ?>" placeholder="Type variant (e.g. 320i, 2.0)" class="border p-2 rounded">
            <input type="hidden" name="car_id" value="<?php echo $row['car_id']; ?>">
            <input type="hidden" name="edit_car" value="1">

            <select name="make" onchange="updateModelOptions(this,'modelSelect<?php echo $row['car_id']; ?>')" required class="border p-2 rounded">
              <option value="">Select Make</option>
              <?php foreach($makes as $m): ?>
                <option value="<?php echo $m; ?>" <?php if($row['make']==$m) echo 'selected'; ?>><?php echo $m; ?></option>
              <?php endforeach; ?>
            </select>

            <select id="modelSelect<?php echo $row['car_id']; ?>" name="model" required class="border p-2 rounded">
              <!-- will be filled by JS onload -->
            </select>

            <input type="number" name="year" value="<?php echo htmlspecialchars($row['year']); ?>" required class="border p-2 rounded">
            <select name="engine_capacity" required class="border p-2 rounded">
              <option value="">Select Engine Capacity</option>
              <?php for($ec=0.6;$ec<=8.0;$ec+=0.1): ?>
                <option value="<?php echo number_format($ec,1); ?>" <?php if(floatval($row['engine_capacity'])==round($ec,1)) echo 'selected'; ?>><?php echo number_format($ec,1); ?> L</option>
              <?php endfor; ?>
            </select>
            <input type="number" name="mileage" value="<?php echo htmlspecialchars($row['mileage']); ?>" required class="border p-2 rounded">

            <select name="transmission" required class="border p-2 rounded">
              <?php foreach($transmissions as $t): ?>
                <option value="<?php echo $t; ?>" <?php if($row['transmission']==$t) echo 'selected'; ?>><?php echo $t; ?></option>
              <?php endforeach; ?>
            </select>

            <select name="fuel" required class="border p-2 rounded">
              <option value="">Select Fuel</option>
              <?php foreach($fuels as $f): ?>
                <option value="<?php echo $f; ?>" <?php if(isset($row['fuel']) && $row['fuel']==$f) echo 'selected'; ?>><?php echo $f; ?></option>
              <?php endforeach; ?>
            </select>

            <select name="drive_system" required class="border p-2 rounded">
              <option value="">Select Drive System</option>
              <?php foreach($driveSystems as $ds): ?>
                <option value="<?php echo $ds; ?>" <?php if(isset($row['drive_system']) && $row['drive_system']==$ds) echo 'selected'; ?>><?php echo $ds; ?></option>
              <?php endforeach; ?>
            </select>

            <select name="doors" required class="border p-2 rounded">
              <option value="">Select Doors</option>
              <?php for($d=2;$d<=5;$d++): ?>
                <option value="<?php echo $d; ?>" <?php if(isset($row['doors']) && $row['doors']==$d) echo 'selected'; ?>><?php echo $d; ?>D</option>
              <?php endfor; ?>
            </select>

            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required class="border p-2 rounded">

            <!-- Thumbnail selection -->
            <?php
            $imgRes = $mysqli->query("SELECT image_id, image_path, is_thumbnail FROM car_images WHERE car_id={$row['car_id']}");
            ?>
            <div>
              <label class="font-bold">Select Thumbnail:</label>
              <div class="flex gap-2 mt-2">
                <?php while($img = $imgRes->fetch_assoc()): ?>
                  <label class="flex flex-col items-center">
                    <img src="<?php echo htmlspecialchars($img['image_path']); ?>" class="w-16 h-16 object-cover rounded border mb-1">
                    <input type="radio" name="thumbnail_image_id" value="<?php echo $img['image_id']; ?>" <?php if($img['is_thumbnail']) echo 'checked'; ?>>
                  </label>
                <?php endwhile; ?>
              </div>
            </div>

            <div class="flex justify-end gap-2">
              <button type="button" onclick="toggleModal('editModal<?php echo $row['car_id']; ?>')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
              <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Save</button>
            </div>
          </form>
        </div>
      </div>

      <script>
        // initialize model dropdown for edit modals
        document.addEventListener('DOMContentLoaded',function(){
            const makeSel=document.querySelector("#editModal<?php echo $row['car_id']; ?> select[name='make']");
            updateModelOptions(makeSel,'modelSelect<?php echo $row['car_id']; ?>','<?php echo $row['model']; ?>');
        });
      </script>
    <?php endwhile; ?>
  </div>
</div>

<!-- Add Car Modal -->
<div id="addCarModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
  <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg">
    <h2 class="text-xl font-bold mb-4">Add a New Car</h2>
    <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 gap-4">
  <input type="text" name="variant" placeholder="Type variant (e.g. 320i, 2.0)" class="border p-2 rounded">
      <input type="hidden" name="add_car" value="1">

      <select name="make" onchange="updateModelOptions(this,'modelSelectAdd')" required class="border p-2 rounded">
        <option value="">Select Make</option>
        <?php foreach($makes as $m): ?>
          <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
        <?php endforeach; ?>
      </select>

      <select id="modelSelectAdd" name="model" required class="border p-2 rounded">
        <option value="">Select Model</option>
      </select>

      <input type="number" name="year" placeholder="Year" required class="border p-2 rounded">
      <select name="engine_capacity" required class="border p-2 rounded">
        <option value="">Select Engine Capacity</option>
        <?php for($ec=0.6;$ec<=8.0;$ec+=0.1): ?>
          <option value="<?php echo number_format($ec,1); ?>"><?php echo number_format($ec,1); ?> L</option>
        <?php endfor; ?>
      </select>
      <input type="number" name="mileage" placeholder="Mileage (km)" required class="border p-2 rounded">

      <select name="transmission" required class="border p-2 rounded">
        <option value="">Select Transmission</option>
        <?php foreach($transmissions as $t): ?>
          <option value="<?php echo $t; ?>"><?php echo $t; ?></option>
        <?php endforeach; ?>
        </select>

      <select name="fuel" required class="border p-2 rounded">
        <option value="">Select Fuel</option>
        <?php foreach($fuels as $f): ?>
          <option value="<?php echo $f; ?>"><?php echo $f; ?></option>
        <?php endforeach; ?>
      </select>

      <select name="drive_system" required class="border p-2 rounded">
        <option value="">Select Drive System</option>
        <?php foreach($driveSystems as $ds): ?>
          <option value="<?php echo $ds; ?>"><?php echo $ds; ?></option>
        <?php endforeach; ?>
      </select>

      <select name="doors" required class="border p-2 rounded">
        <option value="">Select Doors</option>
        <?php for($d=2;$d<=5;$d++): ?>
          <option value="<?php echo $d; ?>"><?php echo $d; ?>D</option>
        <?php endfor; ?>
      </select>

      <input type="number" step="0.01" name="price" placeholder="Price" required class="border p-2 rounded">
      <input type="file" name="car_images[]" multiple class="border p-2 rounded">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="toggleModal('addCarModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Add Car</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
