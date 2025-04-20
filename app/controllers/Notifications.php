<?php
class Notifications extends Controller {
    private $notificationHelper;
    
    public function __construct() {
        $this->notificationHelper = new NotificationHelper();
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            } else {
                redirect('users/login');
            }
        }
    }
    
    // Get notifications for the current user
    public function getNotifications() {
        // Determine user type and ID
        $userType = $this->getUserType();
        $userId = $this->getUserId();
        
        if (!$userType || !$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid user information']);
            exit;
        }
        
        // Get notifications using helper
        echo $this->notificationHelper->get_notifications($userType, $userId);
    }
    
    // Mark notification as read
    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        $data = json_decode(file_get_contents('php://input'));
        
        if (!isset($data->notification_id) || !isset($data->to_type)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required data']);
            exit;
        }
        
        $notification = new Notification();
        $result = $notification->markAsRead($data->notification_id, $data->to_type);
        
        echo json_encode(['success' => (bool)$result]);
    }
    
    // Mark all notifications as read
    public function markAllAsRead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
        
        $userType = $this->getUserType();
        $userId = $this->getUserId();
        
        if (!$userType || !$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid user information']);
            exit;
        }
        
        $notification = new Notification();
        $result = $notification->markAllAsRead($userType, $userId);
        
        echo json_encode(['success' => (bool)$result]);
    }
    
    // Helper method to determine user type
    private function getUserType() {
        if (isset($_SESSION['user_role'])) {
            switch ($_SESSION['user_role']) {
                case 'farmer':
                    return 'f';
                case 'buyer':
                    return 'b';
                case 'delivery_person':
                case 'delivery':
                    return 'd';
                case 'consultant':
                    return 'c';
                default:
                    return null;
            }
        } else if (isset($_SESSION['user_type'])) {
            // If user_type is stored directly
            return $_SESSION['user_type'];
        }
        return null;
    }
    
    // Helper method to get user ID
    private function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 
               (isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null);
    }
}