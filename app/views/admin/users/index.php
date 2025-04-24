<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/users.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-dashboard">
    <!-- Main Content -->
    <main>
        <div class="head">
            <h1>Users</h1>
            
        </div>

        <?php require APPROOT . '/views/admin/users/filtering.php'; ?>

        <!-- Users Table -->
        <section class="table-container">
            <table id="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($data['users'])): ?>
                    <?php foreach ($data['users'] as $user): ?>
                        <tr onclick="window.location='<?= URLROOT ?>/adminControllers/show/<?= $user->table ?>/<?= $user->id ?>'" style="cursor:pointer;">
                            <td><?= $user->name ?></td>
                            <td><?= $user->email ?></td>
                            <td><?= $user->phone ?></td>
                            <td><?= $user->role ?></td>
                            <td><?php echo $user->status == 'suspend' ? 'Suspended till ' . $user->suspend_date : $user->status; ?></td>
                            <td class="actions" onclick="event.stopPropagation()">
                                <button title="Suspend" onclick="showSuspendModal(<?= $user->id ?>, '<?= strtolower($user->role) . 's' ?>')">
                                    <i class="fas fa-lock"></i>
                                </button>

                                <form action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= strtolower($user->role) . 's' ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button title="Approve"><i class="fas fa-check"></i></button>
                                </form>

                                <form action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= strtolower($user->role) . 's' ?>">
                                    <input type="hidden" name="action" value="deactivate">
                                    <button type="submit" title="Deactivate" onclick="return confirm('Are you sure you want to deactivate this account?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No users found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="modal">
    <div class="modal-content">
        <h3>Suspend Until</h3>
        <form id="suspendForm" action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST">
            <input type="hidden" id="suspendUserId" name="id" value="">
            <input type="hidden" id="suspendUserRole" name="role" value="">
            <input type="hidden" name="action" value="suspend">
            <input type="date" id="suspendDate" name="suspend_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            <div class="modal-buttons">
                <button type="button" onclick="closeModal()" class="btn-cancel">Cancel</button>
                <button type="submit" onclick="return validateSuspendDate()" class="btn-confirm-suspend">Confirm Suspension</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
    }
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .modal-content h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .modal-content input[type="date"] {
        width: 90%;
        padding: 8px;
        margin: 10px 0px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .modal-buttons {
        margin-top: 15px;
    }
    .btn-cancel {
        background-color: #ccc;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }
    .btn-confirm-suspend {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }
    .actions button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        color: #555;
    }
    .actions button:hover {
        color: #000;
    }
</style>

<script>
    function showSuspendModal(userId, userRole) {
        document.getElementById('suspendUserId').value = userId;
        document.getElementById('suspendUserRole').value = userRole;
        document.getElementById('suspendModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('suspendModal').style.display = 'none';
    }
    
    function validateSuspendDate() {
        const dateInput = document.getElementById('suspendDate');
        if (!dateInput.value) {
            alert('Please select a suspension end date');
            return false;
        }
        return true;
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('suspendModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>