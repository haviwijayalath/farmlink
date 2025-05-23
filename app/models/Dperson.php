<?php
class Dperson extends Database
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function registerDeliveryPerson($data)
    {
        
        $this->db->query('INSERT INTO address (number, street, city) VALUES (:number, :street, :city)');
        $this->db->bind(':number', $data['addr_no']);
        $this->db->bind(':street', $data['street']);
        $this->db->bind(':city', $data['city']);

       
        if ($this->db->execute()) {
            $addressId = $this->db->lastInsertId(); 
        } else {
            return false; 
        }

        
        $this->db->query('INSERT INTO vehicle_info (regno, capacity, type, v_image, structure) VALUES (:regno, :capacity, :vehicle, :v_image, :structure)');
        $this->db->bind(':vehicle', $data['vehicle']);
        $this->db->bind(':regno', $data['regno']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':v_image', $data['v_image']);
        $this->db->bind(':structure', $data['vehicle_strucutre']);

        
        if ($this->db->execute()) {
            $vehicleId = $this->db->lastInsertId(); 
        } else {
            return false; 
        }

        
        $this->db->query('INSERT INTO delivery_persons (name, password, email, phone, image, area, address_id, vehicle_id, license_image, status) 
                          VALUES (:name, :password, :email, :phone, :image, :area, :address_id, :vehicle_id, :limage, :status)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':area', $data['area']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':address_id', $addressId); 
        $this->db->bind(':vehicle_id', $vehicleId); 
        $this->db->bind(':limage', $data['l_image']);
        $this->db->bind(':status', 'pending');

        
        return $this->db->execute();
    }


    
    public function findUserByEmail($email)
    {
       
        $tables = ['farmers', 'buyers', 'consultants', 'suppliers', 'delivery_persons'];

        foreach ($tables as $table) {
            $this->db->query("SELECT * FROM $table WHERE email = :email");
            $this->db->bind(':email', $email);
            $row = $this->db->single();

            
            if ($this->db->rowCount() > 0) {
                return true;  
            }
        }
        return false;  
    }


    public function getUserById($id)
    {
        
        $this->db->query('
            SELECT dp.id, dp.name, dp.email, dp.phone, dp.area, dp.image, dp.address_id, dp.password, dp.vehicle_id,
                   v.type, v.regno, v.capacity, v.v_image, a.number, a.street, a.city, v.structure
            FROM delivery_persons dp
            LEFT JOIN address a ON dp.address_id = a.address_id
            LEFT JOIN vehicle_info v ON dp.vehicle_id = v.vehicle_id
            WHERE dp.id = :id
        ');

        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUser($data)
    {

        
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


    public function addVehicle($data)
    {
        $this->db->query('UPDATE vehicle_info SET type = :type, regno = :regno, capacity = :capacity, v_image = :v_image WHERE vehicle_id = :id');
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':regno', $data['regno']);
        $this->db->bind(':capacity', $data['capacity']);
        $this->db->bind(':v_image', $data['v_image']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }



    
    public function getNewOrdersByArea($deliveryArea)
    {
        $this->db->query('
          SELECT 
              order_success.orderID,
              order_success.status,
              CONCAT(address.number, ", ", address.Street, ", ", address.City) AS pickup_address,
               order_success.dropaddress AS dropoff_address,
              order_success.quantity,
              CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
              order_success.deliveryFee AS amount,
              order_success.orderDate,
              fproducts.name,
              farmers.name as farmer,
              order_buyer.mobileNo,
              farmers.phone as fphone

          FROM 
              order_success
          LEFT JOIN 
              order_buyer ON order_buyer.order_buyerID = order_success.buyerID
          LEFT JOIN
              order_buyer_addr ON order_buyer.address_id = order_buyer_addr.address_id
          LEFT JOIN
              fproducts ON order_success.productID = fproducts.fproduct_id
          LEFT JOIN 
              farmers ON farmers.id = fproducts.farmer_id
          LEFT JOIN 
              address ON farmers.address_id = address.address_id
          WHERE 
              address.City = :deliveryArea  
              AND  (order_success.status = "ready" OR order_success.status = "pending")
              AND order_success.dropAddress IS NOT NULL
      ');

        $this->db->bind(':deliveryArea', $deliveryArea);

        return $this->db->resultSet();
    }


    
    public function confirmOrder($userId, $orderId)
    {
        
        $this->db->query('UPDATE order_success SET dperson_id = :userId, status = "ongoing" WHERE orderID = :orderId');
        $this->db->bind(':userId', $userId);
        $this->db->bind(':orderId', $orderId);
        $this->db->execute();

        
        $this->db->query('SELECT os.orderID, os.buyerID, os.product, fp.fproduct_id AS product_id, fp.farmer_id
        FROM 
            order_success os
        JOIN 
            fproducts fp ON os.productID = fp.fproduct_id
        WHERE 
            os.orderID = :orderId');

        $this->db->bind(':orderId', $orderId);
        return $this->db->single(); 
    }

    
    public function getOrdersByArea($deliveryArea)
    {
        $this->db->query('
            SELECT 
                order_success.orderID,
                order_success.status,
                CONCAT(address.number, ", ", address.Street, ", ", address.City) AS pickup_address,
                CONCAT(order_buyer_addr.number, ", ", order_buyer_addr.street, ", ", order_buyer_addr.city) AS dropoff_address,
                order_success.quantity,
                CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
                order_success.deliveryFee AS amount
            FROM 
                order_success
            LEFT JOIN 
                order_buyer ON order_buyer.buyerId = order_success.buyerID
            LEFT JOIN
                order_buyer_addr ON order_buyer.address_id = order_buyer_addr.address_id
            LEFT JOIN
                fproducts ON order_success.productID = fproducts.fproduct_id
            LEFT JOIN 
                farmers ON farmers.id = fproducts.farmer_id
            LEFT JOIN 
                address ON farmers.address_id = address.address_id
            WHERE 
                address.City = :deliveryArea  
                AND (order_success.status = "ongoing" OR order_success.status = "picked_up")
            GROUP BY 
                order_success.orderID
            ORDER BY
                order_success.orderID DESC
        ');

        $this->db->bind(':deliveryArea', $deliveryArea);

        return $this->db->resultSet();
    }

    public function savePickupImages($orderId, $deliveryId,  $pickupAddr, $pickupImagePath)
    {
    
        $this->db->query('INSERT INTO delivery_info (order_id, delivery_person_id	,pickupAddress, pic_before) VALUES (:orderId, :deliveryId, :pickupaddr, :pic_before)');
        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':deliveryId', $deliveryId);
        $this->db->bind(':pickupaddr', $pickupAddr);
        $this->db->bind(':pic_before', $pickupImagePath);

        if ($this->db->execute()) {
            
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateOrderStatus($orderId)
    {
        $this->db->query("UPDATE order_success SET status = :status WHERE orderID = :id");
        $this->db->bind(':status', 'picked_up');
        $this->db->bind(':id', $orderId);

        return $this->db->execute();
    }

    public function getOrderById($orderId)
    {
        $this->db->query("
            SELECT 
                os.*, 
                fp.farmer_id, 
                fp.fproduct_id AS product_id
            FROM 
                order_success os
            JOIN 
                fproducts fp ON os.productID = fp.fproduct_id
            WHERE 
                os.orderID = :id
        ");
        $this->db->bind(':id', $orderId);
        return $this->db->single();
    }

    public function saveDropoffImage($delivery_id, $dropoffImagePath)
    {
       
        $this->db->query('UPDATE delivery_info SET pic_after = :pic_after WHERE delivery_id = :delivery_id');
        $this->db->bind(':pic_after', $dropoffImagePath);
        $this->db->bind(':delivery_id', $delivery_id);


        
        if ($this->db->execute()) {
            
            $this->db->query('UPDATE delivery_info 
                              SET amount = (SELECT deliveryFee FROM order_success 
                                            WHERE orderID = delivery_info.order_id) 
                              WHERE delivery_id = :delivery_id');
            $this->db->bind(':delivery_id', $delivery_id);

            if ($this->db->execute()) {
                
                $this->db->query('UPDATE order_success 
                                  SET status = :status 
                                  WHERE orderID = (SELECT order_id FROM delivery_info WHERE delivery_id = :delivery_id)');
                $this->db->bind(':status', 'delivered');
                $this->db->bind(':delivery_id', $delivery_id);

                
                return $this->db->execute();
            }
        } else {
            die("Database Error: Failed to update pic_after field.");
        }

        
        return false;
    }

    public function getDeliverySummary($deliveryId)
    {
        $this->db->query("SELECT os.orderID, os.buyerID, os.product, fp.fproduct_id AS product_id, fp.farmer_id
        FROM 
            order_success os
        JOIN 
            fproducts fp ON os.productID = fp.fproduct_id
        WHERE 
            os.orderID = :orderId");

        $this->db->bind(':orderId', $deliveryId);

        return $this->db->single();
    }


    public function deleteAccount($userId)
    {
        $sql = "UPDATE delivery_persons SET status = :status WHERE id = :userId";
        $this->db->query($sql);
        $this->db->bind(':userId', $userId);
        $this->db->bind(':status', 'deactivated');

        
        return $this->db->execute();
    }


    public function getorder($deliveryArea, $order_id)
    {
        $this->db->query('
                SELECT 
                    order_success.orderID,
                    order_success.status,
                    CONCAT(address.number, ", ", address.Street, ", ", address.City) AS pickup_address,
                    CONCAT(order_buyer_addr.number, ", ", order_buyer_addr.street, ", ", order_buyer_addr.city) AS dropoff_address,
                    order_success.quantity,
                    CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
                    order_success.deliveryFee AS amount,
                    order_success.orderDate,
                    fproducts.name,
                    farmers.name as farmer,
                    order_buyer.mobileNo,
                    farmers.phone as fphone
      
                FROM 
                    order_success
                INNER JOIN 
                    fproducts ON fproducts.fproduct_id = order_success.productID
                INNER JOIN 
                    farmers ON fproducts.farmer_id = farmers.id
                INNER JOIN 
                    address address ON farmers.address_id = address.address_id
                INNER JOIN 
                    order_buyer ON order_buyer.order_buyerID = order_success.buyerID
                INNER JOIN 
                    order_buyer_addr ON order_buyer_addr.address_id = order_buyer.address_id
                WHERE 
                    farmers.location = :deliveryArea 
                    AND order_success.status = "ready"
                    AND order_success.orderID = :order_id
            ');

        $this->db->bind(':deliveryArea', $deliveryArea);
        $this->db->bind(':order_id', $order_id);

        return $this->db->single();
    }

    public function history($id)
    {
        $this->db->query('
            SELECT DISTINCT delivery_info.order_id,
                delivery_info.date,
                delivery_info.amount,
                CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
                fproducts.name as productName,
                delivery_info.delivery_id,
                delivery_info.pickupAddress,
                delivery_info.pic_before,
                delivery_info.pic_after,
                farmers.name as farmer,
                farmers.phone as fphone,
                order_buyer.mobileNo,
                order_success.quantity,
                CONCAT(order_buyer_addr.number, ", ", order_buyer_addr.street, ", ", order_buyer_addr.city) AS dropoff_address
            FROM
                delivery_info
            INNER JOIN
                order_success on order_success.orderID = delivery_info.order_id
            INNER JOIN 
                order_buyer on order_buyer.buyerId = order_success.buyerID
            INNER JOIN
                fproducts on order_success.productID = fproducts.fproduct_id
            INNER JOIN
                farmers on fproducts.farmer_id = farmers.id
            INNER JOIN 
                order_buyer_addr ON order_buyer_addr.address_id = order_buyer.address_id
            WHERE
                delivery_info.delivery_person_id = :id
                AND
                order_success.status = "delivered"
            GROUP BY
                delivery_info.order_id
            ORDER BY
                delivery_info.date DESC
            ');

        $this->db->bind(':id', $id);

        return $this->db->resultSet();
    }

    public function getOrderHistoryById($id)
    {
        $this->db->query('
            SELECT 
                delivery_info.order_id,
                delivery_info.date,
                delivery_info.amount,
                CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
                fproducts.name as productName,
                delivery_info.delivery_id,
                delivery_info.pickupAddress,
                delivery_info.pic_before,
                delivery_info.pic_after,
                farmers.name as farmer,
                farmers.phone as fphone,
                order_buyer.mobileNo,
                order_success.quantity,
                CONCAT(order_buyer_addr.number, ", ", order_buyer_addr.street, ", ", order_buyer_addr.city) AS dropoff_address
            FROM
                delivery_info
            INNER JOIN
                order_success on order_success.orderID = delivery_info.order_id
            INNER JOIN 
                order_buyer on order_buyer.buyerId = order_success.buyerID
            INNER JOIN
                fproducts on order_success.productID = fproducts.fproduct_id
            INNER JOIN
                farmers on fproducts.farmer_id = farmers.id
            INNER JOIN 
                order_buyer_addr ON order_buyer_addr.address_id = order_buyer.address_id
            WHERE
                delivery_info.order_id = :id
            ');

        $this->db->bind(':id', $id);

        return $this->db->single(); 

    }

    public function getongoingbyID($deliveryArea, $id)
    {
        $this->db->query('
        SELECT 
            order_success.orderID,
            order_success.status,
            CONCAT(address.number, ", ", address.Street, ", ", address.City) AS pickup_address,
            CONCAT(order_buyer_addr.number, ", ", order_buyer_addr.street, ", ", order_buyer_addr.city) AS dropoff_address,
            order_success.quantity,
            CONCAT(order_buyer.fname, " ", order_buyer.lname) AS buyer,
            order_success.deliveryFee AS amount,
            order_success.orderDate,
            fproducts.name,
            farmers.name AS farmer,
            order_buyer.mobileNo,
            farmers.phone AS fphone
        FROM 
            order_success
        INNER JOIN 
            order_buyer ON order_buyer.order_buyerID = order_success.buyerID
        INNER JOIN
            order_buyer_addr ON order_buyer.address_id = order_buyer_addr.address_id
        INNER JOIN
            fproducts ON order_success.productID = fproducts.fproduct_id
        INNER JOIN 
            farmers ON farmers.id = fproducts.farmer_id
        INNER JOIN 
            address ON farmers.address_id = address.address_id
        WHERE 
            address.City = :deliveryArea  
            AND order_success.status = "ongoing"
            AND order_success.orderID = :order_id
    ');

        $this->db->bind(':deliveryArea', $deliveryArea);
        $this->db->bind(':order_id', $id);

        return $this->db->single();
    }


    public function getDeliveryEarnings($orderId, $deliveryPersonId)
    {
        $this->db->query('SELECT amount, (SELECT SUM(amount) FROM delivery_info WHERE delivery_person_id = :deliveryPersonId) as totearnings 
            FROM delivery_info WHERE order_id = :orderId AND delivery_person_id = :deliveryPersonId LIMIT 1');

        $this->db->bind(':orderId', $orderId);
        $this->db->bind(':deliveryPersonId', $deliveryPersonId);

        return $this->db->single();
    }

   
}
