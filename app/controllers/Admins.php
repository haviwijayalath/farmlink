<?php
  class Admins extends Controller {
    private $adminModel;
    
    public function __construct() {

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
      
      $this->view('admin/products');

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

