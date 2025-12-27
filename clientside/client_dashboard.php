<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service Booking Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../design/css/client_dashboard.css">
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
                    <a href="#book-service" class="nav-link active" data-section="book-service">
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
                <li class="nav-item">
                    <a href="#logout" class="nav-link" id="logout-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
            
            <div class="user-profile">
                <div class="avatar">JD</div>
                <div class="user-info">
                    <h4>John Doe</h4>
                    <p>Premium Member</p>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h2 id="page-title">Book Service</h2>
                <div class="header-actions">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
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
                    
                    <form id="booking-form">
                        <div class="form-section">
                            <h4><i class="fas fa-car"></i> Vehicle Information</h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="vehicle-make">Vehicle Make *</label>
                                    <select id="vehicle-make" class="form-control" required>
                                        <option value="">Select Make</option>
                                        <option value="Toyota">Toyota</option>
                                        <option value="Honda">Honda</option>
                                        <option value="Ford">Ford</option>
                                        <option value="BMW">BMW</option>
                                        <option value="Mercedes">Mercedes</option>
                                        <option value="Audi">Audi</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="vehicle-year">Year *</label>
                                    <select id="vehicle-year" class="form-control" required>
                                        <option value="">Select Year</option>
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                        <option value="2020">2020</option>
                                        <option value="2019">2019</option>
                                        <option value="2018">2018</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="vehicle-model">Vehicle Model *</label>
                                    <input type="text" id="vehicle-model" class="form-control" placeholder="e.g. CRF-300L" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="license-plate">License Plate Number *</label>
                                    <input type="text" id="license-plate" class="form-control" placeholder="Ra-PA-000" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="vehicle-color">Color</label>
                                    <input type="text" id="vehicle-color" class="form-control" placeholder="e.g., Red, Blue, Black">
                                </div>
                                
                                <div class="form-group">
                                    <label for="vin">VIN (Optional)</label>
                                    <input type="text" id="vin" class="form-control" placeholder="Vehicle Identification Number">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h4><i class="fas fa-exclamation-triangle"></i> Service Details</h4>
                            
                            <div class="form-group">
                                <label for="current-issues">Current Issues / Concerns</label>
                                <textarea id="current-issues" class="form-control issues-textarea" placeholder="Describe any problems you're experiencing with your vehicle"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="service-type">Service Type *</label>
                                    <select id="service-type" class="form-control" required>
                                        <option value="">Select Service Type</option>
                                        <option value="oil-change">Oil Change</option>
                                        <option value="tire-rotation">Tire Rotation</option>
                                        <option value="brake-service">Brake Service</option>
                                        <option value="engine-diagnostic">Engine Diagnostic</option>
                                        <option value="full-service">Full Service</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="appointment-date">Preferred Date *</label>
                                    <input type="date" id="appointment-date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
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
                    
                    <div class="bookings-container" id="bookings-container">
                        <!-- Bookings will be dynamically inserted here -->
                    </div>
                </div>
            </section>
            
            <!-- Profile Section -->
            <section class="content-section" id="profile">
                <div class="card">
                    <h3><i class="fas fa-user-circle"></i> My Profile</h3>
                    <p class="card-description">Manage your account information and preferences</p>
                    
                    <div class="profile-container">
                        <div class="profile-sidebar">
                            <div class="profile-avatar">JD</div>
                            <h4>John Doe</h4>
                            <p>Premium Member</p>
                            <p>Member since: June 2021</p>
                            
                            <div class="profile-actions">
                                <button class="btn btn-outline" id="change-photo-btn">
                                    <i class="fas fa-camera"></i> Change Photo
                                </button>
                                <button class="btn btn-outline" id="change-password-btn">
                                    <i class="fas fa-key"></i> Change Password
                                </button>
                            </div>
                        </div>
                        
                        <div class="profile-details">
                            <form id="profile-form">
                                <div class="form-section">
                                    <h4><i class="fas fa-user"></i> Personal Information</h4>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="first-name">First Name</label>
                                            <input type="text" id="first-name" class="form-control" value="John">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="last-name">Last Name</label>
                                            <input type="text" id="last-name" class="form-control" value="Doe">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" id="email" class="form-control" value="john.doe@example.com">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="tel" id="phone" class="form-control" value="+1 (555) 123-4567">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" class="form-control" value="123 Main Street, Anytown, USA">
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h4><i class="fas fa-car"></i> Vehicle Preferences</h4>
                                    
                                    <div class="form-group">
                                        <label for="default-vehicle">Default Vehicle</label>
                                        <select id="default-vehicle" class="form-control">
                                            <option value="1">Honda CRF-300L (RA-PA-001)</option>
                                            <option value="2">Toyota Camry (RA-PA-002)</option>
                                            <option value="3">Ford F-150 (RA-PA-003)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="email-notifications" checked>
                                            Receive email notifications for appointments
                                        </label>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="sms-notifications">
                                            Receive SMS reminders for appointments
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        
        <!-- Logout Modal -->
        <div class="modal-overlay" id="logout-modal">
            <div class="modal">
                <div class="modal-header">
                    <div class="modal-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <div>
                        <h3>Confirm Logout</h3>
                        <p>Are you sure you want to logout from your account?</p>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-outline" id="cancel-logout">
                        Cancel
                    </button>
                    <button class="btn btn-danger" id="confirm-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../access/javascript/client_dashboard.js"></script>
</body>
</html>