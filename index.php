<?php

include 'header.php';  //header
$getalldetails = getAllCourtDetails();
$getReviews = getReviews();

$role = $_SESSION['role'] ?? null;
?>
<section class="hero-pattern text-black py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6 leading-tight">Find and Book the Perfect Futsal Ground</h1>
                <p class="text-xl mb-10 opacity-90">Discover the best futsal venues across Sri Lanka and book with just a few clicks</p>
                
                <!-- Search Bar -->
                <div class="bg-white p-6 rounded-xl shadow-2xl">
                    <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Sport Type</label>
                            <div class="relative">
                                <select id="courtsList" class="form-control">
                            <option value="">-- Select Sport --</option>
                            
                            </select>
                                <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down"></i>
                                </div> -->
                            </div>
                        </div>
						<div class="flex-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Location</label>
                            <div class="relative">
                                <select id="locationsList" class="form-control" disabled>
                            <option value="">--Select Location--</option>
                                </select>
                                <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down"></i>
                                </div> -->
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2 text-left">Date</label>
                            <div class="relative">
                                <input type="date" class="form-control" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" disabled>
                                <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <!-/- <i class="fas fa-calendar-day"></i> -/->
                                </div> -->
                            </div>
                        </div>
                        
                        
						
                    </div>
					
                    <div class="border-t border-gray-200 pt-4 mb-6" id="cost_lst" style="display: none;">
                        <p id="ttcost" class="fw-bold text-primary pt-3 text-[1.5rem]"></p>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Price</span>
                            <span class="font-medium" id="futsal_cost">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium" id="tt_hours">0 hour</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-red-600">Discount</span>
                            <span class="font-medium text-red-600" id="discounts">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Service fee</span>
                            <span class="font-medium" id="service_fee">Rs. 0.00</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-3">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-xl font-bold text-teal-600" id="total_amount">Rs. 0.00</span>
                        </div>
                        <div class="submit-form">
                    </div>
                    </div>
                    
                    <div id="futsalsList" class="scrollable-div" style="display: none;">
                        
<table id="compareTable">
  <thead></thead>
  <tbody id="tableBody"></tbody>
</table>

                    </div>
                          <!-- Hidden Inputs -->
                    <input type="hidden" id="startTime" name="start_time" readonly>
                    <input type="hidden" id="hours" name="hours" min="1" max="8" placeholder="Enter hours">
                    <input type="hidden" id="endTime" name="end_time" readonly>
                    <input type="hidden" id="fut_id" name="futsal_id" readonly>
                    <input type="hidden" id="selectedDateInput" name="selected_date">
                    <input type="hidden" id="costOutput" name="costOutput">
                </div>
			
            </div>
<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="payment_model" style="display:none;">
<!-- Modal Backdrop -->
<div class="fixed inset-0 bg-black opacity-50 flex items-center justify-center z-50">
  <!-- Modal Content -->
  <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
    <h2 class="text-xl font-semibold mb-4 text-center">Select Payment Method</h2>

    <div class="space-y-4" id="paymentMethod">
      <!-- Card Payment -->
      <button id="card_payment" class="w-full bg-blue-600 text-white py-2 px-4 rounded-xl hover:bg-blue-700 transition duration-300" type="button">
        Card Payment
      </button>

      <!-- Bank Transfer -->
      <button id="bank_transfer" class="w-full bg-green-600 text-white py-2 px-4 rounded-xl hover:bg-green-700 transition duration-300" type="button">
        Bank Transfer
      </button>

      <!-- Pay Later -->
      <button id="pay_later" class="w-full bg-yellow-500 text-white py-2 px-4 rounded-xl hover:bg-yellow-600 transition duration-300" type="button">
        Pay Later
      </button>
    </div>
    
    <div class="space-y-4" id="bankDetails" style="display: none;"> </div>

    <!-- Cancel Button -->
    <button type="button"
      onclick="closeModal('payment_model')"
      class="mt-6 w-full text-gray-600 hover:text-gray-800"
    >
      Cancel
    </button>
  </div>
