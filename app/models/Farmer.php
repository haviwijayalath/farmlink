<?php

class Farmer
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  // Saving the address
  public function saveAddress($no, $street, $city)
  {
    $this->db->query('INSERT INTO address (number, Street, City) VALUES(:number, :street, :city)');
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
  public function register($data)
  {
    // Saving the address before saving the farmer
    $address_id = $this->saveAddress($data['addr_no'], $data['addr_street'], $data['addr_city']);

    $this->db->query('INSERT INTO farmers (name, password, email, address_id, phone, image) VALUES(:name, :password, :email, :address_id, :phone, :image)');
    // Bind values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Find farmer by email
  public function findFarmerByEmail($email)
  {
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
  public function login($email, $password)
  {
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
  public function getFarmerById($id)
  {
    $this->db->query('SELECT * FROM farmers WHERE id = :id');
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }

  // list stocks
  public function getStocks()
  {
    $this->db->query('SELECT * FROM fproducts');

    $results = $this->db->resultSet();

    return $results;
  }

  // Add stock
  public function addStock($data)
  {
    $this->db->query('INSERT INTO fproducts (farmer_id, name, description, price, stock, exp_date, image) VALUES(:farmer_id, :name, :description, :price, :stock, :exp_date, :image)');
    // Bind values
    $this->db->bind(':farmer_id', $_SESSION['user_id']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':description', $data['description']);
    $this->db->bind(':price', $data['price']);
    $this->db->bind(':stock', $data['stock']);
    $this->db->bind(':exp_date', $data['exp_date']);
    $this->db->bind(':image', $data['image']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
