<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/order_detail.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

    

    <main>

        <div class="content">
            <h2>Order Details</h2>

            <div class="order-summary">
                <div class="order-info">
                    <div class="order-id">ORDER ID: #6548</div>
                    <div class="created">CREATED: 2 min ago</div>
                    <div class="customer">CUSTOMER: Joseph Wheeler</div>
                </div>
                <div class="order-totals">
                    <div class="total">TOTAL: $2647.32</div>
                    <div class="status pending">STATUS: Pending</div>
                    <div class="actions">
                        <button class="action-btn"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </div>
            </div>

            <table class="order-items">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>NAME</th>
                        <th>PRICE</th>
                        <th>QTY</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#6548</td>
                        <td>Apple iPhone 13</td>
                        <td>$999.29</td>
                        <td>x1</td>
                        <td>$949.32</td>
                    </tr>
                    <tr>
                        <td>#6548</td>
                        <td>Apple iPhone 13</td>
                        <td>$999.29</td>
                        <td>x1</td>
                        <td>$949.32</td>
                    </tr>
                    <tr>
                        <td>#6548</td>
                        <td>Apple iPhone 13</td>
                        <td>$999.29</td>
                        <td>x1</td>
                        <td>$949.32</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">Subtotal:</td>
                        <td>$2,847.96</td>
                    </tr>
                    <tr>
                        <td colspan="4">Delivery Fee:</td>
                        <td>$5.50</td>
                    </tr>
                    <tr>
                        <td colspan="4">Total:</td>
                        <td>$2,647.32</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </main>

    <?php require APPROOT . '/views/inc/footer.php'; ?>