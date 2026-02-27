<?php
// admin/admin_pending.php
session_start();
require_once '../database/connection.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Handle booking actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['booking_id'])) {
        $booking_id = $_POST['booking_id'];
        $action = $_POST['action'];
        $admin_notes = $_POST['admin_notes'] ?? '';
        $cancellation_reason = $_POST['cancellation_reason'] ?? '';
        
        if ($action === 'confirm') {
            $sql = "UPDATE bookings SET status = 'confirmed', admin_notes = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $admin_notes, $booking_id);
        } elseif ($action === 'cancel') {
            $sql = "UPDATE bookings SET status = 'cancelled', cancellation_reason = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $cancellation_reason, $booking_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Booking $action" . "ed successfully!";
        }
        $stmt->close();
    }
}

// Fetch pending bookings
$sql = "SELECT b.*, u.name, u.email, u.phone 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        WHERE b.status = 'pending' 
        ORDER BY b.appointment_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pending Bookings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .booking-card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .booking-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #ffc107; color: #333; }
        .status-confirmed { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .booking-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; }
        .detail-item label { font-weight: bold; color: #666; display: block; }
        .detail-item span { color: #333; }
        .action-buttons { margin-top: 20px; display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-confirm { background: #28a745; color: white; }
        .btn-cancel { background: #dc3545; color: white; }
        .btn-view { background: #17a2b8; color: white; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { background: white; margin: 10% auto; padding: 20px; width: 80%; max-width: 500px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: vertical; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab-btn { padding: 10px 20px; background: #e9ecef; border: none; border-radius: 4px; cursor: pointer; }
        .tab-btn.active { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-tools"></i> Admin Panel - Service Bookings</h1>
            <p>Manage pending, confirmed, and cancelled bookings</p>
        </div>

        <div class="tabs">
            <button class="tab-btn active" onclick="showTab('pending')">Pending</button>
            <button class="tab-btn" onclick="showTab('confirmed')">Confirmed</button>
            <button class="tab-btn" onclick="showTab('cancelled')">Cancelled</button>
            <button class="tab-btn" onclick="showTab('all')">All Bookings</button>
        </div>

        <div id="pending-tab" class="tab-content">
            <h2>Pending Bookings (<?php echo $result->num_rows; ?>)</h2>
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3>Booking #<?php echo $row['id']; ?> - <?php echo $row['service_type']; ?></h3>
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="detail-item">
                                <label>Customer</label>
                                <span><?php echo htmlspecialchars($row['name']); ?> (<?php echo $row['email']; ?>)</span>
                            </div>
                            <div class="detail-item">
                                <label>Vehicle</label>
                                <span><?php echo htmlspecialchars($row['vehicle_make']); ?> <?php echo htmlspecialchars($row['vehicle_model']); ?> (<?php echo $row['vehicle_year']; ?>)</span>
                            </div>
                            <div class="detail-item">
                                <label>License Plate</label>
                                <span><?php echo htmlspecialchars($row['license_plate']); ?></span>
                            </div>
                            <div class="detail-item">
                                <label>Appointment Date</label>
                                <span><?php echo date('F j, Y', strtotime($row['appointment_date'])); ?></span>
                            </div>
                            <?php if (!empty($row['issues'])): ?>
                                <div class="detail-item">
                                    <label>Issues Reported</label>
                                    <span><?php echo htmlspecialchars($row['issues']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <button class="btn btn-confirm" onclick="showConfirmModal(<?php echo $row['id']; ?>)">
                                <i class="fas fa-check"></i> Confirm
                            </button>
                            <button class="btn btn-cancel" onclick="showCancelModal(<?php echo $row['id']; ?>)">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button class="btn btn-view" onclick="viewBookingDetails(<?php echo $row['id']; ?>)">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="booking-card">
                    <p>No pending bookings at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3>Confirm Booking</h3>
            <form method="POST">
                <input type="hidden" name="booking_id" id="confirm_booking_id">
                <input type="hidden" name="action" value="confirm">
                
                <div class="form-group">
                    <label>Admin Notes (Optional):</label>
                    <textarea name="admin_notes" rows="4" placeholder="Add any notes for the customer..."></textarea>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-confirm">Confirm Booking</button>
                    <button type="button" class="btn" onclick="closeModal('confirmModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <h3>Cancel Booking</h3>
            <form method="POST">
                <input type="hidden" name="booking_id" id="cancel_booking_id">
                <input type="hidden" name="action" value="cancel">
                
                <div class="form-group">
                    <label>Cancellation Reason (Required):</label>
                    <textarea name="cancellation_reason" rows="4" placeholder="Please provide a reason for cancellation..." required></textarea>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-cancel">Cancel Booking</button>
                    <button type="button" class="btn" onclick="closeModal('cancelModal')">Go Back</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showConfirmModal(bookingId) {
            document.getElementById('confirm_booking_id').value = bookingId;
            document.getElementById('confirmModal').style.display = 'block';
        }

        function showCancelModal(bookingId) {
            document.getElementById('cancel_booking_id').value = bookingId;
            document.getElementById('cancelModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab and activate button
            document.getElementById(tabName + '-tab').style.display = 'block';
            event.target.classList.add('active');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>