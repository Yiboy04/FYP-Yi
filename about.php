<?php
// about.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About - Great Value Car (GVC)</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
  <!-- HEADER (same style as main) -->
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
          <!-- Fold-down menu -->
          <li class="relative" id="moreMenu">
            <button id="moreBtn" class="inline-flex items-center gap-1 px-3 py-1 bg-white bg-opacity-10 hover:bg-opacity-20 rounded">
              <span>More</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
            </button>
            <div id="morePanel" class="hidden absolute right-0 mt-2 w-52 bg-white text-gray-800 rounded-md shadow-lg py-1 z-50">
              <a href="analysis.php" class="block px-4 py-2 hover:bg-gray-100">Analysis</a>
              <a href="saved_search.php" class="block px-4 py-2 hover:bg-gray-100">Saved</a>
              <a href="compare.php" class="block px-4 py-2 hover:bg-gray-100">Compare</a>
              <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='buyer'): ?>
                <a href="buyer_bookings.php" class="block px-4 py-2 hover:bg-gray-100">Bookings</a>
              <?php endif; ?>
              <a href="about.php" class="block px-4 py-2 bg-gray-100 font-medium">About</a>
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

  <!-- MAIN -->
  <main class="flex-grow">
    <section class="bg-white">
      <div class="container mx-auto max-w-4xl px-6 py-10">
        <h2 class="text-3xl font-extrabold mb-3">About Great Value Car (GVC)</h2>
        <p class="text-gray-700 leading-relaxed mb-6">
          Great Value Car (GVC) is a trusted online marketplace that helps you find quality cars at honest prices. 
          Browse a curated selection from reliable sellers, compare key specs at a glance, and get clear cost estimates before you visit. 
          We make it simple to choose the right car — fast, transparent, and stress-free.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
          <div class="bg-gray-50 p-6 rounded-xl border">
            <h3 class="text-xl font-bold mb-2">What you can do on GVC</h3>
            <ul class="list-disc pl-5 space-y-2 text-gray-700">
              <li>Discover quality cars from trusted sellers with clear photos, specs, and pricing.</li>
              <li>Filter by make, model, year, and price — and sort by price, year, or mileage.</li>
              <li>Get transparent total-cost estimates: loan, road tax (ICE & EV), and insurance.</li>
              <li>Open detailed pages to compare trims, view equipment, and check key performance data.</li>
              <li>Contact sellers instantly via WhatsApp to ask questions or book a viewing.</li>
              <li>Pick up where you left off with Recently Viewed and revisit your saved searches.</li>
            </ul>
          </div>
          <div class="bg-gray-50 p-6 rounded-xl border">
            <h3 class="text-xl font-bold mb-2">Built for confidence</h3>
            <ul class="list-disc pl-5 space-y-2 text-gray-700">
              <li>Fast, mobile-friendly experience designed for easy comparison.</li>
              <li>Consistent cards and spec layouts so differences stand out quickly.</li>
              <li>Quick insight charts highlight popular makes, models, and trends.</li>
              <li>Secure profile and session handling to personalize your journey.</li>
            </ul>
          </div>
        </div>

        <h3 class="text-2xl font-extrabold mb-4">Frequently Asked Questions</h3>
        <div class="space-y-3">
          <details class="group bg-white rounded-xl border p-4 open:shadow">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">How do I search and filter cars?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              Use the search panel on the Home or Listings page to choose a make/model and set your year and price range. Your selections stay as you browse.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">How accurate are the calculators?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              Road tax (ICE/EV) and insurance are planning estimates. Actual amounts may vary by JPJ and insurer. For financing, use the loan calculator on the car details page and confirm with your bank or preferred lender.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">What do “Used”, “Reconditioned”, and “New” mean?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              <strong>Used:</strong> Previously registered and driven locally. <strong>Reconditioned:</strong> Imported and refurbished unit, often low mileage. <strong>New:</strong> Unregistered unit from a seller.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">How do I contact a seller?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              Open the car’s details and tap the WhatsApp button under seller info. A chat opens with the car link attached so you can ask questions or arrange a viewing.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">Why do some cars show 0.0 L engine capacity?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              That indicates an electric vehicle (EV). Switch the Road Tax calculator to EV mode for a kW-based estimate.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">What is “Recently Viewed” and how is it used?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              We keep the last few cars you opened (in your current browser session) and show them on the Home page and your Profile so you can return quickly.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">Can I save searches or favorites?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              Yes — use the Saved page in the menu to revisit filters you care about. We’re continuing to refine this feature based on user feedback.
            </div>
          </details>
          <details class="group bg-white rounded-xl border p-4">
            <summary class="cursor-pointer flex items-center justify-between">
              <span class="font-semibold text-gray-800">How do I report a problem with a listing?</span>
              <span class="ml-4 text-gray-500 group-open:rotate-180 transition-transform">▾</span>
            </summary>
            <div class="mt-2 text-gray-700">
              Share the car link with the seller via WhatsApp, or contact your admin. Including details (screenshots and what went wrong) helps us fix issues faster.
            </div>
          </details>
        </div>

        <div class="mt-10">
          <a href="main.php" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Back to Home</a>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-white p-4 mt-auto">
    <div class="container mx-auto text-center">
  <p>&copy; <?php echo date('Y'); ?> Great Value Car (GVC). All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
