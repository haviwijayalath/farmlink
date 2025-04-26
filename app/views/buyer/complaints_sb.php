<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$complaints = $data['complaints'];
$prefilledOrderID = isset($data['selectedOrderID']) ? $data['selectedOrderID'] : '';
?>

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/complaints_sb.css">

<div class="complaint-container" >
<div class="complaint-list" style="margin-top: 50px;">
    <h2>Your Past Complaints</h2>

    <?php if (!empty($complaints) && count($complaints) > 0): ?>
      <?php foreach ($complaints as $complaint): ?>
      <div class="complaint">
          <strong>Order ID: <?= htmlspecialchars($complaint->order_id) ?></strong>
          <p><?= nl2br(htmlspecialchars($complaint->description)) ?></p>
          <small>Submitted on: <?= $complaint->date_submitted ?></small>
      </div>
    <?php endforeach; ?>

    <?php else: ?>
        <p>No complaints submitted yet.</p>
    <?php endif; ?>
</div>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
