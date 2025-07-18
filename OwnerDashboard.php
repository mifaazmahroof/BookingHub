<?php
$client_id = $_SESSION['user_id'];
$data = getClientBooking($client_id);
$client_details = getClientName($client_id);
$bookingData = getStaduimBookingdata($client_id);
$bookingDetails = getBookingDetails($client_id);
$location_details = getLocationDetails();
$pitchdetails = getPitchDetails($client_id);
$reviews_details = getReviewDetails($client_id);
$imagesPitches = getImagesOfPitch($client_id);
$groundDetails = getGroundDetails($client_id);
$courts = getCourttypes();
$showAll = isset($_GET['all']) && $_GET['all'] == '1';
$displayBookings = $showAll ? $bookingDetails : array_slice($bookingDetails, 0, 5);
$default_city = $client_details['location'];
define('ROOT_URL',  'http://'.$_SERVER['HTTP_HOST'] . '/');
$root = ROOT_URL;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - FutsalSL</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="./css/output.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
      .profile-container {
      position: relative;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }

    .profile-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .overlay {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: 0.3s;
    }

    .profile-container:hover .overlay {
      opacity: 1;
    }

    .overlay i {
      font-size: 24px;
    }

    input[type="file"] {
      display: none;
    }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            color: white;
        }
        
        .sidebar-link.active:hover {
            background: linear-gradient(135deg, #0d635c 0%, #0e4d47 100%);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.open {
            transform: translateX(0);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(15, 118, 110, 0.4);
        }
        .tag-box {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 5px;
            gap: 5px;
            cursor: text;
        }

        .tag-box input {
          border: none;
          outline: none;
          flex: 1;
          min-width: 100px;
        }

.tag {
  background-color: #dbeafe;
  padding: 5px 10px;
  border-radius: 15px;
  font-size: 14px;
  display: flex;
  align-items: center;
}

.tag .remove {
  margin-left: 8px;
  cursor: pointer;
}
.no-scrollbar::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}

    .crop-container {
      width: 300px;
      height: 300px;
      overflow: hidden;
      position: relative;
    }
    #preview {
      max-width: 100%;
    }
    #zoomSlider {
      width: 300px;
      margin-top: 10px;
    }





    </style>
    
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div class="flex-1 overflow-y-hidden inset-0 bg-black/50 z-40 md:hidden hidden" id="mobileOverlay"></div>
        
        <!-- Sidebar - Desktop -->
        <aside class="flex flex-col h-screen w-64 bg-white shadow-md z-50">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="./logout.php" class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                        <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span></a>
                </div>
                <div class="text-sm text-gray-600">Vendor Dashboard</div>
            </div>
            <nav class="flex-1 overflow-y-auto md:p-1 p-2 no-scrollbar">
              <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Management</div>
                    <a href="#dashboard_main" class="sidebar-link active flex items-center py-3 px-4 rounded-lg mb-2 transition-all text-gray-700 hover:bg-gray-100 no-underline">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#ground_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-futbol w-5 mr-3"></i>
                        <span>My Grounds</span>
                    </a>
                    <a href="#booking_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span>Bookings</span>
                    </a>
                    <a href="#gallery_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-image w-5 mr-3"></i>
                        <span>Gallery</span>
                    </a>
                </div>
                <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Finance</div>
                    <a href="#earnings_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-money-bill-wave w-5 mr-3"></i>
                        <span>Earnings</span>
                    </a>
                    <a href="#commissions_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-percentage w-5 mr-3"></i>
                        <span>Commissions</span>
                    </a>
                    <a href="#payout_history_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-file-invoice w-5 mr-3"></i>
                        <span>Payout History</span>
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Account</div>
                    <a href="#profile_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-user-circle w-5 mr-3"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#settings_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        <span>Settings</span>
                    </a>
                    <a href="#help_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-question-circle w-5 mr-3"></i>
                        <span>Help</span>
                    </a>
                </div>
                
            </nav>
            
        </aside>
        
        <!-- Sidebar - Mobile -->
        <aside class="mobile-menu flex flex-col h-screen w-64 bg-white shadow-md z-50 md:hidden" id="mobileMenu">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span>
                </div>
                <div class="text-sm text-gray-600">Vendor Dashboard</div>
            </div>
            <nav class="flex-1 overflow-y-auto no-scrollbar md:p-1 p-2 ">
             <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Management</div>
                    <a href="#dashboard" class="sidebar-link active flex items-center py-3 px-4 rounded-lg mb-2 transition-all text-gray-700 hover:bg-gray-100 no-underline">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#grounds" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-futbol w-5 mr-3"></i>
                        <span>My Grounds</span>
                    </a>
                    <a href="#bookings" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span>Bookings</span>
                    </a>
                    <a href="#gallery_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-image w-5 mr-3"></i>
                        <span>Gallery</span>
                    </a>
                </div>
                <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Finance</div>
                    <a href="#earnings" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-money-bill-wave w-5 mr-3"></i>
                        <span>Earnings</span>
                    </a>
                    <a href="#commissions" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-percentage w-5 mr-3"></i>
                        <span>Commissions</span>
                    </a>
                    <a href="#payout_history" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-file-invoice w-5 mr-3"></i>
                        <span>Payout History</span>
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Account</div>
                    <a href="#profile_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-user-circle w-5 mr-3"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#settings_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        <span>Settings</span>
                    </a>
                    <a href="#help" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-question-circle w-5 mr-3"></i>
                        <span>Help</span>
                    </a>
                </div>
                
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center">
                        
                        <h1 class="text-xl font-semibold" id="name_side_bar">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                       <!--  <button class="relative p-2 text-gray-600 hover:text-teal-600">
                            <i class="far fa-bell text-xl"></i>
                            <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-5 w-5 flex items-center justify-center text-xs">3</span>
                        </button> -->
<!--                         <button id="logout_btn" class="relative p-2 text-gray-600 hover:text-teal-600">
                            <i class="fas fa-sign-out-alt text-xl"></i>
                        </button> -->
