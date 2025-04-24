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
            $data['farmer_fee'] = $cartItem->price;
        
            // Check if a cart item was found
            if ($cartItem) {
                // Insert into order_process table
                $this->db->query('INSERT INTO order_process (cartID, buyerID, productID, quantity, deliveryFee, farmerFee, dropAddress)
                                  VALUES (:cartid, :buyerid, :productid, :quantity, :fee, :farmerfee, :drop_addr)');
                
                $this->db->bind(':cartid', $data['cartId']);
                $this->db->bind(':buyerid',  $cartItem->buyer_id);
                $this->db->bind(':productid', $cartItem->product_id);
                $this->db->bind(':quantity', $cartItem->quantity);
                $this->db->bind(':farmerfee', $cartItem->price);
                $this->db->bind(':fee', $data['delivery_fee']);
                $this->db->bind(':drop_addr', $data['drop_addr']);
        
                if (!$this->db->execute()) {
                    return false;  // Return false if insertion fails
                }
            } else {
                return false; // Return false if no cart item found
            }
        
            return $data; // Success
        }
        

        return false;  // If order_buyer insertion fails
    }

public function getFarmerPickupAddressByProduct($productId) {
    $this->db->query("SELECT a.number, a.street, a.city 
                      FROM address a
                      INNER JOIN farmers f ON a.address_id = f.address_id
                      INNER JOIN fproducts p ON p.farmer_id = f.id
                      WHERE p.fproduct_id = :product_id");

    $this->db->bind(':product_id', $productId);

    return $this->db->single(); // fetch one result
    }

public function submitComplaint($userId, $role, $orderId, $description) {
    $time = date('Y-m-d H:i:s');
    $this->db->query("INSERT INTO complaints (order_id, description, user_id, role, date_submitted, status) 
                  VALUES (:order_id, :description, :user_id, :role, :date_submitted, 'new')");

    $this->db->bind(':user_id', $userId);
    $this->db->bind(':role', $role);
    $this->db->bind(':order_id', $orderId);
    $this->db->bind(':description', $description);
    $this->db->bind(':date_submitted', $time);


    return $this->db->execute();
    }


public function getComplaints($userId, $role) {
    $this->db->query("SELECT * FROM complaints 
                      WHERE user_id = :user_id AND role = :role
                      ORDER BY date_submitted DESC");

    $this->db->bind(':user_id', $userId);
    $this->db->bind(':role', $role);

    return $this->db->resultSet(); // fetch all results
    }

    public function getOrderDetailsWithFarmer($orderID) {
        $this->db->query("
            SELECT os.orderID, os.buyerID, fp.farmer_id
            FROM order_success os
            INNER JOIN fproducts fp ON os.productID = fp.fproduct_id
            WHERE os.orderID = :orderID
        ");
        $this->db->bind(':orderID', $orderID);
        return $this->db->single(); // returns associative array/object
    }
    
    public function addReview($data) {
        $this->db->query("
            INSERT INTO farmer_reviews (order_id, buyer_id, farmer_id, description, rating, image, created_at)
            VALUES (:orderID, :buyerID, :farmerID, :description, :rating, :images, NOW())
        ");
        $this->db->bind(':orderID', $data['orderID']);
        $this->db->bind(':buyerID', $data['buyerID']);
        $this->db->bind(':farmerID', $data['farmerID']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':images', $data['images']); // comma-separated or JSON if multiple
    
        return $this->db->execute();
    }

    public function getBuyerAddress($buyerId) {
        $this->db->query('
           SELECT 
            b.name,b.email,b.phone,a.number,a.Street,a.City
            FROM 
                buyers b
            JOIN 
                address a
            ON 
                b.address_id = a.address_id
            WHERE 
                b.id = :buyerId; 
        ');

        $this->db->bind(':buyerId', $buyerId);

        return $this->db->single(); // Fetch a single result
    }
    

}