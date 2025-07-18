$(document).ready(function () {
	const login_btn =   document.getElementById("login_btn");
    const register_btn = document.getElementById("register_btn");
    const logout_btn = document.getElementById("logout_btn");
	if(login_btn){
	  login_btn.addEventListener("click", function(){
document.getElementById("login_popup").style.display = "flex";
        document.getElementById('login_popup').classList.add('d-flex');
    });}
	if(logout_btn){
	
	logout_btn.addEventListener("click", function(){
		alert("logout page");
        window.location.href = './logout.php';
    });}
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
    const body =`username=${encodeURIComponent(username.value)}&password=${encodeURIComponent(password.value)}&logintype=${encodeURIComponent(selectedType)}`

    try{
        
    const response = await fetch('/futsal_db.php?action=login', {
      method: 'POST',
      headers: { "Content-Type": "application/x-www-form-urlencoded"},
      body: body
    });

    const result = await response.json();
    if (`Result: ${result.success}`) {
      
      if(selectedType === 'Client'){
      window.location.href ='./profile.php';
    }
      else if (selectedType === 'Customer'){      
        location.reload();
      }

      
  } else {
      alert(result.message);
  }
} catch (err) {
  alert("Something went wrong.");
}
  });


}

if (Register_popup){

const name = document.getElementById("reg_name").value;
  const address = document.getElementById("reg_add").value;
							const nic = document.getElementById("reg_nic").value;
							const email = document.getElementById("reg_email").value;
              const phone = document.getElementById("reg_phone").value;
              const reg_btn = document.getElementById("reg_submit");
  
 reg_btn.addEventListener("click", function () {
                            

                            if (!name.trim() || !address.trim() || !nic.trim() || !email.trim()) {
                                alert("Please enter all details!");
                                return;
                            }
                            

                            fetch("/futsal_db.php?pushaction=saveUser", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `phone=${phone}&name=${encodeURIComponent(name)}&address=${encodeURIComponent(address)}&nic=${encodeURIComponent(nic)}&email=${encodeURIComponent(email)}`
                            })
                            .then(response => response.json())
                            .then(result => {
                                alert(`Result it: ${result.message}`);
                                location.reload();
                            });
                        });
                      }
});