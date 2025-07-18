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
      window.location.href = './profile.php';
    } else if (selectedType === 'Customer') {
      location.reload();
    }
  } else {
    alert(`Error message: ${result.message}`);
    if (result.query) console.log("SQL Debug:", result.query);
  }
} catch (err) {
  alert(`Something went wrong: ${err.message}`);
}

  });


}

if (Register_popup){

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

      const formData = {
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
      };
console.log(JSON.stringify(formData));
      try {
  const res = await fetch('/futsal_db.php?pushaction=saveUser', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
  });

  const result = await res.json();
  alert(`Save user: ${result.message}`);
} catch (error) {
  console.error("Error parsing JSON:", error);
  alert("Unexpected server response.");
}
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