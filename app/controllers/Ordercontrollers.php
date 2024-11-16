<?php
class Ordercontrollers extends Controller {
    public function __construct() {
        $this->orderModel = $this->model('Order');
    }

    public function index() {
        // Default method content here
        echo "Welcome to the Orders page!";
    }

    // Show new orders page
    public function neworder() {
      $deliveryArea = $_SESSION['user_delivery_area'] ?? null; // Ensure it's set
      

      if (!$deliveryArea) {
          // Handle case where delivery area is not set
          $data = ['orders' => []];
          $this->view('d_person/neworders', $data);
          return;
      }

      $orders = $this->orderModel->getNewOrdersByArea($deliveryArea);


      $data = [
          'orders' => $orders ?? [] // Default to empty array if null
      ];


      $this->view('d_person/neworders', $data);
  }

    // Confirm an order and redirect to new orders page
    public function confirm($orderId) {
        if ($this->orderModel->confirmOrder($orderId)) {
            header('Location: ' . URLROOT . '/ordercontrollers/ongoingDeliveries');
        } else {
            die('Something went wrong.');
        }
    }

    // Display ongoing deliveries
    public function ongoingDeliveries() {
        $deliveryArea = $_SESSION['user_delivery_area'];
        $orders = $this->orderModel->getOrdersByArea($deliveryArea);

        if (!empty($orders)) {
            $_SESSION['order_id'] = $orders[0]->id;
            $_SESSION['pickup_address'] = $orders[0]->pickup_address;
        } else {
            // Handle case where no ongoing deliveries are found
            $_SESSION['order_id'] = null;
            $_SESSION['pickup_address'] = null;
        }

        $data = [
            'orders' => $orders ?? []  // If $orders is null, this will set it to an empty array
        ];
        

        $this->view('d_person/ongoing/ongoing', $data);
    }

    public function proof(){
        $this->view('d_person/ongoing/d_upload');
    }

    public function deliveryUploadPickup() {
        // Check if files are uploaded
        if (isset($_FILES['pickup_image'])) {
            // Directory to save uploaded images
            $uploadDir = APPROOT . '/../public/d_uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Handle pickup image
            $pickupImage = $_FILES['pickup_image'];
            $pickupImageName = $uploadDir . time() . '_pickup_' . basename($pickupImage['name']);
            move_uploaded_file($pickupImage['tmp_name'], $pickupImageName);
    
            $deliveryId = $this->orderModel->savePickupImages($_SESSION['order_id'], $_SESSION['user_id'],$_SESSION['pickup_address'], $pickupImageName);
    
            // Save image paths in the database
            if ( $deliveryId) {

                 // Store file paths in session variables
                $_SESSION['pickup_image'] = 'public/d_uploads/' . basename($pickupImageName);

                $_SESSION['delivery_id'] = $deliveryId;

                // Redirect with success
                redirect('ordercontrollers/ongoingDeliveries');
            } else {
                redirect('ordercontrollers/proof');
            }
            } else {
                redirect('ordercontrollers/proof');
            }
    }

    public function deliveryUploadDropoff() {
        // Check if files are uploaded
        if (isset($_FILES['dropoff_image'])) {
            // Directory to save uploaded images
            $uploadDir = APPROOT . '/../public/d_uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Handle dropoff image
            $dropoffImage = $_FILES['dropoff_image'];
            $dropoffImageName = $uploadDir . time() . '_dropoff_' . basename($dropoffImage['name']);
            move_uploaded_file($dropoffImage['tmp_name'], $dropoffImageName);
    
            // Save image paths in the database
            if ($this->orderModel->saveDropoffImages($_SESSION['delivery_id'],$dropoffImageName)) {

                 // Store file paths in session variables
                $_SESSION['dropoff_image'] = $dropoffImageName;

                // Redirect with success
                redirect('ordercontrollers/ongoingDeliveries');
            } else {
                redirect('ordercontrollers/proof');
            }
            } else {
                redirect('ordercontrollers/proof');
            }
    }

    public function history(){

        $id = $_SESSION['user_id'];

        $history = $this->orderModel->history($id);

        $data = [
            'orders' => $history ?? [] // Default to empty array if null
        ];


        $this->view('d_person/history', $data);
    }

    public function tracking(){
        $orderStatus = $this->orderModel->fetchOrderStatus($_SESSION['order_id']);

        // Check if we received any status
        $status = $orderStatus ? $orderStatus[0]->status : 'new';  // Default to 'PLACED' if no status found

        $this->view('d_person/ongoing/tracking',['status' => $status]);

    }

    
    

}