</div>
</div>

			<div id="user_pg" class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
            <div class="bg-white p-6 rounded-md shadow-md w-96 relative">
                <div class="modal-content p-4">
                    <button id="close_user" class="absolute top-2 right-2 text-gray-600 hover:text-black" onclick="closeModal('user_pg')" type="button">&times;</button>
                
                <h2 class="text-lg font-semibold mb-4">User Details Page</h2>

                <div class="flex items-center border rounded w-full mb-3"><input type="text" id="phone" maxlength="10" placeholder="Enter 10-digit phone number" class="w-full mb-1 px-3 py-2 bg-transparent focus:outline-none" reqiured>
        </div>
                
                <button type="button" id="editPhone" class="btn btn-warning mb-2" style="display: none;">Edit</button>
                <div id="userDetails"></div>
                <p id="cusId" style="display: none;"></p>
                <div class="d-flex gap-2">
                    <button type="button" id="back_p2" class="btn btn-secondary" style="display: none;">Back</button>
                    <button type="button" id="confirm_p2" class="btn btn-primary" style="display: none;">Confirm</button>
                </div>
                </div>
            </div>
            </div>

            <!-- Modal for Card Payment -->
            <div id="payment_pg" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                <span class="btn-close" onclick="closeModal('payment_pg')"></span>
                <h2 class="text-lg font-semibold mb-4">Card Payment</h2>
                <form class="payment-form" id="payment-form">
                    <div class="mb-3">
                    <label for="cardName" class="form-label">Name on Card</label>
                    <input type="text" id="cardName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                    <label for="cardNumber" class="form-label">Card Number</label>
                    <input type="text" id="cardNumber" class="form-control" required placeholder="XXXX-XXXX-XXXX-XXXX" maxlength="19" oninput="detectCardType(this)">
                    <div class="form-text" id="cardTypeDisplay"></div>
                    </div>
                    <div class="mb-3">
                    <label for="expDate" class="form-label">Expiry Date</label>
                    <input type="text" id="expDate" class="form-control" placeholder="MM/YY" required maxlength="5" oninput="formatExpiryDate(this)">
                    <div class="text-danger" id="expDateError"></div>
                    </div>
                    <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" id="cvv" class="form-control" required maxlength="4" pattern="\d{3,4}">
                    </div>
                    <div class="mb-3">
                    <label for="Amount" class="form-label">Amount</label>
                    <input type="text" id="totAmount" class="form-control" required readonly>
                    </div>
                    <div class="d-grid gap-2">
                    <button type="button" id="btnPay" class="btn btn-success">Pay Now</button>
                    <button type="button" id="btnPayLater" class="btn btn-secondary">Pay Later</button>
                    <button type="button" id="btnPayCancel" class="btn btn-danger">Cancel Booking</button>
                    </div>
                    <div class="alert alert-success mt-3 d-none" id="successMsg">Payment Successful! (Simulation)</div>
                </form>
                </div>
            </div>
            </div>
            <!-- Login Popup -->
<div id="login_popup" class="hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
  <div class="bg-white p-6 rounded-md shadow-md w-96 relative">
    <!-- Close button -->
    <button id="close_login" class="absolute top-2 right-2 text-gray-600 hover:text-black" onclick="closeModal('login_popup')" type="button">&times;</button>
    
    <!-- Your login form or content -->
    <h2 class="text-lg font-semibold mb-4">Login</h2>
    <form method="POST" id="loginPopUp">
      <div class="flex items-center border rounded w-full mb-3"><input type="text" placeholder="Username" class="w-full mb-1 px-3 py-2 bg-transparent focus:outline-none" id="login_username" required>
        </div>
        <div class="flex items-center border rounded w-full mb-3">
  <input type="password" id="login_password" name="password" placeholder="Password" autocomplete="off" required class="w-full mb-1 px-3 py-2 bg-transparent focus:outline-none"
  />
  <span onclick="togglePasswordVisibilityLogin()" class="ml-2 px-2 text-gray-600 cursor-pointer">
    <i class="fa fa-eye-slash" id="toggleIcon" aria-hidden="true"></i>
  </span>
</div>


      <!-- <div class="input-group">
<input type="password" placeholder="Password" class="w-full mb-3 px-3 py-2 border rounded" id="login_password" required>     
  <span class="input-group-text cursor-pointer" id="eye_pass" onclick="togglePasswordVisibilityLogin()" style="cursor: pointer;">
      <i class="fa fa-eye-slash" id="toggleIcon" aria-hidden="true"></i>
    </span>
</div> -->
       
      <button type="submit" class="btn btn-primary w-full" id="login_submit">Submit</button>
      <button type="button" class="btn btn-primary w-full mt-2" id="register_btn">Signup</button>
    </form>
  </div>
