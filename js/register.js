 const s_phoneInput = document.getElementById("s_phone");
    const userNameResponse = document.getElementById("checkuser");
    const passwordResponse = document.getElementById("passcheck");
    const confirmPasswordResponse = document.getElementById("confirmcheck");
    const emailResponse = document.getElementById("emailCheck");
    const phoneResponse = document.getElementById("phoneCheck");
    const userRegister = document.getElementById("s_Register");
    const s_userName = document.getElementById("s_user");
    const emailInput = document.getElementById("s_email");
    const s_passWord = document.getElementById("s_pass");
    const s_confirmPassword = document.getElementById("s_c_pass");
    const btnRegister =document.getElementById("s_Register");
    const sltcity = document.getElementById("reg_city");



s_userName.addEventListener("input", function () {
    let sUser = s_userName.value;
    s_passWord.value="";
    // Disable password input by default
    s_passWord.disabled = true;
    userNameResponse.textContent = "";
    const userCritiria = /^(?=.*[A-Za-z]).+$/;
    if (sUser.length >= 6 && sUser.length <= 15) {
        if(userCritiria.test(sUser)){
            userNameResponse.style.color = "green";
            userNameResponse.textContent = "Username is valid";
            fetch(`/futsal_db.php?action=checkUsername&username=${encodeURIComponent(sUser)}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    userNameResponse.style.color = "red";
                    userNameResponse.textContent = `The username "${sUser}" is already taken. Please try another.`;
                } else {
                    userNameResponse.style.color = "green";
                    userNameResponse.textContent = `"${sUser}" is available.`;
                    s_passWord.disabled = false;
                }
            })
            .catch(error => {
                console.error("Error checking username:", error);
                userNameResponse.style.color = "red";
                userNameResponse.textContent = "Error checking username. Please try again.";
                
            });
        }
        else{
            userNameResponse.style.color = "red";
            userNameResponse.textContent = "Please use minimum 1 letter";
            
        }
        
    }
    else{
        if (sUser.length === 0){
            userNameResponse.textContent = ``;
            s_passWord.disabled = true;
        }
        else{
            userNameResponse.style.color = "red";
        userNameResponse.textContent = `username should between 6 - 15 charactors.`;
        s_passWord.disabled = true;
        }
    }
});


s_passWord.addEventListener("input", function () {
    document.getElementById("eye_pass").style.display = "block";
    const password = s_passWord.value;
    const passwordCriteria = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;
if (password==="") {
        document.getElementById("eye_pass").style.display = "none";
    }
    if (passwordCriteria.test(password)) {
        passwordResponse.style.color = "green";
        passwordResponse.textContent = "Password is strong.";
        s_confirmPassword.disabled = false
    } else {
        passwordResponse.style.color = "red";
        passwordResponse.textContent = "Password must be at least 8 characters long and include at least 1 uppercase letter, 1 number, and 1 special character.";
    }
    checkFields();
});

s_confirmPassword.addEventListener("input", function () {
    document.getElementById("eye_Cpass").style.display = "block";
    const password = s_passWord.value;
    const confirmPassword = s_confirmPassword.value;
    if (confirmPassword==="") {
        document.getElementById("eye_Cpass").style.display = "none";
    }
    if (confirmPassword === password) {
        confirmPasswordResponse.style.color = "green";
        confirmPasswordResponse.textContent = "Passwords match.";
    } else {
        confirmPasswordResponse.style.color = "red";
        confirmPasswordResponse.textContent = "Passwords do not match.";
    }
    checkFields();
});

emailInput.addEventListener("input", function () {
    const email = emailInput.value;
    const emailCriteria = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (emailCriteria.test(email)) {
      emailResponse.style.color = "green";
      emailResponse.textContent = "Valid Email Address";
    } else {
      emailResponse.style.color = "red";
      emailResponse.textContent = "Invalid Email Address";
    }
    checkFields();
  });
s_phoneInput.addEventListener("input", function () {
    let phone = s_phoneInput.value;

    // Remove non-digit characters
    phone = phone.replace(/\D/g, "");

    // Limit to 10 digits
    if (phone.length > 10) {
        phone = phone.slice(0, 10);
    }

    // Update the input field with sanitized value
    s_phoneInput.value = phone;

    // Regex to match numbers starting with 07 and exactly 10 digits
    const phoneCriteria = /^07\d{8}$/;

    if (phoneCriteria.test(phone)) {
        phoneResponse.style.color = "green";
        phoneResponse.textContent = "Valid Phone Number";
    } else {
        phoneResponse.style.color = "red";
        phoneResponse.textContent = "Invalid Phone Number (Must start with 07)";
    }

    checkFields();
});






  function checkFields() {
    if (emailInput.value && s_passWord.value && s_confirmPassword.value && sltcity.value) {
        userRegister.disabled = false;  // Enable button
    } else {
        userRegister.disabled = true;   // Disable button
    }
  }
  checkFields();

















document.getElementById("registerForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = this;
  const responseDiv = document.getElementById("response");
  responseDiv.innerText = "Submitting..."; // Optional: loading indicator

  try {
    const formData = new FormData(form);
    
     const dbResponse = await fetch("/futsal_db.php?action=saveclient", {
      method: "POST",
      body: formData,
    });

    const db_result = await dbResponse.json()
    console.log("Convert Res: ",db_result.message);
    if (db_result.status){
        responseDiv.innerText = "Generating Email.....";
    const response = await fetch("register.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`);
    }

    const result = await response.text();
    responseDiv.innerText = result;
    window.location.href = './profile.php';
    }
    else{
    
    responseDiv.innerText = "Database response: ",db_result.message;
    alert("Database response: ",db_result.message);
    location.reload();

    }

  } catch (error) {
    console.error("Submission failed:", error);
    responseDiv.innerText = "An error occurred during submission. Please try again.";
  }
});


