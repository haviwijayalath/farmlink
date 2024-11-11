<?php

  class Farmer {
    private $db;

    public function __construct() {
      $this->db = new Database;
    }

    // Saving the address
    public function saveAddress($no, $street, $city) {
      $this->db->query('INSERT INTO addresses (number, street, city) VALUES(:number, :street, :city)');
      // Bind values
      $this->db->bind(':number', $no);
      $this->db->bind(':street', $street);
      $this->db->bind(':city', $city);

      // Execute
      if ($this->db->execute()) {
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    // Register farmer
    public function register($data) {
      // Saving the address before saving the farmer
      $address_id = saveAddress($data);

      $this->db->query('INSERT INTO farmers (name, email, phone_number, password) VALUES(:name, :email, :phone_number, :image, :password)');
      // Bind values
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':phone_number', $data['phone_number']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':password', $data['password']);

      // Execute
      if ($this->db->execute()) {
        return true;
      } else {
        return false;
      }
    }

    // Find farmer by email
    public function findFarmerByEmail($email) {
      $this->db->query('SELECT * FROM farmers WHERE email = :email');
      // Bind value
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      // Check row
      if ($this->db->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    }

    // Login farmer
    public function login($email, $password) {
      $this->db->query('SELECT * FROM farmers WHERE email = :email');
      $this->db->bind(':email', $email);

      $row = $this->db->single();
      $hashed_password = $row->password;

      if (password_verify($password, $hashed_password)) {
        return $row;
      } else {
        return false;
      }
    }

    // Get farmer by id
    public function getFarmerById($id) {
      $this->db->query('SELECT * FROM farmers WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }
  }