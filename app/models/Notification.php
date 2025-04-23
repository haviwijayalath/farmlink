<?php

class Notification
{
  private $db;

  public function __construct()
  {
    $this->db = new Database;
  }

  // Add a new notification
  public function addNotification($from_type, $from_id, $to_type, $to_id, $subject, $content, $url, $msg_type)
  {
    $tableName = $this->getTableName($to_type);
    if (!$tableName) {
      return false; // Invalid to_type
    }
    $this->db->query("INSERT INTO {$tableName} (from_type, from_id, to_id, subject, content, url, msg_type, date_time, status) VALUES (:from_type, :from_id, :to_id, :subject, :content, :url, :msg_type, :date_time, :status)");

    // Bind parameters
    $this->db->bind(':from_type', $from_type);
    $this->db->bind(':from_id', $from_id);
    $this->db->bind(':to_id', $to_id);
    $this->db->bind(':subject', $subject);
    $this->db->bind(':content', $content);
    $this->db->bind(':url', $url);
    $this->db->bind(':msg_type', $msg_type);
    $this->db->bind(':date_time', date('Y-m-d H:i:s'));
    $this->db->bind(':status', 'unread');
    return $this->db->execute();
  }

  // Get notifications for a specific user
  public function getNotifications($to_type, $to_id)
  {
    $tableName = $this->getTableName($to_type);
    if (!$tableName) {
      return false; // Invalid to_type
    }
    $this->db->query("SELECT * FROM {$tableName} WHERE to_id = :to_id ORDER BY date_time DESC");
    $this->db->bind(':to_id', $to_id);
    return $this->db->resultSet();
  }
  
  // Mark a notification as read
  public function markAsRead($notificationId, $to_type)
  {
    $tableName = $this->getTableName($to_type);
    if (!$tableName) {
      return false;
    }
    
    $this->db->query("UPDATE {$tableName} SET status = 'read' WHERE id = :id");
    $this->db->bind(':id', $notificationId);
    return $this->db->execute();
  }

  // Mark all notifications as read for a user
  public function markAllAsRead($to_type, $to_id)
  {
    $tableName = $this->getTableName($to_type);
    if (!$tableName) {
      return false;
    }
    
    $this->db->query("UPDATE {$tableName} SET status = 'read' WHERE to_id = :to_id AND status = 'unread'");
    $this->db->bind(':to_id', $to_id);
    return $this->db->execute();
  }
  
  // Delete a notification
  public function dismissNotification($notificationId, $to_type)
  {
    $tableName = $this->getTableName($to_type);
    if (!$tableName) {
        return false;
    }
    
    $this->db->query("DELETE FROM {$tableName} WHERE id = :id");
    $this->db->bind(':id', $notificationId);
    return $this->db->execute();
  }

  // Helper method to get table name from type
  private function getTableName($type)
  {
    switch ($type) {
      case 'f':
        return 'notify_farmer';
      case 'b':
        return 'notify_buyer';
      case 'd':
        return 'notify_dperson';
      case 'c':
        return 'notify_consultant';
      default:
        return false;
    }
  }
}