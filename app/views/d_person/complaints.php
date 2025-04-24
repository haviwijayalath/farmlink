<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
$complaints = $data['complaints'];
$prefilledOrderID = isset($data['selectedOrderID']) ? $data['selectedOrderID'] : '';
?>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/complaints.css">

<div class="complaint-container">
<div class="complaint-list">
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

<div class="complaint-form">
    <h2>Submit a Delivery Complaint</h2>
    <form action="<?php echo URLROOT; ?>/ordercontrollers/submitComplaint/" method="post">
        <label for="order_id">Order ID:</label>
        <input type="text" name="order_id" id="order_id" value="<?= htmlspecialchars($prefilledOrderID) ?>" required>

        <label for="description">Complaint Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <button type="submit">Submit Complaint</button>
    </form>
</div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
