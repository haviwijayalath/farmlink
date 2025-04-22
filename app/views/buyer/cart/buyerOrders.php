<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/buyerOrders.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="history-container">
    <div class="history-content-area">
        <h2>Your Orders</h2>
        <table class="order-history-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Shipping Address</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data['orderItems'])): ?>
                <?php foreach ($data['orderItems'] as $item): ?>
                    <tr>
                        <td>
                            <?= $item->product ?>
                        </td>
                        <td>
                            <?= $item->quantity ?>
                        </td>
                        <td>
                            <?= $item->dropAddress ?>
                        </td>
                        <td>
                            <?= $item->orderDate ?>
                        </td>
                        <td>
                            <?= $item->status ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<?php require APPROOT . '/views/inc/footer.php'; ?>
