<?php
include 'header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['value'])) {
    $value = $_POST['value'];
    $pitchdetails = getThisPitchDetails($value);
    $reviews_details = $pitchdetails['Reviews']??[];
    $loc = $pitchdetails['location_id'] ?? null;
    $images = $pitchdetails['Images'] ?? [];

    
} else {
    include './';
}
$current_time = date("H:i:s");
$current_date = date("Y-m-d");
if (count($images) > 0) {
    $mainImage = ROOT_URL . ltrim(str_replace('\\', '/', $images[0]['image_url']), '/');
} else {
    $mainImage = ROOT_URL . 'uploads/default-futsal.jpg';
}

$average = floatval($pitchdetails['average_rating']);
$fullStars = floor($average);
$hasHalfStar = ($average - $fullStars) >= 0.25 && ($average - $fullStars) < 0.75;
$emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);


?>

    <!-- Breadcrumb -->
    <div class="bg-gray-100 py-3">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm">
                <a href="#" class="text-gray-600 hover:text-teal-600 flex items-center">
                    <i class="fas fa-home mr-1"></i> Home
                </a>
                <span class="mx-2 text-gray-400">/</span>
                <!-- <form action="location.php" method="POST" style="display:inline;" id="redirectForm">
                    <input type="hidden" name="location_id" id="valueInput" value="<?= htmlspecialchars($pitchdetails['location']) ?>">
                    <button type="submit" class="text-gray-600 hover:text-teal-600 border-0 bg-transparent cursor-pointer p-0 m-0" onclick="submitForm(<?= $loc ?>)">
                        <?= htmlspecialchars($pitchdetails['location']) ?>
                    </button>
                 </form> -->
                  <form id="redirectForm" action="location.php" method="POST">
  <input type="hidden" name="value" id="valueInput" value="<?= htmlspecialchars($pitchdetails['location']) ?>">
  <button class="text-gray-600 hover:text-teal-600 border-0 bg-transparent cursor-pointer p-0 m-0" onclick="submitForm(<?= htmlspecialchars($pitchdetails['location']) ?>)">
                                <?= htmlspecialchars($pitchdetails['location']) ?>
                            </button>
</form>
                <!--<a href="./location.php" class="text-gray-600 hover:text-teal-600"><?= htmlspecialchars($pitchdetails['location']) ?></a> -->
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-teal-600 font-medium"><?= htmlspecialchars($pitchdetails['name']) ?></span>
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-teal-600 font-medium"><?= htmlspecialchars($pitchdetails['pitch_name']) ?></span>
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Ground Details -->
            <div class="lg:col-span-2">
                <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($pitchdetails['name']) ?> [<?= htmlspecialchars($pitchdetails['pitch_name']) ?>]</h1>
                <div class="flex items-center mb-6">
                    
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

<span class="text-gray-600 mr-4">
    <?= number_format($average, 1) ?> (<?= htmlspecialchars($pitchdetails['review_count']) ?> reviews)
</span>
                    <!-- <div class="flex text-yellow-500 mr-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="text-gray-600 mr-4"><?= htmlspecialchars($pitchdetails['average_rating']) ?> (<?= htmlspecialchars($pitchdetails['review_count']) ?> reviews)</span> -->
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1 text-teal-600"></i>
                        <span><?= htmlspecialchars($pitchdetails['address']) ?></span>
                    </div>
                </div>
                
                <!-- Image Gallery -->

<div class="mb-8">
    <!-- Main Image Display -->
      <div class="relative h-96 rounded-xl overflow-hidden mb-3">
        <img id="mainImage" src="<?= $mainImage ?>" alt="Futsal Ground Main Image" class="w-full h-full object-cover">
        <div class="absolute top-4 right-4 price-tag text-white px-4 py-1 rounded-full text-sm font-bold">
            <?php
            if ($current_time > $pitchdetails['offpeak_start_time'] && $current_time < $pitchdetails['offpeak_end_time']) {
                echo "Rs. {$pitchdetails['offpeak_rate']}/hr";
            } else {
                
                echo "Rs. {$pitchdetails['peak_rate']}/hr";
            }
            ?>
        </div>
    </div>


    <!-- Thumbnail List -->
    <div id="thumbnailContainer" class="flex gap-3 overflow-x-auto">
        <?php
        foreach ($images as $index => $image) {
            $imageUrl = ROOT_URL . ltrim(str_replace('\\', '/', $image['image_url']), '/');
            $isActive = ($imageUrl === $mainImage) ? 'border-4 border-blue-500' : '';
            echo '
            <div class="min-w-[96px] h-24 rounded-lg overflow-hidden cursor-pointer gallery-thumb transition-all border-2 border-transparent ' . $isActive . '" 
                 onclick="changeMainImage(this, \'' . $imageUrl . '\')">
                <img src="' . $imageUrl . '" alt="Futsal Ground" class="w-full h-full object-cover">
            </div>';
        }
        ?>
    </div>
