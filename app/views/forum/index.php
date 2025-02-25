<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat Forum</title>
  <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/forum.css">
  <style>
    .container { max-width: 900px; margin: 0 auto; padding: 20px; }
    .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-right: 5px; }
    .btn-primary { background-color: rgb(26, 161, 57); color: #fff; }
    .btn-secondary { background-color: rgb(26, 161, 57); color: #fff; }
    .btn-danger { background-color: #dc3545; color: #fff; }
    .form-group { margin-bottom: 15px; }
    textarea { width: 100%; padding: 8px; }
    .question-item { padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px; }
    .answers-list { margin-top: 10px; margin-left: 20px; }
    .answer-item { padding: 10px; border: 1px solid #eee; margin-bottom: 10px; border-radius: 4px; }
    .edit-form { margin-top: 10px; border: 1px solid #ccc; padding: 10px; border-radius: 4px; }
    .invalid-feedback { color: red; font-size: 0.9em; }
    .flash { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .flash-success { background-color: #d4edda; color: #155724; }
    .flash-danger { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Chat Forum</h1>
    
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
        <?php
          // If editing a question, hide the ask form.
          if(!isset($data['edit_question'])):
        ?>
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
      <h2>Questions and Answers</h2>
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
              <p><strong>Q:</strong> <?= htmlspecialchars($question->question); ?></p>
              <small>
                Asked by: <?= htmlspecialchars($question->farmer_name); ?> on <?= date("M d, Y", strtotime($question->createdAt)); ?>
              </small>
              <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'farmer' && isset($question->farmer_id) && $_SESSION['user_id'] == $question->farmer_id): ?>
                <br>
                <a href="<?= URLROOT; ?>/forums/editQuestion/<?= $question->id; ?>" class="btn btn-secondary">Edit Question</a>
                <a href="<?= URLROOT; ?>/forums/deleteQuestion/<?= $question->id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this question?');">Delete Question</a>
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
                      <p><strong>A:</strong> <?= htmlspecialchars($answer->answer); ?></p>
                      <small>
                        Answered by: <?= htmlspecialchars($answer->consultant_name); ?> on <?= date("M d, Y", strtotime($answer->createdAt)); ?>
                      </small>
                      <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'consultant' && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $answer->consultant_id): ?>
                        <br>
                        <a href="<?= URLROOT; ?>/forums/editAnswer/<?= $answer->ans_id; ?>" class="btn btn-secondary">Edit</a>
                        <a href="<?= URLROOT; ?>/forums/deleteAnswer/<?= $answer->ans_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this answer?');">Delete</a>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No answers yet for this question.</p>
              <?php endif; ?>
            </div>
            
            <!-- Answer Submission Form for this Question (ONLY for Consultants) -->
            <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'consultant'): ?>
              <?php 
                // Hide the new answer form if an answer is being edited for this question.
                if (!isset($data['edit_answer']) || (isset($data['edit_answer']) && $data['edit_answer']->q_id != $question->id)) : 
              ?>
                <div class="answer-form" style="margin-top: 15px;">
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
</body>
</html>
