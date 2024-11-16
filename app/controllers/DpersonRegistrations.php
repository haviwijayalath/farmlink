<?php 

class DpersonRegistrations extends Controller {
    public function __construct() {
      $this->userModel = $this->model('User'); 
    }

    public function index() {
        // Your code here
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
                    redirect('DpersonRegistrations/login');
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

    public function login() {
            //check for POST
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            //Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter your Email';
            }

            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } 

            //Check for user
            if($this->userModel->findUserByEmail($data['email'])){
                //User found
            } else {
                //User not found
                $data['email_err'] = 'No user found';
            }

            //Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                //Validated
                //Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    //Create session
                   $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';

                    $this->view('users/login', $data);
                }

            } else {
                //Load the view with errors
                $this->view('users/login', $data);
            }

          }else{
          //init data
          $data =[
            'email' => '',
            'password' => '',
            'email_err' => '',
            'password_err' => ''
          ];
  
          //load view
          $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){

                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role; // Store the user's role in session
                $_SESSION['user_phone'] = $user->phone;
                $_SESSION['user_image'] = $user->image;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_addr_no'] = $user->addr_no;
                $_SESSION['user_street'] = $user->street;
                $_SESSION['user_city'] = $user->city;
                $_SESSION['user_vehicle'] = $user->vehicle;
                $_SESSION['user_delivery_area'] = $user->area;
                $_SESSION['user_v_regno'] = $user->regno;
                $_SESSION['user_v_capacity'] = $user->capacity;
                $_SESSION['user_v_image'] = $user->v_image;
                $_SESSION['user_password'] = $user->password;
                redirect('Ordercontrollers/neworders');
        } 
    
        
    public function logout(){

        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_phone']);
        unset($_SESSION['user_image']);
        unset($_SESSION['user_addr_no']);
        unset($_SESSION['user_street']);
        unset($_SESSION['user_city']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_password']);
        unset($_SESSION['user_vehicle']);
        unset($_SESSION['user_delivery_area']);
        unset($_SESSION['user_v_regno']);
        unset($_SESSION['user_v_capacity']);
        unset($_SESSION['user_v_image']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('DpersonRegistrations/login');
      
    }
        

    public function isLoggedIn(){
        if(isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}
?>
