<?php
class Consultants extends Controller {
  private $consultantModel;

  public function __construct() {
    $this->consultantModel = $this->model('Consultant');
  }

  public function register() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone_number' => trim($_POST['phone_number']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'addr_no' => trim($_POST['addr_no']),
        'addr_street' => trim($_POST['addr_street']),
        'addr_city' => trim($_POST['addr_city']),
        'image' => isset($_FILES['image']) ? $_FILES['image'] : '',
        'specialization' => trim($_POST['specialization']),
        'experience' => trim($_POST['experience']),
        'name_err' => '',
        'email_err' => '',
        'phone_number_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'image_err' => '',
        'specialization_err' => '',
        'experience_err' => ''
      ];

      // Validate input (email, name, phone, password, confirm_password, specialization, etc.)
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter email';
      } elseif ($this->consultantModel->findUserByEmail($data['email'])) {
        $data['email_err'] = 'This email is already taken';
      }
      if (empty($data['name'])) {
        $data['name_err'] = 'Please enter name';
      }
      if (empty($data['phone_number'])) {
        $data['phone_number_err'] = 'Please enter phone number';
      }
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must be at least 6 characters';
      }
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Please confirm password';
      } elseif ($data['password'] != $data['confirm_password']) {
        $data['confirm_password_err'] = 'Passwords do not match';
      }
      if (empty($data['specialization'])) {
        $data['specialization_err'] = 'Please enter your specialization';
      }
      // Process image upload (omitted here for brevity)

      if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) &&
          empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['image_err']) &&
          empty($data['specialization_err'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if ($this->consultantModel->register($data)) {
          flash('register_success', 'You are successfully registered! Log in now');
          redirect('users/login');
        } else {
          die('Something went wrong! Please try again.');
        }
      } else {
        $this->view('consultant/register', $data);
      }
    } else {
      $data = [
        'name' => '',
        'email' => '',
        'phone_number' => '',
        'image' => '',
        'password' => '',
        'confirm_password' => '',
        'addr_no' => '',
        'addr_street' => '',
        'addr_city' => '',
        'specialization' => '',
        'experience' => '',
        'name_err' => '',
        'email_err' => '',
        'phone_number_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'image_err' => '',
        'specialization_err' => '',
        'experience_err' => ''
      ];
      $this->view('consultant/register', $data);
    }
  }

  public function index() {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
    $consultant = $this->consultantModel->getConsultantById($_SESSION['user_id']);
    $data = [
      'name' => $consultant->name,
      'phone' => $consultant->phone,
      'email' => $consultant->email,
      'image' => $consultant->image
    ];
    $this->view('consultant/index', $data);
  }

  public function viewprofile() {
    if (!isLoggedIn()) {
      redirect('users/login');
    }
  
    $consultant = $this->consultantModel->getConsultantbyId($_SESSION['user_id']);
  
    if ($consultant) { // Check if consultant data is found
      $data = [
          'name' => $consultant->name,
          'specialization' => $consultant->specialization, // Add specialization
          'experience' => $consultant->experience,         // Add experience
          'phone' => $consultant->phone,
          'email' => $consultant->email,
          'image' => $consultant->image
      ];
  
      $this->view('consultant/viewprofile', $data);
    }
  }
  
  public function editprofile() {
    if (!isLoggedIn()) {
        redirect('users/login');
    }
    
    // Process POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Build data array using the consultant's session id
        $data = [
            'id' => $_SESSION['user_id'],
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'specialization' => trim($_POST['specialization']),
            'experience' => trim($_POST['experience']),
            'address' => trim($_POST['address']),
            'current_password' => isset($_POST['current_password']) ? trim($_POST['current_password']) : '',
            'new_password' => trim($_POST['new_password']),
            'confirm_password' => trim($_POST['confirm_password']),
            'password' => '',  // This will be set later
            'image' => '',     // To be handled below
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'image_err' => ''
        ];
        
        // Basic validations
        if(empty($data['name'])) {
            $data['name_err'] = 'Please enter your name';
        }
        if(empty($data['email'])) {
            $data['email_err'] = 'Please enter your email';
        }
        if(!empty($data['new_password']) && ($data['new_password'] !== $data['confirm_password'])) {
            $data['password_err'] = 'New password and confirm password do not match';
        }
        
        // Handle image upload if a new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = APPROOT . '/../public/uploads/consultants/';
            // Create directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $target_file = $target_dir . $filename;
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                $data['image_err'] = 'Invalid file type. Only JPG, PNG, and GIF files are allowed.';
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $data['image'] = $filename;
                } else {
                    $data['image_err'] = 'Error uploading image. Please try again.';
                }
            }
        } else {
            // If no new image is uploaded, retain the old image
            $consultant = $this->consultantModel->getConsultantById($_SESSION['user_id']);
            $data['image'] = $consultant->image;
        }
        
        // Get current consultant data for password checking
        $consultant = $this->consultantModel->getConsultantById($_SESSION['user_id']);
        
        // Process password update if new password provided
        if (!empty($data['new_password'])) {
            if (password_verify($data['current_password'], $consultant->password)) {
                $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
            } else {
                $data['password_err'] = 'Current password is incorrect';
            }
        } else {
            // Retain the old password
            $data['password'] = $consultant->password;
        }
        
        // If no errors, update consultant profile
        if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['image_err'])) {
            if ($this->consultantModel->updateConsultant($data)) {
                redirect('consultants/viewprofile');
            } else {
                die('Something went wrong while updating your profile.');
            }
        } else {
            // Load view with errors
            $this->view('consultant/editprofile', $data);
        }
        
    } else {
        // On GET, load current consultant data from the model
        $consultant = $this->consultantModel->getConsultantById($_SESSION['user_id']);
        $data = [
            'id' => $consultant->id,
            'name' => $consultant->name,
            'email' => $consultant->email,
            'specialization' => $consultant->specialization,
            'experience' => $consultant->experience,
            'address' => $consultant->address,
            'image' => $consultant->image,
            'name_err' => '',
            'email_err' => '',
            'password_err' => '',
            'image_err' => ''
        ];
        $this->view('consultant/editprofile', $data);
    }
}

