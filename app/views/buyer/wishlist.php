<?php require APPROOT . '/views/inc/header.php'; ?>

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
            <tr>
                <td class="product-name">
                    Carrot
                </td>
                <td>
                    <span class="price">
                        Rs.300.00
                    </span>
                </td>
                <td>
                    In Stock
                </td>
                <td>
                    <div class="added-date">
                        Added on: December 5, 2024
                    </div>
                    <button class="add-to-cart">
                        Add to cart
                    </button>
                    <i class="fas fa-trash"></i>
                </td>
            </tr>

            <tr>
                <td class="product-name">
                    Gova
                </td>
                <td>
                    <span class="price">
                        Rs.200.00
                    </span>
                </td>
                <td>
                    Out of Stock
                </td>
                <td>
                    <div class="added-date">
                        Added on: December 2, 2024
                    </div>
                    <button class="add-to-cart">
                        Add to cart
                    </button>
                    <i class="fas fa-trash"></i>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<?php require APPROOT . '/views/inc/footer.php'; ?>
