// Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Navigation functionality
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.content-section');
    const pageTitle = document.getElementById('page-title');
    
    // Navigation click handler
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                
                // Remove active class from all links and sections
                navLinks.forEach(l => l.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding section
                const sectionId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                    
                    // Update page title
                    const linkText = this.querySelector('span').textContent;
                    pageTitle.textContent = linkText;
                }
            }
        });
    });
    
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    
    if (mobileMenuBtn && sidebar) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Form date handling
    const appointmentDate = document.getElementById('appointment-date');
    if (appointmentDate) {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        appointmentDate.min = today;
        
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        appointmentDate.value = tomorrowStr;
    }
    
    // Form validation
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--danger-color)';
                    
                    // Add error message if not exists
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const errorMsg = document.createElement('div');
                        errorMsg.className = 'error-message';
                        errorMsg.style.color = 'var(--danger-color)';
                        errorMsg.style.fontSize = '0.85rem';
                        errorMsg.style.marginTop = '5px';
                        errorMsg.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.style.borderColor = '';
                    
                    // Remove error message if exists
                    if (field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')) {
                        field.parentNode.removeChild(field.nextElementSibling);
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *');
            }
        });
    }
    
    // Clear form validation on input
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
            
            // Remove error message if exists
            if (this.nextElementSibling && this.nextElementSibling.classList.contains('error-message')) {
                this.parentNode.removeChild(this.nextElementSibling);
            }
        });
    });
    
    // Logout confirmation - moved from HTML
    document.querySelectorAll('a[href*="logout.php"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to logout?')) {
                e.preventDefault();
            }
        });
    });
});



// client_dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // Booking form submission
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Booking...';
            submitBtn.disabled = true;
            
            fetch('../database/book_service.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'alert alert-success';
                    successDiv.innerHTML = `
                        <i class="fas fa-check-circle"></i>
                        <strong>Success!</strong> ${data.message}
                        <button type="button" class="close-alert">&times;</button>
                    `;
                    
                    // Insert at top of form
                    bookingForm.parentNode.insertBefore(successDiv, bookingForm);
                    
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
                    
                } else {
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Error!</strong> ${data.message}
                        <button type="button" class="close-alert">&times;</button>
                    `;
                    
                    bookingForm.parentNode.insertBefore(errorDiv, bookingForm);
                    
                    errorDiv.querySelector('.close-alert').addEventListener('click', () => {
                        errorDiv.remove();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
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
            if (day === 0 || day === 6) {
                alert('Weekend appointments are not available. Please select a weekday.');
                this.value = '';
            }
        });
    }
});