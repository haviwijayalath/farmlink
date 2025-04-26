<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/home.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="main-content">
  
  <!-- Dashboard Overview -->
  <section class="dashboard-overview">
    <?php foreach ([
      'Total Customers'  => $data['stats']['totalUsers']      ?? 0,
      'Total Orders'     => $data['stats']['totalOrders']     ?? 0,
      'Total Complaints' => $data['stats']['totalComplaints'] ?? 0,
      'Total Reports'    => $data['stats']['totalReports']    ?? 0,
    ] as $title => $val): ?>
      <div class="card">
        <h3><?= $title ?></h3>
        <p><?= number_format($val) ?></p>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- Reports Section -->
  <section class="reports">
    <!-- 1) Monthly revenue (static/demo dataset) -->
    <div class="report-chart">
      <h3>Monthly Revenue</h3>
      <canvas id="revenueChart"></canvas>
    </div>
    <!-- 2) Sales by location -->
    <div class="sales-location">
      <h3>Sales By Location</h3>
      <ul>
        <?php foreach ($data['salesByLocation'] as $city => $amt): ?>
          <li><?= htmlspecialchars($city) ?>: Rs <?= number_format($amt,2) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <!-- 3) Sales by category -->
    <div class="total-sales">
      <h3>Sales by Category</h3>
      <canvas id="salesPieChart"></canvas>
    </div>
  </section>

  <!-- Top Products Table -->
  <section class="top-products">
    <h3>Top Selling Products</h3>
    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Price</th>
          <th>Category</th>
          <th>Qty Sold</th>
          <th>Revenue</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($data['topProducts'])): ?>
          <?php foreach ($data['topProducts'] as $p): ?>
            <tr>
              <td><?= htmlspecialchars($p['name']) ?></td>
              <td>Rs <?= number_format($p['revenue'] / max(1,$p['quantity']),2) ?></td>
              <td><?= htmlspecialchars($p['category']) ?></td>
              <td><?= $p['quantity'] ?></td>
              <td>Rs <?= number_format($p['revenue'],2) ?></td>
              <td>
                <a href="<?= URLROOT ?>/admins/productDetails/<?= $p['id'] ?>" class="btn">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="6">No top products data.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // REVENUE CHART (demo data)
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','Jun'],
      datasets: [{
        label: 'This Month',
        data: [58,60,63,65,70,75],
        tension: 0.3,
        fill: false,
        borderColor: '#4caf50'
      },{
        label: 'Last Month',
        data: [50,52,54,58,60,64],
        tension: 0.3,
        fill: false,
        borderColor: '#f44336'
      }]
    },
    options: { responsive: true, plugins: { legend: { position: 'top' } } }
  });

  // CATEGORY DOUGHNUT
  const catLabels = <?= json_encode(array_keys($data['salesByCategory'])) ?>;
  const catData   = <?= json_encode(array_values($data['salesByCategory'])) ?>;
  new Chart(document.getElementById('salesPieChart'), {
    type: 'doughnut',
    data: {
      labels: catLabels,
      datasets: [{
        data: catData,
        backgroundColor: ['#4caf50','#2196f3','#ffc107','#ff6384','#36a2eb']
      }]
    },
    options: { responsive:true, plugins:{ legend:{ position:'right' } } }
  });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
