<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/home.css">

<!-- Main Content -->
<main class="main-content">

  <!-- Dashboard Overview -->
  <section class="dashboard-overview">
    <div class="card">
      <h3>Total Sales</h3>
      <p>RS 34,456.00</p>
    </div>
    <div class="card">
      <h3>Total Orders</h3>
      <p>3456</p>
    </div>
    <div class="card">
      <h3>Top Selling Products</h3>
      <p>Organic Tomatoes</p>
    </div>
    <div class="card">
      <h3>Total Customers</h3>
      <p>42,456</p>
    </div>
  </section>

    <!-- Table Section -->
    <section class="top-products">
    <h3>Top Selling Products</h3>
    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Organic Tomatoes</td>
          <td>Rs 76.89</td>
          <td>Vegetables</td>
          <td>128</td>
          <td>Rs 6,647.15</td>
          <td><button>Edit</button></td>
        </tr>
        <!-- More rows -->
      </tbody>
    </table>
  </section>
</main>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>