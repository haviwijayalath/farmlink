<?php

class Dpersons extends Controller
{

    private $userModel;
    private $notificationHelper;

    public function __construct()
    {
        $this->userModel = $this->model('Dperson');
        $this->notificationHelper = new NotificationHelper();
    }

    public function index()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }
        
        redirect('dpersons/neworder');
    }

    
    public function neworder()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $deliveryArea = $_SESSION['user_delivery_area'] ?? null; 


        if (!$deliveryArea) {
           
            $data = ['orders' => []];
            $this->view('d_person/neworders', $data);
            return;
        }

        $orders = $this->userModel->getNewOrdersByArea($deliveryArea);


        $data = [
            'orders' => $orders ?? [] 
        ];


        $this->view('d_person/neworders', $data);
    }

   
    public function confirmNewOrder($orderId)
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $confirmedOrder = $this->userModel->confirmOrder($_SESSION['user_id'], $orderId);

        if ($confirmedOrder) {
            $this->notificationHelper->send_notification(
                'd',
                $_SESSION['user_id'],
                'b',
                $confirmedOrder->buyerID,
                'Order Confirmed',
                'Your ' . $confirmedOrder->product . '  order is confirmed by the delivery ',
                '/farmlink/buyercontrollers/buyerOrders',
                'info'
            );

            $this->notificationHelper->send_notification(
                'd',
                $_SESSION['user_id'],
                'f',
                $confirmedOrder->farmer_id,
                'Order Confirmed',
                'The order' . $confirmedOrder->orderId . ' for product ' . $confirmedOrder->product . ' is confirmed by the delivery ',
                '/farmlink/farmers/index',
                'info'
            );

            header('Location: ' . URLROOT . '/dpersons/ongoingDeliveries');
        } else {
            die('Something went wrong.');
        }
    }

    
    public function ongoingDeliveries()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $deliveryArea = $_SESSION['user_delivery_area'];
        $orders = $this->userModel->getOrdersByArea($deliveryArea);

        if (!empty($orders)) {
            $_SESSION['order_id'] = $orders[0]->orderID;
            $_SESSION['pickup_address'] = $orders[0]->pickup_address;
        } else {
            
            $_SESSION['order_id'] = null;
            $_SESSION['pickup_address'] = null;
        }

        $data = [
            'orders' => $orders ?? []  
        ];


        $this->view('d_person/ongoing/ongoing', $data);
    }

    public function proof()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        
        $data = [
            'order_id' => $_SESSION['order_id'] ?? null,
            'pickup_image' => $_SESSION['pickup_image'] ?? null,
            'dropoff_image' => $_SESSION['dropoff_image'] ?? null, 
        ];

        $this->view('d_person/ongoing/d_upload', $data);
    }

    



    public function deliveryUploadPickup()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        if (isset($_FILES['pickup_image'])) {
            $uploadDir = APPROOT . '/../public/d_uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $pickupImage = $_FILES['pickup_image'];
            $pickupImageName = time() . '_pickup_' . basename($pickupImage['name']);
            $pickupImagePath = $uploadDir . $pickupImageName;

            if (move_uploaded_file($pickupImage['tmp_name'], $pickupImagePath)) {
                $relativePath = 'public/d_uploads/' . $pickupImageName;

                $deliveryId = $this->userModel->savePickupImages($_SESSION['order_id'], $_SESSION['user_id'], $_SESSION['pickup_address'], $pickupImageName);

                if ($deliveryId) {

                    $_SESSION['pickup_image'] = $relativePath;

                    $_SESSION['delivery_id'] = $deliveryId;

                    
                    redirect('dpersons/ongoingDeliveries');
                }
            }
            redirect('dpersons/proof');
        }
    }

    public function confirmpickup()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if (!isset($_SESSION['order_id'])) {
                flash('order_error', 'Order ID not found in session.');
                redirect('dpersons/ongoing');
            }

            
            if (!isset($_SESSION['pickup_image']) || empty($_SESSION['pickup_image'])) {
                flash('order_error', 'Please upload a pickup image before confirming.');
                redirect('dpersons/ongoingDeliveries'); 
                return;
            }

            $orderId = $_SESSION['order_id'];

            
            if ($this->userModel->updateOrderStatus($orderId) ) {
                
                $orderDetails = $this->userModel->getOrderById($orderId);

                if ($orderDetails) {
                    $product = $orderDetails->product;
                    $buyerId = $orderDetails->buyerID;
                    $farmerId = $orderDetails->farmer_id;
                    $dpId = $_SESSION['user_id'];

                    $this->notificationHelper->send_notification(
                        'd',
                        $_SESSION['user_id'],
                        'b',
                        $buyerId,
                        'Order has picked up',
                        'Your order' . $orderId . ' for product' . $product . ' has been picked up by the delivery ',
                        '/farmlink/buyercontrollers/buyerOrders',
                        'info'
                    );

                    $this->notificationHelper->send_notification(
                        'd',
                        $_SESSION['user_id'],
                        'f',
                        $farmerId,
                        'Order has picked up',
                        'The order' . $orderId . ' for product ' . $product . ' has been picked up by the delivery ',
                        '/farmlink/farmers/manageorders',
                        'info'
                    );

                    redirect('dpersons/ongoingDeliveries');
                } else {
                    flash('order_error', 'Order not found.');
                    redirect('dpersons/ongoingDeliveries');
                }
            } else {
                flash('order_error', 'Failed to update order status.');
                redirect('dpersons/ongoingDeliveries');
            }
        } else {
            redirect('dpersons/ongoingDeliveries');
        }
    }

    public function endDelivery()
    {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        if (!isset($_SESSION['dropoff_image']) || empty($_SESSION['dropoff_image'])) {
            flash('dropoff_error', 'Please upload the dropoff image before ending the delivery.');
            redirect('dpersons/ongoingDeliveries'); 
            return;
        }

        
        $orderId = $_SESSION['order_id'];

        
        $summary = $this->userModel->getDeliverySummary($orderId);

        if ($summary) {
            $orderId = $summary->orderID;
            $product = $summary->product;
            $buyerId = $summary->buyerID;
            $farmerId = $summary->farmer_id;

            
            $this->notificationHelper->send_notification(
                'd',
                $_SESSION['user_id'],
                'b',
                $buyerId,
                'Order id delivered',
                'Your order ' . $orderId . ' for ' . $product . ' is delivered successfully by the delivery person',
                '/farmlink/buyercontrollers/buyerOrders',
                'info'
            );

            $this->notificationHelper->send_notification(
                'd',
                $_SESSION['user_id'],
                'f',
                $farmerId,
                'Order id delivered',
                'The order ' . $orderId . ' for ' . $product . ' is delivered successfully by the delivery person ',
                '/farmlink/farmers/manageorders',
                'info'
            );
        }

       
        unset($_SESSION['pickup_image']);
        unset($_SESSION['dropoff_image']);
        unset($_SESSION['order_id']);
        unset($_SESSION['delivery_id']);

        redirect('dpersons/history');
    }

    public function deliveryUploadDropoff()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        
        if (isset($_FILES['dropoff_image'])) {
          
            $uploadDir = APPROOT . '/../public/d_uploads/';

            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            
            $dropoffImage = $_FILES['dropoff_image'];
            $dropoffImageName = time() . '_dropoff_' . basename($dropoffImage['name']);
            $dropoffImagePath = $uploadDir . $dropoffImageName;

            
            if (move_uploaded_file($dropoffImage['tmp_name'], $dropoffImagePath)) {
                
                $relativePath = 'public/d_uploads/' . $dropoffImageName;

                
                echo "Delivery ID: " .  $_SESSION['delivery_id'];
                echo "Dropoff Image Path: " . $relativePath;

                
                if ($this->userModel->saveDropoffImage($_SESSION['delivery_id'], $dropoffImageName)) {
                    $_SESSION['dropoff_image'] = $relativePath; 
                    redirect('dpersons/proof');
                } else {
                    
                    echo "Failed to save the dropoff image in the database.";
                    redirect('dpersons/proof');
                }
            } else {
                
                echo "Failed to upload dropoff image.";
                redirect('dpersons/proof');
            }
        } else {
            
            echo "No dropoff image uploaded.";
            redirect('dpersons/proof');
        }
    }


    public function history()
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }


        $id = $_SESSION['user_id'];

        $history = $this->userModel->history($id);

        $data = [
            'orders' => $history ?? [] 
        ];


        $this->view('d_person/history', $data);
    }

    public function tracking()
    {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $orderId = $_SESSION['order_id'];
        $deliveryArea = $_SESSION['user_delivery_area'] ?? null; 

        
        $order = $this->userModel->getongoingbyID($deliveryArea, $orderId); 

        if (!$order) {
           
            flash('order_error', 'Order not found');
            redirect('dpersons/ongoing'); 
        }

        
        $data = [
            'status' => $order->status,
            'pickup' => $order->pickup_address,
            'dropoff' => $order->dropoff_address,
            'fee' => $order->amount,
            'orderId' => $order->orderID
        ];

        
        $this->view('d_person/ongoing/tracking', $data);
    }


    public function register()
    {

        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'addr_no' => trim($_POST['addr_no']),
                'street' => trim($_POST['street']),
                'city' => trim($_POST['city']),
                'phone' => trim($_POST['phone']),
                'image' => isset($_POST['saved_image']) ? $_POST['saved_image'] : '', 
                'vehicle' => trim($_POST['vehicle']),
                'area' => trim($_POST['area']),
                'regno' => trim($_POST['regno']),
                'capacity' => trim($_POST['capacity']),
                'v_image' => isset($_POST['saved_vehicle_image']) ? $_POST['saved_vehicle_image'] : '', 
                'l_image' => isset($_POST['saved_liscene_image']) ? $_POST['saved_liscene_image'] : '',
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']), 
                'vehicle_strucutre' => trim($_POST['vehicle_strucutre']),

                'name_err' => '',
                'email_err' => '',
                'addr_no_err' => '',
                'street_err' => '',
                'city_err' => '',
                'phone_err' => '',
                'image_err' => '',
                'vehicle_err' => '',
                'area_err' => '',
                'regno_err' => '',
                'capacity_err' => '',
                'v_image_err' => '',
                'l_image_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'vehicle_strucutre_err' => ''
            ];

            
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            if (empty($data['vehicle_strucutre'])) {
                $data['vehicle_strucutre_err'] = 'Please enter structure';
            }

            
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                //check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email already taken';
                }
            }

           
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter a phone number';
            } elseif (!preg_match('/^\d{10}$/', $data['phone'])) {
                $data['phone_err'] = 'Phone number must be exactly 10 digits';
            }


            
            if (empty($data['street'] && empty($data['city']))) {
                $data['addr_no_err'] = 'Please enter your address';
            }

            
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters long';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) {
                $data['password_err'] = 'Password must include at least one uppercase letter';
            } elseif (!preg_match('/[0-9]/', $data['password'])) {
                $data['password_err'] = 'Password must include at least one number';
            }

            
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm your password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

           
            if (
                empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) &&
                empty($data['password_err']) && empty($data['confirm_password_err'])
            ) {

                
                $image = $_FILES["image"]['name'];
                $v_image = $_FILES["v_image"]['name']; 
                $l_image = $_FILES["l_image"]['name'];
                $_picuploaded = 0;
                $upload_dir = APPROOT . '/../public/uploads/';

                
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                
                if (!empty($image)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image)) {
                        $_target_file = $upload_dir . $image;
                        $imageFileType = strtolower(pathinfo($_target_file, PATHINFO_EXTENSION));
                        $photo = time() . basename($_FILES['image']['name']);

                        
                        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                            $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
                        } elseif ($_FILES["image"]["size"] > 2000000) { 
                            $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
                        } else {
                            $data['image'] = $image; 
                            $_picuploaded = 1; 
                        }
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                    }
                } else {
                    $data['image_err'] = 'Please upload an image';
                }

                
                if (!empty($v_image)) {
                    if (move_uploaded_file($_FILES['v_image']['tmp_name'], $upload_dir . $v_image)) {
                        $_target_file = $upload_dir . $v_image;
                        $v_imageFileType = strtolower(pathinfo($_target_file, PATHINFO_EXTENSION));

                        
                        if ($v_imageFileType != "jpg" && $v_imageFileType != "jpeg" && $v_imageFileType != "png") {
                            $data['v_image_err'] = 'Please upload a vehicle image with extension .jpg, .jpeg, or .png';
                        } elseif ($_FILES["v_image"]["size"] > 2000000) { 
                            $data['v_image_err'] = 'Your vehicle image exceeds the size limit of 2MB';
                        } else {
                            $data['v_image'] = $v_image; 
                            $_picuploaded = 1; 
                        }
                    } else {
                        $data['v_image_err'] = 'Failed to upload vehicle image';
                    }
                } else {
                    $data['v_image_err'] = 'Please upload a vehicle image';
                }

                
                if (!empty($l_image)) {
                    if (move_uploaded_file($_FILES['l_image']['tmp_name'], $upload_dir . $l_image)) {
                        $_target_file = $upload_dir . $l_image;
                        $l_imageFileType = strtolower(pathinfo($_target_file, PATHINFO_EXTENSION));

                        
                        if ($l_imageFileType != "jpg" && $l_imageFileType != "jpeg" && $l_imageFileType != "png") {
                            $data['l_image_err'] = 'Please upload a license image with extension .jpg, .jpeg, or .png';
                        } elseif ($_FILES["l_image"]["size"] > 2000000) {
                            $data['l_image_err'] = 'Your license image exceeds the size limit of 2MB';
                        } else {
                            $data['l_image'] = $l_image; 
                            $_picuploaded = 1; 
                        }
                    } else {
                        $data['l_image_err'] = 'Failed to upload license image';
                    }
                } else {
                    $data['l_image_err'] = 'Please upload a license image';
                }


                
                if ($_picuploaded) {
                    
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    
                    if ($this->userModel->registerDeliveryPerson($data)) {
                        flash('register_success', 'You are successfully registered! Log in now');
                        flash('register_success', 'You are successfully registered! Log in now');
                        redirect('users/login');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                   
                    $this->view('users/dpersonregister', $data);
                }
            } else {
               
                $this->view('users/dpersonregister', $data);
            }
        } else {
           
            $data = [
                'name' => '',
                'email' => '',
                'addr_no' => '',
                'street' => '',
                'city' => '',
                'phone' => '',
                'image' => '',
                'vehicle' => '',
                'area' => '',
                'regno' => '',
                'capacity' => '',
                'v_image' => '',
                'l_image' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => '',
                'vehicle_strucutre' => '',

                'name_err' => '',
                'email_err' => '',
                'addr_no_err' => '',
                'street_err' => '',
                'city_err' => '',
                'phone_err' => '',
                'image_err' => '',
                'vehicle_err' => '',
                'area_err' => '',
                'regno_err' => '',
                'capacity_err' => '',
                'v_image_err' => '',
                'l_image_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'vehicle_strucutre_err' => ''
            ];

           
            $this->view('users/dpersonregister', $data);
        }
    }

    public function orderdetails($order_id)
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $deliveryArea = $_SESSION['user_delivery_area'] ?? null; 

        $orders = $this->userModel->getorder($deliveryArea, $order_id);


        $data = [
            'orders' => $orders ?? [] 
        ];


        $this->view('d_person/order_details', $data);
    }


    public function orderHistorydetails($order_id)
    {

        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

       
        $orderDetails = $this->userModel->getOrderHistoryById($order_id);

    

        $data = [
            'orders' => $orderDetails ?? [] 
        ];

        $this->view('d_person/historydetails', $data);
    }

    public function getongoing($order_id)
    {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $deliveryArea = $_SESSION['user_delivery_area'] ?? null; 
       
        $orderDetails = $this->userModel->getongoingbyID($deliveryArea, $order_id);


        $data = [
            'orders' => $orderDetails ?? [] 
        ];

        $this->view('d_person/ongoing/ongoing_details', $data);
    }
}
