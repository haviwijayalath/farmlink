<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/order_detail.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="order-container">
    <h2 class="page-title">Order Details</h2>

    <?php if (isset($data['orders']) && !empty($data['orders'])) : ?>
        <div class="order-card">
            <div class="order-card-header">
                <h3>Order ID: <?= htmlspecialchars($data['orders']->id); ?></h3>
            </div>
            <div class="order-card-body">
                
                <!-- Table-like layout for order details -->
                <div class="details-table">
                    <div class="row">
                        <div class="cell title">Farmer Name</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->farmer); ?></div>

                        <div class="cell title">Buyer Name</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->buyer); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Farmer Contact</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->fphone); ?></div>

                        <div class="cell title">Buyer Contact</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->phone); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Product Type</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->name); ?></div>

                        <div class="cell title">Quantity</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->capacity); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Pick-up Address</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->pickup_address); ?></div>

                        <div class="cell title">Drop-off Address</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->address); ?></div>
                    </div>
                </div>
                
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-danger mt-3">No order details found.</div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
