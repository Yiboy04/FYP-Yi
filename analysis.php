<?php
// analysis.php — Sales analysis by make
session_start();
$mysqli = new mysqli('localhost','root','','fyp');
if ($mysqli->connect_errno) { die('DB error: ' . $mysqli->connect_error); }

// Fixed settings for viewing-only: Top 10, no year filters
$top = 10;

$where = ["listing_status='sold'"]; // count only sold cars
$whereSQL = 'WHERE ' . implode(' AND ', $where);

$sql = "SELECT make, COUNT(*) AS total FROM cars $whereSQL GROUP BY make ORDER BY total DESC, make ASC";
$res = $mysqli->query($sql);
$data = [];
if ($res) {
  while ($row = $res->fetch_assoc()) { $data[] = $row; }
}

// Prepare top-N arrays for chart
$labels = [];
$counts = [];
foreach (array_slice($data, 0, $top) as $r) {
  $labels[] = $r['make'] ?? '—';
  $counts[] = (int)$r['total'];
}

// Prepare Top-N by model as well
$sqlModel = "SELECT model, COUNT(*) AS total FROM cars $whereSQL GROUP BY model ORDER BY total DESC, model ASC";
$resModel = $mysqli->query($sqlModel);
$modelData = [];
if ($resModel) {
  while ($row = $resModel->fetch_assoc()) { $modelData[] = $row; }
}
$modelLabels = [];
$modelCounts = [];
foreach (array_slice($modelData, 0, $top) as $r) {
  $modelLabels[] = $r['model'] ?? '—';
  $modelCounts[] = (int)$r['total'];
}

// Map each top model to its most common make (for logo beside the model)
$modelMakeMap = [];
if (!empty($modelLabels)) {
  // Build IN list safely
  $in = [];
  foreach ($modelLabels as $m) { $in[] = "'" . $mysqli->real_escape_string($m) . "'"; }
  $inList = implode(',', $in);
  $sqlModelMake = "SELECT model, make, COUNT(*) AS c FROM cars $whereSQL AND model IN ($inList) GROUP BY model, make ORDER BY model ASC, c DESC";
  $mmRes = $mysqli->query($sqlModelMake);
  if ($mmRes) {
    while ($row = $mmRes->fetch_assoc()) {
      $model = $row['model'];
      if (!isset($modelMakeMap[$model])) {
        $modelMakeMap[$model] = $row['make']; // first row per model is highest count due to ORDER BY c DESC
      }
    }
  }
}
// Prepare logo urls per model row (null where unknown)
$modelLogoUrls = [];
$modelMakeNames = [];
foreach ($modelLabels as $m) {
  $mk = $modelMakeMap[$m] ?? null;
  $modelMakeNames[] = $mk ?? '';
  $modelLogoUrls[] = $mk ? resolveMakeLogo($mk) : null;
}

// Prepare by transmission type as well (all sold cars, grouped by transmission)
$sqlTrans = "SELECT transmission, COUNT(*) AS total FROM cars $whereSQL GROUP BY transmission ORDER BY total DESC, transmission ASC";
$resTrans = $mysqli->query($sqlTrans);
$transLabels = [];
$transCounts = [];
if ($resTrans) {
  while ($row = $resTrans->fetch_assoc()) {
    $transLabels[] = $row['transmission'] ?? '—';
    $transCounts[] = (int)$row['total'];
  }
}

// Prepare by condition (join car_details)
$sqlCond = "SELECT COALESCE(cd.car_condition,'—') AS cond, COUNT(*) AS total
            FROM cars c
            JOIN car_details cd ON cd.car_id = c.car_id
            $whereSQL
            GROUP BY cd.car_condition
            ORDER BY total DESC, cond ASC";
$resCond = $mysqli->query($sqlCond);
$condLabels = [];
$condCounts = [];
if ($resCond) {
  while ($row = $resCond->fetch_assoc()) {
    $condLabels[] = $row['cond'];
    $condCounts[] = (int)$row['total'];
  }
}

// Use a common height sized for the largest set among views
$maxRows = max(count($labels), count($modelLabels), count($transLabels), count($condLabels));
$chartHeight = max(360, $maxRows * 44);

