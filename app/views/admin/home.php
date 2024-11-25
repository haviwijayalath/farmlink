<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/home.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

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

  <!-- Reports Section -->
  <section class="reports">
    <div class="report-chart">
      <h3>Revenue</h3>
      <canvas id="revenueChart"></canvas>
    </div>
    <div class="sales-location">
      <h3>Sales By Location</h3>
      <ul>
        <li>Colombo: 72K</li>
        <li>Jaffna: 39K</li>
        <li>Galle: 25K</li>
        <li>N.Eliya: 61K</li>
      </ul>
    </div>
    <div class="total-sales">
      <h3>Total Sales</h3>
      <canvas id="salesPieChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Line Chart for Revenue
  const ctx = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [
        {
          label: 'Current Week',
          data: [58, 60, 63, 65, 70, 75],
          borderColor: '#4caf50',
          tension: 0.4,
          fill: false,
        },
        {
          label: 'Previous Week',
          data: [50, 52, 54, 58, 60, 64],
          borderColor: '#f44336',
          tension: 0.4,
          fill: false,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
      },
    },
  });

  // Doughnut Chart for Total Sales
  const pieCtx = document.getElementById('salesPieChart').getContext('2d');
  const salesPieChart = new Chart(pieCtx, {
    type: 'doughnut',
    data: {
      labels: ['Vegetable', 'Fruits', 'Grains'],
      datasets: [
        {
          data: [38.6, 15.5, 22.1],
          backgroundColor: ['#4caf50', '#2196f3', '#ffc107'],
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'right',
        },
      },
    },
  });

  // Sorting Table Rows
  const table = document.querySelector("table tbody");
  const rows = Array.from(table.rows);

  function sortTable(columnIndex) {
    const sortedRows = rows.sort((a, b) => {
      const aText = a.cells[columnIndex].textContent.trim();
      const bText = b.cells[columnIndex].textContent.trim();
      return aText.localeCompare(bText, undefined, { numeric: true });
    });

    // Rebuild the table
    table.innerHTML = "";
    sortedRows.forEach((row) => table.appendChild(row));
  }

  // Attach sorting to column headers
  document.querySelectorAll("table thead th").forEach((th, index) => {
    th.addEventListener("click", () => sortTable(index));
  });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
