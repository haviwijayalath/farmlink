<?php

class orderControllers extends Controller{

  private $orderModel;

  public function __construct() {
    $this->orderModel = $this->model('Order');
  }

  public function address($cart_id) {
    $data = [
        'cartID' => $cart_id
    ];
    $this->view('buyer/cart/address', $data);
}


  public function saveAddress()
    {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cartId = isset($_POST['cart_id']) ? $_POST['cart_id'] : null;

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
                'cartId' => $cartId
            ];

            // Validate data
            if ($this->validateAddress($data)) {
                // Call the model method to save the address
                $addressId = $this->orderModel->saveOrderBuyer($data);
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