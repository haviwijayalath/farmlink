<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/products.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-container">
  <!-- Header -->
  <header class="admin-header">
    <h2>Products</h2>
    <div class="header-actions">
      <input type="text" class="search-bar" id="searchBar" placeholder="Search by Product ID">
      <button class="search-icon-btn">
        <i class="fas fa-search"></i>
      </button>
    </div>
  </header>

  <!-- Product Table -->
  <div class="table-container">
    <table id="productTable">
      <thead>
        <tr>
          <th>ProductID</th>
          <th>Products</th>
          <th>Price</th>
          <th>Total Sales</th>
          <th>Total Revenue</th>
          <th>Expired At</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><a href="<?= URLROOT ?>/admins/order_details/ORD12350">ORD12350</a></td>
          <td>Cabbage</td>
          <td>Rs 200</td>
          <td>10</td>
          <td>2,000 USD</td>
          <td>Jan 12, 2023 3:40 PM</td>
        </tr>
        <tr>
          <td><a href="<?= URLROOT ?>/admins/order_details/ORD12351">ORD12351</a></td>
          <td>Potato</td>
          <td>Rs 200</td>
          <td>--</td>
          <td>--</td>
          <td>Jan 12, 2023 1:05 PM</td>
        </tr>
        <tr>
          <td><a href="<?= URLROOT ?>/admins/order_details/ORD12352">ORD12352</a></td>
          <td>Karawila</td>
          <td>Rs 250</td>
          <td>15</td>
          <td>3,750 USD</td>
          <td>Jan 11, 2023 11:50 AM</td>
        </tr>
        <!-- Add more rows as needed -->
      </tbody>
    </table>
  </div>
</div>

<script>
  document.getElementById('searchBar').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#productTable tbody tr');

    tableRows.forEach(row => {
      const productId = row.querySelector('td a').textContent.toLowerCase();
      if (productId.includes(searchValue)) {
        row.style.display = ''; // Show the row
      } else {
        row.style.display = 'none'; // Hide the row
      }
    });
  });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
