<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/productDetails.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="content">
    <h2>Product Details</h2>

    <div class="product-summary">
        <img src="<?= URLROOT ?>/public/uploads/<?= $data['product']->image ?>" alt="<?= $data['product']->name ?>" width="200">
        
        <table class="details-table">
            <tr>
                <th>Product Name</th>
                <td><?= $data['product']->name ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td>Rs <?= $data['product']->price ?></td>
            </tr>
            <tr>
                <th>Stock</th>
                <td><?= $data['product']->stock ?> units</td>
            </tr>
            <tr>
                <th>Expiration Date</th>
                <td><?= date('M d, Y', strtotime($data['product']->exp_date)) ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?= $data['product']->description ?></td>
            </tr>
        </table>
    </div>

    <h2>Farmer Information</h2>
    <div class="farmer-info">
        <img src="<?= URLROOT ?>/public/uploads/<?= $data['product']->farmerImage ?>" alt="<?= $data['product']->farmer ?>" width="100">

        <table class="details-table">
            <tr>
                <th>Name</th>
                <td><?= $data['product']->farmer ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= $data['product']->email ?></td>
            </tr>
            <tr>
                <th>Location</th>
                <td><?= $data['product']->location ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= $data['product']->phone ?></td>
            </tr>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
