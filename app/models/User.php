<?php
class User extends Database{
  private $db;

  public function __construct(){
    $this->db = new Database;
  }


  public function login($email, $password){
    // List of tables to check for the login
    $tables = [ 'suppliers', 'delivery_persons', 'farmers', 'consultants', 'buyers'];
    
    // Loop through each table and attempt to find a matching record
    foreach ($tables as $table) {
        $this->db->query("SELECT * FROM $table WHERE email = :email");
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();

        if ($row) {
          // User found; now verify the password
          if (password_verify($password, $row->password)) {
              // Attach role information to user data for session
              $row->role = $table; 
              return $row; // Return user data if password matches
          } else {
              return false; // Password mismatch
          }
        }
    }
    return false; // User not found in any table
  }

  //Find user by email
public function findUserByEmail($email) {
  // List of tables to check
  $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons'];

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


/*public function findUserByUserName($userName) {
    // List of tables to check
    $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons'];

    foreach ($tables as $table) {
        $this->db->query("SELECT * FROM $table WHERE username = :user_name");
        $this->db->bind(':user_name', $userName);
        $row = $this->db->single();

        // If a match is found in any table, return true
        if($this->db->rowCount() > 0){
          return true;
        }else {
          return false;
        }
    }
  }*/
}

?>