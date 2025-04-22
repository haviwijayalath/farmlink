<?php

function flash($name = '', $message = '', $class = 'alert alert-danger') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            // Set the flash message
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && isset($_SESSION[$name])) {
            // Display the flash message
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '">' . $_SESSION[$name] . '</div>';
            // Clear the flash message
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}