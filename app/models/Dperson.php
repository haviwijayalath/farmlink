<?php
class Dperson extends Database{
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function registerDeliveryPerson($data) {
        // Insert the address data first
        $this->db->query('INSERT INTO address (number, street, city) VALUES (:number, :street, :city)');
        $this->db->bind(':number', $data['addr_no']);
        $this->db->bind(':street', $data['street']);
        $this->db->bind(':city', $data['city']);
    
        // Execute the address insertion and get the last inserted address ID
        if ($this->db->execute()) {
            $addressId = $this->db->lastInsertId(); // Now safely fetch address ID
        } else {
            return false; // Address insertion failed
        }
    
        // Insert the vehicle data
        $this->db->query('INSERT INTO vehicle_info (regno, capacity, type, v_image) VALUES (:regno, :capacity, :vehicle, :v_image)');
        $this->db->bind(':vehicle', $data['vehicle']);
        $this->db->bind(':regno', $data['regno']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':v_image', $data['v_image']);
    
        // Execute the vehicle insertion and get the last inserted vehicle ID
        if ($this->db->execute()) {
            $vehicleId = $this->db->lastInsertId(); // Safely fetch vehicle ID
        } else {
            return false; // Vehicle insertion failed
        }
    
        // Insert the delivery person data
        $this->db->query('INSERT INTO delivery_persons (name, password, email, phone, image, area, address_id, vehicle_id) 
                          VALUES (:name, :password, :email, :phone, :image, :area, :address_id, :vehicle_id)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':area', $data['area']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':address_id', $addressId); // Use the address ID as a foreign key
        $this->db->bind(':vehicle_id', $vehicleId); // Use the vehicle ID as a foreign key
    
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
  

    public function getUserById($id) {
        // Update query to join with the addresses table
        $this->db->query('
            SELECT dp.id, dp.name, dp.email, dp.phone, dp.area, dp.image, dp.address_id, dp.password, dp.vehicle_id,
                   v.type, v.regno, v.capacity, v.v_image, a.number, a.street, a.city
            FROM delivery_persons dp
            LEFT JOIN address a ON dp.address_id = a.address_id
            LEFT JOIN vehicle_info v ON dp.vehicle_id = v.vehicle_id
            WHERE dp.id = :id
        ');

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUser($data) {

     // Update address details in the address table
     $this->db->query('
     UPDATE address 
     SET number = :addr_no, street = :street, city = :city
        WHERE address_id = :address_id
    ');
    $this->db->bind(':addr_no', $data['addr_no']);
    $this->db->bind(':street', $data['street']);
    $this->db->bind(':city', $data['city']);
    $this->db->bind(':address_id', $data['address_id']);
    $addressUpdated = $this->db->execute();

    $this->db->query('UPDATE delivery_persons SET name = :name, email = :email, phone = :phone, address_id = :address_id, 
                            area = :area, password = :password, image = :image WHERE id = :id');
    
    // Bind values
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone']);
    $this->db->bind(':address_id', $data['address_id']);
    $this->db->bind(':area', $data['area']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':image', $data['image']);
    $userUpdated = $this->db->execute();
    
   

    return $userUpdated && $addressUpdated;
}


// Add a new vehicle
    public function addVehicle($data) {
    $this->db->query('UPDATE vehicle_info SET type = :type, regno = :regno, capacity = :capacity, v_image = :v_image WHERE vehicle_id = :id');
    $this->db->bind(':type', $data['type']);
    $this->db->bind(':regno', $data['regno']);
    $this->db->bind(':capacity', $data['capacity']);
    $this->db->bind(':v_image', $data['v_image']);
    $this->db->bind(':id', $data['id']);

    return $this->db->execute();
    }

    // Fetch new orders filtered by delivery area
    public function getNewOrdersByArea($deliveryArea) {
      $this->db->query('
          SELECT 
              farmer_buyer_orders.id,
              farmers.location AS pickup_address,
              farmer_buyer_orders.address AS dropoff_address,
              farmer_buyer_orders.capacity,
              buyers.name as buyer,
              farmer_buyer_orders.amount,
              farmer_buyer_orders.date,
              fproducts.name,
              farmers.name as farmer,
              buyers.phone,
              farmers.phone as fphone

          FROM 
              farmer_buyer_orders
          INNER JOIN 
              farmers ON farmer_buyer_orders.farmer_id = farmers.id
          INNER JOIN 
              address AS farmer_addresses ON farmers.address_id = farmer_addresses.address_id
          INNER JOIN 
              buyers ON farmer_buyer_orders.buyer_id = buyers.id
          INNER JOIN 
                fproducts ON fproducts.fproduct_id = farmer_buyer_orders.fproduct_id
          WHERE 
              farmers.location = :deliveryArea 
              AND farmer_buyer_orders.status = "new"
      ');
      
      $this->db->bind(':deliveryArea', $deliveryArea);
  
      return $this->db->resultSet();
  }
  

    // Update order status to "ongoing" when confirmed
    public function confirmOrder($orderId) {
        $this->db->query('UPDATE farmer_buyer_orders SET status = "ongoing" WHERE id = :orderId');
        $this->db->bind(':orderId', $orderId);

        return $this->db->execute();
    }

    // Fetch ongoing orders filtered by delivery area
    public function getOrdersByArea($deliveryArea) {
        $this->db->query('
            SELECT 
                farmer_buyer_orders.id,
                farmers.location AS pickup_address,
                farmer_buyer_orders.address AS dropoff_address,
                farmer_buyer_orders.capacity,
                buyers.name as buyer,
                farmer_buyer_orders.status
            FROM 
                farmer_buyer_orders
            INNER JOIN 
                farmers ON farmer_buyer_orders.farmer_id = farmers.id
            INNER JOIN 
                address AS farmer_addresses ON farmers.address_id = farmer_addresses.address_id
            INNER JOIN 
                buyers ON farmer_buyer_orders.buyer_id = buyers.id
            WHERE 
                farmers.location = :deliveryArea 
                AND farmer_buyer_orders.status = "ongoing"
        ');
        
        $this->db->bind(':deliveryArea', $deliveryArea);
    
        return $this->db->resultSet();
    }

    public function savePickupImages($orderId, $deliveryId,  $pickupAddr, $pickupImagePath) {
        // Insert image paths into the delivery_info table
        $this->db->query('INSERT INTO delivery_info (order_id, delivery_person_id	,location, pic_before) VALUES (:orderId, :deliveryId, :pickupaddr, :pic_before)');
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':deliveryId', $deliveryId);
        $this->db->bind(':pickupaddr', $pickupAddr);
        $this->db->bind(':pic_before', $pickupImagePath);

        if ($this->db->execute()) {
            // Get the last inserted delivery_id
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function saveDropoffImage($delivery_id, $dropoffImagePath) {
        // Insert image paths into the delivery_info table
        $this->db->query('UPDATE delivery_info SET pic_after = :pic_after WHERE delivery_id = :delivery_id');
        $this->db->bind(':pic_after', $dropoffImagePath);
        $this->db->bind(':delivery_id', $delivery_id);

        // Execute the update for delivery_info
        if ($this->db->execute()) {
            // If the image path update was successful, update the order status
            $this->db->query('UPDATE farmer_buyer_orders SET status = :status WHERE id = (SELECT order_id FROM delivery_info WHERE delivery_id = :delivery_id)');
            $this->db->bind(':status', 'delivered');
            $this->db->bind(':delivery_id', $delivery_id);

            // Execute the update for farmer_buyer_order
            return $this->db->execute();
        }

        // If the first update fails, return false
        return false;
        }

        public function fetchOrderStatus($id){
            $this->db->query('SELECT status FROM farmer_buyer_orders WHERE id = :orderId');
            $this->db->bind(':orderId', $id);
            return $this->db->resultSet();

        }

        public function deleteAccount($userId)
        {
        $sql = "DELETE FROM delivery_persons WHERE id = :userId";
        $this->db->query($sql);
        $this->db->bind(':userId', $userId);

        // Execute the query and return true if successful, false otherwise
        return $this->db->execute();
        }


        public function getorder($deliveryArea, $order_id) {
            $this->db->query('
                SELECT 
                    farmer_buyer_orders.id,
                    farmers.location AS pickup_address,
                    farmer_buyer_orders.address,
                    farmer_buyer_orders.capacity,
                    buyers.name as buyer,
                    farmer_buyer_orders.amount,
                    farmer_buyer_orders.date,
                    fproducts.name,
                    farmers.name as farmer,
                    buyers.phone,
                    farmers.phone as fphone
      
                FROM 
                    farmer_buyer_orders
                INNER JOIN 
                    farmers ON farmer_buyer_orders.farmer_id = farmers.id
                INNER JOIN 
                    address AS farmer_addresses ON farmers.address_id = farmer_addresses.address_id
                INNER JOIN 
                    buyers ON farmer_buyer_orders.buyer_id = buyers.id
                INNER JOIN 
                      fproducts ON fproducts.fproduct_id = farmer_buyer_orders.fproduct_id
                WHERE 
                    farmers.location = :deliveryArea 
                    AND farmer_buyer_orders.status = "new"
                    AND farmer_buyer_orders.id = :order_id
            ');
            
            $this->db->bind(':deliveryArea', $deliveryArea);
            $this->db->bind(':order_id', $order_id);
        
            return $this->db->single();
        }

        // Get order details by ID
        public function getOrderById($orderId) {
            $this->db->query('SELECT 
                orders.id, 
                farmers.name AS farmer, 
                farmers.phone AS fphone, 
                buyers.name AS buyer, 
                buyers.phone, 
                orders.pickup_address, 
                orders.dropoff_address, 
                orders.capacity, 
                orders.amount, 
                orders.delivered_date, 
                orders.pic_before, 
                orders.pic_after, 
                orders.products 
                FROM orders 
                INNER JOIN farmers ON orders.farmer_id = farmers.id 
                INNER JOIN buyers ON orders.buyer_id = buyers.id 
                WHERE orders.id = :order_id
            ');

            $this->db->bind(':order_id', $orderId);
            $result = $this->db->single();
            return $result;
    }

    public function history($id){
        $this->db->query('
            SELECT delivery_info.order_id,
                delivery_info.date,
                delivery_info.amount,
                buyers.name as buyer,
                fproducts.name as productName,
                delivery_info.delivery_id,
                delivery_info.location as dropoffAddr,
                delivery_info.pic_before,
                delivery_info.pic_after,
                farmers.name as farmer,
                farmers.phone as fphone,
                buyers.phone as bphone,
                farmer_buyer_orders.capacity,
                farmers.location as pickupAddr
            FROM
                delivery_info
            INNER JOIN
                farmer_buyer_orders on farmer_buyer_orders.id = delivery_info.order_id
            INNER JOIN 
                buyers on farmer_buyer_orders.buyer_id = buyers.id
            INNER JOIN
                fproducts on farmer_buyer_orders.fproduct_id = fproducts.fproduct_id
            INNER JOIN
                farmers on farmer_buyer_orders.farmer_id = farmers.id
            WHERE
                delivery_info.delivery_person_id = :id
            ');

        $this->db->bind(':id', $id);

        return $this->db->resultSet();

    }

    public function getOrderHistoryById($id){
        $this->db->query('
            SELECT delivery_info.order_id,
                delivery_info.date,
                delivery_info.amount,
                buyers.name as buyer,
                fproducts.name as productName,
                delivery_info.delivery_id,
                delivery_info.location as dropoffAddr,
                delivery_info.pic_before,
                delivery_info.pic_after,
                farmers.name as farmer,
                farmers.phone as fphone,
                buyers.phone as bphone,
                farmer_buyer_orders.capacity,
                farmers.location as pickupAddr
            FROM
                delivery_info
            INNER JOIN
                farmer_buyer_orders on farmer_buyer_orders.id = delivery_info.order_id
            INNER JOIN 
                buyers on farmer_buyer_orders.buyer_id = buyers.id
            INNER JOIN
                fproducts on farmer_buyer_orders.fproduct_id = fproducts.fproduct_id
            INNER JOIN
                farmers on farmer_buyer_orders.farmer_id = farmers.id
            WHERE
                delivery_info.order_id = :id
            ');

        $this->db->bind(':id', $id);

        return $this->db->single(); // This returns a single row as an object

    }
        

        
    
}
