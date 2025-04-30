<?php

class orderControllers extends Controller{

  private $orderModel;
  private $notificationHelper;

  public function __construct() {
    $this->orderModel = $this->model('Order');
    $this->notificationHelper = new NotificationHelper();
  }

  public function address($cart_id, $product_id) {
    $data = [
        'cartID' => $cart_id,
        'productID' => $product_id
    ];

    $this->view('buyer/cart/address', $data);
}


  public function getDistanceInKm($origin, $destination){
        $apiKey = 'AIzaSyCoF7QYiTVTL-WIAGcIDGJ4eS62voQcCVU'; 
    
        $originEncoded = urlencode($origin);
        $destinationEncoded = urlencode($destination);
    
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$originEncoded}&destinations={$destinationEncoded}&key={$apiKey}";
    
        $response = file_get_contents($url);
        $data = json_decode($response);
    
        if ($data->status == 'OK') {
            $distanceText = $data->rows[0]->elements[0]->distance->text;
           
            $distanceValue = floatval(str_replace(' km', '', $distanceText));
            return $distanceValue;
        } else {
            return null;
        }
    }
    


  public function saveAddress()
    {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $cartId = isset($_POST['cart_id']) ? $_POST['cart_id'] : null;
            $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;

            
            $data = [
                'title' => trim($_POST['title']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'number' => trim($_POST['number']),
                'street' => trim($_POST['street_address']),
                'city' => trim($_POST['city']),
                'country' => 'Sri Lanka',  
                'mobile' => trim($_POST['mobile']),
                'email' => trim($_POST['email']),
                'buyer_id' => $_SESSION['user_id'],  
                'cartId' => $cartId,
                'productId' => $productId
            ];

       

        $farmerAddress = $this->orderModel->getFarmerPickupAddressByProduct($data['productId']);

        if ($farmerAddress) {
            $pickupLocation = $farmerAddress['number'] . ', ' . $farmerAddress['street'] . ', ' . $farmerAddress['city'] . ', Sri Lanka';
        } else {
            $pickupLocation = 'Colombo';
        }
        
        $dropoffLocation = $data['number'] . ', ' . $data['street'] . ', ' . $data['city'] . ', Sri Lanka';

        
        $distance = $this->getDistanceInKm($pickupLocation, $dropoffLocation);

       
        $baseFee = 200; 
        $ratePerKm = 10; 
        $deliveryFee = ($distance !== null) ? $baseFee + ($ratePerKm * $distance) : $baseFee;

        $data['delivery_fee'] = round($deliveryFee, 2); 
        $data['drop_addr'] = $dropoffLocation;

       
        
        if ($this->validateAddress($data)) {
            
            $data = $this->orderModel->saveOrderBuyer($data);
            $this->view('buyer/cart/payment', $data);
        } else {
           
            $this->view('buyer/cart/address', $data);
        }

    }

    }

    
    private function validateAddress($data)
    {
        return !empty($data['title']) && !empty($data['first_name']) && !empty($data['last_name']) &&
               !empty($data['street']) && !empty($data['city']) && !empty($data['mobile']);
    }

    
    public function getBuyerAddress() {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
    
        $buyerId = $_SESSION['user_id'];
        $buyerData = $this->orderModel->getBuyerAddress($buyerId); 
    
        if ($buyerData) {
           
            $formattedData = [
                'title' => 'Mr', 
                'first_name' => explode(' ', $buyerData->name)[0], 
                'last_name' => explode(' ', $buyerData->name)[1] ?? '', 
                'number' => $buyerData->number,
                'street' => $buyerData->Street,
                'city' => $buyerData->City,
                'mobile' => $buyerData->phone,
                'email' => $buyerData->email
            ];
            echo json_encode($formattedData);
        } else {
            echo json_encode(['error' => 'No address found']);
        }
    }

    
    public function showcomplaint($orderID = null) {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }
    
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $complaints = $this->orderModel->getComplaints($userId, $role);

        $data = [
            'complaints' => $complaints,
            'selectedOrderID' => $orderID 
        ];
    
    
        $this->view('d_person/complaints', $data);
    }

    public function showcomplaint_sb($orderID = null) {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }
    
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $complaints = $this->orderModel->getComplaints($userId, $role);

        $data = [
            'complaints' => $complaints,
            'selectedOrderID' => $orderID 
        ];
    
    
        $this->view('d_person/complaints_sb', $data);
    }

    public function show_buyer_complaint($orderID = null) {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
        }

        $buyerId = $_SESSION['user_id']; 
        $role = $_SESSION['user_role'];
    
        $complaints = $this->orderModel->getComplaints($buyerId, $role);
    
        $data = [
            'complaints' => $complaints,
            'selectedOrderID' => $orderID 
        ];
    
        $this->view('buyer/complaints', $data);
    }  
    
    public function show_buyer_complaint_sb($orderID = null) {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
        }

        $buyerId = $_SESSION['user_id']; 
        $role = $_SESSION['user_role'];
    
        $complaints = $this->orderModel->getComplaints($buyerId, $role);
    
        $data = [
            'complaints' => $complaints,
            'selectedOrderID' => $orderID 
        ];
    
        $this->view('buyer/complaints_sb', $data);
    } 
    
    public function submitComplaint() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $role = $_SESSION['user_role'];
            $orderId = $_POST['order_id'];
            $description = $_POST['description'];
    
           
            $this->orderModel->submitComplaint($userId, $role, $orderId, $description);

            $this->notificationHelper->send_notification(
                'd',                            
                $_SESSION['user_id'],                         
                'a',                         
                4,                               
                'New Complaint Submitted',       
                'A new complaint is submitted by the delivery person '.'for order ID:'.$orderId ,                       
                '/farmlink/admins/viewComplaints', 
                'info'                        
            );
    
            
            redirect('orderControllers/showcomplaint_sb');
        } else {
            
            redirect('orderControllers/showcomplaint');
        }
    }

    public function submitComplaint_buyer() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $role = $_SESSION['user_role'];
            $orderId = $_POST['order_id'];
            $description = $_POST['description'];
    
            
            $this->orderModel->submitComplaint($userId, $role, $orderId, $description);
    
            
            redirect('orderControllers/show_buyer_complaint');
        } else {
            
            redirect('orderControllers/show_buyer_complaint');
        }
    }

    public function review($orderID) {
        $orderDetails = $this->orderModel->getOrderDetailsWithFarmer($orderID);
    
        if (!$orderDetails) {
            die('Invalid Order ID');
        }
    
        $data = [
            'orderID' => $orderID,
            'buyerID' => $orderDetails->buyerID,
            'farmerID' => $orderDetails->farmer_id
        ];
    
        $this->view('buyer/review', $data);
    }
    
    public function submitReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderID = $_POST['order_id'];
            $description = $_POST['description'] ?? '';
            $rating = $_POST['rating'] ?? 0;
            $images = [];
    
            
            if (!empty($_FILES['images']['name'][0])) {
                $uploadDir = 'public/uploads/';

                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                foreach ($_FILES['images']['name'] as $index => $name) {
                    $tmpName = $_FILES['images']['tmp_name'][$index];
                    $newName = uniqid() . '_' . basename($name);
                    move_uploaded_file($tmpName, $uploadDir . $newName);
                    $images[] = $newName;
                }
            }
    
           
            $orderDetails = $this->orderModel->getOrderDetailsWithFarmer($orderID);
    
            $data = [
                'orderID' => $orderID,
                'buyerID' => $orderDetails->buyerID,
                'farmerID' => $orderDetails->farmer_id,
                'description' => $description,
                'rating' => $rating,
                'images' => implode(',', $images)
            ];
    
            if ($this->orderModel->addReview($data)) {
                flash('review_success', 'Review submitted successfully!');
                redirect('buyercontrollers/buyerOrders');
            } else {
                die("Failed to submit review");
            }
        } else {
            redirect('pages/index');
        }
    }
    

}