<?php
// /database/send_notification.php

require_once 'connection.php'; // Include database connection

/**
 * Send notification to user
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param string $title Notification title
 * @param string $message Notification message
 * @param string $type Notification type (info, success, warning, error)
 * @return bool True if successful, false otherwise
 */
function sendNotification($conn, $user_id, $title, $message, $type = 'info') {
    $sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $title, $message, $type);
    return $stmt->execute();
}

/**
 * Get user notifications
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @param int $limit Number of notifications to fetch
 * @return array Array of notifications
 */
function getUserNotifications($conn, $user_id, $limit = 10) {
    $sql = "SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    return $notifications;
}

/**
 * Mark notification as read
 * @param mysqli $conn Database connection
 * @param int $notification_id Notification ID
 * @return bool True if successful
 */
function markNotificationAsRead($conn, $notification_id) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notification_id);
    return $stmt->execute();
}

/**
 * Mark all notifications as read for a user
 * @param mysqli $conn Database connection
 * @param int $user_id User ID
 * @return bool True if successful
 */
function markAllNotificationsAsRead($conn, $user_id) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// If this file is accessed directly, just return without output
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    exit('Access denied');
}
?>