<?php
// car_details_view.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}
if (!isset($_GET['car_id'])) {
    die("No car selected.");
}
$car_id = intval($_GET['car_id']);
// fetch car info
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die("Car not found.");
// track recently viewed cars in session (latest first, max 5)
if (!isset($_SESSION['recently_viewed'])) $_SESSION['recently_viewed'] = [];
// remove if exists to reinsert at front
$_SESSION['recently_viewed'] = array_values(array_filter(
  $_SESSION['recently_viewed'],
  function($id) use ($car_id){ return intval($id) !== $car_id; }
));
array_unshift($_SESSION['recently_viewed'], $car_id);
if (count($_SESSION['recently_viewed']) > 5) {
  $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 5);
}
// fetch seller info (by seller_id on car)
$seller = null;
if (!empty($car['seller_id'])) {
  $s = $mysqli->prepare("SELECT id, name, email, phone FROM sellers WHERE id=?");
  $s->bind_param("i", $car['seller_id']);
  $s->execute();
  $seller = $s->get_result()->fetch_assoc();
  $s->close();
}
// Build WhatsApp link from seller phone (assumes MY country code 60 for local numbers starting with 0)
$waPhoneLink = null;
if (!empty($seller['phone'])) {
  $digits = preg_replace('/\D+/', '', (string)$seller['phone']);
  if ($digits !== '') {
    if (strpos($digits, '0') === 0) {
      // If number starts with 0 and no country code, assume Malaysia (60). Adjust if needed.
      $digits = '60' . ltrim($digits, '0');
    }
    $waText = rawurlencode("Hi, I'm interested in your {$car['make']} {$car['model']} (Car ID {$car_id}).");
    $waPhoneLink = "https://wa.me/{$digits}?text={$waText}";
  }
}
// fetch all images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=?");
$imgQ->bind_param("i", $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while ($row = $resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();
// fetch car_details (include optional car_condition)
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, wheel_size, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param("i", $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();
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
    <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
    <nav>
      <ul class="flex gap-6">
        <li><a href="main.php" class="hover:underline">Home</a></li>
        <li><a href="car_view.php" class="hover:underline">Listings</a></li>
        <li><a href="#" class="hover:underline">About</a></li>
        <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
          <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
        <?php endif; ?>
        <li><a href="logout.php" class="hover:underline">Logout</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="container mx-auto mt-8">
  <div class="mb-4">
    <a href="list_cars.php?make=<?php echo urlencode($car['make']); ?>&model=<?php echo urlencode($car['model']); ?>"
       class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
      <span>←</span>
      <span>Back to Listings</span>
    </a>
  </div>
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
        <div class="mt-6">
          <label class="block font-semibold mb-2 text-blue-600" for="seller_note">Seller's Note</label>
          <div class="border p-3 rounded w-full h-32 bg-gray-50 text-gray-700"><?php echo nl2br(htmlspecialchars($car_details['seller_note'] ?? '')); ?></div>
        </div>
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
          </div>
        </div>
        <?php if (!empty($seller)): ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-green-700">Seller Information</h3>
          <div class="grid grid-cols-1 gap-2 text-gray-800">
            <div><span class="font-semibold">Name:</span> <?php echo htmlspecialchars($seller['name'] ?? ''); ?></div>
            <?php if (!empty($seller['phone'])): ?>
              <div><span class="font-semibold">Phone:</span> <a class="text-blue-600 hover:underline" href="tel:<?php echo htmlspecialchars($seller['phone']); ?>"><?php echo htmlspecialchars($seller['phone']); ?></a></div>
            <?php endif; ?>
            <?php if (!empty($seller['email'])): ?>
              <div><span class="font-semibold">Email:</span> <a class="text-blue-600 hover:underline" href="mailto:<?php echo htmlspecialchars($seller['email']); ?>"><?php echo htmlspecialchars($seller['email']); ?></a></div>
            <?php endif; ?>
          </div>
          <?php if (!empty($waPhoneLink)): ?>
            <div class="mt-3">
              <a href="<?php echo htmlspecialchars($waPhoneLink); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">
                <span>WhatsApp</span>
              </a>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-purple-700">Loan Calculator</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcPrice">Price (RM)</label>
              <input id="lcPrice" type="number" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars(number_format((float)$car['price'], 2, '.', '')); ?>" readonly>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcDown">Down Payment (RM)</label>
              <input id="lcDown" type="number" class="w-full p-2 border rounded" placeholder="0" min="0" step="100">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcYears">Loan Tenure (years)</label>
              <select id="lcYears" class="w-full p-2 border rounded">
                <?php for ($y=1; $y<=9; $y++): ?>
                  <option value="<?php echo $y; ?>" <?php echo ($y===9?'selected':''); ?>><?php echo $y; ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1" for="lcRate">Bank Rate (% p.a.)</label>
              <input id="lcRate" type="number" class="w-full p-2 border rounded" value="3.50" step="0.01" min="0" max="20">
            </div>
          </div>
          <div class="mt-3 flex flex-wrap gap-6 text-sm">
            <div>Monthly Payment: <span id="lcMonthly" class="font-bold text-red-600">RM 0.00</span></div>
            <div>Total Payable: <span id="lcTotal" class="font-semibold">RM 0.00</span></div>
            <div>Total Interest: <span id="lcInterest" class="font-semibold">RM 0.00</span></div>
          </div>
        </div>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <h3 class="text-lg font-semibold mb-2 text-blue-600">Car Details</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-gray-700"><span class="font-semibold">Colour:</span> <?php echo htmlspecialchars($car_details['color'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Horsepower:</span> <?php echo htmlspecialchars($car_details['horsepower'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Engine Code:</span> <?php echo htmlspecialchars($car_details['engine_code'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Gear Numbers:</span> <?php echo htmlspecialchars($car_details['gear_numbers'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Wheel Size:</span> <?php echo htmlspecialchars($car_details['wheel_size'] ?? ''); ?></div>
            <?php if (!empty($car_details['car_condition'])): ?>
              <div class="text-gray-700"><span class="font-semibold">Condition:</span> <?php echo htmlspecialchars($car_details['car_condition']); ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
(function(){
  function fmtRM(num){
    try { return 'RM ' + Number(num).toLocaleString('en-MY', {minimumFractionDigits:2, maximumFractionDigits:2}); }
    catch(e){
      var n = Math.round(Number(num)*100)/100;
      return 'RM ' + (''+n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
  }
  function recalc(){
    var price = parseFloat(document.getElementById('lcPrice').value) || 0;
    var down = parseFloat(document.getElementById('lcDown').value) || 0;
    if (down < 0) down = 0;
    if (down > price) down = price;
    var years = parseInt(document.getElementById('lcYears').value, 10) || 0;
    var rate = parseFloat(document.getElementById('lcRate').value) || 0;
    var n = Math.max(1, years) * 12;
    var principal = Math.max(0, price - down);
    var r = (rate/100) / 12;
    var monthly = 0;
    if (principal === 0) {
      monthly = 0;
    } else if (r === 0) {
      monthly = principal / n;
    } else {
      var pow = Math.pow(1+r, n);
      monthly = principal * r * pow / (pow - 1);
    }
    var total = monthly * n;
    var interest = Math.max(0, total - principal);
    document.getElementById('lcMonthly').textContent = fmtRM(monthly);
    document.getElementById('lcTotal').textContent = fmtRM(total);
    document.getElementById('lcInterest').textContent = fmtRM(interest);
  }
  function hook(){
    ['lcDown','lcYears','lcRate'].forEach(function(id){
      var el = document.getElementById(id);
      if (!el) return;
      el.addEventListener('input', recalc);
      el.addEventListener('change', recalc);
    });
    recalc();
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hook);
  } else { hook(); }
})();
</script>
</body>
</html>
