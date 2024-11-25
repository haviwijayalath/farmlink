<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/cart.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>


<?php 

//session_start();

// Sample cart data (In production, this data should come from a database or API)
// $_SESSION['cart'] = $_SESSION['cart'] ?? [
//     ['name' => 'Carrot', 'price' => 2.50, 'quantity' => 2],
//     ['name' => 'Apple', 'price' => 1.20, 'quantity' => 3]
// ];

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Update cart quantities
//     foreach ($_POST['quantity'] as $index => $quantity) {
//         $_SESSION['cart'][$index]['quantity'] = max(1, intval($quantity));
//     }
// }

// // Calculate total price
// $total = 0;
// foreach ($_SESSION['cart'] as $item) {
//     $total += $item['price'] * $item['quantity'];
// }
// ?>


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
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input type="number" name="quantity[<?php echo $index; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                            </td>
                            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td><button type="submit" name="remove" value="<?php echo $index; ?>">Remove</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <div class="tot_value">
                    <span>Total:</span>
                    <span class="total-amount">$<?php echo number_format($total, 2); ?></span>
                </div>
        </form>
        <a href="<?php echo URLROOT?>Buyercontrollers/deliveryOptions">
            <button type="submit">Delivery Options</button>
        </a>
    </div>


