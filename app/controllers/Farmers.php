<?php
  class Farmers extends Controller {
    private $farmerModel;

    public function __construct() {
      $this->farmerModel = $this->model('Farmer');
    }

    public function register() {
      // Check for POST
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'phone_number' => trim($_POST['phone_number']),
          'image' => $_FILES['image'],
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'phone_number_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if (empty($data['email'])) {
          $data['email_err'] = 'Please enter email';
        } else {
          // if email exists
          if ($this->farmerModel->findFarmerByEmail($data['email'])) {
            $data['email_err'] = 'Email is already taken';
          }
        }

        // Validate Name
        if (empty($data['name'])) {
          $data['name_err'] = 'Please enter name';
        }

        // Validate Phone Number
        if (empty($data['phone_number'])) {
          $data['phone_number_err'] = 'Please enter phone number';
        }

        // Validate Password
        if (empty($data['password'])) {
          $data['password_err'] = 'Please enter password';
        } elseif (strlen($data['password']) < 6) {
          $data['password_err'] = 'Password must be at least 6 characters';
        }

        // Validate Confirm Password
        if (empty($data['confirm_password'])) {
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if ($data['password'] != $data['confirm_password']) {
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }

        // Make sure errors are empty
        if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['image_err']) && empty($data['user_name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
          // Validated
        }

        // Load view with eroors
        $this->view('farmers/register', $data);
      } else {
        // Init data
        $data = [
          'name' => '',
          'email' => '',
          'phone_number' => '',
          'image' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'phone_number_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('farmers/register', $data);
      }
    }
  }