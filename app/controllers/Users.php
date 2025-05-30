<?php 

class Users extends Controller { 

    private $userModel;

    public function __construct() {
      $this->userModel = $this->model('User'); 
    }

    public function index() {

        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            redirect('home/home');
        }
        $this->view('home/home');
    }

    public function login() {
       
        if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            redirect('home/home');
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
           
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
                
            } else {
                $data['email_err'] = 'No user found';
            }
    
            if(empty($data['email_err']) && empty($data['password_err'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    if ($loggedInUser->status === 'pending') {
                        $data['email_err'] = 'Your account is pending admin approval.';
                        flash('log in failed', 'Your account is pending admin approval.');
                        $this->view('users/login', $data);
                        return;
                    } elseif ($loggedInUser->status === 'suspended') {
                        $data['email_err'] = 'Your account has been suspended till ' . date('Y-m-d', strtotime($loggedInUser->suspend_date)) . '. Please <a href="/farmlink/users/support" style="color: blue; text-decoration: underline;">contact support</a>.';
                        flash('log in failed', 'Your account has been suspended till ' . date('Y-m-d', strtotime($loggedInUser->suspend_date)) . '. Please contact support.');
                        $this->view('users/login', $data);
                        return;
                    } elseif ($loggedInUser->status === 'deactivated') {
                        $data['email_err'] = 'Your account has been deactivated. Please <a href="/farmlink/users/support" style="color: blue; text-decoration: underline;">contact support</a>.';
                        flash('log in failed', 'Your account has been deactivated. Please contact support.');
                        $this->view('users/login', $data);
                        return;
                    } else {
                        $this->createUserSession($loggedInUser);
                        return;
                    }
                } else {
                    $data['password_err'] = 'Invalid password.';
                    $this->view('users/login', $data);
                    return;
                }
            } else {
                $this->view('users/login', $data);
                return;
            }
    
        } else {
            
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
        
        switch ($user->role) {
            case 'admins':
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user->id;
                $_SESSION['admin_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = 'admin';
                $_SESSION['user_type'] = 'admin'; 
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
                redirect('dpaccounts/revenueCheck');
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

    public function support() {
        if (isset($_GET['type'])) {
            // Process form
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
            
            if ($_GET['type'] == 'activation') {
                $this->view('users/support_activation');
            } elseif ($_GET['type'] == 'approval') {
                $this->view('users/support_approval');
            } elseif ($_GET['type'] == 'other') {
                $this->view('users/support_other');
            }
        } else {
            // Default view if no type is specified
            $this->view('users/support');
        }
    }

    public function support_activation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'name' => 'Account Activation Request',
                'email' => trim($_POST['email']),
                'message' => trim($_POST['message']),
                'email_err' => '',
                'message_err' => ''
            ];
    
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your Email';
            }
    
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter a message';
            }
    
            if (empty($data['email_err']) && empty($data['message_err'])) {
                $this->userModel->setSupportMessage($data);
                flash('support', 'Your request has been sent successfully.');
                redirect('users/support');
            } else {
                $this->view('users/support_activation', $data);
            }
        } else {
            $data = [
                'email' => '',
                'message' => '',
                'email_err' => '',
                'message_err' => ''
            ];
    
            $this->view('users/support_activation', $data);
        }
    }

    public function support_other() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'message_err' => ''
            ];
    
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }
    
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your Email';
            }
    
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter a message';
            }
    
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['message_err'])) {
                $this->userModel->setSupportMessage($data);
                flash('support', 'Your request has been sent successfully.');
                redirect('users/support');
            } else {
                $this->view('users/support_other', $data);
            }
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'message' => '',
                'name_err' => '',
                'email_err' => '',
                'message_err' => ''
            ];
    
            $this->view('users/support_other', $data);
        }
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'email' => trim($_POST['email']),
                'email_err' => ''
            ];
    
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your Email';
            }
    
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
                $this->userModel->sendResetLink($data['email']);
                redirect('users/linkSent');
            } else {
                $data['email_err'] = 'No user found with that email';
                $this->view('users/forgotPassword', $data);
            }
        } else {
            $data = [
                'email' => '',
                'email_err' => ''
            ];
    
            $this->view('users/forgotPassword', $data);
        }
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? null;
        if ($token) {
            // Check if the token is valid
            if ($this->userModel->isTokenValid($token, $_GET['email'])) {
                $data = [
                    'email' => $_GET['email'],
                    'password' => '',
                    'confirm_password' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];
                
                $this->view('users/resetPassword', $data);
            } else {
                flash('reset_error', 'Invalid or expired token.');
                redirect('users/login');
            }
        } else {
            flash('reset_error', 'No token provided.');
            redirect('users/login');
        }
    }

    public function resettingPassword() {
        // Process form
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'email' => trim($_POST['email']),
            'password' => trim($_POST['password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'password_err' => '',
            'confirm_password_err' => ''
        ];

        if (empty($data['password'])) {
            $data['password_err'] = 'Please enter a password';
        } elseif (strlen($data['password']) < 6) {
            $data['password_err'] = 'Password must be at least 6 characters';
        }

        if (empty($data['confirm_password'])) {
            $data['confirm_password_err'] = 'Please confirm your password';
        } else {
            if ($data['password'] != $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }
        }

        if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
            // Reset the password
            $this->userModel->resetPassword($data['email'], $data['password']);
            flash('reset_success', 'Your password has been reset successfully. You can now log in.');
            redirect('users/login');
        } else {
            $this->view('users/resetPassword', $data);
        }
    }

    public function linkSent() {
        $this->view('users/linkSent');
    }
}
?>
