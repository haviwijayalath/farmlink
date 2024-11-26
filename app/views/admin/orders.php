<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/orders.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<div class="content">
    <h2>Orders</h2>
    <ul class="order-status">
        <li class="pending active">Pending</li>
        <li>Confirmed</li>
        <li>Picked</li>
        <li>Delivered</li>
        <li>Cancelled</li>
    </ul>

    <div class="search-bar">
        <input type="text" id="order-id" placeholder="Search by order id">
        <button onclick="filterByOrderId()"><i class="fas fa-search"></i></button>

        <div class="date-range-filter">
            <input type="date" id="start-date" name="start-date">
            <span>to</span>
            <input type="date" id="end-date" name="end-date">
            <button onclick="filterByDate()">Filter</button>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ORDER ID</th>
                <th>CREATED</th>
                <th>CUSTOMER</th>
                <th>TOTAL</th>
                <th>PROFIT</th>
                <th>STATUS</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody id="order-list">
            <!-- Example Row 1 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12345">ORD12345</a></td>
                <td>2024-11-15</td>
                <td>John Doe</td>
                <td>$250.00</td>
                <td>$50.00</td>
                <td><span class="pending">Pending</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
            <!-- Example Row 2 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12346">ORD12346</a></td>
                <td>2024-11-14</td>
                <td>Jane Smith</td>
                <td>$180.00</td>
                <td>$30.00</td>
                <td><span class="confirmed">Confirmed</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
            <!-- Example Row 3 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12347">ORD12347</a></td>
                <td>2024-11-13</td>
                <td>David Brown</td>
                <td>$320.00</td>
                <td>$65.00</td>
                <td><span class="processing">Processing</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
            <!-- Example Row 4 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12348">ORD12348</a></td>
                <td>2024-11-12</td>
                <td>Alice Johnson</td>
                <td>$150.00</td>
                <td>$25.00</td>
                <td><span class="shipped">Shipped</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
            <!-- Example Row 5 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12349">ORD12349</a></td>
                <td>2024-11-11</td>
                <td>Michael Lee</td>
                <td>$500.00</td>
                <td>$100.00</td>
                <td><span class="delivered">Delivered</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
            <!-- Example Row 6 -->
            <tr class="order">
                <td><a href="<?= URLROOT ?>/admins/order_details/ORD12350">ORD12350</a></td>
                <td>2024-11-10</td>
                <td>Linda Green</td>
                <td>$420.00</td>
                <td>$80.00</td>
                <td><span class="cancelled">Cancelled</span></td>
                <td><button class="view-details">View Details</button></td>
            </tr>
        </tbody>
    </table>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    function filterByDate() {
        const startDate = document.getElementById('start-date').value;
        const endDate = document.getElementById('end-date').value;
        const rows = document.querySelectorAll('#order-list .order');

        rows.forEach(row => {
            const rowDate = row.cells[1].textContent;
            if (startDate && endDate) {
                if (rowDate >= startDate && rowDate <= endDate) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            } else {
                row.style.display = ''; // Show all if no date range is set
            }
        });
    }

    function filterByOrderId() {
        const orderId = document.getElementById('order-id').value.toLowerCase();
        const rows = document.querySelectorAll('#order-list .order');

        rows.forEach(row => {
            const rowOrderId = row.cells[0].textContent.toLowerCase();
            if (rowOrderId.includes(orderId)) {
                row.style.display = ''; // Show the row if it matches
            } else {
                row.style.display = 'none'; // Hide the row if it doesn't match
            }
        });
    }
</script>
