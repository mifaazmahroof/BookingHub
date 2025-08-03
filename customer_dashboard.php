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
    <title>Vendor Dashboard - FutsalSL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile Sidebar Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden" id="mobileOverlay"></div>
        
        <!-- Sidebar - Desktop -->
        <aside class="bg-white w-64 shadow-md flex-shrink-0 hidden md:block">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <a href="./logout.php" class="no-underline text-teal-700 hover:text-teal-900 flex items-center">
                        <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span></a>
                </div>
                <div class="text-sm text-gray-600">Vendor Dashboard</div>
            </div>
            
        </aside>
        
        <!-- Sidebar - Mobile -->
        <aside class="mobile-menu fixed inset-y-0 left-0 w-64 bg-white shadow-md z-50 md:hidden" id="mobileMenu">
            <div class="p-4 border-b border-gray-200">
                <div class="text-2xl font-bold text-teal-600 flex items-center">
                    <i class="fas fa-futbol mr-2"></i>
                    <span>FutsalSL</span>
                </div>
                <div class="text-sm text-gray-600">Vendor Dashboard</div>
            </div>
            
        </aside>
		
<div class="tab-container">
<div class="dashboard-header">
    <h2>Hi <?= htmlspecialchars($cus_details['full_name']) ?>,</h2>
    <p>This is your page</p>
</div>
    <div class="tab-buttons">
        <button class="tab-link active" data-tab="tab3">User Details</button>
        <button class="tab-link" data-tab="tab1">Dashboard</button>
        <button class="tab-link" data-tab="tab2">Booking Table</button>
        
    </div>
    <div class="tab-content" id="tab1">
    <canvas id="bookingPie" width="300" height="300"></canvas>
    <!-- Column 1: Pie Chart -->
    <!-- <div class="dashboard-column">
        <canvas id="bookingPie" width="300" height="300"></canvas>
    </div> -->
</div>
<div class="tab-content" id="tab2">
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
    <div class="tab-content active" id="tab3">
        <br>
    <div class="row" style="display:block;" id="cur_dp_img">
        <div class="col-md-2 mb-4 text-center">
            <img src="<?= htmlspecialchars($cus_details['image_path']) ?>" class="img-thumbnail" style="max-height:150px;">
        </div>
    </div>
    <form id="userForm" action="" method="post" enctype="multipart/form-data" class="mb-5">

    
  <label>Full Name:</label>
  <span><input type="text" name="fullname" value="<?= $cus_details['full_name'] ?>" readonly></span><br>

  <label>Email:</label>
  <input type="email" name="email" value="<?= $cus_details['email'] ?>" readonly><br>

  <label>Phone:</label>
  <input type="text" name="phone" value="<?= $cus_details['phone_number'] ?>" readonly><br>

  <label>Address:</label>
  <input type="text" name="address" value="<?= $cus_details['address'] ?>" readonly><br>
  <label>NIC:</label>
  <input type="text" name="NIC" value="<?= $cus_details['NIC'] ?>" readonly><br>
  
<div class="mb-3" style="display: none;" id="new_dp_img">
            <label for="imageInput" class="form-label">Select DP</label>
            <input type="file" name="dp_image" id="imageInput" class="form-control">
        </div>
  <button type="button" id="editBtn" class="btn">Edit</button>
  <button type="button" id="saveBtn" class="btn" style="display:none;">Save</button>
  <button type="button" id="cancelBtn" class="btn" style="display:none;">Cancel</button>
</form>
    <!-- Column 3: Empty (for future use) -->
   <!--  <div class="dashboard-column"></div> -->
</div>
</div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/bookingPieChart.js"></script>
    <script>
        	window.addEventListener("load", function () {
  const loader = document.getElementById("loader");
  
  
  
  
	const logout_btn = document.getElementById("logout_btn");
    logout_btn.addEventListener("click", function(){
        window.location.href = './logout.php';
    });
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		

<link rel="stylesheet" href="css/style_book.css"> <!-- Optional CSS -->

