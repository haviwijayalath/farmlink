<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Fetch new orders filtered by delivery area
    public function getNewOrdersByArea($deliveryArea) {
      $this->db->query('
          SELECT 
              farmer_buyer_orders.id,
              farmers.location AS pickup_address,
              buyer_addresses.city AS dropoff_address,
              farmer_buyer_orders.capacity,
              buyers.name as buyer
          FROM 
              farmer_buyer_orders
          INNER JOIN 
              farmers ON farmer_buyer_orders.farmer_id = farmers.id
          INNER JOIN 
              address AS farmer_addresses ON farmers.address_id = farmer_addresses.address_id
          INNER JOIN 
              buyers ON farmer_buyer_orders.buyer_id = buyers.id
          INNER JOIN 
              address AS buyer_addresses ON buyers.address_id = buyer_addresses.address_id
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
                buyer_addresses.city AS dropoff_address,
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
            INNER JOIN 
                address AS buyer_addresses ON buyers.address_id = buyer_addresses.address_id
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

    public function saveDropoffImages($delivery_id, $dropoffImagePath) {
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

        public function history($id){
            $this->db->query('
                SELECT delivery_info.order_id,
                    delivery_info.date,
                    delivery_info.amount,
                    buyers.name,
                    fproducts.name as productName
                FROM
                    delivery_info
                INNER JOIN
                    farmer_buyer_orders on farmer_buyer_orders.id = delivery_info.order_id
                INNER JOIN 
                    buyers on farmer_buyer_orders.buyer_id = buyers.id
                INNER JOIN
                    fproducts on farmer_buyer_orders.fproduct_id = fproducts.fproduct_id
                WHERE
                    delivery_info.delivery_person_id = :id
                ');

            $this->db->bind(':id', $id);
    
            return $this->db->resultSet();

        }

        public function fetchOrderStatus($id){
            $this->db->query('SELECT status FROM farmer_buyer_orders WHERE id = :orderId');
            $this->db->bind(':orderId', $id);
            return $this->db->resultSet();

        }

        
    
}