</div>




                
                <!-- Features & Amenities -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <?php
                            if ($pitchdetails['Amenities']){
$Amenities = explode(',', $pitchdetails['Amenities']);
                   echo '<h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Features & Amenities</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

foreach ($Amenities as $tag) {
    echo '<div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-all">
                    <div class="bg-teal-100 w-10 h-10 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-teal-600"></i>
                            </div>
                            <span class="font-medium">'. htmlspecialchars(trim($tag)) .'</span>
                        </div>';

}
echo '</div>';
}
else{
    echo ' ';
}
?>

</div>
                
                
                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">About This Court</h2>
                    <p class="text-gray-700 mb-4">
                        <?= htmlspecialchars($pitchdetails['description']) ?>
                    </p>
                    <p class="text-gray-700 mb-4">
                        The court is equipped with professional floodlighting, allowing for play at any time of day. We offer clean changing rooms with shower facilities, and a comfortable viewing area for spectators. Refreshments are available on-site.
                    </p>
                    <p class="text-gray-700">
                        Equipment rental is available for those who need it, including bibs, balls, and goalkeeper gloves. Free parking is provided for all customers.
                    </p>
                </div>
                
                <!-- Location -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Location</h2>
                    <div class="h-64 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                        <!-- Map placeholder (would be integrated with Google Maps or similar) -->
                        <iframe class="w-full h-full object-cover" width="600" height="450" style="border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode($pitchdetails['address']) ?>&output=embed">
</iframe>          </div>
                    <div class="flex items-center text-gray-700 p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-map-marker-alt text-teal-600 mr-3 text-xl"></i>
                        <span><?= htmlspecialchars($pitchdetails['address']) ?></span>
                    </div>
                </div>
                
                <!-- Reviews -->
                 
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800">Customer Reviews</h2>
                        <div class="flex items-center">
                            <div class="text-yellow-500 text-2xl font-bold mr-2"><?= number_format($average, 1) ?></div>
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

                            <span class="ml-2 text-gray-600">(<?= htmlspecialchars($pitchdetails['review_count']) ?> reviews)</span>
                        </div>
                    </div>
        
        
                    
                    <!-- Review 1 -->
                     <?php

                 
        foreach ($reviews_details as  $review): 
        $average = floatval($review['rating']);
$fullStars = floor($average);
$hasHalfStar = ($average - $fullStars) >= 0.25 && ($average - $fullStars) < 0.75;
$emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
        ?>
                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <div class="flex items-start mb-3">
                             <?php

if($review['cus_id']){
$image = $review['image_path'];
$name = $review['full_name'];
}
else{
    $image = 'uploads/dummy-user.png';
    $name = 'Anonymous';
}
?>


                           
<img src="<?= htmlspecialchars($image) ?>" alt="User" class="w-12 h-12 rounded-full mr-4 border-2 border-teal-500">
                           
                            
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
                        <p class="text-gray-700 italic">
                            "<?= htmlspecialchars($review['comment']) ?>"
                        </p>
                    </div>
                    <?php endforeach; ?>
                    
                    
                    <div class="text-center mt-6">
                        <button class="px-6 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-600 hover:text-white transition-all">
                            View All Reviews <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24 booking-card h-screen overflow-y-auto overflow-hidden">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">Book Your Session</h2>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Select Date</label>
                        <div class="relative">
                            <input type="date" class="block appearance-none w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" id="pitch_date">
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <!-- <i class="fas fa-calendar-day"></i> -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Select Time Slot</label>
                        <div class="grid grid-cols-3 gap-2" id="slot_timing">
                           
                        </div>
                    </div>
                    
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Price per hour</span>
                            <span class="font-medium" id="tt_cost">Rs. 0</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium" id="tt_hours">0 Hour</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-red-600">Discount</span>
                            <span class="font-medium text-red-600" id="discounts">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Service fee</span>
                            <span class="font-medium" id="service_charge">Rs. 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-3">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-xl font-bold text-teal-600" id="tt_amount">Rs. 0</span>
                        </div>
                    </div>
                    
                    <button class="w-full btn-primary text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transition-all mb-4">
                        <i class="fas fa-calendar-check mr-2"></i> Book Now
                    </button>
                    
                    <button class="w-full bg-white border border-teal-600 text-teal-600 font-bold py-3 px-6 rounded-lg hover:bg-teal-50 transition-all flex items-center justify-center">
                        <i class="far fa-heart mr-2"></i> Add to Favorites
                    </button>
                </div>
            </div>
        </div>
        
    </main>
