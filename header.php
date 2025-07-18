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
     header("Location: ./profile.php");
     exit();
}
define('ROOT_URL',  'http://'.$_SERVER['HTTP_HOST'] . '/');
$root = ROOT_URL;

?>
<!doctype html>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futsal Sri Lanka - Book Your Ground</title>
      
<!--     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/style_book.css">  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <link href="./css/output.css" rel="stylesheet">
    
    
</head>
<body>
    <!-- Header -->
     <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="<?= htmlspecialchars($root) ?>"  class="no- text-teal-700 hover:text-teal-900 flex items-center"><i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span></a>
                </div>
            </div>
            <nav class="hidden md:flex space-x-8">
                <a href="<?= htmlspecialchars($root) ?>"  class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="#" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-map-marked-alt mr-2"></i> Find Grounds
                </a>
                <a href="#" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-heart mr-2"></i> Favorites
                </a>
                <a href="#tab2" class="text-gray-600 hover:text-teal-600 font-medium flex items-center">
                    <i class="fas fa-calendar-check mr-2"></i> My Bookings
                </a>
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
                
                
            </div>
        </div>
        
        
    </header>
