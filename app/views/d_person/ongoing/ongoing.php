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
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($data['orders'])): ?>
            <?php foreach ($data['orders'] as $index => $order): ?>
                <tr>
                    <td>
                        <a href="<?= URLROOT ?>/dpersons/getongoing/<?= htmlspecialchars($order->id) ?>" class="ongoing-idbtn">
                            <?= htmlspecialchars($order->id) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($order->pickup_address) ?></td>
                    <td><?= htmlspecialchars($order->dropoff_address) ?></td>
                    <td><?= htmlspecialchars($order->buyer) ?></td>
                    <td>
                        <a href="<?= URLROOT ?>/dpersons/proof" class="ongoingbtn ongoingbtn-route">Proofs ➤</a>
                    </td>
                    <td>
                        <a href="<?= URLROOT ?>/dpersons/tracking" class="tracking-btn">Track Order</a>
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

    
    <div id="info" style="margin-top: 20px;">
    <p><strong>Distance:</strong> <span id="distance"></span></p>
    <p><strong>Delivery Fee:</strong> <span id="fee"></span></p>
    </div>
<!-- Map container -->
<div id="map" style="height: 500px; width: 100%; margin-bottom: 60px; margin-top: 60px;"></div>

</div>




<!-- Google Maps API Script -->
<script>
    // Initialize the map and directions
    // Initialize the map and directions
function initMap() {
    // Pickup and Dropoff Addresses from PHP
    const pickupAddress = "<?= htmlspecialchars($order->pickup_address) ?>";
    const dropoffAddress = "<?= htmlspecialchars($order->dropoff_address) ?>";

    // Map Initialization
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 6.927079, lng: 79.861244 } // General area for map center
    });

    // Enable scrollwheel interaction on click
    google.maps.event.addListener(map, "click", function () {
        this.setOptions({ scrollwheel: true });
    });

    // Directions Services
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    const distanceMatrixService = new google.maps.DistanceMatrixService();

    directionsRenderer.setMap(map);

    // Calculate Route
    const request = {
        origin: pickupAddress,
        destination: dropoffAddress,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function (result, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
        } else {
            console.error("Directions request failed due to " + status);
        }
    });

    // Request to get distance
    distanceMatrixService.getDistanceMatrix(
        {
            origins: [pickupAddress], // Pass as an array
            destinations: [dropoffAddress], // Pass as an array
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: true
        },
        function (response, status) {
            if (status === google.maps.DistanceMatrixStatus.OK) {
                const distanceText = response.rows[0].elements[0].distance.text; // e.g., "15.3 km"
                const distanceValue = response.rows[0].elements[0].distance.value; // e.g., 15300 (meters)

                // Display distance
                document.getElementById("distance").textContent = distanceText;

                // Calculate Delivery Fee
                const baseFee = 5; // Base fee
                const perKmRate = 0.5; // Per kilometer rate
                const distanceKm = distanceValue / 1000; // Convert meters to kilometers

                const deliveryFee = baseFee + distanceKm * perKmRate;
                document.getElementById("fee").textContent = `$${deliveryFee.toFixed(2)}`;
            } else {
                console.error("Distance Matrix request failed due to " + status);
            }
        }
    );
}

// Load the map after the window is fully loaded
window.onload = initMap;

</script>

<!-- Google Maps API Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoF7QYiTVTL-WIAGcIDGJ4eS62voQcCVU&libraries=places&callback=initMap" async defer></script>


<?php require APPROOT . '/views/inc/footer.php'; ?>    