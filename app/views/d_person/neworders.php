<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/neworder.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>


<div class="neworder-container">
    <h2>New Orders</h2>
    <table class="neworders-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pick-Up</th>
                <th>Drop-Off</th>
                <th>Customer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Temporary array of sample orders for testing
            $orders = [
                ['id' => 1, 'pickup' => 'John', 'dropoff' => 'Doe', 'customer_name' => 'CEO, Founder'],
                ['id' => 2, 'pickup' => 'Jane', 'dropoff' => 'Smith', 'customer_name' => 'Manager, Company X'],
                ['id' => 3, 'pickup' => 'Jim', 'dropoff' => 'Beam', 'customer_name' => 'Owner, Farm Y']
            ];

            foreach($orders as $index => $order): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($order['pickup']) ?></td>
                <td><?= htmlspecialchars($order['dropoff']) ?></td>
                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                <td>
                    <a href="" class="newbtn newbtn-confirm">Confirm</a>
                    <a href="/order/delete/<?= $order['id'] ?>" class="newbtn newbtn-delete">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
