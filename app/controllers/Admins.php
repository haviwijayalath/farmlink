<?php
class Admins extends Controller {
  private $adminModel;
  
  public function __construct() {
    if (!isAdmin()) {
      redirect('users/login');
    }
    $this->adminModel = $this->model('Admin');
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

  // Single order + its items
  public function order_details($id) {
    $order      = $this->adminModel->getOrderById($id);
    $orderItems = $this->adminModel->getOrderItems($id);
    if (!$order) die("Order not found");
    $this->view('admin/order_detail', [
      'order'      => $order,
      'orderItems' => $orderItems
    ]);
  }

  // Complaints
  public function viewComplaints() {
    $complaints = $this->adminModel->getAllComplaints();
    $this->view('admin/complaints', ['complaints' => $complaints]);
  }

  // (You can remove viewReports if you're not storing a reports table)
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
