<?php
  class Consultants extends Controller {
    private $consultantModel;

    public function __construct() {
      $this->consultantModel = $this->model('Consultant');
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
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'addr_no' => trim($_POST['addr_no']),
          'addr_street' => trim($_POST['addr_street']),
          'addr_city' => trim($_POST['addr_city']),
          'image' => isset($_POST['image']) ? $_POST['image'] : '', 
          'specialization' => trim($_POST['specialization']),
          'experience'=> trim($_POST['experience']),

          'name_err' => '',
          'email_err' => '',
          'phone_number_err' => '',
          'password_err' => '',
          'confirm_password_err' => '',
          'image_err' => '',
          'specialization_err' => '',
          'experience_err'=>''
        ];

        // Validate Email
        if (empty($data['email'])) {
          $data['email_err'] = 'Please enter email';
        } else {
          // if email exists
          if ($this->consultantModel->findUserByEmail($data['email'])) {
            $data['email_err'] = 'This email is already taken';
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

         // Validate Specialization
         if (empty($data['specialization'])) {
            $data['specialization_err'] = 'Please enter your specialization';
          }

        // image saved directory
        $target_dir = APPROOT . '/../public/uploads/consultant/profile/';
        $filename = time() . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        $_picuploaded = true;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
          $_picuploaded = true;
        } else {
          $data['image_err'] = 'File is not an image';
          $_picuploaded = false;
        }

        // Check file size
        if ($_FILES['image']['size'] > 2000000) {
          $data['image_err'] = 'Your photo exceeds the size limit of 2MB';
          $_picuploaded = false;
        }

        // Allow certain file formats
        if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
          $data['image_err'] = 'Please upload a photo with extension .jpg, .jpeg, or .png';
          $_picuploaded = false;
        }

        // Check if $_picuploaded is set to false
        if ($_picuploaded == false) {
          $data['image_err'] = 'Sorry, your file was not uploaded';
        } else {
          // if everything is ok, try to upload file
          if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image'] = $filename;
          } else {
            $data['image_err'] = 'Sorry, there was an error uploading your file';
          }
        }

        // Make sure no other errors before uploading the picture
        if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['image_err'])) {
          // hashing password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // user registration
          if ($this->consultantModel->register($data)) {
            flash('register_success', 'You are successfully registered! Log in now');
            // redirect to login
            redirect('users/login');
          } else {
            die('Something went wrong! Please try again.');
          }
        } else {
          // Load view with errors
          $this->view('consultant/register', $data);
        }
      } else {
        // Init data
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
          'image' => '',
          'specialization' => '',
          'experience'=>'',

          'name_err' => '',
          'email_err' => '',
          'phone_number_err' => '',
          'password_err' => '',
          'confirm_password_err' => '',
          'image_err' => '',
          'specialization_err' => '',
          'experience_err'=>''
        ];

        // Load view
        $this->view('consultant/register', $data);
      }
    }

    public function index() {
      if (!isLoggedIn()) {
        redirect('users/login');
      }

      $consultant = $this->consultantModel->getConsultantbyId($_SESSION['user_id']);
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

      $this->view('consultant/viewprofile');
    }

    public function editprofile() {
      if (!isLoggedIn()) {
        redirect('users/login');
      }
      
      $this->view('consultant/editprofile');
    }

    public function getQuestions() {
        // Retrieve data using the model
        $questions = $this->consultantModel->fetchQuestions();
    
        // Check if data exists
        if ($questions) {
            // Send data to the view
            $this->view('consultant/pages/forum', ['questions' => $questions]);
        } else {
            // If no data found, handle appropriately
            flash('data_message', 'No questions found', 'alert alert-warning');
            $this->view('consultant/pages/forum', ['questions' => []]);
        }
    }
    
    public function sendAnswer() {
        // Check for POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            // Initialize data
            $data = [
                'consultant_id' => trim($_POST['consultant_id']),
                'q_id' => trim($_POST['q_id']),
                'answer' => trim($_POST['answer']),
                'answer_err' => ''
            ];
    
    
            // Validate description
            if (empty($data['answer'])) {
                $data['answer_err'] = 'Answer is required';
            }
    
            // Ensure no errors exist
            if (empty($data['answer_err'])) {
                // All validations passed
                if ($this->consulatntModel->storeAnswer($data)) {
                if ($this->consulatntModel->storeAnswer($data)) {
                    // Success, redirect or handle response
                    flash('Succesfull', 'Answer successfully submitted');
                    redirect('consultant/index');
                } else {
                    // Error storing data
                    flash('Unsuccesfull', 'Error submitting answer', 'alert alert-danger');
                    $this->view('consultant/pages/forum', $data);
                    $this->view('consultant/pages/forum', $data);
                }
            } else {
                // Load the view with errors
                $this->view('consultant/pages/forum', $data);
                $this->view('consultant/pages/forum', $data);
            }
        } else {
            // Initialize empty data for GET request
            $data = [
                'consultant_id' => '',
                'q_id' => '',
                'answer' => '',
                'answer_err' => ''
            ];
    
            // Load the form view
            $this->view('consultant/pages/forum', $data);
            $this->view('consultant/pages/forum', $data);
        }
    }
    
  
  }