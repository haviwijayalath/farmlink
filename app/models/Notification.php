<?php

class Notification
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  // Add a new notification
  public function addNotification($from_type, $from_id, $to_type, $to_id, $content, $url, $msg_type)
  {
    switch ($to_type) {
      case 'f':
        $this->db->query('INSERT INTO notify_farmer (from_type, from_id, to_id, content, url, msg_type, date_time, status) VALUES (:from_type, :from_id, :to_id, :content, :url, :msg_type, :date_time, :status)');
        break;
      case 'b':
        $this->db->query('INSERT INTO notify_buyer (from_type, from_id, to_id, content, url, msg_type, date_time, status) VALUES (:from_type, :from_id, :to_id, :content, :url, :msg_type, :date_time, :status)');
        break;
      case 'd': 
        $this->db->query('INSERT INTO notify_dperson (from_type, from_id, to_id, content, url, msg_type, date_time, status) VALUES (:from_type, :from_id, :to_id, :content, :url, :msg_type, :date_time, :status)');
        break;
      case 'c':
        $this->db->query('INSERT INTO notify_consultant (from_type, from_id, to_id, content, url, msg_type, date_time, status) VALUES (:from_type, :from_id, :to_id, :content, :url, :msg_type, :date_time, :status)');
        break;
      default:
        return false; // Invalid to_type
    }
    $this->db->bind(':from_type', $from_type);
    $this->db->bind(':from_id', $from_id);
    $this->db->bind(':to_id', $to_id);
    $this->db->bind(':content', $content);
    $this->db->bind(':url', $url);
    $this->db->bind(':msg_type', $msg_type);
    $this->db->bind(':date_time', date('Y-m-d H:i:s'));
    $this->db->bind(':status', 'unread');
    return $this->db->execute();
  }

  
}