</div>
<!-- <div id="Register_popup" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black overflow-y-auto hidden"> -->
   <div id="Register_popup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-y-auto hidden">
  <div class="relative w-full max-w-md m-4 rounded-md bg-white p-4 shadow-md overflow-y-auto max-h-screen">
    <!-- Close button -->
    <button id="close_login" class="absolute top-2 right-2 text-gray-600 hover:text-black" onclick="closeModal('Register_popup')" type="button">&times;</button>

    <!-- Registration Form -->
    <h2 class="mb-4 text-lg font-semibold">User Registration</h2>
    <form id="register_form" method="POST">
      <!-- Name -->
      <label class="block text-sm font-medium text-gray-700">Full Name:</label>
      <div class="flex gap-2 mb-2">
        <input type="text" placeholder="First Name" class="w-1/2 rounded border px-3 py-2" id="reg_fname" required />
        
        <input type="text" placeholder="Last Name" class="w-1/2 rounded border px-3 py-2" id="reg_lname" required />
      </div>

      <!-- Address -->
     <label class="block text-sm font-medium text-gray-700">Address:</label>

<!-- Door No. and Street -->
<div class="flex gap-2 mb-2">
  <input type="text" placeholder="No" class="w-1/3 rounded border px-3 py-2" id="reg_doornu" required />
  <input type="text" placeholder="Street" class="w-2/3 rounded border px-3 py-2" id="reg_street" required />
</div>

<!-- City, District, Province -->
<div class="flex gap-2 mb-3 relative">
  <!-- City -->
  <input type="text" id="reg_city" name="city" placeholder="City" autocomplete="off"
    class="w-full rounded border px-3 py-2" required>

  <!-- Suggestions dropdown -->
  <div id="citySuggestions"
       class="absolute top-full left-0 z-10 w-1/2 bg-white border rounded mt-1 hidden max-h-40 overflow-y-auto shadow-md">
  </div>
</div>

<div class="flex gap-2 mb-3">
  <!-- District -->
  <select class="w-full rounded border px-3 py-2" id="reg_district" required>
    <option value="">District</option>
    <?php foreach ($distinctDistricts as $district): ?>
      <option value="<?= htmlspecialchars($district); ?>"><?= htmlspecialchars($district); ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="flex gap-2 mb-3">
  <!-- Province -->
  <select class="w-full rounded border px-3 py-2" id="reg_province" required>
    <option value="">Province</option>
    <?php foreach ($distinctProvinces as $province): ?>
      <option value="<?= htmlspecialchars($province); ?>"><?= htmlspecialchars($province); ?></option>
    <?php endforeach; ?>
  </select>
</div>



      <!-- Phone Number -->
      <label for="reg_phone" class="block text-sm font-medium text-gray-700">Phone Number: <span class="text-xs text-red-500">[This will be used as your username.]</span></label>
      <input type="text" placeholder="Phone Number" class="mb-2 w-full rounded border px-3 py-2" id="reg_phone" maxlength="10" pattern="\d{10}" title="Enter a 10-digit phone number" required  />

      <!-- Password -->
     <label for="reg_pass" class="block text-sm font-medium text-gray-700">Password:</label>
<div class="relative">
  <input type="password" placeholder="Password" class="mb-2 w-full rounded border px-3 py-2 pr-10" id="reg_pass" required>
  <div class="absolute inset-y-0 right-0 flex items-center px-3 cursor-pointer text-gray-700" onclick="togglePasswordVisibility()">
    <i class="fa fa-eye-slash" id="toggleIcon" aria-hidden="true"></i>
  </div>
</div>

      <!-- NIC -->
      <label class="block text-sm font-medium text-gray-700">NIC:</label>
      <input type="text" placeholder="NIC" class="mb-2 w-full rounded border px-3 py-2" id="reg_nic" required maxlength="12" minlength="10"/>

      <!-- Email -->
      <label class="block text-sm font-medium text-gray-700">Email:</label>
      <input type="email" placeholder="Email" class="mb-4 w-full rounded border px-3 py-2" id="reg_email" required />

      <!-- Submit -->
      <div class="flex justify-center">
        <button type="submit" class="w-2/5 px-3 py-2 rounded-2xl bg-teal-300 hover:bg-teal-700 hover:text-white disabled:bg-gray-500 disabled:text-black" id="reg_submit">Submit</button>
      </div>
    </form>
  </div>
