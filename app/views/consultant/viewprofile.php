<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/viewprofile.css">
<!-- Font Awesome for star icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php require APPROOT . '/views/inc/sidebars/consultant.php'; ?>

<div class="profile-page">

  <!-- PROFILE CARD -->
  <div class="profile-card">
    <!-- ⭐ Average Rating Display -->
    <div class="profile-rating">
      <?php 
        $rating    = round($data['rating'] ?? 0, 1);
        $fullStars = floor($rating);
        $halfStar  = ($rating - $fullStars) >= 0.5;
      ?>
      <div class="stars">
        <?php 
          for ($i = 0; $i < $fullStars; $i++)      echo '<i class="fa fa-star"></i>';
          if ($halfStar)                           echo '<i class="fa fa-star-half-alt"></i>';
          for ($i = $fullStars + $halfStar; $i < 5; $i++) echo '<i class="far fa-star"></i>';
        ?>
      </div>
      <span class="rating-value"><?= $rating ?>/5</span>
    </div>

    <div class="profile-avatar">
      <img 
        src="<?= URLROOT ?>/public/uploads/consultants/<?= 
            !empty($data['image']) 
              ? htmlspecialchars($data['image']) 
              : 'placeholder.png' 
        ?>" 
        alt="<?= htmlspecialchars($data['name']) ?>">
    </div>

    <div class="profile-details">
      <h2><?= htmlspecialchars($data['name']); ?></h2>
      <p><strong>Specialization:</strong> <?= htmlspecialchars($data['specialization']); ?></p>
      <p><strong>Experience:</strong> <?= htmlspecialchars($data['experience']); ?> years</p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($data['phone']); ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($data['email']); ?></p>
      <a href="<?= URLROOT ?>/consultants/editprofile" class="btn edit-btn">
        <i class="fas fa-user-edit"></i> Edit Profile
      </a>
    </div>
  </div>

  <!-- NEW POST CARD -->
  <div class="new-post-card">
    <?php flash('post_message'); ?>
    <h3><i class="fas fa-pencil-alt"></i> Create a Post</h3>
    <form action="<?= URLROOT ?>/consultants/viewprofile" method="POST" enctype="multipart/form-data">
      <textarea name="content" rows="4" placeholder="What's on your mind?" required></textarea>
      <label class="file-label">
        <i class="fas fa-paperclip"></i> Attach files
        <input type="file" name="attachments[]" multiple id="fileInput" accept="image/*" class="file-input">
      </label>

      <!-- PREVIEW CONTAINER -->
      <div id="previewContainer" style="margin-top:10px; display:none;">
        <strong>Preview:</strong><br>
        <div id="thumbs" style="display:flex; gap:10px;"></div>
      </div>

      <button type="submit" class="btn post-btn">
        <i class="fas fa-paper-plane"></i> Post
      </button>
    </form>
  </div>

  <!-- POSTS FEED -->
  <div class="posts-feed">
    <?php if (!empty($data['posts'])): ?>
      <?php foreach ($data['posts'] as $post): ?>
        <div class="post-card">
          <div class="post-header">
            <img 
              src="<?= URLROOT ?>/public/uploads/consultants/<?= 
                  !empty($data['image']) 
                    ? htmlspecialchars($data['image']) 
                    : 'placeholder.png' 
              ?>" 
              class="avatar" alt="<?= htmlspecialchars($data['name']) ?>">
            <div class="poster-info">
              <strong>
                <a href="<?= URLROOT ?>/consultants/publicProfile/<?= $_SESSION['user_id'] ?>">
                  <?= htmlspecialchars($data['name']); ?>
                </a>
              </strong><br>
              <small><?= date('M d, Y \a\t H:i', strtotime($post->created_at)); ?></small>
            </div>
          </div>
          <div class="post-content"><?= nl2br(htmlspecialchars($post->content)); ?></div>

          <!-- === ATTACHMENTS BLOCK (UPDATED) === -->
          <?php if (!empty($post->attachments)): ?>
            <div class="post-attachments">
              <?php foreach ($post->attachments as $att): ?>
                <?php if (strpos($att->mime_type, 'image/') === 0): ?>
                  <!-- Image attachment -->
                  <img 
                    src="data:<?= htmlspecialchars($att->mime_type) ?>;base64,<?= base64_encode($att->file_data) ?>" 
                    class="attachment-img" 
                    alt="<?= htmlspecialchars($att->filename) ?>">
                <?php else: ?>
                  <!-- Other file types -->
                  <a 
                    href="data:<?= htmlspecialchars($att->mime_type) ?>;base64,<?= base64_encode($att->file_data) ?>" 
                    download="<?= htmlspecialchars($att->filename) ?>" 
                    class="attachment-file"
                  >
                    <i class="fas fa-file-alt"></i> <?= htmlspecialchars($att->filename) ?>
                  </a>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <!-- ================================ -->

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="no-posts">No posts yet. Start by sharing an update!</p>
    <?php endif; ?>
  </div>

</div>

<script>
  // Thumbnail preview for the consultant’s own view
  document.getElementById('fileInput').addEventListener('change', function(evt) {
    const files = Array.from(evt.target.files);
    const previewContainer = document.getElementById('previewContainer');
    const thumbs = document.getElementById('thumbs');

    thumbs.innerHTML = '';
    if (files.length === 0) {
      previewContainer.style.display = 'none';
      return;
    }
    previewContainer.style.display = 'block';

    files.forEach(file => {
      if (!file.type.startsWith('image/')) return;
      const img = document.createElement('img');
      img.style.maxWidth = '150px';
      img.style.maxHeight = '150px';
      img.style.objectFit = 'cover';
      img.style.border = '1px solid #ccc';
      img.style.borderRadius = '4px';
      img.src = URL.createObjectURL(file);
      img.onload = () => URL.revokeObjectURL(img.src);
      thumbs.appendChild(img);
    });
  });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
