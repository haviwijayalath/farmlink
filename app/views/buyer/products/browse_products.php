<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/browse_products.css">

<!-- Search and the Filter -->
<div class="search-filter-container">
  <form action="<?= URLROOT ?>/buyer/products/search" method="GET" class="form-inline">
    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search products">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>
  <form action="<?= URLROOT ?>/buyer/products/filter" method="GET" class="form-inline">
    <select name="category" class="form-control mr-sm-2">
      <option value="">Select Category</option>
      <option value="fruits">Fruits</option>
      <option value="vegetables">Vegetables</option>
      <option value="dairy">Dairy</option>
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
  </form>
</div>

<!-- product cards -->
<div class="container">
  <div class="row">
    <?php foreach($data as $product): ?>
      <a href="<?= URLROOT ?>/buyercontrollers/viewproduct/<?= $product->fproduct_id ?>" >
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="card-body">
            
              <img src="<?= URLROOT ?>/public/uploads/farmer/products/<?= !empty(htmlspecialchars($product->image)) && file_exists(APPROOT . '/../public/uploads/farmer/products/' . htmlspecialchars($product->image)) ? htmlspecialchars($product->image) : 'Farmer-bro.jpg' ?>"  alt="<?php echo htmlspecialchars($product->name); ?>" class="product_picture">

              <h5 class="card-title"><?= htmlspecialchars($product->name); ?></h5>
              <p class="card-text">Rs.<?php echo htmlspecialchars($product->price); ?></p>
              <p class="card-text"><?php echo htmlspecialchars($product->stock); ?> KG</p>
              <!-- <p class="card-text">Expiry Date: <?php echo htmlspecialchars($product->exp_date); ?></p> -->
            
            
            </div>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
