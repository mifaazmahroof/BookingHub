function renderBookingPieChart(chartId, labels, data, status) {
  const canvas = document.getElementById(chartId);
  canvas.width = 500;
  canvas.height = 500;

  const ctx = canvas.getContext('2d');
  new Chart(ctx, {
      type: 'pie',
      data: {
          labels: labels.map((label, i) => `${label} (${status[i]})`),
          datasets: [{
              data: data,
              backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#E91E63', '#9C27B0', '#00BCD4']
          }]
      },
      options: {
          responsive: false,
          plugins: {
              legend: {
                  position: 'bottom'
              },
              tooltip: {
                  callbacks: {
                      label: function(context) {
                          const index = context.dataIndex;
                          return `${labels[index]} (${status[index]}): ${data[index]}`;
                      }
                  }
              }
          }
      }
  });
}

function renderStadiumBookingPieChart(chartId, labels, data, status, type) {
    const canvas = document.getElementById(chartId);
    canvas.width = 500;
    canvas.height = 500;

    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels.map((label, i) => `${label} (${status[i]}, ${type[i]})`),
            datasets: [{
                data: data,
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#E91E63', '#9C27B0', '#00BCD4']
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            return `${labels[index]} (${status[index]}, ${type[index]}): ${data[index]}`;
                        }
                    }
                }
            }
        }
    });
}

/* function renderBookingTable(bookings) {
    const tbody = document.querySelector('#bookingTable tbody');
    tbody.innerHTML = ''; // Clear previous content
    bookings.forEach(b => {
        const rowClass = b.status === 'Pending' 
        ? 'pending' 
        : b.status === 'Payment Awaited' 
          ? 'awaited' 
          : '';

      const row = `
          <tr class="${rowClass}">
              <td>${b.booking_id}</td>
              <td>${b.pitch_name}</td>
              <td>${b.created_at}</td>
              <td>${b.timeslot}</td>
              <td>${b.booking_date}</td>
              <td>${b.rate_applied}</td>
              <td>${b.status}</td>
          </tr>
      `;
      tbody.insertAdjacentHTML('beforeend', row);
    });
  } */