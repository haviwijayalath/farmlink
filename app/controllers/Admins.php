<?php
class Admins extends Controller
{
  private $adminModel;
  private $complaintModel;

  public function __construct()
  {
    if (!isAdmin()) {
      redirect('users/login');
    }

    $this->adminModel = $this->model('Admin');
    $this->complaintModel = $this->model('Complaint');
  }

  // Dashboard
  public function index()
  {
    $data = [
      'stats'           => $this->adminModel->getDashboardStats(),
      'salesByLocation' => $this->adminModel->getSalesByLocation(),
      'salesByCategory' => $this->adminModel->getSalesByCategory(),
      'topProducts'     => $this->adminModel->getTopSellingProducts()
    ];
    $this->view('admin/home', $data);
  }

  // Users list
  public function users()
  {
    $users = $this->adminModel->getAllUsers();
    $this->view('admin/users', ['users' => $users]);
  }

  // Orders list

  public function orders() {
    // grab filters from GET 
    $status    = $_GET['status']     ?? '';
    $startDate = $_GET['start_date'] ?? '';
    $endDate   = $_GET['end_date']   ?? '';

    //fetch only the matching orders
    $orders = $this->adminModel->getFilteredOrders($status, $startDate, $endDate);

    //pass both the orders and the current filter values back to the view
    $data = [
      'orders'     => $orders,
      'status'     => $status,
      'start_date' => $startDate,
      'end_date'   => $endDate
    ];

    $this->view('admin/orders', $data);
  }


  public function order_details() { 
      
    $this->view('admin/order_detail');
  }

  public function viewComplaints()
  {
    $complaints = $this->complaintModel->getComplaints();

    $data = [
      'complaints' => $complaints
    ];

    $this->view('admin/complaints/complaints', $data);
  }


