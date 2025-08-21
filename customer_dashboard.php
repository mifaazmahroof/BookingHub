<?php
$cus_id = $_SESSION['user_id'];

// Fetch booking data
$totalBookings = getBookingCount($cus_id);
$bookingData = getBookingBreakdown($cus_id);
$cus_details = getCustomerDetails($cus_id);

?>
<!-- <div class="dashboard-grid"> -->
<div id="loader">
  <div class="spinner"></div>
</div>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - FutsalSL</title>
           <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
     <link href="./css/output.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Font Awesome CDN (v5 or v6, depending on your version) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
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
    </style> -->
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
<!--         <div class="flex-1 overflow-y-hidden inset-0 bg-black/50 z-40 md:hidden hidden" id="mobileOverlay">
            </div> -->

                <!-- Sidebar - Desktop -->
        <aside class="hidden md:flex flex-col h-screen w-64 bg-white shadow-md z-50">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="/" class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                        <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span></a>
                </div>
                <div class="text-sm text-gray-600 ml-3.5">User Dashboard</div>
            </div>
            <nav class="flex-1 overflow-y-auto md:p-1 p-2 no-scrollbar">
              <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Management</div>
                    <a href="#dashboard_main" class="sidebar-link active flex items-center py-3 px-4 rounded-lg mb-2 transition-all text-gray-700 hover:bg-gray-100 no-underline">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
<!--                     <a href="#ground_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-futbol w-5 mr-3"></i>
                        <span>My Grounds</span>
                    </a> -->
                    <a href="#booking_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span>Bookings</span>
                    </a>
<!--                     <a href="#gallery_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-image w-5 mr-3"></i>
                        <span>Gallery</span>
                    </a> -->
                </div>
<!--                 <div class="mb-4 bg-white rounded-xl shadow p-3">
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
                </div> -->
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
        <aside class="mobile-menu flex flex-col h-screen w-64 bg-white shadow-md z-50 md:hidden hidden" id="mobileMenu">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="/" class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                        <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span></a>
                </div>
                <div class="text-sm text-gray-600 ml-3.5">User Dashboard</div>
            </div>

            <nav class="flex-1 overflow-y-auto no-scrollbar md:p-1 p-2 ">
             <div class="mb-4 bg-white rounded-xl shadow p-3">
                    <div class="text-xs uppercase font-semibold text-gray-500 tracking-wide mb-2">Management</div>
                    <a href="#dashboard_main" class="sidebar-link active flex items-center py-3 px-4 rounded-lg mb-2 transition-all text-gray-700 hover:bg-gray-100 no-underline">
                        <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
<!--                     <a href="#grounds" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-futbol w-5 mr-3"></i>
                        <span>My Grounds</span>
                    </a> -->
                    <a href="#bookings_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-calendar-alt w-5 mr-3"></i>
                        <span>Bookings</span>
                    </a>
<!--                     <a href="#gallery_main" class="sidebar-link flex items-center py-3 px-4 rounded-lg mb-2 text-gray-700 hover:bg-gray-100 transition-all no-underline">
                        <i class="fas fa-image w-5 mr-3"></i>
                        <span>Gallery</span>
                    </a> -->
                </div>
                <!-- <div class="mb-4 bg-white rounded-xl shadow p-3">
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
                </div> -->
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
        
<div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm z-10">
                
                <div class="flex items-center justify-between p-4">
                    <button id="menuToggle" class="md:hidden p-4 focus:outline-none">
    <span id="menuIcon" class="fas fa-bars text-2xl text-teal-700"></span>