<div id="login_popup" class="hidden fixed inset-0 flex items-center justify-center bg-black/50 z-50">
  <div class="bg-white p-6 rounded-md shadow-md w-96 relative">
    <!-- Close button -->
    <button id="close_login" class="absolute top-2 right-2 text-gray-600 hover:text-black" onclick="closeModal()">&times;</button>
    
    <!-- Your login form or content -->
    <h2 class="text-lg font-semibold mb-4">Login</h2>
    <form>
      <input type="text" placeholder="Username" class="w-full mb-3 px-3 py-2 border rounded" id="login_username" required>
      <input type="password" placeholder="Password" class="w-full mb-3 px-3 py-2 border rounded" id="login_password" required>      
      <button type="submit" class="btn btn-primary w-full" id="login_submit">Submit</button>
    </form>
  </div>
</div>
<div id="Register_popup" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
  <div class="bg-white p-6 rounded-md shadow-md w-96 relative">
    <!-- Close button -->
    <button id="close_login" class="absolute top-2 right-2 text-gray-600 hover:text-black" onclick="closeModal()">&times;</button>
    
    <!-- Your login form or content -->
    <h2 class="text-lg font-semibold mb-4">User Registration</h2>
    <form>
        <label class="text-500 text-m">Full Name: </label>
      <input type="text" placeholder="Full Name" class="w-full mb-3 px-3 py-2 border rounded" id="reg_name" required>
      <label class="text-500 text-m">Address: </label>
      <input type="text" placeholder="Address" class="w-full mb-3 px-3 py-2 border rounded" id="reg_add" required> 
      <label class="text-500 text-m">Phone Number: </label><span class="text-red-500 text-[0.6rem]"> [This will use as your username]</span>
      <input type="text" placeholder="Phone Number" class="w-full mb-3 px-3 py-2 border rounded" id="reg_phone" required>   
      <label class="text-500 text-m">NIC: </label><span class="text-red-500 text-[0.6rem]"> [This will use as your password]</span>
      <input type="text" placeholder="NIC" class="w-full mb-3 px-3 py-2 border rounded" id="reg_nic" required>  
      <label class="text-500 text-m">Email: </label>
      <input type="text" placeholder="Email" class="w-full mb-3 px-3 py-2 border rounded" id="reg_email" required>   
      <button type="submit" class="btn btn-primary w-full" id="reg_submit">Submit</button>
    </form>
  </div>
</div>
<script>
function changeMainImage(el, url) {
    document.getElementById('mainImage').src = url;

    // Remove highlight from all thumbnails
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('border-4', 'border-blue-500');
    });

    // Highlight the selected one
    el.classList.add('border-4', 'border-black-500');
}
</script>

<script>
    let tt_cost = 0;
    let tt_hours = 0;
    let service_charge = 0;
    let tt_amount = 0;
    let firstItemCost = 0;
    let otherItemCost = 0;
    let dateStr = "<?php echo $current_date; ?>"; // "YYYY-MM-DD"
    let dateObj = new Date(dateStr + "T00:00:00"); // Convert to Date object
    let pitch_details = <?php echo json_encode($pitchdetails); ?>;
    const date_sel = document.getElementById("pitch_date");
    
    date_sel.addEventListener('change',function(){
        dateObj = new Date(this.value);

        
    dateStr = dateObj.toISOString().split('T')[0];
        loadTimeSlots(pitch_details, dateStr, dateObj);
    })

document.addEventListener("DOMContentLoaded", function() {
 loadTimeSlots(pitch_details, dateStr, dateObj);
});
    
    
    

    let selectedSlots = [];


