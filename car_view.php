<?php
session_start();
// helper function to get images from folder (fallback mode)
function getImages($folder) {
  $imageDir = __DIR__ . "/pictures/$folder";
  if (!is_dir($imageDir)) return [];

  $allFiles = scandir($imageDir);
  $imageFiles = [];
  foreach ($allFiles as $file) {
    $filePath = $imageDir . "/" . $file;
    if (is_file($filePath) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
      $imageFiles[$file] = filemtime($filePath);
    }
  }
  arsort($imageFiles); // newest first
  // return as web paths for direct use in <img src="...">
  return array_map(function($fname) use ($folder) {
    return "pictures/$folder/" . $fname;
  }, array_keys($imageFiles));
}

// Default: filesystem-based lists (fallback)
$exteriorImages = getImages('exterior');
$interiorImages = getImages('interior');

// If a car_id is provided, try to load 360 frames from the separate fyp_360 database
$carId = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
// Will hold a friendly display name like: "2020 Honda Civic 1.5 TC-P"
$carDisplayName = '';
if ($carId > 0) {
  // Connect to the same 'fyp' database where 360 tables now live
  $mysqli360 = @new mysqli("localhost", "root", "", "fyp");
  if (!$mysqli360->connect_errno) {
    // Load basic car details to build a display name
    if ($carStmt = $mysqli360->prepare("SELECT * FROM cars WHERE car_id=? LIMIT 1")) {
      $carStmt->bind_param('i', $carId);
      if ($carStmt->execute()) {
        $carRes = $carStmt->get_result();
        if ($carRow = $carRes->fetch_assoc()) {
          $make = trim((string)($carRow['make'] ?? ''));
          $model = trim((string)($carRow['model'] ?? ''));
          // Try a couple of common column names for variant and year
          $variant = trim((string)($carRow['variant'] ?? ($carRow['trim'] ?? '')));
          $year = trim((string)($carRow['year'] ?? ($carRow['manufacture_year'] ?? '')));
          $parts = [];
          if ($year !== '') $parts[] = $year;
          if ($make !== '') $parts[] = $make;
          if ($model !== '') $parts[] = $model;
          if ($variant !== '') $parts[] = $variant;
          $carDisplayName = trim(implode(' ', $parts));
        }
      }
      $carStmt->close();
    }
    // Find set for this car (optional)
    $stmt = $mysqli360->prepare("SELECT set_id FROM car_360_set WHERE car_id=? LIMIT 1");
    if ($stmt) {
      $stmt->bind_param('i', $carId);
      if ($stmt->execute()) {
        $stmt->bind_result($setId);
        if ($stmt->fetch()) {
          $stmt->close();
          // Exterior
          $exterior = [];
          if ($q = $mysqli360->prepare("SELECT image_path FROM car_360_exterior_images WHERE set_id=? AND is_active=1 ORDER BY frame_index ASC")) {
            $q->bind_param('i', $setId);
            if ($q->execute()) {
              $res = $q->get_result();
              while ($row = $res->fetch_assoc()) { $exterior[] = $row['image_path']; }
            }
            $q->close();
          }
          // Interior
          $interior = [];
          if ($q2 = $mysqli360->prepare("SELECT image_path FROM car_360_interior_images WHERE set_id=? AND is_active=1 ORDER BY frame_index ASC")) {
            $q2->bind_param('i', $setId);
            if ($q2->execute()) {
              $res2 = $q2->get_result();
              while ($row = $res2->fetch_assoc()) { $interior[] = $row['image_path']; }
            }
            $q2->close();
          }
          // Override regardless (if none, show none) to reflect latest deletes accurately
          $exteriorImages = $exterior;
          $interiorImages = $interior;
        } else {
          $stmt->close();
        }
      } else {
        $stmt->close();
      }
    }
    $mysqli360->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php
    // Build a dynamic page title including car name if available
    $pageCarName = isset($carDisplayName) ? trim($carDisplayName) : '';
    echo 'Car 360 View' . ($pageCarName !== '' ? (' — ' . htmlspecialchars($pageCarName)) : '');
  ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #car-container {
      position: relative;
      width: 100%;
      max-width: 700px;
      height: 450px;
      margin: 0 auto;
      background: #f9fafb;
      border: 2px solid #e5e7eb;
      border-radius: 1rem;
      overflow: hidden;
      cursor: grab;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    #car-container:active { cursor: grabbing; }
    #car-container img {
      width: 100%; height: 100%; object-fit: contain;
      background: #000; /* ensure letterbox is black behind image */
      display: none;
    }
    #car-container img.active { display: block; }

    /* Preloader */
    #preloader {
      position: fixed; top: 0; left: 0;
      width: 100%; height: 100%;
      background: #fff;
      display: flex; justify-content: center; align-items: center;
      z-index: 9999;
    }
    .loader {
      border: 8px solid #f3f3f3;
      border-top: 8px solid #ef4444;
      border-radius: 50%;
      width: 60px; height: 60px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin { 
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    /* Fullscreen sizing for the viewer container */
    #car-container:fullscreen, #car-container:-webkit-full-screen, #car-container:-ms-fullscreen {
      width: 100vw;
      height: 100vh;
      max-width: none;
      border-radius: 0;
      border: none;
      background: #000; /* Black out empty space in fullscreen */
    }
    button:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  </style>
</head>
<body class="bg-gray-100">

  <!-- HEADER -->
  <header class="bg-red-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">GVC</h1>
      <nav>
        <ul class="flex gap-6">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="list_cars.php" class="underline font-semibold">Listings</a></li>
          <li><a href="#" class="hover:underline">About</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
            <li><a href="buyer_bookings.php" class="hover:underline">Bookings</a></li>
            <li><a href="buyer_profile.php" class="hover:underline">Profile</a></li>
          <?php endif; ?>
          <li><a href="logout.php" class="hover:underline">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Preloader -->
  <div id="preloader"><div class="loader"></div></div>

  <!-- MAIN CONTENT -->
  <main class="container mx-auto py-10">
    <?php if ($carId > 0): ?>
      <div class="mb-4">
        <a href="car_details_view.php?car_id=<?php echo (int)$carId; ?>"
           class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
          <span>←</span>
          <span>Back to Details</span>
        </a>
      </div>
    <?php else: ?>
      <div class="mb-4">
        <button type="button" onclick="history.back();"
                class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
          <span>←</span>
          <span>Back</span>
        </button>
      </div>
    <?php endif; ?>
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">360° Car View</h2>
    <?php if (!empty($carDisplayName)): ?>
      <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 text-gray-800"><?php echo htmlspecialchars($carDisplayName); ?></h2>
    <?php endif; ?>
    <p class="text-center text-gray-600 mb-6">Click and drag or use <kbd class="bg-gray-200 px-2 py-1 rounded">←</kbd><kbd class="bg-gray-200 px-2 py-1 rounded">→</kbd> keys.</p>

    <!-- Toggle Buttons -->
    <div class="flex justify-center mb-4 gap-4">
      <button id="showExterior" 
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
              <?php echo empty($exteriorImages) ? 'disabled' : ''; ?>>
        Exterior
      </button>
      <button id="showInterior" 
              class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded"
              <?php echo empty($interiorImages) ? 'disabled' : ''; ?>>
        Interior
      </button>
      <button id="fullscreenBtn" 
              class="bg-black bg-opacity-70 hover:bg-opacity-90 text-white px-4 py-2 rounded"
              title="Toggle fullscreen">
        Fullscreen
      </button>
    </div>

    <div id="car-container">
      <!-- Exterior images -->
      <?php $first = true; foreach ($exteriorImages as $src): ?>
        <img src="<?php echo htmlspecialchars($src); ?>" data-set="exterior" class="<?php echo $first ? 'active' : ''; ?>">
        <?php $first = false; endforeach; ?>

      <!-- Interior images (hidden initially) -->
      <?php foreach ($interiorImages as $src): ?>
        <img src="<?php echo htmlspecialchars($src); ?>" data-set="interior">
      <?php endforeach; ?>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-red-600 text-white py-4 mt-10">
    <div class="container mx-auto text-center">
      <p>&copy; <?php echo date("Y"); ?> Great Value Car. All rights reserved.</p>
    </div>
  </footer>

  <!-- SCRIPT -->
  <script>
    window.onload = function() {
      document.getElementById("preloader").style.display = "none";
    };

    let images = document.querySelectorAll("#car-container img[data-set='exterior']");
    let currentIndex = 0;
    let isDragging = false;
    let startX = 0;
    let currentSet = 'exterior';

    const container = document.getElementById("car-container");

    function updateImageList(set) {
      images = document.querySelectorAll(`#car-container img[data-set='${set}']`);
      currentIndex = 0;
      document.querySelectorAll("#car-container img").forEach(img => img.classList.remove("active"));
      if (images.length > 0) images[0].classList.add("active");
    }

    function showImage(index) {
      if (images.length === 0) return;
      images[currentIndex].classList.remove("active");
      currentIndex = (index + images.length) % images.length;
      images[currentIndex].classList.add("active");
    }

    // Mouse drag rotate
    container.addEventListener("mousedown", e => {
      if (e.button !== 0) return; // only left click
      isDragging = true;
      startX = e.clientX;
    });
    document.addEventListener("mouseup", () => { isDragging = false; });
    container.addEventListener("mousemove", e => {
      if (!isDragging) return;
      const diff = e.clientX - startX;
      if (Math.abs(diff) > 15) {
        if (diff > 0) {
          showImage(currentIndex - 1);
        } else {
          showImage(currentIndex + 1);
        }
        startX = e.clientX;
      }
    });

    // Arrow keys rotate
    document.addEventListener("keydown", e => {
      if (e.key === "ArrowRight") {
        showImage(currentIndex + 1);
      } else if (e.key === "ArrowLeft") {
        showImage(currentIndex - 1);
      }
    });

    // Toggle buttons
    document.getElementById("showExterior")?.addEventListener("click", () => {
      currentSet = 'exterior';
      updateImageList('exterior');
    });
    document.getElementById("showInterior")?.addEventListener("click", () => {
      currentSet = 'interior';
      updateImageList('interior');
    });

    // Fullscreen toggle
    const fsBtn = document.getElementById('fullscreenBtn');
    function isFullscreen(){
      return document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
    }
    function enterFullscreen(el){
      if (el.requestFullscreen) return el.requestFullscreen();
      if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen();
      if (el.msRequestFullscreen) return el.msRequestFullscreen();
    }
    function exitFullscreen(){
      if (document.exitFullscreen) return document.exitFullscreen();
      if (document.webkitExitFullscreen) return document.webkitExitFullscreen();
      if (document.msExitFullscreen) return document.msExitFullscreen();
    }
    function updateFsBtn(){
      if (isFullscreen()) fsBtn.textContent = 'Exit Fullscreen';
      else fsBtn.textContent = 'Fullscreen';
    }
    fsBtn?.addEventListener('click', () => {
      if (!isFullscreen()) enterFullscreen(container);
      else exitFullscreen();
    });
    document.addEventListener('fullscreenchange', updateFsBtn);
    document.addEventListener('webkitfullscreenchange', updateFsBtn);
    document.addEventListener('msfullscreenchange', updateFsBtn);
    updateFsBtn();
  </script>
</body>
</html>
