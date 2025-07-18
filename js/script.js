$(document).ready(function () {
    let selectedDate = null;
    let selectedLocation = null;
    let selectedFutsal = null;
    let selectedSlot = null;
    let selectedSlotArray = []
    let selectedCourt = null;
    const phoneInput = document.getElementById("phone");
    let booking_ids = []
    let totPayment = 0;
    let selectedCourtIndexes = [0, 1, 2];
    date_select = new Date(selectedDate);
    window.lastTrigger = null;
    let closeTrigger = null;
    
   const pitchCreateBtn = document.getElementById('createBtn_pitch')
    const editButton = document.getElementById("editPhone");
    let customer_id = null;
    const backButton = document.getElementById("back_p2");
    const confirmButton = document.getElementById("confirm_p2");
    const userDetailsDiv = document.getElementById("userDetails");
    const login_btn =   document.getElementById("login_btn");
    const register_btn = document.getElementById("register_btn");
    const logout_btn = document.getElementById("logout_btn");
    const profile_pg = document.getElementById("profile_pg");
    



    
    let dateSelected = new Date();
    let startDate = new Date();


if (document.getElementById("review_submit")){
document.getElementById("review_submit").addEventListener('click',function(){
    const pitch_id = document.getElementById("review_pitch_id").value;
    const review_rate = document.getElementById("review_rating").value;
    const logged_user = document.getElementById("logged_user").value;
    const comment = document.getElementById("comment").value;
    let body='';
    if (logged_user){
        body = `user_id=${document.getElementById("logged_user").value}&rate=${review_rate}&pitch_id=${pitch_id}&comment=${comment}`;
    }
    else{
        body = `user_id=0&rate=${review_rate}&pitch_id=${pitch_id}&comment=${comment}`;
    }

    fetch("/futsal_db.php?action=updatereview", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: body
                            })
                            .then(response => response.json())
                            .then(result => {
                                customer_id = result.review_id;
                                if (result.status){
                                    //customer_id =  `${customer_id}`;

                                
                                document.getElementById("popup").style.display="none";
                                
                                }
                                alert(`Update view: ${result.message}`);

                            });
    
});
}


getLocation()
    function fetchCourts() {
        fetch('/futsal_db.php?action=getCourtType')
    .then(response => response.text())  // Read as text first
    .then(text => {
        if (!text) {
            throw new Error("Empty response from server");
        }
        return JSON.parse(text);  // Parse JSON
    })
    .then(courtTypes => {
        if (!Array.isArray(courtTypes)) {
            throw new Error("Invalid JSON response");
        }
        const courtsContainer = document.getElementById("courtsList");

        
        if (courtsContainer) {

if (courtTypes.length === 1) {
    const court = courtTypes[0];
    courtsContainer.innerHTML = `<option value="${court}">${court}</option>`;
    courtsContainer.selectedIndex = 0;
    courtsContainer.dispatchEvent(new Event("change")); // this will trigger the bound event handler

        }
else{

            courtsContainer.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.textContent = "--Select Sport--";
            defaultOption.value = "";
            courtsContainer.appendChild(defaultOption);
            courtTypes.forEach(court => {
                const locOption = document.createElement("option");
                locOption.value = court;
                locOption.textContent = court;
                courtsContainer.appendChild(locOption);

            });
        }

            
        } else {
            console.error('court container not found');
        }
    })
    .catch(error => console.error('Error fetching courts:', error));
    }

    fetchCourts()

    function getLocByCourt(sport_type) {
        document.getElementById("cost_lst").style.display = 'none';
        document.querySelector(".scrollable-div").style.display = "none";
        fetch(`/futsal_db.php?action=getLocByCourt&court_type=${sport_type}`)
    .then(response => response.text())  // Read as text first
    .then(text => {
        if (!text) {
            throw new Error("Empty response from server");
        }
        return JSON.parse(text);  // Parse JSON
    })
    .then(locations => {
        if (!Array.isArray(locations)) {
            throw new Error("Invalid JSON response");
        }
        const locationsContainer = document.getElementById("locationsList");
        if (locationsContainer) {
            if (locations.length == 1){
                const location = locations[0];                
            locationsContainer.innerHTML = `<option value="${location}">${location}</option>`;
 getCourtDetails(location, selectedCourt);
        }
        else{
            locationsContainer.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.textContent = "--Select Location--";
            defaultOption.value = "";
            locationsContainer.appendChild(defaultOption);
            locations.forEach(location => {
                const locOption = document.createElement("option");
                locOption.value = location;
                locOption.textContent = location;
                locationsContainer.appendChild(locOption);
            });
        }
        } else {
            console.error('Locations container not found');
        }
        

    })
    .catch(error => console.error('Error fetching locations:', error));
    }






if (document.getElementById("courtsList")){
    const selectCourt = document.getElementById("courtsList");
    selectCourt.addEventListener("change", function () {
        const today = new Date();
        document.getElementById("date").disabled = false
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, '0');
const day = String(today.getDate()).padStart(2, '0');


document.getElementById("date").value = `${year}-${month}-${day}`;


        //document.getElementById("date").value = today.toISOString().split('T')[0];
        selectedDate = today
       
        selectedCourt = this.value
        getLocByCourt(this.value);
         if (selectedDate){
             document.getElementById("locationsList").disabled = false;
        }
    });}
