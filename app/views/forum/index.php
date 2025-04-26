<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebars/common_sidebar.php'; ?>

<div class="content-wrapper">
  <div class="container">
    
    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash_message'])): ?>
      <div class="flash <?= $_SESSION['flash_message_class']; ?>">
        <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
      </div>
    <?php endif; ?>

    <!-- Ask a Question Section (ONLY for Farmers) -->
    <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'farmer'): ?>
      <section id="ask-question">
        <h2>Ask a Question</h2>
        <?php if(!isset($data['edit_question'])): ?>
          <form action="<?= URLROOT; ?>/forums/ask" method="POST">
            <div class="form-group">
              <label for="question">Your Question:</label>
              <textarea name="question" id="question" rows="5" placeholder="Type your question here..." class="<?= !empty($data['question_err']) ? 'is-invalid' : ''; ?>"><?= htmlspecialchars($data['question'] ?? ''); ?></textarea>
              <span class="invalid-feedback"><?= $data['question_err'] ?? ''; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Submit Question</button>
          </form>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <!-- Questions and Answers List Section -->
    <section id="questions-list">
      <h2>Chat Forum</h2>
      <?php if(!empty($data['questions'])): ?>
        <?php foreach($data['questions'] as $question): ?>
          <div class="question-item">
            <?php if(isset($data['edit_question']) && $data['edit_question']->id == $question->id): ?>
              <!-- Inline Edit Form for the Question -->
              <form action="<?= URLROOT; ?>/forums/updateQuestion/<?= $question->id; ?>" method="POST">
                <div class="form-group">
                  <label for="edit_question_<?= $question->id; ?>">Edit Question:</label>
                  <textarea name="question" id="edit_question_<?= $question->id; ?>" rows="5" class="<?= !empty($data['question_err']) ? 'is-invalid' : ''; ?>"><?= htmlspecialchars($data['edit_question']->question); ?></textarea>
                  <span class="invalid-feedback"><?= $data['question_err'] ?? ''; ?></span>
                </div>
                <button type="submit" class="btn btn-primary">Update Question</button>
                <a href="<?= URLROOT; ?>/forums/index" class="btn btn-secondary">Cancel</a>
              </form>
            <?php else: ?>
              <!-- Questioner Meta (Profile Picture, Plain Name, Date) -->
              <div class="user-meta">
                <img src="<?= URLROOT . '/uploads/consultant/profile/' . htmlspecialchars($question->farmer_profile_picture); ?>" alt="Profile Picture" class="profile-pic">
                <span class="profile-name"><?= htmlspecialchars($question->farmer_name); ?></span>
                <span class="meta-date"> • <?= date("M d, Y", strtotime($question->createdAt)); ?></span>
              </div>
              <!-- Question Text -->
              <p class="question-text"><strong>Q:</strong> <?= htmlspecialchars($question->question); ?></p>
              <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'farmer' && $_SESSION['user_id'] == $question->farmer_id): ?>
                <div class="question-actions">
                  <a href="<?= URLROOT; ?>/forums/editQuestion/<?= $question->id; ?>" class="btn btn-secondary action-btn"><i class="fas fa-edit"></i></a>
                  <a href="<?= URLROOT; ?>/forums/deleteQuestion/<?= $question->id; ?>" class="btn btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this question?');"><i class="fas fa-trash-alt"></i></a>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <!-- Answers List for this Question -->
            <div class="answers-list">
              <?php if(!empty($question->answers)): ?>
                <?php foreach($question->answers as $answer): ?>
                  <div class="answer-item">
                    <?php if(isset($data['edit_answer']) && $data['edit_answer']->ans_id == $answer->ans_id): ?>
                      <!-- Inline Edit Form for the Answer -->
                      <form action="<?= URLROOT; ?>/forums/updateAnswer/<?= $answer->ans_id; ?>" method="POST">
                        <div class="form-group">
                          <label for="edit_answer_<?= $answer->ans_id; ?>">Edit Answer:</label>
                          <textarea name="answer" id="edit_answer_<?= $answer->ans_id; ?>" rows="3" class="<?= !empty($data['answer_err']) ? 'is-invalid' : ''; ?>"><?= htmlspecialchars($data['edit_answer']->answer); ?></textarea>
                          <span class="invalid-feedback"><?= $data['answer_err'] ?? ''; ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Answer</button>
                        <a href="<?= URLROOT; ?>/forums/index" class="btn btn-secondary">Cancel</a>
                      </form>
                    <?php else: ?>
                      <!-- Answerer Meta (Profile Picture, Clickable Consultant Name, Date) -->
                      <div class="user-meta">
                        <img src="<?= URLROOT . '/uploads/consultants/' . htmlspecialchars($answer->consultant_profile_picture); ?>" alt="Profile Picture" class="profile-pic">
                        <a href="<?= URLROOT ?>/consultants/publicProfile/<?= $answer->consultant_id ?>" class="profile-link"><?= htmlspecialchars($answer->consultant_name); ?></a>
                        <span class="meta-date"> • <?= date("M d, Y", strtotime($answer->createdAt)); ?></span>
                      </div>
                      <!-- Answer Text -->
                      <p class="answer-text"><strong>A:</strong> <?= htmlspecialchars($answer->answer); ?></p>
                      <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'consultant' && $_SESSION['user_id'] == $answer->consultant_id): ?>
                        <div class="answer-actions">
                          <a href="<?= URLROOT; ?>/forums/editAnswer/<?= $answer->ans_id; ?>" class="btn btn-secondary action-btn"><i class="fas fa-edit"></i></a>
                          <a href="<?= URLROOT; ?>/forums/deleteAnswer/<?= $answer->ans_id; ?>" class="btn btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this answer?');"><i class="fas fa-trash-alt"></i></a>
                        </div>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="no-answers">No answers yet for this question.</p>
              <?php endif; ?>
            </div>
            
            <!-- Answer Submission Form for this Question (ONLY for Consultants) -->
            <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'consultant'): ?>
              <?php if(!isset($data['edit_answer']) || $data['edit_answer']->q_id != $question->id): ?>
                <div class="answer-form">
                  <h4>Your Answer:</h4>
                  <form action="<?= URLROOT; ?>/forums/answer" method="POST">
                    <input type="hidden" name="question_id" value="<?= $question->id; ?>">
                    <div class="form-group">
                      <textarea name="answer" rows="3" placeholder="Type your answer here..."><?= htmlspecialchars($data['answer'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Answer</button>
                  </form>
                </div>
              <?php endif; ?>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No questions available.</p>
      <?php endif; ?>
    </section>
  </div>
</div>

<style>
  /* Content wrapper for extra left spacing */
  .content-wrapper {
    margin-left: 240px;
    margin-top: 70px;
    padding: 20px;
  }
  
  /* Main container styling */
  .container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    background-color: #ffffff;
  }
  
  h1, h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
  }
  
  /* Flash messages */
  .flash {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    text-align: center;
  }
  .flash-success { background-color: #d4edda; color: #155724; }
  .flash-danger { background-color: #f8d7da; color: #721c24; }
  
  /* Question and answer items */
  .question-item, .answer-item {
    background: #fff;
    padding: 15px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    position: relative;
  }
  
  .question-text, .answer-text {
    margin: 0 0 10px;
    font-size: 1rem;
  }
  
  /* User meta information */
  .user-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 10px;
  }
  
  .profile-pic {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #ccc;
  }
  
  .profile-name {
    font-weight: bold;
    color: #333;
  }
  
  .profile-link {
    color: #007BFF;
    text-decoration: none;
    font-weight: bold;
    font-size: 0.9rem;
  }
  
  .profile-link:hover {
    text-decoration: underline;
  }
  
  .meta-date {
    font-size: 0.85rem;
    color: #777;
  }
  
  /* Form groups */
  .form-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
  }
  
  .form-group label {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    resize: vertical;
  }
  
  .is-invalid { border-color: red; }
  
  .invalid-feedback {
    color: red;
    font-size: 0.9rem;
    margin-top: 5px;
  }
  
  /* Action buttons */
  .btn {
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    margin: 2px;
    display: inline-block;
  }
  
  .btn-primary { background-color:rgb(31, 156, 38); color: #fff; border: none; }
  .btn-secondary { background-color:rgb(236, 184, 11); color: #fff; }
  .btn-danger { background-color: #dc3545; color: #fff; }
  .btn-warning { background-color: #ffc107; color: #212529; }
  
  /* Action buttons container: position buttons to right */
  .question-actions, .answer-actions {
    position: absolute;
    top: 5px;
    right: 5px;
  }
  
  /* Answer submission form styling */
  .answer-form {
    margin-top: 15px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 4px;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .content-wrapper { margin-left: 0; padding: 10px; }
  }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
