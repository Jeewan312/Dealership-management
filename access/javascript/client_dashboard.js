// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Navigation between sections
    const navLinks = document.querySelectorAll('.nav-link');
    const contentSections = document.querySelectorAll('.content-section');
    const pageTitle = document.getElementById('page-title');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    
    // Sample booking data
    let bookings = [
        {
            id: "BK-00123",
            vehicle: "Honda CRF-300L",
            licensePlate: "RA-PA-001",
            serviceType: "Oil Change",
            date: "2023-10-15",
            time: "10:00 AM",
            status: "upcoming",
            make: "Honda",
            year: "2021"
        },
        {
            id: "BK-00122",
            vehicle: "Toyota Camry",
            licensePlate: "RA-PA-002",
            serviceType: "Brake Service",
            date: "2023-10-05",
            time: "2:30 PM",
            status: "completed",
            make: "Toyota",
            year: "2019"
        },
        {
            id: "BK-00121",
            vehicle: "Ford F-150",
            licensePlate: "RA-PA-003",
            serviceType: "Engine Diagnostic",
            date: "2023-09-28",
            time: "9:00 AM",
            status: "cancelled",
            make: "Ford",
            year: "2020"
        }
    ];
    
    // Initialize the dashboard
    initDashboard();
    
    function initDashboard() {
        // Set up event listeners
        setupNavigation();
        setupMobileMenu();
        setupLogoutModal();
        setupForms();
        setupProfileButtons();
        
        // Initialize bookings display
        displayBookings();
        
        // Set default appointment date
        setDefaultAppointmentDate();
        
        // Update notification badge
        updateNotificationBadge();
    }
    
    function setupNavigation() {
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.id === 'logout-link') {
                    e.preventDefault();
                    document.getElementById('logout-modal').classList.add('active');
                    return;
                }
                
                e.preventDefault();
                
                // Remove active class from all links and sections
                navLinks.forEach(item => item.classList.remove('active'));
                contentSections.forEach(section => section.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding section
                const sectionId = this.getAttribute('data-section');
                document.getElementById(sectionId).classList.add('active');
                
                // Update page title
                const linkText = this.querySelector('span').textContent;
                pageTitle.textContent = linkText;
                
                // Close mobile sidebar if open
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('active');
                }
            });
        });
    }
    
    function setupMobileMenu() {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 992) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnMenuBtn = mobileMenuBtn.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnMenuBtn && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }
    
    function setupLogoutModal() {
        const logoutModal = document.getElementById('logout-modal');
        const cancelLogoutBtn = document.getElementById('cancel-logout');
        const confirmLogoutBtn = document.getElementById('confirm-logout');
        
        cancelLogoutBtn.addEventListener('click', function() {
            logoutModal.classList.remove('active');
        });
        
        confirmLogoutBtn.addEventListener('click', function() {
            // In a real app, you would redirect to logout endpoint
            alert('You have been logged out successfully. In a real application, this would redirect to the login page.');
            logoutModal.classList.remove('active');
            
            // For demo purposes, reset to book service page
            navLinks.forEach(item => item.classList.remove('active'));
            contentSections.forEach(section => section.classList.remove('active'));
            
            document.querySelector('[data-section="book-service"]').classList.add('active');
            document.getElementById('book-service').classList.add('active');
            pageTitle.textContent = 'Book Service';
        });
        
        // Close modal when clicking outside
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('active');
            }
        });
    }
    
    function setupForms() {
        // Booking form submission
        const bookingForm = document.getElementById('booking-form');
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const make = document.getElementById('vehicle-make').value;
            const year = document.getElementById('vehicle-year').value;
            const model = document.getElementById('vehicle-model').value;
            const licensePlate = document.getElementById('license-plate').value;
            const serviceType = document.getElementById('service-type').value;
            const date = document.getElementById('appointment-date').value;
            const issues = document.getElementById('current-issues').value;
            
            // Validate required fields
            if (!make || !year || !model || !licensePlate || !serviceType || !date) {
                alert('Please fill in all required fields (marked with *)');
                return;
            }
            
            // Create new booking object
            const newBooking = {
                id: "BK-" + Math.floor(10000 + Math.random() * 90000),
                vehicle: `${make} ${model}`,
                licensePlate: licensePlate,
                serviceType: serviceType,
                date: date,
                time: "10:00 AM", // Default time for demo
                status: "upcoming",
                make: make,
                year: year,
                issues: issues
            };
            
            // Add to bookings array
            bookings.unshift(newBooking);
            
            // Update bookings display
            displayBookings();
            
            // Update notification badge
            updateNotificationBadge();
            
            // Show success message
            alert(`Appointment booked successfully! Your booking ID is: ${newBooking.id}`);
            
            // Switch to My Bookings section
            document.querySelector('[data-section="my-bookings"]').click();
            
            // Reset form
            bookingForm.reset();
            
            // Set default appointment date again
            setDefaultAppointmentDate();
        });
        
        // Profile form submission
        const profileForm = document.getElementById('profile-form');
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const firstName = document.getElementById('first-name').value;
            const lastName = document.getElementById('last-name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            // Update user info in sidebar
            document.querySelector('.user-info h4').textContent = `${firstName} ${lastName}`;
            document.querySelector('.avatar').textContent = `${firstName.charAt(0)}${lastName.charAt(0)}`;
            document.querySelector('.profile-avatar').textContent = `${firstName.charAt(0)}${lastName.charAt(0)}`;
            document.querySelector('.profile-sidebar h4').textContent = `${firstName} ${lastName}`;
            
            alert('Profile updated successfully!');
        });
    }
    
    function setupProfileButtons() {
        // Change photo button
        document.getElementById('change-photo-btn').addEventListener('click', function() {
            alert('In a real application, this would open a file picker to upload a new profile photo.');
        });
        
        // Change password button
        document.getElementById('change-password-btn').addEventListener('click', function() {
            alert('In a real application, this would open a password change form.');
        });
    }
    
    function displayBookings() {
        const bookingsContainer = document.getElementById('bookings-container');
        
        if (bookings.length === 0) {
            bookingsContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Bookings Found</h4>
                    <p>You haven't made any service appointments yet. Book your first service now!</p>
                    <button class="btn btn-primary" id="book-first-service">
                        <i class="fas fa-calendar-plus"></i> Book Service
                    </button>
                </div>
            `;
            
            // Add event listener to the button
            document.getElementById('book-first-service')?.addEventListener('click', function() {
                document.querySelector('[data-section="book-service"]').click();
            });
            
            return;
        }
        
        let bookingsHTML = '';
        
        bookings.forEach(booking => {
            let statusClass = '';
            let statusText = '';
            
            switch(booking.status) {
                case 'upcoming':
                    statusClass = 'status-upcoming';
                    statusText = 'Upcoming';
                    break;
                case 'completed':
                    statusClass = 'status-completed';
                    statusText = 'Completed';
                    break;
                case 'cancelled':
                    statusClass = 'status-cancelled';
                    statusText = 'Cancelled';
                    break;
            }
            
            bookingsHTML += `
                <div class="booking-card ${booking.status}">
                    <div class="booking-header">
                        <div>
                            <h4>${booking.vehicle}</h4>
                            <div class="booking-id">${booking.id}</div>
                        </div>
                        <div class="booking-status ${statusClass}">${statusText}</div>
                    </div>
                    
                    <div class="booking-details">
                        <div class="detail-row">
                            <span class="detail-label">Service Type:</span>
                            <span>${booking.serviceType}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Date:</span>
                            <span>${formatDate(booking.date)} at ${booking.time}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">License Plate:</span>
                            <span>${booking.licensePlate}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Vehicle:</span>
                            <span>${booking.make} ${booking.year}</span>
                        </div>
                    </div>
                    
                    <div class="booking-actions">
                        ${booking.status === 'upcoming' ? `
                            <button class="btn btn-primary" onclick="rescheduleBooking('${booking.id}')">
                                <i class="fas fa-calendar-alt"></i> Reschedule
                            </button>
                            <button class="btn btn-danger" onclick="cancelBooking('${booking.id}')">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        ` : ''}
                        
                        ${booking.status === 'completed' ? `
                            <button class="btn btn-primary" onclick="bookAgain('${booking.id}')">
                                <i class="fas fa-redo"></i> Book Again
                            </button>
                        ` : ''}
                        
                        <button class="btn btn-outline" onclick="viewBookingDetails('${booking.id}')">
                            <i class="fas fa-eye"></i> Details
                        </button>
                    </div>
                </div>
            `;
        });
        
        bookingsContainer.innerHTML = bookingsHTML;
    }
    
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
    
    function setDefaultAppointmentDate() {
        // Set minimum date for appointment date to today
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('appointment-date');
        dateInput.min = today;
        
        // Set default appointment date to 3 days from now
        const defaultDate = new Date();
        defaultDate.setDate(defaultDate.getDate() + 3);
        dateInput.value = defaultDate.toISOString().split('T')[0];
    }
    
    function updateNotificationBadge() {
        const upcomingBookings = bookings.filter(b => b.status === 'upcoming').length;
        document.querySelector('.notification-badge').textContent = upcomingBookings;
    }
    
    // Global functions for booking actions
    window.rescheduleBooking = function(bookingId) {
        alert(`Reschedule functionality for booking ${bookingId} would open a rescheduling form.`);
    };
    
    window.cancelBooking = function(bookingId) {
        if (confirm('Are you sure you want to cancel this booking?')) {
            // Update booking status
            const bookingIndex = bookings.findIndex(b => b.id === bookingId);
            if (bookingIndex !== -1) {
                bookings[bookingIndex].status = 'cancelled';
                displayBookings();
                updateNotificationBadge();
                alert(`Booking ${bookingId} cancelled successfully.`);
            }
        }
    };
    
    window.bookAgain = function(bookingId) {
        const booking = bookings.find(b => b.id === bookingId);
        if (booking) {
            // Pre-fill the booking form with previous booking details
            document.getElementById('vehicle-make').value = booking.make;
            document.getElementById('vehicle-year').value = booking.year;
            
            // Extract model from vehicle name
            const model = booking.vehicle.replace(booking.make, '').trim();
            document.getElementById('vehicle-model').value = model;
            
            document.getElementById('license-plate').value = booking.licensePlate;
            document.getElementById('service-type').value = booking.serviceType.toLowerCase().replace(' ', '-');
            
            // Navigate to book service section
            document.querySelector('[data-section="book-service"]').click();
            
            alert('Previous booking details have been pre-filled. Please review and submit the form.');
        }
    };
    
    window.viewBookingDetails = function(bookingId) {
        const booking = bookings.find(b => b.id === bookingId);
        if (booking) {
            const details = `
                Booking ID: ${booking.id}
                Vehicle: ${booking.vehicle} (${booking.year})
                License Plate: ${booking.licensePlate}
                Service Type: ${booking.serviceType}
                Appointment: ${formatDate(booking.date)} at ${booking.time}
                Status: ${booking.status}
                ${booking.issues ? `Issues Reported: ${booking.issues}` : ''}
            `;
            alert(`Booking Details:\n\n${details}`);
        }
    };
});