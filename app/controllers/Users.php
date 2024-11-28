<?php 

class Users extends Controller { 

    private $userModel;

    public function __construct() {
      $this->userModel = $this->model('User'); 
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
        if (empty($data['password'])) {
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

        //Redirect based on role
        switch ($user->role) {
            case 'admins':
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user->id;
                $_SESSION['admin_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = 'admin';
                redirect('admins/dashboard');
                break;
            case 'consultants':
                redirect('pages/index');
                break;

            case 'farmers':
                // initializing the session variables
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_image'] = $user->image;
                $_SESSION['user_role'] = 'farmer'; 
                redirect('farmers/index');
                break;

            case 'buyers':
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = 'buyer';
                $_SESSION['user_phone'] = $user->phone;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_password'] = $user->password;
                redirect('Buyercontrollers/browseproducts');
                break;

            case 'suppliers':
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
                $_SESSION['user_vehicle'] = $user->type;
                $_SESSION['user_delivery_area'] = $user->area;
                $_SESSION['user_v_regno'] = $user->regno;
                $_SESSION['user_v_capacity'] = $user->capacity;
                $_SESSION['user_v_image'] = $user->v_image;
                $_SESSION['user_password'] = $user->password;
                redirect('dpersons/neworder');
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

        //Logout based on role
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
