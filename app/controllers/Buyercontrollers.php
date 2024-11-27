<?php

class Buyercontrollers extends Controller {

    private $buyerModel;

    public function __construct() {
        $this->buyerModel = $this->model('Buyer'); 
    }

    public function register(){
        if (isLoggedIn()) {
            redirect('buyers/index');
          }

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
        if (!isLoggedIn()) {
            redirect('users/login'); 
          }
          
        $data = [];
        $this->view('buyer/accounts/buyer_account', $data);
    }

    public function editProfile() {
        if (!isLoggedIn()) {
            redirect('users/login');
          }
        $this->view('buyer/accounts/buyer_editaccount');
    }

    public function cartDetails(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        // get the cart items from database
        $cartItems = $this->buyerModel->getCartItems();
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->price * $item->quantity;
        }
    
        $data = [
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->view('buyer/cart/cart', $data);
    }

    public function addToCart(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'buyer_id' => $_POST['user_id'],
                'product_id' => $_POST['product_id'],
                'quantity' => $_POST['quantity']
            ];

            if($this->buyerModel->addCartItem($data)){
                redirect('Buyercontrollers/cartDetails');
            } else {
                die('Something went wrong while adding to the cart.');
            }
        } else {
            redirect('Buyercontrollers/cartDetails');
        }
    }

    public function updateCartItem() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'cart_id' => $_POST['cart_id'],
                'quantity' => $_POST['quantity']
            ];
    
            // Update the cart item
            if ($this->buyerModel->updateCartItem($data)) {
                // Redirect to cart details page after updating
                redirect('Buyercontrollers/cartDetails');
            } else {
                // Handle error scenario
                die('Something went wrong while updating the cart.');
            }
        } else {
            // Redirect to cart details page if not a POST request
            redirect('Buyercontrollers/cartDetails');
        }
    }
    
    
    public function removeCartItem($id){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        if ($this->buyerModel->removeCartItem($id)){
            redirect('Buyercontrollers/cartDetails');
        } else {
            die('Something went wrong while removing the cart item.');
        }
    }

    public function deliveryOptions(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        $this->view('buyer/cart/delivery_details');
    }

    public function paymentDetails(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        $this->view('buyer/cart/payment');
    }

    public function orderConfirm(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        $this->view('buyer/cart/confirmOrder');
    }

    public function buyerOrders(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        $this->view('buyer/cart/buyerOrders');
    }

    public function wishlist(){
        if (!isLoggedIn()) {
            redirect('users/login');
          }

        $this->view('buyer/wishlist');
    }

}