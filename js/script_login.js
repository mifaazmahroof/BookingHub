$(document).ready(function () {
const login_popup = document.getElementById('login_popup');
const Register_popup = document.getElementById('Register_popup');

if (login_popup) {
const username = document.getElementById('login_username');
const password = document.getElementById('login_password');
const loginBtn = document.getElementById('login_submit');

function validate() {
    loginBtn.disabled = !(username.value && password.value);
  }
  
  username.addEventListener('input', validate);
  password.addEventListener('input', validate);

  loginBtn.addEventListener('click', async () => {

      let selectedType="";
  if (!isNaN(username.value)&&username.value.length===10){
     selectedType = 'Customer';
  }
  else{
     selectedType = 'Client';
  }


 const body = `username=${encodeURIComponent(username.value)}&password=${encodeURIComponent(password.value)}&logintype=${encodeURIComponent(selectedType)}`;
console.log(body);
try {
  const response = await fetch('/futsal_db.php?action=login', {
    method: 'POST',
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: body
  });

  if (!response.ok) {
    throw new Error(`HTTP error! Status: ${response.status}`);
  }

  const result = await response.json();

  if (result.success) {
    if (selectedType === 'Client') {
      window.location.href = './Profile.php';
    } else if (selectedType === 'Customer') {
      location.reload();
    }
  } else {
    alert(`Error message: ${result.message}`);
    if (result.query) console.log("SQL Debug:", result.query);
  }
} catch (err) {
  alert(`Something went wrong with db: ${err.message}`);
}

  });


}

if (Register_popup.offsetParent !== null){

              const fname = document.getElementById("reg_fname");
              const lname = document.getElementById("reg_lname");
              const door = document.getElementById("reg_doornu");
              const street = document.getElementById("reg_street");
              const city = document.getElementById("reg_city");
              const district = document.getElementById("reg_district");
              const province = document.getElementById("reg_province");
							const nic = document.getElementById("reg_nic");
							const email = document.getElementById("reg_email");
							const password = document.getElementById("reg_pass");
              const reg_phone = document.getElementById("reg_phone");
              //const reg_btn = document.getElementById("reg_submit");




reg_phone.addEventListener("input", function () {
        let phone = reg_phone.value;

        if (phone.length === 10) {
            //reg_phone.disabled = true;
            //editButton.style.display = "block";
            fetch(`/futsal_db.php?action=getphone&phoneNo=${phone}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                      alert("Phone number already exists.");
                      reg_phone.value = '';
                      //reg_phone.disabled = false;
                    }

                })
                .catch(error => console.error("Error:", error));
        }
    });
  document.getElementById('register_form').addEventListener('submit', async function (e) {
      e.preventDefault();
  //const form = this;
  const responseDiv = document.getElementById("response");
  responseDiv.innerText = "Submitting..."; // Optional: loading indicator
     /*  const formData = {
        fname: fname.value,
        lname: lname.value,
        door: door.value,
        street: street.value,
        phone: phone.value,
        password: password.value,
        nic: nic.value,
        email: email.value,
        city: cityInput.value,
        district: districtDropdown.value,
        province: provinceDropdown.value
      }; */
try{
const formData = new FormData();
formData.append('fname', fname.value);
formData.append('lname', lname.value);
formData.append('door', door.value);
formData.append('street', street.value);
formData.append('phone', phone.value);
formData.append('password', password.value);
formData.append('nic', nic.value);
formData.append('email', email.value);
formData.append('city', cityInput.value);
formData.append('district', districtDropdown.value);
formData.append('province', provinceDropdown.value);

console.log(formData);
  const res = await fetch('/futsal_db.php?action=saveUser', {
  method: 'POST',
  body: formData,
});
 const db_result = await res.json()
    console.log("Convert Res: ",db_result.message);
    if (db_result.status){
        responseDiv.innerText = "Generating Email.....";
    const response = await fetch("register_user.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`Server error: ${response.status}`);
    }

    const result = await response.text();
    responseDiv.innerText = result;
     alert(`Database response: ${result}`);
    window.location.href = './Profile.php';
    }
    else{
    
    responseDiv.innerText = `Database response: ${db_result.message}`;
    alert(`Database response: ${db_result.message}`);
    location.reload();

    }

}
catch (error) {
    console.error("Submission failed:", error);
    responseDiv.innerText = "An error occurred during submission. Please try again.";
  }

/* const res = await fetch('/futsal_db.php?pushaction=saveUser', {
  method: 'POST',
  body: JSON.stringify(formData)
});

const text = await res.text();
console.log("Raw response:", text);

try {
  result = JSON.parse(text);
  console.log("Parsed JSON:", result);
} catch (error) {
  console.error("Server returned non-JSON or empty response:", error);
}
 */




      /* try {
  const res = await fetch('/futsal_db.php?pushaction=saveUser', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
  });
const text = await res.text();
console.log(text);
try {
  result = JSON.parse(text);
  console.log(result);
} catch (e) {
  console.error("Server returned non-JSON or empty response:", text,e);
  throw new Error("Invalid JSON response");
}
  /-* const result = await res.json();
  console.log(`Save user: ${result.message}`);
  alert(`Save user: ${result.message}`); *-/
} catch (error) {
  console.error("Error parsing JSON:", error);
  alert(`Unexpected server response. from save file\n${error}`);
} */
    });
      
 /* reg_btn.addEventListener("click", function () {
                            

                            if (!fname.trim() ||!lname.trim() || !door.trim() || !street.trim() || !province.trim() || !district.trim() || !city.trim() || !nic.trim() || !email.trim() || !password.trim() ||!phone.trim()) {
                                alert("Please enter all details!");
                                return;
                            }
                            

                            fetch("/futsal_db.php?pushaction=saveUser", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `phone=${phone}&name=${encodeURIComponent(name)}&address=${encodeURIComponent(address)}&nic=${encodeURIComponent(nic)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
                            })
                            .then(response => response.json())
                            .then(result => {
                                alert(result.message);
                                location.reload();
                            });
                        }); */
                      }
});