<?php
// compare.php
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) {
  die("DB error: " . $mysqli->connect_error);
}

function fetchOne($mysqli, $carId) {
  $carId = (int)$carId;
  if ($carId <= 0) return null;
  $sql = "SELECT c.*, cd.color, cd.horsepower, cd.engine_code, cd.gear_numbers, cd.front_wheel_size, cd.rear_wheel_size, cd.torque, cd.car_type, cd.car_condition
          FROM cars c
          LEFT JOIN car_details cd ON cd.car_id = c.car_id
          WHERE c.car_id = $carId LIMIT 1";
  if (!$res = $mysqli->query($sql)) return null;
  $row = $res->fetch_assoc();
  $res->free();
  if (!$row) return null;
  // thumbnail
  $thumb = null;
  $q1 = "SELECT ci.image_path FROM car_images ci
          JOIN (SELECT MIN(image_id) AS min_id FROM car_images WHERE car_id=$carId AND (is_thumbnail=1 OR is_thumbnail='1')) t
          ON t.min_id = ci.image_id LIMIT 1";
  if ($r1 = $mysqli->query($q1)) { $tmp = $r1->fetch_row(); if ($tmp) $thumb = $tmp[0]; $r1->free(); }
  if (!$thumb) {
    $q2 = "SELECT ci.image_path FROM car_images ci
            JOIN (SELECT MIN(image_id) AS min_id FROM car_images WHERE car_id=$carId) t
            ON t.min_id = ci.image_id LIMIT 1";
    if ($r2 = $mysqli->query($q2)) { $tmp = $r2->fetch_row(); if ($tmp) $thumb = $tmp[0]; $r2->free(); }
  }
  $row['thumb'] = $thumb ?: 'assets/logos/no-image.png';
  return $row;
}

// Options for selectors (loaded dynamically via AJAX now)
$list = [];

$aId = isset($_GET['a']) ? (int)$_GET['a'] : 0;
$bId = isset($_GET['b']) ? (int)$_GET['b'] : 0;
$carA = $aId ? fetchOne($mysqli, $aId) : null;
$carB = $bId ? fetchOne($mysqli, $bId) : null;

