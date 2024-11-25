<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/complaints.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
    <header>
        <h1>Complaints</h1>
    </header>

    <section class="search-bar">
        <input type="text" placeholder="Search for complaints...">
        <button class="search-button">Search</button>
    </section>

    <section class="complaint-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>OrderID</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>8628318826</td>
                    <td>Judith A. Anderson</td>
                    <td>Level 3</td>
                    <td>Computer Lenovo E4412</td>
                    <td>In progress</td>
                    <td><button>Manage</button></td>
                </tr>
            </tbody>
        </table>
    </section>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
