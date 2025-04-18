<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/publicProfile.css">
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<div class="profile-calendar">
  <!-- PROFILE PANEL -->
  <div class="profile-panel">
    <div class="profile-card">
      <!-- Rating Display -->
      <div class="profile-rating">
        <div class="stars">
          <?php 
            $rating = round($data['avg_rating'] ?? 0, 1);
            $fullStars = floor($rating);
            $halfStar = $rating - $fullStars >= 0.5;
            for ($i = 0; $i < $fullStars; $i++) echo '<i class="fa fa-star"></i>';
            if ($halfStar) echo '<i class="fa fa-star-half-alt"></i>';
            for ($i = $fullStars + $halfStar; $i < 5; $i++) echo '<i class="far fa-star"></i>';
          ?>
        </div>
        <span><?= $rating ?>/5</span>
      </div>

      <!-- Profile Info -->
      <img 
        src="<?= URLROOT ?>/public/uploads/consultants/<?= 
          !empty($data['image']) ? htmlspecialchars($data['image']) : 'placeholder.png' 
        ?>" 
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

      <!-- Rating Form -->
      <div class="rate-box">
        <form method="POST" action="<?= URLROOT ?>/consultants/rate">
          <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
              <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
              <label for="star<?= $i ?>"><i class="fa fa-star"></i></label>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="consultant_id" value="<?= $data['id'] ?>">
          <button type="submit" class="btn-rate">Rate</button>
        </form>
      </div>
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
            src="<?= URLROOT ?>/public/uploads/consultants/<?= 
                  !empty($data['image']) ? htmlspecialchars($data['image']) : 'placeholder.png' 
                ?>" 
            class="avatar" alt="Avatar"
          >
          <div class="poster-info">
            <strong><?= htmlspecialchars($data['name']); ?></strong><br>
            <small><?= date('M d, Y H:i', strtotime($post->created_at)); ?></small>
          </div>
        </div>
        <p class="post-content"><?= nl2br(htmlspecialchars($post->content)); ?></p>
        <?php if (!empty($post->attachments)): ?>
          <div class="post-attachments">
            <?php foreach ($post->attachments as $att): ?>
              <?php if (strpos($att->mime_type, 'image/') === 0): ?>
                <img 
                  src="<?= URLROOT ?>/public/uploads/consultants/posts/<?= htmlspecialchars($att->filename) ?>"
                  class="post-img" alt="Attachment"
                >
              <?php else: ?>
                <a 
                  href="<?= URLROOT ?>/public/uploads/consultants/posts/<?= htmlspecialchars($att->filename) ?>"
                  class="post-file" target="_blank"
                >
                  <?= htmlspecialchars($att->filename) ?>
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

<!-- corrected calendar script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectedDates = <?= isset($data['availability']) ? $data['availability'] : '[]' ?>;
  let current = selectedDates.length 
    ? new Date(selectedDates[0]) 
    : new Date();

  const calEl = document.getElementById('public-calendar');
  const hdrEl = document.getElementById('currentMonthYear');

  function draw(year, month) {
    calEl.innerHTML = '';
    const names = [ "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec" ];
    hdrEl.textContent = `${names[month]} ${year}`;

    ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"].forEach(day => {
      const h = document.createElement('div');
      h.className = 'calendar-header';
      h.textContent = day;
      calEl.appendChild(h);
    });

    const firstDay = new Date(year, month, 1).getDay();
    const prevLast = new Date(year, month, 0).getDate();
    for (let i = 0; i < firstDay; i++) {
      const blank = document.createElement('div');
      blank.className = 'calendar-day inactive';
      blank.textContent = prevLast - firstDay + i + 1;
      calEl.appendChild(blank);
    }

    const lastDate = new Date(year, month + 1, 0).getDate();
    for (let d = 1; d <= lastDate; d++) {
      const cell = document.createElement('div');
      cell.className = 'calendar-day';
      cell.textContent = d;
      const ds = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      if (selectedDates.includes(ds)) cell.classList.add('selected');
      calEl.appendChild(cell);
    }
  }

  document.getElementById('prevMonth').onclick = () => {
    current.setMonth(current.getMonth() - 1);
    draw(current.getFullYear(), current.getMonth());
  };
  document.getElementById('nextMonth').onclick = () => {
    current.setMonth(current.getMonth() + 1);
    draw(current.getFullYear(), current.getMonth());
  };

  draw(current.getFullYear(), current.getMonth());
});
</script>

<!-- Font Awesome for star icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php require APPROOT . '/views/inc/footer.php'; ?>