if (document.getElementById("date")){
    
    document.getElementById("date").addEventListener("change", function () {
        selectedDate = new Date(this.value);
        selectedLocation = document.getElementById("locationsList")?.value;

        // Alert with both selected date and location
        //alert(`Location selected: ${selectedLocation}\nCourt selected: ${selectCourt}`);
        if (selectedLocation) {
            //document.getElementById("ttcost").textContent = "";
            getCourtDetails(selectedLocation,selectedCourt);
            document.getElementById("cost_lst").style.display = 'none';
            //document.querySelector(".submit-form button").style.display = "none";
        }
        
        document.getElementById("locationsList").disabled = false;
    });
}
    function showPage2() {
        document.getElementById("user_pg").style.display = "flex";
        document.getElementById('user_pg').classList.add('d-flex');
        document.getElementById('payment_pg').style.display = 'none';
        document.getElementById('payment_pg').classList.remove('d-flex');

    }

    function showPage1() {
        document.getElementById('user_pg').style.display = 'none';
        document.getElementById('user_pg').classList.remove('d-flex');
        document.getElementById('page1').style.display = 'flex';
        document.getElementById('payment_pg').style.display = 'none';
        document.getElementById('payment_pg').classList.remove('d-flex');
    }

    function showPage3() {
        document.getElementById('payment_model').style.display = 'block';

    }
    document.getElementById("card_payment").addEventListener('click', function(){
        const totCost = document.getElementById("total_amount").textContent;
        document.getElementById('user_pg').style.display = 'none';
        document.getElementById('user_pg').classList.remove('d-flex');
        //document.getElementById('page1').style.display = 'none';
        
        document.getElementById('totAmount').value = totCost;
        document.getElementById('user_pg').style.display = 'flex';
        //document.getElementById('payment_pg').classList.add('d-flex');
    });
    

