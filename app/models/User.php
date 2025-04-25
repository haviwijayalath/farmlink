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
}
