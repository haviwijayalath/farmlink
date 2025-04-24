<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/users/support.css">

<div class="container mt-5">
  <h1 class="text-center mb-4">Support Center</h1>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
          <?php flash('support'); ?>
          <h2 class="card-title text-center mb-4">How can we help you?</h2>
          <div class="d-grid gap-3">
            <a href="<?php echo URLROOT; ?>/users/support?type=activation" class="btn btn-success btn-lg p-4">
              <h4>Request activation for suspended or deactivated account</h4>
            </a>
            <a href="<?php echo URLROOT; ?>/users/support?type=approval" class="btn btn-success btn-lg p-4">
              <h4>Account has not yet been approved</h4>
            </a>
            <a href="<?php echo URLROOT; ?>/users/support?type=other" class="btn btn-success btn-lg p-4">
              <h4>Other</h4>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
