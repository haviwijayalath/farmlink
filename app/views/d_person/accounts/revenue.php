<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/revenue.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="container">
    <h2>Earnings Dashboard</h2>

    <!-- Earnings Summary Boxes with Modified Layout -->
    <div class="summary-container">
        <div class="summary-box">
            <h3>Total Earnings (This Month)</h3>
            <p>$<?php echo number_format(540.75, 2); ?></p>
        </div>
        <div class="summary-box">
            <h3>Last Month</h3>
            <p>$<?php echo number_format(480.30, 2); ?></p>
        </div>
    </div>

    <div class="summary-chart-container">
        <div class="summary-box">
            <h3>Yearly Earnings</h3>
            <p>$<?php echo number_format(6200.50, 2); ?></p>
        </div>
        <div class="chart-box">
            <canvas id="earningsChart"></canvas>
        </div>
    </div>

    <!-- Earnings Filter Form -->
    <form action="#" method="POST" class="filter-form">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date">
        
        <label for="order_id">Order ID:</label>
        <input type="text" name="order_id" id="order_id" placeholder="Enter Order ID">
        
        <button type="submit">Filter</button>
    </form>

    <!-- Earnings Breakdown Table -->
    <table class="earnings-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Amount ($)</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dummyEarnings = [
                ["order_id" => "ORD123", "amount" => 120.50, "status" => "Paid", "payment_date" => "2024-01-05"],
                ["order_id" => "ORD124", "amount" => 95.25, "status" => "Paid", "payment_date" => "2024-01-10"],
                ["order_id" => "ORD125", "amount" => 150.00, "status" => "Pending", "payment_date" => "-"],
                ["order_id" => "ORD126", "amount" => 175.00, "status" => "Paid", "payment_date" => "2024-01-15"],
            ];

            foreach ($dummyEarnings as $earning): ?>
                <tr>
                    <td><?php echo htmlspecialchars($earning['order_id']); ?></td>
                    <td><?php echo number_format($earning['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($earning['status']); ?></td>
                    <td><?php echo htmlspecialchars($earning['payment_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Chart.js Graph - Monthly Earnings Trend (Smaller Chart)
const ctx = document.getElementById('earningsChart').getContext('2d');
const earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Earnings ($)',
            data: [1200, 1400, 1800, 1600, 2200, 2500, 2800, 2900, 3000, 3500, 3800, 4000], // Dummy Data
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Allow custom sizing
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
