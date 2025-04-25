<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/orders.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="admin-main-content" style="margin-left: 250px; padding: 20px;">
  <h2>Orders</h2>

  <!-- Status tabs (you can wire these up to filter client‑ or server‑side) -->
  <ul class="order-status">
    <li class="pending active">Pending</li>
    <li>Confirmed</li>
    <li>Picked</li>
    <li>Delivered</li>
    <li>Cancelled</li>
  </ul>

  <!-- Search & Date filters -->
  <div class="search-bar">
    <input type="text" id="order-id" placeholder="Search by order id" onkeyup="filterByOrderId()">
    <button onclick="filterByOrderId()"><i class="fas fa-search"></i></button>

    <div class="date-range-filter">
      <input type="date" id="start-date">
      <span>to</span>
      <input type="date" id="end-date">
      <button onclick="filterByDate()">Filter</button>
    </div>
  </div>

  <!-- Orders Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ORDER ID</th>
          <th>CREATED</th>
          <th>CUSTOMER</th>
          <th>TOTAL (Rs)</th>
          <th>PROFIT (Rs)</th>
          <th>STATUS</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody id="order-list">
        <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $o): ?>
            <tr class="order">
              <td>
                <a href="<?= URLROOT ?>/admins/order_details/<?= $o->id ?>">
                  <?= htmlspecialchars($o->id) ?>
                </a>
              </td>
              <td><?= date('M d, Y', strtotime($o->created_at)) ?></td>
              <td><?= htmlspecialchars($o->buyerName) ?></td>
              <td><?= number_format($o->total_amount, 2) ?></td>
              <td>
                <!-- if you have a profit column, use it; otherwise blank or same as total -->
                <?= isset($o->profit)
                    ? number_format($o->profit, 2)
                    : '—' ?>
              </td>
              <td><span class="<?= strtolower($o->status) ?>">
                  <?= ucfirst(htmlspecialchars($o->status)) ?>
                </span>
              </td>
              <td>
                <a href="<?= URLROOT ?>/admins/order_details/<?= $o->id ?>" class="view-details">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
  function filterByDate() {
    const start = document.getElementById('start-date').value;
    const end   = document.getElementById('end-date').value;
    document.querySelectorAll('#order-list .order').forEach(row => {
      const date = row.cells[1].textContent;
      row.style.display =
        (!start || date >= start) && (!end || date <= end)
        ? '' : 'none';
    });
  }

  function filterByOrderId() {
    const q = document.getElementById('order-id').value.toLowerCase();
    document.querySelectorAll('#order-list .order').forEach(row => {
      const id = row.cells[0].textContent.toLowerCase();
      row.style.display = id.includes(q) ? '' : 'none';
    });
  }
</script>
