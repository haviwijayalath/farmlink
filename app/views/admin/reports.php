<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/reports1.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
    <header>
        <h1>Reports</h1>
    </header>
    <section class="complaint-table">
        <h2>Farmers (Total Revenue: <?= number_format($data['totalFarmerRevenue'], 2) ?> Rs)</h2>
        <table id="farmersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['farmers'] as $farmer): ?>
                    <tr>
                        <td><?= htmlspecialchars($farmer->name) ?></td>
                        <td><?= number_format($farmer->revenues->total_farmer_fee, 2) ?> Rs</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Delivery Persons (Total Revenue: <?= number_format($data['totalDeliveryRevenue'], 2) ?> Rs)</h2>
        <table id="deliveryTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['deliveryPersons'] as $dp): ?>
                    <tr>
                        <td><?= htmlspecialchars($dp->name) ?></td>
                        <td><?= number_format($dp->revenues->total_delivery_fee, 2) ?> Rs</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>