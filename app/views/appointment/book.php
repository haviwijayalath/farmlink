<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="appointment-container">
  <h1 class="page-title">
    Make an Appointment with <?= htmlspecialchars($data['consultant']->name); ?>
  </h1>
  
  <div class="consultant-details">
    <div class="consultant-image">
      <img 
        src="<?= URLROOT ?>/public/uploads/consultants/<?= 
          !empty($data['consultant']->image) 
            ? $data['consultant']->image 
            : 'placeholder.png' 
        ?>" 
        alt="<?= htmlspecialchars($data['consultant']->name); ?>"
      >
    </div>
    <div class="consultant-info">
      <p><strong>Specialization:</strong> <?= htmlspecialchars($data['consultant']->specialization); ?></p>
      <p><strong>Experience:</strong> <?= htmlspecialchars($data['consultant']->experience); ?> years</p>
    </div>
  </div>
  
  <form 
    action="<?= URLROOT; ?>/appointments/create/<?= $data['consultant']->id; ?>" 
    method="POST" 
    class="appointment-form"
  >
    <!-- DATE DROPDOWN -->
    <div class="form-group">
      <label for="appointment_date">Appointment Date</label>
      <select 
        name="appointment_date" 
        id="appointment_date"
        required
        class="<?= !empty($data['date_err']) ? 'is-invalid' : '' ?>"
      >
        <option value="">-- Select a Date --</option>
        <?php foreach($data['availableDates'] as $d): ?>
          <option 
            value="<?= $d ?>" 
            <?= (isset($data['appointment_date']) && $data['appointment_date']===$d) ? 'selected' : '' ?>
          >
            <?= date('F j, Y', strtotime($d)); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <span class="error"><?= $data['date_err'] ?? ''; ?></span>
    </div>

    <!-- TIME INPUT -->
    <div class="form-group">
      <label for="appointment_time">Appointment Time</label>
      <input 
        type="time" 
        name="appointment_time" 
        id="appointment_time" 
        value="<?= htmlspecialchars($data['appointment_time'] ?? '') ?>" 
        required
        class="<?= !empty($data['time_err']) ? 'is-invalid' : '' ?>"
      >
      <span class="error"><?= $data['time_err'] ?? ''; ?></span>
    </div>

    <!-- NOTES -->
    <div class="form-group">
      <label for="notes">Notes (Optional)</label>
      <textarea 
        name="notes" 
        id="notes" 
        rows="3" 
        placeholder="Enter any additional details..."
      ><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
      <span class="error"><?= $data['notes_err'] ?? ''; ?></span>
    </div>

    <button type="submit" class="btn btn-primary">Make Appointment</button>
  </form>
</div>

<style>
  .appointment-container {
    margin-left: 240px;
    margin-top: 70px;
    padding: 30px;
    max-width: 800px;
    background: #fdfdfd;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }
  .page-title {
    font-family: 'Segoe UI', sans-serif;
    font-size: 1.8rem;
    margin-bottom: 25px;
    text-align: center;
    color: #333;
  }
  .consultant-details {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 15px;
  }
  .consultant-image img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #007bff;
  }
  .consultant-info p {
    margin: 4px 0;
    font-size: 1rem;
    color: #555;
  }
  .appointment-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .form-group {
    display: flex;
    flex-direction: column;
  }
  .form-group label {
    font-weight: bold;
    margin-bottom: 6px;
    font-size: 1rem;
    color: #333;
  }
  .form-group select,
  .form-group input,
  .form-group textarea {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-family: inherit;
  }
  .form-group select.is-invalid,
  .form-group input.is-invalid {
    border-color: #e74c3c;
  }
  .form-group input:focus,
  .form-group textarea:focus,
  .form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.3);
  }
  .error {
    color: #e74c3c;
    font-size: 0.9rem;
    margin-top: 4px;
  }
  .btn {
    padding: 10px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-family: inherit;
    transition: background-color 0.3s ease;
    text-align: center;
    display: inline-block;
  }
  .btn-primary {
    background-color: #007bff;
    color: #fff;
  }
  .btn-primary:hover {
    background-color: #0056b3;
  }
  @media (max-width: 768px) {
    .appointment-container {
      margin-left: 0;
      padding: 20px;
    }
  }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
