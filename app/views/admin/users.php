<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/users.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="admin-dashboard">
    <!-- Main Content -->
    <main>
        <div class="head">
            <h1>Users</h1>
            <div class="search-filter-container">
                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search..." onkeyup="searchUsers()" />
                    <button class="search-icon-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <button class="filter-button" onclick="toggleFilterOptions()">
                    <i class="fas fa-sliders-h"></i> Filter
                </button>
            </div>

            <div class="notifications">
                <div class="profile-pic">
                    <img src="profile.jpg" alt="Admin" />
                </div>
            </div>
        </div>

        <!-- Filter Options (Dropdown) -->
        <div id="filter-options" class="filter-options hidden">
            <label>
                <input type="radio" name="role" value="all" onchange="filterByRole()" checked /> All Roles
            </label>
            <label>
                <input type="radio" name="role" value="customer" onchange="filterByRole()" /> Customers
            </label>
            <label>
                <input type="radio" name="role" value="farmer" onchange="filterByRole()" /> Farmers
            </label>
            <label>
                <input type="radio" name="role" value="supplier" onchange="filterByRole()" /> Suppliers
            </label>
            <label>
                <input type="radio" name="role" value="delivery" onchange="filterByRole()" /> Delivery Persons
            </label>
            <label>
                <input type="radio" name="role" value="consultant" onchange="filterByRole()" /> Consultants
            </label>
        </div>

        <!-- Users Table -->
        <section class="table-container">
            <table id="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-role="customer">
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td>(201) 555-0124</td>
                        <td>Customer</td>
                        <td>12 Nov 2024</td>
                        <td class="actions">
                            <button><i class="fas fa-lock"></i></button>
                            <button><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr data-role="farmer">
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td>(202) 555-0194</td>
                        <td>Farmer</td>
                        <td>10 Nov 2024</td>
                        <td class="actions">
                            <button><i class="fas fa-lock"></i></button>
                            <button><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr data-role="supplier">
                        <td>Michael Johnson</td>
                        <td>michael@example.com</td>
                        <td>(203) 555-0188</td>
                        <td>Supplier</td>
                        <td>8 Nov 2024</td>
                        <td class="actions">
                            <button><i class="fas fa-lock"></i></button>
                            <button><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr data-role="delivery">
                        <td>Emily Davis</td>
                        <td>emily@example.com</td>
                        <td>(204) 555-0134</td>
                        <td>Delivery Person</td>
                        <td>7 Nov 2024</td>
                        <td class="actions">
                            <button><i class="fas fa-lock"></i></button>
                            <button><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    // Toggle visibility of filter options
    function toggleFilterOptions() {
        const filterOptions = document.getElementById('filter-options');
        filterOptions.classList.toggle('hidden');
    }

    // Filter table rows based on selected role
    function filterByRole() {
        const roleFilter = document.querySelector('input[name="role"]:checked').value;
        const rows = document.querySelectorAll('#users-table tbody tr');

        rows.forEach(row => {
            const role = row.getAttribute('data-role');
            if (roleFilter === 'all' || role === roleFilter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Hide the filter options after selecting a filter
        const filterOptions = document.getElementById('filter-options');
        filterOptions.classList.add('hidden'); // Ensure the filter options are hidden after selection
    }

    // Search table rows
    function searchUsers() {
        const searchValue = document.getElementById('search-input').value.toLowerCase();
        const rows = document.querySelectorAll('#users-table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    }
</script>
