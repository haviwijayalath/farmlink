<?php
class User extends Database{
  private $db;

  public function __construct(){
    $this->db = new Database;
  }

  /*//Register farmer
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
  }*/

  public function registerDeliveryPerson($data) {

    // Insert the address data first
    $this->db->query('INSERT INTO address (number, street, city) VALUES (:number, :street, :city)');
    $this->db->bind(':number', $data['addr_no']);
    $this->db->bind(':street', $data['street']);
    $this->db->bind(':city', $data['city']);
   
    // Execute the address insertion
    if ($this->db->execute()) {
      // Get the last inserted address ID
      $addressId = $this->db->lastInsertId();

      // Now, insert the delivery person data along with the address ID as a foreign key
      $this->db->query('INSERT INTO delivery_persons (name, password, email, phone, image, vehicle, area, regno, capacity, v_image, address_id) 
                        VALUES (:name, :password, :email, :phone, :image, :vehicle, :area, :regno, :capacity, :v_image, :address_id)');

      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':phone', $data['phone']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':vehicle', $data['vehicle']);
      $this->db->bind(':area', $data['area']);
      $this->db->bind(':regno', $data['regno']);
      $this->db->bind(':capacity', $data['capacity']);
      $this->db->bind(':v_image', $data['v_image']);
      $this->db->bind(':password', $data['password']);
      $this->db->bind(':address_id', $addressId); // Use the address ID as a foreign key

      // Execute the delivery person insertion
      return $this->db->execute();
    } else {
        return false; // Address insertion failed
    }
  }

  /*public function registerSupplier($data) {
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
  }*/


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