<div class="border-zinc-200 dark:border-zinc-800">
    <div x-data="{ open: false }" class="relative rounded group/item">
      <button @click="open = !open" class="flex items-center justify-between w-full px-2 py-2">
        
        <div class="mr-3 text-right hidden sm:block">
                                <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($client_details['name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($client_details['email_id']) ?></div>
                            </div>
<?php
if($client_details['image_path']){
$image = $client_details['image_path'];
}
else{
    $image = 'uploads/dp.jpeg';
}
?>

                            <img src="<?= htmlspecialchars($image) ?>" alt="User" class="h-10 w-10 rounded-full" id="s_dp"/>
        <svg class="w-5 h-5 ml-auto text-zinc-600 group/edit invisible group-hover/item:visible" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
      </button>
      <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-full rounded bg-white shadow">
        <a href="#profile_main"  @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-user"></i> My profile
        </a>
        <a href="#settings_main" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-gear"></i> Settings
        </a>
        <a href="./logout.php" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline">
          <i class="fa-solid fa-sign-out-alt"></i> Sign out
        </a>
      </div>
    </div>
  </div>

                        <!-- <div class="border-zinc-200 dark:border-zinc-800">
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open" class="flex items-center justify-between w-full px-2 py-2 rounded">
        
        <div class="mr-3 text-right hidden sm:block">
                                <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($client_details['name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($client_details['email_id']) ?></div>
                            </div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Gecko_foot_on_glass.JPG/250px-Gecko_foot_on_glass.JPG" alt="User" class="h-10 w-10 rounded-full" />
        <svg class="w-5 h-5 ml-auto text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
      </button>
      <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-full rounded bg-white shadow dark:bg-zinc-800">
        <a href="/my-profile" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">
          <i class="fa-solid fa-user"></i> My profile
        </a>
        <a href="/settings" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">
          <i class="fa-solid fa-gear"></i> Settings
        </a>
        <a href="./logout.php" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700 no-underline">
          <i class="fa-solid fa-sign-out-alt"></i> Sign out
        </a>
      </div>
    </div>
  </div> -->
                       <!--  <div class="flex items-center">
                            <div class="mr-3 text-right hidden sm:block">
                                <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($client_details['name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($client_details['email_id']) ?></div>
                            </div>
                            <div class="bg-teal-600 text-white rounded-full h-9 w-9 flex items-center justify-center">
                                <button class="font-bold" onclick="profilepage()">CS</button>
<!-/- <span class="font-bold" onclick="profilepage()">CS</span> -/->
                            </div>
                        </div> -->
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar" id="dashboard_main">
                <!-- Welcome Message -->
                <div class="bg-white rounded-xl shadow mb-6 p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800">Welcome back, <?= htmlspecialchars($client_details['name']) ?>!</h2>
                        <button class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all whitespace-nowrap" id="createBtn_pitch">
                            <i class="fas fa-plus mr-2"></i> Add New Ground
                        </button>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Stats Card 1 -->
                    <div class="bg-white rounded-xl shadow p-6 stats-card transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Total Bookings</h3>
                            <div class="bg-blue-100 text-blue-500 rounded-full h-9 w-9 flex items-center justify-center">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><?= htmlspecialchars($data['total_bookings']) ?></span>
                            <span class="text-green-500 text-sm flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> 12%
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Compared to last month</div>
                    </div>
                    
                    <!-- Stats Card 2 -->
                    <div class="bg-white rounded-xl shadow p-6 stats-card transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Revenue</h3>
                            <div class="bg-green-100 text-green-500 rounded-full h-9 w-9 flex items-center justify-center">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2">Rs. <?= htmlspecialchars($data['total_Revenue']) ?></span>
                            <span class="text-green-500 text-sm flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> 8%
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Compared to last month</div>
                    </div>
                    
                    <!-- Stats Card 3 -->
                    <div class="bg-white rounded-xl shadow p-6 stats-card transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Upcoming Bookings</h3>
                            <div class="bg-purple-100 text-purple-500 rounded-full h-9 w-9 flex items-center justify-center">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><?= htmlspecialchars($data['upcoming']) ?></span>
                            <span class="text-red-500 text-sm flex items-center">
                                <i class="fas fa-arrow-down mr-1"></i> 4%
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2">For the next 7 days</div>
                    </div>
                    
                    <!-- Stats Card 4 -->
                    <div class="bg-white rounded-xl shadow p-6 stats-card transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500 text-sm font-medium">Average Rating</h3>
                            <div class="bg-yellow-100 text-yellow-500 rounded-full h-9 w-9 flex items-center justify-center">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><?= htmlspecialchars($client_details['average_rating']) ?></span>
                            <!-- <span class="text-green-500 text-sm flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> 0.2
                            </span> -->
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Based on <?= htmlspecialchars($client_details['review_count']) ?> reviews</div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-lg font-bold text-gray-800">Recent Bookings</h2>
                           
                        <div class="text-right mt-2">
    <button id="toggleView" class="text-teal-600 hover:text-teal-800 text-sm font-medium whitespace-nowrap">
        View All <i class="fas fa-chevron-down ml-1"></i>
    </button>
</div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pitch</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booked Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($bookingDetails as $index => $row): ?>
    <tr class="hover:bg-gray-50 <?= $index >= 5 ? 'hidden-row hidden' : '' ?>">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#BK-<?= htmlspecialchars($row['booking_id']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['booking_date']) ?> • <?= htmlspecialchars($row['timeslot']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['pitch_name']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['full_name']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['created_at']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['rate_applied']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
         <?php
    $status = htmlspecialchars($row['status']);
    $class = '';

    if (strtolower($status) === 'confirmed') {
        $class = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800';
    } elseif (strtolower($status) === 'pending') {
        $class = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800';
    }elseif (strtolower($status) === 'payment awaited') {
        $class = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
    }
    ?>
    <span class="<?= $class ?>"><?= $status ?></span>    
        
        <!-- <\?= htmlspecialchars($row['status']) ?> --></td>
    </tr>
<?php endforeach; ?>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Reviews -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-lg font-bold text-gray-800">Recent Reviews</h2>
                            <a href="#" class="text-teal-600 hover:text-teal-800 text-sm font-medium whitespace-nowrap">View All <i class="fas fa-chevron-right ml-1"></i></a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Review 1 -->
                            <!-- <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all">
                                <div class="flex items-start mb-4">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-12 h-12 rounded-full mr-4 border-2 border-teal-500">
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Sanjay Amarasinghe</h3>
                                        <div class="flex items-center mb-1">
                                            <div class="flex text-yellow-500 text-sm mr-2">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <span class="text-xs text-gray-500">2 days ago</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">"Excellent facilities! The pitch was in perfect condition and the staff were very helpful. The booking process through the website was simple and quick."</p>
                            </div> -->
                            <?php foreach ($reviews_details as $review) : 
                    $average = floatval($review['rating']);
$fullStars = floor($average);
$hasHalfStar = ($average - $fullStars) >= 0.25 && ($average - $fullStars) < 0.75;
$emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
if($review['cus_id']){
$image = $review['image_path'];
$name = $review['full_name'];
}
else{
    $image = 'uploads/dummy-user.png';
    $name = 'Anonymous';
}
                    ?>
                    
                <!-- Review 1 -->
                <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-lg transition-all">
                    <div class="flex items-start mb-6">
                        <img src="<?= html_entity_decode($image) ?>" alt="User" class="w-14 h-14 rounded-full mr-4 border-2 border-teal-500">
                        <div>
                                <div class="flex items-center mb-1">
                                  
                                    <h3 class="font-bold text-gray-800 mr-3"><?= htmlspecialchars($name) ?></h3>
                                    
                                    <div class="flex text-yellow-500 mr-2">
    <?php for ($i = 0; $i < $fullStars; $i++): ?>
        <i class="fas fa-star"></i>
    <?php endfor; ?>

    <?php if ($hasHalfStar): ?>
        <i class="fas fa-star-half-alt"></i>
    <?php endif; ?>

    <?php for ($i = 0; $i < $emptyStars; $i++): ?>
        <i class="far fa-star"></i>
    <?php endfor; ?>
                                </div>
                                
                                </div>
                                
                                <div class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($review['review_date']) ?></div>
                            </div>
                            <p class="flex text-gray-700 font-bold ml-4">[<?= $review['pitch_name'] ?>]</p>
                    </div>
                    
                    <p class="text-gray-700 italic">"<?= $review['comment'] ?>"</p>
                    
                </div>
                <?php endforeach; ?>
                            
                            <!-- Review 2 -->
                            <!-- <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all">
                                <div class="flex items-start mb-4">
                                    <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="User" class="w-12 h-12 rounded-full mr-4 border-2 border-teal-500">
                                    <div>
                                        <h3 class="font-bold text-gray-800 mb-1">Dinesh Perera</h3>
                                        <div class="flex items-center mb-1">
                                            <div class="flex text-yellow-500 text-sm mr-2">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                            <span class="text-xs text-gray-500">1 week ago</span>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm">"Great location and good facilities. The changing rooms were clean and the pitch was well-maintained. Only downside was that the refreshments were a bit expensive."</p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="ground_main">
<!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-lg font-bold text-gray-800">Pitches</h2>
                           
                        <div class="text-right mt-2">
    <button id="toggleView" class="text-teal-600 hover:text-teal-800 text-sm font-medium whitespace-nowrap">
        View All <i class="fas fa-chevron-down ml-1"></i>
    </button>
</div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="text-center">
                                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">DP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Pitch ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Pitch Type - Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Opening / Closing Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Weekend Opening / Closing Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Off Peak / Peak Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle text-center">Edit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($groundDetails as $index => $row): ?>
    <tr class="hover:bg-gray-50 <?= $index >= 5 ? 'hidden-row hidden' : '' ?>">
        <td class="px-1 py-1 whitespace-nowrap text-sm text-gray-500 align-middle"><img src="<?= ROOT_URL . htmlspecialchars($row['image_path']) ?>" class="text-white rounded-full w-30 flex items-center justify-center" /></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#PT-<?= htmlspecialchars($row['pitch_id']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['gamename']) ?> • <?= htmlspecialchars($row['pitch_name']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['opening_time']) ?> • <?= htmlspecialchars($row['closing_time']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['WeekEnd_opentime']) ?> • <?= htmlspecialchars($row['WeekEnd_closetime']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['offpeak_rate']) ?> • <?= htmlspecialchars($row['peak_rate']) ?></td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><button type="submit" class="bg-teal-100 rounded-2xl p-4 hover:bg-teal-300 hover:text-gray-200" PId="<?= htmlspecialchars($row['pitch_id']) ?>">Edit</button></td>
        
    </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="booking_main">
<p>booking</p>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="gallery_main">
<section class="p-6 bg-gray-100 min-h-screen">

<div id="imgresponse"></div>
<?php foreach ($imagesPitches as $pitch_id => $paths): ?>
    <div class="mb-10">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold capitalize text-gray-800"><?= htmlspecialchars($paths['pitch_name']) ?> Images</h2>
          

<form class="uploadForm" enctype="multipart/form-data" data-pitch-id="<?= htmlspecialchars($pitch_id) ?>">
  
  <input type="hidden" name="pitch_id" id="getPitchId" value="<?= htmlspecialchars($pitch_id) ?>">

  <label class="cursor-pointer bg-teal-600 text-white px-4 py-2 rounded-xl hover:bg-teal-700">
    + Add Images
    <input type="file" name="image[]" class="hidden imageInput" multiple accept="image/*">
  </label>
</form>


      </div>
      <div id="image-gallery<?= htmlspecialchars($pitch_id) ?>">
      
<?php
$pitch_Id = htmlspecialchars($pitch_id); // or get it from URL or session
include 'pitchImages.php';
?>



        
      </div>
    </div>
  <?php endforeach; ?>





<!--  <div id="image-gallery">
  <?php include 'pitchImages.php'; ?>
</div> -->
  
</section>

            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="earnings_main">
<p>earnings</p>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="commissions_main">
<p>commission</p>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="payout_history_main">
<p>Payout</p>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="profile_main">
                <div class="bg-white rounded-xl shadow mb-6 p-6">
                  <div class="grid grid-cols-[auto_1fr_auto] gap-4 items-center">

<?php
if($client_details['image_path']){
$image = $client_details['image_path'];
}
else{
    $image = 'uploads/dp.jpeg';
}
?>
  <!-- Image (spans 2 rows) -->
<div class="profile-container row-span-2" onclick="document.getElementById('uploadInput').click()">
  <img id="profileImage" src="<?= htmlspecialchars($image) ?>" alt="Profile" />
  <div class="overlay">
    <i class="fa fa-camera"></i>
  </div>
  <input type="file" id="uploadInput"  name="profile_picture" accept="image/*" onchange="previewImage(event)">
</div>
  <!-- <img src="<?= htmlspecialchars($image) ?>" alt="User" class="h-30 w-30 rounded-full object-cover row-span-2" /> -->

  <!-- Name -->
  <h2 class="text-5xl font-bold text-gray-800"><?= htmlspecialchars($client_details['name']) ?></h2>

  <!-- Edit Button (spans 2 rows) -->
  <button class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all whitespace-nowrap row-span-2" id="createBtn_pitch">
    <i class="fas fa-pen mr-2"></i> Edit Profile
  </button>

  <!-- Address -->
   
  <h5 class="text-xl text-gray-700"><?= htmlspecialchars($client_details['address']) ?></h5>
</div>

                   <!--  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                      <?php
if($client_details['image_path']){
$image = $client_details['image_path'];
}
else{
    $image = 'uploads/dummy-user.png';
}
?>

                            <img src="<?= htmlspecialchars($image) ?>" alt="User" class="h-50 w-50 rounded-full" />
                        <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($client_details['name']) ?></h2><br>
                        <h5 class="text-md font-bold text-gray-800"><?= htmlspecialchars($client_details['address']) ?></h5>
                        <button class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all whitespace-nowrap" id="createBtn_pitch">
                            <i class="fas fa-pen mr-2"></i> Edit Profile
                        </button>
                    </div> -->
                </div>

<div class="bg-white rounded-xl shadow mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Stadium Details</h2>
                           

                        </div>
                    </div>
                    <div class="overflow-x-auto">
                      
                        <div class="modal fade" style="display: none" id="newCityModal" tabindex="-1" aria-labelledby="newCityModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCityModalLabel">Add New City</h5>
        <button type="button" onclick="cancelSubmit()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
<div class="mb-3">
  <label for="location_group" class="form-label">Select Location Group</label>
  <select class="form-select" name="location_group" id="location_group" required onchange="selectPrDis()">
    <option value="">-- Select Province - District --</option>
    <?php
      foreach ($location_details as $group => $cities) {
          echo "<option value=\"$group\">$group</option>";
      }
    ?>
  </select>
</div>

        <div class="mb-3">
          <label for="new_city_name" class="form-label">City Name</label>
          <input type="text" class="form-control" id="new_city_name" placeholder="Enter City Name" required disabled>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cancelSubmit()">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitNewCity()" id="addNewCity">Add City</button>
      </div>
    </div>
  </div>
</div>


<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-md">
  <!-- <h2 class="text-2xl font-semibold mb-6 text-gray-800">User Profile</h2> -->

  <form id="profileForm" class="w-full space-y-2">
    <!-- Full Name -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Stadium Name</label></div>
      <div class="flex-1">
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($client_details['name']) ?>"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div>
    </div>
<!-- Address -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Address</label></div>
      <div class="flex-1">
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($client_details['address']) ?>"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div>
    </div>
    <!-- Email -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Email</label></div>
      <div class="flex-1">
        <input type="email" id="email_id" name="email_id" value="<?= htmlspecialchars($client_details['email_id']) ?>"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div>
    </div>

    <!-- Phone -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Phone</label></div>
      <div class="flex-1">
        <input type="text" id="contact_info" name="contact_info" value="<?= htmlspecialchars($client_details['contact_info']) ?>"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div>
    </div>

    
        <!-- Description -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Description</label></div>
      <div class="flex-1">
        <textarea id="notes" name="notes" rows="4"
              class="w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
              disabled><?= htmlspecialchars($client_details['description']) ?></textarea>
       <!--  <input type="text" id="description" name="description" value=""
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div> -->
    </div>
    <!-- Discount Type -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Discount Type</label></div>
      <div class="flex-1">
            <select class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" name="discount_type" id="discount_type" disabled
    >
 <option value="percentage" <?= $client_details['discount_type'] === 'percentage' ? 'selected' : '' ?>>Percentage</option>
      <option value="amount" <?= $client_details['discount_type'] === 'amount' ? 'selected' : '' ?>>Amount</option>
      <option value="none" <?= $client_details['discount_type'] === 'none' ? 'selected' : '' ?>>None</option>
        </select>
       <!--  <input type="text" id="address" name="address"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled> -->
      </div>
    </div>
    <!-- Discount -->
    <div class="flex py-2">
      <div class="w-1/3 font-medium text-gray-700"><label class="align-middle">Discount</label></div>
      <div class="flex-1">
        <input type="text" id="discount" name="discount" value="<?= htmlspecialchars($client_details['discount']) ?>"
               class="w-full px-3 py-1.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
               disabled>
      </div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end pt-6 gap-4">
      <button type="button" id="editBtn"
              class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-6 rounded-md transition"
              onclick="toggleEdit(true)">Edit</button>

      <button type="submit" id="saveBtn"
              class="bg-green-600 hover:bg-green-700 text-white px-10 py-3 rounded-md transition hidden"
              onclick="saveProfile()">Save</button>
    </div>
  </form>
</div>









<!-- Registration Form -->
<div class="container my-5 hidden" >
  <div class="card mx-auto shadow p-4" style="max-width: 600px;">
    <form id="updateForm">

      <div class="mb-3">
        <label for="indoor_name" class="form-label">Indoor Name</label>
        <input type="text" class="form-control" id="indoor_name" name="indoor_name" required>
      </div>

      <div class="mb-3">
        <label for="reg_doornu" class="form-label">Address</label>
        <div class="flex gap-2 mb-2">
  <input type="text" placeholder="Number and Street name" class="w-full rounded border px-3 py-2" id="reg_doornu" name="address" required />
  
</div>

<!-- City, District, Province -->

<div class="flex gap-2 mb-3">
  <!-- Province -->
  <select class="w-full rounded border px-3 py-2" id="reg_province" name="province" required>
    <option value="">-- Select Province --</option>
    <?php foreach ($provinces as $province): ?>
      <option value="<?= htmlspecialchars($province); ?>"><?= htmlspecialchars($province); ?></option>
    <?php endforeach; ?>
  </select>
</div>
<div class="flex gap-2 mb-3">
  <!-- District -->
  <select class="w-full rounded border px-3 py-2 disabled:bg-gray-100" id="reg_district" name="district" required disabled>
    <option value="">-- Select District --</option>
  </select>
</div>
<div class="flex gap-2 mb-3 relative">
  <!-- City -->
<!--<input type="text" id="reg_city" name="city" placeholder="City" autocomplete="off"
    class="w-full rounded border px-3 py-2" required> -->
    <select class="w-full rounded border px-3 py-2 disabled:bg-gray-100" id="reg_city" name="city" required disabled onchange="checkNewGroup(this.value)">
      <option value="">-- Select City --</option>
  </select>

  <!-- Suggestions dropdown -->
  <div id="citySuggestions"
       class="absolute top-full left-0 z-10 w-1/2 bg-white border rounded mt-1 hidden max-h-40 overflow-y-auto shadow-md">
  </div>
</div>


      </div>


      <div class="mb-3">
        <label for="s_phone" class="form-label">Mobile Number</label>
        <input type="tel" class="form-control" id="s_phone" name="phone" maxlenght="10"  pattern="\d{10}" placeholder="Enter 10-digit phone number" required>
      <label id="phoneCheck" class="form-text"></label>      
      </div>

      <div class="mb-3">
        <label for="s_email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="s_email" name="email" placeholder="Enter valid email address" required>
        <label id="emailCheck" class="form-text"></label>
      </div>

      <div class="mb-3">
        <label for="s_user" class="form-label">Username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="s_user" name="username" required autocomplete="off">
        <label id="checkuser" class="form-text"></label>
      </div>

<!--       <div class="mb-3">
        <label for="s_pass" class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="s_pass" name="password" required autocomplete="off">
        <label id="passcheck" class="form-text"></label>
        <div class="absolute inset-y-0 right-0 flex items-center px-3 cursor-pointer text-gray-700" onclick="togglePasswordVisibility()">
    <i class="fa fa-eye-slash" id="toggleIcon" aria-hidden="true"></i>
  </div>
        
      </div> -->
      <div class="mb-3 position-relative">
  <label for="s_pass" class="form-label">Password <span class="text-danger">*</span></label>
  
  <div class="input-group">
    <input type="password" class="form-control" id="s_pass" name="password" required autocomplete="off">
    <span class="input-group-text cursor-pointer" id="eye_pass" style="display:none" onclick="togglePasswordVisibility()" style="cursor: pointer;">
      <i class="fa fa-eye-slash" id="toggleIcon" aria-hidden="true"></i>
    </span>
  </div>

  <label id="passcheck" class="form-text"></label>
</div>

      <div class="mb-3 position-relative">
        <label for="s_c_pass" class="form-label">Confirm Password <span class="text-danger">*</span></label>
        <div class="input-group">
        <input type="password" class="form-control" id="s_c_pass" name="confirm_password" required disabled>
        <span class="input-group-text cursor-pointer" id="eye_Cpass" style="display:none" onclick="toggleCPasswordVisibility()" style="cursor: pointer;">
      <i class="fa fa-eye-slash" id="toggleIcon2" aria-hidden="true"></i>
    </span>
        
        </div>
        <label id="confirmcheck" class="form-text"></label>
      </div>

      <div class="d-grid">
        <button type="submit" id="s_Register" class="btn btn-success" disabled>Register</button>
      </div>

    </form>
    <div id="response"></div>
  </div>
</div>
                    </div>
                </div>


            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="settings_main">
<p>settings</p>
            </main>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 hidden no-scrollbar" id="help_main">
<p>Help</p>
            </main>
        </div>




    </div>

<datalist id="hourList">
  <option value="00:00">
  <option value="01:00">
  <option value="02:00">
  <option value="03:00">
  <option value="04:00">
  <option value="05:00">
  <option value="06:00">
  <option value="07:00">
  <option value="08:00">
  <option value="09:00">
  <option value="10:00">
  <option value="11:00">
  <option value="12:00">
  <option value="13:00">
  <option value="14:00">
  <option value="15:00">
  <option value="16:00">
  <option value="17:00">
  <option value="18:00">
  <option value="19:00">
  <option value="20:00">
  <option value="21:00">
  <option value="22:00">
  <option value="23:00">
</datalist>

<div id="_createPitchModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-white rounded-2xl shadow-lg shadow-zinc-500 w-full max-w-2xl mx-4 max-h-screen px-3 py-1 flex flex-col">
    <div class="p-6 flex flex-col flex-grow overflow-y-auto">
      <form method="POST" action="" id="createPitchForm" name="createPitchForm" class="flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b-zinc-300 shadow pb-3 flex-shrink-0">
          <h2 class="text-xl font-semibold">Create New Pitch</h2>
          <button type="button" class="text-gray-500 hover:text-black text-2xl" onclick="closeModal()">×</button>
        </div>

        <!-- Modal Body -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 flex-grow overflow-y-auto">
          
            <div>
              <label class="block text-sm font-medium mb-1">Pitch Name:</label>
              <input type="text" name="pitch_name" placeholder="Pitch Name" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
            </div>
            <div>
              <label for="court_id" class="block text-sm font-medium mb-1">Court Type:</label>
              <select name="court_id" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" id="gameType" required onchange="checkNewGroup(this.value)">
                <option value="">-- Select Court Type --</option>
                <?php foreach ($courts as $row): ?>
                  <option value="<?= htmlspecialchars($row['gameid']) ?>"><?= htmlspecialchars($row['gamename']) ?></option>
                <?php endforeach; ?>
                <option value="__New__">-- New Type --</option>
              </select>
            </div>
         
<div class="col-span-full rounded-2xl border border-zinc-400 shadow-zinc-500  shadow-md bg-white p-6 space-y-4">
  <!-- Toggle Row -->
  <div class="flex items-center justify-between">
    <label for="enableWeekendTime" class="text-sm font-medium text-zinc-900">
      Will this be available 24 hours a day?
    </label>
    <label class="relative inline-flex items-center cursor-pointer">
      <input type="checkbox" id="enableWeekendTime" class="sr-only peer" onchange="toggle24open(this)">
      <div class="w-11 h-6 rounded-full bg-zinc-300 peer-checked:bg-green-500 transition-colors duration-200"></div>
      <div class="absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform duration-200 transform peer-checked:translate-x-5"></div>
    </label>
  </div>

  <!-- Time Inputs -->
  <div id="openTimeFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
      <label for="opening_time" class="block mb-1 text-sm font-medium text-zinc-800">Opening Time:</label>
      <input
        list="hourList"
        placeholder="HH:00"
        id="opening_time"
        name="opening_time"
        step="3600"
        required
        onchange="setWeekopenTime(this.value)"
        class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200"
      >
    </div>
    <div>
      <label for="closing_time" class="block mb-1 text-sm font-medium text-zinc-800">Closing Time:</label>
      <input
        list="hourList"
        placeholder="HH:00"
        id="closing_time"
        name="closing_time"
        step="3600"
        required
        onchange="setWeekcloseTime(this.value)"
        class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200"
      >
    </div>
  </div>
</div>


            <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-6 space-y-4">
                 <div class="col-span-full flex items-center justify-between mt-2">
  <label class="text-sm font-medium">Set Off Peak Start/End Time</label>
  <!-- <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableHoilydayPrice" class="sr-only peer" onchange="toggleHolidayPrice(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label> -->
</div>
<div id="openTimeFields" class="grid sm:grid-cols-2 gap-4">
  <div>
              <label class="block text-sm font-medium mb-1">Off Peak Start:</label>
              <input list="hourList" placeholder="HH:00" name="off_peak_start" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required step="3600" onchange="setWeekPeakStart(this.value)">
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Off Peak End:</label>
              <input list="hourList" placeholder="HH:00" name="off_peak_end" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required step="3600" onchange="setWeekPeakEnd(this.value)">
            </div>
</div>
            
            </div>
            <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-6 space-y-4">
                 <div class="col-span-full flex items-center justify-between mt-2">
  <label class="text-sm font-medium">Set Price Peak/Off Peak</label>
<!--   <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableHoilydayPrice" class="sr-only peer" onchange="toggleHolidayPrice(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label> -->
</div>
<div id="openTimeFields" class="grid sm:grid-cols-2 gap-4">
  <div>
              <label class="block text-sm font-medium mb-1">Peak Rate:</label>
              <input type="number" step="0.01" name="peak_rate" placeholder="Rs: 0"  class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" onchange="setWeekPeakrate(this.value)" required>
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Off-Peak Rate:</label>
              <input type="number" step="0.01" name="off_peak_rate" placeholder="Rs: 0" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" onchange="setWeekOffPeakrate(this.value)" required>
            </div>
</div>
            
            </div>
<!-- Toggle for Weekend Timing -->
            <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-4 space-y-2">
              <div class="col-span-full flex items-center justify-between mt-2">
  <label for="enableWeekendTime" class="text-sm font-medium">Enable Weekend Opening/Closing Time</label>
  <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableWeekendTime" class="sr-only peer" onchange="toggleWeekendTime(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label>
</div>

          <!-- Weekend Time Fields (Hidden by default) -->
          <div id="weekendTimeFields" class="col-span-full grid sm:grid-cols-2 gap-4 hidden">
            <div>
              <label for="weekend_open_time" class="block text-sm font-medium mb-1">Weekend Opening Time:</label>
              <input list="hourList" name="weekend_open_time" id="weekend_open_time" placeholder="HH:00" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Weekend Closing Time:</label>
              <input list="hourList" name="weekend_close_time" id="weekend_close_time" placeholder="HH:00" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
            </div>
          </div>
            </div>
            
       
            <!-- Toggle for Weekend pricing -->
             <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-4 space-y-2">
              <div class="col-span-full flex items-center justify-between mt-2">
  <label for="enableWeekendPrice" class="text-sm font-medium">Enable Weekend peak/off peak price</label>
  <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableWeekendPrice" class="sr-only peer" onchange="toggleWeekendPrice(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label>
</div>
<div id="weekendpriceFields" class="col-span-full grid sm:grid-cols-2 gap-4 hidden">
<div>
              <label class="block text-sm font-medium mb-1">Weekend Peak Rate:</label>
              <input type="number" step="0.01" name="weekend_peak_rate" id="weekend_peak_rate" placeholder="Rs: 0" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
            </div>            
<div>
              <label class="block text-sm font-medium mb-1">Weekend Off-Peak Rate:</label>
              <input type="number" step="0.01" name="weekend_offPeak_rate" id="weekend_offPeak_rate" placeholder="Rs: 0" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
            </div>
            
            </div>
             </div>
       
            <!-- Toggle for holiday pricing -->
              <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-4 space-y-2">
                 <div class="col-span-full flex items-center justify-between mt-2">
  <label for="enableHoilydayPrice" class="text-sm font-medium">Enable Holiday peak/off peak price</label>
  <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableHoilydayPrice" class="sr-only peer" onchange="toggleHolidayPrice(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label>
</div>
<div id="holidaypriceFields" class="col-span-full grid sm:grid-cols-2 gap-4 hidden">
<div>
              <label class="block text-sm font-medium mb-1">Holiday Peak Rate:</label>
              <input type="number" step="0.01" placeholder="Rs: 0" name="holiday_peak_rate" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
            </div>            
<div>
              <label class="block text-sm font-medium mb-1">Holiday Off-Peak Rate:</label>
              <input type="number" step="0.01" placeholder="Rs: 0" name="holiday_offpeak_rate" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" required>
            </div>
            
            </div>
              </div>


            <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-4 space-y-2">
                 <div class="col-span-full flex items-center justify-between mt-2">
  <label class="text-sm font-medium">Features & Amenities</label>
</div>
<div class="tag-input-wrapper">
  <div id="tag-box" class="tag-box  w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200">
    <input type="text" id="multiInput" placeholder="Type and press Enter or comma" />
  </div>
  <input type="hidden" id="tagValues" name="tagValues" />
</div>
              
            </div>
      <div class="col-span-full bg-white border border-zinc-400 shadow-zinc-500  shadow-md rounded-2xl p-4 space-y-2">
                 <div class="col-span-full flex items-center justify-between mt-2">
  <label class="text-sm font-medium">Set Display Picture for the Pitch</label>
  <!-- <label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" id="enableHoilydayPrice" class="sr-only peer" onchange="toggleHolidayPrice(this)">
    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-all duration-200"></div>
    <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 transform peer-checked:translate-x-full"></div>
  </label> -->
</div>
            <div class="col-span-full">
              <input type="file" name="dp_image" id="imageInput" class="w-full rounded border border-zinc-400 px-3 py-2 text-sm shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200" accept="image/*" required>
            </div>
<div id="imageCrop" class="space-y-4 hidden">
              <div class="w-[300px] h-[300px] overflow-hidden border rounded-full relative mx-auto">
    <img id="preview" style="display:none;" class="border rounded">
  </div>

  <div class="flex items-center justify-center">
      <input type="range" id="zoomSlider" min="0.1" max="3" step="0.01" value="1"
        class="w-[300px] hidden">
        <button id="cropImageBtn" style="display:none;" type="button">Crop</button>
    </div>

  <input type="hidden" id="croppedImageData" name="cropped_image_data">
  
            </div>
            
            </div>
          
          <!-- Your inputs here -->
        </div>

        <!-- Modal Footer -->
        <div class="mt-6 text-right border-t pt-4 flex-shrink-0">
          <button type="submit" name="create_pitch" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow">Create Pitch</button>
        </div>
      </form>
    </div>
  </div>
</div>





  <!-- Add New Game -->
<div id="newGameType" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 p-6 relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold">Add New Game Type</h2>
      <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
    </div>

    <!-- Body -->
    <div class="mb-4">
      <label for="new_game_type" class="block text-sm font-medium text-gray-700 mb-1">Game Name</label>
      <input type="text" id="new_game_type" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter game type">
    </div>

    <!-- Footer -->
    <div class="flex justify-end space-x-2">
      <button onclick="closeModal()" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-100">Cancel</button>
      <button onclick="submitNewGameType()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Game</button>
    </div>

  </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


<script>
    function toggleEdit(enable) {
  const inputs = document.querySelectorAll('#profileForm input');
  const selects = document.querySelectorAll('#profileForm select');
  const textareas = document.querySelectorAll('#profileForm textarea');
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const typeSelect = document.getElementById('discount_type');
  const discountInput = document.getElementById('discount');

  inputs.forEach(input => input.disabled = !enable);
  selects.forEach(select => select.disabled = !enable);
  textareas.forEach(textarea => textarea.disabled = !enable);

  editBtn.classList.toggle('hidden', enable);
  saveBtn.classList.toggle('hidden', !enable);

  // Re-check discount input state when enabling edit
  if (enable) {
    if (typeSelect.value === 'none') {
      discountInput.disabled = true;
      discountInput.removeAttribute('min');
      discountInput.removeAttribute('max');
    } else if (typeSelect.value === 'percentage') {
      discountInput.disabled = false;
      discountInput.setAttribute('min', '0');
      discountInput.setAttribute('max', '100');
    } else {
      discountInput.disabled = false;
      discountInput.removeAttribute('min');
      discountInput.removeAttribute('max');
    }
  }
}

// Attach listener after DOM is loaded
window.addEventListener('DOMContentLoaded', () => {
  const typeSelect = document.getElementById('discount_type');
  const discountInput = document.getElementById('discount');

  // On initial load
  if (typeSelect && discountInput) {
    if (typeSelect.value === 'percentage') {
      discountInput.setAttribute('min', '0');
      discountInput.setAttribute('max', '100');
    }
  }

  // Change listener
  typeSelect.addEventListener('change', function () {
    if (this.value === 'none') {
      discountInput.disabled = true;
      discountInput.removeAttribute('min');
      discountInput.removeAttribute('max');
    } else if (this.value === 'percentage') {
      discountInput.disabled = false;
      discountInput.setAttribute('min', '0');
      discountInput.setAttribute('max', '100');
    } else {
      discountInput.disabled = false;
      discountInput.removeAttribute('min');
      discountInput.removeAttribute('max');
    }
  });
});

  function saveProfile() {
    event.preventDefault();
    const updatedData = {
      fullname: document.getElementById('fullname').value,
      email: document.getElementById('email').value,
      phone: document.getElementById('phone').value,
      address: document.getElementById('address').value
    };

    console.log("Save to DB:", updatedData);
    toggleEdit(false);
  }
</script>


<script>
  function previewImage(event) {
    const input = event.target;
    const formData = new FormData();
    formData.append('profile_picture', input.files[0]);
    formData.append('stadium_id',  <?= $client_id ?>); // Replace with actual user ID

      const reader = new FileReader();
      fetch('/futsal_db.php?action=stadium_dp', {
    method: 'POST',
    body: formData
      }
  )
  .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('profileImage').src = data.image_url;
        document.getElementById('s_dp').src = data.image_url;
      } else {
        alert('Upload failed: ' + data.message);
      }
    })
    .catch(err => console.error(err));
  }
  
</script>

<script>
  function refreshImages(id) {
  fetch('pitchImages.php?pitch_id=' + encodeURIComponent(id),{
    method: 'GET',
    credentials: 'same-origin' // important to include session cookies
  })
    .then(res => res.text())
    .then(html => {
      document.getElementById('image-gallery'+ id).innerHTML = html;
    });
}
function deleteImage(imageId) {

  if (!confirm("Are you sure you want to delete this image?")) return;

  fetch('/futsal_db.php?action=delete_image', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'image_id=' + encodeURIComponent(imageId)
  })

  .then(res => res.text())
  .then(response => {
    if (response.trim() === 'success') {
      // Remove image div from the DOM
      const imgDiv = document.querySelector(`[data-img='${imageId}']`);
      const pId = document.getElementById('getPitchId').value;
      if (imgDiv) imgDiv.remove();
      console.log(pId);
 refreshImages(pId);
    } else {
      alert("Failed to delete image.");
      console.error(response);
    }
  })
  .catch(err => {
    console.error(err);
    alert("Error deleting image.");
  });




}
</script>
<script>
  
document.querySelectorAll('.uploadForm').forEach(form => {
  const fileInput = form.querySelector('.imageInput');
  const restext = document.getElementById('imgresponse');
  const pitchId = form.dataset.pitchId;
  fileInput.addEventListener('change', function () {
    const formData = new FormData(form);
    if (restext) restext.innerText = "Uploading...";
    fetch('/futsal_db.php?action=upload_images', {
    method: 'POST',
    body: formData
  })
    .then(res => res.text())
    .then(result => {
      if (restext) restext.innerText = result;
       refreshImages(pitchId);
      // Optional: refresh images section dynamically
    })
    .catch(err => {
      console.error("Upload failed:", err);
    });
  });



    

if (restext) restext.innerText = "";

});
</script>



<script>
  // On button click to show a section
function showDiv(divId) {

document.querySelectorAll('nav .sidebar-link.active').forEach(el => el.classList.remove('active'));

  // Add active class to the clicked sidebar link
  const activeLink = document.querySelector(`nav .sidebar-link[href="#${divId}"]`);
  if (activeLink) {
    activeLink.classList.add('active');
  }

  // Hide all <main> sections
  document.querySelectorAll('main').forEach(main => main.classList.add('hidden'));

  // Show the targeted <main> section
  //const targetId = hrefID.replace('#','');
  const targetMain = document.getElementById(divId);
  const targetText = activeLink ? activeLink.textContent.trim() : '';

  if (targetMain) {
    document.getElementById("name_side_bar").textContent = targetText;
    targetMain.classList.remove('hidden');
  }



    // Hide all sections
    document.querySelectorAll('.content-div').forEach(div => div.style.display = 'none');

    // Show the selected section
    const selectedDiv = document.getElementById(divId);
    selectedDiv.style.display = 'block';

    // Store the ID in localStorage
    localStorage.setItem('visibleDiv', divId);
}

</script>

<!-- <script>
document.getElementById('multiimageInput').addEventListener('change', function() {
  const form = document.getElementById('uploadForm');
  const formData = new FormData(form);
document.getElementById('response').innerText = "Uploading...";

  const formObject = Object.fromEntries(formData.entries());
console.log(formObject);

  fetch('/futsal_db.php?action=upload_images', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    location.reload();
  })
  .catch(err => {
    document.getElementById('response').innerHTML = "Upload error!";
    console.error(err);
  });
});
</script> -->


<script>
const editButtons = document.querySelectorAll('button[pid]');
  editButtons.forEach(button => {
    button.addEventListener("click", function () {

event.preventDefault();  // ✅ Stops the page from reloading
  const hrefID = "#gallery_main";
 

  // Remove active class from all sidebar links
  document.querySelectorAll('nav .sidebar-link.active').forEach(el => el.classList.remove('active'));

  // Add active class to the clicked sidebar link
  const activeLink = document.querySelector(`nav .sidebar-link[href="${hrefID}"]`);
  if (activeLink) {
    activeLink.classList.add('active');
  }

  // Hide all <main> sections
  document.querySelectorAll('main').forEach(main => main.classList.add('hidden'));

  // Show the targeted <main> section
  const targetId = hrefID.replace('#','');
  const targetMain = document.getElementById(targetId);
  const targetText = activeLink ? activeLink.textContent.trim() : '';

  if (targetMain) {
    document.getElementById("name_side_bar").textContent = targetText;
    targetMain.classList.remove('hidden');
  }

      
      const pid = this.getAttribute("pid");
      // Replace this with your actual logic
      console.log("Edit clicked for pid:", pid);
      yourEditFunction(pid);
    });
  });

</script>
<script>

function directpage(event, value) {
  event.preventDefault();  // ✅ Stops the page from reloading
  const hrefID = value;
 

  // Remove active class from all sidebar links
  document.querySelectorAll('nav .sidebar-link.active').forEach(el => el.classList.remove('active'));

  // Add active class to the clicked sidebar link
  const activeLink = document.querySelector(`nav .sidebar-link[href="${value}"]`);
  if (activeLink) {
    activeLink.classList.add('active');
  }

  // Hide all <main> sections
  document.querySelectorAll('main').forEach(main => main.classList.add('hidden'));

  // Show the targeted <main> section
  const targetId = hrefID.replace('#','');
  const targetMain = document.getElementById(targetId);
  const targetText = activeLink ? activeLink.textContent.trim() : '';

  if (targetMain) {
    document.getElementById("name_side_bar").textContent = targetText;
    targetMain.classList.remove('hidden');

  }

  
}
</script>
<script>
  function refreshPitchImages(pitchId) {
  fetch('load_pitch_images_partial.php?pitch_id=' + encodeURIComponent(pitchId))
    .then(res => res.text())
    .then(html => {
      // Replace section
      const section = document.querySelector(`[data-pitch-id="${pitchId}"]`).closest('.mb-10');
      if (section) section.outerHTML = html;

      // Cache in localStorage
      localStorage.setItem(`pitchGallery_${pitchId}`, html);
    });
}
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.uploadForm').forEach(form => {
    const pitchId = form.dataset.pitchId;
    const section = form.closest('.mb-10');

    const cached = localStorage.getItem(`pitchGallery_${pitchId}`);
    if (cached && section) {
      section.outerHTML = cached;
    }
  });
});
</script>
<!-- <script>
  window.addEventListener('DOMContentLoaded', () => {
    const lastVisible = localStorage.getItem('visibleDiv');
    if (lastVisible) {
        showDiv(lastVisible);
    } 
});
</script> -->
<script>
const input = document.getElementById('multiInput');
const tagBox = document.getElementById('tag-box');
const hiddenInput = document.getElementById('tagValues');

let tags = [];

input.addEventListener('keydown', function (e) {
  if ((e.key === 'Enter' || e.key === ',') && input.value.trim() !== '') {
    e.preventDefault();
    addTag(input.value.trim());
    input.value = '';
      input.focus();
  }
});

function addTag(text) {
  if (tags.includes(text)) return; // avoid duplicates
  tags.push(text);
  updateTags();

}

function removeTag(text) {
  tags = tags.filter(tag => tag !== text);
  updateTags();
}

function updateTags() {
  // Clear all except input
  tagBox.innerHTML = '';
  tags.forEach(tag => {
    const tagEl = document.createElement('span');
    tagEl.className = 'tag';
    tagEl.innerHTML = `${tag}<span class="remove" onclick="removeTag('${tag}')">&times;</span>`;
    tagBox.appendChild(tagEl);
  });
  tagBox.appendChild(input);

  // Update hidden field with comma-separated values
  hiddenInput.value = tags.join(',');
}
</script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
  let cropper;
  const imageInput = document.getElementById('imageInput');
  const preview = document.getElementById('preview');
  const cropBtn = document.getElementById('cropImageBtn');
  const divImageCrop = document.getElementById('imageCrop');
  const croppedImageInput = document.getElementById('croppedImageData');
  const zoomSlider = document.getElementById('zoomSlider');

  imageInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.style.display = 'block';
    divImageCrop.classList.remove('hidden');
    cropBtn.style.display = 'inline';
    zoomSlider.style.display = 'inline';

    if (cropper) cropper.destroy();

    preview.onload = () => {
      cropper = new Cropper(preview, {
        viewMode: 1,
        dragMode: 'move',
        aspectRatio: 1,
        autoCropArea: 1,
        background: false,
        cropBoxResizable: false,
        cropBoxMovable: false,
        guides: false,
        highlight: false,
        center: true,
        ready() {
          // Center the crop box with fixed dimensions
          const containerData = cropper.getContainerData();
          const imageData = cropper.getImageData();

          // Dynamically set crop box size based on image size, with max 300x300
          const cropSize = Math.min(imageData.naturalWidth, imageData.naturalHeight, 300);
          cropper.setCropBoxData({
            width: cropSize,
            height: cropSize,
            left: (containerData.width - cropSize) / 2,
            top: (containerData.height - cropSize) / 2
          });

          // Make the crop box visually circular
          const cropBox = document.querySelector('.cropper-crop-box');
          if (cropBox) cropBox.style.borderRadius = '50%';

          const viewBox = document.querySelector('.cropper-view-box');
          if (viewBox) {
            viewBox.style.borderRadius = '50%';
            viewBox.style.overflow = 'hidden';
          }
        }
      });
    };
  });

  // Zoom slider functionality
  zoomSlider.addEventListener('input', function () {
    const zoomLevel = parseFloat(this.value);
    if (cropper) cropper.zoomTo(zoomLevel);
  });

  // Crop button action
  cropBtn.addEventListener('click', function () {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    const base64 = canvas.toDataURL('image/jpeg');

    croppedImageInput.value = base64;

    divImageCrop.classList.add('hidden');
    preview.style.display = 'none';
    cropBtn.style.display = 'none';
    zoomSlider.style.display = 'none';

    cropper.destroy();
  });
