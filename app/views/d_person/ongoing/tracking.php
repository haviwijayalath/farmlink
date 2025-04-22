<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/tracking.css">
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/map.css">

<div class="ongoingorder-container">
<!-- Distance and Fee -->
<div class="map-info" style="margin-bottom: 30px;">
    <p><strong>Distance:</strong> <span id="distance"></span></p>
    <p><strong>Delivery Fee:</strong> Rs.<?= htmlspecialchars($data['fee']) ?></p>
</div>

<!-- Google Map Display -->
<div id="map" style="height: 500px; width: 100%; margin-bottom: 60px; margin-top: 60px;"></div>

</div>

<div class="content">

<!-- Display the current order status -->
<div class="order-status">
    <h3>Current Order Status: <?= htmlspecialchars($data['status']) ?></h3>
  </div>

  <!-- Progress Tracker -->
  <div class="track">
    <ul id="progress">
      <!-- PLACED -->
      <li class="<?= ($data['status'] == 'new' || $data['status'] == 'ongoing' || $data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-file-alt"></i></div>
        <p>Order Processed</p>
      </li>

      <!-- SHIPPED -->
      <li class="<?= ($data['status'] == 'ongoing' || $data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-shipping-fast"></i></div>
        <p>Out for Deliver</p>
      </li>

      <!-- DELIVERED -->
      <li class="<?= ($data['status'] == 'delivered') ? 'active' : ''; ?>">
        <div class="icon"><i class="fas fa-home"></i></div>
        <p>Order Arrived</p>
      </li>
    </ul>
  </div>
</div>





<!-- Google Maps API Script -->
<script>
    // Initialize the map and directions
    // Initialize the map and directions
function initMap() {
    // Pickup and Dropoff Addresses from PHP
    const pickupAddress = "<?= htmlspecialchars($data['pickup']) ?>";
    const dropoffAddress = "<?= htmlspecialchars($data['dropoff']) ?>";

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
