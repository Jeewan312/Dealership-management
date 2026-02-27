<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action']; // 'confirm' or 'cancel'

    if ($action === 'confirm') {
        $new_status = 'confirmed';
        $notif_title = 'Booking Confirmed';
        $notif_message = 'Your appointment has been confirmed.';
    } elseif ($action === 'cancel') {
        $new_status = 'cancelled';
        $notif_title = 'Booking Cancelled';
        $notif_message = 'Your appointment has been cancelled.';
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    $conn->begin_transaction();

    try {
        // Update booking
        $update = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $update->bind_param("si", $new_status, $booking_id);
        $update->execute();

        if ($update->affected_rows === 0) {
            throw new Exception('Booking not found or already updated.');
        }

        // Get user_id
        $user_query = $conn->prepare("SELECT user_id FROM bookings WHERE id = ?");
        $user_query->bind_param("i", $booking_id);
        $user_query->execute();
        $user = $user_query->get_result()->fetch_assoc();
        $user_id = $user['user_id'];

        // Insert notification
        $notif = $conn->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
        $notif->bind_param("iss", $user_id, $notif_title, $notif_message);
        $notif->execute();

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}