</script>

<script>
  const dashbd = document.getElementById("dashboard_main");
  const dbSideBar = document.getElementById("dashboardSideBar");
  const grounds = document.getElementById("ground_main");
  const groundSideBar = document.getElementById("groundSideBar");

  const sideBarName = document.getElementById("name_side_bar");
  window.addEventListener("DOMContentLoaded", function () {
  const hash = window.location.hash;





document.querySelectorAll('nav .sidebar-link').forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent page jump to hash

    // Step 1: Remove 'active' from all links
    document.querySelectorAll('nav .sidebar-link.active').forEach(el => el.classList.remove('active'));

    // Step 2: Add 'active' to the clicked link
    this.classList.add('active');

    // Step 3: Hide all <main> sections
    document.querySelectorAll('main').forEach(main => main.classList.add('hidden'));

    // Step 4: Show matching <main> by ID from href
    const targetId = this.getAttribute('href').replace('#', '');
    const target_text = this.textContent;
 
    const targetMain = document.getElementById(targetId);
    if (targetMain) {
      document.getElementById("name_side_bar").textContent = target_text;
      targetMain.classList.remove('hidden');
    }
  });
});

});
  
</script>


<script>
const form = document.forms["createPitchForm"];
const openTime = document.getElementById("opening_time");
const openingTime = form.elements["opening_time"];

