<?php
class Dpaccount extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database; // Assuming there's a Database class for handling PDO connections
    }

    public function getMonthlyEarnings($deliveryPersonId, $monthOffset = 0) {
        // Calculate start and end of the target month in SQL
        $this->db->query("
            SELECT SUM(di.amount) as total
            FROM delivery_info di
            INNER JOIN order_success os ON di.order_id = os.orderID
            WHERE di.delivery_person_id = :id
              AND di.date >= DATE_FORMAT(CURRENT_DATE - INTERVAL :offset MONTH, '%Y-%m-01')
              AND di.date < DATE_FORMAT(CURRENT_DATE - INTERVAL :offset - 1 MONTH, '%Y-%m-01')
        ");
        $this->db->bind(':id', $deliveryPersonId);
        $this->db->bind(':offset', $monthOffset);
        return $this->db->single()->total ?? 0;
    }
    

    public function getYearlyEarnings($deliveryPersonId) {
        $this->db->query("
            SELECT SUM(di.amount) as total
            FROM delivery_info di
            INNER JOIN order_success os ON di.order_id = os.orderID
            WHERE di.delivery_person_id = :id
              AND YEAR(os.orderDate) = YEAR(CURRENT_DATE)
        ");
        $this->db->bind(':id', $deliveryPersonId);
        return $this->db->single()->total ?? 0;
    }

    public function getMonthlyTrend($deliveryPersonId) {
        $this->db->query("
            SELECT 
                MONTH(os.orderDate) as month,
                SUM(di.amount) as total
            FROM delivery_info di
            INNER JOIN order_success os ON di.order_id = os.orderID
            WHERE di.delivery_person_id = :id
              AND YEAR(os.orderDate) = YEAR(CURRENT_DATE)
            GROUP BY MONTH(os.orderDate)
        ");
        $this->db->bind(':id', $deliveryPersonId);
        return $this->db->resultSet();
    }

    public function getFilteredEarnings($deliveryPersonId, $orderId = '', $date = '') {
        // Base SQL
        $sql = "
            SELECT 
            di.order_id, 
            di.amount, 
            os.status, 
            di.date
        FROM delivery_info di
        INNER JOIN order_success os ON di.order_id = os.orderID
        WHERE di.delivery_person_id = :dpid
        ";
    
        // Add filters if provided
        if ($orderId) {
            $sql .= " AND di.order_id LIKE :orderId";
        }
    
        if ($date) {
            $sql .= " AND DATE(di.date) = :deliveredDate";
        }
    
        // Prepare and bind
        $this->db->query($sql);
        $this->db->bind(':dpid', $deliveryPersonId);
    
        if ($orderId) {
            $this->db->bind(':orderId', "%$orderId%");
        }
    
        if ($date) {
            $this->db->bind(':deliveredDate', $date);
        }
    
        return $this->db->resultSet();
    }
    
    
}
