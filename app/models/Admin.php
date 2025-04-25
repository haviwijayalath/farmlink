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

  public function getUsers()
    {
        $tables = [
            'farmers' => 'Farmer',
            'delivery_persons' => 'Delivery_Person',
        ];

        $users = [];

        foreach ($tables as $table => $roleName) {
            $this->db->query("SELECT id, name FROM $table");
            $result = $this->db->resultSet();

            foreach ($result as $user) {
                $user->role = $roleName;
                $user->table = $table;
                $users[] = $user;
            }
        }

        return $users;
    }

    public function getFilteredReports($role)
    {
        $allowedRoles = ['farmers', 'delivery_persons'];
        $roleNames = [
            'farmers' => 'Farmer',
            'delivery_persons' => 'Delivery_Person',
        ];

        $results = [];

        if (!empty($role) && in_array($role, $allowedRoles)) {
            // Filter by role (table), and maybe also by status
            if (!empty($role)) {
                $query = "SELECT id, name, email, phone, status FROM $role ";
                $this->db->query($query);
            } else {
                $query = "SELECT id, name, email, phone, status FROM $role";
                $this->db->query($query);
            }

            $result = $this->db->resultSet();
            foreach ($result as $user) {
                $user->role = $roleNames[$role];
                $user->table = $role;
                $results[] = $user;
            }
        } else {
            // No specific role: check all tables for status (or fetch all if no status)
            foreach ($allowedRoles as $table) {
                if (!empty($role)) {
                    $query = "SELECT id, name, email, phone, status FROM $table ";
                    $this->db->query($query);
                } else {
                    $query = "SELECT id, name, email, phone, status FROM $table";
                    $this->db->query($query);
                }

                $res = $this->db->resultSet();
                foreach ($res as $user) {
                    $user->role = $roleNames[$table];
                    $user->table = $table;
                    $results[] = $user;
                }
            }
        }

        return $results;
    }


}