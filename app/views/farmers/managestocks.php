<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmers/managestocks.css?version=1">


<div class="mt-4">
  <a href="<?= URLROOT ?>/farmers/addstocks" class="btn btn-success"> + Add New Stock</a>
</div>
<br>

<div class="container">
  <div class="row">
    <?php foreach($data as $product): ?>
      <?php if($product->farmer_id != $_SESSION['user_id']) continue; ?>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body">
            <img src="<?= URLROOT ?>/public/uploads/farmer/products/<?= !empty(htmlspecialchars($product->image)) && file_exists(APPROOT . '/../public/uploads/farmer/products/' . htmlspecialchars($product->image)) ? htmlspecialchars($product->image) : 'Farmer-bro.jpg' ?>"  alt="<?php echo htmlspecialchars($product->name); ?>" class="product_picture" width="300">
            <h5 class="card-title"><?= htmlspecialchars($product->name); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($product->description); ?></p>
            <p class="card-text">Price: $<?php echo htmlspecialchars($product->price); ?></p>
            <p class="card-text">Stock: <?php echo htmlspecialchars($product->stock); ?></p>
            <p class="card-text">Expiry Date: <?php echo htmlspecialchars($product->exp_date); ?></p>
            <a href="<?= URLROOT ?>/farmers/edit/<?= $product->id ?>" class="btn btn-primary">Edit</a>
            <a href="<?= URLROOT ?>/farmers/delete/<?= $product->id ?>" class="btn btn-danger">Remove</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>