<?php
/* Template Name: Create Account Page */
#include 'futsal_db.php';
include 'header.php';
$location_details = getLocationDetails();
$provinces = getDistinctProvinces();

?>
<!-- Loader -->
<div id="loader" class="text-center my-4">
  <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

  <!-- Add New City Modal -->
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
<!-- Registration Form -->
<div class="container my-5">
  <div class="card mx-auto shadow p-4" style="max-width: 600px;">
    <h3 class="text-center mb-4">Indoor Facility Registration</h3>
    <form id="registerForm">

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


<script>
  function togglePasswordVisibility() {
    const passInput = document.getElementById("s_pass");
    const toggleIcon = document.getElementById("toggleIcon");
    const isPassword = passInput.type === "password";

    passInput.type = isPassword ? "text" : "password";
    toggleIcon.classList.toggle("fa-eye", isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", !isPassword);
  }

   function toggleCPasswordVisibility() {
    const passInput = document.getElementById("s_c_pass");
    const toggleIcon = document.getElementById("toggleIcon2");
    const isPassword = passInput.type === "password";

    passInput.type = isPassword ? "text" : "password";
    toggleIcon.classList.toggle("fa-eye", isPassword);
    toggleIcon.classList.toggle("fa-eye-slash", !isPassword);
  }
</script>

<script>
  const phoneNo = document.getElementById('s_phone');
  const email = document.getElementById('s_email');

  

</script>
<script>


function checkNewGroup(value) {
  if (value === '__new__') {
    
    document.getElementById('newCityModal').classList.remove('fade');
    document.getElementById("newCityModal").style.display = "flex";
        document.getElementById('newCityModal').classList.add('d-flex');
  }
else{
  document.getElementById('newCityModal').classList.remove('d-flex');
  
    document.getElementById("newCityModal").style.display = "none";
  document.getElementById('newCityModal').classList.add('fade');
}

}


function selectPrDis(){
  document.getElementById("new_city_name").disabled = false;

}


function cancelSubmit(){
  document.getElementById('newCityModal').classList.add('fade');
    document.getElementById("newCityModal").style.display = "none";
        document.getElementById('newCityModal').classList.remove('d-flex');
        document.getElementById("city").selectedIndex = 0;
        document.getElementById("city").value = "";
        document.getElementById("location_group").value="";
        document.getElementById("new_city_name").value="";
}

function submitNewCity() {
  const groupValue = document.getElementById("location_group").value;
  const cityValue = document.getElementById("new_city_name").value.trim();

  if (!groupValue || !cityValue) {
    alert("Please select a location group and enter a city name.");
    return;
  }

  const [province, district] = groupValue.split(" - ");  // Assuming format is "Province - District"

  const formData = new FormData();
  formData.append("province", province.trim());
  formData.append("district", district.trim());
  formData.append("city", cityValue);

  fetch("/futsal_db.php?action=insert_location", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'Success') {
      if (data.message ==="Location already exists."){

        document.getElementById("city").value=cityValue;

document.getElementById("city").dispatchEvent(new Event('change'));
      }
      else{
        const selectElement = document.getElementById("city");
        const optgroups = selectElement.getElementsByTagName('optgroup');
        for (let i = 0; i < optgroups.length; i++) {
    if (optgroups[i].label === groupValue) {
      // Return all options inside the matched optgroup
      const newOption = document.createElement("option");
          newOption.value = cityValue;
          newOption.textContent = cityValue;
          newOption.selected = true;  // Select the new option

          // Insert the new option into the existingOptions optgroup
          optgroups[i].appendChild(newOption);
          document.getElementById("city").value=cityValue;

document.getElementById("city").dispatchEvent(new Event('change'));
      
    }
  }

      }
      
        
        document.getElementById("location_group").value="";
        document.getElementById("new_city_name").value="";
      // Optionally append the new city to your city dropdown here
    } else {
      alert("Error: " + data.message);
    }
  });
  document.getElementById('newCityModal').classList.add('fade');
    document.getElementById("newCityModal").style.display = "none";
        document.getElementById('newCityModal').classList.remove('d-flex');
}

</script>

<script>
  	window.addEventListener("load", function () {
  const loader = document.getElementById("loader");
  loader.style.opacity = '0';
  loader.style.transition = 'opacity 0.5s ease';
  setTimeout(() => {
    loader.style.display = 'none';
  }, 500);
});




</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="asset/js/register.js"></script> <!-- Updated JS -->

<link rel="stylesheet" href="asset/css/style_book.css"> <!-- Optional CSS -->
<?php include 'footer.php'; // Include WordPress footer ?>