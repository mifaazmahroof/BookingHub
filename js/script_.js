$(document).ready(function () {
    console.log("script.js is loaded");
    let selectedDate = null;
    let selectedLocation = null;
    let selectedFutsal = null;
    let selectedSlot = null;
    let selectedSlotArray = []
    let selectedCourt = null;
    const phoneInput = document.getElementById("phone");
    let booking_ids = []
    let totPayment = 0;
   
    const editButton = document.getElementById("editPhone");
    let customer_id = null;
    const backButton = document.getElementById("back_p2");
    const confirmButton = document.getElementById("confirm_p2");
    const userDetailsDiv = document.getElementById("userDetails");
    



    
    let dateSelected = new Date();
    let startDate = new Date();

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
            courtsContainer.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.textContent = "-- Select Sport --";
            defaultOption.value = "";
            courtsContainer.appendChild(defaultOption);
            courtTypes.forEach(court => {
                const locOption = document.createElement("option");
                locOption.value = court;
                locOption.textContent = court;
                courtsContainer.appendChild(locOption);

            });
           
            
        } else {
            console.error('court container not found');
        }
    })
    .catch(error => console.error('Error fetching courts:', error));
    }

    fetchCourts()
    const selectCourt = document.getElementById("courtsList");
    selectCourt.addEventListener("change", function () {
        selectedCourt = this.value
        document.getElementById("date").disabled = false;
        getLocByCourt(this.value);
    });

    document.getElementById("date").addEventListener("change", function () {
        selectedDate = new Date(this.value);
        selectedLocation = document.getElementById("locationsList")?.value;

        // Alert with both selected date and location
        //alert(`Location selected: ${selectedLocation}\nCourt selected: ${selectCourt}`);
        if (selectedLocation) {
            document.getElementById("ttcost").textContent = "";
            getCourtDetails(selectedLocation,selectedCourt);
            document.querySelector(".submit-form button").style.display = "none";
            

        }
        
        document.getElementById("locationsList").disabled = false;
    })

    function getLocByCourt(sport_type) {
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
        } else {
            console.error('Locations container not found');
        }
    })
    .catch(error => console.error('Error fetching locations:', error));
    }
    const selectElement = document.getElementById("locationsList");
    selectElement.addEventListener("change", function () {
        getCourtDetails(this.value, selectedCourt);
        //fetchFutsalbyLoc(this.value);
        document.querySelector(".scrollable-div").style.display = "block"; 
    });

    

    function getCourtDetails(location,court_type) {
        fetch(`/futsal_db.php?action=getCourtDetails&location=${location}&court=${court_type}`)
        .then(response => response.json())
        .then(courtDetails => {
            const container = document.getElementById("futsalsList");
            container.innerHTML = "";

            const table = document.createElement("table");
            const thead = document.createElement("thead");
            const theadRow = document.createElement("tr");
            const tbody = document.createElement("tbody");
            table.classList.add(".scrollable-table");
            const emptyTh = document.createElement("th");
            theadRow.appendChild(emptyTh);

            courtDetails.forEach(center => {
                const th = document.createElement("th");
                const date = new Date(selectedDate); // e.g., '2025-04-19'
                const day = date.getDay(); 

                if (day == 0 || day == 6) {


                th.innerHTML = `${center.stadium_name} [${center.court_name}]<br>Off-Peak:&nbsp;&nbsp;${center.weekend_offpeak_rate}/=<br>Peak:&nbsp;&nbsp;${center.weekend_peak_rate}/=`;
                }
                else{
                    th.innerHTML = `${center.stadium_name} [${center.court_name}]<br>Off-Peak:&nbsp;&nbsp;${center.off_peak_rate}/=<br>Peak:&nbsp;&nbsp;${center.peak_rate}/=`;
                    
                }
                
                
                theadRow.appendChild(th);
            });

            thead.appendChild(theadRow);
            table.appendChild(thead);
            for (let hour = 0; hour < 24; hour++) {
                let timeSlot = hour.toString().padStart(2, '0') + ":00";
                
                // Check if any futsal center is open at this hour
                let isAnyCenterOpen = courtDetails.some(center => {
                    let openingHour = parseInt(center.open_time.split(":")[0]);
                    let closingHour = parseInt(center.close_time.split(":")[0]);
                    return hour >= openingHour && hour < closingHour;
                });
            
                // Skip this hour if no centers are open
                if (!isAnyCenterOpen) continue;
                if (isPastTime(selectedDate, timeSlot)) {
                    continue;
                }

               
                const formattedDate = selectedDate.toISOString().split('T')[0];
            
                const row = document.createElement("tr");
                const timeTd = document.createElement("td");
                timeTd.textContent = timeSlot;
                row.appendChild(timeTd);
            
                courtDetails.forEach(center => {
                    const td = document.createElement("td");
                    let openingHour = parseInt(center.open_time.split(":")[0]);
                    let closingHour = parseInt(center.close_time.split(":")[0]);
                    let peakst = parseInt(center.peak_start.split(":")[0]);
                    let peakend = parseInt(center.peak_end.split(":")[0]);
                    

                    const isnotPeak = hour >= peakst && hour < peakend;
                    if(!isnotPeak){
                        td.classList.add("peak")
                    };


                    
                    /* getbookingstatus(location,court_type,timeSlot)
    .then(status => console.log("Booking Status:", status))
    .catch(error => console.error("Error:", error)); */

    if (hour >= openingHour && hour < closingHour) {
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
                        checkbox.type = "checkbox";
                        checkbox.value = `${center.stadium_name}-${timeSlot}-${center.court_name}`;
                        checkbox.classList.add("time-slot-checkbox");
                        checkbox.dataset.center = center.court_name;
                        td.appendChild(checkbox);
            
                        
            
                        // Add event listener to manage selections
                        checkbox.addEventListener('change', () => {
                            
                            handleLocationSlotSelection(courtDetails, checkbox, container);
                            
                        });
                }
                             
            })
            .catch(error => console.error("Error fetching booking status:", error));
                        
                        
                    } else {
                        td.classList.add("disabled");
                    }
                    
                    row.appendChild(td);
                });
            
                tbody.appendChild(row);
            }
            

            table.appendChild(tbody);
            container.appendChild(table);      
           

            // Submit button setup
            let submitDiv = document.querySelector(".submit-form");
            let submitButton = document.createElement("button");
            submitButton.type = "submit";
            submitButton.innerText = "Submit";
            submitButton.style.padding = "10px 20px";
            submitButton.style.fontSize = "16px";
            submitButton.style.display = "none";
             // Initially hidden
            submitDiv.appendChild(submitButton);
            

            // Event listener for submit button
            submitButton.addEventListener("click", () => {
                const selectedSlots = document.querySelectorAll(".time-slot-checkbox:checked");

                //alert(`Selected date: ${document.getElementById("selectedDateInput").textContent}\nTotal hours selected: ${document.getElementById("hours").textContent}\nTotal Cost: ${document.getElementById("costOutput").textContent}`);
                calculateTotalAmount(selectedSlots, courtDetails);
                showPage2();
                
            });
        });
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
            //alert(`is now gt: ${now.getHours() > hour}, if same hour gt mins ${now.getHours() === hour && now.getMinutes() > minutes}`);
            return now.getHours() > hour || (now.getHours() === hour && now.getMinutes() > minutes);
        }
    }
    function handleLocationSlotSelection(futsalCenters, selectedCheckbox, container) {
        const selectedCenter = selectedCheckbox.dataset.center;
        const allCheckboxes = document.querySelectorAll(".time-slot-checkbox");

        // Get all selected checkboxes
        const selectedSlots = document.querySelectorAll(".time-slot-checkbox:checked");
        
        if (selectedSlots.length > 0) {
            const selectedSlots = document.querySelectorAll(".time-slot-checkbox:checked");
            //calculateTotalAmount(selectedSlots, futsalCenters);
            
            // Show submit button
            document.querySelector(".submit-form button").style.display = "inline-block";
            calculateTotalAmount(selectedSlots, futsalCenters);
            // Disable other centers, but keep selected center active
            futsalCenters.forEach(center => {
                container.querySelectorAll(`input[data-center="${center.court_name}"]`).forEach(checkbox => {
                    checkbox.disabled = center.court_name !== selectedCenter && selectedSlots.length > 0;
                });
            });
        } else {
            document.getElementById("ttcost").textContent = "";
            // Re-enable all checkboxes if no slots are selected
            allCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;

            });

            // Hide submit button if no slot is selected
            document.querySelector(".submit-form button").style.display = "none";
        }
    }

    // Function to calculate the total hours and amount
    function calculateTotalAmount(selectedSlots, futsalCenters) {
        let totalHours = selectedSlots.length;
        let totalAmount = 0;
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

        // Set values in the respective HTML elements
        document.getElementById("ttcost").textContent = `Total Cost: Rs. ${totalAmount.toFixed(2)}`;
        document.getElementById("costOutput").textContent = totalAmount.toFixed(2);
        document.getElementById("hours").textContent = totalHours;
        totPayment = totalAmount;
        
    }

    function showPage2() {
        document.getElementById("user_pg").style.display = "flex";
        document.getElementById('user_pg').classList.add('d-flex');
        document.getElementById('payment_pg').style.display = 'none';
        document.getElementById('payment_pg').classList.remove('d-flex');

    }
  
});