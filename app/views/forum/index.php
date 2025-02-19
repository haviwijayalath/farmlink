<!-- File: app/views/forum/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chat Forum</title>
  <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css">
  <style>
    .container { max-width: 800px; margin: 0 auto; padding: 20px; }
    .btn { padding: 8px 12px; text-decoration: none; background-color: #007BFF; color: #fff; border-radius: 4px; }
    .form-group { margin-bottom: 15px; }
    textarea { width: 100%; padding: 8px; }
    .question-item, .answer-item { padding: 10px; border: 1px solid #ddd; margin-bottom: 10px; border-radius: 4px; }
    .answers-list { margin-left: 20px; }
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
        <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
      </div>
    <?php endif; ?>

    <!-- Ask a Question Section (For Farmers) -->
    <section id="ask-question">
      <h2>Ask a Question</h2>
      <form action="<?= URLROOT; ?>/forums/ask" method="POST">
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
      <h2>Questions and Answers</h2>
      <?php if(!empty($data['questions'])): ?>
        <?php foreach($data['questions'] as $question): ?>
          <div class="question-item">
            <p><strong>Q:</strong> <?= htmlspecialchars($question->question); ?></p>
            <small>Asked by: <?= htmlspecialchars($question->farmer_name); ?> on <?= date("M d, Y", strtotime($question->createdAt)); ?></small>
            
            <!-- Answers for this question -->
            <?php if(isset($question->answers) && !empty($question->answers)): ?>
              <div class="answers-list">
                <?php foreach($question->answers as $answer): ?>
                  <div class="answer-item">
                    <p><strong>A:</strong> <?= htmlspecialchars($answer->answer); ?></p>
                    <small>Answered by: <?= htmlspecialchars($answer->consultant_name); ?> on <?= date("M d, Y", strtotime($answer->createdAt)); ?></small>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p>No answers yet.</p>
            <?php endif; ?>
            
            <!-- Answer form for this question (for consultants) -->
            <div class="answer-form">
              <h4>Your Answer:</h4>
              <form action="<?= URLROOT; ?>/forums/answer" method="POST">
                <!-- Pass the question id -->
                <input type="hidden" name="question_id" value="<?= $question->id; ?>">
                <div class="form-group">
                  <textarea 
                    name="answer" 
                    class="form-control" 
                    rows="3" 
                    placeholder="Type your answer here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Answer</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No questions available.</p>
      <?php endif; ?>
    </section>

  </div>
</body>
</html>
