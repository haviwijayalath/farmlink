<?php
class Accountcontrollers extends Controller {
  public function __construct() {
      $this->userModel = $this->model('Account');
  }

  // Method to get user data by ID (reusable function)
  private function getUserDataById($id) {
      $user = $this->userModel->getUserById($id);

      if ($user) {
          return [
              'id' => $user->id,
              'name' => $user->name,
              'email' => $user->email,
              'phone' => $user->phone,
              'address' => $user->address,
              'area' => $user->area,
              'image' => $user->image
          ];
      } else {
          flash('user_message', 'User not found');
          redirect('dpersoncontrollers/neworder'); // Or an appropriate page
      }
  }

  public function editProfile($id) {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Sanitize POST data
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

          // Initialize data array
          $data = [
              'id' => $id,
              'name' => trim($_POST['name']),
              'email' => trim($_POST['email']),
              'phone' => trim($_POST['phone']),
              'address' => trim($_POST['address']),
              'area' => trim($_POST['area']),
              'current_password' => trim($_POST['current_password']),
              'new_password' => trim($_POST['new_password']),
              'confirm_password' => trim($_POST['confirm_password']),
              'password' => '',
              'image' => '',
              'name_err' => '',
              'email_err' => '',
              'phone_err' => '',
              'address_err' => '',
              'area_err' => '',
              'password_err' => '',
              'image_err' => ''
          ];

          // Validate data
          if (empty($data['name'])) $data['name_err'] = 'Please enter name';
          if (empty($data['email'])) $data['email_err'] = 'Please enter email';
          if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {
              $data['password_err'] = 'Passwords do not match';
          }

          // Handle image upload if exists
          if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
              $target_dir = APPROOT . "/public/uploads/";
              $target_file = $target_dir . basename($_FILES["image"]["name"]);
              
              // Move the uploaded file
              if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                  $data['image'] = basename($_FILES["image"]["name"]);
              } else {
                  $data['image_err'] = 'Error uploading image';
              }
          }

          // Check for existing password and hash new password if provided
          $user = $this->userModel->getUserById($id);
          if (password_verify($data['current_password'], $user->password)) {
              $data['password'] = !empty($data['new_password']) ? password_hash($data['new_password'], PASSWORD_DEFAULT) : $user->password;
          } else {
              $data['password_err'] = 'Current password is incorrect';
          }

          // If no errors, update the user
          if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['address_err']) && empty($data['password_err']) && empty($data['image_err'])) {
              if ($this->userModel->updateUser($data)) {
                  flash('user_message', 'Profile updated successfully');
                  redirect('dpersoncontrollers/viewprofile');
              } else {
                  die('Something went wrong');
              }
          } else {
              // Load view with errors
              $this->view('accountcontrollers/editProfile', $data);
          }
      } else {
          // Get existing user data
          $data = $this->getUserDataById($id);

          // Load view
          $this->view('accountcontrollers/editProfile', $data);
      }
  }

  public function account($id) {
      // Get existing user data
      $data = $this->getUserDataById($id);

      // Load the account view with user data
      $this->view('d_person/account', $data);
  }
}

