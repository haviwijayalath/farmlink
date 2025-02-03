<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat Forum</title>
  <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css">
  <!-- Inline styles for demonstration. Remove and move to your CSS file as needed. -->
  <style>
    .container { max-width: 800px; margin: 0 auto; padding: 20px; }
    .btn { padding: 8px 12px; text-decoration: none; background-color: #007BFF; color: #fff; border-radius: 4px; }
    .btn-secondary { background-color: #6c757d; }
    .form-group { margin-bottom: 15px; }
    textarea { width: 100%; padding: 8px; }
    .questions-list, .answers-list { list-style: none; padding: 0; }
    .question-item, .answer-item { padding: 10px; border: 1px solid #ddd; margin-bottom: 10px; border-radius: 4px; }
    .invalid-feedback { color: red; font-size: 0.9em; }
    .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .alert-success { background-color: #d4edda; color: #155724; }
    .alert-danger { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Chat Forum</h1>

    <!-- Flash Message -->
    <?php if(isset($_SESSION['flash_message'])): ?>
      <div class="alert <?= $_SESSION['flash_message_class']; ?>">
        <?php 
          echo $_SESSION['flash_message']; 
          unset($_SESSION['flash_message']);
        ?>
      </div>
    <?php endif; ?>

    <!-- Ask a Question Section (For Farmers) -->
    <section id="ask-question">
      <h2>Ask a Question</h2>
      <form action="<?= URLROOT; ?>/forum/ask" method="POST">
        <div class="form-group">
          <label for="question">Your Question:</label>
          <textarea 
            name="question" 
            id="question" 
            class="form-control <?= !empty($data['question_err']) ? 'is-invalid' : ''; ?>" 
            rows="5" 
            placeholder="Type your question here..."><?= htmlspecialchars($data['question'] ?? ''); ?></textarea>
          <span class="invalid-feedback"><?= $data['question_err'] ?? ''; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit Question</button>
      </form>
    </section>

    <!-- Questions List Section -->
    <section id="questions-list">
      <h2>Questions List</h2>
      <?php if(!empty($data['questions'])): ?>
        <ul class="questions-list">
          <?php foreach($data['questions'] as $question): ?>
            <li class="question-item">
              <p><strong>Q:</strong> <?= htmlspecialchars($question->question); ?></p>
              <!-- Link to view answers for this question -->
              <a href="<?= URLROOT; ?>/forum/index/<?= $question->id; ?>" class="btn btn-secondary">View Answers</a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p>No questions available.</p>
      <?php endif; ?>
    </section>

    <!-- Answers Section -->
    <?php if(isset($data['current_question_id'])): ?>
      <section id="answers">
        <h2>Answers for: 
          <?= htmlspecialchars($data['current_question_text'] ?? 'Selected Question'); ?>
        </h2>
        <?php if(!empty($data['answers'])): ?>
          <ul class="answers-list">
            <?php foreach($data['answers'] as $answer): ?>
              <li class="answer-item">
                <p><strong>A:</strong> <?= htmlspecialchars($answer->answer); ?></p>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>No answers found for this question.</p>
        <?php endif; ?>
      </section>

      <!-- Send an Answer Section (For Consultants) -->
      <section id="send-answer">
        <h2>Send an Answer</h2>
        <form action="<?= URLROOT; ?>/forum/answer" method="POST">
          <!-- Pass the current question id in a hidden field -->
          <input type="hidden" name="question_id" value="<?= $data['current_question_id']; ?>">
          <div class="form-group">
            <label for="answer">Your Answer:</label>
            <textarea 
              name="answer" 
              id="answer" 
              class="form-control <?= !empty($data['answer_err']) ? 'is-invalid' : ''; ?>" 
              rows="5" 
              placeholder="Type your answer here..."><?= htmlspecialchars($data['answer'] ?? ''); ?></textarea>
            <span class="invalid-feedback"><?= $data['answer_err'] ?? ''; ?></span>
          </div>
          <button type="submit" class="btn btn-primary">Submit Answer</button>
        </form>
      </section>
    <?php endif; ?>

  </div>
</body>
</html>