// Helper to resolve a logo image path for a make, with graceful fallback to an inline SVG monogram
function resolveMakeLogo($make) {
  $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim((string)$make)));
  // Known alias variants for certain brands (also tolerates common typos)
  $variants = [$slug];
  if (strpos($slug, 'mercedes') !== false) {
    // Try common spellings and a frequent typo "mercerdes"
    $variants = array_unique(array_merge($variants, [
      'mercedes-benz','mercedes','mercedesbenz','mercedes_benz','merc','mercerdes'
    ]));
  }
  // Use paths relative to this app folder (no leading slash) so they resolve under /FYP/
  $exts = ['svg','png','webp','jpg','jpeg'];
  foreach ($variants as $name) {
    foreach ($exts as $ext) {
      $rel = "assets/logos/$name.$ext";
      $abs = __DIR__ . '/' . $rel;
      if (file_exists($abs)) return $rel; // return first match
    }
  }
  // Fallback: generate a simple colored monogram SVG data URI (letter of the make)
  $initial = strtoupper(substr($make ?? '?', 0, 1));
  $palette = ['#EF4444','#F59E0B','#10B981','#3B82F6','#6366F1','#8B5CF6','#EC4899','#14B8A6','#84CC16','#F97316'];
  $hash = 0; for ($i=0; $i<strlen($slug); $i++) { $hash = (31*$hash + ord($slug[$i])) & 0x7fffffff; }
  $bg = $palette[$hash % count($palette)];
  $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">'
       . '<rect width="64" height="64" rx="8" ry="8" fill="'.$bg.'"/>'
       . '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial,Helvetica,sans-serif" font-size="32" fill="#ffffff">'
       . htmlspecialchars($initial, ENT_QUOTES, 'UTF-8')
       . '</text></svg>';
  $data = 'data:image/svg+xml;utf8,' . rawurlencode($svg);
  return $data;
}