const closeTime = document.getElementById("closing_time");
const closingTime = form.elements["closing_time"];

const weekOpenTime = document.getElementById("weekend_open_time");
const weekopeningTime = form.elements["weekend_open_time"];

const weekCloseTime = document.getElementById("weekend_close_time");
const weekclosingTime = form.elements["weekend_close_time"];

const weekEndPeakPrice = document.getElementById("weekend_Peak_rate");
const weekEndPeakRate = form.elements["weekend_Peak_rate"];

const weekEndOffPeakPrice = document.getElementById("weekend_offPeak_rate");
const weekEndOffPeakRate = form.elements["weekend_offPeak_rate"];

const holidaypeakprice = document.getElementById("holiday_peak_rate");
const holidaypeakrate = form.elements["holiday_peak_rate"];

const holidayOffpeakprice = document.getElementById("holiday_offpeak_rate");
const holidayOffpeakrate = form.elements["holiday_offpeak_rate"];

 // Adjust ID as needed
function toggle24open(checkbox) {
    const section = document.getElementById("openTimeFields");
    if (checkbox.checked) {
    section.classList.add("hidden");
    closeTime.required = false;
    openTime.required = false;
    openingTime.value = "00:00";
    closingTime.value = "23:00";
    weekopeningTime.value = "00:00";
    weekclosingTime.value = "23:00";
  } else {
    section.classList.remove("hidden");
    openTime.required = true;
    closeTime.required = true;
    openingTime.value = "";
    closingTime.value = "";
    weekopeningTime.value = "";
    weekclosingTime.value = "";
  }
  }

