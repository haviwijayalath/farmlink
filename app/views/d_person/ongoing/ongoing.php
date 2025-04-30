<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/ongoing.css">

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="ongoingorder-container">
    <h2>Ongoing</h2>
    <h4 style="color: red;"> <?php flash('order_error'); ?> </h4>
    <h4 style="color: red;"> <?php flash('dropoff_error'); ?> </h4>

    <?php if (!empty($data['orders'])): ?>

        <table class="ongoingorders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pick-Up</th>
                    <th>Drop-Off</th>
                    <th>Buyer</th>
                    <th>Delivery Fee</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['orders'] as $index => $order): ?>
                    <tr>
                        <td>
                            <a href="<?= URLROOT ?>/dpersons/getongoing/<?= htmlspecialchars($order->orderID) ?>" class="ongoing-idbtn">
                                <?= htmlspecialchars($order->orderID) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($order->pickup_address) ?></td>
                        <td><?= htmlspecialchars($order->dropoff_address) ?></td>
                        <td><?= htmlspecialchars($order->buyer) ?></td>
                        <td><?= htmlspecialchars($order->amount) ?></td>
                        <td>
                            <a href="<?= URLROOT ?>/dpersons/proof" class="ongoingbtn ongoingbtn-route">Proofs ➤</a>
                        </td>
                        <td>
                            <a href="<?= URLROOT ?>/dpersons/tracking/<?= $order->orderID ?>" class="tracking-btn">Track Order</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Ongoing Order Info -->
        <div id="info" style="margin-bottom: 20px;">
            <p><strong>Distance:</strong> <span id="distance"></span></p>
            <p><strong>Delivery Fee:</strong> Rs.<?= htmlspecialchars($order->amount) ?></p>
        </div>

        <!-- Map Container -->
        <div id="map" style="height: 500px; width: 100%; margin-bottom: 60px; margin-top: 60px;"></div>
    <?php else: ?>
        <p>No ongoing orders available.</p>
    <?php endif; ?>
</div>





<script>
   
function initMap() {
    
    const pickupAddress = "<?= htmlspecialchars($order->pickup_address) ?>";
    const dropoffAddress = "<?= htmlspecialchars($order->dropoff_address) ?>";

   
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: 6.927079, lng: 79.861244 } 
    });

    
    google.maps.event.addListener(map, "click", function () {
        this.setOptions({ scrollwheel: true });
    });

    
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    const distanceMatrixService = new google.maps.DistanceMatrixService();

    directionsRenderer.setMap(map);

   
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

   
    distanceMatrixService.getDistanceMatrix(
        {
            origins: [pickupAddress], 
            destinations: [dropoffAddress], 
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: true
        },
        function (response, status) {
            if (status === google.maps.DistanceMatrixStatus.OK) {
                const distanceText = response.rows[0].elements[0].distance.text; 
                const distanceValue = response.rows[0].elements[0].distance.value; 

                
                document.getElementById("distance").textContent = distanceText;

                
                const baseFee = 5;
                const perKmRate = 0.5; 
                const distanceKm = distanceValue / 1000; 

                const deliveryFee = baseFee + distanceKm * perKmRate;
                document.getElementById("fee").textContent = `$${deliveryFee.toFixed(2)}`;
            } else {
                console.error("Distance Matrix request failed due to " + status);
            }
        }
    );
}


window.onload = initMap;

</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoF7QYiTVTL-WIAGcIDGJ4eS62voQcCVU&libraries=places&callback=initMap" async defer></script>


<?php require APPROOT . '/views/inc/footer.php'; ?>    