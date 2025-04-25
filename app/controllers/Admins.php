<?php
  class Admins extends Controller {
    private $adminModel;
    private $complaintModel;
    
    public function __construct() {
      if (!isAdmin()) {
        redirect('users/login');
    }

    $this->adminModel = $this->model('Admin'); 
    $this->complaintModel = $this->model('Complaint');
    
    }

  // Dashboard
  public function index() {
    $data = [
      'stats'           => $this->adminModel->getDashboardStats(),
      'salesByLocation' => $this->adminModel->getSalesByLocation(),
      'salesByCategory' => $this->adminModel->getSalesByCategory(),
      'topProducts'     => $this->adminModel->getTopSellingProducts()
    ];
    $this->view('admin/home', $data);
  }

  // Users list
  public function users() {
    $users = $this->adminModel->getAllUsers();
    $this->view('admin/users', ['users' => $users]);
  }

  // Orders list
  public function orders() {
    $orders = $this->adminModel->getAllOrders();
    $this->view('admin/orders', ['orders' => $orders]);
  }

    public function order_details() { 
      
      $this->view('admin/order_detail');

    }

    public function viewComplaints() {
      $complaints = $this->complaintModel->getComplaints();

      $data = [
          'complaints' => $complaints
      ];

      $this->view('admin/complaints/complaints', $data);
    }

    public function manageComplaint($complaint_id) {
      $complaint = $this->complaintModel->getComplaintById($complaint_id);
      $data = [
          'complaint' => $complaint
      ];

      $this->view('admin/complaints/manage', $data);
    }

    public function show($id) {
      // Fetch all complaints using the existing model function
      $complaints = $this->complaintModel->getComplaints();
  
      // Filter the complaint matching the ID
      $selectedComplaint = null;
      foreach ($complaints as $complaint) {
          if ($complaint->complaint_id == $id) {
              $selectedComplaint = $complaint;
              break;
          }
      }
  
      if (!$selectedComplaint) {
          // Optional: flash message or redirect
          flash('complaint_msg', 'Complaint not found');
          redirect('admin/complaints');
      }
  
      // Send data to the view
      $this->view('admin/complaints/show', ['complaint' => $selectedComplaint]);
    }

    public function resolve($complaint_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $faultBy = $_POST['fault_by'];
            $adminNotes = trim($_POST['admin_notes']);
    
            // Sanitize inputs (basic level, can enhance as needed)
            $faultBy = htmlspecialchars($faultBy);
            $adminNotes = htmlspecialchars($adminNotes);
    
            // Load the complaint
            $complaint = $this->complaintModel->getComplaintById($complaint_id);
    
            if (!$complaint) {
                die("Complaint not found");
            }
    
            $order_id = $complaint->order_id;
    
            // Fetch involved users based on order ID
            $farmer_id = $complaint->farmer_id;
            $delivery_id = $complaint->delivery_person_id;
    
            // Update complaint record with decision and notes
            $this->complaintModel->resolveComplaint($complaint_id, $faultBy, $adminNotes);
    
            // Take action based on decision
            if ($faultBy === 'farmer') {
                $this->complaintModel->deactivateFarmer($farmer_id);
            } elseif ($faultBy === 'delivery') {
                $this->complaintModel->deactivateDperson($delivery_id);
            }
    
            flash('complaint_msg', 'Complaint resolved and action taken.');
            redirect('admins/viewComplaints');
        } else {
            redirect('admins/viewComplaints');
        }
    }

    public function filterComplaints() {
      $role = $_GET['role'] ?? '';
      $status = $_GET['status'] ?? '';
  
      $complaints = $this->complaintModel->getFilteredComplaints($role, $status);
  
      $data = [
          'complaints' => $complaints
      ];
  
      $this->view('admin/complaints/complaints', $data);
  }
  
    
    public function viewReports() { 
      
      $this->view('admin/reports');

    }

  // Products list
  public function products() {
    $products = $this->adminModel->getProducts();
    $this->view('admin/products', ['products' => $products]);
  }
  public function viewProducts() { $this->products(); }

  // Single product
  public function productDetails($id) {
    $product = $this->adminModel->getProductById($id);
    if (!$product) die("Product not found");
    $this->view('admin/productDetails', ['product' => $product]);
  }

  // Show profile
  public function account() {
    $adminId = $_SESSION['admin_id'];
    $admin   = $this->adminModel->getAdminById($adminId);
    if (!$admin) {
      flash('user_error','Admin not found','alert alert-danger');
      redirect('users/login');
    }
    $this->view('admin/account', ['admin' => $admin]);
  }

  // EDIT PROFILE
  public function editAccount() {
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

  // CHANGE PASSWORD
  public function changepwrd() {
    $adminId = $_SESSION['admin_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $current = $_POST['current_password'];
      $new     = $_POST['new_password'];
      $confirm = $_POST['confirm_password'];

      if ($new !== $confirm) {
        return $this->view('admin/changePwrd', ['error'=>'Passwords do not match']);
      }

      if ($this->adminModel->changePassword($adminId, $current, $new)) {
        redirect('admins/account');
      } else {
        return $this->view('admin/changePwrd', ['error'=>'Current password incorrect']);
      }
    } else {
      $this->view('admin/changePwrd');
    }
  }

  // Deactivate
  public function deactivateConfirmation() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $this->adminModel->deactivateAccount($_SESSION['admin_id']);
      session_destroy();
      redirect('users/login');
    } else {
      $this->view('admin/confirmation');
    }
  }
}