function loadTimeSlots(futsal, dateString, dateObject) {
    selectedFutsal = futsal;

    /* let firstItemCost = futsal.initial_cost;
    let otherItemCost = futsal.extra_cost; */
    let firstItemCost = futsal?.initial_cost ?? 0;
    let otherItemCost = futsal?.extra_cost ?? 0;
    const timeSlots = generateTimeSlots(futsal.opening_time, futsal.closing_time, dateString);
    $("#slot_timing").empty();

    timeSlots.forEach((slot) => {
        const hour = parseInt(slot.split(":")[0], 10);
        const isPeak = hour < parseInt(futsal.offpeak_start_time) || hour >= parseInt(futsal.offpeak_end_time);
        const cost = isPeak ? futsal.peak_rate : futsal.offpeak_rate;
        const pitchId = futsal.pitch_id;
        const txtslot = `<span>${slot}</span><br><small>${cost}/=</small>`;
        const btn_class = isPeak ? "slot-btn py-2 px-3 bg-teal-500 text-teal-100 rounded-lg hover:bg-teal-700 transition-all text-center" : "slot-btn py-2 px-3 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-all text-center"
        const pastCheck = isPastTime(dateObject, slot);

 if (!pastCheck) {
        const $button = $("<button>")
            .html(txtslot)
            .addClass(btn_class)
            .data({
                slot: slot,
                cost: cost,
                pitchId: pitchId
            })
            .on("click", function () {
                const slotData = $(this).data();
                const slotKey = `${slotData.slot}-${slotData.pitchId}`;

                const existingIndex = selectedSlots.findIndex(s => `${s.slot}-${s.pitchId}` === slotKey);

                if (existingIndex > -1) {
                    // Deselect
                    selectedSlots.splice(existingIndex, 1);

                    $(this).removeClass("bg-teal-700 text-white").addClass("bg-teal-100 text-teal-700");
                    if (tt_cost > 0){
                        tt_cost -= parseFloat(slotData.cost);
                    
                    }
                    
                } else {
                    // Select
                    selectedSlots.push(slotData);
                    $(this).removeClass("bg-teal-100 text-teal-700").addClass("bg-teal-700 text-white");
                    tt_cost += parseFloat(slotData.cost);
                    

                }





                
                tt_hours = selectedSlots.length;
                service_charge = parseFloat(firstItemCost);
                let extraItemcost = (tt_hours - 1) * otherItemCost;
                $("#tt_cost").text(`Rs: ${tt_cost}`);
                if (tt_hours > 0) {
                    service_charge +=  parseFloat(extraItemcost);
                    } else {
                    service_charge = 0;
                    }
                
                if (tt_hours > 1){
                    $("#tt_hours").text(`${tt_hours} Hours`);
                }
                else{
                    $("#tt_hours").text(`${tt_hours} Hour`);
                }
                $("#service_charge").text(`Rs: ${service_charge}`);

                tt_amount = tt_cost +service_charge;
                $("#tt_amount").text(`Rs: ${tt_amount}`);
                
            });

       
            /* $button
                .removeClass("bg-teal-100 text-teal-700 hover:bg-teal-200")
                .addClass("bg-gray-100 text-gray-400 cursor-not-allowed")
                .prop("disabled", true); */
        

        $button.appendTo("#slot_timing");
        }
    });
}


    function generateTimeSlots(openTime, closeTime, selectedDate) {
        let slots = [];
        let start = new Date(`${selectedDate}T${openTime}`);
        const end = new Date(`${selectedDate}T${closeTime}`);
        while (start < end) {
            slots.push(start.toTimeString().substring(0, 5)); // HH:mm format
            start.setHours(start.getHours() + 1);
        }
        return slots;
    }

    function isToday(dateObj) {
        const today = new Date();
        return dateObj.toDateString() === today.toDateString();
    }

    function isPastTime(dateObj, slot) {
        if (isToday(dateObj)) {
            const [hour, minutes] = slot.split(":").map(Number);
            const now = new Date();
            return now.getHours() > hour || (now.getHours() === hour && now.getMinutes() > minutes);
        }
        return false;
    }

    function selectSlot(slot, futsal) {
        selectedSlot = slot;
        const hour = parseInt(slot.split(":")[0], 10);
        const isPeak = hour >= parseInt(futsal.peak_start) && hour < parseInt(futsal.peak_end);
        const cost = isPeak ? futsal.peak_rate : futsal.offpeak_rate;

        $("#startTime").val(slot);
        $("#costOutput").val(`${cost} /=`);
    }
</script>
<script>
  function submitForm(val) {
    document.getElementById('valueInput').value = val;
        // Optional: console log for debugging
    
    document.getElementById('redirectForm').submit();
  }
</script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/js/futsalpage.js"></script>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="/Asset/css/style_book.css">  -->

<?php
include 'footer.php';
?>