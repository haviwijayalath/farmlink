<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/usersfilter.css">

<form method="GET" action="<?= URLROOT ?>/adminControllers/filterUsers" class="search-bar">
    <select name="role">
        <option value="">Search by Role</option>
        <option value="buyers" <?= ($_GET['role'] ?? '') === 'buyers' ? 'selected' : '' ?>>Buyer</option>
        <option value="delivery_persons" <?= ($_GET['role'] ?? '') === 'delivery_persons' ? 'selected' : '' ?>>Delivery Person</option>
        <option value="farmers" <?= ($_GET['role'] ?? '') === 'farmers' ? 'selected' : '' ?>>Farmer</option>
        <option value="consultants" <?= ($_GET['role'] ?? '') === 'consultants' ? 'selected' : '' ?>>Consultant</option>
    </select>

    <select name="status">
        <option value="">All Statuses</option>
        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="suspended" <?= ($_GET['status'] ?? '') === 'suspended' ? 'selected' : '' ?>>Suspended</option>
        <option value="deleted" <?= ($_GET['status'] ?? '') === 'deleted' ? 'selected' : '' ?>>Deleted</option>
        <option value="approved" <?= ($_GET['status'] ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
    </select>

    <button type="submit">Filter</button>
    <a href="<?= URLROOT ?>/adminControllers/filterUsers" class="clear-button">Clear</a>
</form>