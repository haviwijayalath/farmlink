<?php
class Buyer extends Database{
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function registerBuyer($data) {
    
          // Now, insert the buyer data along with the address ID as a foreign key
          $this->db->query('INSERT INTO buyers (name, password, email, phone) 
                            VALUES (:name, :password, :email, :phone)');
    
          $this->db->bind(':name', $data['name']);
          $this->db->bind(':email', $data['email']);
          $this->db->bind(':phone', $data['phone']);
          $this->db->bind(':password', $data['password']);

          // Execute the delivery person insertion
          return $this->db->execute();
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
  

    public function getUserById($id) {
        // Update query to join with the addresses table
        $this->db->query('
            SELECT dp.id, dp.name, dp.email, dp.phone, dp.area, dp.image, dp.address_id, dp.password,
                   dp.vehicle, dp.regno, dp.capacity, dp.v_image, a.number, a.street, a.city
            FROM delivery_persons dp
            LEFT JOIN address a ON dp.address_id = a.address_id
            WHERE dp.id = :id
        ');

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

public function updateUser($data) {

    $this->db->query('UPDATE buyers SET name = :name, email = :email, phone = :phone , password = :password,');
    
    // Bind values
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone']);
    $this->db->bind(':password', $data['password']);

    $userUpdated = $this->db->execute();

    return $userUpdated ;
}
           
}
