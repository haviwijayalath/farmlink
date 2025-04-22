<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/showaccount.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-dashboard">
    <main>
        <div class="head">
            <h1><?= ucfirst($data['role']) ?> Account Details</h1>
        </div>

        <div class="user-details-card">
            <div class="profile-section">
                <img src="<?= URLROOT ?>/public/uploads/<?= $data['user']->image ?? 'default.png' ?>" alt="Profile Image">
                <h2><?= $data['user']->name ?></h2>
                <p class="role-badge"><?= ucfirst($data['role']) ?></p>
                <p class="status <?= strtolower($data['user']->status ?? 'approved') ?>"><?= ucfirst($data['user']->status ?? 'N/A') ?></p>

                <div class="action-buttons">
                    <form method="post" action="<?= URLROOT ?>/adminControllers/changeStatus">
                        <input type="hidden" name="id" value="<?= $data['user']->id ?>">
                        <input type="hidden" name="role" value="<?= $data['role'] ?>">
                        <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                        <button type="submit" name="action" value="suspend" class="btn-suspend">Suspend</button>
                        <button type="submit" name="action" value="delete" class="btn-delete" onclick="return confirm('Are you sure you want to delete this account?')">Delete</button>
                    </form>
                </div>
            </div>



            <div class="info-section">
                <div class="info-row"><strong>Email:</strong> <?= $data['user']->email ?></div>
                <div class="info-row"><strong>Phone:</strong> <?= $data['user']->phone ?></div>
                <div class="info-row"><strong>Status:</strong> <?= $data['user']->status ?></div>

                <!-- Optional fields (only shown if they exist) -->
                <?php if (!empty($data['user']->experience)): ?>
                    <div class="info-row"><strong>Experience:</strong> <?= $data['user']->experience ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->address)): ?>
                  <div class="info-row"><strong>Address:</strong> <?= $data['user']->address ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->specialization)): ?>
                    <div class="info-row"><strong>Specialization:</strong> <?= $data['user']->specialization ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->area)): ?>
                    <div class="info-row"><strong>Delivery Area:</strong> <?= $data['user']->area ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->rate)): ?>
                    <div class="info-row"><strong>Rate:</strong> <?= $data['user']->rate ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->type)): ?>
                    <div class="info-row"><strong>Vehicle Type:</strong> <?= $data['user']->type ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->regno)): ?>
                    <div class="info-row"><strong>Registration No:</strong> <?= $data['user']->regno ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->capacity)): ?>
                    <div class="info-row"><strong>Capacity:</strong> <?= $data['user']->capacity ?></div>
                <?php endif; ?>

                <?php if (!empty($data['user']->v_image)): ?>
                    <div class="info-row vehicle-image">
                        <strong>Vehicle Image:</strong><br>
                        <img src="<?= URLROOT ?>/public/uploads/<?= $data['user']->v_image ?>" alt="Vehicle Image">
                    </div>
                <?php endif; ?>


                <!-- You can add more role-specific fields here -->
            </div>
        </div>
    </main>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>