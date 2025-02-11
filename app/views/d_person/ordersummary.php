<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/summary.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php';?>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("summaryModal").style.display = "block";
        });

        function closeModal() {
            document.getElementById("summaryModal").style.display = "none";
            window.location.href = "delivery_dashboard.php"; // Redirect to dashboard
        }
</script>

<div id="summaryModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">×</span>
        <h2>Delivery Completed!</h2>
        <div class="summary-box"><strong>Order ID:</strong> <?php echo $_SESSION['order_id']; ?></div>
        <div class="summary-box"><strong>Earned:</strong> $<?php echo number_format($summary['amount'], 2); ?></div>
        <div class="summary-box"><strong>Total Earnings:</strong> $<?php echo number_format($summary['totearnings'], 2); ?></div>
        <button class="confirm-btn" onclick="closeModal()">OK</button>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
