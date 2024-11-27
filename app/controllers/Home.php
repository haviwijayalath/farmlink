<?php

class Home extends Controller
{
    public function __construct(){
    }

    public function home2() {
        // Load the login view
        $this->view('home/home2');
    }

    public function home() {
        // Load the login view
        $this->view('home/home');
    }
}