<?php
class Dpaccounts extends Controller
{

    private $userModel;
    private $earningsModel;

    public function __construct()
    {
        
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login'); // Redirect to login page if not logged in
        }

        $this->userModel = $this->model('Dperson');
        $this->earningsModel = $this->model('Dpaccount');
    }

    public function index()
    {
        echo ("invalid");
    }

    private function getUserDataById($id)
    {
        $user = $this->userModel->getUserById($id);

        if ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            
                'address' => $user->number . ', ' . $user->street . ', ' . $user->city,
                'area' => $user->area,
                'image' => $user->image,
                'type' => $user->type,
                'regno' => $user->regno,
                'capacity' => $user->capacity,
                'v_image' => $user->v_image,
                'v_strcture' => $user->structure
            ];
        } else {
            flash('user_message', 'User not found');
            redirect('dpersons/neworder');
        }
    }

    public function editProfile($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
           
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            
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
                'image' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'image_err' => ''
            ];

            
            if (empty($data['name'])) $data['name_err'] = 'Please enter name';
            if (empty($data['email'])) $data['email_err'] = 'Please enter email';
            if (!empty($data['new_password']) && $data['new_password'] !== $data['confirm_password']) {
                $data['password_err'] = 'Passwords do not match';
            }

            
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = APPROOT . '/../public/uploads/';

                
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                
                $file_name = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $file_name;

                
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

            
            $user = $this->userModel->getUserById($id);

        
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

            
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err']) && empty($data['address_err']) && empty($data['image_err']) && empty($data['password_err'])) {
                if ($this->userModel->updateUser($data)) {
                    redirect('dpaccounts/account');
                } else {
                    die('Something went wrong');
                }
            } else {
                
                $this->view('d_person/accounts/editaccount', $data);
            }
        } else {
            
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
                'image' => $user->image 
            ];

            
            $this->view('d_person/accounts/editaccount', $data);
        }
    }

    public function account()
    {
        
        $id = $_SESSION['user_id'];  

        
        $data = $this->getUserDataById($id);

        
        $this->view('d_person/accounts/account', $data);
    }

    public function vehicleinfo()
    {

        $id = $_SESSION['user_id'];
        $data = $this->getUserDataById($id);

        
        $this->view('d_person/vehicles/vehicleinfo', $data);
    }



    

    public function addvehicle()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
           
            $data = [
                'type' => trim($_POST['type']),
                'regno' => trim($_POST['regno']),
                'capacity' => trim($_POST['capacity']),
                'v_image' => '', 
                'id' => $_SESSION['user_vehicle_id']
            ];

        
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
            
            $id = $_SESSION['user_id'];
            $this->view('d_person/vehicles/addvehicle', ['id' => $id]);
        }
    }


    public function deactivate()
    {
        
        $result = $this->userModel->deleteAccount($_SESSION['user_id']);

        if ($result) {

            
            session_unset(); 
            session_destroy(); 

            $this->view('d_person/accounts/deactivation');
        } else {
            
            flash('error', 'Failed to deactivate the user account. Please try again.');
            redirect('dpaccounts/account');
        }
    }

    public function confirmdelete($id)
    {
        $this->view('d_person/accounts/confirmation', ['id' => $id]);
    }


    public function revenueCheck()
    {
        if (!isLoggedIn() || $_SESSION['user_role'] != 'dperson') {
            redirect('users/login');
        }

        $deliveryPersonId = $_SESSION['user_id'];

        
        $currentMonth = $this->earningsModel->getMonthlyEarnings($deliveryPersonId, 0);
        $lastMonth = $this->earningsModel->getMonthlyEarnings($deliveryPersonId, 1);
        $yearly = $this->earningsModel->getYearlyEarnings($deliveryPersonId);
        $trendData = $this->earningsModel->getMonthlyTrend($deliveryPersonId);
        $totalEarning = $this->earningsModel->getTotal($deliveryPersonId);

        $monthlyEarnings = array_fill(1, 12, 0);
        foreach ($trendData as $row) {
            $monthlyEarnings[(int)$row->month] = (float)$row->total;
        }

        
        $orderIdFilter = '';
        $dateFilter = '';
        $earnings = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderIdFilter = trim($_POST['order_id'] ?? '');
            $dateFilter = trim($_POST['date'] ?? '');

            
            if ($orderIdFilter === '' && $dateFilter === '') {
                $earnings = $this->earningsModel->getFilteredEarnings($deliveryPersonId);
            } else {
                
                $earnings = $this->earningsModel->getFilteredEarnings($deliveryPersonId, $orderIdFilter, $dateFilter);
            }
        } else {
           
            $earnings = $this->earningsModel->getFilteredEarnings($deliveryPersonId);
        }

        
        $data = [
            'currentMonth' => $currentMonth,
            'lastMonth' => $lastMonth,
            'yearly' => $yearly,
            'monthlyTrend' => $monthlyEarnings,
            'earnings' => $earnings,
            'order_id' => $orderIdFilter, 
            'date' => $dateFilter,
            'total' => $totalEarning         
        ];

        $this->view('d_person/accounts/revenue', $data);
    }
}
