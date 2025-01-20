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
  
    public function getUserById($id){
        $this->db->query('select * from buyers where id = :id');

        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function updateUser($data) {

        $this->db->query('UPDATE buyers SET name = :name, email = :email, password = :password, phone = :phone where id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', $data['new_password']);

        $userUpdated = $this->db->execute();

        return $userUpdated ;
    }

    public function deleteAccount($userID){
        $this->db->query('
            delete from buyers where id = :userID
        ');
        $this->db->bind(':userID',$userID);

        // Execute the query and return true if successful, false otherwise
        return $this->db->execute();
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

    public function getWishlistItem(){
        $this->db->query('
            SELECT w.wishlist_id, f.name, f.exp_date, f.price, f.fproduct_id
            FROM wishlist w 
            JOIN fproducts f ON w.product_id = f.fproduct_id
            WHERE w.buyer_id = :buyer_id
        ');

        $this->db->bind(':buyer_id', $_SESSION['user_id']);
        return $this->db->resultSet();
    }

    public function addWishlistItem($data){
        $this->db->query('
            INSERT INTO wishlist (buyer_id,product_id)
            VALUES (:buyer_id, :product_id)
        ');

        $this->db->bind(':buyer_id',$data['buyer_id']);
        $this->db->bind(':product_id',$data['product_id']);

        print_r($data);

        return $this->db->execute();
    }

    public function removeWishlistItem($id){
        $this->db->query('
            DELETE FROM wishlist WHERE wishlist_id = :id
        ');
        
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
           
}
