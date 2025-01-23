<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmers/managestocks.css?version=1">

<div class="mt-4">
  <a href="<?= URLROOT ?>/farmers/addstocks" class="btn btn-success"> + Add New Stock</a>
</div>

<h4 style="color: green;"> <?php flash('register_success'); ?> </h4>

<hr>
<div class="container">
  
  <div class="row">
    <?php foreach($data as $product): ?>
      <?php if($product->farmer_id != $_SESSION['user_id']) continue; ?>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">

            <img src="<?= URLROOT ?>/public/uploads/farmer/products/<?= !empty(htmlspecialchars($product->image)) && file_exists(APPROOT . '/../public/uploads/farmer/products/' . htmlspecialchars($product->image)) ? htmlspecialchars($product->image) : 'Farmer-bro.jpg' ?>"  alt="<?php echo htmlspecialchars($product->name); ?>" class="product_picture">

            <h5 class="card-title"><?= htmlspecialchars($product->name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($product->description); ?></p>
            <p class="card-text">Price: Rs.<?php echo htmlspecialchars($product->price); ?></p>
            <p class="card-text">Stock: <?php echo htmlspecialchars($product->stock); ?></p>
            <p class="card-text">Expiry Date: <?php echo htmlspecialchars($product->exp_date); ?></p>
            <a href="<?= URLROOT ?>/farmers/editstocks/<?= $product->fproduct_id ?>" class="btn btn-primary">Edit</a>
            <a href="javascript:void(0)" onclick="showRemovePopup()" class="btn btn-danger">Remove</a>
            <!-- 
            <a href="<?= URLROOT ?>/farmers/deletestock/<?= $product->fproduct_id ?>" class="btn btn-danger">Remove</a> -->
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Remove Confirmation Popup -->
<div class="popup-container" id="remove-popup" style="display: none;">
  <div class="popup-content">
    <h2>Are you sure you want to remove this item from the market?</h2>
    <div class="button-container">
      <button class="remove-button" onclick="confirmRemove()">Remove</button>
      <button class="cancel-button" onclick="closeRemovePopup()">Cancel</button>
    </div>
  </div>
</div>

<script>
  // Show the popup
  function showRemovePopup() {
    const popup = document.getElementById('remove-popup');
    popup.style.display = 'flex';
    popup.style.alignItems = 'center'; // Ensure popup is centered
    popup.style.justifyContent = 'center';
  }

  // Hide the popup
  function closeRemovePopup() {
    document.getElementById('remove-popup').style.display = 'none';
  }

  // Handle logout confirmation
  function confirmRemove() {
    window.location.href = "<?= URLROOT ?>/farmers/deletestock/<?= $product->fproduct_id ?>";
  }

</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>
