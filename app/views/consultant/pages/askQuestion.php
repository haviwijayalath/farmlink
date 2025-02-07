<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask a Question</title>
    <link rel="stylesheet" href="<?= URLROOT ?>/public/css/consultants/pages.css" <!-- Link your CSS -->
</head>
<body>
    <div class="container">
        <h1>Ask a Question</h1>

        <!-- Flash message -->
        <?php if (isset($_SESSION['question_message'])): ?>
            <div class="alert <?php echo $_SESSION['question_message_class']; ?>">
                <?php echo $_SESSION['question_message']; ?>
            </div>
            <?php unset($_SESSION['question_message']); ?>
        <?php endif; ?>

        <!-- Question form -->
        <form action="<?php echo URLROOT; ?>/askQuestions" method="POST">
            <div class="form-group">
                <label for="farmer_id">Farmer ID:</label>
                <input 
                    type="text" 
                    name="farmer_id" 
                    id="farmer_id" 
                    class="form-control <?php echo (!empty($data['farmer_id_err'])) ? 'is-invalid' : ''; ?>" 
                    value="<?php echo htmlspecialchars($data['farmer_id']); ?>" 
                    readonly>
                <!-- Farmer ID is read-only as it’s likely pre-filled -->
            </div>

            <div class="form-group">
                <label for="question">Your Question:</label>
                <textarea 
                    name="question" 
                    id="question" 
                    class="form-control <?php echo (!empty($data['question_err'])) ? 'is-invalid' : ''; ?>" 
                    rows="5"><?php echo htmlspecialchars($data['question']); ?></textarea>
                <span class="invalid-feedback"><?php echo $data['question_err']; ?></span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
