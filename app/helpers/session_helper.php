<?php
 session_start();
 
 function isLoggedIn(){
  if(isset($_SESSION['user_id'])) {
      return true;
  } else {
      return false;
  }
}

function isAdmin()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// flash message helper
  // EXAMPLE - flash('register_success', 'You are successfully registered', 'alert alert-danger')
  // DISPLAY ON VIEW - <?php echo flash(;register_success');
  function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
      if (!empty($message) && empty($_SESSION[$name])) {
        $_SESSION[$name] = $message;
        $_SESSION[$name . '_class'] = $class;
      } elseif (empty($message) && !empty($_SESSION[$name])) {
        $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
        echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
        unset($_SESSION[$name]);
        unset($_SESSION[$name . '_class']);
      }
    }
  }