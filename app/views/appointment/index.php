<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="container">
  <h1>Your Appointments</h1>
  <?php flash('appointment_message'); ?>
  
  <?php if(empty($data['appointments'])): ?>
    <p>No appointments found. Please book an appointment.</p>
  <?php else: ?>
    <form method="get" action="" class="filter-form">
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
          <th>Consultant</th>
          <th>Date</th>
          <th>Time</th>
          <th>Message</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data['appointments'] as $appointment): ?>
          <tr>
            <td><?= htmlspecialchars($appointment->consultant_name) ?></td>
            <td><?= htmlspecialchars($appointment->date) ?></td>
            <td><?= htmlspecialchars($appointment->time) ?></td>
            <td><?= htmlspecialchars($appointment->message) ?></td>
            <td><?= htmlspecialchars($appointment->status) ?></td>
            <td>
              <?php if($appointment->status == 'Pending' || $appointment->status == 'Accepted'): ?>
                <a href="<?= URLROOT ?>/appointments/cancel/<?= $appointment->id ?>" class="btn btn-warning" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
              <?php else: ?>
                <span>No action</span>
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
    font-family: Arial, sans-serif;
  }
  .appointment-table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
  }
  .appointment-table thead tr {
    background-color: #f5f5f5;
  }
  .appointment-table th,
  .appointment-table td {
    border: 1px solid #ddd;
    padding: 10px;
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
  .btn-warning {
    background-color: #ffc107;
    color: #212529;
  }
  .btn-warning:hover {
    background-color: #e0a800;
  }
  .filter-form {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
  font-family: Arial, sans-serif;
}

.filter-form label {
  font-weight: bold;
  color: #333;
  font-size: 0.95rem;
}

.filter-form select {
  padding: 6px 10px;
  font-size: 0.95rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #fff;
  transition: border-color 0.2s ease;
}

.filter-form select:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
