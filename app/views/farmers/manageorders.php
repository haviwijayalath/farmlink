<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">

<div class="admin-container">
  <!-- Header -->
  <header class="admin-header">
    <h2>Products</h2>
    <div class="header-actions">
      <input type="text" class="search-bar" id="searchBar" placeholder="Search by Product ID">
      <button class="search-icon-btn">Search</button>
    </div>
  </header>

  <!-- Product Table -->
  <div class="table-container">
    <table id="productTable">
      <thead>
        <tr>
          <th>ProductID</th>
          <th>Product</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Expired At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($data['products'] as $product) : ?>
        <tr id="row-<?= $product->fproduct_id ?>">
          <td><a href="<?= URLROOT ?>/admins/productDetails/<?= $product->fproduct_id ?>"><?= $product->fproduct_id ?></a></td>
          <td><?= htmlspecialchars($product->name) ?></td>
          <td>Rs <?= number_format($product->price, 2) ?></td>
          <td><?= $product->stock !== null ? $product->stock : '--' ?></td>
          <td><?= date('M d, Y h:i A', strtotime($product->exp_date)) ?></td>
          <td>
            <form action="<?= URLROOT ?>/admins/rejectProduct" method="POST">
              <input type="hidden" name="product_id" value="<?= $product->fproduct_id ?>">
              <button type="submit" class="reject-btn">Reject</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</div>

<!-- JavaScript -->
<script>
  // Search Functionality
  document.getElementById('searchBar').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#productTable tbody tr');

    tableRows.forEach(row => {
      const productId = row.querySelector('td a').textContent.toLowerCase();
      row.style.display = productId.includes(searchValue) ? '' : 'none';
    });
  });

  // Reject Product Functionality (AJAX)
  document.querySelectorAll('.reject-btn').forEach(button => {
    button.addEventListener('click', function () {
      const productId = this.dataset.id;

      if (confirm('Are you sure you want to reject this product?')) {
        fetch("<?= URLROOT ?>/admins/rejectProduct", {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ productId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById(`row-${productId}`).remove();
            alert('Product has been rejected.');
          } else {
            alert('Failed to reject the product.');
          }
        })
        .catch(error => console.error('Error:', error));
      }
    });
  });
</script>


<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>