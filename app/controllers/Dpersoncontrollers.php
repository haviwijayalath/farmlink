<?php

class Dpersoncontrollers extends Controller {
    public function __construct() {
        
    }

    // Function to display account page
    public function viewProfile() {
        $data = [];
        $this->view('d_person/accounts/account', $data);
    }

    public function editProfile() {
        $this->view('d_person/accounts/editaccount');
    }

}