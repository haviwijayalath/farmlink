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
                    flash('register_success', 'You are successfully registered! Log in now');
                    flash('register_success', 'You are successfully registered! Log in now');
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
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login'); 
          }

        $data = [
            'name' => $_SESSION['user_name'],
            'phone_num' => $_SESSION['user_phone'],
            'email' => $_SESSION['user_email']
        ];

        $this->view('buyer/accounts/buyer_account',$data);
    }

    public function editProfile($id = null) {
        if($id === null){
            $id = $_SESSION['user_id'];
        }
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_SESSION['user_id'],
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => ($_POST['phone']),
                'password' => $_POST['current_password'],
                'new_password' => $_POST['new_password'],
                'confirm_password' => $_POST['confirm_password'],
                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'new_password_error' => '',
                'confirm_password_error' => ''
            ];

            // Validate data
            if (empty($data['name'])) $data['name_err'] = 'please enter name';
            if (empty($data['email'])) $data['email_err'] = 'please enter email';
            if (empty($data['phone'])) $data['phone_err'] = 'please enter phone number';

            // Check for existing password and hash new password if provided
            $user = $this->buyerModel->getUserById($id);

            // Handle password update logic
            if (!empty($data['new_password'])) {
                if (empty($data['password']) || !password_verify($data['password'], $user->password)) {
                    $data['password_err'] = 'Current password is incorrect';
                } elseif ($data['new_password'] !== $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                } else {
                    // Hash the new password
                    $data['new_password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                }
            } else {
                $data['new_password'] = $user->password; // Keep the current password
            }

             // If no errors, update the user
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['password_err']) && empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                if ($this->buyerModel->updateUser($data)) {
                    redirect('buyer/accounts/buyer_account');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('buyer/accounts/buyer_editaccount', $data);
            }

            if ($this->buyerModel->updateUser($data)) {
                // Set a flash message
                flash('update_success', 'Your profile has been updated. Please log in again.');
            
                // Log the user out
                session_destroy();
                unset($_SESSION['user_id']);
                unset($_SESSION['user_role']);
                
                redirect('users/login'); // Redirect to the login page
            } else {
                die('Something went wrong');
            }
            
        } else {
            // Get existing user data
            $user = $this->buyerModel->getUserById($id);

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
          ];
            // Load view
            $this->view('buyer/accounts/buyer_editaccount', $data);
        }
    }

    public function deactivate(){
        $result = $this->buyerModel->deleteAccount($_SESSION['user_id']);

        if($result){
            //clear session data
            session_unset();
            session_destroy();

            $this->view('buyer/accounts/deactivation');
        } else {
             // Set an error flash message and redirect to an appropriate page
            flash('error', 'Failed to deactivate the user account. Please try again.');
            redirect('Buyercontrollers/viewProfile');
        }
    }

    public function cartDetails(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        // get the cart items from database
        $cartItems = $this->buyerModel->getCartItems(); 
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
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'buyer_id' => $_SESSION['user_id'],
                'buyer_id' => $_SESSION['user_id'],
                'product_id' => $_POST['product_id'],
                'quantity' => $_POST['quantity']
            ];

            if(empty($data['product_id'])){
                echo ("not working");
            }

            if(empty($data['product_id'])){
                echo ("not working");
            }

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
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
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
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        if ($this->buyerModel->removeCartItem($id)){
            redirect('Buyercontrollers/cartDetails');
        } else {
            die('Something went wrong while removing the cart item.');
        }
    }

    public function deliveryOptions(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        $this->view('buyer/cart/delivery_details');
    }

    public function paymentDetails(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        $this->view('buyer/cart/payment');
    }

    public function orderConfirm(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        $this->view('buyer/cart/confirmOrder');
    }

    public function buyerOrders(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        $this->view('buyer/cart/buyerOrders');
    }

    public function wishlistDetails(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

          // get the wislist item from database
          $wishlistItem = $this->buyerModel->getWishlistItem();

          $data = [
            'wishlistItems' => $wishlistItem
          ];

        $this->view('buyer/wishlist', $data);
    }

    public function addToWishlist(){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'buyer_id' => $_SESSION['user_id'],
                'product_id' => $_POST['product_id'],
            ];

            if(empty($data['product_id'])){
                echo ("not working");
            }

            if($this->buyerModel->addWishlistItem($data)){
                redirect('Buyercontrollers/wishlistDetails');
            } else {
                die('Something went wrong while adding to the wishlist.');
            }
        } else {
            redirect('Buyercontrollers/wishlistDetails');
        }
        
    }

    // Function to display all products and filter products
    public function browseproducts() {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
        }

        $filter_variables = [
            'category' => $_GET['category'] ?? '',
            'price' => $_GET['price'] ?? '',
            'stock' => $_GET['stock'] ?? '',
            'exp_date' => $_GET['exp_date'] ?? ''
        ];

        $data = $this->buyerModel->getProducts($filter_variables);
        $this->view('buyer/products/browse_products', $data);
    }

    public function removeWishlist($id){
        if (!isLoggedIn() || $_SESSION['user_role'] != 'buyer') {
            redirect('users/login');
          }

        if ($this->buyerModel->removeWishlistItem($id)){
            redirect('Buyercontrollers/wishlistDetails');
        } else {
            die('Something went wrong while removing the wishlist item.');
        }    
    }

    // Function to display a single product
    public function viewproduct($id) {
        $product = $this->buyerModel->getProductById($id);
        $data = [
            'pName' => $product->productName,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'pImage' => $product->productImage,
            'exp_date' => $product->exp_date,
            'fId' => $id,
            'fName' => $product->farmerName,
            'fImage' => $product->farmerImage,
            'fEmail' => $product->email
        ];
        $this->view('buyer/products/view_product', $data);
    }

    public function payhereProcess(){
        $amount = 3000;
        $merchant_id = "1229272";
        $merchant_secret = "Mjg0OTYwNzA0MjU4NDUzNDYyODMxOTIzMzMzNDczNzY5MzI1NzM3" ;
        $order_id = uniqid();
        $item = "Door bell wireles";
        $currency = "LKR";
        $first_name = "Saman";
        $last_name = "Perera";
        $email = "samanp@gmail.com";
        $phone = "0771234567";
        $address = "No.1, Galle Road";
        $city = "Colombo";

        // $hash = strtoupper(
        //     md5(
        //         $merchant_id . 
        //         $order_id . 
        //         number_format($amount, 2, '.', '') . 
        //         $currency .  
        //         strtoupper(md5($merchant_secret)) 
        //     ) 
        // );

        $hash = strtoupper(
            md5(
                $merchant_id .
                $order_id .
                number_format($amount, 2, '.', '') .
                $currency .
                strtoupper(md5($merchant_secret))
            )
        );        

        $data = [
            "amount" => $amount,
            "merchant_id" => $merchant_id,
            "order_id" => $order_id,
            "item" => $item,
            "currency" => $currency,
            "hash" => $hash,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "address" => $address,
            "city" => $city
        ];

        $jsonOBJ = json_encode($data);

        echo $jsonOBJ;
    }

}