// Prepare logo URLs for each label in order (used by the chart plugin)
$logoUrls = [];
foreach ($labels as $mk) {
  $logoUrls[] = resolveMakeLogo($mk);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Analysis</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
  <!-- Header (match main.php style) -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
      <nav>
        <ul class="flex gap-6 items-center">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="list_cars.php" class="hover:underline">Listings</a></li>
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

  <main class="container mx-auto flex-1 p-6">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
      <h2 class="text-2xl font-bold">Sales Analysis</h2>
      <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600 hidden md:inline">Top 10 • View-only</span>
        <label for="viewSelect" class="text-sm text-gray-700">View</label>
        <select id="viewSelect" class="border p-2 rounded">
          <option value="make" selected>By Make</option>
          <option value="model">By Model</option>
          <option value="transmission">By Transmission</option>
          <option value="condition">By Condition</option>
        </select>
      </div>
    </div>

    <?php if (empty($labels)): ?>
      <div class="bg-white rounded-xl shadow p-6 text-gray-600">No sales found yet. Once cars are marked as sold, they’ll appear here.</div>
    <?php else: ?>
      <div class="bg-white rounded-xl shadow p-6">
        <canvas id="salesChart" height="<?php echo $chartHeight; ?>"></canvas>
      </div>

      <div class="bg-white rounded-xl shadow p-6 mt-6">
        <h3 class="text-lg font-semibold mb-3">Data</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr id="dataHeadRow" class="text-left text-gray-600">
                <th class="py-2 pr-6">Make</th>
                <th class="py-2">Sold</th>
              </tr>
            </thead>
            <tbody id="dataBodyRows">
              <?php foreach (array_slice($data,0,$top) as $r): ?>
                <?php $logo = resolveMakeLogo($r['make']); ?>
                <tr class="border-t">
                  <td class="py-2 pr-6">
                    <span class="inline-flex items-center gap-2">
                      <img src="<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($r['make']); ?> logo" class="w-5 h-5 rounded object-contain" />
                      <span><?php echo htmlspecialchars($r['make']); ?></span>
                    </span>
                  </td>
                  <td class="py-2 font-semibold"><?php echo (int)$r['total']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <script>
  const makeLabels = <?php echo json_encode($labels); ?>;
  const makeCounts = <?php echo json_encode($counts); ?>;
  const logoUrls = <?php echo json_encode($logoUrls); ?>;
  const modelLabels = <?php echo json_encode($modelLabels); ?>;
  const modelCounts = <?php echo json_encode($modelCounts); ?>;
  const modelLogoUrls = <?php echo json_encode($modelLogoUrls); ?>;
  const modelMakeNames = <?php echo json_encode($modelMakeNames); ?>;
  const transLabels = <?php echo json_encode($transLabels); ?>;
  const transCounts = <?php echo json_encode($transCounts); ?>;
  const condLabels = <?php echo json_encode($condLabels); ?>;
  const condCounts = <?php echo json_encode($condCounts); ?>;

  // Preload images for logos
  const logoImages = logoUrls.map(url => { const img = new Image(); img.src = url; return img; });
  const modelLogoImages = modelLogoUrls.map(url => { if (!url) return null; const img = new Image(); img.src = url; return img; });

      // Plugin to draw logos beside the make name (between label and bars)
      const logoPlugin = {
        id: 'logoPlugin',
        afterDraw(chart, args, opts) {
          const { ctx, chartArea, scales } = chart;
          if (!opts || !opts.images) return;
          const y = scales.y; if (!y) return;
          const size = opts.size || 18; // icon size
          const gapToBar = opts.gapToBar || 6; // gap between icon and chart area (tick line)
          const xPos = chartArea.left - gapToBar - size; // render just left of tick line, within ticks.padding space
          ctx.save();
          (y.ticks || []).forEach((t, i) => {
            const img = opts.images[i]; if (!img) return;
            const yPos = y.getPixelForTick(i) - size/2;
            try { if (img.complete) ctx.drawImage(img, xPos, yPos, size, size); } catch(e) { /* ignore */ }
          });
          ctx.restore();
        }
      };

      const ctx = document.getElementById('salesChart').getContext('2d');
      const colorsFor = (n, step=37) => Array.from({length:n}, (_,i)=> `hsl(${(i*step)%360} 70% 55%)`);
      let currentView = 'make';
      const chart = new Chart(ctx, {
        type: 'bar',
        data: { labels: makeLabels, datasets: [{ label: 'Cars Sold', data: makeCounts, backgroundColor: colorsFor(makeCounts.length), barThickness: 24, maxBarThickness: 28, borderRadius: 4 }] },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          layout: { padding: { left: 16 } },
          plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: (ctx)=>` ${ctx.parsed.x ?? ctx.parsed.y} sold` } },
            // ticks.padding below reserves space between label and tick line for the icon
            logoPlugin: { images: logoImages, size: 26, gapToBar: 8 }
          },
          scales: {
            x: { beginAtZero: true, ticks: { precision:0, font: { size: 12 } } },
            y: { ticks: { autoSkip: false, padding: 40, font: { size: 14 } } }
          },
          indexAxis: 'y'
        },
        plugins: [logoPlugin]
      });

      // Ensure redraw after any image loads
      logoImages.forEach(img => { if (img && !img.complete) img.onload = () => chart.draw(); });
      modelLogoImages.forEach(img => { if (img && !img.complete) img.onload = () => chart.draw(); });

      // Table rendering
      const headRow = document.getElementById('dataHeadRow');
      const bodyRows = document.getElementById('dataBodyRows');

      function clearChildren(el){ while (el.firstChild) el.removeChild(el.firstChild); }

      function renderMakeTable(){
        // Header
        clearChildren(headRow);
        const th1 = document.createElement('th'); th1.className = 'py-2 pr-6'; th1.textContent = 'Make';
        const th2 = document.createElement('th'); th2.className = 'py-2'; th2.textContent = 'Sold';
        headRow.appendChild(th1); headRow.appendChild(th2);
        // Body
        clearChildren(bodyRows);
        makeLabels.forEach((mk, i) => {
          const tr = document.createElement('tr'); tr.className = 'border-t';
          const td1 = document.createElement('td'); td1.className = 'py-2 pr-6';
          const wrap = document.createElement('span'); wrap.className = 'inline-flex items-center gap-2';
          const img = document.createElement('img'); img.src = logoUrls[i]; img.alt = (mk||'') + ' logo'; img.className = 'w-5 h-5 rounded object-contain';
          const name = document.createElement('span'); name.textContent = mk || '—';
          wrap.appendChild(img); wrap.appendChild(name); td1.appendChild(wrap);
          const td2 = document.createElement('td'); td2.className = 'py-2 font-semibold'; td2.textContent = String(makeCounts[i] ?? 0);
          tr.appendChild(td1); tr.appendChild(td2); bodyRows.appendChild(tr);
        });
      }

      function renderModelTable(){
        // Header
        clearChildren(headRow);
        const th1 = document.createElement('th'); th1.className = 'py-2 pr-6'; th1.textContent = 'Model';
        const th2 = document.createElement('th'); th2.className = 'py-2 pr-6'; th2.textContent = 'Make';
        const th3 = document.createElement('th'); th3.className = 'py-2'; th3.textContent = 'Sold';
        headRow.appendChild(th1); headRow.appendChild(th2); headRow.appendChild(th3);
        // Body
        clearChildren(bodyRows);
        modelLabels.forEach((mdl, i) => {
          const tr = document.createElement('tr'); tr.className = 'border-t';
          // Model with brand logo (based on make)
          const td1 = document.createElement('td'); td1.className = 'py-2 pr-6';
          const wrap = document.createElement('span'); wrap.className = 'inline-flex items-center gap-2';
          const logo = modelLogoUrls[i];
          if (logo) { const img = document.createElement('img'); img.src = logo; img.alt = (modelMakeNames[i]||'') + ' logo'; img.className = 'w-5 h-5 rounded object-contain'; wrap.appendChild(img); }
          const name = document.createElement('span'); name.textContent = mdl || '—';
          wrap.appendChild(name); td1.appendChild(wrap);
          // Make name
          const td2 = document.createElement('td'); td2.className = 'py-2 pr-6'; td2.textContent = modelMakeNames[i] || '—';
          // Sold
          const td3 = document.createElement('td'); td3.className = 'py-2 font-semibold'; td3.textContent = String(modelCounts[i] ?? 0);
          tr.appendChild(td1); tr.appendChild(td2); tr.appendChild(td3); bodyRows.appendChild(tr);
        });
      }

      function renderTransmissionTable(){
        // Header
        clearChildren(headRow);
        const th1 = document.createElement('th'); th1.className = 'py-2 pr-6'; th1.textContent = 'Transmission';
        const th2 = document.createElement('th'); th2.className = 'py-2'; th2.textContent = 'Sold';
        headRow.appendChild(th1); headRow.appendChild(th2);
        // Body
        clearChildren(bodyRows);
        transLabels.forEach((t, i) => {
          const tr = document.createElement('tr'); tr.className = 'border-t';
          const td1 = document.createElement('td'); td1.className = 'py-2 pr-6'; td1.textContent = t || '—';
          const td2 = document.createElement('td'); td2.className = 'py-2 font-semibold'; td2.textContent = String(transCounts[i] ?? 0);
          tr.appendChild(td1); tr.appendChild(td2); bodyRows.appendChild(tr);
        });
      }

      function renderConditionTable(){
        // Header
        clearChildren(headRow);
        const th1 = document.createElement('th'); th1.className = 'py-2 pr-6'; th1.textContent = 'Condition';
        const th2 = document.createElement('th'); th2.className = 'py-2'; th2.textContent = 'Sold';
        headRow.appendChild(th1); headRow.appendChild(th2);
        // Body
        clearChildren(bodyRows);
        condLabels.forEach((c, i) => {
          const tr = document.createElement('tr'); tr.className = 'border-t';
          const td1 = document.createElement('td'); td1.className = 'py-2 pr-6'; td1.textContent = c || '—';
          const td2 = document.createElement('td'); td2.className = 'py-2 font-semibold'; td2.textContent = String(condCounts[i] ?? 0);
          tr.appendChild(td1); tr.appendChild(td2); bodyRows.appendChild(tr);
        });
      }

      // Ensure initial table reflects current view (make by default)
      renderMakeTable();

      // Toggle between Make and Model views using the selector
      document.getElementById('viewSelect').addEventListener('change', (e) => {
        const view = e.target.value; if (view === currentView) return;
        currentView = view;
        if (view === 'make') {
          chart.data.labels = makeLabels;
          chart.data.datasets[0].data = makeCounts;
          chart.data.datasets[0].backgroundColor = colorsFor(makeCounts.length, 37);
          chart.options.plugins.logoPlugin.images = logoImages; // show logos
          chart.options.scales.y.ticks.padding = 40;
          chart.options.indexAxis = 'y';
          renderMakeTable();
        } else {
          if (view === 'model') {
            chart.data.labels = modelLabels;
            chart.data.datasets[0].data = modelCounts;
            chart.data.datasets[0].backgroundColor = colorsFor(modelCounts.length, 43);
            chart.options.plugins.logoPlugin.images = modelLogoImages; // show make logos beside models
            chart.options.scales.y.ticks.padding = 40;
            chart.options.indexAxis = 'y';
            renderModelTable();
          } else if (view === 'transmission') {
            chart.data.labels = transLabels;
            chart.data.datasets[0].data = transCounts;
            chart.data.datasets[0].backgroundColor = colorsFor(transCounts.length, 29);
            chart.options.plugins.logoPlugin.images = []; // no logos for transmission types
            chart.options.scales.y.ticks.padding = 12;
            chart.options.indexAxis = 'y';
            renderTransmissionTable();
          } else if (view === 'condition') {
            chart.data.labels = condLabels;
            chart.data.datasets[0].data = condCounts;
            chart.data.datasets[0].backgroundColor = colorsFor(condCounts.length, 51);
            chart.options.plugins.logoPlugin.images = []; // no logos for condition
            // Keep horizontal bars (sideways) like Make/Model
            chart.options.indexAxis = 'y';
            chart.options.scales.x = { beginAtZero: true, ticks: { precision: 0, font: { size: 12 } } };
            chart.options.scales.y = { ticks: { autoSkip: false, padding: 12, font: { size: 14 } } };
            renderConditionTable();
          }
        }
        chart.update();
      });
      </script>
    <?php endif; ?>
  </main>
</body>
</html>
