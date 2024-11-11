<?php
class Account extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database; // Assuming there's a Database class for handling PDO connections
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM delivery_persons WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUser($data) {
        $this->db->query('UPDATE delivery_persons SET name = :name, email = :email, phone = :phone, address = :address, area = :area, password = :password, image = :image WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':area', $data['area']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':image', $data['image']);

        // Execute
        return $this->db->execute();
    }
}
