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
                            <td><?= $user->status ?></td>
                            <td class="actions">
                                <form action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= strtolower($user->role) . 's' ?>">
                                    <input type="hidden" name="action" value="suspend">
                                    <button title="Suspend"><i class="fas fa-lock"></i></button>
                                </form>

                                <form action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= strtolower($user->role) . 's' ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button title="Approve"><i class="fas fa-check"></i></button>
                                </form>

                                <form action="<?= URLROOT ?>/adminControllers/changeStatus" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $user->id ?>">
                                    <input type="hidden" name="role" value="<?= strtolower($user->role) . 's' ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button title="Delete"><i class="fas fa-trash"></i></button>
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

<?php require APPROOT . '/views/inc/footer.php'; ?>