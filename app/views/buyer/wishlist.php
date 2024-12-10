<?php require APPROOT . '/views/inc/buyerHeader.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/buyer/wishlist.css">

<?php require APPROOT . '/views/inc/sidebars/buyer_sidebar.php'; ?>

<div class="container">
    <h1>My Wishlist</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Stock Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['wishlistItems'] as $item): ?>
                <tr>
                    <td class="product-name">
                        <?= htmlspecialchars($item->name) ?>
                    </td>
                    <td>
                        <span class="price">
                            Rs.<?= number_format($item->price, 2) ?>
                        </span>
                    </td>
                    <td>
                        In Stock
                    </td>
                    <td>
                        <div class="added-date">
                            Expire : <?= htmlspecialchars($item->exp_date) ?>
                        </div>
                        <button class="add-to-cart">
                            Add to cart
                        </button>
                        <a href="<?= URLROOT ?>/Buyercontrollers/removeWishlist/<?= $item->wishlist_id ?>" >
                        <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>
