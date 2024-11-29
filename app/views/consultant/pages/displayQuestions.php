<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions List</title>
    <link rel="stylesheet" href="path/to/your/css/style.css"> <!-- Include any CSS -->
</head>
<body>
    <div class="container">
        <h1>Questions List</h1>
        
        <!-- Flash message for no data -->
        <?php if (isset($_SESSION['data_message'])): ?>
            <div class="<?php echo $_SESSION['data_message_class']; ?>">
                <?php echo $_SESSION['data_message']; ?>
            </div>
            <?php unset($_SESSION['data_message']); ?>
        <?php endif; ?>

        <!-- Check if questions array is not empty -->
        <?php if (!empty($data['questions'])): ?>
            <ul class="questions-list">
                <?php foreach ($data['questions'] as $question): ?>
                    <li class="question-item">
                        <p><?php echo htmlspecialchars($question['description']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No questions available to display.</p>
        <?php endif; ?>
    </div>
</body>
</html>
