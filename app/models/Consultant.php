<?php
class Consultant {
  private $db;

  public function __construct() {
    $this->db = new Database;
  }

  // Save address.
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

  // Register consultant.
  public function register($data) {
    $address_id = $this->saveAddress($data['addr_no'], $data['addr_street'], $data['addr_city']);
    $this->db->query('INSERT INTO consultants (name, password, email, address_id, phone, image, specialization, experience) VALUES(:name, :password, :email, :address_id, :phone, :image, :specialization, :experience)');
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);
    $this->db->bind(':specialization', $data['specialization']);
    $this->db->bind(':experience', $data['experience']);
    return $this->db->execute();
  }

  // Find user by email across several tables.
  public function findUserByEmail($email) {
    $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons'];
    foreach ($tables as $table) {
      $this->db->query("SELECT * FROM $table WHERE email = :email");
      $this->db->bind(':email', $email);
      $row = $this->db->single();
      if ($this->db->rowCount() > 0) {
        return true;
      }
    }
    return false;
  }

  // Login consultant.
  public function login($email, $password) {
    $this->db->query('SELECT * FROM consultants WHERE email = :email');
    $this->db->bind(':email', $email);
    $row = $this->db->single();
    if ($row && password_verify($password, $row->password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Get consultant by id.
  public function getConsultantById($id) {
    $this->db->query('SELECT * FROM consultants WHERE id = :id');
    $this->db->bind(':id', $id);
    return $this->db->single();
  }

  // --- Forum Functionality (for answers) ---
  // Store an answer provided by a consultant.
  public function storeAnswer($data) {
    $this->db->query('INSERT INTO forum_answers (question_id, consultant_id, answer, created_at) VALUES (:question_id, :consultant_id, :answer, NOW())');
    $this->db->bind(':question_id', $data['question_id']);
    $this->db->bind(':consultant_id', $_SESSION['user_id']);
    $this->db->bind(':answer', $data['answer']);
    return $this->db->execute();
  }

  // Fetch all answers for a given question.
  public function fetchAnswers($question_id) {
    $this->db->query('SELECT * FROM forum_answers WHERE question_id = :question_id ORDER BY created_at ASC');
    $this->db->bind(':question_id', $question_id);
    return $this->db->resultSet();
  }
}
