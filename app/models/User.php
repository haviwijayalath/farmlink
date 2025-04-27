<?php
class User extends Database
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }


  public function login($email, $password)
  {
      // List of tables to check for the login
      $tables = ['delivery_persons', 'farmers', 'consultants', 'buyers', 'admins'];

      foreach ($tables as $table) {
          $this->db->query("SELECT * FROM $table WHERE email = :email");
          $this->db->bind(':email', $email);
        
          $row = $this->db->single();

          if ($row) {
              // Check password
              if (password_verify($password, $row->password)) {

            // Skip status check for admins and buyers
            // if ($table !== 'admins' && $table !== 'buyers' && isset($row->status)) {
            //   if ($row->status === 'pending') {
            //     return 'pending';
            //   } elseif ($row->status === 'suspended') {
            //     return 'suspended';
            //   }
            // }
                  // Attach role information
                  $row->role = $table;
                  return $row;
              } else {
                  return false; // Password mismatch
              }
          }
      }
      return false; // User not found
  }

  //Find user by email
  public function findUserByEmail($email)
  {
    // List of tables to check
    $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons', 'admins'];

    foreach ($tables as $table) {
      $this->db->query("SELECT * FROM $table WHERE email = :email");
      $this->db->bind(':email', $email);
      $row = $this->db->single();

      // Check if a match is found
      if ($this->db->rowCount() > 0) {
        return true;  // Stop iteration and return true immediately if a match is found
      }
    }
    return false;  // Return false if no match is found in any table
  }

  public function setSupportMessage($data)
  {
    $this->db->query('INSERT INTO support (name, email, message) VALUES (:name, :email, :message)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':message', $data['message']);
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function emailExistsInPasswordResets($email)
  {
    $this->db->query('SELECT * FROM password_resets WHERE email = :email');
    $this->db->bind(':email', $email);
    $this->db->execute();

    return $this->db->rowCount() > 0;
  }

  public function sendResetLink($email)
  {
    // Generate a unique token
    $token = bin2hex(random_bytes(16));
    $tokenHash = password_hash($token, PASSWORD_DEFAULT);

    // Set the expiration time (e.g., 1 hour from now)
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store the token in the database
    // check if the email exists in the password_resets table
    if ($this->emailExistsInPasswordResets($email)) {
      $this->db->query('UPDATE password_resets SET token = :token, expires = :expires WHERE email = :email');
    } else {
      $this->db->query('INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)');
    }
    $this->db->bind(':email', $email);
    $this->db->bind(':token', $tokenHash);
    $this->db->bind(':expires', $expires);

    // Execute the query
    if ($this->db->execute()) {
      // Send the email with the reset link
      $resetLink = 'http://localhost/farmlink/users/resetPassword?token=' . $token . '&email=' . urlencode($email);
      $subject = 'Password Reset Request';
      $message = 'Click the <a href="' . $resetLink . '">here</a> to reset your password: ';

      // Use your mailer function here to send the email
      $mail = mailerConfig();
      $mail->setFrom('no-reply-farmlink@demomailtrap.co', 'Support Farmlink');
      $mail->addAddress($email);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();
      return true; 
    } else {
      return false;
    }
  }

  public function isTokenValid($token, $email)
  {
    // Check if the token exists in the database and is not expired
    $this->db->query('SELECT * FROM password_resets WHERE email = :email AND expires > NOW()');
    $this->db->bind(':email', $email);
    $this->db->execute();

    $row = $this->db->single();

    if ($row && password_verify($token, $row->token)) {
      return true; // Token is valid
    } else {
      return false; // Token is invalid or expired
    }
  }

  public function resetPassword($email, $password)
  {
    $table = $this->whichTable($email);
    if ($table) {
      // Hash the new password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Update the password in the corresponding table
      $this->db->query("UPDATE $table SET password = :password WHERE email = :email");
      $this->db->bind(':password', $hashedPassword);
      $this->db->bind(':email', $email);

      return $this->db->execute();
    }
  }

  public function whichTable($email)
  {
    // List of tables to check
    $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons', 'admins'];

    foreach ($tables as $table) {
      $this->db->query("SELECT * FROM $table WHERE email = :email");
      $this->db->bind(':email', $email);
      $this->db->execute();

      // Check if a match is found
      if ($this->db->rowCount() > 0) {
        return $table;  // Return the table name immediately if a match is found
      }
    }
    return false;  // Return false if no match is found in any table
  }

}
