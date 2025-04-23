<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">

<div class="admin-container">
  <!-- Header -->
  <header class="admin-header">
    <h2>Expired Stock</h2>
    <div class="header-actions">
      <input type="text" class="search-bar" id="searchBar" placeholder="Search by name or type">
      <button class="search-icon-btn">Search</button>
    </div>
  </header>

  <!-- Product Table -->
  <div class="table-container">
    <table id="productTable">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Name</th>
          <th>Type</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Expiry Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data as $product): ?>
          <?php if($product->farmer_id != $_SESSION['user_id']) continue; ?>
          <tr>
            <td><?php echo $product->fproduct_id; ?></td>
            <td><?php echo $product->name; ?></td>
            <td><?php echo $product->type; ?></td>
            <td>Rs <?php echo number_format($product->price, 2); ?></td>
            <td><?php echo $product->stock; ?> kg</td>
            <td><?php echo date('M d, Y', strtotime($product->exp_date)); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Total Loss Summary -->
  <div class="total-loss-container">
    <div class="loss-summary">
      <h3>Total Expired Stock Value</h3>
      <?php
      $totalLoss = 0;
      foreach($data as $product):
        if($product->farmer_id == $_SESSION['user_id']):
          $totalLoss += $product->price * $product->stock;
        endif;
      endforeach;
      ?>
      <div class="loss-amount">
        <span>Total Loss:</span>
        <span class="amount">Rs <?= number_format($totalLoss, 2); ?></span>
      </div>
    </div>
  </div>
</div>

<style>
  .total-loss-container {
    margin: 20px 0;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .loss-summary h3 {
    margin-top: 0;
    color: #dc3545;
  }
  
  .loss-amount {
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    font-weight: bold;
    padding: 10px 0;
  }
  
  .amount {
    color: #dc3545;
  }
</style>

<!-- JavaScript -->
<script>
  // Search Functionality
  document.getElementById('searchBar').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#productTable tbody tr');

    tableRows.forEach(row => {
      const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
      const type = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

      if (name.includes(searchValue) || type.includes(searchValue)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>