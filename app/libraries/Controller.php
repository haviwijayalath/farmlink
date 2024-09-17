<?php
  // Loads models and views

  class Controller {
    // load model
    public function model($model) {
      // requiring themodel file and instantiating it
      require_once '../app/models/' . $model . '.php';
      return new $model();
    }

    // load view
    public function view($view, $data = []) {
      // check for the view file
      if (file_exists('../app/views/' . $view . '.php')) {
        require_once '../app/views/' . $view . '.php';
      } else {
        die('View does not exist');
      }
    }
  }