<?php

class Consultant
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

  // Register consultant
  public function register($data)
  {
    // Saving the address before saving the consultant
    $address_id = $this->saveAddress($data['addr_no'], $data['addr_street'], $data['addr_city']);

    $this->db->query('INSERT INTO consultants (name, password, email, address_id, phone, image,specialization,experience) VALUES(:name, :password, :email, :address_id, :phone, :image, :specialization, :experience)');
    // Bind values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone_number']);
    $this->db->bind(':image', $data['image']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':address_id', $address_id);
    $this->db->bind(':specialization', $data['specialization']);
    $this->db->bind(':experience', $data['experience']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
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

  // Login consultant
  public function login($email, $password)
  {
    $this->db->query('SELECT * FROM consultants WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();
    $hashed_password = $row->password;

    if (password_verify($password, $hashed_password)) {
      return $row;
    } else {
      return false;
    }
  }

  // Get consultant by id
  public function getConsultantById($id)
  {
    $this->db->query('SELECT * FROM consultants WHERE id = :id');
    $this->db->bind(':id', $id);

    $row = $this->db->single();

    return $row;
  }

  // Fetch all questions from the questions table
  public function fetchQuestions() {
    $this->db->query('SELECT q_id, farmer_id, description FROM forum_questions');

    // Return the result set as an array of objects
    return $this->db->resultSet();
  }

  // Store an answer in the answers table
   public function storeAnswer($data) {
    $this->db->query('INSERT INTO forum_answers (consultant_id, q_id, description) VALUES (:consultant_id, :q_id, :description)');

    // Bind parameters
    $this->db->bind(':consultant_id', $data['consultant_id']);
    $this->db->bind(':q_id', $data['q_id']);
    $this->db->bind(':description', $data['answer']);

    // Execute and return the result
    return $this->db->execute();
  }
}