<?php
  class Admins extends Controller {
    private $adminModel;
    
    public function __construct() {
      if (!isAdmin()) {
        redirect('users/login');
    }

    $this->adminModel = $this->model('Admin'); 
    
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
      
      $this->view('admin/complaints');

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

