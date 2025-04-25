<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$complaints = $data['complaints'];
$prefilledOrderID = isset($data['selectedOrderID']) ? $data['selectedOrderID'] : '';
?>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/complaints_sb.css">

<div class="complaint-container">
  <div class="complaint-list">
    <h2>Your Past Complaints</h2>

    <?php if (!empty($complaints) && count($complaints) > 0): ?>
      <?php foreach ($complaints as $complaint): ?>
        <div class="complaint">
          <strong>Order ID: <?= htmlspecialchars($complaint->order_id) ?></strong>
          <p><?= nl2br(htmlspecialchars($complaint->description)) ?></p>
          <small>Submitted on: <?= htmlspecialchars($complaint->date_submitted) ?></small>

          <?php if (!empty($complaint->status)): ?>
            <p><strong>Status:</strong> <?= htmlspecialchars($complaint->status) ?></p>
          <?php endif; ?>

          <?php if (!empty($complaint->faultby)): ?>
            <p><strong>Fault By:</strong> <?= htmlspecialchars($complaint->fault_by) ?></p>
          <?php endif; ?>

          <?php if (!empty($complaint->admin_notes)): ?>
            <p><strong>Admin Notes:</strong><br><?= nl2br(htmlspecialchars($complaint->admin_notes)) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No complaints submitted yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
