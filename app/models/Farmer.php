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

    $this->db->query('INSERT INTO farmers (name, password, email, address_id, phone, image, rate) VALUES(:name, :password, :email, :address_id, :phone, :image, :rate)');
    // Bind values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);
    $this->db->bind(':rate', 0);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Update farmer
  public function updateProfile($data)
  {
    $this->db->query('UPDATE farmers SET name = :name, email = :email, phone = :phone, image = :image WHERE id = :id');
    // Bind values
    $this->db->bind(':id', $_SESSION['user_id']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone']);
    $this->db->bind(':image', $data['image']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Update password
  // public function updatePassword($data)
  // {
  //   $this->db->query('UPDATE farmers SET password = :password WHERE id = :id');
  //   // Bind values
  //   $this->db->bind(':id', $_SESSION['user_id']);
  //   $this->db->bind(':password', $data['password']);

  //   // Execute
  //   if ($this->db->execute()) {
  //     return true;
  //   } else {
  //     return false;
  //   }
  // }

  // verify password
  // public function verifyPassword($password)
  // {
  //   $this->db->query('SELECT * FROM farmers WHERE id = :id');
  //   $this->db->bind(':id', $_SESSION['user_id']);

  //   $row = $this->db->single();
  //   $hashed_password = $row->password;

  //   if (password_verify($password, $hashed_password)) {
  //     return true;
  //   } else {
  //     return false;
  //   }
  // }

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

  // Get stock by id
  public function getStockById($id)
  {
    $this->db->query('SELECT * FROM fproducts WHERE fproduct_id = :id');
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }
  
  // Add stock
  public function addStock($data)
  {
    $this->db->query('INSERT INTO fproducts (farmer_id, name, type, description, price, stock, exp_date, image) VALUES(:farmer_id, :name, :type, :description, :price, :stock, :exp_date, :image)');
    // Bind values
    $this->db->bind(':farmer_id', $_SESSION['user_id']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':type', $data['type']);
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

  public function updateStock($id, $data) 
  {
    $this->db->query('UPDATE fproducts SET name = :name, description = :description, price = :price, stock = :stock, exp_date = :exp_date, image = :image WHERE fproduct_id = :id');
    // Bind values
    $this->db->bind(':id', $id);
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

  // Delete stock
  public function deleteStock($id)
  {
    $this->db->query('DELETE FROM fproducts WHERE fproduct_id = :id');
    // Bind values
    $this->db->bind(':id', $id);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // remove expired stocks
  public function removeExpiredStocks()
  {
    // Move expired stocks to exp_products table
    $this->db->query('INSERT INTO exp_products (fproduct_id, farmer_id, name, type, price, stock, exp_date) SELECT fproduct_id, farmer_id, name, type, price, stock, exp_date FROM fproducts WHERE exp_date < CURDATE()');
    if ($this->db->execute()) {
      // Delete expired stocks from fproducts table
      $this->db->query('DELETE FROM fproducts WHERE exp_date < CURDATE()');
      if ($this->db->execute()) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  // Get expired stocks
  public function getExpiredStocks()
  {
    $this->db->query('SELECT * FROM exp_products');

    $results = $this->db->resultSet();

    return $results;
  }
}