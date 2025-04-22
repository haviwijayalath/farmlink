<?php
class Admin extends Database{
  private $db;

  public function __construct() {
    $this->db = new Database;
  }

  public function getProducts() {
    $this->db->query("SELECT fproduct_id, name, price, stock, exp_date FROM fproducts");
    return $this->db->resultSet();
  }

  public function getProductById($id) {
    $this->db->query("
        SELECT 
            f.fproduct_id, 
            f.farmer_id, 
            f.name, 
            f.price, 
            f.stock, 
            f.exp_date, 
            f.image, 
            f.description,
            fr.name AS farmer, 
            fr.email, 
            fr.location,
            fr.phone,
            fr.image AS farmerImage
        FROM fproducts f
        JOIN farmers fr ON f.farmer_id = fr.id
        WHERE f.fproduct_id = :id
    ");
    $this->db->bind(':id', $id);
    
    return $this->db->single(); // Fetch only one product
  }



}