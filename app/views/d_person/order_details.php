<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/order_detail.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="order_container">
    <h2>Order Details</h2>

    <?php if (isset($data['orders']) && !empty($data['orders'])) : ?>
        <div class="card mt-3">
            <div class="card-header">
                <h4>Order ID: <?= htmlspecialchars($data['orders']->id); ?></h4>
            </div>
            <div class="card-body">
                <h5>Farmer Details</h5>
                <p><strong>Name:</strong> <?= htmlspecialchars($data['orders']->farmer); ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($data['orders']->fphone); ?></p>
                
                <hr>
                
                <h5>Buyer Details</h5>
                <p><strong>Name:</strong> <?= htmlspecialchars($data['orders']->buyer); ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($data['orders']->phone); ?></p>
                
                <hr>
                
                <h5>Product Details</h5>
                <p><strong>Type:</strong> <?= htmlspecialchars($data['orders']->name); ?></p>
                <p><strong>Quantity:</strong> <?= htmlspecialchars($data['orders']->capacity); ?></p>
                
                <hr>
                
                <h5>Delivery Information</h5>
                <p><strong>Pick-up address:</strong> <?= htmlspecialchars($data['orders']->pickup_address); ?></p>
                <p><strong>Drop-off address:</strong> <?= htmlspecialchars($data['orders']->dropoff_address); ?></p>
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-danger mt-3">No order details found.</div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