document.getElementById("bank_transfer").addEventListener('click', function(){
document.getElementById('paymentMethod').style.display='none';
document.getElementById('bankDetails').style.display='block';
const nameSpan = document.createElement("p");
nameSpan.innerHTML = "M.M.M.Ahamed<br>88123456789<br>Commerial Bank<br>Nawalapitiya";
document.getElementById('bankDetails').appendChild(nameSpan);


});
    document.getElementById("btnPay").addEventListener('click', function (e) {

            e.preventDefault(); // Prevent form submission
        
            const form = document.getElementById("payment-form");

        
            if (form.checkValidity()) {
                const expInput = document.getElementById("expDate").value;
        const valid = validateExpiryDate(expInput);
        if (valid){
            const bstatus = "Confirmed"
  
                const fetchPromises = booking_ids.map(book_id => {
                    
        
                    return fetch("/futsal_db.php?action=updatebooking", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `id=${book_id}&status=${encodeURIComponent(bstatus)}`
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === "Success") {
                            
                        } else {
                            console.warn("Payment failed");
                            alert("Payment failed for one or more bookings.");
                            throw new Error("Payment failed");
                        }
                    });
                });
        
                Promise.all(fetchPromises)
                    .then(() => {
                        alert("All payments confirmed.");
                        document.getElementById('successMsg').style.display = 'block';
                        document.getElementById('payment_pg').style.display = 'none';
                        document.getElementById('payment_pg').classList.remove('d-flex');
                        location.reload();
                    })
                    .catch(error => {
                        console.error("Error during booking confirmation:", error);
                    });
        
            }
        } else {
                form.reportValidity();
            }
        });



       

        document.getElementById("btnPayLater").addEventListener('click', function () {
            const bstatus = "Payment Awaited"
  
            const fetchPromises = booking_ids.map(book_id => {
                
                return fetch("/futsal_db.php?action=updatebooking", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${book_id}&status=${bstatus}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === "Success") {
                        console.log("Payment confirmed");
                    } else {
                        console.warn("Payment failed");
                        alert("Payment failed for one or more bookings.");
                        throw new Error("Payment failed");
                    }
                });
            });
    
            Promise.all(fetchPromises)
                .then(() => {
                    alert("Do the payment later.");
                    document.getElementById('payment_pg').style.display = 'none';
                    document.getElementById('payment_pg').classList.remove('d-flex');
                    location.reload();
                })
                .catch(error => {
                    console.error("Error during booking confirmation:", error);
                }); 
        });
        document.getElementById("btnPayCancel").addEventListener('click', function () {

            const fetchPromises = booking_ids.map(book_id => {
                return fetch("/futsal_db.php?action=cancelBooking", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${book_id}`
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === "Success") {
                        console.log("Booking cancelled");
                        
                        location.reload();
                    } else {
                        console.warn("Booking cancel failed");
                       
                    }
                });
                
            });
            Promise.all(fetchPromises)
                    .then(() => {
                        alert("Bookings are Cancelled");
                        document.getElementById('successMsg').textContent = 'Bookings are cancelled'
                        document.getElementById('successMsg').style.display = 'block';
                        document.getElementById('payment_pg').style.display = 'none';
                        document.getElementById('payment_pg').classList.remove('d-flex');
                        location.reload();
                    })
                    .catch(error => {
                        console.error("Error during cancel booking:", error);
                    });
        
    
            
        });


        
    const selectElement = document.getElementById("locationsList");
    selectElement.addEventListener("change", function () {
        
        getCourtDetails(this.value, selectedCourt);
        document.getElementById("cost_lst").style.display = 'none';
        //fetchFutsalbyLoc(this.value);
        
    });

   
    function getCourtDetails(location,court_type) {
        fetch(`/futsal_db.php?action=getCourtDetails&location=${location}&court=${court_type}`)
        .then(response => response.json())
        .then(courtDetails => {
            document.getElementById("tt_hours").textContent = "0 Hour";
            document.getElementById("service_fee").textContent = `Rs. 0.00`;
            document.getElementById("total_amount").textContent = `Rs. 0.00`;
            document.getElementById("futsal_cost").textContent = `Rs. 0.00`;
            document.getElementById("discounts").textContent = `Rs. 0.00`;
            document.getElementById("cost_lst").style.display = 'none';
             const datediv = document.getElementById("date");
            const now = new Date();
            const currentHour = now.getHours(); 
            let cHour = currentHour.toString().padStart(2, '0') + ":00:00";
            
            const isdatetoday = isToday(selectedDate);
            let isCenterOpen = true;
            const date = new Date(selectedDate); // e.g., '2025-04-19'
            const day = date.getDay();

            
            
            if (isdatetoday) {
 if (day == 0 || day == 6) {
      isCenterOpen = courtDetails.some(c =>{
                //console.log(`Today: ${isdatetoday}\n isOpen: ${isCenterOpen}\nnowTime: ${cHour}\nisWeekend: Yes`)
                return parseInt(cHour)  >= parseInt(c.w_open_time) && parseInt(cHour)  < parseInt(c.w_close_time)});
            }
                else{
                      isCenterOpen = courtDetails.some(c =>{
                //console.log(`Today: ${isdatetoday}\n isOpen: ${isCenterOpen}\nnowTime: ${cHour}\nisWeekend: No`)
                return parseInt(cHour)  >= parseInt(c.open_time) && parseInt(cHour)  < parseInt(c.close_time)});
                }

                
             

            }
           

            if (isCenterOpen ) 
                {
let centerSts = false;
                 
                     const container = document.getElementById("futsalsList");
                            container.innerHTML = "";
const table = document.createElement("table");
const thead = document.createElement("thead");
                            thead.classList.add("bg-gray-50");
                            //const theadRow = document.createElement("tr");
                            const tbody = document.createElement("tbody");
                            tbody.classList.add("bg-white","divide-y","divide-gray-200");


/* const table = document.getElementById("compareTable");
      const thead = table.querySelector("thead");
      thead.classList.add("bg-gray-50");
      const tbody = document.getElementById("tableBody");
      tbody.classList.add("bg-white","divide-y","divide-gray-200"); */

      thead.innerHTML = "";
      tbody.innerHTML = "";

      // --- Dropdown Header (if more than 3 courts) ---
     
      
            if (courtDetails.length > 3) {
                 const headerRow = document.createElement("tr");
                const labelTh = document.createElement("th");
      labelTh.textContent = "Courts";
      headerRow.appendChild(labelTh);
        for (let i = 0; i < 3; i++) {
          const selectTh = document.createElement("th");
          const select = document.createElement("select");
          select.dataset.slot = i;

          courtDetails.forEach((court, idx) => {
            const option = document.createElement("option");
            option.value = idx;
            option.textContent = `${court.stadium_name} [${court.court_name}]`;
            if (selectedCourtIndexes[i] === idx) option.selected = true;
            if (selectedCourtIndexes.includes(idx) && selectedCourtIndexes[i] !== idx) {
              option.disabled = true;
            }
            select.appendChild(option);
          });

          select.addEventListener("change", (e) => {
            const slotIndex = parseInt(e.target.dataset.slot);
            const newValue = parseInt(e.target.value);
            if (selectedCourtIndexes.includes(newValue)) {
              alert("Court already selected. Choose another.");
              e.target.value = selectedCourtIndexes[slotIndex];
              return;
            }
            selectedCourtIndexes[slotIndex] = newValue;
            renderCompareTable();
          });

          selectTh.appendChild(select);
          headerRow.appendChild(selectTh);
          thead.appendChild(headerRow);
        }
      } else {
        selectedCourtIndexes = courtDetails.map((_, idx) => idx);
      }

      
const theadRow = document.createElement("tr");


                     const emptyTh = document.createElement("th");
                            emptyTh.classList.add("px-6","py-3","text-center","text-l","font-large","text-gray-500","uppercase","tracking-wider");
                            emptyTh.innerHTML = "TimeSlot";
                            theadRow.appendChild(emptyTh);
                    
                            

                            selectedCourtIndexes.forEach(index => {
                                const center = courtDetails[index];
                                if (!center) return; 
                                //console.log(center.stadium_name);
                                const th = document.createElement("th");
                                th.classList.add("px-6","py-3","text-center","text-l","font-large","text-gray-500","uppercase","tracking-wider");
                                const date = new Date(selectedDate); // e.g., '2025-04-19'
                                const day = date.getDay();

                                if (day == 0 || day == 6) {


                                th.innerHTML = `<span class="px-2 inline-block leading-5 rounded-3xl bg-red-100">${center.stadium_name}<br><span class="text-sm">[${center.court_name}]</span><br><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Off-Peak: ${center.weekend_offpeak_rate}/=</span>&nbsp;&nbsp;&nbsp;<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Peak: ${center.weekend_peak_rate}/=</span></span>`;
                                }
                                else{
                                    //console.log(center);
                                    th.innerHTML = `${center.stadium_name}<br><span class="text-sm">[${center.court_name}]</span><br><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Off-Peak: ${center.off_peak_rate}/=</span>&nbsp;&nbsp;&nbsp;<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Peak: ${center.peak_rate}/=</span>`;
                                    
                                }
                                
                                
                                theadRow.appendChild(th);
                            });

                            thead.appendChild(theadRow);
                            table.appendChild(thead);
                            for (let hour = 0; hour < 24; hour++) {
                                let timeSlot = hour.toString().padStart(2, '0') + ":00";
                                let openingHour = '';
                                let closingHour = '';
                                
                                // Check if any futsal center is open at this hour
                                let isAnyCenterOpen = courtDetails.some(center => {
                                    
                                const date = new Date(selectedDate); // e.g., '2025-04-19'
                                const day = date.getDay();
                                let isWeekendDifOpenClose = !!center.w_open_time;


                                      

                    //if (day == 0 || day == 6) 
                                    if(isWeekendDifOpenClose)
                                        {
                                            openingHour = parseInt(center.w_open_time.split(":")[0]);
                                            closingHour = parseInt(center.w_close_time.split(":")[0]);
                                        }
                                    else{
                                        openingHour = parseInt(center.open_time.split(":")[0]);
                                        closingHour = parseInt(center.close_time.split(":")[0]);
                                    }
                                    //console.log(`now hour: ${hour} , opening hour: ${openingHour} , closing hour: ${closingHour}`);
                                    return hour >= openingHour && hour <= closingHour;
                                });
                                
                                // Skip this hour if no centers are open
                                if (!isAnyCenterOpen)continue;
                                if (isPastTime(selectedDate, timeSlot)) {
                                    continue;
                                }
                                
                                const formattedDate = selectedDate.toISOString().split('T')[0];
                            
                                const row = document.createElement("tr");
                                row.classList.add("hover:bg-gray-50");
                                const timeTd = document.createElement("td");
                                timeTd.classList.add("px-6","py-4","whitespace-nowrap","text-sm","font-medium","text-gray-900");

                                timeTd.textContent = timeSlot;
                                row.appendChild(timeTd);
                            
                                selectedCourtIndexes.forEach(index => {
                                    const center = courtDetails[index];
                                    if (!center) return; 
                                    const td = document.createElement("td");
                                    let openingHour = parseInt(center.open_time.split(":")[0]);
                                    let closingHour = parseInt(center.close_time.split(":")[0]);
                                    let peakst = parseInt(center.peak_start.split(":")[0]);
                                    let peakend = parseInt(center.peak_end.split(":")[0]);
                                    

                                    const isnotPeak = hour >= peakst && hour < peakend;
                                    if(!isnotPeak){
                                        td.classList.add("bg-teal-800");
                                    };


                                    if (hour >= openingHour && hour <= closingHour) {
                                    
                                        centerSts = true;
                                        fetch(`/futsal_db.php?action=getbookingstatus&location=${location}&pitch=${center.court_name}&timeslot=${timeSlot}&date=${formattedDate}`)
                                        
                                        //fetch(`/futsal_db.php?action=getbookingstatus&location=${location}&court=${court_type}&timeslot=${timeSlot}&date=${formattedDate}`)
                                            .then(response => {
                                                // Check if the response is ok (status 200-299)
                                                if (!response.ok) {
                                                    throw new Error(`HTTP error! status: ${response.status}`);
                                                }
                                                return response.json();
                                            })
                                            .then(data => {          
                                                const status = data.status?.trim(); 
                                                if (status === "Confirmed"){
                                                    const confirmSpan = document.createElement("span")
                                                    confirmSpan.classList.add("wrap-confirm");
                                                    confirmSpan.textContent="Booked";
                                                    td.appendChild(confirmSpan);
                                                    
                                                    
                                                }
                                                else if (status === "Pending"){
                                                    
                                                    const pendingSpan = document.createElement("span")
                                                    pendingSpan.classList.add("wrap-pending");
                                                    pendingSpan.textContent="Pending";
                                                    td.appendChild(pendingSpan);
                                                    
                                                }
                                                else if (status === "Payment Awaited"){
                                                    
                                                    const awaitSpan = document.createElement("span")
                                                    awaitSpan.classList.add("wrap-awaited");
                                                    awaitSpan.textContent="Payment Awaited";
                                                    td.appendChild(awaitSpan);
                                                    
                                                }
                                                else {
                                                    
                                                        const checkbox = document.createElement("input");
                                                        checkbox.classList.add("select-toggle");
                                                        checkbox.type = "checkbox";
                                                        checkbox.value = `${center.stadium_name}-${timeSlot}-${center.court_name}`;
                                                        //checkbox.classList.add("time-slot-checkbox");
                                                        checkbox.dataset.center = center.court_name;
                                                        td.appendChild(checkbox);
                                            
                                                        
                                            
                                                        // Add event listener to manage selections
                                                        checkbox.addEventListener('change', () => {
                                                            
                                                            handleLocationSlotSelection(courtDetails, checkbox, container);
                                                            
                                                        });
                                                }
                                                            
                                            })
                                            .catch(error => console.error("Error fetching booking status:", error));
                                                        
                                                        
                                                    } 
                                    else 
                                        {
                                            td.classList.add("disabled");
                                            td.className = "bg-gray-400";
                                            td.disabled = true;
                                        }
                                    
                                    row.appendChild(td);
                                });
                            
                                tbody.appendChild(row);
                            }
                            

                            table.appendChild(tbody);
                            container.appendChild(table);      
                        

                            // Submit button setup
                            let submitDiv = document.querySelector(".submit-form");
                            submitDiv.innerHTML = "";
                            let submitButton = document.createElement("button");
                            submitButton.type = "submit";
                            submitButton.innerText = "Submit";
                            submitButton.classList.add("btn");
                            
                            submitButton.style.padding = "0.5% 2%";
                            submitButton.style.margin = "1%";
                            submitButton.style.fontSize = "1.2rem";
                            submitButton.className = "px-4 py-2 m-1.5 font-extrabold text-center text-base rounded-2xl bg-teal-600 text-green-500 hover:bg-green-500 hover:text-white disabled:bg-gray-500 disabled:text-white";
                            submitButton.style.display = "block";
                            submitButton.disabled = true;
                            
                            // Initially hidden
                            submitDiv.appendChild(submitButton);
                           
                                document.querySelector(".scrollable-div").style.display = "block"; 
                                document.getElementById("cost_lst").style.display = "block";
                           
                            
                            
                            // Event listener for submit button
                            submitButton.addEventListener("click", () => {
                                const selectedSlots = document.querySelectorAll(".select-toggle:checked");

                                //alert(`Selected date: ${document.getElementById("selectedDateInput").textContent}\nTotal hours selected: ${document.getElementById("hours").textContent}\nTotal Cost: ${document.getElementById("costOutput").textContent}`);
                                calculateTotalAmount(selectedSlots, courtDetails);
                                if (document.getElementById("logout_btn")){
                                    showPage3();
                                }
                                else{
                                    showPage2();
                                }
                                
                                
                            });

        }
        else    {
           

            document.getElementById("cost_lst").style.display = "none";
            document.getElementById("futsalsList").style.display = 'none';
            alert("No courts available at this time\nSelect another day");
            datediv.focus();
            
           
        }
        });
    }

    // Function to handle slot selection and disable other centers
    function handleLocationSlotSelection(futsalCenters, selectedCheckbox, container) {
        
        const selectedCenter = selectedCheckbox.dataset.center;
        const allCheckboxes = document.querySelectorAll(".select-toggle");

        // Get all selected checkboxes
        const selectedSlots = document.querySelectorAll(".select-toggle:checked");
        
        if (selectedSlots.length > 0) {
            const selectedSlots = document.querySelectorAll(".select-toggle:checked");
            //calculateTotalAmount(selectedSlots, futsalCenters);
            
            // Show submit button
            document.querySelector(".submit-form button").disabled = false;
            //document.querySelector(".submit-form button").style.display = "inline-block";
            calculateTotalAmount(selectedSlots, futsalCenters);
            // Disable other centers, but keep selected center active
            futsalCenters.forEach(center => {
                container.querySelectorAll(`input[data-center="${center.court_name}"]`).forEach(checkbox => {
                    
                    checkbox.disabled = center.court_name !== selectedCenter && selectedSlots.length > 0;

                });
            });
        } 
        else 
        {
           
            // Re-enable all checkboxes if no slots are selected
            allCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;

            });
            document.querySelector(".submit-form button").disabled = true;
            document.getElementById("ttcost").textContent = "";
            document.getElementById("tt_hours").textContent = "0 Hour";
            document.getElementById("service_fee").textContent = `Rs. 0.00`;
            document.getElementById("total_amount").textContent = `Rs. 0.00`;
            document.getElementById("futsal_cost").textContent = `Rs. 0.00`;
            document.getElementById("discounts").textContent = `Rs. 0.00`;
            // Hide submit button if no slot is selected
            //document.querySelector(".submit-form button").style.display = "none";
            
        }
    }

    // Function to calculate the total hours and amount
    function calculateTotalAmount(selectedSlots, futsalCenters) {
        let totalHours = selectedSlots.length;
        let totalAmount = 0;
        let firstItemCost = 0;
        let otherItemCost = 0; 
        let discounts = 0;
        let dis_type = '';
        selectedSlotArray = [];
        selectedSlots.forEach(slot => {
            const [centerName, timeSlot,courtType] = slot.value.split('-');
            const hour = parseInt(timeSlot.split(':')[0]);
            //alert(`selected center: ${center.name}\nCenter Name: ${centerName}`);
            // Get the center's peak/off-peak info

            const center = futsalCenters.find(center => center.stadium_name === centerName && center.court_name === courtType);

            //document.getElementById("fut_id").textContent = center.id;
            //alert(`futsal id: ${document.getElementById("fut_id").textContent}`);
            if (center) {
                
                const date = new Date(selectedDate); // e.g., '2025-04-19'
                const day = date.getDay();
                let firstItemCost = center?.initial_cost ?? 0;
let otherItemCost = center?.extra_cost ?? 0;
               
               if (firstItemCost == 0 && otherItemCost == 0) {}
                if(parseFloat(center.stadium_discount) > 0){
                    discounts = parseFloat(center.stadium_discount).toFixed(2);
                    dis_type = center.stadium_discount_type
                }
                else if(parseFloat(center.court_discount) > 0){
                    discounts = parseFloat(center.court_discount).toFixed(2);
                    dis_type = center.court_discount_type
                }

                const peakStart = parseInt(center.peak_start.split(':')[0]);
                const peakEnd = parseInt(center.peak_end.split(':')[0]);

                // Determine if the slot is peak or off-peak
                let amount =0;
                const isnotPeak = hour >= peakStart && hour < peakEnd;
                if (day===0 ||day===6){
                    amount = isnotPeak ? center.weekend_offpeak_rate : center.weekend_peak_rate;
                }
                else{
                amount = isnotPeak ? center.off_peak_rate : center.peak_rate;
                }

                totalAmount += parseFloat(amount);
                selectedSlotArray.push({
                stadium_name: centerName,
                pitch_name: courtType,
                booking_date: selectedDate.toISOString().split('T')[0],
                timeslot: timeSlot,  // Taking from selectedSlot array
                rate_applied: amount})
                
            }
        });
        let serviceCost = parseFloat(firstItemCost);
        
        let totalCost = 0;
        let extraItemcost = (totalHours - 1) * otherItemCost;
        if (totalHours > 0) {
            serviceCost +=  parseFloat(extraItemcost);
        } else {
            serviceCost = 0;
        }
        if (totalHours > 1) {
            document.getElementById("tt_hours").textContent = `${totalHours} Hours`;
        } else {
            document.getElementById("tt_hours").textContent = `${totalHours} Hour`;
        }
        
if (dis_type === 'percentage')
            {
discounts = totalAmount * (discounts/100);
        }


        totalCost = totalAmount + serviceCost - discounts;
        

        
        
        document.getElementById("discounts").textContent = `Rs. ${parseFloat(discounts).toFixed(2)}`;
        document.getElementById("service_fee").textContent = `Rs. ${serviceCost.toFixed(2)}`;
        document.getElementById("total_amount").textContent = `Rs. ${totalCost.toFixed(2)}`;

        // Set values in the respective HTML elements
        document.getElementById("futsal_cost").textContent = `Rs. ${totalAmount.toFixed(2)}`;

        //document.getElementById("ttcost").textContent = `Total Cost: Rs. ${totalAmount.toFixed(2)}`;
        //document.getElementById("costOutput").textContent = totalAmount.toFixed(2);
        document.getElementById("hours").textContent = totalHours;
        totPayment = totalAmount;
        
    }

    function isToday(date) {

        const today = new Date();
        //alert(`selected date: ${date.toDateString()}, Today: ${today.toDateString()}`);
        return date.toDateString() === today.toDateString();
    }

    function isPastTime(date, slot) {
        if (isToday(date)) {
            const [hour, minutes] = slot.split(":").map(Number);

            const now = new Date();
            //console.log(`Timeslot: ${slot}, Now: ${now.toTimeString()}, Hour: ${hour}, Minutes: ${minutes}\n ishour: ${now.getHours() > hour} ismin: ${(now.getHours() === hour && now.getMinutes() > minutes)}`);
            //alert(`is now gt: ${now.getHours() > hour}, if same hour gt mins ${now.getHours() === hour && now.getMinutes() > minutes}`);
            return now.getHours() > hour || (now.getHours() === hour && now.getMinutes() > minutes);
        }
    }

 
    phoneInput.addEventListener("input", function () {
        let phone = phoneInput.value;

        if (phone.length === 10) {
            phoneInput.disabled = true;
            editButton.style.display = "block";
            fetch(`/futsal_db.php?action=getphone&phoneNo=${phone}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        customer_id = `${data.id}`;
                        userDetailsDiv.innerHTML = `<p style="color: green;font-weight: bold;">Full name: ${data.name}<br>Address: ${data.address}<br>Email: ${data.Email}<br>NIC: ${data.NIC}<br></p>`;
                        confirmButton.style.display = "block";
                        backButton.style.display = "block";
                    } else {
                        window.lastTrigger = $('#user_pg')[0];
                        document.getElementById('user_pg').style.display = 'none';
                        document.getElementById('user_pg').classList.remove('d-flex');
                        document.getElementById("Register_popup").style.display = "flex";
                        document.getElementById('Register_popup').classList.add('d-flex');
                        /* userDetailsDiv.innerHTML = `
                            <p style="color: red; font-weight: bold;">New User! Enter details:</p>
                            <input type="text" id="fullname" placeholder="Full Name">
                            <input type="text" id="address" placeholder="Address">
							<input type="text" id="nic" placeholder="NIC">
							<input type="text" id="email" placeholder="email">
                            <br>
                            <button id="saveUser" disable>Save</button>
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
                            else{
                                document.getElementById("saveUser").disabled = false;
                            }
                            

                            fetch("/futsal_db.php?action=saveUser", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `phone=${phone}&name=${encodeURIComponent(name)}&address=${encodeURIComponent(address)}&nic=${encodeURIComponent(nic)}&email=${encodeURIComponent(email)}`
                            })
                            .then(response => response.json())
                            .then(result => {
                                customer_id = result.cus_id;
                                if (result.status){
                                    //customer_id =  `${customer_id}`;
                                    
                                
                                const el = document.getElementById("cusId");
                                if (el) {
                                el.textContent = `${result.cus_id}`;
                                document.getElementById("saveUser").style.display="none";
                                confirmButton.style.display = "block";
                                backButton.style.display = "block";
                                }
                                
                                }
                                alert(`Save User: ${result.message}`);

                            });

                            
                        }); */
                    }

                })
                .catch(error => console.error("Error:", error));
        }
    });

    confirmButton.addEventListener("click", () => {


        
        const totCost = document.getElementById("total_amount").textContent;
        
        payment.amount = totCost;
        payhere.startPayment(payment);
/* 

        const fetchPromises = selectedSlotArray.map(Slot => {
            Slot.cus_id = customer_id;
            Slot.status = "Pending";
    
            return fetch("/futsal_db.php?action=createBooking", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `stadium_name=${encodeURIComponent(Slot.stadium_name)}&pitch_name=${encodeURIComponent(Slot.pitch_name)}&booking_date=${Slot.booking_date}&timeslot=${Slot.timeslot}&rate_applied=${Slot.rate_applied}&cus_id=${Slot.cus_id}&status=${encodeURIComponent(Slot.status)}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === "Success") {
                    booking_ids.push(result.Booking_id);
                } else {
                    alert(`Booking failed for ${Slot.timeslot}: ` + result.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
        });
    
        Promise.all(fetchPromises).then(() => {
            if (booking_ids.length > 0) {
                console.log(`booking length: ${booking_ids.length}`);
                showPage3();
            } else {
                alert("No bookings were confirmed.");
            }
        }); */
    });
    
    backButton.addEventListener("click", function () {
        showPage1();                     
    });
    if(login_btn){
        
    login_btn.addEventListener("click", function(){
document.getElementById("login_popup").style.display = "flex";
        document.getElementById('login_popup').classList.add('d-flex');
    });
}

