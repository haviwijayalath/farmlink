<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>
<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<?php
$orderID = isset($data['orderID']) ? $data['orderID'] : '';
?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/review.css">

<div class="review-form-container" style="margin-top: 100px; margin-left: 450px;">
    <h2>Submit a Review</h2>

    <form action="<?= URLROOT ?>/ordercontrollers/submitReview" method="post" enctype="multipart/form-data">
        <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderID) ?>">

        <div class="form-group">
            <label for="order_id_display">Order ID</label>
            <input type="text" id="order_id_display" value="<?= htmlspecialchars($orderID) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="rating">Rate the Farmer:</label>
            <div class="star-rating">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>">
                    <label for="star<?= $i ?>">★</label>
                <?php endfor; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="description">Review Description (Optional):</label>
            <textarea name="description" id="description" rows="4" placeholder="Write your feedback here..." required></textarea>
        </div>

        <div class="form-group">
            <label for="images">Upload Images (Optional):</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*">
        </div>

        <button type="submit" class="submit-btn">Submit Review</button>
    </form>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