  public function manageComplaint($complaint_id)
  {
    $complaint = $this->complaintModel->getComplaintById($complaint_id);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

      $adminNote = trim($_POST['admin_note']);
      $faultBy = isset($_POST['fault_by']) ? trim($_POST['fault_by']) : '';

      $errors = [];

      // Validate admin note
      if (empty($adminNote)) {
        $errors['admin_note'] = 'Admin note is required.';
      }

      // Validate fault radio
      if (empty($faultBy)) {
        $errors['fault_by'] = 'Fault selection is required.';
      }

      if (empty($errors)) {
       
        $this->complaintModel->resolveComplaint($complaint_id, $adminNote, $faultBy);

        
        flash('complaint_message', 'Complaint resolved successfully!');
        redirect('admin/complaints');
      } else {
        // Return errors back to the view
        $data = [
          'complaint' => $complaint,
          'errors' => $errors,
          'admin_note' => $adminNote,
          'fault_by' => $faultBy
        ];
        $this->view('admin/complaints/manage', $data);
      }
    } else {
      
      $data = [
        'complaint' => $complaint,
        'errors' => [],
        'admin_note' => '',
        'fault_by' => ''
      ];
      $this->view('admin/complaints/manage', $data);
    }
  }

  public function show($id)
  {
    
    $complaints = $this->complaintModel->getComplaints();

    
    $selectedComplaint = null;
    foreach ($complaints as $complaint) {
      if ($complaint->complaint_id == $id) {
        $selectedComplaint = $complaint;
        break;
      }
    }

    if (!$selectedComplaint) {
      
      flash('complaint_msg', 'Complaint not found');
      redirect('admin/complaints');
    }

    
    $this->view('admin/complaints/show', ['complaint' => $selectedComplaint]);
  }

  public function resolve($complaint_id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $faultBy = isset($_POST['fault_by']) ? trim($_POST['fault_by']) : '';
        $adminNotes = isset($_POST['admin_notes']) ? trim($_POST['admin_notes']) : '';

       
        $faultBy = htmlspecialchars($faultBy);
        $adminNotes = htmlspecialchars($adminNotes);

        
        $complaint = $this->complaintModel->getComplaintById($complaint_id);

        if (!$complaint) {
            die("Complaint not found");
        }

        
        $errors = [];

        if (empty($faultBy)) {
            $errors['fault_by'] = 'You must select who is at fault.';
        }

        if (empty($adminNotes)) {
            $errors['admin_notes'] = 'You must provide admin notes.';
        }

        if (!empty($errors)) {
           
            $data = [
                'complaint' => $complaint,
                'errors' => $errors,
                'fault_by' => $faultBy,
                'admin_notes' => $adminNotes
            ];

            $this->view('admin/complaints/manage', $data);
            return;
        }

        $order_id = $complaint->order_id;
        $farmer_id = $complaint->farmer_id;
        $delivery_id = $complaint->delivery_person_id;

        // Update complaint record
        $this->complaintModel->resolveComplaint($complaint_id, $faultBy, $adminNotes);

        // Take action based on decision
        if ($faultBy === 'farmer') {
            $this->complaintModel->deductFarmerRating($farmer_id);
        }

        flash('complaint_msg', 'Complaint resolved and action taken.');
        redirect('admins/viewComplaints');
    } else {
        redirect('admins/viewComplaints');
    }
}

  public function filterComplaints()
  {
    $role = $_GET['role'] ?? '';
    $status = $_GET['status'] ?? '';

    $complaints = $this->complaintModel->getFilteredComplaints($role, $status);

    $data = [
      'complaints' => $complaints
    ];

    $this->view('admin/complaints/complaints', $data);
  }

  public function filterReports()
  {
    $role = $_GET['role'] ?? '';

    $reports = $this->complaintModel->getFilteredReports($role);
    $reports = $this->complaintModel->getFilteredReports($role);

    $data = [
      'users' => $reports,
      'selected_role' => $role
    ];
    $data = [
      'users' => $reports,
      'selected_role' => $role
    ];

    $this->view('admin/reports', $data);
  }


  public function viewReports()
  {
    $users = $this->adminModel->getUsers();

    $farmers = [];
    $deliveryPersons = [];
    $totalFarmerRevenue = 0;
    $totalDeliveryRevenue = 0;

    foreach ($users as $user) {
      if ($user->role === 'Farmer') {
        $user->revenues = $this->adminModel->getTotalRevenue($user->id, null);
        $farmers[] = $user;
        $totalFarmerRevenue += $user->revenues->total_farmer_fee ?? 0;
      } elseif ($user->role === 'Delivery_Person') {
        $user->revenues = $this->adminModel->getTotalRevenue(null, $user->id);
        $deliveryPersons[] = $user;
        $totalDeliveryRevenue += $user->revenues->total_delivery_fee ?? 0;
      }
    }

    $data = [
      'farmers' => $farmers,
      'deliveryPersons' => $deliveryPersons,
      'totalFarmerRevenue' => $totalFarmerRevenue,
      'totalDeliveryRevenue' => $totalDeliveryRevenue
    ];

    $this->view('admin/reports', $data);
  }

  //   public function viewMonthlyRevenue($userId, $role = null)
  // {
  //     if ($role === null) {
  //         die('Role is missing!'); // Don't proceed if role is missing
  //     }

  //     $monthlyRevenue = $this->adminModel->getMonthlyRevenue($userId, $role);

  //     $this->view('admin/monthly_revenue', [
  //         'monthlyRevenue' => $monthlyRevenue,
  //         'role' => $role
  //     ]);
  // }

  public function viewProducts()
  {

    $products = $this->adminModel->getProducts();

    $data = [
      'products' => $products
    ];
    $this->view('admin/products', $data);
  }


  
  public function productDetails($id)
  {
    $product = $this->adminModel->getProductById($id);
    if (!$product) die("Product not found");
    $this->view('admin/productDetails', ['product' => $product]);
  }

  
  public function account()
  {
    $adminId = $_SESSION['admin_id'];
    $admin   = $this->adminModel->getAdminById($adminId);
    if (!$admin) {
      flash('user_error', 'Admin not found', 'alert alert-danger');
      redirect('users/login');
    }
    $this->view('admin/account', ['admin' => $admin]);
  }

  
  public function editAccount()
  {
    $adminId = $_SESSION['admin_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'id'    => $adminId,
        'name'  => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone'])
      ];
      if ($this->adminModel->updateAccount($adminId, $data)) {
        redirect('admins/account');
      } else {
        die('Failed to update account');
      }
    } else {
      $admin = $this->adminModel->getAdminById($adminId);
      $this->view('admin/editAccount', ['admin' => $admin]);
    }
  }

  
  public function changepwrd()
  {
    $adminId = $_SESSION['admin_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $current = $_POST['current_password'];
      $new     = $_POST['new_password'];
      $confirm = $_POST['confirm_password'];

      if ($new !== $confirm) {
        return $this->view('admin/changePwrd', ['error' => 'Passwords do not match']);
      }

      if ($this->adminModel->changePassword($adminId, $current, $new)) {
        redirect('admins/account');
      } else {
        return $this->view('admin/changePwrd', ['error' => 'Current password incorrect']);
      }
    } else {
      $this->view('admin/changePwrd');
    }
  }

  
  public function deactivateConfirmation()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->adminModel->deactivateAccount($_SESSION['admin_id']);
      session_destroy();
      redirect('users/login');
    } else {
      $this->view('admin/confirmation');
    }
  }
}
