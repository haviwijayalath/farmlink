<?php
class Order extends Database{
    private $db;

    public function __construct()
    {
        $this->db = new Database();  // Assuming you have a Database class for DB connection
    }

    public function saveOrderBuyer($data)
    {

        // Insert address into order_buyer_addr
        $this->db->query('INSERT INTO order_buyer_addr (number, street, city) 
                  VALUES (:number, :street, :city)');
        $this->db->bind(':number', $data['number']);
        $this->db->bind(':street', $data['street']);
        $this->db->bind(':city', $data['city']);

        // Execute the address insertion and get the last inserted address ID
        if ($this->db->execute()) {
            $addressId = $this->db->lastInsertId(); // Now safely fetch address ID
        } else {
            return false; // Address insertion failed
        }

        // Insert buyer details into order_buyer
        $this->db->query('INSERT INTO order_buyer (title, buyerId, fname, lname, mobileNo, email, address_id) 
                  VALUES (:title, :buyerid, :first_name, :last_name, :mobile, :email, :address_id)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':buyerid', $data['buyer_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':mobile', $data['mobile']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':address_id', $addressId);

        // Execute the buyer insertion
        if ($this->db->execute()) {
            // Fetch a single product ID and quantity from buyer_cart using cartId
            $this->db->query('SELECT buyer_id, product_id, quantity, price FROM buyer_carts WHERE cart_id = :cartid LIMIT 1');
            $this->db->bind(':cartid', $data['cartId']);
            $cartItem = $this->db->single(); // Fetch one cart item
        
            // Check if a cart item was found
            if ($cartItem) {
                // Insert into order_process table
                $this->db->query('INSERT INTO order_process (cartID, buyerID, productId, quantity, farmerFee) 
                                  VALUES (:cartid, :buyerid, :productid, :quantity, :farmerfee)');
                
                $this->db->bind(':cartid', $data['cartId']);
                $this->db->bind(':buyerid',  $cartItem->buyer_id);
                $this->db->bind(':productid', $cartItem->product_id);
                $this->db->bind(':quantity', $cartItem->quantity);
                $this->db->bind(':farmerfee', $cartItem->price);
        
                if (!$this->db->execute()) {
                    return false;  // Return false if insertion fails
                }
            } else {
                return false; // Return false if no cart item found
            }
        
            return true; // Success
        }
        

    return false;  // If order_buyer insertion fails
}
}