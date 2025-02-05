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

        // Execute the delivery person insertion
        return $this->db->execute();

}

}