</div>


        </div>
    </section>

    <!-- Featured Grounds -->
     <?php if (!empty($getalldetails) && is_array($getalldetails)): ?>
    <section class="py-16 bg-white">
       
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Top-Rated Futsal Grounds</h2>
                <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Discover the most popular futsal venues based on user ratings and bookings</p>
            </div>
            <div class="overflow-x-auto no-scrollbar">
            <div class="flex gap-8 min-w-max">
               
    <?php foreach($getalldetails as $row): ?>
        
<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 ground-card transition-all duration-300">
                    <div class="relative">
                        <?php

if($row['pitch_image']){
$image = $row['pitch_image'];
}
else{
    $image = 'images/futsal1.jpg';
}
$imageUrl = strpos($image, '?') !== false 
    ? $image . '&auto=format&fit=crop&w=80&q=80' 
    : $image . '?auto=format&fit=crop&w=80&q=80';
?>
                        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Futsal Ground" class="w-full h-64 object-cover md:object-top ">
                        <div class="absolute top-4 right-4 price-tag text-white px-4 py-1 rounded-full text-sm font-bold">
                            Rs: <?= htmlspecialchars($row['Peak_rate']) ?>/= </div> <div class="absolute top-4 left-4 price-tag text-white px-4 py-1 rounded-full text-sm font-bold">Rs: <?= htmlspecialchars($row['Offpeak_rate']) ?>/=
                        </div>
                        <div class="absolute bottom-4 left-4 bg-white text-teal-600 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                            <i class="fas fa-star mr-1"></i> 
                            <?php 
                            if ($row['average_rating']){
                                echo $row['average_rating']; 
                            }
                            else{
                                echo '0';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-3 flex-col sm:flex-row sm:items-center sm:gap-4">
     <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($row['Stadium_name']) ?><br>
        <span class="text-sm font-bold text-gray-800 mt-1">[<?= htmlspecialchars($row['Court_Name']) ?>]</span></h3>
   
    <div class="flex items-center text-gray-500 mt-2 sm:mt-0">
        <i class="fas fa-map-marker-alt mr-1 text-teal-600"></i>
        <span class="text-sm">2.5 km</span>
    </div>
</div>
                     
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-teal-600"></i>
                            <span class="text-sm"><?= htmlspecialchars($row['Stadium_address']) ?></span>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-5">
                            <?php
                            if ($row['tagline']){
$courts = explode(',', $row['tagline']);
foreach ($courts as $tag) {
    echo '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium mr-1">'
        . htmlspecialchars(trim($tag)) .
        '</span>';
}}
else{
    echo '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-medium mr-1"> </span>';
}
?>
                        </div>
                        <div class="flex justify-between">
                            <button class="review-button px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all flex items-center" onclick="openPopup(this)" pitch-id-data="<?= htmlspecialchars($row['id']) ?>" type="button">
                                <i class="far fa-star mr-2 star-icon"></i> Leave a Review
                            </button>
                            <form id="redirectForm" action="FutsalDetailPage.php" method="POST">
  <input type="hidden" name="value" id="valueInput" value="<?= htmlspecialchars($row['id']) ?>">
  <button class="px-4 py-2 btn-primary text-white rounded-lg hover:shadow-lg transition-all" onclick="submitForm(<?= htmlspecialchars($row['id']) ?>)" type="button">
                                Book Now
                            </button>
</form>
                            
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                    
</div>

            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block px-8 py-3 border-2 border-teal-600 text-teal-600 font-medium rounded-lg hover:bg-teal-600 hover:text-white transition-all">
                    View All Grounds <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
       
        <div class="popup-overlay" id="popup" style="display: none;">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">×</span>
    <h3>Submit Your Review</h3>

      <div class="stars" id="starContainer">
        <i data-star="1">&#9733;</i>
        <i data-star="2">&#9733;</i>
        <i data-star="3">&#9733;</i>
        <i data-star="4">&#9733;</i>
        <i data-star="5">&#9733;</i>
      </div>
      <input type="hidden" name="rating" id="review_rating" value="0">
      <input type="hidden" name="pitch_id" id="review_pitch_id">
      <input type="hidden" name="user-id" id="logged_user" value="<?= htmlspecialchars($user_id)?>">
      <textarea id="comment" name="comment" rows="4" placeholder="Write your review here..." required></textarea><br><br>
      <button type="submit" id="review_submit">Submit</button>

  </div>
</div>
    </section>
     <?php else: ?>
<?php endif; ?>


    <!-- How It Works -->
    <!-- <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">How It Works</h2>
                <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Book your futsal ground in just 3 simple steps</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-teal-600 text-2xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Search Grounds</h3>
                    <p class="text-gray-600">Find the perfect futsal ground by location, date, time and facilities.</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-teal-600 text-2xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Select & Book</h3>
                    <p class="text-gray-600">Choose your preferred time slot and confirm your booking instantly.</p>
                </div>
                
                <div class="text-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition-all">
                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-teal-600 text-2xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Play & Enjoy</h3>
                    <p class="text-gray-600">Arrive at the ground and enjoy your game with friends!</p>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Recent Reviews -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">What Our Players Say</h2>
                <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">Read testimonials from our satisfied customers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($getReviews as $review) : 
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
                    </div>
                    
                    <p class="text-gray-700 italic">"<?= $review['comment'] ?>"</p>
                    <p class="text-gray-700 font-bold mr-b"><?= $review['name'] ?> [<?= $review['pitch_name'] ?>]</p>
                </div>
                <?php endforeach; ?>
                <!-- Review 2 -->
                <!-- <div class="bg-white rounded-xl shadow-md p-8 hover:shadow-lg transition-all">
                    <div class="flex items-start mb-6">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="User" class="w-14 h-14 rounded-full mr-4 border-2 border-teal-500">
                        <div>
                            <h3 class="font-bold text-gray-800">Roshan Perera</h3>
                            <div class="flex items-center mt-1">
                                <div class="flex">
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="fas fa-star text-yellow-500"></i>
                                    <i class="far fa-star text-yellow-500"></i>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">4 days ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 italic">"Kandy Futsal Center is a great place to play. The indoor court protects you from the unpredictable weather. Online booking saved us a lot of time and hassle."</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-flex items-center text-teal-600 font-medium hover:text-teal-800">
                    View All Reviews <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div> -->
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Why Choose FutsalSL</h2>
                <div class="w-20 h-1 bg-teal-600 mx-auto"></div>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">We provide the best platform for futsal enthusiasts in Sri Lanka</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-search text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Easy Search</h3>
                    <p class="text-gray-600">Find the perfect futsal ground near you with our advanced search filters and interactive map.</p>
                </div>
                
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-bolt text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Instant Booking</h3>
                    <p class="text-gray-600">Real-time availability and instant confirmation for all your bookings.</p>
                </div>
                
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-shield-alt text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Secure Payments</h3>
                    <p class="text-gray-600">Multiple payment options with SSL encryption for complete security.</p>
                </div>
                
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-star text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Verified Reviews</h3>
                    <p class="text-gray-600">Read genuine reviews from other players before booking.</p>
                </div>
                
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-headset text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">Our customer support team is always ready to assist you.</p>
                </div>
                
                <div class="text-center p-6 feature-card">
                    <div class="inline-flex items-center justify-center p-4 bg-teal-100 rounded-full mb-6 feature-icon">
                        <i class="fas fa-calendar-check text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Easy Rescheduling</h3>
                    <p class="text-gray-600">Change your booking time with just a few clicks when needed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-teal-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold mb-6">Own a Futsal Ground?</h2>
                <p class="text-xl mb-8">Join Sri Lanka's largest futsal network and start receiving bookings today. It's free to register!</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="https://shorturl.at/KjBRi" class="px-8 py-3 bg-white text-teal-600 font-bold rounded-lg hover:bg-gray-100 transition-all no-underline"><i class="fas fa-plus mr-2"></i> Add Your Stadium</a>    
                <!-- <button class="px-8 py-3 bg-white text-teal-600 font-bold rounded-lg hover:bg-gray-100 transition-all" type="button" onclick="sentwhatapp()">
                        
                    </button> -->
                    <button class="px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-teal-600 transition-all" type="button">
                        Learn More
                    </button>
                </div>
            </div>
        </div>
    </section>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    
<script>
  function togglePasswordVisibility() {
    const passInput = document.getElementById("reg_pass");
    const toggleIcon = document.getElementById("toggleIcon");
    const isPassword = passInput.type === "password";

    passInput.type = isPassword ? "text" : "password";
    toggleIcon.classList.toggle("fa-eye", isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", !isPassword);
  }
  function togglePasswordVisibilityLogin() {
    const passInput = document.getElementById("login_password");
    const toggleIcon = document.getElementById("toggleIcon");
    const isPassword = passInput.type === "password";

    passInput.type = isPassword ? "text" : "password";
    toggleIcon.classList.toggle("fa-eye", isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", !isPassword);
  }
</script>
<script>
    const cityInput = document.getElementById('reg_city');
    const suggestionsBox = document.getElementById('citySuggestions');
    const districtDropdown = document.getElementById('reg_district');
    const provinceDropdown = document.getElementById('reg_province');
    const fname = document.getElementById("reg_fname");
    const lname = document.getElementById("reg_lname");
    const door = document.getElementById("reg_doornu");
    const street = document.getElementById("reg_street");
    const phone = document.getElementById("reg_phone");
    const password = document.getElementById("reg_pass");
    const nic = document.getElementById("reg_nic");
    const email = document.getElementById("reg_email");

    cityInput.addEventListener('input', async function () {
      const query = cityInput.value;
      console.log(query);
      if (query.length >= 3) {

        const response = await fetch(`futsal_db.php?action=getcities&query=${encodeURIComponent(query)}`);
        const data = await response.json();
        suggestionsBox.innerHTML = '';
        suggestionsBox.classList.remove('hidden');
        if (data.length > 0) {
          data.forEach(item => {
            const div = document.createElement('div');
            div.classList.add('autocomplete-suggestion');
            div.textContent = item.city;
            div.addEventListener('click', () => {
              cityInput.value = item.city;
              suggestionsBox.classList.add('hidden');
              suggestionsBox.innerHTML = '';
              districtDropdown.innerHTML = `<option value="${item.district}">${item.district}</option>`;
              provinceDropdown.innerHTML = `<option value="${item.province}">${item.province}</option>`;
            });
            suggestionsBox.appendChild(div);
          });
        } else {
            suggestionsBox.classList.add('hidden');
            districtDropdown.innerHTML = `<option value="">District</option>`;
            provinceDropdown.innerHTML = `<option value="">Province</option>`;
		const response_d = await fetch(`futsal_db.php?action=getDistinctDistricts`);
        const data_d = await response_d.json();
        if (data_d.length > 0){
            data_d.forEach(item => {
                console.log(item);
                const district = document.createElement('option');
                district.value = item.district;
                district.textContent=item.district;
                districtDropdown.appendChild(district);
            });
        }
        
         
        }
      } else {
        suggestionsBox.innerHTML = '';
      }
    });

districtDropdown.addEventListener('change', async function () {
    const query = districtDropdown.value;

    const response = await fetch(`futsal_db.php?action=getDistinctProvince&district=${encodeURIComponent(query)}`);
    const data = await response.json();  // use .json() instead of .text()

    if (data.province) {
        provinceDropdown.innerHTML = `<option value="${data.province}">${data.province}</option>`;
    } else {
        provinceDropdown.innerHTML = `<option value="">Select Province</option>`;
    }
});


    // Load all districts and provinces for manual selection


  
  </script>


    <script>
  function openPopup(button) {
    
    const productId = button.getAttribute('pitch-id-data');
    document.getElementById('review_pitch_id').value = productId;
    document.getElementById('popup').style.display = 'flex';
}
    
  

  function closePopup() {
    document.getElementById('popup').style.display = 'none';
  }

  const stars = document.querySelectorAll('#starContainer i');
  const ratingInput = document.getElementById('review_rating');

  stars.forEach(star => {
    star.addEventListener('click', () => {
      const rating = star.getAttribute('data-star');
      ratingInput.value = rating;
      stars.forEach(s => s.classList.remove('selected'));
      for (let i = 0; i < rating; i++) {
        stars[i].classList.add('selected');
      }
    });
  });
</script>
      <script>



          
    function toggleCell(cell) {
    cell.classList.toggle('selected');
     // Get the checkbox inside the cell
    const checkbox = cell.querySelector('input[type="checkbox"]');
    
    if (checkbox) {
      // Toggle checkbox based on cell selection
      checkbox.checked = cell.classList.contains('selected');
    }
  }
    </script>
    <script>
  function submitForm(val) {
    document.getElementById('valueInput').value = val;
        // Optional: console log for debugging
    console.log("Submitting value:", val);
    document.getElementById('redirectForm').submit();
  }
</script>

<script src="js/script.js"></script>
<script src="js/script_login.js"></script>

<?php
include 'footer.php'; // Footer
?>