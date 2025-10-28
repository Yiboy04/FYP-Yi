<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
    header("Location: admin_login.php");
    exit();
}
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) { die("DB error: " . $mysqli->connect_error); }
if (!isset($_GET['car_id'])) { die("No car selected."); }
$car_id = intval($_GET['car_id']);
// fetch car info
$stmt = $mysqli->prepare("SELECT * FROM cars WHERE car_id=?");
$stmt->bind_param("i", $car_id);
$stmt->execute();
$car = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$car) die("Car not found.");
// Handle delete (admin only)
$adminMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_car'])) {
  try {
    $mysqli->begin_transaction();
    // Remove dependent rows (be safe even if FKs have CASCADE)
    if ($st = $mysqli->prepare('DELETE FROM car_images WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM car_details WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM car_more_detail WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM bookings WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM saved_cars WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM car_360_set WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    if ($st = $mysqli->prepare('DELETE FROM cars WHERE car_id=?')) { $st->bind_param('i', $car_id); $st->execute(); $st->close(); }
    $mysqli->commit();
    header('Location: admin_cars.php?msg=deleted');
    exit();
  } catch (Exception $e) {
    $mysqli->rollback();
    $adminMsg = 'Failed to delete listing.';
  }
}
// fetch images
$imgs = [];
$imgQ = $mysqli->prepare("SELECT image_path FROM car_images WHERE car_id=?");
$imgQ->bind_param("i", $car_id);
$imgQ->execute();
$resImg = $imgQ->get_result();
while ($row = $resImg->fetch_assoc()) $imgs[] = $row['image_path'];
$imgQ->close();
// fetch car_details
$details = $mysqli->prepare("SELECT color, horsepower, engine_code, gear_numbers, front_wheel_size, rear_wheel_size, torque, car_type, seller_note, car_condition FROM car_details WHERE car_id=?");
$details->bind_param("i", $car_id);
$details->execute();
$car_details = $details->get_result()->fetch_assoc();
$details->close();
// seller info
$seller = null;
if (!empty($car['seller_id'])) {
  $s = $mysqli->prepare("SELECT id, name, email, phone FROM sellers WHERE id=?");
  $s->bind_param("i", $car['seller_id']);
  $s->execute();
  $seller = $s->get_result()->fetch_assoc();
  $s->close();
}
// Build WhatsApp link from seller phone (assume MY country code 60 for numbers starting with 0)
$waPhoneLink = null;
if (!empty($seller['phone'])) {
  $digits = preg_replace('/\D+/', '', (string)$seller['phone']);
  if ($digits !== '') {
    if (strpos($digits, '0') === 0) { $digits = '60' . ltrim($digits, '0'); }
    $waText = rawurlencode("Admin here, I'm contacting about your ".$car['year'].' '.$car['make'].' '.$car['model'].' '.$car['variant'].'.');
    $waPhoneLink = "https://wa.me/{$digits}?text={$waText}";
  }
}
// 360 check
$has360 = false;
if ($chk = $mysqli->prepare("SELECT 1 FROM car_360_set WHERE car_id=? LIMIT 1")) {
  $chk->bind_param('i', $car_id);
  if ($chk->execute()) { $chk->store_result(); $has360 = $chk->num_rows > 0; }
  $chk->close();
}
// Ensure more_detail table and columns exist and fetch
$mysqli->query("CREATE TABLE IF NOT EXISTS car_more_detail (
  car_id INT NOT NULL PRIMARY KEY,
  speaker_brand VARCHAR(255) NULL,
  speaker_quantity INT NULL,
  length_mm INT NULL,
  height_mm INT NULL,
  width_mm INT NULL,
  wheel_base_mm INT NULL,
  turning_circle VARCHAR(50) NULL,
  fuel_consumption DECIMAL(5,2) NULL,
  front_suspension VARCHAR(255) NULL,
  rear_suspension VARCHAR(255) NULL,
  driver_assistance TEXT NULL,
  zero_to_hundred_s DECIMAL(5,2) NULL,
  top_speed_kmh INT NULL,
  heated_seat TINYINT(1) NULL,
  cooling_seat TINYINT(1) NULL,
  other_features TEXT NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cmd_car FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS zero_to_hundred_s DECIMAL(5,2) NULL AFTER driver_assistance");
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS top_speed_kmh INT NULL AFTER zero_to_hundred_s");
$mysqli->query("ALTER TABLE car_more_detail ADD COLUMN IF NOT EXISTS other_features TEXT NULL AFTER cooling_seat");
$moreStmt = $mysqli->prepare("SELECT speaker_brand, speaker_quantity, length_mm, height_mm, width_mm, wheel_base_mm, turning_circle, fuel_consumption, front_suspension, rear_suspension, driver_assistance, zero_to_hundred_s, top_speed_kmh, heated_seat, cooling_seat, other_features FROM car_more_detail WHERE car_id=?");
$moreStmt->bind_param("i", $car_id);
$moreStmt->execute();
$car_more = $moreStmt->get_result()->fetch_assoc();
$moreStmt->close();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Car View - <?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script>
function changeMain(src){ document.getElementById('mainImage').src=src; }
</script>
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Admin Car View</h1>
    <a href="admin_cars.php" class="underline">Back</a>
  </div>
</header>
<main class="container mx-auto mt-8">
  <div class="bg-white rounded shadow p-6">
    <?php if (!empty($adminMsg)): ?>
      <div class="mb-4 bg-yellow-100 text-yellow-800 px-4 py-2 rounded"><?php echo htmlspecialchars($adminMsg); ?></div>
    <?php endif; ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
          <label class="block font-semibold mb-2 text-blue-600">Seller's Note</label>
          <div class="border p-3 rounded w-full h-32 bg-gray-50 text-gray-700"><?php echo nl2br(htmlspecialchars($car_details['seller_note'] ?? '')); ?></div>
          <div class="mt-3">
            <?php if ($has360): ?>
              <a href="car_view.php?car_id=<?php echo (int)$car_id; ?>" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-semibold">3D View</a>
            <?php else: ?>
              <a href="#" aria-disabled="true" title="3D view not available" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg font-semibold opacity-50 cursor-not-allowed pointer-events-none">3D View</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div>
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($car['make'].' '.$car['model']); ?></h2>
        <div class="text-red-600 text-xl font-bold mb-4">RM<?php echo number_format($car['price'],2); ?></div>
        <div class="mb-4 flex items-center gap-3">
          <form method="post" onsubmit="return confirm('Are you sure you want to delete this listing? This action cannot be undone.');">
            <input type="hidden" name="delete_car" value="1">
            <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">Delete Listing</button>
          </form>
        </div>
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
              <a href="<?php echo htmlspecialchars($waPhoneLink); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">WhatsApp</a>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bg-gray-50 rounded-lg shadow p-4 mb-4">
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-blue-600">Car Details</h3>
            <button type="button" id="openMoreDetailsView" class="bg-white border border-blue-600 text-blue-600 px-3 py-1.5 rounded hover:bg-blue-50">More Details</button>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-gray-700"><span class="font-semibold">Colour:</span> <?php echo htmlspecialchars($car_details['color'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Horsepower:</span> <?php echo htmlspecialchars($car_details['horsepower'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Engine Code:</span> <?php echo htmlspecialchars($car_details['engine_code'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Gear Numbers:</span> <?php echo htmlspecialchars($car_details['gear_numbers'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Front Wheel Size:</span> <?php echo htmlspecialchars($car_details['front_wheel_size'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Rear Wheel Size:</span> <?php echo htmlspecialchars($car_details['rear_wheel_size'] ?? ''); ?></div>
            <div class="text-gray-700"><span class="font-semibold">Torque:</span> <?php echo htmlspecialchars($car_details['torque'] ?? ''); ?></div>
            <?php if (!empty($car_details['car_type'])): ?>
              <div class="text-gray-700"><span class="font-semibold">Car Type:</span> <?php echo htmlspecialchars($car_details['car_type']); ?></div>
            <?php endif; ?>
            <?php if (!empty($car_details['car_condition'])): ?>
              <div class="text-gray-700"><span class="font-semibold">Condition:</span> <?php echo htmlspecialchars($car_details['car_condition']); ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<!-- Read-only More Details Slide-over (reuse same rendering as buyer view) -->
<div id="moreDetailsModalView" class="hidden fixed inset-0 z-50" aria-modal="true" role="dialog">
  <div id="moreDetailsBackdropView" class="absolute inset-0 bg-black bg-opacity-50 opacity-0 transition-opacity duration-300"></div>
  <div class="absolute inset-y-0 right-0 max-w-full flex">
    <div id="moreDetailsPanelView" class="w-screen max-w-xl transform translate-x-full transition-transform duration-300 ease-out">
      <div class="h-full flex flex-col bg-white shadow-xl">
        <div class="flex items-center justify-between px-5 py-3 border-b">
          <h3 class="text-xl font-semibold">More Details</h3>
          <button id="closeMoreDetailsView" class="text-gray-500 hover:text-gray-700" aria-label="Close">✕</button>
        </div>
        <div class="p-5 space-y-6 overflow-y-auto" style="max-height: calc(100vh - 7rem);">
          <h4 class="text-xl font-semibold">Car features and spec</h4>
          <div>
            <label for="featureSearchView" class="sr-only">Search features</label>
            <div class="relative">
              <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
              <input id="featureSearchView" type="text" placeholder="Search for features and spec" class="w-full border rounded pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>
          <div>
            <h5 class="text-lg font-semibold mb-2">All features</h5>
            <?php
              $fmtPriceV = isset($car['price']) ? 'RM'.number_format($car['price'], 2) : null;
              $sec_basic_v = [
                'Make / Model' => trim(($car['make'] ?? '').' '.($car['model'] ?? '')),
                'Variant' => $car['variant'] ?? null,
                'Year' => $car['year'] ?? null,
                'Price' => $fmtPriceV,
                'Mileage' => isset($car['mileage']) ? $car['mileage'].' km' : null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
                'Fuel' => $car['fuel'] ?? null,
                'Drive System' => $car['drive_system'] ?? null,
                'Doors' => isset($car['doors']) ? $car['doors'].'D' : null,
                'Condition' => $car_details['car_condition'] ?? null,
                'Type' => $car_details['car_type'] ?? null,
              ];
              $sec_perf_v = [
                'Horsepower' => $car_details['horsepower'] ?? null,
                'Torque' => $car_details['torque'] ?? null,
                '0-100 km/h (s)' => $car_more['zero_to_hundred_s'] ?? null,
                'Top Speed (km/h)' => $car_more['top_speed_kmh'] ?? null,
                'Engine Code' => $car_details['engine_code'] ?? null,
                'Gear Numbers' => $car_details['gear_numbers'] ?? null,
                'Transmission' => $car['transmission'] ?? null,
                'Engine Capacity' => isset($car['engine_capacity']) ? $car['engine_capacity'].' L' : null,
              ];
              $sec_wheels_v = [
                'Front Wheel Size' => $car_details['front_wheel_size'] ?? null,
                'Rear Wheel Size' => $car_details['rear_wheel_size'] ?? null,
              ];
              $sec_audio_v = [
                'Speaker Brand' => $car_more['speaker_brand'] ?? null,
                'Speaker Quantity' => isset($car_more['speaker_quantity']) ? $car_more['speaker_quantity'] : null,
              ];
              $sec_drivers_v = [];
              if (!empty($car_more['driver_assistance'])) {
                $items = preg_split("/(\r?\n)|,\s*/", $car_more['driver_assistance']);
                foreach ($items as $it) { $it = trim((string)$it); if ($it !== '') { $sec_drivers_v[] = $it; } }
              }
              $sec_interior_v = [
                'Heated Seat' => (isset($car_more['heated_seat']) && (int)$car_more['heated_seat'] === 1) ? 'Yes' : null,
                'Cooling Seat' => (isset($car_more['cooling_seat']) && (int)$car_more['cooling_seat'] === 1) ? 'Yes' : null,
              ];
              $sec_dimensions_v = [
                'Length (mm)' => $car_more['length_mm'] ?? null,
                'Width (mm)' => $car_more['width_mm'] ?? null,
                'Height (mm)' => $car_more['height_mm'] ?? null,
                'Wheel Base (mm)' => $car_more['wheel_base_mm'] ?? null,
                'Turning Circle' => $car_more['turning_circle'] ?? null,
              ];
              $sec_fuel_v = [
                'Fuel Consumption (L/100km)' => $car_more['fuel_consumption'] ?? null,
              ];
              $sec_suspension_v = [
                'Front Suspension' => $car_more['front_suspension'] ?? null,
                'Rear Suspension' => $car_more['rear_suspension'] ?? null,
              ];
              $sec_other_v = [];
              if (!empty($car_more['other_features'])) {
                $oitems = preg_split("/(\r?\n)|,\s*/", $car_more['other_features']);
                foreach ($oitems as $it) { $it = trim((string)$it); if ($it !== '') { $sec_other_v[] = $it; } }
              }
              $sections_v = [
                'Audio and Communications' => $sec_audio_v,
                'Drivers Assistance' => $sec_drivers_v,
                'Dimensions' => $sec_dimensions_v,
                'Fuel Economy' => $sec_fuel_v,
                'Suspension' => $sec_suspension_v,
                'Interior' => $sec_interior_v,
                'Other' => $sec_other_v,
                'Performance' => $sec_perf_v,
                'Wheels & Tyres' => $sec_wheels_v,
                'Basic Specs' => $sec_basic_v,
              ];
            ?>
            <div id="featureAccordionView" class="divide-y border rounded">
              <?php foreach ($sections_v as $secName => $items): $clean = array_filter($items, function($v){ return !is_null($v) && $v !== ''; }); $cnt = count($clean); $open = ($secName === 'Basic Specs'); ?>
                <div class="acc-section-v" data-acc-section>
                  <button type="button" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 focus:outline-none acc-toggle-v" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
                    <div class="flex items-center gap-3">
                      <span class="font-semibold"><?php echo htmlspecialchars($secName); ?></span>
                    </div>
                    <div class="flex items-center gap-3">
                      <span class="inline-flex items-center justify-center text-xs font-semibold text-gray-700 bg-gray-100 rounded px-2 py-0.5 acc-count-v"><?php echo (int)$cnt; ?></span>
                      <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200 acc-caret-v <?php echo $open ? 'rotate-180' : ''; ?>" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </div>
                  </button>
                  <div class="acc-panel-v <?php echo $open ? '' : 'hidden'; ?> px-6 pb-4">
                    <?php if ($cnt > 0): ?>
                      <?php $isBullets = array_values($clean) === $clean; ?>
                      <ul class="list-disc pl-5 space-y-1 acc-list-v">
                        <?php if ($isBullets): ?>
                          <?php foreach ($clean as $text): ?>
                            <li class="text-sm text-gray-700"><span class="acc-item-text"><?php echo htmlspecialchars((string)$text); ?></span></li>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <?php foreach ($clean as $label => $val): ?>
                            <li class="text-sm text-gray-700"><span class="font-medium"><?php echo htmlspecialchars($label); ?>:</span> <span class="acc-item-text"><?php echo htmlspecialchars((string)$val); ?></span></li>
                          <?php endforeach; ?>
                        <?php endif; ?>
                      </ul>
                    <?php else: ?>
                      <div class="text-sm text-gray-500 italic acc-empty-v">No items yet.</div>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="px-5 py-3 border-t flex justify-end">
          <button id="closeMoreDetailsBottomView" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function(){ var openBtn=document.getElementById('openMoreDetailsView'); var modal=document.getElementById('moreDetailsModalView'); var closeTop=document.getElementById('closeMoreDetailsView'); var closeBottom=document.getElementById('closeMoreDetailsBottomView'); var backdrop=document.getElementById('moreDetailsBackdropView'); var panel=document.getElementById('moreDetailsPanelView'); var accRoot=document.getElementById('featureAccordionView'); var searchInput=document.getElementById('featureSearchView'); function openModal(){ if(!modal) return; modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); requestAnimationFrame(function(){ backdrop&&backdrop.classList.add('opacity-100'); panel&&panel.classList.remove('translate-x-full'); closeTop&&closeTop.focus(); }); } function closeModal(){ if(!modal) return; backdrop&&backdrop.classList.remove('opacity-100'); panel&&panel.classList.add('translate-x-full'); var onEnd=function(e){ if(e.target===panel){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); panel.removeEventListener('transitionend', onEnd);} }; panel&&panel.addEventListener('transitionend', onEnd); } if (openBtn) openBtn.addEventListener('click', openModal); if (closeTop) closeTop.addEventListener('click', closeModal); if (closeBottom) closeBottom.addEventListener('click', closeModal); if (backdrop) backdrop.addEventListener('click', closeModal); document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeModal(); }}); if (accRoot){ accRoot.addEventListener('click', function(e){ var btn=e.target.closest('.acc-toggle-v'); if(!btn) return; var section=btn.closest('[data-acc-section]'); var panelEl=section.querySelector('.acc-panel-v'); var caret=section.querySelector('.acc-caret-v'); var expanded=btn.getAttribute('aria-expanded')==='true'; if(expanded){ panelEl.classList.add('hidden'); caret&&caret.classList.remove('rotate-180'); btn.setAttribute('aria-expanded','false'); } else { panelEl.classList.remove('hidden'); caret&&caret.classList.add('rotate-180'); btn.setAttribute('aria-expanded','true'); } }); } function normalize(s){ return (s||'').toString().toLowerCase(); } function updateCounts(section){ var countEl=section.querySelector('.acc-count-v'); var list=section.querySelectorAll('.acc-list-v > li'); var emptyEl=section.querySelector('.acc-empty-v'); var visible=0; list.forEach(function(li){ if(!li.classList.contains('hidden')) visible++; }); if(countEl) countEl.textContent=visible; if(emptyEl){ emptyEl.classList.toggle('hidden', visible!==0); } } function applySearch(term){ var q=normalize(term); var sections=accRoot?accRoot.querySelectorAll('[data-acc-section]'):[]; sections.forEach(function(sec){ var items=sec.querySelectorAll('.acc-list-v > li'); var any=0; items.forEach(function(li){ var text=normalize(li.textContent); var show=q===''?true:text.indexOf(q)!==-1; li.classList.toggle('hidden', !show); if(show) any++; }); var btn=sec.querySelector('.acc-toggle-v'); var panelEl=sec.querySelector('.acc-panel-v'); var caret=sec.querySelector('.acc-caret-v'); if(q!==''){ if(any>0){ panelEl.classList.remove('hidden'); btn&&btn.setAttribute('aria-expanded','true'); caret&&caret.classList.add('rotate-180'); } else { panelEl.classList.add('hidden'); btn&&btn.setAttribute('aria-expanded','false'); caret&&caret.classList.remove('rotate-180'); } } updateCounts(sec); }); } if (searchInput){ searchInput.addEventListener('input', function(){ applySearch(searchInput.value); }); } })();
</script>
</body>
</html>
