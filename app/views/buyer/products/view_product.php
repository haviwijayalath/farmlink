<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/view_product.css">

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <img src="<?= URLROOT ?>/public/uploads/farmer/products/<?= !empty(htmlspecialchars($data['pImage'])) && file_exists(APPROOT . '/../public/uploads/farmer/products/' . htmlspecialchars($data['pImage'])) ? htmlspecialchars($data['pImage']) : 'Farmer-bro.jpg' ?>" alt="<?php echo htmlspecialchars($data['pName']); ?>" class="product_picture">

      <div class="reviews-section">
        <h4>Reviews</h4>
        <?php if (!empty($data['reviews'])): ?>
          <?php foreach ($data['reviews'] as $review): ?>
            <div class="review">
              <div class="review-rating">
                <?= str_repeat('⭐', htmlspecialchars($review->rating)) ?>
              </div>

              <div class="review-date">
                <?= date('jS M, Y', strtotime($review->created_at)) ?>
              </div>

              <?php if (!empty($review->description)): ?>
                <p class="review-text"><?= htmlspecialchars($review->description) ?></p>
              <?php endif; ?>

              <?php if (!empty($review->reviewImage)): ?>
                <img src="<?= URLROOT ?>/public/public/uploads/<?= htmlspecialchars($review->reviewImage) ?>" alt="Review Image" class="product_picture">
              <?php endif; ?>

              <div class="reviewer-profile">
                <div class="reviewer-info">
                  <strong><?= htmlspecialchars($review->buyerName) ?></strong>
                  <span>Verified Buyer</span>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-reviews">No reviews yet. Be the first to leave one after ordering!</p>
        <?php endif; ?>
      </div>

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
      <p>Rating: <?= htmlspecialchars($data['rate']); ?></p>
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