function setWeekopenTime(value) {
  const opentimeValue = value;

  // Make sure the input field has name="weekOpenTime"
  form.elements["weekend_open_time"].value = opentimeValue;
  //form.weekOpenTime.value = opentimeValue;
  console.log("Previous Weekend Open Time:", form.weekend_open_time.value);
  
}

function setWeekcloseTime(value) {
  const closetimeValue = value;

  // Make sure the input field has name="weekOpenTime"
  form.elements["weekend_close_time"].value = closetimeValue;
  //form.weekOpenTime.value = opentimeValue;
  console.log("Previous Weekend Open Time:", form.weekend_close_time.value);
  
}

function setWeekPeakrate(value) {
  const rateValue = value;

  // Make sure the input field has name="weekOpenTime"
  form.elements["weekend_peak_rate"].value = rateValue;
  form.elements["holiday_peak_rate"].value = rateValue;
  //form.weekOpenTime.value = opentimeValue;
   
}

function setWeekOffPeakrate(value) {
  const rateValue = value;

  // Make sure the input field has name="weekOpenTime"
  form.elements["weekend_offPeak_rate"].value = rateValue;
  form.elements["holiday_offpeak_rate"].value = rateValue;
  
  //form.weekOpenTime.value = opentimeValue;
  
}

function toggleWeekendTime(checkbox) {
    const section = document.getElementById("weekendTimeFields");
    if (checkbox.checked) {
      section.classList.remove("hidden");
      weekCloseTime.required = true;
      weekOpenTime.required = true;
      weekopeningTime.value = "";
      weekclosingTime.value = "";
      
    }
    else {
      section.classList.add("hidden");
      weekCloseTime.required = false;
      weekOpenTime.required = false;
      weekopeningTime.value = "";
      weekclosingTime.value = "";
    }

    section.classList.toggle("hidden", !checkbox.checked);
  }

