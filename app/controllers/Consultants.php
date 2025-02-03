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
          'experience' => $consultant->experience,       // Add experience
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
    
    $this->view('consultant/editprofile');
  }
}
