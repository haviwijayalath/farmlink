<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send an Answer</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css">
</head>
<body>
    <div class="container">
        <h1>Send an Answer</h1>
        <?php if (!empty(flash('Succesfull')) || !empty(flash('Unsuccesfull'))) : ?>
            <div class="alert"><?php echo flash('Succesfull') ?: flash('Unsuccesfull'); ?></div>
        <?php endif; ?>
        <form action="<?php echo URLROOT; ?>/pages/sendAnswer" method="POST">
            <div class="form-group">
                <label for="answer">Your Answer</label>
                <textarea 
                    name="answer" 
                    id="answer" 
                    class="form-control <?php echo (!empty($data['answer_err'])) ? 'is-invalid' : ''; ?>"
                ><?php echo htmlspecialchars($data['answer']); ?></textarea>
                <span class="invalid-feedback"><?php echo $data['answer_err']; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Submit Answer</button>
        </form>
    </div>
</body>
</html>
