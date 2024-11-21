<?php 

class Dpersons extends Controller {
    public function __construct() {
      $this->userModel = $this->model('Dperson'); 
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

      $orders = $this->userModel->getNewOrdersByArea($deliveryArea);


      $data = [
          'orders' => $orders ?? [] // Default to empty array if null
      ];


      $this->view('d_person/neworders', $data);
  }

  

    // Confirm an order and redirect to new orders page
    public function confirm($orderId) {
        if ($this->userModel->confirmOrder($orderId)) {
            header('Location: ' . URLROOT . '/dpersons/ongoingDeliveries');
        } else {
            die('Something went wrong.');
        }
    }

    // Display ongoing deliveries
    public function ongoingDeliveries() {
        $deliveryArea = $_SESSION['user_delivery_area'];
        $orders = $this->userModel->getOrdersByArea($deliveryArea);

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
    
            $deliveryId = $this->userModel->savePickupImages($_SESSION['order_id'], $_SESSION['user_id'],$_SESSION['pickup_address'], $pickupImageName);
    
            // Save image paths in the database
            if ( $deliveryId) {

                 // Store file paths in session variables
                $_SESSION['pickup_image'] = 'public/d_uploads/' . basename($pickupImageName);

                $_SESSION['delivery_id'] = $deliveryId;

                // Redirect with success
                redirect('dpersons/ongoingDeliveries');
            } else {
                redirect('dpersons/proof');
            }
            } else {
                redirect('dpersons/proof');
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
            if ($this->userModel->saveDropoffImages($_SESSION['delivery_id'],$dropoffImageName)) {

                 // Store file paths in session variables
                $_SESSION['dropoff_image'] = $dropoffImageName;

                // Redirect with success
                redirect('dpersons/ongoingDeliveries');
            } else {
                redirect('dpersons/proof');
            }
            } else {
                redirect('dpersons/proof');
            }
    }

    public function history(){

        $id = $_SESSION['user_id'];

        $history = $this->userModel->history($id);

        $data = [
            'orders' => $history ?? [] // Default to empty array if null
        ];


        $this->view('d_person/history', $data);
    }

    public function tracking(){
        $orderStatus = $this->userModel->fetchOrderStatus($_SESSION['order_id']);

        // Check if we received any status
        $status = $orderStatus ? $orderStatus[0]->status : 'new';  // Default to 'PLACED' if no status found

        $this->view('d_person/ongoing/tracking',['status' => $status]);

    }

    
    
    public function register() {

        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'addr_no' => trim($_POST['addr_no']),
                'street' => trim($_POST['street']),
                'city' => trim($_POST['city']),
                'phone' => trim($_POST['phone']),
                'image' => isset($_POST['saved_image']) ? $_POST['saved_image'] : '', // Keep previously uploaded image,
                'vehicle' => trim($_POST['vehicle']),
                'area' => trim($_POST['area']),
                'regno' => trim($_POST['regno']),
                'capacity' => trim($_POST['capacity']),
                'v_image' => isset($_POST['saved_vehicle_image']) ? $_POST['saved_vehicle_image'] : '', // Keep previously uploaded vehicle image
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),// Pass role to view

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
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate name
            if(empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }else {
              //check email
              if($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email already taken';
              }
            }

            // Validate phone number
            if(empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone number';
            }

            //validate address
            if (empty($data['addr_no'] && empty($data['city']))) {
                $data['addr_no_err'] = 'Please enter your address';
            }
            

            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate confirm password
            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm your password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure there are no errors before uploading the image
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) &&
                empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Now handle the image upload (if no errors from above)
                $image = $_FILES["image"]['name'];
                $v_image = $_FILES["v_image"]['name']; // Handle vehicle image upload
                $_picuploaded = 0;
                $upload_dir = APPROOT . '/../public/uploads/';

                // Ensure the upload directory exists
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Validate and move the uploaded image
                if (!empty($image)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir.$image)) {
                        $_target_file = $upload_dir.$image;
                        $imageFileType = strtolower(pathinfo($_target_file, PATHINFO_EXTENSION));
                        $photo = time() . basename($_FILES['image']['name']);

                        // Validate image extension
                    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                        $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
                    } elseif ($_FILES["image"]["size"] > 2000000) { // Check if image exceeds 2MB
                        $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
                    } else {
                        $data['image'] = $image; // Save the profile image name to data
                        $_picuploaded = 1; // Mark that image was uploaded successfully
                    }
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                    }
                } else {
                    $data['image_err'] = 'Please upload an image';
                }

                // Validate and move the vehicle image
            if (!empty($v_image)) {
                if (move_uploaded_file($_FILES['v_image']['tmp_name'], $upload_dir . $v_image)) {
                    $_target_file = $upload_dir . $v_image;
                    $v_imageFileType = strtolower(pathinfo($_target_file, PATHINFO_EXTENSION));

                    // Validate image extension for vehicle image
                    if ($v_imageFileType != "jpg" && $v_imageFileType != "jpeg" && $v_imageFileType != "png") {
                        $data['v_image_err'] = 'Please upload a vehicle image with extension .jpg, .jpeg, or .png';
                    } elseif ($_FILES["v_image"]["size"] > 2000000) { // Check if image exceeds 2MB
                        $data['v_image_err'] = 'Your vehicle image exceeds the size limit of 2MB';
                    } else {
                        $data['v_image'] = $v_image; // Save the vehicle image name to data
                        $_picuploaded = 1; // Mark that vehicle image was uploaded successfully
                    }
                } else {
                    $data['v_image_err'] = 'Failed to upload vehicle image';
                }
            } else {
                $data['v_image_err'] = 'Please upload a vehicle image';
            }

                // If the image is uploaded successfully, proceed with registration
                if($_picuploaded) {
                  //hash password
                  $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                  //register user
                  if($this->userModel->registerDeliveryPerson($data)){
                    redirect('users/login');
                 }else{
                     die('Something went wrong');
                 }

                }else {
                      // Load view with errors (if there are validation errors)
                      $this->view('users/dpersonregister', $data);
                }

            } else {
                // Load view with errors (if there are validation errors)
                $this->view('users/dpersonregister', $data);
            }

        } else {
            // Init data (for GET request)
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
                'password' => '',
                'confirm_password' => '',
                'role' => '',

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
                'password_err' => '',
                'confirm_password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('users/dpersonregister', $data);
        }
    }

    public function orderdetails($order_id){
        $deliveryArea = $_SESSION['user_delivery_area'] ?? null; // Ensure it's set

        $orders = $this->userModel->getorder($deliveryArea, $order_id);


      $data = [
          'orders' => $orders ?? [] // Default to empty array if null
      ];


      $this->view('d_person/order_details', $data);
    }

    
}
?>
