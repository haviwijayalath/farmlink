<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">

<div class="admin-container">
  <!-- Header -->
  <header class="admin-header">
    <h2>Orders</h2>
    <div class="header-actions">
      <input type="text" class="search-bar" id="searchBar" placeholder="Search by ID, buyer, or product">
      <button class="search-icon-btn">Search</button>
    </div>
  </header>

  <!-- Product Table -->
  <div class="table-container">
    <table id="productTable">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Buyer</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Amount</th>
          <th>Delivery Address</th>
          <th>Ordered Date</th>
          <th>Delivery Person</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($data as $order) : ?>
        <tr id="row-<?= $order->orderID ?>">
          <td><?= htmlspecialchars($order->orderID) ?></td>
          <td><?= htmlspecialchars($order->buyer_name) ?></td>
          <td><?= htmlspecialchars($order->product) ?></td>
          <td><?= htmlspecialchars($order->quantity) . 'kg' ?></td>
          <td>Rs <?= number_format($order->famersFee, 2) ?></td>
          <td><?= htmlspecialchars($order->dropAddress) ?></td>
          <td><?= date('M d, Y h:i A', strtotime($order->orderDate)) ?></td>
          <td><?= htmlspecialchars($order->dperson_name) ?></td>
          <td>
            <?php if ($order->status == 'processing') : ?>
              <form action="<?= URLROOT ?>/farmers/orderready" method="POST">
                <input type="hidden" name="order_id" value="<?= $order->orderID ?>">
                <button type="submit" class="ready-btn">Order Ready</button>
              </form>
            <?php else: ?>
              <button class="status-confirmed" style="padding: 8px 12px;" disabled>Confirmed</button>
            <?php endif; ?>
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
      const orderId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
      const buyerName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
      const product = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

      if (orderId.includes(searchValue) || buyerName.includes(searchValue) || product.includes(searchValue)) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
</script>


<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>