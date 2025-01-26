<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Forum</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css"> <!-- Link your CSS -->
</head>
<body>
    <div class="container">
        <h1>Chat Forum</h1>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert <?php echo $_SESSION['flash_message_class']; ?>">
                <?php echo $_SESSION['flash_message']; ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <!-- Ask a Question Section -->
        <section id="ask-question">
            <h2>Ask a Question</h2>
            <form action="<?php echo URLROOT; ?>/askQuestions" method="POST">
                <div class="form-group">
                    <label for="farmer_id">Farmer ID:</label>
                    <input 
                        type="text" 
                        name="farmer_id" 
                        id="farmer_id" 
                        class="form-control <?php echo (!empty($data['farmer_id_err'] ?? '')) ? 'is-invalid' : ''; ?>" 
                        value="<?php echo htmlspecialchars($data['farmer_id'] ?? ''); ?>"      
                    >
                </div>
                <div class="form-group">
                    <label for="question">Your Question:</label>
                    <textarea 
                        name="question" 
                        id="question" 
                        class="form-control <?php echo (!empty($data['question_err'] ?? '')) ? 'is-invalid' : ''; ?>" 
                        rows="5"><?php echo htmlspecialchars($data['question'] ?? ''); ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['question_err'] ?? ''; ?></span>
                </div>
                <button type="submit" class="btn btn-primary">Submit Question</button>
            </form>
        </section>

        <!-- Questions List Section -->
        <section id="questions-list">
            <h2>Questions List</h2>
            <?php if (!empty($data['questions'] ?? [])): ?>
                <ul class="questions-list">
                    <?php foreach ($data['questions'] as $question): ?>
                        <li class="question-item">
                            <p><?php echo htmlspecialchars($question['description'] ?? ''); ?></p>
                            <a href="<?php echo URLROOT; ?>/pages/forum/<?php echo $question['id'] ?? ''; ?>" class="btn btn-secondary">View Answers</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No questions available.</p>
            <?php endif; ?>
        </section>

        <!-- Answers Section -->
        <section id="answers">
            <h2>Answers</h2>
            <?php if (!empty($data['answers'] ?? [])): ?>
                <ul class="answers-list">
                    <?php foreach ($data['answers'] as $answer): ?>
                        <li class="answer-item">
                            <p><?php echo htmlspecialchars($answer->description ?? ''); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No answers found for this question.</p>
            <?php endif; ?>
        </section>

        <!-- Send an Answer Section -->
        <section id="send-answer">
            <h2>Send an Answer</h2>
            <form action="<?php echo URLROOT; ?>/pages/forum" method="POST">
                <div class="form-group">
                    <label for="answer">Your Answer:</label>
                    <textarea 
                        name="answer" 
                        id="answer" 
                        class="form-control <?php echo (!empty($data['answer_err'] ?? '')) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($data['answer'] ?? ''); ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['answer_err'] ?? ''; ?></span>
                </div>
                <button type="submit" class="btn btn-primary">Submit Answer</button>
            </form>
        </section>
    </div>
</body>
</html>