document.getElementById("reg_province").addEventListener("change", function(){

	  fetch(`/futsal_db.php?action=getDistrictByProvince&province=${encodeURIComponent(this.value)}`)
    .then(response => response.text())  // Read as text first
    .then(text => {
        if (!text) {
            throw new Error("Empty response from server");
        }
        return JSON.parse(text);  // Parse JSON
    })
    .then(districts => {
        if (!Array.isArray(districts)) {
            throw new Error("Invalid JSON response");
        }
        const locationsContainer = document.getElementById("reg_district");
        if (locationsContainer) {
            locationsContainer.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.textContent = "-- Select District --";
            defaultOption.value = "";
            locationsContainer.appendChild(defaultOption);
            districts.forEach(location => {
                const locOption = document.createElement("option");
                locOption.value = location;
                locOption.textContent = location;
                locationsContainer.appendChild(locOption);
            });
			
			locationsContainer.disabled = false;
        } else {
            console.error('Locations container not found');
        }
    })
    .catch(error => console.error('Error fetching locations:', error));
	
});



document.getElementById("reg_district").addEventListener("change", function(){

	  fetch(`/futsal_db.php?action=getCitiesByDistrict&district=${encodeURIComponent(this.value)}`)
    .then(response => response.text())  // Read as text first
    .then(text => {
        if (!text) {
            throw new Error("Empty response from server");
        }
        return JSON.parse(text);  // Parse JSON
    })
    .then(districts => {
        if (!Array.isArray(districts)) {
            throw new Error("Invalid JSON response");
        }
        const locationsContainer = document.getElementById("reg_city");
        if (locationsContainer) {
            locationsContainer.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.textContent = "-- Select City --";
            defaultOption.value = "";
            locationsContainer.appendChild(defaultOption);
			
            districts.forEach(location => {
                const locOption = document.createElement("option");
                locOption.value = location;
                locOption.textContent = location;
                locationsContainer.appendChild(locOption);
            });
            const defaultOption2 = document.createElement("option");
            defaultOption2.textContent = "-- New / None --";
            defaultOption2.value = "__new__";
            locationsContainer.appendChild(defaultOption2);
			
			locationsContainer.disabled = false;
        } else {
            console.error('Locations container not found');
        }
    })
    .catch(error => console.error('Error fetching locations:', error));
	
});
