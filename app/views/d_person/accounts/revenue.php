<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/revenue.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="container">
    <h2>Earnings Dashboard</h2>

    <!-- Total Earnings Summary -->
    <div class="summary-box">
        <h3>Total Earnings: $<?php echo number_format(540.75, 2); ?></h3>
    </div>

    <!-- Earnings Filter Form -->
    <form action="#" method="POST">
        <label for="date">Date:</label>
        <input type="date" name="date" id="date">
        
        <label for="order_id">Order ID:</label>
        <input type="text" name="order_id" id="order_id" placeholder="Enter Order ID">
        
        <button type="submit">Filter</button>
    </form>

    <!-- Earnings Breakdown Table -->
    <table border="1" cellpadding="10">
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

<?php require APPROOT . '/views/inc/footer.php'; ?>   
