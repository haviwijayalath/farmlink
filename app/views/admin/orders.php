<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/orders.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="admin-main-content">
  <h2>Orders</h2>

  <!-- Filter form -->
  <form class="filter-form" method="get" action="<?= URLROOT ?>/admins/orders">
    <div class="filter-group">
      <label for="status">Status</label>
      <select name="status" id="status">
        <option value=""  <?= $status==='' ? 'selected' : '' ?>>All</option>
        <option value="Pending"   <?= $status==='Pending'   ? 'selected' : '' ?>>Pending</option>
        <option value="Ready" <?= $status==='Ready' ? 'selected' : '' ?>>Ready</option>
        <option value="Ongoing"    <?= $status==='Ongoing'    ? 'selected' : '' ?>>Ongoing</option>
        <option value="Delivered" <?= $status==='Delivered' ? 'selected' : '' ?>>Delivered</option>
      </select>
    </div>

    <div class="filter-group">
      <label for="start_date">From</label>
      <input
        type="date"
        id="start_date"
        name="start_date"
        value="<?= htmlspecialchars($start_date) ?>"
      >
    </div>

    <div class="filter-group">
      <label for="end_date">To</label>
      <input
        type="date"
        id="end_date"
        name="end_date"
        value="<?= htmlspecialchars($end_date) ?>"
      >
    </div>

    <button type="submit">Filter</button>
  </form>

  <!-- Orders Table -->
  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ORDER ID</th>
          <th>CREATED</th>
          <th>CUSTOMER</th>
          <th>TOTAL (Rs)</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($orders)): ?>
          <?php foreach($orders as $o): ?>
            <tr>
              <td><?= htmlspecialchars($o->id) ?></td>
              <td><?= date('M d, Y', strtotime($o->created_at)) ?></td>
              <td><?= htmlspecialchars($o->buyerName) ?></td>
              <td><?= number_format($o->total_amount, 2) ?></td>
              <td>
                <span class="<?= strtolower($o->status) ?>">
                  <?= ucfirst(htmlspecialchars($o->status)) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