if(register_btn){
register_btn.addEventListener("click", function(){
    lastTrigger = $('#register_btn')[0];
    console.log(lastTrigger);
document.getElementById("Register_popup").style.display = "flex";
        document.getElementById('Register_popup').classList.add('d-flex');
    });
}
if(logout_btn){
    logout_btn.addEventListener("click", function(){
        window.location.href = './logout.php';
    });}
if(profile_pg){
    profile_pg.addEventListener("click",function(){
         window.location.href = './profile.php';

    });}
    editButton.addEventListener("click", function () {
        phoneInput.disabled = false;
        phoneInput.value = "";
        phoneInput.focus();
        editButton.style.display = "none";
        userDetailsDiv.innerHTML = "";
        confirmButton.style.display = "none";
    });

    /* 

    const payment = {
      sandbox: true,
      merchant_id: "1230218", // Replace with your Merchant ID
      return_url: "https://adabooking.lk/return.php",
      cancel_url: "https://adabooking.lk/cancel.php",
      notify_url: "https://adabooking.lk/notify.php",
      order_id: "ORDER_" + new Date().getTime(),
      items: "Test Item", // use dynamic amount
      currency: "LKR",
      first_name: "John",
      last_name: "Doe",
      email: "john@example.com",
      phone: "0771234567",
      address: "123 Main Street",
      city: "Colombo",
      country: "Sri Lanka",
    };
  
    payhere.onCompleted = function (orderId) {
      alert("Payment completed: " + orderId);
    };
  
    payhere.onDismissed = function () {
      alert("Payment dismissed");
    };
  
    payhere.onError = function (error) {
      alert("Error: " + error);
    };
 */

