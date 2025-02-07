<?php
class Farmer {
  private $db;

  public function __construct() {
    $this->db = new Database;
  }

  // Save address and return inserted address ID.
  public function saveAddress($no, $street, $city) {
    $this->db->query('INSERT INTO address (number, Street, City) VALUES(:number, :street, :city)');
    $this->db->bind(':number', $no);
    $this->db->bind(':street', $street);
    $this->db->bind(':city', $city);
    if ($this->db->execute()) {
      return $this->db->lastInsertId();
    } else {
      return false;
    }
  }

  // Register farmer.
  public function register($data) {
    $address_id = $this->saveAddress($data['addr_no'], $data['addr_street'], $data['addr_city']);
    $this->db->query('INSERT INTO farmers (name, password, email, address_id, phone, image) VALUES(:name, :password, :email, :address_id, :phone, :image)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);
    return $this->db->execute();
  }

  // Find farmer by email.
  public function findFarmerByEmail($email) {
    $this->db->query('SELECT * FROM farmers WHERE email = :email');
    $this->db->bind(':email', $email);
    $row = $this->db->single();
    return $this->db->rowCount() > 0;
  }

  // Login farmer.
  public function login($email, $password) {
    $this->db->query('SELECT * FROM farmers WHERE email = :email');
    $this->db->bind(':email', $email);
    $row = $this->db->single();
    if ($row && password_verify($password, $row->password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Get farmer by id.
  public function getFarmerById($id) {
    $this->db->query('SELECT * FROM farmers WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  
  // --- Forum Functionality (for questions) ---
  // Store a question asked by a farmer.
  public function storeQuestion($data) {
    // Inserts into the new forum_questions table.
    $this->db->query('INSERT INTO forum_questions (consultant_id, questions, createdAt) VALUES (:consultant_id, :question, NOW())');
    $this->db->bind(':consultant_id', $_SESSION['user_id']);
    $this->db->bind(':question', $data['question']);
    return $this->db->execute();
  }

  public function fetchQuestions() {
    // This query joins the forum_questions table with the farmers table to get the farmer's name.
    $this->db->query(
      "SELECT fq.q_id, fq.questions, fq.createdAt, f.name AS farmer_name
       FROM forum_questions fq
       LEFT JOIN farmers f ON fq.farmer_id = f.id
       LEFT JOIN consultants c ON fq.consultant_id = c.id
       ORDER BY fq.createdAt DESC"
    );
    
    // Return the result set as an array of objects.
    $questions = $this->db->resultSet();
    return $questions;
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
 
   public function updateStock($id, $data) {
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
}