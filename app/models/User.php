<?php
class User extends Database{
  private $db;

  public function __construct(){
    $this->db = new Database;
  }

  //Register farmer
  public function registerFarmer($data) {
    $this->db->query('INSERT INTO farmers (name, password, email, phone, username) VALUES (:name, :password, :email, :phone_number, :username)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone_number', $data['phone_number']);
    $this->db->bind(':username', $data['user_name']);
    $this->db->bind(':password', $data['password']);
    return $this->db->execute();

    //Execute
    if($this->db->execute()){
      return true;
    }else {
      return false;
    }
  }

  public function registerDeliveryPerson($data) {
    $this->db->query('INSERT INTO delivery_persons (name, password, email, phone, username) VALUES (:name, :password, :email, :phone_number, :username)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone_number', $data['phone_number']);
    $this->db->bind(':username', $data['user_name']);
    $this->db->bind(':password', $data['password']);
    return $this->db->execute();

    //Execute
    if($this->db->execute()){
      return true;
    }else {
      return false;
    }
  }

  public function registerSupplier($data) {
    $this->db->query('INSERT INTO suppliers (name, password, email, phone, username) VALUES (:name, :password, :email, :phone_number, :username)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone_number', $data['phone_number']);
    $this->db->bind(':username', $data['user_name']);
    $this->db->bind(':password', $data['password']);
    return $this->db->execute();

    //Execute
    if($this->db->execute()){
      return true;
    }else {
      return false;
    }
  }


  public function login($userName, $password){
    // List of tables to check for the login
    $tables = [ 'suppliers', 'delivery_persons', 'farmers', 'consultants', 'buyers'];
    
    // Loop through each table and attempt to find a matching record
    foreach ($tables as $table) {
        $this->db->query("SELECT * FROM $table WHERE username = :user_name");
        $this->db->bind(':user_name', $userName);
        
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

        //check row
        if($this->db->rowCount() > 0){
          return true;
        }else {
          return false;
        }
    }
  }

public function findUserByUserName($userName) {
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
  }
}

?>