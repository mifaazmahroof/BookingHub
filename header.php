<?php
date_default_timezone_set('Asia/Colombo');
include 'futsal_db.php'; // Make sure the path and file name are correct
session_start();
$role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$user = null; // Initialize to avoid undefined variable
$full_name = null;

if ($role === "customer" && $user_id) {
    $user = getCustomerDetails($user_id); // Assumes function exists and returns full name
	$full_name = $user['full_name'];
}

if ($role === "client" && $user_id) {
    $user = getClientName($user_id); // Changed from $client_id to $user_id assuming same session key
	$full_name = $user['name'];
     header("Location: ./Profile.php");
     exit();
}
define('ROOT_URL',  'http://'.$_SERVER['HTTP_HOST'] . '/');
$root = ROOT_URL;

?>
<!doctype html>
<html lang="en" x-data="tabHandler()" xmlns="http://www.w3.org/1999/xhtml">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="/Manifest.json">
<meta name="theme-color" content="#0d6efd">
    <title>Futsal Sri Lanka - Book Your Ground</title>
      
<!--     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/style_book.css">  -->



<script>
  let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;

  // Show custom install button
  document.getElementById('installBtn').style.display = 'block';

  document.getElementById('installBtn').addEventListener('click', () => {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then((choiceResult) => {
      deferredPrompt = null;
    });
  });
});

</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <link href="./css/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/service-worker.js');
}
</script>

    
<script>
  /* // Show popup on page load
  window.addEventListener("load", () => {
    document.getElementById("popup").classList.remove("hidden");
  });

  // Close popup on button
  document.getElementById("close-btn").addEventListener("click", () => {
    document.getElementById("popup").classList.add("hidden");
  });

  // Close popup when clicking outside
  window.addEventListener("click", (e) => {
    const popup = document.getElementById("popup");
    if (e.target === popup) popup.classList.add("hidden");
  });
 */
  // Go to Top button visibility
  
</script>
</head>
<!-- Scroll to Top Button -->

    <!-- Header -->
     <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="<?= htmlspecialchars($root) ?>"  class="no- text-teal-700 hover:text-teal-900 flex items-center"><i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span><sup class="text-gray-500 hover:text-gray-900 text-sm" id="name_ctr" style="text-transform: uppercase;"></sup></a>
                </div>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="."  class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="#" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-map-marked-alt mr-2"></i> Find Grounds
                </a>
                <a href="favorites.php" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-heart mr-2"></i> Favorites
                </a>
                <?php if($full_name): ?>
                <a href="#tab2" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-calendar-check mr-2"></i> My Bookings
                </a>
                <?php endif; ?>
            </nav>
            <div class="flex items-center space-x-4">
                

			 <?php if($full_name): ?>
                    <button id="profile_pg"><i class="fas fa-user"></i>&nbsp;<span id="user_acc" value="<?php echo htmlspecialchars($user_id); ?>"><?php echo htmlspecialchars($full_name); ?></span> </button>
					<button class="px-4 py-2 text-sm rounded-md btn-primary text-white hover:shadow-lg transition-all" id="logout_btn">
             <i class="fas fa-sign-out-alt"></i> Logout</a></button>
                <?php else: ?>
                    <button class="px-4 py-2 text-sm rounded-md btn-primary text-white hover:shadow-lg transition-all" id="login_btn">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login / Signup
                </button>
                <!-- <button class="px-4 py-2 text-sm rounded-md border border-teal-600 text-teal-600 hover:bg-teal-50 transition-all" id="register_btn">
                    Register
                </button> -->
				
                <?php endif; ?>
                <button
  type="button"
  class="hidden bottom-6 right-6 z-50 btn-primary text-white p-2 rounded-full shadow-lg hover:bg-blue-600 hover:shadow-lg transition text-xl"
  id="goTopBtn">&nbsp;&nbsp;↑&nbsp;&nbsp;
  <!-- <span class="[&>svg]:w-4">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="3"
      stroke="currentColor">
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
    </svg> -0->
  </span>-->
</button>
                
            </div>
            
        </div>
        
        
    </header>

<!-- <div id="popup" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
  <div class="relative bg-white rounded-xl shadow-lg p-6 w-80 text-center">
    <button id="close-btn" class="absolute top-2 right-3 text-red-500 text-xl font-bold">&times;</button>
    <h2 class="text-lg font-semibold mb-2">Special Offer!</h2>
    <p class="mb-4">Get 20% off on your first order.</p>
    <a href="offer-page.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Claim Now</a>
  </div>
</div> -->
