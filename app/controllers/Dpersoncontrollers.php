<?php

class Dpersoncontrollers extends Controller {
  public function __construct(){
    /*if(!isLoggedIn())
    {
        redirect('users/login');
    }*/
  }
    // Function to display account page
    public function viewProfile() {
        $data = [];
        $this->view('d_person/accounts/account', $data);
    }

    public function editProfile() {
        $this->view('d_person/accounts/editaccount');
    }

    public function neworder()
  {
      // Load the view template
      $this->view('d_person/neworders');
  }

  public function vehicleinfo()
  {
      // Load the view template
        $this->view('d_person/vehicles/vehicleinfo');
  }

  public function addvehicle()
  {
      // Load the view template
      $this->view('d_person/vehicles/addvehicle');
  }

  public function history()
  {
      // Load the view template
      $this->view('d_person/history');
  }

  public function ongoing()
  {
      // Load the view template
      $this->view('d_person/ongoing/ongoing');
  }

  public function deliveryupload()
  {
      // Load the view template
      $this->view('d_person/ongoing/d_upload');
  }


}