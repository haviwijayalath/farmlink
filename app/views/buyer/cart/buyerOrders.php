<?php require APPROOT . '/views/inc/header.php'; ?>

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
                <tr>
                    <td>Carrot</td>
                    <td>50 kg</td>
                    <td>123-B, 2nd Lane, Embilipitiya</td>
                    <td>2024-11-25</td>
                    <td>Delivered</td>
                </tr>
                <tr>
                    <td>Gova</td>
                    <td>100 kg</td>
                    <td>456-A, Green Street, Matara</td>
                    <td>2024-11-20</td>
                    <td>In Transit</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<?php require APPROOT . '/views/inc/footer.php'; ?>
