    <!-- Footer -->
  <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <div class="text-2xl font-bold mb-4 flex items-center">
                        <i class="fas fa-futbol mr-2 text-teal-400"></i>
                        <span>FutsalSL</span>
                    </div>
                    <p class="text-gray-400 mb-6">The #1 platform for futsal ground bookings in Sri Lanka.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-teal-400 text-lg"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-teal-400 text-lg"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-teal-400 text-lg"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-teal-400 text-lg"><i class="fab fa-youtube"></i></a>
                    </div>
                    <p id="UserLocation"></p>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-6 text-teal-400">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Home</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Find Grounds</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">My Bookings</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Favorites</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Add Your Ground</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-6 text-teal-400">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">FAQs</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-all">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-bold mb-6 text-teal-400">Contact Info</h3>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-teal-400"></i>
                            <span>123 Sports Street, Colombo 03, Sri Lanka</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-teal-400"></i>
                            <span>+94 11 123 4567</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-teal-400"></i>
                            <span>info@futsalsl.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
                <p>&copy; 2025 FutsalSL. All rights reserved.</p>
            </div>
        </div>
    </footer>

<script>
                    

const userLocation = document.getElementById("UserLocation");

function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError, {
          enableHighAccuracy: true, // Use GPS if available
          timeout: 10000, // Max wait time (10 seconds)
          maximumAge: 0 // Don't use cached position
        });
      } else {
        userLocation.innerText = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      const accuracy = position.coords.accuracy;
      userLocation.innerHTML = `La: ${lat}<br>Lo: ${lon}`;
      findCity(lat, lon)
    }

    function showError(error) {
      switch(error.code) {
        case error.PERMISSION_DENIED:
          alert("User denied the request for Geolocation.");
          break;
        case error.POSITION_UNAVAILABLE:
          alert("Location information is unavailable.");
          break;
        case error.TIMEOUT:
          alert("The request to get user location timed out.");
          break;
        case error.UNKNOWN_ERROR:
          alert("An unknown error occurred.");
          break;
      }
    }

    function findCity(lat, lon) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`;
        
  fetch(url)
    .then(response => response.json())
    .then(data => {
      const city = data.address.city;
      const town = data.address.town;
      const village = data.address.village;
      const country = data.address.country;
      const poCode = data.address.postcode;
      userLocation.innerHTML = `You're in ${town}, ${country}`;
      
    })

    .catch(error => console.error('Error:', error));
}
                </script>
				</body>
                
                </html>