</button>
                    <div class="flex-1 items-center">
                        
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
                                <div class="text-sm font-medium text-gray-800"><?= htmlspecialchars($cus_details['full_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($cus_details['email']) ?></div>
                            </div>
<?php
if($cus_details['image_path']){
$image = $cus_details['image_path'];
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
            <div x-show="open" @click.away="open = false" class="absolute flex flex-col z-10 mt-2 w-full rounded bg-white shadow flex flex-col">
        <a href="#profile_main"  @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-user"></i><span class="hidden sm:block"> My profile</span>
        </a>
        <a href="#settings_main" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-gear"></i><span class="hidden sm:block"> Settings</span>
        </a>
        <a href="./logout.php" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline">
          <i class="fa-solid fa-sign-out-alt"></i><span class="hidden sm:block"> Sign out</span>
        </a>
      </div>

<!--       <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-full rounded bg-white shadow">
        <a href="#profile_main"  @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-user"></i> My profile
        </a>
        <a href="#settings_main" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline" onclick="directpage(event, this.getAttribute('href'))">
          <i class="fa-solid fa-gear"></i> Settings
        </a>
        <a href="./logout.php" @click="open = false" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-teal-100 dark:hover:bg-teal-700 hover:text-white text-gray-700 no-underline">
          <i class="fa-solid fa-sign-out-alt"></i> Sign out
        </a>
      </div> -->
    </div>
  </div>
                    </div>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar" id="dashboard_main">
                <!-- Welcome Message -->
                <div class="bg-white rounded-xl shadow mb-6 p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <h2 class="text-xl font-bold text-gray-800">Welcome back, <?= htmlspecialchars($cus_details['full_name']) ?>!</h2>
                        <!-- <button class="btn-primary text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all whitespace-nowrap" id="createBtn_pitch">
                            <i class="fas fa-plus mr-2"></i> Add New Ground
                        </button> -->
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
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><!-- <?= htmlspecialchars($data['total_bookings']) ?> --></span>
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
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2">Rs. <!-- <?= htmlspecialchars($data['total_Revenue']) ?> --></span>
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
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><!-- <?= htmlspecialchars($data['upcoming']) ?> --></span>
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
                            <span class="text-2xl md:text-3xl font-bold text-gray-800 mr-2"><!-- <?= htmlspecialchars($client_details['average_rating']) ?> --></span>
                            <!-- <span class="text-green-500 text-sm flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i> 0.2
                            </span> -->
                        </div>
                        <div class="text-xs text-gray-500 mt-2">Based on <!-- <?= htmlspecialchars($client_details['review_count']) ?> --> reviews</div>
                    </div>
                </div>
                </main>
                <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar hidden" id="booking_main">
<div>
    <table id="bookingTable">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Pitch Name</th>
                    <th>Booked Date</th>
                    <th>Slot Time</th>
                    <th>Slot Booked Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
</div>


                    </main>
                    <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar hidden" id="profile_main">
                        <div class="tab-content active" id="tab3">
    <form id="userForm" action="" method="post" enctype="multipart/form-data" class="mb-5">
        <div class="row">
            <!-- Left Column: Profile Image -->
            <div class="col-md-5 text-center mb-4" id="cur_dp_img">
                <img src="<?= htmlspecialchars($cus_details['image_path']) ?>" class="img-thumbnail" style="max-height: 250px;">
                <div class="mt-3" id="new_dp_img" style="display: none;">
                    <label for="imageInput" class="form-label">Select DP</label>
                    <input type="file" name="dp_image" id="imageInput" class="form-control">
                </div>
            </div>

            <!-- Right Column: User Details -->
            <div class="col-md-7">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $cus_details['full_name'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?= $cus_details['email'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" class="form-control" name="phone" value="<?= $cus_details['phone_number'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <input type="text" class="form-control" name="address" value="<?= $cus_details['address'] ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="NIC" class="form-label">NIC:</label>
                    <input type="text" class="form-control" name="NIC" value="<?= $cus_details['NIC'] ?>" readonly>
                </div>
                <div class="mb-3" style="display: none;" id="new_dp_img">
            <label for="imageInput" class="form-label">Select DP</label>
            <input type="file" name="dp_image" id="imageInput" class="form-control">
        </div>
            </div>
        </div>

        <!-- Buttons Row: Spanning Full Width -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <button type="button" id="editBtn" class="btn btn-primary">Edit</button>
                <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Save</button>
                <button type="button" id="cancelBtn" class="btn btn-secondary" style="display:none;">Cancel</button>
            </div>
        </div>
    </form>
</div>

                    </main>
                    <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar hidden" id="settings_main">
                    </main>
                    <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar hidden" id="help_main">
                    </main>


</div>


    
    </div>
   
<script>
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
const menuIcon = document.getElementById('menuIcon');
    function toggleMobileMenu() {
        mobileMenu.classList.toggle('hidden');
        
    if (mobileMenu.classList.contains('hidden')) {
        menuIcon.classList.remove('fa-times');
        menuIcon.classList.add('fa-bars');
    } else {        
        menuIcon.classList.remove('fa-bars');
        menuIcon.classList.add('fa-times');
    }
    }

    menuToggle.addEventListener('click', toggleMobileMenu);
</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/bookingPieChart.js"></script>
    
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
        	window.addEventListener("load", function () {
  const loader = document.getElementById("loader");
  
  
  
  
/* 	const logout_btn = document.getElementById("logout_btn");
    logout_btn.addEventListener("click", function(){
        window.location.href = './logout.php';
    }); */
  loader.style.opacity = '0';
  loader.style.transition = 'opacity 0.5s ease';
  setTimeout(() => {
    loader.style.display = 'none';
  }, 500);
});

const form = document.getElementById('userForm');
const editBtn = document.getElementById('editBtn');
const saveBtn = document.getElementById('saveBtn');
const cancelBtn = document.getElementById('cancelBtn');

// Backup original values
let originalValues = {};

editBtn.addEventListener('click', () => {
  const inputs = form.querySelectorAll('input');
  inputs.forEach(input => {
    originalValues[input.name] = input.value;
    input.removeAttribute('readonly');
  });
  document.getElementById('cur_dp_img').style.display = 'none';
  document.getElementById('new_dp_img').style.display = 'block';
  editBtn.style.display = 'none';
  saveBtn.style.display = 'inline-block';
  cancelBtn.style.display = 'inline-block';
});

cancelBtn.addEventListener('click', () => {
  const inputs = form.querySelectorAll('input');
  inputs.forEach(input => {
    input.value = originalValues[input.name];
    input.setAttribute('readonly', true);
  });

  document.getElementById('cur_dp_img').style.display = 'block';
  document.getElementById('new_dp_img').style.display = 'none';
  editBtn.style.display = 'inline-block';
  saveBtn.style.display = 'none';
  cancelBtn.style.display = 'none';
});

saveBtn.addEventListener('click', () => {
  const formData = new FormData(form);
  formData.append('user_id', <?= $cus_id ?>); 
  console.log(formData);
  fetch('/futsal_db.php?action=update_user', {
    method: 'POST',
    body: formData
  }).then(response => response.text())
    .then(data => {
      console.log(data);
      const inputs = form.querySelectorAll('input');
      inputs.forEach(input => input.setAttribute('readonly', true));

      document.getElementById('cur_dp_img').style.display = 'block';
  document.getElementById('new_dp_img').style.display = 'none';
      editBtn.style.display = 'inline-block';
      saveBtn.style.display = 'none';
      cancelBtn.style.display = 'none';
      location.reload();
    });
});






 const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', () => {
            // Remove active classes
            tabLinks.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(tab => tab.classList.remove('active'));

            // Add active classes
            link.classList.add('active');
            const target = document.getElementById(link.dataset.tab);
            target.classList.add('active');
        });
    });



        const bookings = <?= json_encode($totalBookings) ?>;
        const labels = <?= json_encode($bookingData['labels']) ?>;
        const data = <?= json_encode($bookingData['data']) ?>;
        const status = <?= json_encode($bookingData['status']) ?>;

        // Render booking table with color for pending rows
        function renderBookingTable(bookings) {
            const tbody = document.querySelector('#bookingTable tbody');
            tbody.innerHTML = '';

            bookings.forEach(b => {
                const row = document.createElement('tr');
                if (b.status.toLowerCase() === 'pending') {
                    row.classList.add('pending');
                }
                else if(b.status.toLowerCase() === 'payment awaited') {
                    row.classList.add('awaited');
                }

                row.innerHTML = `
                    <td>${b.booking_id}</td>
                    <td>${b.pitch_name}</td>
                    <td>${b.created_at}</td>
                    <td>${b.timeslot}</td>
                    <td>${b.booking_date}</td>
                    <td>${b.rate_applied}</td>
                    <td>${b.status}</td>
                `;
                tbody.appendChild(row);
            });
        }

        renderBookingTable(bookings);
        renderBookingPieChart('bookingPie', labels, data, status);
    </script>
<script>
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


    <!--     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
		

<!-- <link rel="stylesheet" href="css/style_book.css"> --> <!-- Optional CSS -->

