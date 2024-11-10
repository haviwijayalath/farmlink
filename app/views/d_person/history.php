<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/history.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="history-container">
    <div class="history-content-area">
        <h2>Delivery History</h2>
        <table class="order-history-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Static Data for demonstration purposes -->
                <tr>
                    <td>Adidas Ultra Boost</td>
                    <td><a href="#" class="order-link" data-orderid="25421">#25421</a></td>
                    <td>Jan 8th, 2022</td>
                    <td>Bessie Cooper</td>
                    <td><span class="status delivered">Delivered</span></td>
                    <td>$200.00</td>
                </tr>
                <tr>
                    <td>Nike Air Max</td>
                    <td><a href="#" class="order-link" data-orderid="25422">#25422</a></td>
                    <td>Jan 9th, 2022</td>
                    <td>John Doe</td>
                    <td><span class="status canceled">Canceled</span></td>
                    <td>$180.00</td>
                </tr>
                <tr>
                    <td>Puma RS-X</td>
                    <td><a href="#" class="order-link" data-orderid="25423">#25423</a></td>
                    <td>Jan 10th, 2022</td>
                    <td>Jane Smith</td>
                    <td><span class="status delivered">Delivered</span></td>
                    <td>$150.00</td>
                </tr>
                <tr>
                    <td>Converse Chuck Taylor</td>
                    <td><a href="#" class="order-link" data-orderid="25424">#25424</a></td>
                    <td>Jan 11th, 2022</td>
                    <td>Bob Johnson</td>
                    <td><span class="status delivered">Delivered</span></td>
                    <td>$120.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal to show order details -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Order Details</h3>
        <div id="order-details">
            <!-- Order details will be loaded here via AJAX -->
        </div>
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
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>