/*
    // Payment completed. It can be a successful failure.
    payhere.onCompleted = function onCompleted(orderId) {
        console.log("Payment completed. OrderID:" + orderId);
        // Note: validate the payment and show success or failure page to the customer
    };

    // Payment window closed
    payhere.onDismissed = function onDismissed() {
        // Note: Prompt user to pay again or show an error page
        console.log("Payment dismissed");
    };

    // Error occurred
    payhere.onError = function onError(error) {
        // Note: show an error page
        console.log("Error:"  + error);
    };

    // Put the payment variables here
    var payment = {
        "sandbox": true,
        "merchant_id": "1230218",    // Replace your Merchant ID
        "return_url": "https://adabooking.lk/return.php",     // Important
        "cancel_url": "https://adabooking.lk/cancel.php",     // Important
        "notify_url": "https://adabooking.lk/notify.php",
        "order_id": "ORDER_" + new Date().getTime(),
        "items": "Door bell wireles",
        "currency": "LKR",
        "hash": "45D3CBA93E9F2189BD630ADFE19AA6DC", // *Replace with generated hash retrieved from backend
        "first_name": "Saman",
        "last_name": "Perera",
        "email": "samanp@gmail.com",
        "phone": "0771234567",
        "address": "No.1, Galle Road",
        "city": "Colombo",
        "country": "Sri Lanka",
        "delivery_address": "No. 46, Galle road, Kalutara South",
        "delivery_city": "Kalutara",
        "delivery_country": "Sri Lanka",
        "custom_1": "",
        "custom_2": ""
    };
*/
    // Show the payhere.js popup, when "PayHere Pay" is clicked
    document.getElementById('confirm_p2').onclick = function (e) {
        const totCost = document.getElementById("total_amount").textContent;
        payment["amount"] =totCost;
        payhere.startPayment(payment);
    };










