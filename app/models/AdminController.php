<?php
class AdminController extends Database
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUsers()
    {
        $tables = [
            'farmers' => 'Farmer',
            'consultants' => 'Consultant',
            'delivery_persons' => 'Delivery_Person',
            'buyers' => 'Buyer'
        ];

        $users = [];

        foreach ($tables as $table => $roleName) {
            $this->db->query("SELECT id, name, email, phone, status, suspend_date FROM $table");
            $result = $this->db->resultSet();

            foreach ($result as $user) {
                $user->role = $roleName;
                $user->table = $table;
                $users[] = $user;
            }
        }

        return $users;
    }

    public function getUserDetailsById($table, $id)
    {
        $allowedTables = ['farmers', 'consultants', 'delivery_persons', 'buyers'];

        if (!in_array($table, $allowedTables)) {
            return null;
        }

        // Start with base query
        $selectFields = "u.*";
        $joins = "";

        // Add address fields if not buyer
        if ($table !== 'buyers') {
            $selectFields .= ", CONCAT(a.number, ', ', a.street, ', ', a.city) AS address";
            $joins .= " LEFT JOIN address a ON u.address_id = a.address_id";
        }

        // Add vehicle fields if delivery person
        if ($table === 'delivery_persons') {
            $selectFields .= ", v.type, v.regno, v.capacity, v.v_image";
            $joins .= " LEFT JOIN vehicle_info v ON u.vehicle_id = v.vehicle_id";
        }

        // Build the final query
        $query = "SELECT $selectFields FROM $table u $joins WHERE u.id = :id";

        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateUserStatus($role, $id, $status, $suspendDate = null)
    {
        $allowedRoles = ['farmers', 'buyers', 'delivery_persons', 'consultants'];

        if (!in_array($role, $allowedRoles)) {
            return false;
        }

        $sql = "UPDATE $role SET status = :status, suspend_date = :suspend_date WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':suspend_date', $suspendDate);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function getFilteredUsers($role, $status)
    {
        $allowedRoles = ['farmers', 'buyers', 'delivery_persons', 'consultants'];
        $roleNames = [
            'farmers' => 'Farmer',
            'consultants' => 'Consultant',
            'delivery_persons' => 'Delivery_Person',
            'buyers' => 'Buyer'
        ];
        $results = [];

        if (!empty($role) && in_array($role, $allowedRoles)) {
            // Filter by role (table), and maybe also by status
            if (!empty($status)) {
                $query = "SELECT id, name, email, phone, status FROM $role WHERE status = :status";
                $this->db->query($query);
                $this->db->bind(':status', $status);
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
                if (!empty($status)) {
                    $query = "SELECT id, name, email, phone, status FROM $table WHERE status = :status";
                    $this->db->query($query);
                    $this->db->bind(':status', $status);
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

    public function getSupportRequests()
    {
        $this->db->query("SELECT * FROM support");
        return $this->db->resultSet();
    }

    public function deleteSupportMessage($id)
    {
        $this->db->query("DELETE FROM support WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
