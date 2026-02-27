<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get stats
$stats = [];
$result = $conn->query("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending
    FROM bookings");
$stats = $result->fetch_assoc();

// Get recent bookings
$recent = [];
$result = $conn->query("SELECT b.id, b.service_type, b.appointment_date, b.status, u.name as user_name 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC 
    LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $recent[] = $row;
}

echo json_encode([
    'stats' => $stats,
    'recent' => $recent
]);