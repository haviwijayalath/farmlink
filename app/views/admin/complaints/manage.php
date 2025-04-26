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
            <label><input type="radio" name="fault_by" value="farmer"> Fault by Farmer</label><br>
            <label><input type="radio" name="fault_by" value="delivery"> Fault by Delivery Person</label><br>
            <label><input type="radio" name="fault_by" value="none"> No Fault / Dismiss Complaint</label><br><br>

            <textarea name="admin_notes" placeholder="Admin notes or observations..." title="Admin note is required" required>
            <?php echo isset($data['admin_notes']) ? $data['admin_notes'] : ''; ?>
            </textarea>
            <div class="button-row">
                <a href="<?= URLROOT ?>/admins/viewComplaints" class="btn-back">← Back to Complaints</a>
                <button type="submit" class="btn-submit">Submit Decision</button>
            </div>


            <?php if (!empty($data['errors']['fault_by'])) : ?>
                <div class="error"><?php echo $data['errors']['fault_by']; ?></div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>