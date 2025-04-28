<?php
class Admin extends Database
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  /**
   * Dashboard statistics
   */
  public function getDashboardStats()
  {
    // 1) Count each role table
    // We can do it in one SQL with sub‑selects:
    $this->db->query("
      SELECT
        (SELECT COUNT(*) FROM admins)            AS totalAdmins,
        (SELECT COUNT(*) FROM farmers)           AS totalFarmers,
        (SELECT COUNT(*) FROM buyers)            AS totalBuyers,
        (SELECT COUNT(*) FROM consultants)       AS totalConsultants,
        (SELECT COUNT(*) FROM delivery_persons)  AS totalDeliveryPersons
    ");
    $users = $this->db->single();
    // Sum them up
    $totalUsers =
      $users->totalAdmins
      + $users->totalFarmers
      + $users->totalBuyers
      + $users->totalConsultants
      + $users->totalDeliveryPersons;

    // 2) Total orders — use your order_buyer table (or whatever you name it)
    $this->db->query("SELECT COUNT(*) AS totalOrders FROM order_success");
    $totalOrders = $this->db->single()->totalOrders;

    // 3) Total complaints
    $this->db->query("SELECT COUNT(*) AS totalComplaints FROM complaints");
    $totalComplaints = $this->db->single()->totalComplaints;

    // 4) Total “reports”
    // If you have a product_reviews table and you want to treat those as “reports”:
    $this->db->query("SELECT COUNT(*) AS totalReports FROM fproducts_reviews");
    $totalReports = $this->db->single()->totalReports;

    return [
      'totalUsers'      => $totalUsers,
      'totalOrders'     => $totalOrders,
      'totalComplaints' => $totalComplaints,
      'totalReports'    => $totalReports
    ];
  }

  public function getSalesByLocation()
  {
    $this->db->query("
        SELECT oba.city,
               SUM(os.famersFee + os.deliveryFee) AS total
        FROM order_success os
        JOIN order_buyer_addr oba
          ON os.orderID = oba.address_id
        GROUP BY oba.city
    ");
    $rows = $this->db->resultSet();
    $out = [];
    foreach ($rows as $r) {
      $out[$r->city] = (float)$r->total;
    }
    return $out;
  }

  /**
   *  Sales by product category
   *  JOIN order_success → fproducts
   */
  public function getSalesByCategory()
  {
    $this->db->query("
        SELECT p.type AS category,
               SUM(os.famersFee + os.deliveryFee) AS total
        FROM order_success os
        JOIN fproducts p
          ON os.productID = p.fproduct_id
        GROUP BY p.type
    ");
    $rows = $this->db->resultSet();
    $out = [];
    foreach ($rows as $r) {
      $out[$r->category] = (float)$r->total;
    }
    return $out;
  }

  /**
   *  Top selling products (by quantity)
   */
  public function getTopSellingProducts($limit = 5)
  {
    $this->db->query("
        SELECT
          p.fproduct_id AS id,
          p.name        AS name,
          p.type        AS category,
          SUM(os.quantity)                       AS quantity,
          SUM(os.famersFee + os.deliveryFee)     AS revenue
        FROM order_success os
        JOIN fproducts p
          ON os.productID = p.fproduct_id
        GROUP BY p.fproduct_id, p.name, p.type
        ORDER BY quantity DESC
        LIMIT :limit
    ");
    $this->db->bind(':limit', $limit);
    $rows = $this->db->resultSet();
    $out = [];
    foreach ($rows as $r) {
      $out[] = [
        'id'       => $r->id,
        'name'     => $r->name,
        'category' => $r->category,
        'quantity' => (int)$r->quantity,
        'revenue'  => (float)$r->revenue
      ];
    }
    return $out;
  }

  /**
   * User management
   */
  public function getAllUsers()
  {
    $this->db->query("
      SELECT id, name, email, 'admin' AS role, status, createdAt FROM admins
      UNION ALL
      SELECT id, name, email, 'farmer' AS role, status, createdAt FROM farmers
      UNION ALL
      SELECT id, name, email, 'buyer' AS role, status, createdAt FROM buyers
      UNION ALL
      SELECT id, name, email, 'consultant' AS role, status, createdAt FROM consultants
      UNION ALL
      SELECT id, name, email, 'delivery_person' AS role, status, createdAt FROM delivery_persons
      ORDER BY id DESC
    ");
    return $this->db->resultSet();
  }

  public function suspendUser($userId)
  {
    $this->db->query("UPDATE users SET status = 'suspended' WHERE id = :id");
    $this->db->bind(':id', $userId);
    return $this->db->execute();
  }

  public function activateUser($userId)
  {
    $this->db->query("UPDATE users SET status = 'active' WHERE id = :id");
    $this->db->bind(':id', $userId);
    return $this->db->execute();
  }

  /**
   * Order management
   */
  public function getAllOrders()
  {
    $this->db->query("
      SELECT
        os.orderID       AS id,
        os.buyerID       AS buyer_id,
        b.name           AS buyerName,
        os.status        AS status,
        (os.famersFee + os.deliveryFee)  AS total_amount,
        os.orderDate     AS created_at
      FROM order_success os
      LEFT JOIN buyers b 
        ON os.buyerID = b.id
      ORDER BY os.orderDate DESC
    ");
    return $this->db->resultSet();
  }

  public function getOrderById($orderId)
  {
    $this->db->query("
      SELECT
        os.orderID       AS id,
        os.buyerID       AS buyer_id,
        b.name           AS buyerName,
        b.email          AS buyerEmail,
        os.productID     AS productID,
        os.product       AS productName,
        os.quantity      AS quantity,
        os.famersFee     AS farmersFee,
        os.deliveryFee   AS deliveryFee,
        (os.famersFee + os.deliveryFee)  AS total_amount,
        os.dropAddress   AS dropAddress,
        os.orderDate     AS created_at,
        os.status        AS status
      FROM order_success os
      LEFT JOIN buyers b 
        ON os.buyerID = b.id
      WHERE os.orderID = :id
    ");
    $this->db->bind(':id', $orderId);
    return $this->db->single();
  }

  /**
   * Fetch all line‐items for a given orderID
   */
  public function getOrderItems($orderId)
  {
    $this->db->query("
      SELECT
        os.productID   AS sku,
        os.product     AS name,
        os.famersFee   AS price,
        os.quantity,
        os.deliveryFee AS delivery_fee
      FROM order_success os
      WHERE os.orderID = :id
    ");
    $this->db->bind(':id', $orderId);

    return $this->db->resultSet();
  }

  /**
   * Complaint management
   */
  public function getAllComplaints()
  {
    $this->db->query("
      SELECT
        c.id,
        c.order_id,
        c.user_id,
        c.role AS userRole,
        c.description,
        c.status,
        c.date_submitted        AS created_at,
        c.fault_by,
        c.admin_notes,
        -- Pick the right name based on c.role:
        CASE
          WHEN c.role = 'buyer'               THEN b.name
          WHEN c.role = 'delivery_persons'    THEN dp.name
          ELSE 'Unknown'
        END                         AS userName
      FROM complaints c
      LEFT JOIN buyers b 
        ON c.user_id = b.id
      LEFT JOIN delivery_persons dp 
        ON c.user_id = dp.id
      ORDER BY c.date_submitted DESC
    ");
    return $this->db->resultSet();
  }

  public function resolveComplaint($complaintId, $faultBy = null, $adminNotes = null)
  {
    $this->db->query("
      UPDATE complaints
         SET status      = 'resolved',
             fault_by    = :fault_by,
             admin_notes = :admin_notes
       WHERE id = :id
    ");
    $this->db->bind(':fault_by',    $faultBy);
    $this->db->bind(':admin_notes', $adminNotes);
    $this->db->bind(':id',          $complaintId);
    return $this->db->execute();
  }
  /**
   * Report management
   */
  public function getAllReports()
  {
    $this->db->query("
      SELECT r.id, r.title, r.description, r.created_at,
             u.name AS userName
      FROM reports r
      JOIN users u ON r.user_id = u.id
      ORDER BY r.created_at DESC
    ");
    return $this->db->resultSet();
  }

  /**
   * Product management
   */
  public function getProducts()
  {
    $this->db->query("
      SELECT

        p.fproduct_id   AS id,
        p.name          AS productName,
        p.price,
        p.stock,
        p.exp_date,
        f.name          AS farmerName
      FROM fproducts p
      JOIN farmers f 
        ON p.farmer_id = f.id
      ORDER BY p.fproduct_id DESC
    ");
    return $this->db->resultSet();
  }

  public function getProductById($productId)
  {
    $this->db->query("
      SELECT
        p.fproduct_id   AS id,
        p.name          AS productName,
        p.type          AS category,
        p.description,
        p.price,
        p.stock,
        p.exp_date,
        p.image,
        f.id            AS farmerId,
        f.name          AS farmerName,
        f.email         AS farmerEmail,
        f.image         AS farmerImage
      FROM fproducts p
      JOIN farmers f 
        ON p.farmer_id = f.id
      WHERE p.fproduct_id = :id
    ");
    $this->db->bind(':id', $productId);
    return $this->db->single();
  }

  /**
   * Admin account management
   */
  public function getAdminById($adminId)
  {
    $this->db->query("
      SELECT id, name, email, phone, password
      FROM admins
      WHERE id = :id
    ");
    $this->db->bind(':id', $adminId);
    return $this->db->single();
  }



  public function getUsers()
  {
    $tables = [
      'farmers' => 'Farmer',
      'delivery_persons' => 'Delivery_Person',
    ];

    $users = [];

    foreach ($tables as $table => $roleName) {
      $this->db->query("SELECT id, name FROM $table");
      $result = $this->db->resultSet();

      foreach ($result as $user) {
        $user->role = $roleName;
        $user->table = $table;
        $users[] = $user;
      }
    }

    return $users;
  }

  public function getTotalRevenue($farmerId = null, $deliveryPersonId = null)
  {
    if ($farmerId !== null) {
      // Query only for Farmer revenue
      $query = "
            SELECT 
                SUM(os.famersFee) AS total_farmer_fee
            FROM order_success os
            JOIN fproducts fp ON os.productID = fp.fproduct_id
            WHERE fp.farmer_id = :farmerid
        ";

      $this->db->query($query);
      $this->db->bind(':farmerid', $farmerId);
      return $this->db->single();
      
    } elseif ($deliveryPersonId !== null) {
      // Query only for Delivery Person revenue
      $query = "
            SELECT 
                SUM(di.amount) AS total_delivery_fee
            FROM delivery_info di
            WHERE di.delivery_person_id = :dpersonid
        ";

      $this->db->query($query);
      $this->db->bind(':dpersonid', $deliveryPersonId);
      return $this->db->single();
    } else {
      return null; // No id provided
    }
  }

  public function getFilteredOrders($status = null, $from = null, $to = null)
  {
    $sql = "
        SELECT
          os.orderID   AS id,
          os.orderDate AS created_at,
          b.name       AS buyerName,
          (os.famersFee + os.deliveryFee) AS total_amount,
          os.status
        FROM order_success os
        LEFT JOIN buyers b ON os.buyerID = b.id
        WHERE 1=1
      ";
    // Add filters
    if ($status) {
      $sql .= " AND os.status = :status";
    }
    if ($from) {
      $sql .= " AND os.orderDate >= :from";
    }
    if ($to) {
      $sql .= " AND os.orderDate <= :to";
    }
    $sql .= " ORDER BY os.orderDate DESC";

    $this->db->query($sql);
    if ($status) {
      $this->db->bind(':status', $status);
    }
    if ($from) {
      $this->db->bind(':from', $from . ' 00:00:00');
    }
    if ($to) {
      $this->db->bind(':to', $to . ' 23:59:59');
    }
    return $this->db->resultSet();
  }

  public function getFilteredReports($role)
  {
    $allowedRoles = ['farmers', 'delivery_persons'];
    $roleNames = [
      'farmers' => 'Farmer',
      'delivery_persons' => 'Delivery_Person',
    ];

    $results = [];

    if (!empty($role) && in_array($role, $allowedRoles)) {
      // Filter by role (table), and maybe also by status
      if (!empty($role)) {
        $query = "SELECT id, name, email, phone, status FROM $role ";
        $this->db->query($query);
      } else {
        $query = "SELECT id, name, email, phone, status FROM $role";
        $this->db->query($query);
      }

      $result = $this->db->resultSet();
      foreach ($result as $user) {
        $user->role = $roleNames[$role];
        $user->table = $role;
        $results[] = $user;
      }
    } else {
      // No specific role: check all tables for status (or fetch all if no status)
      foreach ($allowedRoles as $table) {
        if (!empty($role)) {
          $query = "SELECT id, name, email, phone, status FROM $table ";
          $this->db->query($query);
        } else {
          $query = "SELECT id, name, email, phone, status FROM $table";
          $this->db->query($query);
        }

        $res = $this->db->resultSet();
        foreach ($res as $user) {
          $user->role = $roleNames[$table];
          $user->table = $table;
          $results[] = $user;
        }
      }
    }

    return $results;
  }

  public function getFilteredComplaints($role = '', $status = '')
  {
    // Build the base query
    $query = "SELECT complaints.*, 
                     b.name, 
                     d.name AS dpname 
              FROM complaints
              LEFT JOIN buyers b ON complaints.user_id = b.id
              LEFT JOIN delivery_persons d ON complaints.user_id = delivery_persons.id
              WHERE 1";

    // Add filters dynamically
    $params = [];

    if (!empty($role)) {
      $query .= " AND complaints.role = :role";
      $params[':role'] = $role;
    }

    if (!empty($status)) {
      $query .= " AND complaints.status = :status";
      $params[':status'] = $status;
    }

    $this->db->query($query);

    // Bind parameters
    foreach ($params as $key => $value) {
      $this->db->bind($key, $value);
    }

    // Execute and get result
    return $this->db->resultSet();
  }


  public function updateAccount($adminId, $data)
  {
    $this->db->query("
      UPDATE admins
      SET name = :name,
          email = :email,
          phone = :phone
      WHERE id = :id
    ");
    $this->db->bind(':name',  $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':phone', $data['phone']);
    $this->db->bind(':id',    $adminId);
    return $this->db->execute();
  }

  public function changePassword($adminId, $currentPwd, $newPwd)
  {
    // Fetch current hash
    $this->db->query("SELECT password FROM admins WHERE id = :id");
    $this->db->bind(':id', $adminId);
    $row = $this->db->single();

    if ($row && password_verify($currentPwd, $row->password)) {
      $hash = password_hash($newPwd, PASSWORD_DEFAULT);
      $this->db->query("UPDATE admins SET password = :pwd WHERE id = :id");
      $this->db->bind(':pwd', $hash);
      $this->db->bind(':id',  $adminId);
      return $this->db->execute();
    }

    return false;
  }

  public function deactivateAccount($adminId)
  {
    $this->db->query("UPDATE admins SET status = 'inactive' WHERE id = :id");
    $this->db->bind(':id', $adminId);
    return $this->db->execute();
  }
}
