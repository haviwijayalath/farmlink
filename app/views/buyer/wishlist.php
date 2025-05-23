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
        <?php if (!empty($data['wishlistItems'])): ?>
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
                        <?= number_format($item->stock) ?> KG
                    </td>
                    <td>
                        <div class="added-date">
                            Expire : <?= htmlspecialchars($item->exp_date) ?>
                        </div>
                        <form action="<?= URLROOT ?>/buyercontrollers/addToCart" method="POST" >
                            <input type="hidden" name="product_id" value="<?= $item->fproduct_id ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="add-to-cart">Add to cart</button>
                        </form>
                        <a href="<?= URLROOT ?>/Buyercontrollers/removeWishlist/<?= $item->wishlist_id ?>" >
                        <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No Wishlist item found.</td>
                    </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>
