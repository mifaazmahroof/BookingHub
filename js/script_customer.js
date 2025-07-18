document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phone");
    const editButton = document.getElementById("editPhone");
    const userDetailsDiv = document.getElementById("userDetails");

    phoneInput.addEventListener("input", function () {
        let phone = phoneInput.value;

        if (phone.length === 10) {
            phoneInput.disabled = true;
            editButton.style.display = "block";
            fetch(`/futsal_db.php?action=getphone&phoneNo=${phone}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        userDetailsDiv.innerHTML = `<p class="found">Full name: ${data.name}<br>Address: ${data.address}<br>Email: ${data.Email}<br>NIC: ${data.NIC}<br></p>`;
                    } else {
                        userDetailsDiv.innerHTML = `
                            <p class="not-found">New User! Enter details:</p>
                            <input type="text" id="fullname" placeholder="Full Name">
                            <input type="text" id="address" placeholder="Address">
							<input type="text" id="nic" placeholder="NIC">
							<input type="text" id="email" placeholder="email"><br>
                            <button id="saveUser">Save</button>
                        `;

                        document.getElementById("saveUser").addEventListener("click", function () {
                            let name = document.getElementById("fullname").value;
                            let address = document.getElementById("address").value;
							let nic = document.getElementById("nic").value;
							let email = document.getElementById("email").value;

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
                                alert(result.message);
                                location.reload();
                            });
                        });
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    });

    editButton.addEventListener("click", function () {
        phoneInput.disabled = false;
        phoneInput.value = "";
        phoneInput.focus();
        editButton.style.display = "none";
        userDetailsDiv.innerHTML = "";
    });
});
