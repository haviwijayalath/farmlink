<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/cart.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="cart-container">
    <h1>Shopping Cart</h1>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty (Kg)</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['cartItems'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item->name) ?></td>
                    <td>Rs.<?= number_format($item->price, 2) ?></td>
                    <td>
                        <form action="<?= URLROOT ?>/Buyercontrollers/updateCartItem" method="POST" style="display: inline;">
                            <input type="hidden" name="cart_id" value="<?= $item->cart_id ?>">
                            <input type="number" name="quantity" value="<?= $item->quantity ?>" min="1" required>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>Rs.<?= number_format($item->price * $item->quantity, 2) ?></td>
                    <td>
                        <!-- Trigger the confirmation popup -->
                        <a href="javascript:void(0)" class="btn-remove" onclick="showPopup(<?= $item->cart_id ?>)">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="tot_value">
        <span>Total:</span>
        <span class="total-amount">Rs.<?= number_format($data['total'], 2) ?></span>
    </div>
    <a class="btn-delivery" href="<?= URLROOT ?>/Buyercontrollers/deliveryOptions">Delivery Options</a>
</div>

<!-- Confirmation Popup -->
<div id="remove-popup" class="popup-container" style="display: none;">
    <div class="popup-content">
        <h2>Are you sure you want to remove this item?</h2>
        <div class="button-container">
            <a href="javascript:void(0)" id="confirm-remove" class="remove-button">Yes</a>
            <button onclick="closePopup()" class="cancel-button">Cancel</button>
        </div> 
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    let cartIdToRemove = null;

    // Show the popup when the remove button is clicked
    function showPopup(cartId) {
        cartIdToRemove = cartId;
        document.getElementById('remove-popup').style.display = 'flex';
    }

    // Close the popup
    function closePopup() {
        document.getElementById('remove-popup').style.display = 'none';
    }

    // Handle confirmation of removal
    document.getElementById('confirm-remove').addEventListener('click', function() {
        if (cartIdToRemove !== null) {
            window.location.href = "<?= URLROOT ?>/Buyercontrollers/removeCartItem/" + cartIdToRemove;
        }
    });
</script>
