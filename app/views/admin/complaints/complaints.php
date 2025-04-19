<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/complaints.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
    <header>
        <h1>Complaints</h1>
    </header>

    <?php require APPROOT . '/views/admin/complaints/filtering.php'; ?>

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

                    <td><?= $complaint->user_id ?></td>
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
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
