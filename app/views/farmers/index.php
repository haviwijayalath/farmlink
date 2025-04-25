<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/farmer/index.css">

<main class="main-content">
  <!-- Dashboard Overview -->
  <section class="dashboard-overview">
    <div class="card">
      <h3>Total Sales</h3>
      <p>Rs <?= number_format($data['totalSales'], 2) ?></p>
      <small><?= $data['salesChange'] ?>% from last month</small>
    </div>
    <div class="card">
      <h3>Total Orders</h3>
      <p><?= $data['totalOrders'] ?></p>
      <small><?= $data['pendingOrders'] ?> pending orders</small>
    </div>
    <div class="card">
      <h3>Current Stock</h3>
      <p><?= $data['currentStock'] ?>kg</p>
      <small><?= $data['expiringStockCount'] ?>kg expiring within 2 days</small>
    </div>
    <div class="card">
      <h3>Products</h3>
      <p><?= $data['totalProducts'] ?> products</p>
    </div>
  </section>

  <!-- Recent Activity Section -->
  <section class="recent-activity">
    <div class="section-header">
      <h2>Recent Orders</h2>
      <a href="<?= URLROOT ?>/farmers/manageorders" class="view-all">View All</a>
    </div>
    <div class="activity-table">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Product</th>
            <th>Buyer</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($data['recentOrders'])): ?>
            <?php foreach($data['recentOrders'] as $order): ?>
              <tr>
                <td>#<?= $order->orderID ?></td>
                <td><?= $order->product_name ?></td>
                <td><?= $order->buyer_name ?></td>
                <td>Rs <?= number_format($order->famersFee, 2) ?></td>
                <td><?= date('M d, Y', strtotime($order->orderDate)) ?></td>
                <td><span class="status-<?= strtolower($order->status) ?>"><?= ucfirst($order->status) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6">No recent orders</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Two-column layout for charts and expiring stock -->
  <section class="dashboard-details">
    <div class="detail-column">
      <div class="chart-container">
        <h3>Top Products </h3>

        <div class="top-products-list">
          <ul class="product-list">
            <?php 
            $count = 0;
            if(!empty($data['topProducts'])): 
              foreach($data['topProducts'] as $product):
                if($count >= 5) break; // Show only top 5
            ?>
              <li>
                <div class="product-info">
                  <div class="product-details">
                    <span class="product-name"><?= htmlspecialchars($product->name) ?></span>
                    <span class="product-quantity">Total sold: <?= $product->total_quantity ?>kg</span>
                  </div>
                </div>
              </li>
            <?php
                $count++;
              endforeach;
            else:
            ?>
              <li>No product data available</li>
            <?php endif; ?>
          </ul>
        </div>

      </div>
    </div>
  </section>
</main>

<style>
  .main-content {
  flex-grow: 1;
  padding: 20px;
  margin-left: 300px;
  margin-top: 120px;
  margin-bottom: 80px;
}
/* Additional styles for the dashboard */
.dashboard-overview {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 30px;
}

.card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card h3 {
  margin-top: 0;
  color: #555;
  font-size: 16px;
}

.card p {
  margin: 10px 0;
  font-size: 24px;
  font-weight: bold;
  color: #333;
}

.card small {
  color: #777;
  font-size: 12px;
}

.recent-activity {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 30px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.section-header h2 {
  margin: 0;
  font-size: 18px;
}

.view-all {
  color: #3498db;
  text-decoration: none;
}

.activity-table table {
  width: 100%;
  border-collapse: collapse;
}

.activity-table th, .activity-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.activity-table th {
  background: #f9f9f9;
}

.status-processing {
  background: #f39c12;
  color: white;
  padding: 3px 8px;
  border-radius: 3px;
  font-size: 12px;
}

.status-ready, .status-delivered {
  background: #2ecc71;
  color: white;
  padding: 3px 8px;
  border-radius: 3px;
  font-size: 12px;
}

.status-cancelled {
  background: #e74c3c;
  color: white;
  padding: 3px 8px;
  border-radius: 3px;
  font-size: 12px;
}

.dashboard-details {
  display: grid;
  grid-template-columns: 3fr 2fr;
  gap: 20px;
  margin-bottom: 30px;
}

.detail-column {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-container {
  width: 100%;
  height: 300px;
}

.expiring-list {
  list-style: none;
  padding: 0;
}

.expiring-list li {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.expiring-list li:last-child {
  border-bottom: none;
}

.product-info {
  display: flex;
  justify-content: space-between;
}

.product-name {
  font-weight: bold;
}

.expiry-date {
  font-size: 12px;
  color: #e74c3c;
  margin-top: 5px;
}

.top-products-list {
  margin-top: 20px;
}
.product-list {
  list-style: none;
  padding: 0;
}
.product-list li {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
.product-info {
  display: flex;
  align-items: center;
}
.product-thumbnail {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 4px;
  margin-right: 10px;
}
.product-details {
  flex-grow: 1;
}
.product-name {
  font-weight: bold;
  display: block;
}
.product-price, .product-quantity {
  display: block;
  font-size: 12px;
  color: #777;
}

@media (max-width: 992px) {
  .dashboard-overview {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .dashboard-details {
    grid-template-columns: 1fr;
  }
  
  .chart-container {
    height: 250px;
  }
}

@media (max-width: 576px) {
  .dashboard-overview {
    grid-template-columns: 1fr;
  }
}

</style>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>