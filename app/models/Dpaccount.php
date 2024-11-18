<?php
class Dpaccount extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database; // Assuming there's a Database class for handling PDO connections
    }

    
}
