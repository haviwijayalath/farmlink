<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/publicProfile.css">
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="profile-calendar">
  <!-- PROFILE PANEL -->
  <div class="profile-panel">
    <div class="profile-card">
      <!-- Average Rating Display -->
      <div class="profile-rating">
        <div class="stars">
          <?php 
            $avg    = round($data['avg_rating'] ?? 0, 1);
            $full   = floor($avg);
            $half   = ($avg - $full) >= .5;
            for ($i = 0; $i < $full; $i++)       echo '<i class="fa fa-star"></i>';
            if ($half)                          echo '<i class="fa fa-star-half-alt"></i>';
            for ($i = $full + $half; $i < 5; $i++) echo '<i class="far fa-star"></i>';
          ?>
        </div>
        <span class="rating-value"><?= $avg ?>/5</span>
      </div>

      <!-- Profile Info -->
      <img 
        src="<?= URLROOT ?>/public/uploads/consultants/<?= htmlspecialchars($data['image'] ?: 'placeholder.png') ?>"
        alt="<?= htmlspecialchars($data['name']) ?>"
        class="profile-pic"
      >
      <div class="profile-info">
        <h2><?= htmlspecialchars($data['name']) ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
        <p><strong>Specialization:</strong> <?= htmlspecialchars($data['specialization']) ?></p>
        <p><strong>Experience:</strong> <?= htmlspecialchars($data['experience']) ?> years</p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></p>
        <a href="<?= URLROOT ?>/appointments/book/<?= $data['id'] ?>" class="btn-appointment">
          Make Appointment
        </a>
      </div>

      <!-- Rating Form (only for farmers) -->
      <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'farmer'): ?>
        <div class="rate-box">
          <?php $your = (int)($data['user_rating'] ?? 0); ?>
          <form method="POST" action="<?= URLROOT ?>/consultants/rate">
            <div class="star-rating">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <input 
                  type="radio" 
                  id="star<?= $i ?>" 
                  name="rating" 
                  value="<?= $i ?>" 
                  <?= $i === $your ? 'checked' : '' ?>
                >
                <label for="star<?= $i ?>"><i class="fa fa-star"></i></label>
              <?php endfor; ?>
            </div>
            <input type="hidden" name="consultant_id" value="<?= $data['id'] ?>">
            <button type="submit" class="btn-rate" disabled>Rate</button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- CALENDAR PANEL -->
  <div class="calendar-panel">
    <div id="public-calendar-header">
      <button id="prevMonth" class="nav-btn">&lt;</button>
      <span id="currentMonthYear"></span>
      <button id="nextMonth" class="nav-btn">&gt;</button>
    </div>
    <div id="public-calendar" class="calendar-grid"></div>
    <div class="calendar-footer-note">Available dates</div>
  </div>
</div>

<!-- POSTS FEED -->
<div class="posts-feed">
  <h3>Latest Posts</h3>
  <?php if (!empty($data['posts'])): ?>
    <?php foreach ($data['posts'] as $post): ?>
      <div class="post-item">
        <div class="post-header">
          <img 
            src="<?= URLROOT ?>/public/uploads/consultants/<?= htmlspecialchars($data['image'] ?: 'placeholder.png') ?>"
            class="avatar" 
            alt="Avatar"
          >
          <div class="poster-info">
            <strong><?= htmlspecialchars($data['name']) ?></strong><br>
            <small><?= date('M d, Y H:i', strtotime($post->created_at)) ?></small>
          </div>
        </div>
        <p class="post-content"><?= nl2br(htmlspecialchars($post->content)) ?></p>
        <?php if (!empty($post->attachments)): ?>
          <div class="post-attachments">
            <?php foreach ($post->attachments as $att): ?>
              <?php if (strpos($att->mime_type, 'image/') === 0): ?>
                <img 
                  src="data:<?= $att->mime_type ?>;base64,<?= base64_encode($att->file_data) ?>"
                  class="post-img"
                  alt="<?= htmlspecialchars($att->filename) ?>"
                >
              <?php else: ?>
                <a 
                  href="data:<?= $att->mime_type ?>;base64,<?= base64_encode($att->file_data) ?>" 
                  download="<?= htmlspecialchars($att->filename) ?>"
                  class="post-file"
                >
                  <i class="fas fa-file-alt"></i> <?= htmlspecialchars($att->filename) ?>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="no-posts">No posts yet.</p>
  <?php endif; ?>
</div>

<!-- Star-rating & Calendar scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Enable Rate button when a star is picked
  const stars  = document.querySelectorAll('.star-rating input[name="rating"]');
  const button = document.querySelector('.btn-rate');
  function toggleRateBtn() {
    button.disabled = ! Array.from(stars).some(r => r.checked);
  }
  stars.forEach(r => r.addEventListener('change', toggleRateBtn));
  toggleRateBtn();

  // Calendar drawing (unchanged)
  const selectedDates = <?= $data['availability'] ?>;
  let current = selectedDates.length
    ? new Date(selectedDates[0])
    : new Date();
  const calEl = document.getElementById('public-calendar');
  const hdrEl = document.getElementById('currentMonthYear');

  function draw(year, month) {
    calEl.innerHTML = '';
    const names = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    hdrEl.textContent = `${names[month]} ${year}`;
    ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"].forEach(d => {
      const h = document.createElement('div');
      h.className = 'calendar-header';
      h.textContent = d;
      calEl.appendChild(h);
    });
    const first = new Date(year, month, 1).getDay();
    const prevLast = new Date(year, month, 0).getDate();
    for (let i = 0; i < first; i++) {
      const b = document.createElement('div');
      b.className = 'calendar-day inactive';
      b.textContent = prevLast - first + i + 1;
      calEl.appendChild(b);
    }
    const last = new Date(year, month + 1, 0).getDate();
    for (let d = 1; d <= last; d++) {
      const cell = document.createElement('div');
      cell.className = 'calendar-day';
      cell.textContent = d;
      const ds = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      if (selectedDates.includes(ds)) cell.classList.add('selected');
      calEl.appendChild(cell);
    }
  }
  document.getElementById('prevMonth').onclick = () => {
    current.setMonth(current.getMonth()-1);
    draw(current.getFullYear(), current.getMonth());
  };
  document.getElementById('nextMonth').onclick = () => {
    current.setMonth(current.getMonth()+1);
    draw(current.getFullYear(), current.getMonth());
  };
  draw(current.getFullYear(), current.getMonth());
});
</script>

<!-- Font Awesome for star icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php require APPROOT . '/views/inc/footer.php'; ?>
