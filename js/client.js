
document.addEventListener('DOMContentLoaded', () => {
    fetch('client_dashboard_data.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.success) {
                document.getElementById('total-bookings').innerText = data.total_bookings;
                document.getElementById('top-customer').innerText = 
                    `${data.top_customer.fullname} (${data.top_customer.count} bookings)`;
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
});
