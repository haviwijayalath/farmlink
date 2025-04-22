<?php 

class Users extends Controller { 

    private $userModel;

    public function __construct() {
      $this->userModel = $this->model('User'); 
    }

    public function login() {
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
    
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter your Email';
            }
    
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            }
    
            if($this->userModel->findUserByEmail($data['email'])){
                // User found
            } else {
                $data['email_err'] = 'No user found';
            }
    
            if(empty($data['email_err']) && empty($data['password_err'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
    
                if ($loggedInUser === 'pending') {
                    $data['email_err'] = 'Your account is pending admin approval.';
                    flash('log in failed', 'Your account is pending admin approval.');
                    $this->view('users/login', $data);
                    return;
                } elseif ($loggedInUser === 'suspended') {
                    $data['email_err'] = 'Your account has been suspended. Please contact support.';
                    flash('log in failed', 'Your account has been suspended. Please contact support.');
                    $this->view('users/login', $data);
                    return;
                } elseif ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                    return;
                } else {
                    $data['password_err'] = 'Invalid email or password.';
                    $this->view('users/login', $data);
                    return;
                }
            } else {
                $this->view('users/login', $data);
                return;
            }
    
        } else {
            // This part was misaligned earlier
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
    
            $this->view('users/login', $data);
        }
    }
    
    public function createUserSession($user){
        // Redirect based on role, and set extra session variable user_type
        switch ($user->role) {
            case 'admins':
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user->id;
                $_SESSION['admin_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = 'admin';
                $_SESSION['user_type'] = 'admin'; // Added
                redirect('admins/dashboard');
                break;

            case 'farmers':
                // Initializing the session variables for a farmer
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_image'] = $user->image;
                $_SESSION['user_role'] = 'farmer'; 
                $_SESSION['user_type'] = 'farmer'; // Added
                redirect('farmers/index');
                break;

            case 'buyers':
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = 'buyer';
                $_SESSION['user_phone'] = $user->phone;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_type'] = 'buyer'; // Added
                redirect('Buyercontrollers/browseproducts');
                break;

            case 'suppliers':
                $_SESSION['user_type'] = 'supplier'; // Added
                redirect('pages/index');
                break;

            case 'delivery_persons':
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = 'dperson';
                $_SESSION['user_phone'] = $user->phone;
                $_SESSION['user_image'] = $user->image;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_addr_no'] = $user->addr_no;
                $_SESSION['user_street'] = $user->street;
                $_SESSION['user_city'] = $user->city;
                $_SESSION['user_vehicle_id'] = $user->vehicle_id;
                $_SESSION['user_delivery_area'] = $user->area;
                $_SESSION['user_password'] = $user->password;
                $_SESSION['user_type'] = 'delivery_person'; // Added
                redirect('dpersons/neworder');
                break;

            case 'consultants':
                // Initializing the session variables for a consultant
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_image'] = $user->image;
                $_SESSION['user_role'] = 'consultant'; 
                $_SESSION['user_type'] = 'consultant'; // Added
                redirect('consultants/viewprofile');
                break;

            default:
                redirect('pages/index');
                break;
        }
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
        unset($_SESSION['user_type']); // Added

        // Logout based on role
        switch ($_SESSION['user_role']) {
            case 'admins':
                session_destroy();
                redirect('users/login');
                break;
            case 'consultants':
                session_destroy();
                redirect('users/login');
                break;
            case 'farmers':
                session_destroy();
                redirect('users/login');
                break;
            case 'buyers':
                session_destroy();
                redirect('users/login');
                break;
            case 'suppliers':
                session_destroy();
                redirect('users/login');
                break;
            case 'delivery_persons':
                unset($_SESSION['user_vehicle']);
                unset($_SESSION['user_delivery_area']);
                unset($_SESSION['user_v_regno']);
                unset($_SESSION['user_v_capacity']);
                unset($_SESSION['user_v_image']);
                unset($_SESSION['user_role']);
                unset($_SESSION['user_vehicle_id']);
                session_destroy();
                redirect('users/login');
                break;
            default:
                session_destroy();
                redirect('users/login');
                break;
        } 
    }

}
?>
