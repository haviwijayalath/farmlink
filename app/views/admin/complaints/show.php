<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/show.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container complaint-container">
    <div class="button-row">
        <a href="<?= URLROOT ?>/admins/viewComplaints" class="btn-back">← Back to Complaints</a>
        <a href="<?= URLROOT ?>/admins/manageComplaint/<?= $data['complaint']->complaint_id ?>" class="btn-back">Manage</a>
    </div>


    <!-- Complaint Info -->
    <section class="section-box">
        <h2>User Info</h2>
        <div class="grid-2">
            <div><strong>Complaint ID:</strong> <?= $data['complaint']->complaint_id ?></div>
            <div><strong>User ID:</strong> <?= $data['complaint']->user_id ?></div>
            <div><strong>Role:</strong> <?= ucfirst($data['complaint']->role) ?></div>

            <?php if ($data['complaint']->role === 'buyer'): ?>
                <div><strong>Name:</strong> <?= htmlspecialchars($data['complaint']->buyer_name) ?></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($data['complaint']->buyer_email) ?></div>
            <?php elseif ($data['complaint']->role === 'dperson'): ?>
                <div><strong>Name:</strong> <?= htmlspecialchars($data['complaint']->delivery_name) ?></div>
                <div><strong>Email:</strong> <?= htmlspecialchars($data['complaint']->delivery_email) ?></div>
            <?php else: ?>
                <div><strong>Name:</strong> Unknown</div>
                <div><strong>Email:</strong> Unknown</div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Complaint Details -->
    <section class="section-box">
        <h2>Complaint Info</h2>
        <div class="grid-2">
            <div><strong>Order ID:</strong> <?= $data['complaint']->order_id ?></div>
            <div><strong>Status:</strong> <?= $data['complaint']->status ?></div>
            <div><strong>Description:</strong> <?= $data['complaint']->description ?? 'No description' ?></div>
            <div><strong>Submitted At:</strong> <?= $data['complaint']->date_submitted ?? 'N/A' ?></div>
        </div>
    </section>

    <!-- Delivery Info -->
    <section class="section-box">
        <h2>Delivery Info</h2>
        <div class="grid-2">
            <div><strong>Delivery Person ID:</strong> <?= $data['complaint']->delivery_person_id ?></div>
            <div><strong>Name:</strong> <?= $data['complaint']->delivery_name ?></div>
            <div><strong>Email:</strong> <?= $data['complaint']->delivery_email ?></div>
            <div><strong>Pickup Address:</strong> <?= $data['complaint']->pickupAddress ?></div>
            <div><strong>Delivered At:</strong> <?= $data['complaint']->delivered_date ?></div>
        </div>
    </section>

    <!-- Product Info -->
    <section class="section-box">
        <h2>Product Info</h2>
        <div class="grid-2">
            <div><strong>Product:</strong> <?= $data['complaint']->product ?></div>
            <div><strong>Ordered At:</strong> <?= $data['complaint']->orderDate ?></div>
            <div><strong>Quantity:</strong> <?= $data['complaint']->quantity ?></div>
        </div>
    </section>

    <!-- Farmer Info -->
    <section class="section-box">
        <h2>Farmer Info</h2>
        <div class="grid-2">
            <div><strong>Farmer ID:</strong> <?= $data['complaint']->farmer_id ?></div>
            <div><strong>Name:</strong> <?= $data['complaint']->name ?></div>
            <div><strong>Email:</strong> <?= $data['complaint']->email ?></div>
        </div>
    </section>

    <!-- Delivery Images -->
    <section class="section-box">
    <h2>Delivery Images</h2>
    <div class="image-gallery">
        <div class="image-card">
            <p><strong>Before Delivery</strong></p>
            <img src="<?= URLROOT ?>/public/d_uploads/<?= !empty($data['complaint']->pic_before) && 
                file_exists(APPROOT . '/../public/d_uploads/' . $data['complaint']->pic_before) ? $data['complaint']->pic_before : 'Farmer-bro.jpg' ?>" alt="Before Delivery">
        </div>
        <div class="image-card">
            <p><strong>After Delivery</strong></p>
            <img src="<?= URLROOT ?>/<?= htmlspecialchars($data['complaint']->pic_after); ?>" alt="After Delivery">
        </div>
    </div>
</section>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
