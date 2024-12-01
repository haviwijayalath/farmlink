<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answers</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css"> <!-- Include your CSS -->
</head>
<body>
    <div class="container">
        <h1>Answers</h1>

        <!-- Flash message -->
        <?php if (isset($_SESSION['data_message'])): ?>
            <div class="<?php echo $_SESSION['data_message_class']; ?>">
                <?php echo $_SESSION['data_message']; ?>
            </div>
            <?php unset($_SESSION['data_message']); ?>
        <?php endif; ?>

        <!-- Check if answers exist -->
        <?php if (!empty($data['answers'])): ?>
            <ul class="answers-list">
                <?php foreach ($data['answers'] as $answer): ?>
                    <li class="answer-item">
                        <p><?php echo htmlspecialchars($answer->description); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No answers found for this question.</p>
        <?php endif; ?>
    </div>
</body>
</html>
