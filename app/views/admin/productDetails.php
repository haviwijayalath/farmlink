<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/productDetails.css">


<main class="content">
  <h2>Product Details</h2>

  <div class="product-summary">
    <?php if (!empty($product->productImage)): ?>
      <img 
        src="<?= URLROOT ?>/public/uploads/<?= htmlspecialchars($product->productImage) ?>" 
        alt="<?= htmlspecialchars($product->productName) ?>" 
        class="product-image"
      >
    <?php endif; ?>

    <table class="details-table">
      <tr><th>Name</th>
          <td><?= htmlspecialchars($product->productName) ?></td>
      </tr>
      <tr><th>Price</th>
          <td>Rs <?= number_format($product->price,2) ?></td>
      </tr>
      <tr><th>Stock</th>
          <td><?= (int)$product->stock ?> units</td>
      </tr>
      <tr><th>Expiry</th>
          <td><?= date('M d, Y', strtotime($product->exp_date)) ?></td>
      </tr>
      <tr><th>Description</th>
          <td><?= nl2br(htmlspecialchars($product->description)) ?></td>
      </tr>
      <tr><th>Farmer</th>
          <td><?= htmlspecialchars($product->farmerName) ?></td>
      </tr>
    </table>
  </div>

  <div class="actions">
    <a href="<?= URLROOT ?>/admins/products" class="btn">Back to Products</a>
    <a href="<?= URLROOT ?>/admins/editProduct/<?= $product->id ?>" class="btn">Edit</a>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
