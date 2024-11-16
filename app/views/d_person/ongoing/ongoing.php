<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/ongoing.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="ongoingorder-container">
    <h2>Ongoing</h2>
    <table class="ongoingorders-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pick-Up</th>
                <th>Drop-Off</th>
                <th>Buyer</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['orders'])): ?>
            <?php foreach ($data['orders'] as $index => $order): ?>
                <tr>
                    <td>
                        <a href="<?= URLROOT ?>/orders/orderDetails/<?= htmlspecialchars($order->id) ?>" class="ongoing-idbtn">
                            <?= htmlspecialchars($order->id) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($order->pickup_address) ?></td>
                    <td><?= htmlspecialchars($order->dropoff_address) ?></td>
                    <td><?= htmlspecialchars($order->buyer) ?></td>
                    <td>
                        <a href="<?= URLROOT ?>/Ordercontrollers/proof" class="ongoingbtn ongoingbtn-route">Proofs ➤</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No ongoing orders available.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>


    <!-- Map container -->
    <div id="map"></div>
</div>

<!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCl4sjIQ6o8QygScVKj4PJuo5H4X44Cfhk&callback=initMap" async defer></script>

<script>
function initMap() {
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    
    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: {lat: 37.7749, lng: -122.4194} // San Francisco
    });
    directionsRenderer.setMap(map);

    // Hardcoded pickup and dropoff locations
    var start = "San Francisco, CA"; // Pickup location
    var end = "Oakland, CA";         // Dropoff location

    var request = {
        origin: start,
        destination: end,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function(result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
        }
    });
}
</script>

<!-- Optional styling for button -->

<?php require APPROOT . '/views/inc/footer.php'; ?>    