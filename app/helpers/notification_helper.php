<?php

/**
 * Notification Helper
 *
 * This helper class is responsible for sending notifications.
 *
 * Place the send notification function inside controller methods where you need to send any notifications as,
 * 
 *    send_notification($from_type, $from_id, $to_type, $to_id, $subject, $content, $url, $msg_type)
 *    - from_type: Type of the sender ( f - farmer, b - buyer, d - dperson, c - consultant )
 *    - from_id: ID of the sender
 *    - to_type: Type of the receiver ( f - farmer, b - buyer, d - dperson, c - consultant )
 *    - to_id: ID of the receiver
 *    - subject: Subject of the notification / title
 *    - content: Content of the notification
 *    - url: URL associated with the notification
 *    - msg_type: Type of the message (e.g., 'info', 'warning', 'error')
 * 
 *    This function sends a notification based on the provided parameters.
 */

class NotificationHelper {
  private $notification;
  public function __construct() {
    $this->notification = new Notification();
  }

  public function send_notification($from_type, $from_id, $to_type, $to_id, $subject, $content, $url, $msg_type) {
    return $this->notification->addNotification($from_type, $from_id, $to_type, $to_id, $subject, $content, $url, $msg_type);
  }

  public function get_notifications($to_type, $to_id) {
    return json_encode($this->notification->getNotifications($to_type, $to_id));
  }
}


function getUserId() {
  if (isset($_SESSION['user_id'])) {
    return $_SESSION['user_id'];
  } elseif (isset($_SESSION['admin_id'])) {
    return $_SESSION['admin_id'];
  }
  return null;
}

function getUserType() {
  if (isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
      case 'farmer':
          return 'f';
      case 'buyer':
          return 'b';
      case 'dperson':
          return 'd';
      case 'consultant':
          return 'c';
      case 'admin':
          return 'a';
      default:
          return null;
    }
  } else if (isset($_SESSION['user_type'])) {
    // If user_type is stored directly
    return $_SESSION['user_type'];
  }
  return null;
}