function fmtRM($n){ return 'RM ' . number_format((float)$n, 2); }
function safe($v){ return htmlspecialchars((string)$v); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Compare Cars - GVC</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
  <!-- HEADER -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">Great Value Car (GVC)</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="list_cars.php" class="hover:underline">Listings</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
            <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
          <?php endif; ?>
          <li class="relative" id="moreMenu">
            <button id="moreBtn" class="inline-flex items-center gap-1 px-3 py-1 bg-white bg-opacity-10 hover:bg-opacity-20 rounded">
              <span>More</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div id="morePanel" class="hidden absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
              <a href="analysis.php" class="block px-4 py-2 hover:bg-gray-100">Analysis</a>
              <a href="saved_search.php" class="block px-4 py-2 hover:bg-gray-100">Saved</a>
              <a href="compare.php" class="block px-4 py-2 hover:bg-gray-100">Compare</a>
              <a href="about.php" class="block px-4 py-2 hover:bg-gray-100">About</a>
            </div>
          </li>
          <li><a href="logout.php" class="hover:underline">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>
  <script>
    (function(){
      const menu = document.getElementById('moreMenu');
      const btn = document.getElementById('moreBtn');
      const panel = document.getElementById('morePanel');
      if (!menu || !btn || !panel) return;
      btn.addEventListener('click', (e) => { e.preventDefault(); panel.classList.toggle('hidden'); });
      document.addEventListener('click', (e) => { if (!menu.contains(e.target)) panel.classList.add('hidden'); });
    })();
  </script>

  <!-- CONTENT -->
  <main class="flex-grow">
    <div class="container mx-auto max-w-7xl px-4 py-6">
      <h2 class="text-3xl font-extrabold mb-4">Compare Cars</h2>
      <form id="compareForm" method="get" class="bg-white rounded-xl shadow p-4 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
          <?php
            // Helper to build display label (without car_id)
            $buildLabel = function($row){
              $mk=$row['make']??''; $md=$row['model']??''; $vr=$row['variant']??''; $yr=$row['year']??''; $pr=fmtRM($row['price']??0);
              $name = trim($mk.' '.$md.' '.($vr?:''));
              return "{$name} {$yr} ({$pr})";
            };
            $prefillA = '';
            if ($carA) { $prefillA = $buildLabel($carA); }
            $prefillB = '';
            if ($carB) { $prefillB = $buildLabel($carB); }
          ?>
          <div>
            <label class="block text-sm mb-1">Car A</label>
            <input id="aInput" list="carsA" class="w-full p-2 border rounded" placeholder="Type make, model, variant or year" value="<?php echo safe($prefillA); ?>">
            <datalist id="carsA"></datalist>
            <input type="hidden" name="a" id="aHidden" value="<?php echo $aId ?: ''; ?>">
          </div>
          <div>
            <label class="block text-sm mb-1">Car B</label>
            <input id="bInput" list="carsB" class="w-full p-2 border rounded" placeholder="Type make, model, variant or year" value="<?php echo safe($prefillB); ?>">
            <datalist id="carsB"></datalist>
            <input type="hidden" name="b" id="bHidden" value="<?php echo $bId ?: ''; ?>">
          </div>
          <div class="flex gap-2">
            <button class="px-4 py-2 bg-red-600 text-white rounded w-full lg:w-auto">Compare</button>
            <a href="#" id="swapAB" class="px-4 py-2 bg-gray-200 rounded w-full lg:w-auto text-center">Swap</a>
            <a href="compare.php" class="px-4 py-2 bg-gray-200 rounded w-full lg:w-auto text-center">Clear</a>
          </div>
        </div>
      </form>

      <?php if ($carA && $carB): ?>
        <?php
          // Prepare metrics for radar (normalized between the two)
          $yearA = (int)($carA['year'] ?? 0); $yearB = (int)($carB['year'] ?? 0);
          $hpA = (float)($carA['horsepower'] ?? 0); $hpB = (float)($carB['horsepower'] ?? 0);
          $tqA = (float)($carA['torque'] ?? 0); $tqB = (float)($carB['torque'] ?? 0);
          $miA = (float)($carA['mileage'] ?? 0); $miB = (float)($carB['mileage'] ?? 0);
          $prA = (float)($carA['price'] ?? 0); $prB = (float)($carB['price'] ?? 0);
          function normPair($a,$b,$invert=false){
            $min = min($a,$b); $max = max($a,$b);
            if ($max == $min) return [1,1];
            if ($invert){
              $na = ($max - $a)/($max-$min); $nb = ($max - $b)/($max-$min);
            } else {
              $na = ($a - $min)/($max-$min); $nb = ($b - $min)/($max-$min);
            }
            return [round($na*100,1), round($nb*100,1)];
          }
          [$nYearA,$nYearB] = normPair($yearA,$yearB,false);
          [$nHpA,$nHpB] = normPair($hpA,$hpB,false);
          [$nTqA,$nTqB] = normPair($tqA,$tqB,false);
          [$nMiA,$nMiB] = normPair($miA,$miB,true);
          [$nPrA,$nPrB] = normPair($prA,$prB,true);
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
          <!-- Left card -->
          <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex gap-3 items-center">
              <div class="w-28 h-20 bg-gray-100 overflow-hidden rounded">
                <img src="<?php echo safe($carA['thumb']); ?>" alt="<?php echo safe($carA['make'].' '.$carA['model']); ?>" class="w-full h-full object-cover">
              </div>
              <div>
                <div class="text-xs text-gray-500">Car A</div>
                <div class="font-bold text-lg"><?php echo safe($carA['make'].' '.$carA['model']); ?></div>
                <div class="text-gray-600 text-sm"><?php echo safe($carA['variant'] ?? ''); ?></div>
              </div>
              <div class="ml-auto text-right">
                <div class="text-gray-500 text-sm"><?php echo (int)$carA['year']; ?></div>
                <div class="text-red-600 font-extrabold"><?php echo fmtRM($carA['price']); ?></div>
              </div>
            </div>
          </div>
          <!-- Right card -->
          <div class="bg-white rounded-2xl shadow p-4">
            <div class="flex gap-3 items-center">
              <div class="w-28 h-20 bg-gray-100 overflow-hidden rounded order-2 xl:order-none ml-auto">
                <img src="<?php echo safe($carB['thumb']); ?>" alt="<?php echo safe($carB['make'].' '.$carB['model']); ?>" class="w-full h-full object-cover">
              </div>
              <div>
                <div class="text-xs text-gray-500">Car B</div>
                <div class="font-bold text-lg"><?php echo safe($carB['make'].' '.$carB['model']); ?></div>
                <div class="text-gray-600 text-sm"><?php echo safe($carB['variant'] ?? ''); ?></div>
              </div>
              <div class="text-right">
                <div class="text-gray-500 text-sm"><?php echo (int)$carB['year']; ?></div>
                <div class="text-red-600 font-extrabold"><?php echo fmtRM($carB['price']); ?></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Stats table -->
        <?php
          function betterBadge($a,$b,$type='higher'){ // returns class for A relative to B
            if (!is_numeric($a) || !is_numeric($b)) return '';
            if ($a == $b) return '';
            $isBetter = ($type==='higher') ? ($a>$b) : ($a<$b);
            return $isBetter ? 'text-green-600' : 'text-red-600';
          }
        ?>
        <div class="mt-6 bg-white rounded-2xl shadow overflow-hidden max-w-4xl mx-auto">
          <div class="grid grid-cols-3 text-xs font-semibold text-gray-500 uppercase tracking-wide p-3 border-b">
            <div class="text-left">Car A</div>
            <div class="text-center">Metric</div>
            <div class="text-right">Car B</div>
          </div>
          <div class="divide-y">
            <?php
              $rows = [
                ['label'=>'Year','a'=>$yearA,'b'=>$yearB,'type'=>'higher','fmt'=>'year'],
                ['label'=>'Price','a'=>$prA,'b'=>$prB,'type'=>'lower','fmt'=>'rm'],
                ['label'=>'Mileage (km)','a'=>$miA,'b'=>$miB,'type'=>'lower'],
                ['label'=>'Horsepower (hp)','a'=>$hpA,'b'=>$hpB,'type'=>'higher'],
                ['label'=>'Torque (Nm)','a'=>$tqA,'b'=>$tqB,'type'=>'higher'],
                ['label'=>'Engine Capacity (L)','a'=>$carA['engine_capacity']??null,'b'=>$carB['engine_capacity']??null,'type'=>'higher','fmt'=>'engine'],
                ['label'=>'Transmission','a'=>$carA['transmission']??'','b'=>$carB['transmission']??''],
                ['label'=>'Fuel','a'=>$carA['fuel']??'','b'=>$carB['fuel']??''],
                ['label'=>'Drive System','a'=>$carA['drive_system']??'','b'=>$carB['drive_system']??''],
                ['label'=>'Doors','a'=>$carA['doors']??'','b'=>$carB['doors']??''],
                ['label'=>'Condition','a'=>$carA['car_condition']??'','b'=>$carB['car_condition']??''],
                ['label'=>'Color','a'=>$carA['color']??'','b'=>$carB['color']??''],
                ['label'=>'Gear Numbers','a'=>$carA['gear_numbers']??'','b'=>$carB['gear_numbers']??''],
                ['label'=>'Front Wheel Size','a'=>$carA['front_wheel_size']??'','b'=>$carB['front_wheel_size']??''],
                ['label'=>'Rear Wheel Size','a'=>$carA['rear_wheel_size']??'','b'=>$carB['rear_wheel_size']??''],
              ];
              foreach ($rows as $r):
                $a = $r['a']; $b = $r['b']; $lab = $r['label']; $type = $r['type'] ?? null; $fmt = $r['fmt'] ?? null;
                // Formatting per field
                $dispA = '';
                $dispB = '';
                if ($fmt === 'rm') {
                  $dispA = fmtRM($a);
                  $dispB = fmtRM($b);
                } elseif ($fmt === 'year') {
                  $dispA = is_numeric($a) ? (string)intval($a) : safe($a);
                  $dispB = is_numeric($b) ? (string)intval($b) : safe($b);
                } elseif ($fmt === 'engine') {
                  $dispA = is_numeric($a) ? number_format((float)$a, 1) : safe($a);
                  $dispB = is_numeric($b) ? number_format((float)$b, 1) : safe($b);
                } else {
                  $dispA = is_numeric($a) ? number_format((float)$a) : safe($a);
                  $dispB = is_numeric($b) ? number_format((float)$b) : safe($b);
                }
                $clsA = $type ? betterBadge($a,$b,$type) : '';
                $clsB = $type ? betterBadge($b,$a,$type) : '';
            ?>
            <div class="grid grid-cols-3 p-3 items-center">
              <div class="text-left <?php echo $clsA; ?>"><?php echo $dispA; ?></div>
              <div class="text-center text-sm text-gray-700 font-medium"><?php echo safe($lab); ?></div>
              <div class="text-right <?php echo $clsB; ?>"><?php echo $dispB; ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">Select two cars and press Compare to see a side-by-side comparison with specs and pricing.</div>
      <?php endif; ?>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white p-4 mt-auto">
    <div class="container mx-auto text-center">
      <p>&copy; <?php echo date('Y'); ?> Great Value Car (GVC). All rights reserved.</p>
    </div>
  </footer>

  <script>
    // On submit, parse the selected display strings to extract #ID and put into hidden fields
    (function(){
      const form = document.getElementById('compareForm');
      const aInput = document.getElementById('aInput');
      const bInput = document.getElementById('bInput');
      const aHidden = document.getElementById('aHidden');
      const bHidden = document.getElementById('bHidden');
      function extractId(str){
        const m = (str||'').match(/^#?(\d+)/);
        return m ? m[1] : '';
      }
      // Only set from typed numeric id if hidden is still empty;
      // preserve IDs already mapped from suggestions.
      form?.addEventListener('submit', function(){
        if (!aHidden.value) {
          aHidden.value = extractId(aInput.value);
        }
        if (!bHidden.value) {
          bHidden.value = extractId(bInput.value);
        }
      });
    })();

    // Swap helper
    document.getElementById('swapAB')?.addEventListener('click', function(e){
      e.preventDefault();
      const params = new URLSearchParams(window.location.search);
      const a = params.get('a'); const b = params.get('b');
      if (!a && !b) return; params.set('a', b||''); params.set('b', a||'');
      window.location.search = params.toString();
    });
  </script>
  <?php if (!($carA && $carB)): ?>
  <script>
    // Swap link should still work even without chart
    document.getElementById('swapAB')?.addEventListener('click', function(e){
      e.preventDefault();
      const params = new URLSearchParams(window.location.search);
      const a = params.get('a'); const b = params.get('b');
      if (!a && !b) return; params.set('a', b||''); params.set('b', a||'');
      window.location.search = params.toString();
    });

    // Dynamic search for Car A and Car B using backend endpoint (full DB)
    (function(){
      const aInput = document.getElementById('aInput');
      const bInput = document.getElementById('bInput');
      const dlA = document.getElementById('carsA');
      const dlB = document.getElementById('carsB');
      const aHidden = document.getElementById('aHidden');
      const bHidden = document.getElementById('bHidden');
      let lastAItems = [];
      let lastBItems = [];

      function debounce(fn, ms){ let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); }; }

      async function fetchOptions(query){
        if (!query) return [];
        try {
          const res = await fetch(`compare_search.php?q=${encodeURIComponent(query)}`);
          if (!res.ok) return [];
          const data = await res.json();
          return Array.isArray(data) ? data : [];
        } catch(e){ return []; }
      }

      function populateDatalist(datalist, items){
        while (datalist.firstChild) datalist.removeChild(datalist.firstChild);
        items.forEach(it=>{
          const opt = document.createElement('option');
          opt.value = it.label;
          datalist.appendChild(opt);
        });
      }

      const onTypeA = debounce(async (e)=>{
        const items = await fetchOptions(e.target.value);
        lastAItems = items;
        populateDatalist(dlA, items);
      }, 200);
      const onTypeB = debounce(async (e)=>{
        const items = await fetchOptions(e.target.value);
        lastBItems = items;
        populateDatalist(dlB, items);
      }, 200);

      function resolveHiddenFromLabel(inputEl, items, hiddenEl){
        const val = inputEl.value || '';
        const found = items.find(it => it.label === val);
        hiddenEl.value = found && found.id != null ? String(found.id) : '';
      }

      aInput?.addEventListener('change', ()=>resolveHiddenFromLabel(aInput, lastAItems, aHidden));
      bInput?.addEventListener('change', ()=>resolveHiddenFromLabel(bInput, lastBItems, bHidden));

      aInput?.addEventListener('input', onTypeA);
      bInput?.addEventListener('input', onTypeB);
    })();
  </script>
  <?php endif; ?>
</body>
</html>
