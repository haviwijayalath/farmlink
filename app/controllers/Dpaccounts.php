<?php
class Dpaccounts extends Controller {

    private $userModel;
    
  public function __construct() {
      $this->userModel = $this->model('Dperson');
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
              // Combine address parts into a single string
              'address' => $user->number . ', ' . $user->street . ', ' . $user->city,
              'area' => $user->area,
              'image' => $user->image,
              'type' => $user->vehicle,
              'regno' => $user->regno,
              'capacity' => $user->capacity,
              'v_image' => $user->v_image
          ];
      } else {
          flash('user_message', 'User not found');
          redirect('dpersons/neworder'); // Or an appropriate page
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
            'area' => trim($_POST['area']),
            'addr_no' => trim($_POST['addr_no']),
            'street' => trim($_POST['street']),
            'city' => trim($_POST['city']),
            'address_id' => trim($_POST['address_id']),  // Retrieve address_id for updating
            'current_password' => isset($_POST['current_password']) ? trim($_POST['current_password']) : '',
            'new_password' => trim($_POST['new_password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'password' => '',
            'image' => '',
            'name_err' => '',
            'email_err' => '',
            'phone_err' => '',
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
                $target_dir = APPROOT . '/../public/uploads/'; // Adjust path relative to APPROOT

                // Ensure the upload directory exists
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Define the target file
                $file_name = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;

                // Validate and move the uploaded file
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $file_type = $_FILES["image"]["type"];

                if (!in_array($file_type, $allowed_types)) {
                    $data['image_err'] = 'Invalid file type. Only JPG, PNG, and GIF files are allowed.';
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $data['image'] = $file_name; // Save only the filename for the database
                    } else {
                        $data['image_err'] = 'Error uploading image. Please try again.';
                    }
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
          if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['address_err'])  && empty($data['image_err'])) {
              if ($this->userModel->updateUser($data)) {
                  redirect('Dpaccounts/account');
              } else {
                  die('Something went wrong');
              }
          } else {
              // Load view with errors
              $this->view('d_person/accounts/editaccount', $data);
          }
      } else {
          // Get existing user data
          $user = $this->userModel->getUserById($id);

          $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'area' => $user->area,
            'addr_no' => $user->number,
            'street' => $user->street,
            'city' => $user->city,
            'address_id' => $user->address_id
        ];

          // Load view
          $this->view('d_person/accounts/editaccount', $data);
      }
  }

  public function account() {
    // Get the user ID from session
    $id = $_SESSION['user_id'];  // Use session to get the logged-in user's ID

    // Get existing user data
    $data = $this->getUserDataById($id);

    // Load the account view with user data
    $this->view('d_person/accounts/account', $data);
    }

    public function vehicleinfo() {

    $id = $_SESSION['user_id'];
    $data = $this->getUserDataById($id);

    // Load the account view with user data
    $this->view('d_person/vehicles/vehicleinfo', $data);
    }

    /*public function deleteVehicle(){
        // Assume vehicle ID is passed as a query parameter
        $vehicleId = $_GET['id'] ?? null;

        if ($vehicleId && $this->vehicleModel->deleteVehicle($vehicleId)) {
            flash('vehicle_message', 'Vehicle deleted successfully');
            redirect('Accountcontrollers/viewVehicles'); // Redirect to view page
        } else {
            flash('vehicle_message', 'Failed to delete vehicle', 'alert alert-danger');
            redirect('Accountcontrollers/viewVehicles');
        }

    }*/

    public function addVehicle(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process the form
            $data = [
                'type' => trim($_POST['type']),
                'regno' => trim($_POST['regno']),
                'capacity' => trim($_POST['capacity']),
                'v_image' => '', // Handle file upload
            ];

            // Handle file upload
            if (!empty($_FILES['v_image']['name'])) {
                $fileName = $_FILES['v_image']['name'];
                $fileTmp = $_FILES['v_image']['tmp_name'];
                $destination = APPROOT . '/../public/uploads/' . $fileName;

                if (move_uploaded_file($fileTmp, $destination)) {
                    $data['v_image'] = $fileName;
                }
            }

            if ($this->vehicleModel->addVehicle($data)) {
                flash('vehicle_message', 'Vehicle added successfully');
                redirect('Dpaccounts/viewVehicles');
            } else {
                flash('vehicle_message', 'Failed to add vehicle', 'alert alert-danger');
                $this->view('vehicles/addVehicle', $data);
            }
        } else {
            // Load the form
            $this->view('vehicles/addVehicle');
        }
    }
    }