/*     window.payNow = function () {
        console.log("Amount: " + totCost);
        
        console.log("Paying: " + JSON.stringify(payment));
        
        payhere.startPayment(payment);
    }; */


   

    
});

 function closeModal(div){

        if (div){
            document.getElementById(div).style.display = 'none';
            document.getElementById(div).classList.remove('d-flex');

        }
        console.log(window.lastTrigger);
        if (window.lastTrigger && typeof window.lastTrigger.scrollIntoView === 'function') {
            window.lastTrigger.scrollIntoView({ behavior: "smooth", block: "center" });
            window.lastTrigger.style.display = 'flex';
            window.lastTrigger.classList.add('d-flex');
        }
        else {
    console.warn("lastTriggerElement is not set or invalid.");
  }
        /* document.getElementById("payment_model").style.display = 'none';
    document.querySelector('.fixed.inset-0').style.display = 'none';
        document.getElementById('user_pg').style.display = 'none';
        document.getElementById('user_pg').classList.remove('d-flex');
        document.getElementById('payment_pg').style.display = 'none';
        document.getElementById('payment_pg').classList.remove('d-flex');
        document.getElementById('login_popup').style.display = 'none';
        document.getElementById('login_popup').classList.remove('d-flex');
        document.getElementById('Register_popup').style.display = 'none';
        document.getElementById('Register_popup').classList.remove('d-flex'); */
    }
