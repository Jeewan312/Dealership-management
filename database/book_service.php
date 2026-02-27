<?php
// database/book_service.php
header('Content-Type: application/json');
require_once 'connection.php';  // adjust path if needed

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$vehicle_make   = $_POST['vehicle_make'] ?? '';
$vehicle_model  = $_POST['vehicle_model'] ?? '';
$vehicle_year   = $_POST['vehicle_year'] ?? '';
$license_plate  = $_POST['license_plate'] ?? '';
$vehicle_color  = $_POST['vehicle_color'] ?? '';
$vin            = $_POST['vin'] ?? '';
$issues         = $_POST['issues'] ?? '';
$service_type   = $_POST['service_type'] ?? '';
$appointment_date = $_POST['appointment_date'] ?? '';

// Basic validation
if (empty($vehicle_make) || empty($vehicle_model) || empty($vehicle_year) || empty($license_plate) || empty($service_type) || empty($appointment_date)) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

// Insert into database
try {
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, vehicle_make, vehicle_model, vehicle_year, license_plate, vehicle_color, vin, issues, service_type, appointment_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ississssss", $user_id, $vehicle_make, $vehicle_model, $vehicle_year, $license_plate, $vehicle_color, $vin, $issues, $service_type, $appointment_date);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking created successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>