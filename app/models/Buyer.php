<?php
class Buyer extends Database{
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function registerBuyer($data) {
    
          // Now, insert the buyer data along with the address ID as a foreign key
          $this->db->query('INSERT INTO buyers (name, password, email, phone) 
                            VALUES (:name, :password, :email, :phone)');
    
          $this->db->bind(':name', $data['name']);
          $this->db->bind(':email', $data['email']);
          $this->db->bind(':phone', $data['phone']);
          $this->db->bind(':password', $data['password']);

          // Execute the delivery person insertion
          return $this->db->execute();
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
    

        // public function getUserById($id) {
        //     // Update query to join with the addresses table
        //     $this->db->query('
        //         SELECT dp.id, dp.name, dp.email, dp.phone, dp.area, dp.image, dp.address_id, dp.password,
        //                dp.vehicle, dp.regno, dp.capacity, dp.v_image, a.number, a.street, a.city
        //         FROM delivery_persons dp
        //         LEFT JOIN address a ON dp.address_id = a.address_id
        //         WHERE dp.id = :id
        //     ');

        //     $this->db->bind(':id', $id);
        //     return $this->db->single();
        // }

    public function updateUser($data) {

        $this->db->query('UPDATE buyers SET name = :name, email = :email, phone = :phone , password = :password,');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', $data['password']);

        $userUpdated = $this->db->execute();

        return $userUpdated ;
    }

    public function getCartItems(){
        $this->db->query('
            SELECT c.cart_id, c.quantity, p.name,  p.price 
            FROM buyer_carts c
            JOIN fproducts p ON c.product_id = p.fproduct_id
            WHERE c.buyer_id = :buyer_id
        ');

        $this->db->bind(':buyer_id', $_SESSION['user_id']);
        return $this->db->resultSet();
    }

    public function addCartItem($data){
        $this->db->query('
            INSERT INTO buyer_carts (buyer_id,product_id,quantity) 
            VALUES (:buyer_id,:product_id,:quantity)
        '); 
        
        $this->db->bind(':buyer_id',$data['buyer_id']);
        $this->db->bind(':product_id',$data['product_id']);
        $this->db->bind(':quantity',$data['quantity']);

        print_r($data);

        return $this->db->execute();
    }

    public function updateCartItem($data){
        $this->db->query('
            UPDATE buyer_carts 
            SET quantity = :quantity 
            WHERE cart_id = :cart_id
        ');
        $this->db->bind(':cart_id', $data['cart_id']);
        $this->db->bind(':quantity', $data['quantity']);
        return $this->db->execute();
    }

    public function removeCartItem($id){
        $this->db->query('
            DELETE FROM buyer_carts WHERE cart_id = :id
        ');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function getProducts() {
        $this->db->query('SELECT * FROM fproducts');

        $results = $this->db->resultSet();

        return $results;
    }

    public function getProductById($id)
    {
        $this->db->query('SELECT p.name as productName, p.description, p.price, p.stock, p.image as productImage, p.exp_date, f.id as farmerId, f.name as farmerName, f.image as farmerImage, f.email FROM fproducts p JOIN farmers f ON f.id = p.farmer_id WHERE fproduct_id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }
           
}
