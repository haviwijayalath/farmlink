<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/manage.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="manage-complaint-container">
<?php if (!$data['complaint']): ?>
    <p>Complaint not found.</p>
<?php else: ?>
    <h2>Manage Complaint #<?= $data['complaint']->complaint_id ?></h2>
    <p>Status: <?= $data['complaint']->status ?></p>

    <form action="<?= URLROOT ?>/admins/resolve/<?= $data['complaint']->complaint_id ?>" method="post">
        <label><input type="radio" name="fault_by" value="farmer" required> Fault by Farmer</label><br>
        <label><input type="radio" name="fault_by" value="delivery" required> Fault by Delivery Person</label><br>
        <label><input type="radio" name="fault_by" value="none" required> No Fault / Dismiss Complaint</label><br><br>
        <textarea name="admin_notes" placeholder="Admin notes or observations..." required></textarea><br>
        <div class="button-row">
          <a href="<?= URLROOT ?>/admins/viewComplaints" class="btn-back">← Back to Complaints</a>
          <button type="submit" class="btn-submit">Submit Decision</button>
        </div>
    </form>
<?php endif; ?>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>