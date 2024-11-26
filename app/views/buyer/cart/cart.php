<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/cart.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="cart-container">
        <h1>Shopping Cart</h1>
        <form method="POST">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty(Kg)</th>
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
                            <form action="<?= URLROOT ?>/Buyercontrollers/updateCartItem" method="POST">
                                <input type="hidden" name="cart_id" value="<?= $item->cart_id ?>">
                                <input type="number" name="quantity" value="<?= $item->quantity ?>" min="1">
                                <button type="submit">Update</button>
                            </form>
                        </td>

                        <td>Rs.<?= number_format($item->price * $item->quantity, 2) ?></td>

                        <td>
                           <a class="btn-remove" href="<?= URLROOT ?>/Buyercontrollers/removeCartItem/<?= $item->cart_id ?>">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
                <div class="tot_value">
                    <span>Total:</span>

                    <span class="total-amount">Rs.<?= number_format($data['total'], 2) ?></span>

                </div>
        </form>
        
            <a class="btn-delivery" href="<?php echo URLROOT?>Buyercontrollers/deliveryOptions">
                Delivery Options
            </a>
        
     
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

