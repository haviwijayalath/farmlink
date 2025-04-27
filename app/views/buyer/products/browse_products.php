<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/browse_products.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_home_sidebar.php'; ?>


<!-- Search and the Filter -->
<div class="search-filter-container" style="position: sticky; top: 0; z-index: 100; ">
  <form action="<?= URLROOT ?>/buyercontrollers/browseproducts" method="GET" class="form-inline">
    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search products" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit" class="btn btn-primary">Search</button>

  <form action="<?= URLROOT ?>/buyercontrollers/browseproducts" method="GET" class="form-inline">
    <select name="category" class="form-control mr-sm-2">
      <option value="">Select Category</option>
      <option value="fruit" <?= isset($_GET['category']) && $_GET['category'] === 'fruit' ? 'selected' : '' ?>>Fruits</option>
      <option value="vege" <?= isset($_GET['category']) && $_GET['category'] === 'vege' ? 'selected' : '' ?>>Vegetables</option>
      <option value="dairy" <?= isset($_GET['category']) && $_GET['category'] === 'dairy' ? 'selected' : '' ?>>Dairy</option>
    </select>
    <select name="price" class="form-control mr-sm-2">
      <option value="">Select Price</option>
      <option value="ASC" <?= isset($_GET['price']) && $_GET['price'] === 'ASC' ? 'selected' : '' ?>>Low to High</option>
      <option value="DESC" <?= isset($_GET['price']) && $_GET['price'] === 'DESC' ? 'selected' : '' ?>>High to Low</option>
    </select>
    <select name="stock" class="form-control mr-sm-2">
      <option value="">Select Stock</option>
      <option value="ASC" <?= isset($_GET['stock']) && $_GET['stock'] === 'ASC' ? 'selected' : '' ?>>Low to High</option>
      <option value="DESC" <?= isset($_GET['stock']) && $_GET['stock'] === 'DESC' ? 'selected' : '' ?>>High to Low</option>
    </select>
    <select name="exp_date" class="form-control mr-sm-2">
      <option value="">Select Expiry Date</option>
      <option value="ASC" <?= isset($_GET['exp_date']) && $_GET['exp_date'] === 'ASC' ? 'selected' : '' ?>>Earliest to Latest</option>
      <option value="DESC" <?= isset($_GET['exp_date']) && $_GET['exp_date'] === 'DESC' ? 'selected' : '' ?>>Latest to Earliest</option>
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
  </form>
</div>

<!-- product cards -->
<div class="container" style="margin-left: 200px;">
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
