<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">

<!-- Main Content -->
<main class="admin-container">

  <!-- Table Section -->
    <header class="admin-header">
      <h2>Sales</h2>
      <div class="header-actions">
        <input type="text" class="search-bar" id="searchBar" placeholder="Search by month, product, or order ID">
        <button class="search-icon-btn">Search</button>
      </div>
    </header>
    <div class="table-container">
      <table id="productTable">
        <thead>
        <tr>
          <th>Month</th>
          <th>Total Sales (Rs)</th>
          <th>Orders Count</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['monthlySales'])): ?>
          <?php foreach ($data['monthlySales'] as $index => $monthData): ?>
          <tr>
            <td><?= $monthData['month'] ?></td>
            <td>Rs <?= number_format($monthData['totalFee'], 2) ?></td>
            <td><?= count($monthData['orders']) ?></td>
            <td>
            <button class="toggle-details" data-month="<?= $index ?>">View Details</button>
            </td>
          </tr>
          <tr class="order-details" id="month-<?= $index ?>-details" style="display: none;">
            <td colspan="4">
            <table class="details-table">
              <thead>
              <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Amount (Rs)</th>
                <th>Status</th>
                <th>Order Date</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($monthData['orders'] as $order): ?>
                <tr>
                <td><?= $order->orderID ?></td>
                <td><?= $order->product_name ?></td>
                <td><?= $order->quantity . 'kg'?></td>
                <td><?= number_format($order->famersFee, 2) ?></td>
                <td><span class="status-<?= strtolower($order->status) ?>"><?= ucfirst($order->status) ?></span></td>
                <td><?= date('Y-m-d', strtotime($order->orderDate)) ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
          <td colspan="4">No sales data available</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
  <!-- Sales Summary Section -->
  <section class="sales-summary">
    <div class="summary-container">
      <h3>Sales Summary</h3>
      <div class="summary-details">
        <div class="summary-item">
          <span class="summary-label">Total Sales:</span>
          <span class="summary-value">Rs <?= number_format($data['totalSales'], 2) ?></span>
        </div>
        <div class="summary-item">
          <span class="summary-label">Total Orders:</span>
          <span class="summary-value"><?= $data['totalOrders'] ?></span>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
  const toggleButtons = document.querySelectorAll('.toggle-details');
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
    const monthIndex = this.getAttribute('data-month');
    const detailsRow = document.getElementById(`month-${monthIndex}-details`);
    
    if (detailsRow.style.display === 'none') {
      detailsRow.style.display = 'table-row';
      this.textContent = 'Hide Details';
    } else {
      detailsRow.style.display = 'none';
      this.textContent = 'View Details';
    }
    });
  });
  });
  
  document.addEventListener('DOMContentLoaded', function() {
    const searchBar = document.getElementById('searchBar');
    const searchBtn = document.querySelector('.search-icon-btn');
    
    const performSearch = () => {
  const searchTerm = searchBar.value.toLowerCase();
  const tableRows = document.querySelectorAll('#productTable tbody tr:not(.order-details)');
  
  tableRows.forEach(row => {
    const month = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
    const shouldShow = month.includes(searchTerm);
    
    // Get the row's index to find its detail row
    const monthIndex = row.querySelector('.toggle-details')?.getAttribute('data-month');
    const detailsRow = monthIndex ? document.getElementById(`month-${monthIndex}-details`) : null;
    
    // Hide/show the main row
    row.style.display = shouldShow ? '' : 'none';
    
    // Always hide details row when filtering
    if (detailsRow) {
      detailsRow.style.display = 'none';
      row.querySelector('.toggle-details').textContent = 'View Details';
    }
  });
    };
    
    searchBtn.addEventListener('click', performSearch);
    searchBar.addEventListener('keyup', function(e) {
  if (e.key === 'Enter') {
    performSearch();
  }
    });
    });
</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>