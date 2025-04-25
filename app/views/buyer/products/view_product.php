<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/view_product.css">

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <img src="<?= URLROOT ?>/public/uploads/farmer/products/<?= !empty(htmlspecialchars($data['pImage'])) && file_exists(APPROOT . '/../public/uploads/farmer/products/' . htmlspecialchars($data['pImage'])) ? htmlspecialchars($data['pImage']) : 'Farmer-bro.jpg' ?>"  alt="<?php echo htmlspecialchars($data['pName']); ?>" class="product_picture">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($data['pName']); ?></h2>
      <p><?= htmlspecialchars($data['description']); ?></p>
      <p style="color: #28a745; font-weight:bold;">Price: Rs.<?= htmlspecialchars($data['price']); ?></p>
      <p>Stock: <?= htmlspecialchars($data['stock']); ?>KG</p>

      <p>Expiry Date: <?= htmlspecialchars($data['exp_date']); ?></p>
      <hr>
      <p>Farmer: <?= htmlspecialchars($data['fName']); ?></p>
      <p>Email: <?= htmlspecialchars($data['fEmail']); ?></p>
      <hr>
      <form action="<?= URLROOT ?>/buyercontrollers/addToCart" method="POST">
        <input type="hidden" name="product_id" value="<?= $data['fId'] ?>">
        <input type="number" name="quantity" value="1" min="1" max="<?= $data['stock'] ?>">
        <button type="submit" class="btn btn-primary">Add to Cart</button>
      </form>

      <form action="<?= URLROOT ?>/buyercontrollers/addToWishlist" method="POST">
        <input type="hidden" name="product_id" value="<?= $data['fId'] ?> ">
        <button type="submit" class="btn btn-primary">Add to Wishlist</button>
      </form>
    </div>
  </div>
  

<?php require APPROOT . '/views/inc/footer.php'; ?>
