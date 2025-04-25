<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/complaints.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
  <header>
    <h1>Complaints</h1>
  </header>

  <section class="search-bar">
    <input 
      type="text" 
      id="complaint-search" 
      placeholder="Search complaints..." 
      onkeyup="filterComplaints()" 
    >
    <button onclick="filterComplaints()"><i class="fas fa-search"></i></button>
  </section>

  <section class="complaint-table">
    <table id="complaints-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Role</th>
          <th>Order ID</th>
          <th>Description</th>
          <th>Submitted</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($complaints)): ?>
          <?php foreach ($complaints as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c->id) ?></td>
              <td><?= htmlspecialchars($c->userName) ?></td>
              <td><?= ucwords(str_replace('_',' ',$c->userRole)) ?></td>
              <td><?= htmlspecialchars($c->order_id) ?></td>
              <td><?= nl2br(htmlspecialchars($c->description)) ?></td>
              <td><?= date('M d, Y H:i', strtotime($c->created_at)) ?></td>
              <td class="<?= strtolower($c->status) ?>">
                <?= ucfirst(htmlspecialchars($c->status)) ?>
              </td>
              <td>
                <?php if ($c->status !== 'resolved'): ?>
                  <form 
                    action="<?= URLROOT ?>/admins/resolveComplaint/<?= $c->id ?>" 
                    method="POST" 
                    style="display:inline;"
                    onsubmit="return confirm('Mark complaint #<?= $c->id ?> as resolved?')"
                  >
                    <button type="submit">Resolve</button>
                  </form>
                <?php else: ?>
                  <span class="resolved-flag">✔</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">No complaints found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
function filterComplaints() {
  const q = document.getElementById('complaint-search').value.toLowerCase();
  document.querySelectorAll('#complaints-table tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>
