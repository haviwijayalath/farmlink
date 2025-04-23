<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/buyerOrders.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="history-container">
    <div class="history-content-area">
        <h2>Your Orders</h2>
        <table class="order-history-table">
            <thead>
                <tr>
                    <th>OrderID</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Shipping Address</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data['orderItems'])): ?>
                <?php foreach ($data['orderItems'] as $item): ?>
                    <tr>
                        <td><?= $item->orderID ?></td> <!-- Ensure orderID is present in the $item object -->
                        <td><?= $item->product ?></td>
                        <td><?= $item->quantity ?></td>
                        <td><?= $item->dropAddress ?></td>
                        <td><?= $item->orderDate ?></td>
                        <td><?= $item->status ?></td>
                        <td>
                            <!-- Review Button -->
                            <a href="<?= URLROOT ?>/reviews/add/<?= $item->orderID ?>" class="btn btn-sm btn-primary">Review</a>
                            <!-- Complaint Button -->
                            <a href="<?= URLROOT ?>/orderControllers/show_buyer_complaint/<?= $item->orderID ?>" class="btn btn-sm btn-danger">Complaint</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No orders found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
