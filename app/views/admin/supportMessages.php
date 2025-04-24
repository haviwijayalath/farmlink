<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/users.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-dashboard">
    <!-- Main Content -->
    <main>
        <?php flash('message_action'); ?>
        <div class="head">
            <h1>Support Messages</h1>
        </div>

        <!-- Filter Section -->
        <div class="search-filter-container">
            <div class="filter-dropdown">
                <select id="message-filter" onchange="filterMessages()">
                    <option value="all">All Messages</option>
                    <option value="activation">Account Activation Request</option>
                    <option value="other">Other Messages</option>
                </select>
            </div>
        </div>

        <!-- Messages Table -->
        <section class="table-container">
            <table id="messages-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $message): ?>
                        <tr class="message-row" data-name="<?= $message->name ?>">
                            <td><?= $message->name ?></td>
                            <td><?= $message->email ?></td>
                            <td><?= $message->message ?></td>
                            <td class="actions">
                                <button title="Reply" onclick="window.location.href='mailto:<?= $message->email ?>'">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                
                                <form action="<?= URLROOT ?>/adminControllers/clearMessage" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $message->id ?>">
                                    <button type="submit" title="Clear" onclick="return confirm('Are you sure you want to clear this message?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No messages found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<style>
    /* Additional styles for message table */
    #messages-table td:nth-child(3) {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #messages-table td:nth-child(3):hover {
        white-space: normal;
        overflow: visible;
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
    
    .actions button[title="Reply"] {
        color: #2563eb;
    }
    
    .actions button[title="Reply"]:hover {
        color: #1d4ed8;
    }
    
    .actions button[title="Clear"] {
        color: #e74c3c;
    }
    
    .actions button[title="Clear"]:hover {
        color: #c0392b;
    }
    
    .filter-dropdown {
        margin-bottom: 20px;
    }
    
    .filter-dropdown select {
        padding: 8px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background-color: #f9fafb;
        font-size: 14px;
        color: #2563eb;
        cursor: pointer;
        outline: none;
    }
    
    .filter-dropdown select:hover {
        border-color: #2563eb;
        background-color: #f3f4f6;
    }
</style>

<script>
    function filterMessages() {
        const filter = document.getElementById('message-filter').value;
        const rows = document.querySelectorAll('.message-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'activation') {
                row.style.display = name === 'Account Activation Request' ? '' : 'none';
            } else if (filter === 'other') {
                row.style.display = name !== 'Account Activation Request' ? '' : 'none';
            }
        });
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>