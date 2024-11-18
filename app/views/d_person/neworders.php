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
                            <a href="<?= URLROOT ?>/orders/orderDetails/<?= htmlspecialchars($order->id) ?>" class="newbtn newbtn-delete" >
                                <?= htmlspecialchars($order->id) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($order->pickup_address) ?></td>
                        <td><?= htmlspecialchars($order->dropoff_address) ?></td>
                        <td><?= htmlspecialchars($order->capacity) ?></td>
                        <td>
                            <a href="<?= URLROOT ?>/ordercontrollers/confirm/<?= htmlspecialchars($order->id) ?>" class="newbtn newbtn-confirm">Confirm</a>
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

<?php require APPROOT . '/views/inc/footer.php'; ?>
