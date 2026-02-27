<?php
// database/cancel_booking.php
session_start();
require_once 'connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$booking_id = $_POST['booking_id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Check if booking belongs to user
$sql = "SELECT id FROM bookings WHERE id = ? AND user_id = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found or cannot be cancelled']);
    exit;
}

// Update booking status
$update_sql = "UPDATE bookings SET status = 'cancelled', cancellation_reason = 'Cancelled by user' WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("i", $booking_id);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel booking']);
}

$update_stmt->close();
$stmt->close();
$conn->close();
?>