<?php
// /database/my_booking.php
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-warning">Please login to view your bookings.</div>';
    return;
}

$user_id = $_SESSION['user_id'];

// Handle booking cancellation by user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];
    
    // Check if booking belongs to user and is pending
    $check_sql = "SELECT id FROM booking WHERE id = ? AND user_id = ? AND status = 'pending'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    
    if ($check_stmt->get_result()->num_rows > 0) {
        $cancel_sql = "UPDATE booking SET status = 'cancelled', cancellation_reason = 'Cancelled by user' WHERE id = ?";
        $cancel_stmt = $conn->prepare($cancel_sql);
        $cancel_stmt->bind_param("i", $booking_id);
        
        if ($cancel_stmt->execute()) {
            echo '<div class="alert alert-success">Booking cancelled successfully!</div>';
        }
    }
}

// Fetch user bookings
$sql = "SELECT * FROM booking WHERE user_id = ? ORDER BY appointment_date DESC, created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0): ?>
    <div class="bookings-container">
        <div class="status-filters">
            <button class="filter-btn active" data-status="all">All</button>
            <button class="filter-btn" data-status="pending">Pending</button>
            <button class="filter-btn" data-status="confirmed">Confirmed</button>
            <button class="filter-btn" data-status="cancelled">Cancelled</button>
        </div>

        <div class="bookings-list">
            <?php while($booking = $result->fetch_assoc()): 
                // Format date
                $appointment_date = date('F j, Y', strtotime($booking['appointment_date']));
                $created_date = date('M d, Y H:i', strtotime($booking['created_at']));
                
                // Map service type to readable name
                $service_types = [
                    'oil-change' => 'Oil Change',
                    'tire-rotation' => 'Tire Rotation',
                    'brake-service' => 'Brake Service',
                    'engine-diagnostic' => 'Engine Diagnostic',
                    'full-service' => 'Full Service',
                    'battery-replacement' => 'Battery Replacement',
                    'ac-service' => 'AC Service'
                ];
                
                $service_name = $service_types[$booking['service_type']] ?? ucfirst($booking['service_type']);
            ?>
                <div class="booking-item" data-status="<?php echo $booking['status']; ?>">
                    <div class="booking-header">
                        <h4>Booking #<?php echo $booking['id']; ?> - <?php echo $service_name; ?></h4>
                        <span class="status-badge status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <span class="detail-label">Vehicle:</span>
                            <span class="detail-value">
                                <?php echo htmlspecialchars($booking['vehicle_make']); ?> 
                                <?php echo htmlspecialchars($booking['vehicle_model']); ?> 
                                (<?php echo $booking['vehicle_year']; ?>)
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Vehicle Number:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($booking['license_plate']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Appointment Date:</span>
                            <span class="detail-value"><?php echo $appointment_date; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Booked On:</span>
                            <span class="detail-value"><?php echo $created_date; ?></span>
                        </div>
                        
                        <?php if (!empty($booking['issues'])): ?>
                            <div class="detail-row">
                                <span class="detail-label">Issues:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['issues']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($booking['cancellation_reason'])): ?>
                            <div class="detail-row cancellation-reason">
                                <span class="detail-label">Cancellation Reason:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['cancellation_reason']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($booking['admin_notes'])): ?>
                            <div class="detail-row admin-notes">
                                <span class="detail-label">Admin Notes:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($booking['admin_notes']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($booking['status'] === 'pending'): ?>
                        <div class="booking-actions">
                            <form method="POST" class="cancel-form">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                <button type="submit" name="cancel_booking" class="btn-cancel-booking" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times"></i> Cancel Booking
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <style>
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #ffc107; color: #333; }
        .status-confirmed { background: #28a745; color: white; }
        .status-cancelled { background: #dc3545; color: white; }
        .status-completed { background: #17a2b8; color: white; }
        
        .booking-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .booking-details .detail-row {
            margin-bottom: 8px;
            display: flex;
        }
        
        .detail-label {
            font-weight: bold;
            min-width: 150px;
            color: #666;
        }
        
        .detail-value {
            color: #333;
        }
        
        .cancellation-reason {
            background: #ffebee;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .admin-notes {
            background: #e8f5e9;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .status-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 16px;
            border: 2px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .filter-btn.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .btn-cancel-booking {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .btn-cancel-booking:hover {
            background: #c82333;
        }
    </style>

    <script>
        // Filter bookings by status
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const bookingItems = document.querySelectorAll('.booking-item');
            
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(b => b.classList.remove('active'));
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const status = this.dataset.status;
                    
                    // Show/hide booking items based on status
                    bookingItems.forEach(item => {
                        if (status === 'all' || item.dataset.status === status) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
<?php else: ?>
    <div class="no-bookings">
        <i class="fas fa-calendar-times" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
        <h4>No Bookings Yet</h4>
        <p>You haven't made any service bookings yet. <a href="#book-service">Book your first service appointment!</a></p>
    </div>
<?php endif;
$stmt->close();
?>