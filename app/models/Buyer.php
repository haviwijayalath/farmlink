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
            SELECT c.cart_id, c.quantity, c.price, p.name,  p.price, c.product_id 
            FROM buyer_carts c
            JOIN fproducts p ON c.product_id = p.fproduct_id
            WHERE c.buyer_id = :buyer_id
            order by c.cart_id desc
            limit 1
        ');

        $this->db->bind(':buyer_id', $_SESSION['user_id']);
        return $this->db->resultSet();
    }

    public function addCartItem($data){
 
        $this->db->query('select price from fproducts where fproduct_id = :product_id');
        $this->db->bind(':product_id',$data['product_id']);

        $product = $this->db->single();

        if (!$product) {
            return false; // Product not found
        }

        // cheack buyer already has an item in the cart
        $this->db->query('
            select cart_id from buyer_carts where buyer_id = :buyer_id
        ');

        $this->db->bind(':buyer_id' , $data['buyer_id']);
        $existingCartItem = $this->db->single();

        if($existingCartItem){
            // If an item exists, update it instead of adding a new one
            $this->db->query('
            UPDATE buyer_carts 
            SET product_id = :product_id, quantity = :quantity, price = :price 
            WHERE buyer_id = :buyer_id
            ');

            $this->db->bind(':product_id', $data['product_id']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':buyer_id', $data['buyer_id']);
            $this->db->bind(':price', $product->price);

        } else {
            // If no item exists, insert a new one
        $this->db->query('
            INSERT INTO buyer_carts (buyer_id, product_id, quantity, price) 
            VALUES (:buyer_id, :product_id, :quantity, :price)
            ');

            $this->db->bind(':buyer_id', $data['buyer_id']);
            $this->db->bind(':product_id', $data['product_id']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':price',$product->price);
        }
        
        print_r($data);

        return $this->db->execute();
    }

    public function updateCartItem($data){

        // fetch the current unit price
        $this->db->query('
            select price from fproducts where fproduct_id = (select product_id from buyer_carts where cart_id = :cart_id)
        ');
        $this->db->bind(':cart_id',$data['cart_id']);
        $product = $this->db->single();

        if (!$product) {
            return false; // Product not found
        }

        // recalculate the price
        $newPrice = $product->price * $data['quantity'];

        $this->db->query('
            UPDATE buyer_carts 
            SET quantity = :quantity ,price = :price
            WHERE cart_id = :cart_id
        ');

        $this->db->bind(':cart_id', $data['cart_id']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':price', $newPrice);
        return $this->db->execute();
    }

    public function removeCartItem($id){
        $this->db->query('
            DELETE FROM buyer_carts WHERE cart_id = :id
        ');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function getProducts($filter_vars) {
        
        $query = 'SELECT * FROM fproducts';
        $orderBy = [];

        if (!empty($filter_vars)) {
            // Handle the category condition
            if (!empty($filter_vars['category'])) {
                $whereCondition = "type = '" . $filter_vars['category'] . "'";
            }

            // Handle ORDER BY clauses
            if (!empty($filter_vars['price'])) {
                $orderBy[] = 'price ' . ($filter_vars['price'] === 'ASC' ? 'ASC' : 'DESC');
            }
            
            if (!empty($filter_vars['stock'])) {
                $orderBy[] = 'stock ' . ($filter_vars['stock'] === 'ASC' ? 'ASC' : 'DESC');
            }
            
            if (!empty($filter_vars['exp_date'])) {
                $orderBy[] = 'exp_date ' . ($filter_vars['exp_date'] === 'ASC' ? 'ASC' : 'DESC');
            }

            // Add WHERE clause if conditions exist
            if (!empty($whereCondition)) {
                $query .= ' WHERE ' . $whereCondition;
            }

            // Add ORDER BY clause if ordering exists
            if (!empty($orderBy)) {
                $query .= ' ORDER BY ' . implode(', ', $orderBy);
            }
        }

        $this->db->query($query);

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

    public function getSuccessOrderDetails(){
        $buyer_id = $_SESSION['user_id'];

        $this->db->query('
            select product,quantity,dropAddress,orderDate,status from order_success
            where buyerID = :id
        ');

        $this->db->bind(':id', $buyer_id);

        // Fetch all results
        $results = $this->db->resultSet();

        // Return the results (empty array if no orders exist)
        return $results ?: [];
    }
           
}
