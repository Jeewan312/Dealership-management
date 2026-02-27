<?php 
// Start session at the very top
session_start();

include '../database/connection.php';

// Debug: Check session data
error_log("Session data: " . print_r($_SESSION, true));

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: ../login.php');
//     exit;
// }

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? 0;
$user_name = $_SESSION['name'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service Booking Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../design/css/client_dashboard.css">
    <style>
        /* Alert styles */
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .close-alert {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: inherit;
            margin-left: auto;
            cursor: pointer;
            opacity: 0.7;
        }
        
        .close-alert:hover {
            opacity: 1;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Notification styles */
        .notification-container {
            position: relative;
            margin-right: 15px;
        }
        
        .notification-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #333;
            cursor: pointer;
            position: relative;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }
        
        .notification-btn:hover {
            background-color: #f0f0f0;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-bike"></i>
                <h1>AutoCare</h1>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="booking_form.php" class="nav-link active" data-section="book-service">
                        <i class="fas fa-calendar-plus"></i>
                        <span>Book Service</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#my-bookings" class="nav-link" data-section="my-bookings">
                        <i class="fas fa-calendar-check"></i>
                        <span>My Bookings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#profile" class="nav-link" data-section="profile">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                <li class="nav-item">
                    <a href="../admin/admin_pending.php" class="nav-link" target="_blank">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Panel</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item">
                    <a href="../database/logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
            
            <div class="user-profile">
                <div class="avatar">
                    <?php 
                        // Display user initials
                        $initials = '';
                        $nameParts = explode(' ', $user_name);
                        foreach ($nameParts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        echo substr($initials, 0, 2);
                    ?>
                </div>
                <div class="user-info">
                    <h4><?php echo htmlspecialchars($user_name); ?></h4>
                    <p><?php echo ucfirst($user_role); ?></p>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h2 id="page-title">Book Service</h2>
                <div class="header-actions">
                    <!-- Notification dropdown -->
                    <div class="notification-container">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge"><?php 
                                // Get unread notification count
                                $notification_sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
                                $notification_stmt = $conn->prepare($notification_sql);
                                $notification_stmt->bind_param("i", $user_id);
                                $notification_stmt->execute();
                                $notification_result = $notification_stmt->get_result();
                                $notification_count = $notification_result->fetch_assoc()['count'] ?? 0;
                                echo $notification_count > 0 ? $notification_count : '';
                            ?></span>
                        </button>
                        <div class="notification-dropdown">
                            <div class="notification-header">
                                <h4>Notifications</h4>
                                <button class="mark-all-read">Mark all as read</button>
                            </div>
                            <div class="notification-list">
                                <?php
                                // Get notifications
                                $notif_sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10";
                                $notif_stmt = $conn->prepare($notif_sql);
                                $notif_stmt->bind_param("i", $user_id);
                                $notif_stmt->execute();
                                $notif_result = $notif_stmt->get_result();
                                
                                if ($notif_result->num_rows > 0) {
                                    while ($notif = $notif_result->fetch_assoc()) {
                                        $read_class = $notif['is_read'] ? 'read' : 'unread';
                                        echo '<div class="notification-item ' . $read_class . '" data-id="' . $notif['id'] . '">';
                                        echo '<div class="notification-content">';
                                        echo '<div class="notification-title">' . htmlspecialchars($notif['title']) . '</div>';
                                        echo '<div class="notification-message">' . htmlspecialchars($notif['message']) . '</div>';
                                        echo '<div class="notification-time">' . date('M d, Y H:i', strtotime($notif['created_at'])) . '</div>';
                                        echo '</div>';
                                        if (!$notif['is_read']) {
                                            echo '<span class="notification-dot"></span>';
                                        }
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<div class="notification-empty">No notifications</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <button class="mobile-menu-btn" id="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <!-- Book Service Section -->
            <section class="content-section active" id="book-service">
                <div class="card">
                    <h3><i class="fas fa-calendar-plus"></i> Schedule Vehicle Service</h3>
                    <p class="card-description">Fill in the details below to schedule your vehicle service appointment</p>
                    
                    <!-- Message container for AJAX responses -->
                    <div id="message-container"></div>
                    
                    <!-- Form submits via AJAX -->
                    <form id="booking-form" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        
                        <div class="form-section">
                            <h4><i class="fas fa-car"></i> Vehicle Information</h4>
                            
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label for="vehicle-make">Vehicle Make <span class="required">*</span></label>
                                    <select id="vehicle-make" name="vehicle_make" class="form-control" required>
                                        <option value="">Select Make</option>
                                        <option value="Toyota">Toyota</option>
                                        <option value="Honda">Honda</option>
                                        <option value="Ford">Ford</option>
                                        <option value="BMW">BMW</option>
                                        <option value="Mercedes">Mercedes</option>
                                        <option value="Audi">Audi</option>
                                        <option value="Suzuki">Suzuki</option>
                                        <option value="Hyundai">Hyundai</option>
                                    </select>
                                </div>
                                
                                <div class="form-group-half">
                                    <label for="vehicle-year">Year <span class="required">*</span></label>
                                    <select id="vehicle-year" name="vehicle_year" class="form-control" required>
                                        <option value="">Select Year</option>
                                        <?php
                                        $current_year = date('Y');
                                        for ($year = $current_year; $year >= 2000; $year--) {
                                            echo "<option value='$year'>$year</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-divider"></div>
                            
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label for="vehicle-model">Vehicle Model <span class="required">*</span></label>
                                    <input type="text" id="vehicle-model" name="vehicle_model" class="form-control" placeholder="e.g. CRF-300L" required>
                                </div>
                                
                                <div class="form-group-half">
                                    <label for="license-plate">Vehicle Number <span class="required">*</span></label>
                                    <input type="text" id="license-plate" name="license_plate" class="form-control" placeholder="Ra-PA-000" required>
                                </div>
                            </div>
                            
                            <div class="form-divider"></div>
                            
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label for="vehicle-color">Color</label>
                                    <input type="text" id="vehicle-color" name="vehicle_color" class="form-control" placeholder="e.g., Red, Blue, Black">
                                </div>
                                
                                <div class="form-group-half">
                                    <label for="vin">VIN (Optional)</label>
                                    <input type="text" id="vin" name="vin" class="form-control" placeholder="Vehicle Identification Number">
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider">
                            <hr>
                        </div>
                        
                        <div class="form-section">
                            <h4><i class="fas fa-exclamation-triangle"></i> Service Details</h4>
                            
                            <div class="form-group-full">
                                <label for="current-issues">Current Issues / Concerns</label>
                                <textarea id="current-issues" name="issues" class="form-control issues-textarea" placeholder="Describe any problems you're experiencing with your vehicle"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label for="service-type">Service Type <span class="required">*</span></label>
                                    <select id="service-type" name="service_type" class="form-control" required>
                                        <option value="">Select Service Type</option>
                                        <option value="oil-change">Oil Change</option>
                                        <option value="tire-rotation">Tire Rotation</option>
                                        <option value="brake-service">Brake Service</option>
                                        <option value="engine-diagnostic">Engine Diagnostic</option>
                                        <option value="full-service">Full Service</option>
                                        <option value="battery-replacement">Battery Replacement</option>
                                        <option value="ac-service">AC Service</option>
                                    </select>
                                </div>
                                
                                <div class="form-group-half">
                                    <label for="appointment-date">Preferred Date <span class="required">*</span></label>
                                    <input type="date" id="appointment-date" name="appointment_date" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="book_service" class="btn btn-primary">
                                <i class="fas fa-calendar-check"></i> Book Appointment
                            </button>
                            <button type="reset" class="btn btn-outline">
                                <i class="fas fa-redo"></i> Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            
            <!-- My Bookings Section -->
            <section class="content-section" id="my-bookings">
                <div class="card">
                    <h3><i class="fas fa-calendar-check"></i> My Bookings</h3>
                    <p class="card-description">View and manage your upcoming and past service appointments</p>
                    
                    <?php
                    // Include the my_booking.php to display bookings
                    require_once '../database/my_booking.php';
                    ?>
                </div>
            </section>
            
            <!-- Profile Section -->
            <section class="content-section" id="profile">
                <div class="card">
                    <h3><i class="fas fa-user-circle"></i> My Profile</h3>
                    <p class="card-description">Manage your account information and preferences</p>
                    
                    <?php
                    // Get user profile
                    $profile_sql = "SELECT * FROM users WHERE id = ?";
                    $profile_stmt = $conn->prepare($profile_sql);
                    $profile_stmt->bind_param("i", $user_id);
                    $profile_stmt->execute();
                    $profile_result = $profile_stmt->get_result();
                    
                    if ($profile_result->num_rows > 0) {
                        $profile = $profile_result->fetch_assoc();
                        ?>
                        <div class="profile-info">
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label>Full Name</label>
                                    <p class="profile-value"><?php echo htmlspecialchars($profile['name']); ?></p>
                                </div>
                                <div class="form-group-half">
                                    <label>Email</label>
                                    <p class="profile-value"><?php echo htmlspecialchars($profile['email']); ?></p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group-half">
                                    <label>Phone</label>
                                    <p class="profile-value"><?php echo htmlspecialchars($profile['phone'] ?? 'Not provided'); ?></p>
                                </div>
                                <div class="form-group-half">
                                    <label>User Role</label>
                                    <p class="profile-value"><?php echo ucfirst($profile['role'] ?? 'customer'); ?></p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group-full">
                                    <label>Address</label>
                                    <p class="profile-value"><?php echo htmlspecialchars($profile['address'] ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Include JavaScript -->
    <script src="../access/javascript/client_dashboard.js"></script>
    
    <!-- Inline JavaScript for booking form -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingForm = document.getElementById('booking-form');
        const messageContainer = document.getElementById('message-container');
        
        // Set minimum date for appointment (tomorrow)
        const appointmentDate = document.getElementById('appointment-date');
        if (appointmentDate) {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            appointmentDate.min = tomorrow.toISOString().split('T')[0];
            
            // Disable weekends
            appointmentDate.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const day = selectedDate.getDay();
                if (day === 0 || day === 6) { // 0 = Sunday, 6 = Saturday
                    alert('Weekend appointments are not available. Please select a weekday.');
                    this.value = '';
                }
            });
        }
        
        if (bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Disable submit button to prevent multiple submissions
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
                submitBtn.disabled = true;
                
                // Clear previous messages
                messageContainer.innerHTML = '';
                
                // Collect form data
                const formData = new FormData(this);
                
                // Debug: Log form data
                console.log('Form data:', Object.fromEntries(formData));
                
                // Submit via AJAX
                fetch('../database/book_service.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Response received');
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    
                    if (data.success) {
                        // Show success message
                        const successDiv = document.createElement('div');
                        successDiv.className = 'alert alert-success';
                        successDiv.innerHTML = `
                            <i class="fas fa-check-circle"></i>
                            <strong>Success!</strong> ${data.message}
                            <button type="button" class="close-alert">&times;</button>
                        `;
                        
                        messageContainer.appendChild(successDiv);
                        
                        // Reset form
                        bookingForm.reset();
                        
                        // Auto remove message after 5 seconds
                        setTimeout(() => {
                            if (successDiv.parentNode) {
                                successDiv.remove();
                            }
                        }, 5000);
                        
                        // Close alert on click
                        successDiv.querySelector('.close-alert').addEventListener('click', () => {
                            successDiv.remove();
                        });
                        
                        // Reload bookings section
                        if (typeof loadBookings === 'function') {
                            loadBookings();
                        }
                        
                    } else {
                        // Show error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger';
                        errorDiv.innerHTML = `
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Error!</strong> ${data.message}
                            <button type="button" class="close-alert">&times;</button>
                        `;
                        
                        messageContainer.appendChild(errorDiv);
                        
                        errorDiv.querySelector('.close-alert').addEventListener('click', () => {
                            errorDiv.remove();
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Error!</strong> An error occurred. Please try again.
                        <button type="button" class="close-alert">&times;</button>
                    `;
                    
                    messageContainer.appendChild(errorDiv);
                    
                    errorDiv.querySelector('.close-alert').addEventListener('click', () => {
                        errorDiv.remove();
                    });
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
        
        // Notification handling
        const notificationContainer = document.querySelector('.notification-container');
        const notificationDropdown = document.querySelector('.notification-dropdown');
        
        if (notificationContainer && notificationDropdown) {
            notificationContainer.addEventListener('mouseenter', function() {
                notificationDropdown.style.display = 'block';
            });
            
            notificationContainer.addEventListener('mouseleave', function() {
                setTimeout(() => {
                    if (!notificationDropdown.matches(':hover') && !this.matches(':hover')) {
                        notificationDropdown.style.display = 'none';
                    }
                }, 100);
            });
            
            // Mark notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.dataset.id;
                    if (notificationId) {
                        fetch('../database/notifications.php?action=mark_read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'notification_id=' + notificationId
                        });
                        
                        this.classList.remove('unread');
                        this.classList.add('read');
                        this.querySelector('.notification-dot')?.remove();
                        
                        // Update badge count
                        const badge = document.querySelector('.notification-badge');
                        let count = parseInt(badge.textContent) || 0;
                        if (count > 0) {
                            count--;
                            badge.textContent = count > 0 ? count : '';
                        }
                    }
                });
            });
            
            // Mark all as read
            const markAllBtn = document.querySelector('.mark-all-read');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fetch('../database/notifications.php?action=mark_all_read', {
                        method: 'POST'
                    }).then(() => {
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('unread');
                            item.classList.add('read');
                            item.querySelector('.notification-dot')?.remove();
                        });
                        document.querySelector('.notification-badge').textContent = '';
                    });
                });
            }
        }
    });
    </script>
</body>
</html>