<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="container">
  <h1>Our Consultants</h1>
  <div class="cards-container">
    <?php if(!empty($data['consultants'])): ?>
      <?php foreach($data['consultants'] as $consultant): ?>
        <div class="card">
          <img src="<?= URLROOT ?>/public/uploads/consultants/<?= !empty($consultant->image) ? $consultant->image : 'placeholder.png' ?>" alt="<?= htmlspecialchars($consultant->name) ?>">
          <h3>
            <a href="<?= URLROOT ?>/consultants/publicProfile/<?= $consultant->id ?>">
              <?= htmlspecialchars($consultant->name) ?>
            </a>
          </h3>
          <p><strong>Specialization:</strong> <?= htmlspecialchars($consultant->specialization) ?></p>
          <p><strong>Experience:</strong> <?= htmlspecialchars($consultant->experience) ?> years</p>
          <p><strong>Email:</strong> <?= htmlspecialchars($consultant->email) ?></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No consultants found.</p>
    <?php endif; ?>
  </div>
</div>

<style>
  /* Adjust the container to leave space for the fixed sidebar */
  .container {
    margin-left: 220px; /* Adjust this value based on your sidebar width */
    padding: 20px;
  }
  
  .cards-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: flex-start;
  }
  
  .card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: calc(33.33% - 20px);
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  
  .card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
  }
  
  .card h3 {
    font-size: 20px;
    margin-bottom: 10px;
  }
  
  .card p {
    font-size: 14px;
    color: #666;
    margin: 5px 0;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card {
      width: calc(50% - 20px);
    }
  }
  @media (max-width: 480px) {
    .card {
      width: 100%;
    }
  }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
