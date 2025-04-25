<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/filtering.css">

<form method="GET" action="<?= URLROOT ?>/admins/filterReports">
    <section class="search-bar">

        <select name="role">
            <option value="">Search by Role</option>
            <option value="buyer" <?= ($_GET['role'] ?? '') === 'buyer' ? 'selected' : '' ?>>Famer</option>
            <option value="dperson" <?= ($_GET['role'] ?? '') === 'dperson' ? 'selected' : '' ?>>Delivery Person</option>
        </select>


        <button type="submit">Filter</button>

        <!-- Clear Filter button -->
        <a href="<?= URLROOT ?>/admins/filterComplaints" class="clear-button">Clear</a>
    </section>
</form>
