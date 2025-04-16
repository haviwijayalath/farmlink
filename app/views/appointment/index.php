<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="container">
  <h1>Your Appointments</h1>
  <?php flash('appointment_message'); ?>
  
  <?php if(empty($data['appointments'])): ?>
    <p>No appointments found. Please book an appointment.</p>
  <?php else: ?>
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
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