public function publicProfile($id) {
  if (!isLoggedIn()) {
      redirect('users/login');
  }
  
  $consultant = $this->consultantModel->getConsultantById($id);
  if (!$consultant) {
      flash('profile_error', 'Consultant not found', 'alert alert-danger');
      redirect('farmers/bookconsultant');
  }
  
  // Retrieve consultant's availability
  $availability = $this->consultantModel->getAvailability($id);
  $preselectedDates = [];
  foreach ($availability as $slot) {
      // Ensure that available_date is in the format "YYYY-MM-DD"
      $preselectedDates[] = $slot->available_date;
  }
  
  // Add the JSON encoded array of dates to the data array.
  $data = [
      'id' => $consultant->id,
      'name' => $consultant->name,
      'email' => $consultant->email,
      'specialization' => $consultant->specialization,
      'experience' => $consultant->experience,
      'phone' => $consultant->phone,
      'image' => $consultant->image,
      'availability' => json_encode($preselectedDates)  // Pass availability as JSON
  ];
  
  $this->view('consultant/publicProfile', $data);
}

public function setAvailability() {
  if (!isLoggedIn()) {
      redirect('users/login');
  }

  // On POST: process the submitted dates.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Sanitize POST data
      $datesJson = isset($_POST['dates']) ? $_POST['dates'] : '[]';
      $dates = json_decode($datesJson, true);

      // Delete previous availability if you want to fully reset the calendar:
      $this->consultantModel->deleteAvailability($_SESSION['user_id']);

      if (!empty($dates)) {
          foreach ($dates as $date) {
              $this->consultantModel->addAvailability($_SESSION['user_id'], $date);
          }
          flash('availability_success', 'Availability updated successfully');
          redirect('consultants/setAvailability'); // Or whichever page you prefer
      } else {
          flash('availability_error', 'No dates selected', 'alert alert-danger');
          redirect('consultants/setAvailability');
      }
  } else {
      // On GET, simply load the view with the calendar interface.
      // Optionally, load existing availability to preselect dates in the calendar.
      $availability = $this->consultantModel->getAvailability($_SESSION['user_id']);
      $preselectedDates = [];
      foreach ($availability as $slot) {
          // Assuming available_date is stored in format 'Y-m-d'
          $preselectedDates[] = $slot->available_date;
      }
      $data = [ 'preselected' => json_encode($preselectedDates) ];
      $this->view('consultant/availability', $data);
  }
}


}
