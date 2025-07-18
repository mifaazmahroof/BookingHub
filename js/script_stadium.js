$(document).ready(function () {

    const s_phoneInput = document.getElementById("s_phone");
    const userNameResponse = document.getElementById("res_user");
    const passwordResponse = document.getElementById("passcheck");
    const confirmPasswordResponse = document.getElementById("confirmcheck");
    const emailResponse = document.getElementById("emailCheck");
    const userRegister = document.getElementById("s_Register");
    const s_userName = document.getElementById("s_user");
    const emailInput = document.getElementById("s_email");
    const s_passWord = document.getElementById("s_pass");
    const s_confirmPassword = document.getElementById("s_c_pass");
    const btnRegister =document.getElementById("s_Register");
    const sltcity = document.getElementById("city");


    $(document).ready(function() {
        $('#city').select2({
          placeholder: "-- Select City --"
        });
      });


    s_userName.addEventListener("input", function () {
    let sUser = s_userName.value;
    s_passWord.value="";
    // Disable password input by default
    s_passWord.disabled = true;
    userNameResponse.textContent = "";
    const userCritiria = /^(?=.*[A-Za-z]).+$/;
    console.log(sUser);
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
    const password = s_passWord.value;
    const passwordCriteria = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/;

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
    const password = s_passWord.value;
    const confirmPassword = s_confirmPassword.value;

    if (confirmPassword === password) {
        confirmPasswordResponse.style.color = "green";
        confirmPasswordResponse.textContent = "Passwords match.";
    } else {
        confirmPasswordResponse.style.color = "red";
        confirmPasswordResponse.textContent = "Passwords do not match.";
    }
    checkFields();
});

btnRegister.addEventListener("click", function () {
    let indoor_door = document.getElementById("indoor_name").value;
    let location = document.getElementById("city").value;
    let address = document.getElementById("address").value;
    let tel = s_phoneInput.value;
    let email = emailInput.value;
    let userName = s_userName.value;
    let password = s_passWord.value;
     

    if (!indoor_door.trim() || !location.trim() || !address.trim() || !tel.trim() || !userName.trim() || !password.trim()) {
        alert("Please enter all details!");
        return;
    }   
    console.log("register")

    fetch("/futsal_db.php?action=saveclient", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `indoor_door=${encodeURIComponent(indoor_door)}&location=${encodeURIComponent(location)}&address=${encodeURIComponent(address)}&phone=${encodeURIComponent(tel)}&email=${encodeURIComponent(email)}&userN=${encodeURIComponent(userName)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(result => {
        console.log(result.stadiumId);
        customer_id = result.stadiumId;
        if (result.status){
            customer_id =  `${customer_id}`;
            document.getElementById("cusId").textContent = `${customer_id}`;
            document.getElementById("saveUser").style.display="none";
            confirmButton.style.display = "block";
            backButton.style.display = "block";
        }
        alert(result.message);

        //location.reload();
    });

    
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

  function checkFields() {
    if (emailInput.value && s_passWord.value && s_confirmPassword.value && sltcity.value) {
        userRegister.disabled = false;  // Enable button
    } else {
        userRegister.disabled = true;   // Disable button
    }
  }
  checkFields();

  /* document.getElementById("showPass").addEventListener("click", function ()  {
    
    
    
      const button = document.querySelector(".toggle-password");
      const isPassword = s_passWord.type === "password";
      s_passWord.type = isPassword ? "text" : "password";
      button.textContent = isPassword ? "🙈" : "👁️";
}); */
});