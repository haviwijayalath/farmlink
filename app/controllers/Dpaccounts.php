<?php
class Dpaccounts extends Controller {

    private $userModel;
    
  public function __construct() {
    // Protect all methods in this controller
    if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
        redirect('users/login'); // Redirect to login page if not logged in
    }

      $this->userModel = $this->model('Dperson');
  }

  public function index(){
    echo("invalid");
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
              'type' => $user->type,
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
            'address_id' => trim($_POST['address_id']),
            'current_password' => isset($_POST['current_password']) ? trim($_POST['current_password']) : '',
            'new_password' => trim($_POST['new_password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'password' => '',
            'image' => '',  // Set default as empty, to handle image separately
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
        } else {
            // If no new image is uploaded, retain the old image
            $user = $this->userModel->getUserById($id);
            $data['image'] = $user->image;
        }

        // Check for existing password and hash new password if provided
        $user = $this->userModel->getUserById($id);

        // If new password is provided, verify the current password and hash the new password
        if (!empty($data['new_password'])) {
            if (password_verify($data['current_password'], $user->password)) {
                $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
            } else {
                $data['password_err'] = 'Current password is incorrect';
            }
        } else {
            // If no new password is provided, retain the old password
            $data['password'] = $user->password;
        }

        // If no errors, update the user
        if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['address_err']) && empty($data['image_err']) && empty($data['password_err'])) {
            if ($this->userModel->updateUser($data)) {
                redirect('dpaccounts/account');
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
            'address_id' => $user->address_id,
            'image' => $user->image // Include existing image data
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

    public function addvehicle(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            // Process the form
            $data = [
                'type' => trim($_POST['type']),
                'regno' => trim($_POST['regno']),
                'capacity' => trim($_POST['capacity']),
                'v_image' => '', // Handle file upload
                'id' => $_SESSION['user_id'],
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

            if ($this->userModel->addVehicle($data)) {
                redirect('dpaccounts/vehicleinfo');
            } else {
                flash('vehicle_message', 'Failed to add vehicle', 'alert alert-danger');
                $this->view('d_person/vehicles/addvehicle', $data);
            }
        } else {
            // Load the form
            $id = $_SESSION['user_id'];
            $this->view('d_person/vehicles/addvehicle', $id);
        }
    }


    public function deactivate()
    {
    // Attempt to deactivate the user
    $result = $this->userModel->deleteAccount($_SESSION['user_id']);

    if ($result) {

        // Clear the session data to log the user out
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        
        $this->view('d_person/accounts/deactivation');
    } else {
        // Set an error flash message and redirect to an appropriate page
        flash('error', 'Failed to deactivate the user account. Please try again.');
        redirect('dpaccounts/account');
    }
    }


    /*private function setFlash($key, $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }*/

    public function confirmdelete($id) {
        $this->view('d_person/accounts/confirmation', $id);
    }
    

    }

