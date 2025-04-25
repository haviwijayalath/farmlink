<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/complaints.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="container">
    <header>
        <h1>Reports</h1>
    </header>

    <?php require APPROOT . '/views/admin/filter_repots.php'; ?>

    <section class="complaint-table">
        <table id="complaintsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($data['users'])): ?>
                    <?php foreach ($data['users'] as $user): ?>
                        <tr>
                            <td><?= $user->name ?></td>
                            <td><?= $user->role ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No users found.</td></tr>
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
