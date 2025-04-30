<script>
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const earningsChart = new Chart(ctx, {
        type: 'bar', // Changed from 'line' to 'bar'
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Earnings ($)',
                data: <?= json_encode(array_values($data['monthlyTrend'])) ?>,
                backgroundColor: 'rgba(5, 61, 48, 0.6)', // Slightly more opaque for better bar visibility
                borderRadius: 4 // Optional: gives bars rounded corners
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true // Ensures bars start at zero
                }
            }
        }
    });
</script>
