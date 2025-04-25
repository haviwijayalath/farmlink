<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<?php
  // Safeguard: ensure $products is always an array
  $products = $products ?? [];
?>

<main class="admin-container">
  <header class="admin-header">
    <h2>Products</h2>
    <div class="header-actions">
      <input 
        type="text" 
        id="searchBar" 
        class="search-bar" 
        placeholder="Search by Product ID or Name"
      >
      <button class="btn" onclick="filterProducts()">Search</button>
      <!--
        <a href="<?= URLROOT ?>/admins/addProduct" class="btn">+ Add Product</a>
        Uncomment and implement addProduct() when ready
      -->
    </div>
  </header>

  <div class="table-container">
    <table id="productTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Expiry</th>
          <th>Farmer</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $p): ?>
            <tr id="row-<?= $p->id ?>">
              <td>
                <a href="<?= URLROOT ?>/admins/productDetails/<?= $p->id ?>">
                  <?= htmlspecialchars($p->id) ?>
                </a>
              </td>
              <td><?= htmlspecialchars($p->productName) ?></td>
              <td>Rs <?= number_format($p->price, 2) ?></td>
              <td><?= (int)$p->stock ?></td>
              <td><?= date('M d, Y', strtotime($p->exp_date)) ?></td>
              <td><?= htmlspecialchars($p->farmerName) ?></td>
              <td class="actions">
                <a 
                  href="<?= URLROOT ?>/admins/productDetails/<?= $p->id ?>" 
                  class="btn-sm"
                >View</a>
                <form 
                  action="<?= URLROOT ?>/admins/rejectProduct" 
                  method="POST" 
                  style="display:inline;"
                >
                  <input 
                    type="hidden" 
                    name="product_id" 
                    value="<?= $p->id ?>"
                  >
                  <button type="submit" class="btn-sm btn-danger">
                    Reject
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">No products found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<script>
  function filterProducts() {
    const q = document.getElementById('searchBar').value.toLowerCase();
    document.querySelectorAll('#productTable tbody tr').forEach(row => {
      const id   = row.cells[0].textContent.toLowerCase();
      const name = row.cells[1].textContent.toLowerCase();
      row.style.display = (id.includes(q) || name.includes(q)) ? '' : 'none';
    });
  }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
