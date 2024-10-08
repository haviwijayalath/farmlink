<?php
  // URL format - /controller/method/params

  class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
      $url = $this->getUrl();

      // Look in controllers for first value
      if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
        // Set as controller & unset 0th index
        $this->currentController = ucwords($url[0]); 
        unset($url[0]);
      }

      // requiring the controller
      require_once '../app/controllers/' . $this->currentController . '.php';

      // instantiate the controller class
      $this->currentController = new $this->currentController;

      // check for second part of the url and setting it as the method
      if (isset($url[1])) {
        if (method_exists($this->currentController, $url[1])) {
          $this->currentMethod = $url[1];
          unset($url[1]);
        }
      }

      // get params
      $this->params = $url ? array_values($url) : [];

      // call a callback with an array of params
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    // getting the url by splitting it into an array
    public function getUrl() {
      if (isset($_GET['url'])) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  }