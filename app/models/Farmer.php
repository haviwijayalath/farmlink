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
    // Assuming the logged-in farmer's ID is in $_SESSION['user_id']
    $this->db->query("
      INSERT INTO forum_questions (farmer_id, questions, createdAt)
      VALUES (:farmer_id, :questions, NOW())
    ");
    $this->db->bind(':farmer_id', $_SESSION['user_id']);
    $this->db->bind(':questions', $data['question']);
    return $this->db->execute();
  }

  public function fetchQuestions() {
    $this->db->query("
      SELECT 
        fq.q_id AS id, 
        fq.questions AS question, 
        fq.createdAt, 
        fq.farmer_id,
        f.name AS farmer_name
      FROM forum_questions fq
      LEFT JOIN farmers f ON fq.farmer_id = f.id
      ORDER BY fq.createdAt DESC
    ");
    return $this->db->resultSet();
  }

  // Fetch a single question by ID
  public function getQuestionById($q_id) {
    $this->db->query("SELECT q_id AS id, farmer_id, questions AS question, createdAt FROM forum_questions WHERE q_id = :q_id");
    $this->db->bind(':q_id', $q_id);
    return $this->db->single();
}


// Update a question (only updates the question text)
public function updateQuestion($q_id, $data) {
  $this->db->query("UPDATE forum_questions SET questions = :question WHERE q_id = :q_id");
  $this->db->bind(':question', $data['question']);
  $this->db->bind(':q_id', $q_id);
  return $this->db->execute();
}

// Delete a question
public function deleteQuestion($q_id) {
  $this->db->query("DELETE FROM forum_questions WHERE q_id = :q_id");
  $this->db->bind(':q_id', $q_id);
  return $this->db->execute();
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