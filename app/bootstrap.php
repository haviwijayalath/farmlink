<?php
  // Load config
  require_once 'config/config.php';

  //Load helpers
  require_once 'helpers/url_helper.php';
  require_once 'helpers/session_helper.php';
  require_once 'models/Notification.php'; // model required for notification
  require_once 'helpers/notification_helper.php'; // helper for notification

  // autoload core libraries
  spl_autoload_register(function($className) {
    require_once 'libraries/'. $className .'.php';
  });