function toggleWeekendPrice(checkbox) {
  const section = document.getElementById("weekendpriceFields");
  //section.classList.toggle("hidden", !checkbox.checked);
  if (checkbox.checked) {
    section.classList.remove("hidden");
    weekEndPeakPrice.required = true;
    weekEndOffPeakPrice.required = true;
    weekEndPeakRate.value = "";
    weekEndOffPeakRate.value = "";
    
  }
  else {
    section.classList.add("hidden");
    weekEndPeakPrice.required = false;
    weekEndOffPeakPrice.required = false;
  }
}

function toggleHolidayPrice(checkbox) {
  const section = document.getElementById("holidaypriceFields");
  //section.classList.toggle("hidden", !checkbox.checked);
  if (checkbox.checked) {
    section.classList.remove("hidden");
    holidaypeakPrice.required = true;
    holidayOffpeakPrice.required = true;
    holidaypeakrate.value = "";
    holidayOffpeakrate.value = "";
    
  }
  else {
    section.classList.add("hidden");
    holidaypeakPrice.required = false;
    holidayOffpeakPrice.required = false;
  }
}


</script>

<script>

const form_pitch = document.getElementById('createPitchForm');
//const form = document.forms["createPitchForm"];
form_pitch.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submit
const select = document.getElementById("court_id");

