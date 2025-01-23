<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/history.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="history-container">
    <div class="history-content-area">
        <h2>Delivery History</h2>

        <div class="search-filter-container">
            <div class="search-bar">
                <!-- Search by Customer -->
                <input type="text" id="search-customer" placeholder="Search by Customer..." onkeyup="filterTable()" />

                <!-- Search by Date -->
                <input type="date" id="search-date" onchange="filterTable()" />

                <button class="search-icon-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <table class="order-history-table">
            <thead>
                <tr>
                    <th>Order_ID</th>
                    <th>Product</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['orders'])): ?>
                    <?php foreach($data['orders'] as $index => $order): ?>
                        <tr>
                            <td>
                                <a href="<?= URLROOT ?>/dpersons/orderHistorydetails/<?= htmlspecialchars($order->order_id) ?>" class="historybtn">
                                    <?= htmlspecialchars($order->order_id) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($order->productName) ?></td>
                            <td class="customer-name"><?= htmlspecialchars($order->buyer) ?></td>
                            <td><?= htmlspecialchars($order->amount) ?></td>
                            <td class="order-date"><?= htmlspecialchars($order->date) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No delivered orders available for you.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        // When the user clicks on an order ID, display mock order details using static data
        $('.order-link').on('click', function (e) {
            e.preventDefault();
            var orderID = $(this).data('orderid');

            var orderDetails = {
                '25421': {
                    customerName: 'Bessie Cooper',
                    pickupLocation: '123 Farm St, Harvestville',
                    dropoffLocation: '456 Buyer Rd, Market City',
                    deliveryFee: '$20.00',
                    status: 'Delivered'
                },
                '25422': {
                    customerName: 'John Doe',
                    pickupLocation: '789 Field Ln, Farmtown',
                    dropoffLocation: '321 Store Ave, Sellsville',
                    deliveryFee: '$15.00',
                    status: 'Canceled'
                },
                '25423': {
                    customerName: 'Jane Smith',
                    pickupLocation: '654 Orchard Dr, Greenvillage',
                    dropoffLocation: '987 Shop Rd, Buyerburg',
                    deliveryFee: '$25.00',
                    status: 'Delivered'
                },
                '25424': {
                    customerName: 'Bob Johnson',
                    pickupLocation: '147 Grain Rd, Agroville',
                    dropoffLocation: '369 Trade St, Commerce City',
                    deliveryFee: '$18.00',
                    status: 'Delivered'
                }
            };

            // Get the relevant order details
            var details = orderDetails[orderID];

            // Display the order details in the modal
            var detailsHtml = "<p><strong>Customer Name:</strong> " + details.customerName + "</p>" +
                              "<p><strong>Pickup Location:</strong> " + details.pickupLocation + "</p>" +
                              "<p><strong>Dropoff Location:</strong> " + details.dropoffLocation + "</p>" +
                              "<p><strong>Delivery Fee:</strong> " + details.deliveryFee + "</p>" +
                              "<p><strong>Status:</strong> " + details.status + "</p>";

            $('#order-details').html(detailsHtml);
            $('#orderModal').show();
        });

        // Close the modal when the user clicks the close button
        $('.close').on('click', function () {
            $('#orderModal').hide();
        });
    });

    function filterTable() {
    const customerInput = document.getElementById('search-customer').value.toLowerCase();
    const dateInput = document.getElementById('search-date').value;

    const tableRows = document.querySelectorAll('.order-history-table tbody tr');

    tableRows.forEach(row => {
        const customerName = row.querySelector('.customer-name').textContent.toLowerCase();
        const orderDate = row.querySelector('.order-date').textContent;

        const matchesCustomer = customerInput === "" || customerName.includes(customerInput);
        const matchesDate = dateInput === "" || orderDate === dateInput;

        if (matchesCustomer && matchesDate) {
            row.style.display = ""; // Show row
        } else {
            row.style.display = "none"; // Hide row
        }
    });
}

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>



