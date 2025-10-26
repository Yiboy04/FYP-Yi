<?php
// main.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
    die("DB error: " . $mysqli->connect_error);
}
// Fetch makes and models from database
$makes = [];
$models = [];
$resMakes = $mysqli->query("SELECT DISTINCT make FROM cars ORDER BY make ASC");
while ($row = $resMakes->fetch_assoc()) $makes[] = $row['make'];
if (isset($_GET['make']) && $_GET['make'] !== '') {
    $make = $mysqli->real_escape_string($_GET['make']);
    $resModels = $mysqli->query("SELECT DISTINCT model FROM cars WHERE make='$make' ORDER BY model ASC");
    while ($row = $resModels->fetch_assoc()) $models[] = $row['model'];
}
// Recently viewed cars (up to 5, preserve latest-first order)
$recentCars = [];
if (!empty($_SESSION['recently_viewed']) && is_array($_SESSION['recently_viewed'])) {
  // Normalize, unique, cap to 5, preserve order
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

// --- Mini insight (random view) ---
$views = ['make','model','transmission','condition'];
try {
  $randIdx = random_int(0, count($views)-1);
  $chartView = $views[$randIdx];
} catch (Throwable $e) {
  $chartView = $views[array_rand($views)];
}
$insight = [
  'view' => $chartView,
  'title' => '',
  'labels' => [],
  'values' => []
];
if ($chartView === 'make') {
  $sql = "SELECT make AS label, COUNT(*) AS sold FROM cars GROUP BY make ORDER BY sold DESC LIMIT 5";
  if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) { $insight['labels'][] = $row['label']; $insight['values'][] = (int)$row['sold']; }
    $res->free();
  }
  $insight['title'] = 'Top 5 by Make';
} elseif ($chartView === 'model') {
  $sql = "SELECT model, make, COUNT(*) AS sold FROM cars GROUP BY model, make ORDER BY sold DESC LIMIT 5";
  if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) { $label = trim($row['model']); if (!empty($row['make'])) { $label .= ' (' . $row['make'] . ')'; } $insight['labels'][] = $label; $insight['values'][] = (int)$row['sold']; }
    $res->free();
  }
  $insight['title'] = 'Top 5 Models';
} elseif ($chartView === 'transmission') {
  $sql = "SELECT COALESCE(c.transmission,'—') AS label, COUNT(*) AS sold FROM cars c GROUP BY c.transmission ORDER BY sold DESC, label ASC LIMIT 5";
  if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) { $insight['labels'][] = $row['label']; $insight['values'][] = (int)$row['sold']; }
    $res->free();
  }
  $insight['title'] = 'Top 5 by Transmission';
} else { // condition
  $sql = "SELECT COALESCE(cd.car_condition,'—') AS label, COUNT(*) AS sold FROM car_details cd JOIN cars c ON c.car_id = cd.car_id GROUP BY cd.car_condition ORDER BY sold DESC LIMIT 5";
  if ($res = $mysqli->query($sql)) {
    while ($row = $res->fetch_assoc()) { $insight['labels'][] = $row['label']; $insight['values'][] = (int)$row['sold']; }
    $res->free();
  }
  $insight['title'] = 'Top 5 by Condition';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Main Page - Car Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

  <!-- HEADER -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="car_view.php" class="hover:underline">Listings</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
            <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
          <?php endif; ?>
          <!-- Fold-down menu -->
          <li class="relative" id="moreMenu">
            <button id="moreBtn" class="inline-flex items-center gap-1 px-3 py-1 bg-white bg-opacity-10 hover:bg-opacity-20 rounded">
              <span>More</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div id="morePanel" class="hidden absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
              <a href="analysis.php" class="block px-4 py-2 hover:bg-gray-100">Analysis</a>
              <a href="saved_search.php" class="block px-4 py-2 hover:bg-gray-100">Saved</a>
              <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
                <a href="buyer_bookings.php" class="block px-4 py-2 hover:bg-gray-100">Bookings</a>
              <?php endif; ?>
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

  <!-- MAIN CONTENT -->
  <main class="flex-grow relative overflow-hidden">
    <!-- Background slideshow layers (use <img> for reliable decoding) -->
    <div id="bgA" class="absolute inset-0 opacity-100 transition-opacity duration-700">
      <img id="bgAImg" alt="" aria-hidden="true" class="w-full h-full object-cover select-none" draggable="false" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" />
    </div>
    <div id="bgB" class="absolute inset-0 opacity-0 transition-opacity duration-700">
      <img id="bgBImg" alt="" aria-hidden="true" class="w-full h-full object-cover select-none" draggable="false" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" />
    </div>
    <!-- Dark overlay for readability -->
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>

    <div class="relative z-10 flex justify-center items-center min-h-[70vh]">
      <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-xl w-[600px]">
      <h2 class="text-2xl font-bold mb-4">Find Used Cars</h2>

      <!-- Make & Model -->
      <form method="get" action="list_cars.php">
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-1">Make</label>
          <select class="w-full p-2 border rounded" name="make" id="makeSelect">
            <option value="">Select Make</option>
            <?php foreach($makes as $m): ?>
              <option value="<?php echo htmlspecialchars($m); ?>" <?php if(isset($_GET['make']) && $_GET['make']==$m) echo 'selected'; ?>><?php echo htmlspecialchars($m); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block mb-1">Model</label>
          <select class="w-full p-2 border rounded" name="model" id="modelSelect">
            <option value="">Select Model</option>
            <?php foreach($models as $mod): ?>
              <option value="<?php echo htmlspecialchars($mod); ?>" <?php if(isset($_GET['model']) && $_GET['model']==$mod) echo 'selected'; ?>><?php echo htmlspecialchars($mod); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Year Slider -->
      <div class="mb-6">
        <label class="block mb-2">Year: <span id="yearDisplay">1957 - 2025</span></label>
        <input type="range" id="minYear" min="1957" max="2025" value="1957" class="w-full mb-2">
        <input type="range" id="maxYear" min="1957" max="2025" value="2025" class="w-full">
      </div>

      <!-- Price -->
      <div class="mb-6">
        <label class="block mb-2">Price: <span id="priceDisplay">RM 1 - RM 100,000,000</span></label>
        <input type="range" id="minPrice" min="1" max="100000000" value="1" class="w-full mb-2">
        <input type="range" id="maxPrice" min="1" max="100000000" value="100000000" class="w-full">

        <!-- Popup trigger -->
        <button type="button" id="openPricePopup" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">
          Enter Specific Price
        </button>
      </div>

  <input id="hiddenMinYear" type="hidden" name="minYear" value="<?php echo isset($_GET['minYear']) ? intval($_GET['minYear']) : 1957; ?>">
  <input id="hiddenMaxYear" type="hidden" name="maxYear" value="<?php echo isset($_GET['maxYear']) ? intval($_GET['maxYear']) : 2025; ?>">
  <input id="hiddenMinPrice" type="hidden" name="minPrice" value="<?php echo isset($_GET['minPrice']) ? intval($_GET['minPrice']) : 1; ?>">
  <input id="hiddenMaxPrice" type="hidden" name="maxPrice" value="<?php echo isset($_GET['maxPrice']) ? intval($_GET['maxPrice']) : 100000000; ?>">
      <button class="w-full bg-red-600 text-white py-2 rounded-lg text-lg font-semibold">Search</button>
      </form>
      <script>
        document.getElementById('makeSelect').addEventListener('change', function() {
          const make = this.value;
          const url = new URL(window.location.href);
          url.searchParams.set('make', make);
          url.searchParams.delete('model'); // reset model selection
          window.location.href = url.toString();
        });
      </script>
      </div>
    </div>
  </main>

  <!-- RECENTLY VIEWED -->
  <section class="py-10">
    <div class="container mx-auto max-w-6xl px-4">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">Recently Viewed</h2>
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
  </section>

  <!-- MINI ANALYSIS CHART -->
  <section class="py-10">
    <div class="container mx-auto max-w-3xl px-4">
      <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-2xl font-bold">Quick Insight</h2>
          <a href="analysis.php" class="text-blue-600 hover:underline text-sm">See full analysis</a>
        </div>
        <div class="text-gray-600 mb-4"><?php echo htmlspecialchars($insight['title']); ?></div>
        <?php if (!empty($insight['labels'])): ?>
          <div class="relative">
            <canvas id="miniInsightChart" height="240"></canvas>
          </div>
        <?php else: ?>
          <div class="text-gray-500">No data available for insight right now.</div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- FUEL ECONOMY CALCULATOR SECTION -->
  <section class="py-10">
    <div class="container mx-auto max-w-3xl px-4">
      <div class="bg-white rounded-2xl shadow-xl p-6">
        <h2 class="text-2xl font-bold mb-4">Fuel Economy Calculator</h2>
        <p class="text-gray-600 mb-4">Enter your trip distance and fuel used. We'll convert and show values in mpg (US), mpg (UK), L/km, and L/100km.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block mb-1 font-medium" for="feDistance">Distance</label>
            <input id="feDistance" type="number" min="0" step="0.01" class="w-full p-2 border rounded" placeholder="e.g. 120">
          </div>
          <div>
            <label class="block mb-1 font-medium" for="feDistUnit">Distance Unit</label>
            <select id="feDistUnit" class="w-full p-2 border rounded">
              <option value="km">Kilometers (km)</option>
              <option value="mi">Miles (mi)</option>
            </select>
          </div>
          <div>
            <label class="block mb-1 font-medium" for="feFuel">Fuel Used</label>
            <input id="feFuel" type="number" min="0" step="0.01" class="w-full p-2 border rounded" placeholder="e.g. 8.5">
          </div>
          <div>
            <label class="block mb-1 font-medium" for="feFuelUnit">Fuel Unit</label>
            <select id="feFuelUnit" class="w-full p-2 border rounded">
              <option value="L">Liters (L)</option>
              <option value="galUS">Gallons (US)</option>
              <option value="galUK">Gallons (UK)</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm">mpg (US)</div>
            <div id="feMpgUS" class="text-xl font-bold">—</div>
          </div>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm">mpg (UK)</div>
            <div id="feMpgUK" class="text-xl font-bold">—</div>
          </div>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm">L/km</div>
            <div id="feLperKm" class="text-xl font-bold">—</div>
          </div>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm">L/100km</div>
            <div id="feLper100" class="text-xl font-bold">—</div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- ROAD TAX & INSURANCE CALCULATOR SECTION -->
  <section class="py-10">
    <div class="container mx-auto max-w-3xl px-4">
      <div class="bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-2xl font-bold">Road Tax & Insurance</h2>
          <div class="inline-flex rounded-lg overflow-hidden border">
            <button id="rtTab" type="button" class="px-4 py-2 bg-red-600 text-white font-semibold">Road Tax</button>
            <button id="insTab" type="button" class="px-4 py-2 bg-white text-gray-700">Insurance</button>
          </div>
        </div>
        <!-- Road Tax -->
        <div id="rtPanel" class="block">
          <p class="text-gray-600 mb-4">Estimate Malaysian private car road tax. ICE uses West Malaysia saloon rates with an East Malaysia adjustment (approx). EVs use a kW-based structure in Malaysia.</p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block mb-1 font-medium" for="rtPower">Powertrain</label>
              <select id="rtPower" class="w-full p-2 border rounded">
                <option value="ice">ICE (Petrol/Diesel)</option>
                <option value="ev">EV (Electric Vehicle)</option>
              </select>
            </div>
            <div>
              <label id="rtCCLabel" class="block mb-1 font-medium" for="rtCC">Engine Capacity (cc)</label>
              <input id="rtCC" type="number" min="100" step="1" class="w-full p-2 border rounded" placeholder="e.g. 1998">
            </div>
            <div>
              <label class="block mb-1 font-medium" for="rtType">Vehicle Type</label>
              <select id="rtType" class="w-full p-2 border rounded">
                <option value="saloon">Saloon</option>
                <option value="non-saloon">Non-saloon</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 font-medium" for="rtRegion">Region</label>
              <select id="rtRegion" class="w-full p-2 border rounded">
                <option value="west">West Malaysia</option>
                <option value="east">East Malaysia</option>
              </select>
            </div>
          </div>
          <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm">Estimated Road Tax</div>
            <div class="flex flex-col gap-1">
              <div id="rtResult" class="text-xl font-bold">—</div>
              <div id="rtNote" class="text-sm text-gray-500"></div>
            </div>
          </div>
        </div>
        <!-- Insurance -->
        <div id="insPanel" class="hidden">
          <p class="text-gray-600 mb-4">Estimate comprehensive car insurance. Base rate depends on vehicle category; SST and stamp duty are fixed and visible.</p>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block mb-1 font-medium" for="insSum">Sum Insured (RM)</label>
              <input id="insSum" type="number" min="0" step="100" class="w-full p-2 border rounded" placeholder="e.g. 60000">
            </div>
            <div>
              <label class="block mb-1 font-medium" for="insRateCat">Base Rate (per RM100)</label>
              <select id="insRateCat" class="w-full p-2 border rounded">
                <option value="small" data-min="1.80" data-max="2.80">Small car (RM1.80 – RM2.80)</option>
                <option value="big" data-min="2.80" data-max="3.80">Big car (RM2.80 – RM3.80)</option>
                <option value="lux" data-min="4.00" data-max="6.00">Luxury/Performance (RM4.00 – RM6.00)</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 font-medium" for="insNCD">NCD (%)</label>
              <div class="flex gap-2">
                <input id="insNCD" type="number" min="0" max="55" step="5" value="0" class="w-full p-2 border rounded">
                <select id="insNCDPreset" class="p-2 border rounded w-40">
                  <option value="0">Year 0 (0%)</option>
                  <option value="25">Year 1 (25%)</option>
                  <option value="30">Year 2 (30%)</option>
                  <option value="38.33">Year 3 (38.33%)</option>
                  <option value="45">Year 4 (45%)</option>
                  <option value="55">Year 5+ (55%)</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block mb-1 font-medium" for="insSST">SST (%)</label>
              <input id="insSST" type="number" min="0" step="0.01" value="6" class="w-full p-2 border rounded bg-gray-50" readonly>
            </div>
            <div>
              <label class="block mb-1 font-medium" for="insStamp">Stamp Duty (RM)</label>
              <input id="insStamp" type="number" min="0" step="1" value="10" class="w-full p-2 border rounded bg-gray-50" readonly>
            </div>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-gray-500 text-sm">Base Premium (Min – Max)</div>
              <div id="insBase" class="text-xl font-bold">—</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-gray-500 text-sm">Premium After NCD (Min – Max)</div>
              <div id="insAfterNcd" class="text-xl font-bold">—</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 sm:col-span-2">
              <div class="text-gray-500 text-sm">SST + Stamp Duty (Min – Max)</div>
              <div id="insTaxStamp" class="text-xl font-bold">—</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-gray-500 text-sm">Estimated Total (Lowest)</div>
              <div id="insTotalMin" class="text-2xl font-extrabold text-green-600">—</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-gray-500 text-sm">Estimated Total (Highest)</div>
              <div id="insTotalMax" class="text-2xl font-extrabold text-red-600">—</div>
            </div>
            <div class="sm:col-span-2 text-sm text-gray-600">
              <span id="insRangeNote"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white p-4">
    <div class="container mx-auto text-center">
      <p>&copy; <?php echo date("Y"); ?> MyCar (FYP). All rights reserved.</p>
    </div>
  </footer>

  <!-- PRICE POPUP -->
  <div id="pricePopup" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-xl w-80">
      <h2 class="text-lg font-semibold mb-3">Enter Price Range (RM)</h2>
      <div class="flex items-center gap-2 mb-3">
        <input id="popupMinPrice" type="number" class="border p-2 rounded w-1/2" placeholder="Min" />
        <span>-</span>
        <input id="popupMaxPrice" type="number" class="border p-2 rounded w-1/2" placeholder="Max" />
      </div>
      <div class="flex justify-end gap-2">
        <button id="cancelPopup" class="px-3 py-1 bg-gray-300 rounded">Cancel</button>
        <button id="applyPopup" class="px-3 py-1 bg-blue-600 text-white rounded">Apply</button>
      </div>
    </div>
  </div>

  <script>
  // --- Background slideshow ---
  (function(){
    // Brand-focused photos: Toyota, BMW, Mercedes-Benz, Honda (Unsplash, high-res)
    // Exactly five images, shown in order without random shuffle
    const urls = [
      // Toyota
      'https://www.goodwood.com/globalassets/.road--racing/road/news/2020/6-june/list-dan-trent-luxury-cars-2020/bmw-i7-2600.jpg?rxy=0.5,0.5',
      // BMW
      'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=2560&q=90',
      // Mercedes-Benz
      'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?auto=format&fit=crop&w=2560&q=90',
      // Honda
      'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=2560&q=90',
      // Mixed/neutral automotive backdrop
      'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=2560&q=90'
    ];
    const a = document.getElementById('bgA');
    const b = document.getElementById('bgB');
    const aImg = document.getElementById('bgAImg');
    const bImg = document.getElementById('bgBImg');
    if (!a || !b || !aImg || !bImg) return;
    let idx = 0;
    const fadeMs = 150;
    const delayMs = 2500; // quicker rotation
    function ensureDecoded(src){
      return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => {
          if (img.decode) { img.decode().catch(()=>{}).finally(() => resolve(img.src)); }
          else { resolve(img.src); }
        };
        img.onerror = () => resolve(src); // fail-safe
        img.src = src;
      });
    }
    function setA(src){ aImg.src = src; }
    function setB(src){ bImg.src = src; }
    // load first then start rotation
    ensureDecoded(urls[0]).then((first) => {
      setA(first);
      setTimeout(rotate, delayMs);
    });
    async function rotate(){
      idx = (idx + 1) % urls.length;
      const next = urls[idx];
      const decodedSrc = await ensureDecoded(next);
      setB(decodedSrc);
      // cross-fade A -> B
      b.style.opacity = '1';
      a.style.opacity = '0';
      setTimeout(() => {
        setA(decodedSrc);
        a.style.opacity = '1';
        b.style.opacity = '0';
        setTimeout(rotate, delayMs);
      }, fadeMs + 50);
    }
  })();

  const minYear = document.getElementById("minYear");
  const maxYear = document.getElementById("maxYear");
    const yearDisplay = document.getElementById("yearDisplay");

    const minPrice = document.getElementById("minPrice");
    const maxPrice = document.getElementById("maxPrice");
    const priceDisplay = document.getElementById("priceDisplay");

    const popup = document.getElementById("pricePopup");
    const openPopup = document.getElementById("openPricePopup");
    const cancelPopup = document.getElementById("cancelPopup");
    const applyPopup = document.getElementById("applyPopup");
    const popupMinPrice = document.getElementById("popupMinPrice");
    const popupMaxPrice = document.getElementById("popupMaxPrice");

    // Hidden inputs to submit current slider/popup values
    const hiddenMinYear = document.getElementById("hiddenMinYear");
    const hiddenMaxYear = document.getElementById("hiddenMaxYear");
    const hiddenMinPrice = document.getElementById("hiddenMinPrice");
    const hiddenMaxPrice = document.getElementById("hiddenMaxPrice");

    // If hidden inputs carry values (from previous GET), initialize sliders accordingly
    if (hiddenMinYear.value) minYear.value = hiddenMinYear.value;
    if (hiddenMaxYear.value) maxYear.value = hiddenMaxYear.value;
    if (hiddenMinPrice.value) minPrice.value = hiddenMinPrice.value;
    if (hiddenMaxPrice.value) maxPrice.value = hiddenMaxPrice.value;

    // Year slider update
    function updateYear() {
      let min = parseInt(minYear.value);
      let max = parseInt(maxYear.value);
      if (min > max) min = max;
      yearDisplay.textContent = `${min} - ${max}`;
      hiddenMinYear.value = minYear.value;
      hiddenMaxYear.value = maxYear.value;
    }
    minYear.addEventListener("input", updateYear);
    maxYear.addEventListener("input", updateYear);
  updateYear();

    // Price slider update
    function updatePrice() {
      let min = parseInt(minPrice.value);
      let max = parseInt(maxPrice.value);
      if (min > max) min = max;
      priceDisplay.textContent = `RM ${min.toLocaleString()} - RM ${max.toLocaleString()}`;
      hiddenMinPrice.value = minPrice.value;
      hiddenMaxPrice.value = maxPrice.value;
    }
    minPrice.addEventListener("input", updatePrice);
    maxPrice.addEventListener("input", updatePrice);
  updatePrice();

    // Popup events
    openPopup.addEventListener("click", (e) => {
      e.preventDefault();
      popup.classList.remove("hidden");
      popupMinPrice.value = minPrice.value;
      popupMaxPrice.value = maxPrice.value;
    });

    cancelPopup.addEventListener("click", () => {
      popup.classList.add("hidden");
    });

    applyPopup.addEventListener("click", () => {
      let min = parseInt(popupMinPrice.value) || 1;
      let max = parseInt(popupMaxPrice.value) || 100000000;
      if (min > max) {
        alert("Minimum price cannot be greater than maximum price.");
        return;
      }
      minPrice.value = min;
      maxPrice.value = max;
      updatePrice();
      popup.classList.add("hidden");
    });

  // --- Fuel Economy Calculator ---
  (function(){
    const dist = document.getElementById('feDistance');
    const distUnit = document.getElementById('feDistUnit');
    const fuel = document.getElementById('feFuel');
    const fuelUnit = document.getElementById('feFuelUnit');
    const mpgUS = document.getElementById('feMpgUS');
    const mpgUK = document.getElementById('feMpgUK');
    const lPerKm = document.getElementById('feLperKm');
    const lPer100 = document.getElementById('feLper100');

    function fmt(n, d=2){
      if (!isFinite(n)) return '—';
      return Number(n).toLocaleString(undefined, {minimumFractionDigits:d, maximumFractionDigits:d});
    }
    function recalc(){
      const dVal = parseFloat(dist.value);
      const fVal = parseFloat(fuel.value);
      if (!isFinite(dVal) || dVal <= 0 || !isFinite(fVal) || fVal <= 0) {
        mpgUS.textContent = mpgUK.textContent = lPerKm.textContent = lPer100.textContent = '—';
        return;
      }
      // Convert to base units: km and liters
      const km = distUnit.value === 'mi' ? dVal * 1.609344 : dVal; // 1 mile = 1.609344 km
      let liters;
      if (fuelUnit.value === 'L') liters = fVal;
      else if (fuelUnit.value === 'galUS') liters = fVal * 3.785411784; // 1 US gal = 3.785411784 L
      else liters = fVal * 4.54609; // 1 UK gal = 4.54609 L

      // Compute metrics
      const kmPerL = km / liters; // km per liter
      const lPerKmVal = liters / km; // L per km
      const lPer100Val = lPerKmVal * 100; // L per 100km
      const miles = km / 1.609344;
      const galUS = liters / 3.785411784;
      const galUK = liters / 4.54609;
      const mpgUSVal = miles / galUS;
      const mpgUKVal = miles / galUK;

      mpgUS.textContent = fmt(mpgUSVal);
      mpgUK.textContent = fmt(mpgUKVal);
      lPerKm.textContent = fmt(lPerKmVal, 3);
      lPer100.textContent = fmt(lPer100Val);
    }
    ['input','change'].forEach(evt => {
      dist.addEventListener(evt, recalc);
      distUnit.addEventListener(evt, recalc);
      fuel.addEventListener(evt, recalc);
      fuelUnit.addEventListener(evt, recalc);
    });
  })();

  // --- Mini Insight Chart ---
  (function(){
    const data = <?php echo json_encode($insight, JSON_UNESCAPED_UNICODE); ?>;
    if (!data || !data.labels || data.labels.length === 0) return;
    const ctx = document.getElementById('miniInsightChart');
    if (!ctx) return;
    const palette = ['#ef4444','#f59e0b','#10b981','#3b82f6','#8b5cf6','#ec4899','#14b8a6','#22c55e','#f97316','#a1a1aa'];
    const bg = data.values.map((_, i) => palette[i % palette.length] + 'CC');
    const border = data.values.map((_, i) => palette[i % palette.length]);
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: data.title,
          data: data.values,
          backgroundColor: bg,
          borderColor: border,
          borderWidth: 1,
          borderRadius: 6,
          barThickness: 18
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        scales: {
          x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
          y: { ticks: { color: '#374151' }, grid: { display: false } }
        },
        plugins: {
          legend: { display: false },
          tooltip: { enabled: true },
          title: { display: false }
        }
      }
    });
  })();

  // --- Road Tax & Insurance Calculators ---
  (function(){
    // Tab toggle
    const rtTab = document.getElementById('rtTab');
    const insTab = document.getElementById('insTab');
    const rtPanel = document.getElementById('rtPanel');
    const insPanel = document.getElementById('insPanel');
    function setTab(which){
      if (which === 'rt') {
        rtTab.className = 'px-4 py-2 bg-red-600 text-white font-semibold';
        insTab.className = 'px-4 py-2 bg-white text-gray-700';
        rtPanel.classList.remove('hidden');
        rtPanel.classList.add('block');
        insPanel.classList.add('hidden');
      } else {
        insTab.className = 'px-4 py-2 bg-red-600 text-white font-semibold';
        rtTab.className = 'px-4 py-2 bg-white text-gray-700';
        insPanel.classList.remove('hidden');
        insPanel.classList.add('block');
        rtPanel.classList.add('hidden');
      }
    }
    rtTab?.addEventListener('click', () => setTab('rt'));
    insTab?.addEventListener('click', () => setTab('ins'));

    // Road tax estimate (approx): base on West MY saloon schedule
    // Breakpoints (cc): <=1000, <=1200, <=1400, <=1600, <=1800, <=2000, <=2500, <=3000, <=>3000
    // Rates (RM): base + per-cc above lower bound
    const schedules = {
      saloon: [
        {max:1000, base:20, percc:0},
        {max:1200, base:55, percc:0},
        {max:1400, base:70, percc:0},
        {max:1600, base:90, percc:0},
        {max:1800, base:200, percc:0.40, from:1600},
        {max:2000, base:280, percc:0.50, from:1800},
        {max:2500, base:380, percc:1.00, from:2000},
        {max:3000, base:880, percc:2.50, from:2500},
        {max:Infinity, base:2130, percc:4.50, from:3000}
      ],
      nonsaloon: [
        {max:1000, base:20, percc:0},
        {max:1200, base:45, percc:0},
        {max:1400, base:60, percc:0},
        {max:1600, base:70, percc:0},
        {max:1800, base:160, percc:0.30, from:1600},
        {max:2000, base:220, percc:0.40, from:1800},
        {max:2500, base:300, percc:0.80, from:2000},
        {max:3000, base:700, percc:1.60, from:2500},
        {max:Infinity, base:1500, percc:3.00, from:3000}
      ]
    };
    function computeRoadTax(cc, type, region){
      const sched = type === 'saloon' ? schedules.saloon : schedules.nonsaloon;
      let tax = 0;
      for (const band of sched){
        if (cc <= band.max){
          if (band.percc && band.from){
            const above = Math.max(0, cc - band.from);
            tax = band.base + above * band.percc;
          } else {
            tax = band.base;
          }
          break;
        }
      }
      // Adjusters (rough): East MY ~0.5x for lower bands
      let factor = 1;
      if (region === 'east') factor *= 0.5;
      return Math.max(0, tax * factor);
    }
    // EV kW-based schedule (Malaysia). Bands use 10 kW blocks within each band, with base covering the first 10 kW (except band 1 which starts incrementing above 50 kW).
    function computeEvRoadTaxKw(kw){
      if (!isFinite(kw) || kw <= 0) return 0;
      if (kw > 1010) return 20000; // cap above 1,010 kW
      // Helper to compute blocks
      function blocks(fromKw, toKw, blockStartKw){
        const spanTop = Math.min(kw, toKw);
        const over = spanTop - blockStartKw;
        if (over <= 0) return 0;
        return Math.ceil(over / 10);
      }
      // Band definitions
      if (kw <= 100){
        // Band 1: up to 100 kW — Base RM 20, +RM10 per 10 kW block above 50 kW, max RM 70
        const base = 20, inc = 10, max = 70;
        const blk = Math.min(5, blocks(0, 100, 50));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 210){
        // Band 2: 100.001 to 210 kW — Base RM 80, +RM20 per 10 kW block above 110 kW, max RM 280
        const base = 80, inc = 20, max = 280;
        const blk = Math.min(10, blocks(100, 210, 110));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 310){
        // Band 3: 210.001 to 310 kW — Base RM 305, +RM30 per 10 kW block above 220 kW, max RM 575
        const base = 305, inc = 30, max = 575;
        const blk = Math.min(9, blocks(210, 310, 220));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 410){
        // Band 4: 310.001 to 410 kW — Base RM 615, +RM50 per 10 kW block above 320 kW, max RM 1,065
        const base = 615, inc = 50, max = 1065;
        const blk = Math.min(9, blocks(310, 410, 320));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 510){
        // Band 5: 410.001 to 510 kW — Base RM 1,140, +RM100 per 10 kW block above 420 kW, max RM 2,040
        const base = 1140, inc = 100, max = 2040;
        const blk = Math.min(9, blocks(410, 510, 420));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 610){
        // Band 6: 510.001 to 610 kW — Base RM 2,165, +RM150 per 10 kW block above 520 kW, max RM 3,515
        const base = 2165, inc = 150, max = 3515;
        const blk = Math.min(9, blocks(510, 610, 520));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 710){
        // Band 7: 610.001 to 710 kW — Base RM 3,690, +RM200 per 10 kW block above 620 kW, max RM 5,490
        const base = 3690, inc = 200, max = 5490;
        const blk = Math.min(9, blocks(610, 710, 620));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 810){
        // Band 8: 710.001 to 810 kW — Base RM 5,715, +RM250 per 10 kW block above 720 kW, max RM 7,965
        const base = 5715, inc = 250, max = 7965;
        const blk = Math.min(9, blocks(710, 810, 720));
        return Math.min(max, base + inc * blk);
      } else if (kw <= 910){
        // Band 9: 810.001 to 910 kW — Base RM 8,240, +RM300 per 10 kW block above 820 kW, max RM 10,940
        const base = 8240, inc = 300, max = 10940;
        const blk = Math.min(9, blocks(810, 910, 820));
        return Math.min(max, base + inc * blk);
      } else {
        // Band 10: 910.001 to 1,010 kW — Base RM 11,265, +RM350 per 10 kW block above 920 kW, max RM 14,415
        const base = 11265, inc = 350, max = 14415;
        const blk = Math.min(9, blocks(910, 1010, 920));
        return Math.min(max, base + inc * blk);
      }
    }
    function fmtRM(n){
      return 'RM ' + (Number(n)||0).toLocaleString('en-MY', {minimumFractionDigits:2, maximumFractionDigits:2});
    }
    const rtPower = document.getElementById('rtPower');
    const rtCC = document.getElementById('rtCC');
    const rtType = document.getElementById('rtType');
    const rtRegion = document.getElementById('rtRegion');
    const rtResult = document.getElementById('rtResult');
    const rtNote = document.getElementById('rtNote');
    const rtCCLabel = document.getElementById('rtCCLabel');
    function recalcRT(){
      const isEV = rtPower && rtPower.value === 'ev';
      if (isEV) {
        const kw = parseFloat(rtCC.value);
        if (!isFinite(kw) || kw <= 0) { rtResult.textContent = '—'; rtNote.textContent=''; return; }
        const val = computeEvRoadTaxKw(kw);
        rtResult.textContent = fmtRM(val);
        rtNote.textContent = '';
        return;
      }
      rtNote.textContent = '';
      const cc = parseInt(rtCC.value, 10);
      if (!isFinite(cc) || cc <= 0) { rtResult.textContent = '—'; return; }
      const val = computeRoadTax(cc, rtType.value === 'saloon' ? 'saloon' : 'nonsaloon', rtRegion.value);
      rtResult.textContent = fmtRM(val);
    }
    ['input','change'].forEach(evt => {
      rtPower?.addEventListener(evt, () => {
        const isEV = rtPower.value === 'ev';
        // Switch input semantics: cc -> kW
        if (isEV) {
          rtCC.disabled = false; rtCC.classList.remove('bg-gray-100');
          rtCCLabel.textContent = 'Motor Power (kW)';
          rtCC.min = '1'; rtCC.step = '0.1'; rtCC.placeholder = 'e.g. 150';
          rtType.disabled = true; rtType.classList.add('bg-gray-100');
          rtRegion.disabled = true; rtRegion.classList.add('bg-gray-100');
        } else {
          rtCC.disabled = false; rtCC.classList.remove('bg-gray-100');
          rtCCLabel.textContent = 'Engine Capacity (cc)';
          rtCC.min = '100'; rtCC.step = '1'; rtCC.placeholder = 'e.g. 1998';
          rtType.disabled = false; rtType.classList.remove('bg-gray-100');
          rtRegion.disabled = false; rtRegion.classList.remove('bg-gray-100');
        }
        recalcRT();
      });
      rtCC?.addEventListener(evt, recalcRT);
      rtType?.addEventListener(evt, recalcRT);
      rtRegion?.addEventListener(evt, recalcRT);
    });
    // Initialize UI state and compute once on load
    if (rtPower) {
      const evInit = () => {
        const isEV = rtPower.value === 'ev';
        if (isEV) {
          rtCCLabel.textContent = 'Motor Power (kW)';
          rtCC.min = '1'; rtCC.step = '0.1'; rtCC.placeholder = 'e.g. 150';
          rtType.disabled = true; rtType.classList.add('bg-gray-100');
          rtRegion.disabled = true; rtRegion.classList.add('bg-gray-100');
        } else {
          rtCCLabel.textContent = 'Engine Capacity (cc)';
          rtCC.min = '100'; rtCC.step = '1'; rtCC.placeholder = 'e.g. 1998';
          rtType.disabled = false; rtType.classList.remove('bg-gray-100');
          rtRegion.disabled = false; rtRegion.classList.remove('bg-gray-100');
        }
        recalcRT();
      };
      // run after current call stack to ensure elements are ready
      setTimeout(evInit, 0);
    }

    // Insurance estimate
    const insSum = document.getElementById('insSum');
  const insRateCat = document.getElementById('insRateCat');
    const insNCD = document.getElementById('insNCD');
    const insNCDPreset = document.getElementById('insNCDPreset');
    const insSST = document.getElementById('insSST');
    const insStamp = document.getElementById('insStamp');
    const insBase = document.getElementById('insBase');
    const insAfterNcd = document.getElementById('insAfterNcd');
    const insTaxStamp = document.getElementById('insTaxStamp');
    const insTotalMin = document.getElementById('insTotalMin');
    const insTotalMax = document.getElementById('insTotalMax');
    const insRangeNote = document.getElementById('insRangeNote');
    function recalcINS(){
      const sum = parseFloat(insSum.value);
      // Rates are per RM100; compute min/max based on category
      const minRate = parseFloat(insRateCat?.selectedOptions?.[0]?.dataset?.min || '0');
      const maxRate = parseFloat(insRateCat?.selectedOptions?.[0]?.dataset?.max || '0');
      const ncd = parseFloat(insNCD.value) / 100;
      const sst = parseFloat(insSST.value) / 100;
      const stamp = parseFloat(insStamp.value) || 0;
      if (!isFinite(sum) || sum <= 0 || !isFinite(minRate) || !isFinite(maxRate)) {
        insBase.textContent = insAfterNcd.textContent = insTaxStamp.textContent = insTotalMin.textContent = insTotalMax.textContent = '—';
        insRangeNote.textContent = '';
        return;
      }
      // Convert per RM100 to fraction
      const rateMinFrac = (minRate / 100);
      const rateMaxFrac = (maxRate / 100);
      const baseMin = sum * rateMinFrac;
      const baseMax = sum * rateMaxFrac;
      const ncdMin = baseMin * ncd;
      const ncdMax = baseMax * ncd;
      const afterMin = Math.max(0, baseMin - ncdMin);
      const afterMax = Math.max(0, baseMax - ncdMax);
      const sstMin = afterMin * sst;
      const sstMax = afterMax * sst;
      const totalMin = afterMin + sstMin + stamp;
      const totalMax = afterMax + sstMax + stamp;
      insBase.textContent = `${fmtRM(baseMin)} – ${fmtRM(baseMax)}`;
      insAfterNcd.textContent = `${fmtRM(afterMin)} – ${fmtRM(afterMax)}`;
      insTaxStamp.textContent = `${fmtRM(sstMin + stamp)} – ${fmtRM(sstMax + stamp)}`;
      insTotalMin.textContent = fmtRM(totalMin);
      insTotalMax.textContent = fmtRM(totalMax);
      insRangeNote.textContent = `Note: Final premium should range from ${fmtRM(totalMin)} to ${fmtRM(totalMax)} depending on insurer.`;
    }
    insNCDPreset?.addEventListener('change', () => { insNCD.value = insNCDPreset.value; recalcINS(); });
    ['input','change'].forEach(evt => {
      insSum?.addEventListener(evt, recalcINS);
      insRateCat?.addEventListener(evt, recalcINS);
      insNCD?.addEventListener(evt, recalcINS);
      insSST?.addEventListener(evt, recalcINS);
      insStamp?.addEventListener(evt, recalcINS);
    });
  })();
  </script>

</body>
</html>

</body>
</html>
