<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/history_detail.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="order-container">
    <h2 class="page-title">Order Details</h2>

    <?php if (isset($data['orders']) && !empty($data['orders'])) : ?>
        <div class="order-card">
        <div class="order-card-header">
            <div class="order-header-flex">
                <h3 class="order-id-title">Order ID: <?= htmlspecialchars($data['orders']->order_id); ?></h3>
                <a href="<?= URLROOT ?>/orderControllers/showcomplaint/<?= $data['orders']->order_id ?>" class="complaint-btn">Complaint</a>
            </div>
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
                        <div class="cell"><?= htmlspecialchars($data['orders']->mobileNo); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Pick-up Address</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->pickupAddress); ?></div>

                        <div class="cell title">Drop-off Address</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->dropoff_address); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Capacity</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->quantity); ?></div>

                        <div class="cell title">Amount</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->amount); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Delivered Date</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->date); ?></div>
                    </div>

                    <div class="row">
                        <div class="cell title">Pic-Before</div>
                        <div class="cell">
                            <img src="<?= URLROOT ?>/public/uploads/<?= htmlspecialchars($data['orders']->pic_before); ?>" alt="Pic Before" class="order-image">
                        </div>

                        <div class="cell title">Pic-After</div>
                        <div class="cell">
                            <img src="<?= URLROOT ?>/public/uploads/<?= htmlspecialchars($data['orders']->pic_after); ?>" alt="Pic After" class="order-image">
                        </div>
                    </div>

                    <div class="row">
                        <div class="cell title">Products</div>
                        <div class="cell"><?= htmlspecialchars($data['orders']->productName); ?></div>
                    </div>
                    
                </div>
                
            </div>
        </div>
    <?php else : ?>
        <div class="alert alert-danger mt-3">No order details found.</div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
