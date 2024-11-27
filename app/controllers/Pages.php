<?php
  class Pages extends Controller {
    private $postModel;
    
    public function __construct() {
      
    }

    public function index() { 
      $this->view('home/home');
    }

    public function about() {
      $data = [
        'title' => 'About Us'
      ];
      $this->view('pages/about', $data);
    }
  }