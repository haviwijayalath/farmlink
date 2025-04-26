<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="container">
  <h1>Your Appointments</h1>
  
  <?php flash('appointment_message'); ?>

  <?php if (empty($data['appointments'])): ?>
    <p>You have no appointments at the moment.</p>
  <?php else: ?>
    <form method="get" action="">
      <label for="status">Filter by Status:</label>
      <select name="status" id="status" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="Pending" <?= (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
        <option value="Accepted" <?= (isset($_GET['status']) && $_GET['status'] == 'Accepted') ? 'selected' : '' ?>>Accepted</option>
        <option value="Cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
        <option value="Declined" <?= (isset($_GET['status']) && $_GET['status'] == 'Declined') ? 'selected' : '' ?>>Declined</option>
      </select>
    </form>
    <br>

    <table class="appointment-table">
      <thead>
        <tr>
          <th>Farmer</th>
          <th>Date</th>
          <th>Time</th>
          <th>Message</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data['appointments'] as $appointment): ?>
          <tr>
            <td><?= htmlspecialchars($appointment->farmer_name) ?></td>
            <td><?= htmlspecialchars($appointment->date) ?></td>
            <td><?= htmlspecialchars($appointment->time) ?></td>
            <td><?= htmlspecialchars($appointment->message) ?></td>
            <td><?= htmlspecialchars($appointment->status) ?></td>
            <td>
              <?php if ($appointment->status == 'Pending'): ?>
                <a href="<?= URLROOT ?>/appointments/accept/<?= $appointment->id ?>" class="btn btn-success">Accept</a>
                <a href="<?= URLROOT ?>/appointments/decline/<?= $appointment->id ?>" class="btn btn-danger">Decline</a>
              <?php elseif ($appointment->status == 'Accepted'): ?>
                <a href="<?= URLROOT ?>/appointments/cancel/<?= $appointment->id ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                <?php elseif ($appointment->status === 'Cancelled'): ?>
                  <span class="text-muted">Cancelled</span>
                <?php else: ?>
                <span><?= htmlspecialchars($appointment->status) ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<style>
  .container {
    margin-left: 220px;
    margin-top: 70px;
    padding: 20px;
  }
  h1 {
    margin-bottom: 20px;
  }
  .appointment-table {
    width: 100%;
    border-collapse: collapse;
  }
  .appointment-table thead tr {
    background-color: #f5f5f5;
  }
  .appointment-table th,
  .appointment-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }
  .appointment-table th {
    font-weight: bold;
  }
  .appointment-table tr:nth-child(even) {
    background-color: #fafafa;
  }
  .btn {
    padding: 6px 12px;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    font-size: 0.9rem;
    margin: 2px;
  }
  .btn-success {
    background-color: #28a745;
  }
  .btn-danger {
    background-color: #dc3545;
  }
  .btn-warning {
    background-color: #ffc107;
    color: #212529;
  }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
