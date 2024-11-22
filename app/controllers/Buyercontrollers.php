<?php

class Buyercontrollers extends Controller {

    private $buyerModel;

    public function __construct() {
        $this->buyerModel = $this->model('Buyer');
    }

    public function register(){
        // check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // process form
            // sanitize the post data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => trim($_POST['role']),// Pass role to view

                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
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
              if($this->buyerModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email already taken';
              }
            }

            // Validate phone number
            if(empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone number';
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

             // Make sure there are no errors before submit
             if(empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) &&
             empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                // hashing password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                //register user
                if($this->buyerModel->registerBuyer($data)){
                    redirect('users/login');
                 }else{
                     die('Something went wrong');
                 }

                }else {
                    // Load view with errors (if there are validation errors)
                    $this->view('users/buyer_register', $data);
              }
             } else{
                // init data from get request
                $data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'password' => '',
                'confirm_password' => '',
                'role' => '',// Pass role to view

                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
                ];

                // Load view
                $this->view('users/buyer_register',$data);
             }
        }

    // Function to display account page
    public function viewProfile() {
        $data = [];
        $this->view('buyer/accounts/buyer_account', $data);
    }

    public function editProfile() {
        $this->view('buyer/accounts/buyer_editaccount');
    }

    public function cartDetails(){
        $data = [];
        $this->view('buyer/cart/cart', $data);
    }

    public function deliveryOptions(){
        $this->view('buyer/cart/delivery_details');
    }

    public function paymentDetails(){
        $this->view('buyer/cart/payment');
    }

}