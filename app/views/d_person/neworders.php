<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/neworder.css">
<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="neworder-container">
    <h2>New Orders</h2>
    <table class="neworders-table">
        <thead>
            <tr>
                <th>Order_ID</th>
                <th>Pick-Up</th>
                <th>Drop-Off</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['orders'])): ?>
                <?php foreach($data['orders'] as $index => $order): ?>
                    <tr>
                        <td>
                            <a href="<?= URLROOT ?>/dpersons/orderdetails/<?= htmlspecialchars($order->orderID) ?>" class="newbtn newbtn-delete">
                                <?= htmlspecialchars($order->orderID) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($order->pickup_address) ?></td>
                        <td><?= htmlspecialchars($order->dropoff_address) ?></td>
                        <td><?= htmlspecialchars($order->quantity) ?></td>
                        <td>
                            <a href="#" 
                               class="newbtn newbtn-confirm" 
                               data-url="<?= URLROOT ?>/dpersons/confirmNewOrder/<?= htmlspecialchars($order->orderID) ?>" 
                               onclick="showPopup2(this)">Confirm</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No new orders available for your area.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="popup-container" id="popupContainer" style="display: none;">
    <div class="popup-content">
        <span class="close" onclick="closePopup2()">&times;</span>
        <div class="popup-header">
            <h2>Confirm Delivery</h2>
        </div>
        <div class="popup-body">
            <p>Are you sure you want to confirm this order for delivery?</p>
        </div>
        <div class="popup-footer">
            <button class="btn-primary" id="confirmBtn">Confirm</button>
            <button class="btn-danger" onclick="closePopup2()">Cancel</button>
        </div>
    </div>
</div>

<script>
    let confirmUrl = "";

    // Show the popup when the confirm button is clicked
    function showPopup2(element) {
        confirmUrl = element.getAttribute('data-url'); // Get the URL from the clicked element
        const popupContainer = document.getElementById('popupContainer');
        popupContainer.style.display = 'block'; // Show the popup
        document.body.classList.add('blurred'); // Add blur class to body

        // Attach the confirm button action dynamically
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.onclick = () => {
            window.location.href = confirmUrl; // Redirect to the confirmation URL
        };
    }

    // Close the popup
    function closePopup2() {
        const popupContainer = document.getElementById('popupContainer');
        popupContainer.style.display = 'none'; // Hide the popup
        document.body.classList.remove('blurred'); // Remove blur class
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
