<?php
session_start();
// helper function to get images from folder
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
    return array_keys($imageFiles);
}

// get both sets
$exteriorImages = getImages('exterior');
$interiorImages = getImages('interior');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Car 360 View</title>
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
          <li><a href="car_view.php" class="underline font-semibold">Listings</a></li>
          <li><a href="#" class="hover:underline">About</a></li>
          <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
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
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">360° Car View</h2>
    <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">1995 MK4 Toyota Supra 3.0</h2>
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
    </div>

    <div id="car-container">
      <!-- Exterior images -->
      <?php $first = true; foreach ($exteriorImages as $file): ?>
        <img src="pictures/exterior/<?php echo $file; ?>" data-set="exterior" class="<?php echo $first ? 'active' : ''; ?>">
        <?php $first = false; endforeach; ?>

      <!-- Interior images (hidden initially) -->
      <?php foreach ($interiorImages as $file): ?>
        <img src="pictures/interior/<?php echo $file; ?>" data-set="interior">
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
  </script>
</body>
</html>
