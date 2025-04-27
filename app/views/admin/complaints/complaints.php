<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/complaints.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
    <header>
        <h1>Complaints</h1>
    </header>

    <div class="filters">

    <select id="roleFilter" onchange="filterComplaints()">
        <option value="">All Roles</option>
        <option value="buyer">Buyer</option>
        <option value="dperson">Delivery Person</option>
    </select>

    <select id="statusFilter" onchange="filterComplaints()">
        <option value="">All Statuses</option>
        <option value="new">New</option>
        <option value="resolved">Resolved</option>
    </select>
</div>


    <section class="complaint-table">
        <table id="complaintsTable">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint By</th>
                    <th>Role</th>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data['complaints']) && is_array($data['complaints'])): ?>
            <?php foreach ($data['complaints'] as $complaint): ?>
                <tr>
                    <td>
                    <a href="<?= URLROOT ?>/admins/show/<?= $complaint->complaint_id ?>" class="btn-complaint-id">
                        <?= $complaint->complaint_id ?>
                    </a>
                    </td>

                    <td>
                        <?php if ($complaint->role === 'dperson'): ?>
                            <?= $complaint->delivery_name ?>
                        <?php elseif ($complaint->role === 'buyer'): ?>
                            <?= $complaint->buyer_name ?>
                        <?php else: ?>
                            Unknown
                        <?php endif; ?>
                    </td>
                    <td><?= ucfirst($complaint->role) ?></td>
                    <td><?= $complaint->order_id ?></td>
                    <td><?= $complaint->status ?></td>
                    <td>
                        <a href="<?= URLROOT ?>/admins/manageComplaint/<?= $complaint->complaint_id ?>" class="btn-manage">Manage</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="6">No complaints found.</td>
            </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </section>
</div>

<script>
    function filterComplaints() {
        let input = document.getElementById('complaintSearch').value.toLowerCase();
        let rows = document.querySelectorAll('#complaintsTable tbody tr');
        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
        });
    }

    function filterComplaints() {
    let searchInput = document.getElementById('complaintSearch').value.toLowerCase();
    let roleFilter = document.getElementById('roleFilter').value.toLowerCase();
    let statusFilter = document.getElementById('statusFilter').value.toLowerCase();

    let rows = document.querySelectorAll('#complaintsTable tbody tr');

    rows.forEach(row => {
        let textMatch = row.innerText.toLowerCase().includes(searchInput);

        let roleCell = row.querySelector('td:nth-child(3)');
        let statusCell = row.querySelector('td:nth-child(5)');

        let roleMatch = !roleFilter || (roleCell && roleCell.innerText.toLowerCase() === roleFilter);
        let statusMatch = !statusFilter || (statusCell && statusCell.innerText.toLowerCase() === statusFilter);

        if (textMatch && roleMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>


<?php require APPROOT . '/views/inc/footer.php'; ?>
