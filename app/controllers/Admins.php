<?php
  class Admins extends Controller {
    private $adminModel;
    
    public function __construct() {
      if (!isAdmin()) {
        redirect('users/login');
    }

    $this->adminModel = $this->model('Admin'); 
    $this->complaintModel = $this->model('Complaint');
    
    }

    public function index() { 
      
      $this->view('admin/home');

    }

    public function users() { 
      
      $this->view('admin/users');

    }

    public function orders() { 
      
      $this->view('admin/orders');

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

    public function viewProducts() { 
      
      $products = $this->adminModel->getProducts();

        $data = [
            'products' => $products
        ];

        $this->view('admin/products', $data);
    }

    public function productDetails($id) {
      // Fetch a single product by ID
      $product = $this->adminModel->getProductById($id);
  
      if (!$product) {
          die("Product not found"); // Handle error appropriately
      }
  
      $data = [
          'product' => $product
      ];
  
      $this->view('admin/productDetails', $data);
    }

    public function account() { 
      
      $this->view('admin/account');

    }

    public function editAccount() { 
      
      $this->view('admin/editAccount');

    }


    public function changepwrd() { 
      
      $this->view('admin/changePwrd');
    }

    public function deactivate() { 
      
      $this->view('admin/deactivate');
    }

    public function deactivateConfirmation() { 
      
      $this->view('admin/confirmation');
    }
}

