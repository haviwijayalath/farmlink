<?php
class Complaint extends Database {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getComplaints() {
      $this->db->query('
      SELECT 
      c.id AS complaint_id,
      c.user_id,
      c.role,
      c.status,
      c.order_id,
      c.description,
      c.date_submitted,
  
      d.delivery_person_id,
      d.pickupAddress,
      d.pic_before,
      d.pic_after,
      d.date AS delivered_date,
  
      os.product,
      os.quantity,
      os.dropAddress,
      os.orderDate,
  
      fp.farmer_id,
  
      f.name,
      f.email,
  
      b.name AS buyer_name,
      b.email AS buyer_email,
      b.phone AS buyer_phone,
  
      dp.name AS delivery_name,
      dp.email AS delivery_email,
      dp.phone AS delivery_phone
  
      FROM complaints c
      INNER JOIN delivery_info d ON c.order_id = d.order_id
      INNER JOIN order_success os ON c.order_id = os.orderID
      INNER JOIN fproducts fp ON os.productID = fp.fproduct_id
      INNER JOIN farmers f ON fp.farmer_id = f.id
    
      LEFT JOIN buyers b 
        ON c.role = :buyer AND c.user_id = b.id
    
      LEFT JOIN delivery_persons dp 
        ON c.role = :dperson AND c.user_id = dp.id
    
    
          ORDER BY c.date_submitted DESC
      ');

    $this->db->bind(':dperson', 'dperson');
    $this->db->bind(':buyer', 'buyer');
  
      return $this->db->resultSet();

  }

  public function resolveComplaint($complaint_id, $faultBy, $notes) {
    $this->db->query("UPDATE complaints 
                      SET status = :status, fault_by = :fault_by, admin_notes = :notes 
                      WHERE id = :id");

    $this->db->bind(':status', 'resolved');
    $this->db->bind(':fault_by', $faultBy);
    $this->db->bind(':notes', $notes);
    $this->db->bind(':id', $complaint_id);

    return $this->db->execute();
}

public function deactivateFarmer($farmer_id) {
    $this->db->query("UPDATE farmers SET status = 'deactivated' WHERE id = :id");
    $this->db->bind(':id', $farmer_id);

    return $this->db->execute();
}

public function deactivateDperson($delivery_id) {
    $this->db->query("UPDATE delivery_persons SET status = 'deactivated' WHERE id = :id");
    $this->db->bind(':id', $delivery_id);

    return $this->db->execute();
}

public function getComplaintById($complaint_id) {
    $this->db->query('
        SELECT 
            c.id AS complaint_id,
            c.user_id,
            c.role,
            c.status,
            c.order_id,
            d.delivery_person_id,
            fp.farmer_id
        FROM complaints c
        INNER JOIN delivery_info d ON c.order_id = d.order_id
        INNER JOIN order_success os ON c.order_id = os.orderID
        INNER JOIN fproducts fp ON os.productID = fp.fproduct_id
        INNER JOIN farmers f ON fp.farmer_id = f.id
        WHERE c.id = :id
        ORDER BY c.date_submitted DESC
    ');

    $this->db->bind(':id', $complaint_id);
    return $this->db->single(); // ✅ return a single row instead of an array
}

public function getFilteredComplaints($role, $status) {
    $sql = "SELECT *, id AS complaint_id FROM complaints WHERE 1=1";

    if (!empty($role)) {
        $sql .= " AND role = :role";
    }

    if (!empty($status)) {
        $sql .= " AND status = :status";
    }

    $this->db->query($sql);

    if (!empty($role)) {
        $this->db->bind(':role', $role);
    }

    if (!empty($status)) {
        $this->db->bind(':status', $status);
    }

    return $this->db->resultSet();
}


}