// Append selected text separately
 // You can use any key name
        const formData = new FormData(form_pitch);
		formData.append('user_id', <?= $client_id ?>); 
    if (select) {
        const selectedText = select.options[select.selectedIndex].text;
        formData.append('game_type', selectedText);
    } else {
        console.error("Select element not found.");
    }

    const formObject = Object.fromEntries(formData.entries());
console.log(formObject);
    alert("Creating Ground");
    //console.log(formData);
        fetch('/futsal_db.php?action=create_pitch', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            alert(response); // Show success message
            form_pitch.reset(); // Clear form
            var modal = bootstrap.Modal.getInstance(document.getElementById('_createPitchModal'));
            modal.hide(); // Close modal
            location.reload(); // Optional: reload page to show updates
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Something went wrong. Try again.");
        });
    });




</script>



<!-- <script>
document.getElementById("logout_btn").addEventListener("click", function(){
        window.location.href = './logout.php';
    });

</script> -->

<script>
    const editBtn = document.getElementById('editBtn_pitch');
    const createBtn = document.getElementById('createBtn_pitch');
    const pitchModel = document.getElementById("_createPitchModal");
    const _newGameType = document.getElementById('newGameType');
    const previewAndCrop = document.getElementById('previewAndCrop');
 createBtn.addEventListener("click", function(){
        pitchModel.style.display = "flex";
        pitchModel.classList.add('d-flex');

    });
    function closeModal(){

        pitchModel.style.display = 'none';
        pitchModel.classList.remove('d-flex');
        _newGameType.classList.remove('d-flex');
        _newGameType.style.display = 'none';
  
    }


    function checkNewGroup(value) {
        
  if (value === '__New__') {
     document.getElementById('newGameType').classList.remove('hidden');
  document.getElementById('new_game_type').focus();
}
/*     _newGameType.classList.remove('fade');
    _newGameType.style.display = "flex";
    _newGameType.classList.add('d-flex');
    _newGameType.classList.remove('hidden');  */
  
else{
  document.getElementById('newGameType').classList.remove('d-flex');
    document.getElementById("newGameType").style.display = "none";
  document.getElementById('newGameType').classList.add('fade');
}
    }
    

    function submitNewGameType() {
  const gameName = document.getElementById("new_game_type").value.trim();
console.log(gameName);
  const formData = new FormData();
  formData.append("new_game_type", gameName);
  formData.append("stadium_id",<?= $client_id ?> );
console.log(formData);
  fetch("/futsal_db.php?action=insert_gameType", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'Exist'){
 document.getElementById("gameType").value=cityValue;

document.getElementById("gameType").dispatchEvent(new Event('change'));

    }
    
    else if (data.status === 'Success') {
      const selectElement = document.getElementById("gameType");
        const optgroups = selectElement.getElementsByTagName('optgroup');
        const newOption = document.createElement("option");
          newOption.value = cityValue;
          newOption.textContent = cityValue;
          newOption.selected = true; 
     
      
        
        document.getElementById("new_game_type").value="";
      // Optionally append the new city to your city dropdown here
    } else {
      alert("Error: " + data.message);
    }
  });
  document.getElementById('newGameType').classList.add('fade');
    document.getElementById("newGameType").style.display = "none";
        document.getElementById('newGameType').classList.remove('d-flex');
}

</script>

<!-- 	  <link href="./css/output.css" rel="stylesheet">
	<!-/- Updated JS -/->
	<link rel="stylesheet" href="/Asset/css/style_book.css"> -->
		<!-- Optional CSS -->
<!-- In <head> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

<!-- Before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="/Asset/js/script.js"></script> -->


   
