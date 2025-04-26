<?php
class Appointment {
  private $db;
  
  public function __construct() {
    $this->db = new Database;
  }
  
  // Book an appointment
  public function bookAppointment($data) {
    $this->db->query("
      INSERT INTO appointments (consultant_id, farmer_id, appointment_date, appointment_time, notes, status)
      VALUES (:consultant_id, :farmer_id, :appointment_date, :appointment_time, :notes, 'Pending')
    ");
    $this->db->bind(':consultant_id', $data['consultant_id']);
    $this->db->bind(':farmer_id', $data['farmer_id']);
    $this->db->bind(':appointment_date', $data['appointment_date']);
    $this->db->bind(':appointment_time', $data['appointment_time']);
    $this->db->bind(':notes', $data['notes']);
    return $this->db->execute();
  }

  public function getAvailability($consultant_id) {
    $this->db->query("
      SELECT available_date
        FROM consultant_availability
       WHERE consultant_id = :cid
       ORDER BY available_date ASC
    ");
    $this->db->bind(':cid', $consultant_id);
    return $this->db->resultSet();
  }
  
  // Retrieve appointments for a farmer
  public function getAppointmentsByFarmer($farmer_id) {
    $this->db->query("SELECT 
        a.*, 
        c.name AS consultant_name, 
        a.appointment_date AS date, 
        a.appointment_time AS time,
        a.status, 
        a.notes AS message 
      FROM appointments a
      JOIN consultants c ON a.consultant_id = c.id
      WHERE a.farmer_id = :farmer_id 
      ORDER BY a.appointment_date DESC");
    $this->db->bind(':farmer_id', $farmer_id);
    return $this->db->resultSet();
  }
  
  // Retrieve appointments for a consultant
  public function getAppointmentsByConsultant($consultant_id) {
    $this->db->query("SELECT 
        a.appointment_id AS id, 
        a.appointment_date AS date, 
        a.appointment_time AS time, 
        a.notes AS message,
        a.status AS status,
        f.name AS farmer_name
      FROM appointments a
      JOIN farmers f ON a.farmer_id = f.id
      WHERE a.consultant_id = :consultant_id
      ORDER BY a.appointment_date DESC");
    $this->db->bind(':consultant_id', $consultant_id);
    return $this->db->resultSet();
  }

  /**
   * Fetch a single appointment by its ID.
   */
  public function getAppointmentById($appointment_id) {
    $this->db->query("
      SELECT *
        FROM appointments
       WHERE appointment_id = :id
    ");
    $this->db->bind(':id', $appointment_id);
    return $this->db->single();
  }

  public function getLastInsertId() {
    return $this->db->lastInsertId();
  }
  
  // Cancel an appointment. (You may wish to update the status rather than deleting.)
  public function cancelAppointment($appointment_id) {
    $this->db->query("DELETE FROM appointments WHERE appointment_id = :id");
    $this->db->bind(':id', $appointment_id);
    return $this->db->execute();
  }

  public function updateStatus($appointment_id, $status) {
    $this->db->query("UPDATE appointments SET status = :status WHERE appointment_id = :id");
    $this->db->bind(':status', $status);
    $this->db->bind(':id', $appointment_id);
    return $this->db->execute();
}

}
