<?php

class Buyercontrollers extends Controller {
    public function __construct() {
        
    }

    // Function to display account page
    public function viewProfile() {
        $data = [];
        $this->view('buyer/accounts/buyer_account', $data);
    }

    public function editProfile() {
        $this->view('buyer/accounts/buyer_editaccount');
    }

}