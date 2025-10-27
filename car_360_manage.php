<?php
// car_360_manage.php — Seller management for 3D (360) images per car
session_start();
$mysqli = new mysqli("localhost", "root", "", "fyp");
if ($mysqli->connect_errno) die("DB error: " . $mysqli->connect_error);

// AuthZ: only sellers can manage
if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
  http_response_code(403);
  die('Forbidden: seller access required');
}

$car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
if ($car_id <= 0) die('Invalid car id');

// Verify ownership
$owns = false;
if ($st = $mysqli->prepare("SELECT seller_id, make, model FROM cars WHERE car_id=? LIMIT 1")) {
  $st->bind_param('i', $car_id);
  $st->execute();
  $car = $st->get_result()->fetch_assoc();
  $st->close();
  if (!$car) die('Car not found');
  $owns = (intval($car['seller_id']) === intval($_SESSION['user_id']));
}
if (!$owns) { http_response_code(403); die('Forbidden: you do not own this car'); }

// Ensure 360 tables exist (idempotent)
$mysqli->query("CREATE TABLE IF NOT EXISTS car_360_set (
  set_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  car_id INT UNSIGNED NOT NULL,
  source_car_db VARCHAR(64) NOT NULL DEFAULT 'fyp',
  title VARCHAR(128) NULL,
  notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (set_id),
  UNIQUE KEY u_car (car_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS car_360_exterior_images (
  image_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  set_id INT UNSIGNED NOT NULL,
  frame_index INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (image_id),
  KEY idx_set_frame (set_id, frame_index)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$mysqli->query("CREATE TABLE IF NOT EXISTS car_360_interior_images (
  image_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  set_id INT UNSIGNED NOT NULL,
  frame_index INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (image_id),
  KEY idx_set_frame (set_id, frame_index)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Get or create a set for this car
$set_id = 0;
if ($st = $mysqli->prepare("SELECT set_id FROM car_360_set WHERE car_id=? LIMIT 1")) {
  $st->bind_param('i', $car_id);
  $st->execute();
  $st->bind_result($sid);
  if ($st->fetch()) { $set_id = intval($sid); }
  $st->close();
}
if ($set_id === 0) {
  if ($ins = $mysqli->prepare("INSERT INTO car_360_set (car_id, title) VALUES (?, ?)")) {
    $title = $car['make'] . ' ' . $car['model'] . ' — 360 Set';
    $ins->bind_param('is', $car_id, $title);
    $ins->execute();
    $set_id = $ins->insert_id;
    $ins->close();
  }
}

// Handle uploads (paths only; file move is basic for demo)
$baseDir = __DIR__ . '/uploads/360';
@mkdir($baseDir, 0777, true);
@mkdir($baseDir . '/exterior', 0777, true);
@mkdir($baseDir . '/interior', 0777, true);

$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_type']) && in_array($_POST['add_type'], ['exterior','interior'], true)) {
    $type = $_POST['add_type'];
    if (!empty($_FILES['frames']['name'][0])) {
      $files = $_FILES['frames'];
      $count = count($files['name']);
      // Respect PHP's max_file_uploads limit to avoid warning and partial failure
      $maxUploads = (int)ini_get('max_file_uploads');
      $processCount = $count;
      if ($maxUploads > 0 && $count > $maxUploads) {
        $processCount = $maxUploads;
        $notice = 'Processed first ' . $processCount . ' files. Remaining ' . ($count - $processCount) . ' exceeded PHP max_file_uploads=' . $maxUploads . '. Upload in batches or increase PHP limit.';
      }
      // Build sortable index array and sort by filename (natural, case-insensitive)
      $idxs = range(0, $processCount - 1);
      usort($idxs, function($a, $b) use ($files) {
        return strnatcasecmp($files['name'][$a], $files['name'][$b]);
      });
      // Get current max frame_index
      $maxIdx = 0;
      $tbl = $type === 'exterior' ? 'car_360_exterior_images' : 'car_360_interior_images';
  $q = $mysqli->prepare("SELECT COALESCE(MAX(frame_index), 0) FROM {$tbl} WHERE set_id=?");
      $q->bind_param('i', $set_id);
      $q->execute();
      $q->bind_result($mx);
      if ($q->fetch()) { $maxIdx = intval($mx); }
      $q->close();
      // Insert each in sorted order
      foreach ($idxs as $i) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
        $tmp = $files['tmp_name'][$i];
        $name = basename($files['name'][$i]);
        $slug = preg_replace('/[^A-Za-z0-9_.-]/','_', $name);
        $targetRel = "uploads/360/{$type}/" . time() . "_" . $slug;
        $targetAbs = __DIR__ . '/' . $targetRel;
        if (@move_uploaded_file($tmp, $targetAbs)) {
          $maxIdx++;
          if ($ins = $mysqli->prepare("INSERT INTO {$tbl} (set_id, frame_index, image_path) VALUES (?,?,?)")) {
            $ins->bind_param('iis', $set_id, $maxIdx, $targetRel);
            $ins->execute();
            $ins->close();
          }
        }
      }
      $notice = ($notice ? $notice . ' ' : '') . 'Frames uploaded.';
    }
  } elseif (isset($_POST['delete_img'], $_POST['img_id'], $_POST['del_type'])) {
    $imgId = intval($_POST['img_id']);
    $tbl = ($_POST['del_type'] === 'interior') ? 'car_360_interior_images' : 'car_360_exterior_images';
    $del = $mysqli->prepare("DELETE FROM {$tbl} WHERE image_id=? AND set_id=?");
    $del->bind_param('ii', $imgId, $set_id);
    $del->execute();
    $del->close();
    $notice = 'Frame deleted.';
  } elseif (isset($_POST['reindex_type']) && in_array($_POST['reindex_type'], ['exterior','interior'], true)) {
    // Re-number frames by time added (created_at ASC, then image_id)
    $type = $_POST['reindex_type'];
    $tbl = $type === 'exterior' ? 'car_360_exterior_images' : 'car_360_interior_images';
    $rows = [];
    if ($st = $mysqli->prepare("SELECT image_id, image_path, created_at FROM {$tbl} WHERE set_id=? AND is_active=1")) {
      $st->bind_param('i', $set_id);
      $st->execute();
      $res = $st->get_result();
      while ($r = $res->fetch_assoc()) { $rows[] = $r; }
      $st->close();
    }
    usort($rows, function($a, $b){
      $ta = strtotime($a['created_at'] ?? '');
      $tb = strtotime($b['created_at'] ?? '');
      if ($ta !== $tb) return $ta <=> $tb; // older first
      return ((int)$a['image_id']) <=> ((int)$b['image_id']);
    });
    $i = 1; // 1-based indexing
    foreach ($rows as $r) {
      if ($up = $mysqli->prepare("UPDATE {$tbl} SET frame_index=? WHERE image_id=? AND set_id=?")) {
        $up->bind_param('iii', $i, $r['image_id'], $set_id);
        $up->execute();
        $up->close();
      }
      $i++;
    }
    $notice = 'Frames sorted by time added.';
  } elseif (isset($_POST['save_order_type']) && in_array($_POST['save_order_type'], ['exterior','interior'], true)) {
    // Persist new numeric order chosen by the user
    $type = $_POST['save_order_type'];
    $tbl = $type === 'exterior' ? 'car_360_exterior_images' : 'car_360_interior_images';
    $field = $type === 'exterior' ? 'order_ext' : 'order_int';
    $posted = isset($_POST[$field]) && is_array($_POST[$field]) ? $_POST[$field] : [];
    // Load current list
    $rows = [];
    if ($st = $mysqli->prepare("SELECT image_id, frame_index, image_path FROM {$tbl} WHERE set_id=? AND is_active=1 ORDER BY frame_index ASC")) {
      $st->bind_param('i', $set_id);
      $st->execute();
      $res = $st->get_result();
      while ($r = $res->fetch_assoc()) {
        $id = (int)$r['image_id'];
        $new = null;
        if (isset($posted[$id])) {
          $nv = (int)$posted[$id];
          if ($nv >= 1) $new = $nv; // user-facing is 1-based
        }
        $rows[] = [ 'id'=>$id, 'cur'=>(int)$r['frame_index'], 'new'=>$new, 'path'=>$r['image_path'] ];
      }
      $st->close();
    }
    // Stable sort: items with a 'new' value first by that value ascending; others keep current order
    usort($rows, function($a,$b){
      $an = $a['new']; $bn = $b['new'];
      if ($an !== null && $bn !== null) return $an <=> $bn;
      if ($an !== null) return -1;
      if ($bn !== null) return 1;
      return $a['cur'] <=> $b['cur'];
    });
    // Re-number sequentially from 1
    $i = 1;
    foreach ($rows as $r) {
      if ($up = $mysqli->prepare("UPDATE {$tbl} SET frame_index=? WHERE image_id=? AND set_id=?")) {
        $up->bind_param('iii', $i, $r['id'], $set_id);
        $up->execute();
        $up->close();
      }
      $i++;
    }
    $notice = 'Order saved.';
  }
}

// Load frames
$ext = [];
$int = [];
$r1 = $mysqli->prepare("SELECT image_id, frame_index, image_path FROM car_360_exterior_images WHERE set_id=? AND is_active=1 ORDER BY frame_index ASC");
$r1->bind_param('i', $set_id);
$r1->execute();
$re1 = $r1->get_result();
while ($row = $re1->fetch_assoc()) $ext[] = $row;
$r1->close();
$r2 = $mysqli->prepare("SELECT image_id, frame_index, image_path FROM car_360_interior_images WHERE set_id=? AND is_active=1 ORDER BY frame_index ASC");
$r2->bind_param('i', $set_id);
$r2->execute();
$re2 = $r2->get_result();
while ($row = $re2->fetch_assoc()) $int[] = $row;
$r2->close();

// Expose server upload limits to UI
$maxUploads = (int)ini_get('max_file_uploads');
$maxFileSize = ini_get('upload_max_filesize');
$postMaxSize = ini_get('post_max_size');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage 3D View — <?php echo htmlspecialchars(($car['make'] ?? '').' '.($car['model'] ?? '')); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<header class="bg-red-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-2xl font-bold">Manage 3D View</h1>
    <a href="car_details.php?car_id=<?php echo (int)$car_id; ?>" class="underline">Back to Car</a>
  </div>
</header>
<main class="container mx-auto mt-8">
  <?php if (!empty($notice)): ?>
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($notice); ?></div>
  <?php endif; ?>
  <div class="bg-white rounded shadow p-6">
    <div class="flex items-start justify-between mb-4">
      <h2 class="text-xl font-semibold">Car: <?php echo htmlspecialchars(($car['make'] ?? '').' '.($car['model'] ?? '')); ?></h2>
      <!-- Preview toggle (top-right) -->
      <div class="inline-flex rounded-lg overflow-hidden border">
        <button id="pvBtnExterior" type="button" class="px-3 py-1 bg-indigo-600 text-white text-sm">Exterior</button>
        <button id="pvBtnInterior" type="button" class="px-3 py-1 bg-white text-gray-700 text-sm">Interior</button>
      </div>
    </div>
    <!-- 3D Preview -->
    <div class="mb-6">
      <div id="pvContainer" class="relative w-full max-w-3xl h-72 md:h-80 bg-gray-50 border border-gray-200 rounded-xl overflow-hidden mx-auto select-none">
        <div id="pvEmpty" class="absolute inset-0 flex items-center justify-center text-gray-500 text-sm">No frames to preview. Upload some images on the left or right panel below.</div>
        <img id="pvImg" alt="Preview" class="w-full h-full object-contain hidden" draggable="false" />
        <!-- simple hint -->
        <div class="absolute bottom-2 right-2 bg-black bg-opacity-40 text-white text-xs px-2 py-1 rounded">Drag or use ←/→</div>
      </div>
    </div>
    <p class="text-sm text-gray-600 mb-4">Server limits — Max files per request: <span class="font-semibold"><?php echo (int)$maxUploads; ?></span>, Max file size: <span class="font-semibold"><?php echo htmlspecialchars($maxFileSize); ?></span>, Max post size: <span class="font-semibold"><?php echo htmlspecialchars($postMaxSize); ?></span>. If you need to upload more than the max per request, this page will auto-batch your upload.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Exterior uploader/list -->
      <section>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Exterior Frames</h3>
        <form method="post" enctype="multipart/form-data" class="mb-3 frames-upload-form" data-type="exterior">
          <input type="hidden" name="add_type" value="exterior">
          <input type="file" name="frames[]" multiple accept="image/*" class="border p-2 rounded w-full mb-2 frames-input" data-max="<?php echo (int)$maxUploads; ?>">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded upload-btn">Upload</button>
        </form>
        <form method="post" class="mb-3">
          <input type="hidden" name="reindex_type" value="exterior">
          <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded">Sort by time added</button>
        </form>
        <?php if (!empty($ext)): ?>
          <div class="grid grid-cols-3 gap-2">
            <?php $extCount = count($ext); foreach($ext as $e): ?>
              <div class="border rounded p-1 bg-gray-50">
                <div class="flex items-center justify-between mb-1">
                  <div class="text-xs text-gray-600">#<?php echo (int)$e['frame_index']; ?></div>
                  <!-- order input (1-based for UX) -->
                  <input type="number" min="1" max="<?php echo (int)$extCount; ?>" value="<?php echo (int)$e['frame_index']; ?>" name="order_ext[<?php echo (int)$e['image_id']; ?>]" form="orderExtForm" class="w-14 text-xs border rounded px-1 py-0.5" />
                </div>
                <img src="<?php echo htmlspecialchars($e['image_path']); ?>" class="w-full h-24 object-cover rounded">
                <form method="post" class="mt-1 text-right">
                  <input type="hidden" name="delete_img" value="1">
                  <input type="hidden" name="img_id" value="<?php echo (int)$e['image_id']; ?>">
                  <input type="hidden" name="del_type" value="exterior">
                  <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded">Delete</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="flex items-center gap-2 mt-2">
            <button type="button" onclick="openSortModal('exterior')" class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Sort visually</button>
          </div>
          <form id="orderExtForm" method="post" class="mt-2">
            <input type="hidden" name="save_order_type" value="exterior">
            <button type="submit" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Save Order</button>
          </form>
        <?php else: ?>
          <div class="text-sm text-gray-600">No exterior frames yet.</div>
        <?php endif; ?>
      </section>

      <!-- Interior uploader/list -->
      <section>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Interior Frames</h3>
        <form method="post" enctype="multipart/form-data" class="mb-3 frames-upload-form" data-type="interior">
          <input type="hidden" name="add_type" value="interior">
          <input type="file" name="frames[]" multiple accept="image/*" class="border p-2 rounded w-full mb-2 frames-input" data-max="<?php echo (int)$maxUploads; ?>">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded upload-btn">Upload</button>
        </form>
        <form method="post" class="mb-3">
          <input type="hidden" name="reindex_type" value="interior">
          <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded">Sort by time added</button>
        </form>
        <?php if (!empty($int)): ?>
          <div class="grid grid-cols-3 gap-2">
            <?php $intCount = count($int); foreach($int as $e): ?>
              <div class="border rounded p-1 bg-gray-50">
                <div class="flex items-center justify-between mb-1">
                  <div class="text-xs text-gray-600">#<?php echo (int)$e['frame_index']; ?></div>
                  <!-- order input (1-based for UX) -->
                  <input type="number" min="1" max="<?php echo (int)$intCount; ?>" value="<?php echo (int)$e['frame_index']; ?>" name="order_int[<?php echo (int)$e['image_id']; ?>]" form="orderIntForm" class="w-14 text-xs border rounded px-1 py-0.5" />
                </div>
                <img src="<?php echo htmlspecialchars($e['image_path']); ?>" class="w-full h-24 object-cover rounded">
                <form method="post" class="mt-1 text-right">
                  <input type="hidden" name="delete_img" value="1">
                  <input type="hidden" name="img_id" value="<?php echo (int)$e['image_id']; ?>">
                  <input type="hidden" name="del_type" value="interior">
                  <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded">Delete</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="flex items-center gap-2 mt-2">
            <button type="button" onclick="openSortModal('interior')" class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Sort visually</button>
          </div>
          <form id="orderIntForm" method="post" class="mt-2">
            <input type="hidden" name="save_order_type" value="interior">
            <button type="submit" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Save Order</button>
          </form>
        <?php else: ?>
          <div class="text-sm text-gray-600">No interior frames yet.</div>
        <?php endif; ?>
      </section>
    </div>
  </div>
</main>
<!-- Visual Sort Modal -->
<div id="sortModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40">
  <div class="absolute inset-0 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl md:max-w-5xl overflow-hidden flex flex-col" style="max-height: 90vh;">
      <div class="px-4 py-2 border-b flex items-center justify-between">
        <h3 id="sortTitle" class="text-lg font-semibold">Sort Frames</h3>
        <button type="button" class="text-gray-600 hover:text-black" onclick="closeSortModal()">✕</button>
      </div>
      <div class="px-4 py-1 text-sm text-gray-600 border-b flex items-center justify-between">
        <div>Click thumbnails in your desired order. You can take your time; use Undo to remove the last pick.</div>
        <div>Selected: <span id="sortCount" class="font-semibold">0</span></div>
      </div>
      <div class="p-3 overflow-auto flex-1 min-h-0">
  <div id="sortGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3"></div>
      </div>
      <div class="px-3 py-2 border-t flex items-center justify-between">
        <div class="flex gap-2">
          <button type="button" onclick="undoSortPick()" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded">Undo</button>
          <button type="button" onclick="clearSortPicks()" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded">Clear</button>
        </div>
        <div class="flex gap-2">
          <button type="button" onclick="closeSortModal()" class="text-sm bg-white border hover:bg-gray-50 text-gray-800 px-3 py-1 rounded">Cancel</button>
          <button type="button" onclick="saveSortedOrder()" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Save order</button>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<script>
// Client-side batching uploader to avoid hitting PHP max_file_uploads
(function(){
  function naturalSortFiles(files){
    const arr = Array.from(files);
    arr.sort((a,b) => a.name.localeCompare(b.name, undefined, {numeric:true, sensitivity:'base'}));
    return arr;
  }
  async function uploadBatch(url, type, files){
    const fd = new FormData();
    fd.append('add_type', type);
    for (const f of files) fd.append('frames[]', f, f.name);
    await fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' });
  }
  function bindForms(){
    const forms = document.querySelectorAll('.frames-upload-form');
    forms.forEach(form => {
      const input = form.querySelector('.frames-input');
      const btn = form.querySelector('.upload-btn');
      const type = form.getAttribute('data-type');
      const max = parseInt(input?.dataset?.max || '20', 10);
      form.addEventListener('submit', async (e) => {
        if (!input || !input.files || input.files.length === 0) return; // allow normal (no files)
        e.preventDefault();
        btn.disabled = true; btn.textContent = 'Uploading…';
        const sorted = naturalSortFiles(input.files);
        const chunkSize = Math.max(1, max - 1); // keep below server cap conservatively
        const url = window.location.href;
        for (let i=0; i<sorted.length; i+=chunkSize){
          const slice = sorted.slice(i, i+chunkSize);
          await uploadBatch(url, type, slice);
        }
        window.location.reload();
      });
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', bindForms); else bindForms();
})();

// Lightweight 3D preview using current frames
(function(){
  const ext = <?php echo json_encode(array_map(function($e){return $e['image_path'];}, $ext)); ?>;
  const inte = <?php echo json_encode(array_map(function($e){return $e['image_path'];}, $int)); ?>;
  const pvImg = document.getElementById('pvImg');
  const pvEmpty = document.getElementById('pvEmpty');
  const pv = document.getElementById('pvContainer');
  const btnExt = document.getElementById('pvBtnExterior');
  const btnInt = document.getElementById('pvBtnInterior');
  let mode = (ext && ext.length) ? 'exterior' : 'interior';
  let imgs = mode==='exterior' ? ext : inte;
  let idx = 0;
  let dragging = false, startX = 0;

  function setButtons(){
    if (!btnExt || !btnInt) return;
    if (mode==='exterior') { btnExt.className='px-3 py-1 bg-indigo-600 text-white text-sm'; btnInt.className='px-3 py-1 bg-white text-gray-700 text-sm'; }
    else { btnInt.className='px-3 py-1 bg-indigo-600 text-white text-sm'; btnExt.className='px-3 py-1 bg-white text-gray-700 text-sm'; }
  }
  function show(idxNew){
    if (!imgs || imgs.length===0) { pvImg.classList.add('hidden'); pvEmpty.classList.remove('hidden'); return; }
    pvEmpty.classList.add('hidden');
    pvImg.classList.remove('hidden');
    idx = (idxNew + imgs.length) % imgs.length;
    pvImg.src = imgs[idx];
  }
  function setMode(m){ mode = m; imgs = (m==='exterior') ? ext : inte; idx = 0; setButtons(); show(idx); }

  btnExt?.addEventListener('click', () => setMode('exterior'));
  btnInt?.addEventListener('click', () => setMode('interior'));

  pv?.addEventListener('mousedown', (e)=>{ if (e.button!==0) return; dragging=true; startX=e.clientX; });
  document.addEventListener('mouseup', ()=>{ dragging=false; });
  pv?.addEventListener('mousemove', (e)=>{ if(!dragging) return; const dx=e.clientX-startX; if(Math.abs(dx)>15){ show(idx + (dx>0?-1:1)); startX=e.clientX; } });
  document.addEventListener('keydown', (e)=>{ if(e.key==='ArrowRight') show(idx+1); else if(e.key==='ArrowLeft') show(idx-1); });

  // init
  setButtons();
  show(0);
})();

// Visual sorting modal logic
(function(){
  // Prepare metadata: id + path
  const extMeta = <?php echo json_encode(array_map(function($e){ return ['id'=>(int)$e['image_id'], 'path'=>$e['image_path']]; }, $ext)); ?>;
  const intMeta = <?php echo json_encode(array_map(function($e){ return ['id'=>(int)$e['image_id'], 'path'=>$e['image_path']]; }, $int)); ?>;

  const modal = document.getElementById('sortModal');
  const grid = document.getElementById('sortGrid');
  const title = document.getElementById('sortTitle');
  const countEl = document.getElementById('sortCount');
  let curType = 'exterior';
  let items = [];
  let picks = []; // array of image_ids in chosen order
  let pickedSet = new Set();

  function render(){
    countEl.textContent = String(picks.length);
    // Build a map from id -> rank number (1-based)
    const rankMap = new Map();
    picks.forEach((id, i) => rankMap.set(id, i+1));
    // Sort tiles: selected first by their rank (1..n), then the rest by original order
    const sortedItems = items.slice().sort((a,b) => {
      const ra = rankMap.get(a.id) ?? Infinity;
      const rb = rankMap.get(b.id) ?? Infinity;
      if (ra !== rb) return ra - rb;
      return (a.orig ?? 0) - (b.orig ?? 0);
    });
    // Render tiles
    grid.innerHTML = sortedItems.map(it => {
      const r = rankMap.get(it.id) || '';
      const origBadge = (it.orig ?? 0) + 1;
      return `
        <div class="relative group cursor-pointer border rounded overflow-hidden bg-gray-50" data-id="${it.id}">
          <img src="${it.path}" class="w-full h-28 md:h-32 object-cover" />
          <div class="absolute inset-0 hidden group-hover:flex items-center justify-center bg-black bg-opacity-25 text-white text-sm">Click to ${r? 'unselect' : 'select next'}</div>
          ${r ? `<div class=\"absolute top-1 left-1 bg-indigo-600 text-white text-xs font-semibold px-2 py-0.5 rounded\">${r}</div>` : ''}
        </div>
      `;
    }).join('');
    // Bind click handlers
    grid.querySelectorAll('[data-id]').forEach(el => {
      el.addEventListener('click', () => {
        const id = parseInt(el.getAttribute('data-id'));
        if (pickedSet.has(id)) {
          // remove from picks
          picks = picks.filter(x => x !== id);
          pickedSet.delete(id);
        } else {
          picks.push(id);
          pickedSet.add(id);
        }
        render();
      });
    });
  }

  function open(type){
    curType = type === 'interior' ? 'interior' : 'exterior';
    const src = (curType === 'exterior') ? extMeta : intMeta;
    // Keep original index for stable secondary sort
    items = src.map((it, idx) => ({ id: it.id, path: it.path, orig: idx }));
    picks = [];
    pickedSet = new Set();
    title.textContent = 'Sort ' + (curType === 'exterior' ? 'Exterior' : 'Interior') + ' Frames';
    render();
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  }

  function close(){
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }

  function undo(){
    if (picks.length > 0) {
      const last = picks.pop();
      pickedSet.delete(last);
      render();
    }
  }

  function clear(){
    picks = [];
    pickedSet = new Set();
    render();
  }

  function save(){
    // Target form based on type
    const formId = curType === 'exterior' ? 'orderExtForm' : 'orderIntForm';
    const fieldNamePrefix = curType === 'exterior' ? 'order_ext' : 'order_int';
    const form = document.getElementById(formId);
    if (!form) return;
    // Remove previously injected inputs
    Array.from(form.querySelectorAll('.modal-order-input')).forEach(n => n.remove());
    // Disable any existing numeric inputs tied to this form so only modal picks are submitted
    document.querySelectorAll(`input[name^="${fieldNamePrefix}["]`).forEach(inp => {
      // Only disable those that would submit with this form
      if ((inp.getAttribute('form') === formId) || (inp.form && inp.form.id === formId)) {
        inp.disabled = true;
      }
    });
    // Create new inputs for each pick (1-based)
    picks.forEach((id, i) => {
      const inp = document.createElement('input');
      inp.type = 'hidden';
      inp.name = `${fieldNamePrefix}[${id}]`;
      inp.value = String(i+1);
      inp.className = 'modal-order-input';
      form.appendChild(inp);
    });
    // Submit form
    form.submit();
    close();
  }

  // Expose to global scope for onclick handlers
  window.openSortModal = open;
  window.closeSortModal = close;
  window.undoSortPick = undo;
  window.clearSortPicks = clear;
  window.saveSortedOrder = save;
})();
</script>
</html>
