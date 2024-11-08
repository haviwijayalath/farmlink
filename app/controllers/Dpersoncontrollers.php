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

}