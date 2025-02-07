<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/summary.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php';?>

<div class="container">
    <div class="summary-card">
        <h2>Order Completion Summary</h2>
        
        <div class="info-box">
            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_id ?? 'ORDXXX'); ?></p>
            <p><strong>Amount Earned:</strong> $<?php echo number_format($amount_earned ?? 100.00, 2); ?></p>
            <p><strong>Deductions:</strong> -$<?php echo number_format($deductions ?? 0.00, 2); ?></p>
            <hr>
            <p class="total"><strong>Updated Total Earnings:</strong> $<?php echo number_format($total_earnings ?? 600.75, 2); ?></p>
        </div>

        <div class="button-group">
            <a href="<?php echo URLROOT; ?>/earnings" class="btn">View Earnings</a>
            <a href="<?php echo URLROOT; ?>/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
