<?php
class Account extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database; // Assuming there's a Database class for handling PDO connections
    }

    public function getUserById($id) {
            // Update query to join with the addresses table
            $this->db->query('
                SELECT dp.id, dp.name, dp.email, dp.phone, dp.area, dp.image, dp.address_id, dp.password,
                       a.number, a.street, a.city
                FROM delivery_persons dp
                LEFT JOIN address a ON dp.address_id = a.address_id
                WHERE dp.id = :id
            ');
    
            $this->db->bind(':id', $id);
            return $this->db->single();
        }

    public function updateUser($data) {

         // Update address details in the address table
         $this->db->query('
         UPDATE address 
         SET number = :addr_no, street = :street, city = :city
            WHERE address_id = :address_id
        ');
        $this->db->bind(':addr_no', $data['addr_no']);
        $this->db->bind(':street', $data['street']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':address_id', $data['address_id']);
        $addressUpdated = $this->db->execute();

        $this->db->query('UPDATE delivery_persons SET name = :name, email = :email, phone = :phone, address_id = :address_id, 
                                area = :area, password = :password, image = :image WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address_id', $data['address_id']);
        $this->db->bind(':area', $data['area']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':image', $data['image']);
        $userUpdated = $this->db->execute();
        
       

        return $userUpdated && $addressUpdated;
    }
}
