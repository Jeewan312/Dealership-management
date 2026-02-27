<?php
// /database/notifications.php

session_start();
require_once 'connection.php';
require_once 'send_notification.php'; // Include the functions file
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle different actions
$action = $_GET['action'] ?? 'get';

switch ($action) {
    case 'get':
        $notifications = getUserNotifications($conn, $user_id, 20);
        $unread_count = 0;
        
        foreach ($notifications as $notification) {
            if (!$notification['is_read']) {
                $unread_count++;
            }
        }
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unread_count
        ]);
        break;
        
    case 'mark_read':
        $notification_id = $_POST['notification_id'] ?? 0;
        if ($notification_id > 0) {
            $success = markNotificationAsRead($conn, $notification_id);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid notification ID']);
        }
        break;
        
    case 'mark_all_read':
        $success = markAllNotificationsAsRead($conn, $user_id);
        echo json_encode(['success' => $success]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>