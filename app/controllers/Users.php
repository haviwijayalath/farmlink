<?php 

class Users extends Controller {
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
                'phone_number' => trim($_POST['phone_number']),
                'image' => isset($_POST['saved_image']) ? $_POST['saved_image'] : '', // Keep previously uploaded image,
                'user_name' => trim($_POST['user_name']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),// Pass role to view
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'image_err' => '',
                'user_name_err' => '',
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
            if(empty($data['phone_number'])) {
                $data['phone_number_err'] = 'Please enter phone number';
            }

            // Validate user name
            if(empty($data['user_name'])) {
                $data['user_name_err'] = 'Please enter a username';
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
            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) &&
                empty($data['user_name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // Now handle the image upload (if no errors from above)
                $image = $_FILES["image"]['name'];
                $_picuploaded = 0;
                $upload_dir = $_SERVER['DOCUMENT_ROOT'].'/farmlink/public/uploads/';

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
                            $data['image'] = $image; //save the image name to data
                            $_picuploaded = 1; // Mark that image was uploaded successfully
                        }
                    } else {
                        $data['image_err'] = 'Failed to upload image';
                    }
                } else {
                    $data['image_err'] = 'Please upload an image';
                }

                // If the image is uploaded successfully, proceed with registration
                if($_picuploaded) {
                  //hash password
                  $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                  // Based on role, save to the appropriate table
                  switch ($data['role']) {
                    case 'Farmer':
                        $this->userModel->registerFarmer($data);
                        redirect('users/login');
                        break;
                    case 'Supplier':
                        $this->userModel->registerSupplier($data);
                        redirect('users/login');
                        break;
                    case 'Consultant':
                        $this->userModel->registerConsultant($data);
                        redirect('users/login');
                        break;
                    case 'WholesaleBuyer':
                        $this->userModel->registerWholesaleBuyer($data);
                        redirect('users/login');
                        break;
                    case 'DeliveryPerson':
                        $this->userModel->registerDeliveryPerson($data);
                        redirect('users/login');
                        break;
                    default:
                        die('Invalid role selected');
                  }

                }else {
                      // Load view with errors (if there are validation errors)
                      $this->view('users/register', $data);
                }

            } else {
                // Load view with errors (if there are validation errors)
                $this->view('users/register', $data);
            }

        } else {
            // Init data (for GET request)
            $data = [
                'name' => '',
                'email' => '',
                'phone_number' => '',
                'image' => '',
                'user_name' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'image_err' => '',
                'user_name_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('users/register', $data);
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
                'user_name' => trim($_POST['user_name']),
                'password' => trim($_POST['password']),
                'user_name_err' => '',
                'password_err' => ''
            ];

            //Validate user name
            if(empty($data['user_name'])) {
                $data['user_name_err'] = 'Please enter a username';
            }

            // Validate password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } 

            //Check for user/userName
            if($this->userModel->findUserByUserName($data['user_name'])){
                //User found
            } else {
                //User not found
                $data['user_name_err'] = 'No user found';
            }

            //Make sure errors are empty
            if(empty($data['user_name_err']) && empty($data['password_err'])){
                //Validated
                //Check and set logged in user
                $loggedInUser = $this->userModel->login($data['user_name'], $data['password']);

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
            'user_name' => '',
            'password' => '',
            'user_name_err' => '',
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
        $_SESSION['user_phone_number'] = $user->phone;
        $_SESSION['user_image'] = $user->image;
        $_SESSION['user_user_name'] = $user->username;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_password'] = $user->password;

        // Redirect based on role
        switch ($_SESSION['user_role']) {
          case 'consultants':
              redirect('pages/index');
              break;
          case 'farmers':
              redirect('pages/index');
              break;
          case 'buyers':
              redirect('pages/index');
              break;
          case 'suppliers':
              redirect('pages/index');
              break;
          case 'delivery_persons':
              redirect('dpersoncontrollers/neworder');
              break;
          default:
              redirect('pages/index');
              break;
        }   
    }
        
    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_phone_number']);
      unset($_SESSION['user_image']);
      unset($_SESSION['user_user_name']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_password']);
      unset($_SESSION['user_role']);
      session_destroy();
      redirect('users/login');
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
