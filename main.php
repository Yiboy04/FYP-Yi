<?php
// main.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Main Page - Car Search</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

  <!-- HEADER -->
  <header class="bg-red-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <h1 class="text-2xl font-bold">MyCar (FYP)</h1>
      <nav>
        <ul class="flex gap-6">
          <li><a href="main.php" class="hover:underline">Home</a></li>
          <li><a href="#" class="hover:underline">Listings</a></li>
          <li><a href="#" class="hover:underline">About</a></li>
          <li><a href="logout.php" class="hover:underline">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- MAIN CONTENT -->
  <main class="flex-grow flex justify-center items-center bg-cover bg-center" 
        style="background-image: url('https://images.unsplash.com/photo-1502877338535-766e1452684a');">

    <div class="bg-white bg-opacity-90 p-8 rounded-2xl shadow-xl w-[600px]">
      <h2 class="text-2xl font-bold mb-4">Find Used Cars</h2>

      <!-- Make & Model -->
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-1">Make</label>
          <select class="w-full p-2 border rounded">
            <option value="">Select Make</option>
            <option value="Toyota">Toyota</option>
            <option value="Honda">Honda</option>
            <option value="Nissan">Nissan</option>
            <option value="Mazda">Mazda</option>
            <option value="Mitsubishi">Mitsubishi</option>
          </select>
        </div>
        <div>
          <label class="block mb-1">Model</label>
          <select class="w-full p-2 border rounded">
            <option value="">Select Model</option>
            <option value="Corolla">Corolla</option>
            <option value="Civic">Civic</option>
            <option value="Skyline">Skyline</option>
            <option value="RX-7">RX-7</option>
            <option value="Lancer Evolution">Lancer Evolution</option>
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
        <button id="openPricePopup" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded">
          Enter Specific Price
        </button>
      </div>

      <button class="w-full bg-red-600 text-white py-2 rounded-lg text-lg font-semibold">Search</button>
    </div>
  </main>

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

    // Year slider update
    function updateYear() {
      let min = parseInt(minYear.value);
      let max = parseInt(maxYear.value);
      if (min > max) min = max;
      yearDisplay.textContent = `${min} - ${max}`;
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
    }
    minPrice.addEventListener("input", updatePrice);
    maxPrice.addEventListener("input", updatePrice);
    updatePrice();

    // Popup events
    openPopup.addEventListener("click", () => {
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
  </script>
</body>
</html>
