<?php

class orderControllers extends Controller{

  private $orderModel;

  public function __construct() {
    $this->orderModel = $this->model('Order');
  }

  public function address($cart_id, $product_id) {
    $data = [
        'cartID' => $cart_id,
        'productID' => $product_id
    ];

    $this->view('buyer/cart/address', $data);
}


  public function getDistanceInKm($origin, $destination){
        $apiKey = 'AIzaSyCoF7QYiTVTL-WIAGcIDGJ4eS62voQcCVU'; // Replace with your actual key
    
        $originEncoded = urlencode($origin);
        $destinationEncoded = urlencode($destination);
    
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$originEncoded}&destinations={$destinationEncoded}&key={$apiKey}";
    
        $response = file_get_contents($url);
        $data = json_decode($response);
    
        if ($data->status == 'OK') {
            $distanceText = $data->rows[0]->elements[0]->distance->text;
            // Example: "123 km"
            $distanceValue = floatval(str_replace(' km', '', $distanceText));
            return $distanceValue;
        } else {
            return null;
        }
    }
    


  public function saveAddress()
    {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cartId = isset($_POST['cart_id']) ? $_POST['cart_id'] : null;
            $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;

            // Sanitize and get POST data
            $data = [
                'title' => trim($_POST['title']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'number' => trim($_POST['number']),
                'street' => trim($_POST['street_address']),
                'city' => trim($_POST['city']),
                'country' => 'Sri Lanka',  // Fixed country
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'buyer_id' => $_SESSION['user_id'],  // Assuming the buyer is logged in, and buyer ID is in the session
                'cartId' => $cartId,
                'productId' => $productId
            ];

        // Get pickup location from farmer/seller (you must fetch this from DB)

        $farmerAddress = $this->orderModel->getFarmerPickupAddressByProduct($data['productId']);

        if ($farmerAddress) {
            $pickupLocation = $farmerAddress['number'] . ', ' . $farmerAddress['street'] . ', ' . $farmerAddress['city'] . ', Sri Lanka';
        } else {
            $pickupLocation = 'Colombo'; // fallback or error handling
        }
        // Use buyer’s address from form
        $dropoffLocation = $data['number'] . ', ' . $data['street'] . ', ' . $data['city'] . ', Sri Lanka';

        // Get distance in km
        $distance = $this->getDistanceInKm($pickupLocation, $dropoffLocation);

        // Set fee
        $baseFee = 500; // fixed base fee
        $ratePerKm = 100; // you can adjust this
        $deliveryFee = ($distance !== null) ? $baseFee + ($ratePerKm * $distance) : $baseFee;

        $data['delivery_fee'] = round($deliveryFee, 2); // Store it in $data
        $data['drop_addr'] = $dropoffLocation;

        // ✅ DEBUG PRINT BLOCK (Remove these when done)
        //echo "<h3>DEBUG INFO:</h3>";
        //echo "<p><strong>Pickup Location:</strong> {$pickupLocation}</p>";
        //echo "<p><strong>Dropoff Location:</strong> {$dropoffLocation}</p>";
        //echo "<p><strong>Distance (km):</strong> {$distance}</p>";
        //echo "<p><strong>Delivery Fee (Rs):</strong> {$data['delivery_fee']}</p>";
        //echo "<pre>"; print_r($data); echo "</pre>";
        //exit; // stop here so you can view debug output 👈
        // ✅ END DEBUG BLOCK

        // Validate data
        if ($this->validateAddress($data)) {
            // Call the model method to save the address
            $data = $this->orderModel->saveOrderBuyer($data);
            $this->view('buyer/cart/payment', $data);
        } else {
            // If validation fails, reload the form with errors
            $this->view('buyer/cart/address', $data);
        }

    }

    }

    // Simple validation method
    private function validateAddress($data)
    {
        return !empty($data['title']) && !empty($data['first_name']) && !empty($data['last_name']) &&
               !empty($data['street']) && !empty($data['city']) && !empty($data['mobile']);
    }


}