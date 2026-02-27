<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../visitor/homepage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../design/css/signin.css">
    <style>
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .success { color: green; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
   <body>

<div class="container">
    <div class="header">
        <h1>User Authentication</h1>
        <p>Create a new account</p>
    </div>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p class="error-message">'.$_SESSION['error'].'</p>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p class="success-message">'.$_SESSION['success'].'</p>';
        unset($_SESSION['success']);
    }
    ?>

    <div class="content">
        <div class="form-container">

            <!-- ✅ ID ADDED -->
            <form class="form" id="signup-form" method="POST" action="../database/register.php">

                <h2 class="form-title">Create Account</h2>

                <div class="input-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" id="full-name" >
                    <div class="error-message" id="full-name-error">Full name is required</div>
                </div>

                <div class="input-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" id="email" >
                    <div class="error-message" id="email-error">Valid email is required</div>
                    <div class="success-message" id="email-success">Email format is valid</div>
                </div>

                <div class="input-group">
                    <label>Phone Number *</label>
                    <input type="text" name="phone" id="phone">
                    <div class="error-message" id="phone-error">Valid phone number is required</div>
                    <div class="success-message" id="phone-success">Phone format is valid</div>
                </div>

                <div class="input-group">
                    <label>Address *</label>
                    <input type="text" name="address" id="address">
                    <div class="error-message" id="address-error">Address is required</div>
                </div>

                <div class="input-group">
                    <label>Password *</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" >
                        <!-- ✅ ID ADDED -->
                        <button type="button" class="password-toggle" id="signup-password-toggle">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="password-error">
                        Password must be at least 8 characters
                    </div>
                </div>

                <div class="input-group">
                    <label>Confirm Password *</label>
                    <div class="password-container">
                        <input type="password" name="cpassword" id="confirm-password" >
                        <!-- ✅ ID ADDED -->
                        <button type="button" class="password-toggle" id="confirm-password-toggle">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    <div class="error-message" id="confirm-password-error">
                        Passwords do not match
                    </div>
                </div>

                <button type="submit" name="Register" class="btn">
                    Create Account
                </button>

                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>

            </form>
        </div>
    </div>
</div>
    <script>document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    if (!form) return;

    // 1. FIX: Initialize password toggle functionality
    const signupPasswordToggle = document.getElementById('signup-password-toggle');
    const confirmPasswordToggle = document.getElementById('confirm-password-toggle');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    
    if (signupPasswordToggle && passwordInput) {
        signupPasswordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    if (confirmPasswordToggle && confirmPasswordInput) {
        confirmPasswordToggle.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // 2. FIX: Hide pre-existing error messages on page load
    document.querySelectorAll('.error-message, .success-message').forEach(el => {
        el.style.display = 'none';
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('full-name');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const address = document.getElementById('address');
        const password = document.getElementById('password');
        const cpassword = document.getElementById('confirm-password');

        let isValid = true;

        // Clear previous errors - FIXED: Only clear dynamic errors
        document.querySelectorAll('.client-error').forEach(el => el.remove());

        /* ---------- NAME ---------- */
        if (!name.value.trim()) {
            showError(name, 'Full name is required');
            isValid = false;
        } else if (name.value.trim().length < 3) {
            showError(name, 'Name must be at least 3 characters');
            isValid = false;
        }

        /* ---------- EMAIL ---------- */
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            showError(email, 'Enter a valid email address');
            isValid = false;
        }

        /* ---------- NEPALI PHONE NUMBER ---------- */
        // FIXED: More flexible phone validation for Nepal
        // Allows: 98, 97, 96 (Ncell), 984, 985 (NTC), etc.
        const nepaliPhoneRegex = /^(98|97|96|984|985)\d{6,8}$/;
        
        if (!phone.value.trim()) {
            showError(phone, 'Phone number is required');
            isValid = false;
        } else {
            // Remove any spaces, dashes, etc.
            const cleanPhone = phone.value.trim().replace(/[\s\-\(\)]/g, '');
            
            // Check if it contains only digits
            if (!/^\d+$/.test(cleanPhone)) {
                showError(phone, 'Phone number must contain only digits');
                isValid = false;
            } else if (cleanPhone.length < 10) {
                showError(phone, 'Phone number must be at least 10 digits');
                isValid = false;
            } else if (cleanPhone.length > 10) {
                showError(phone, 'Phone number cannot exceed 10 digits');
                isValid = false;
            } else if (!nepaliPhoneRegex.test(cleanPhone)) {
                showError(phone, 'Please enter a valid Nepali phone number (starts with 98, 97, 96, etc.)');
                isValid = false;
            }
        }

        /* ---------- ADDRESS ---------- */
        if (!address.value.trim()) {
            showError(address, 'Address is required');
            isValid = false;
        }

        /* ---------- PASSWORD ---------- */
        // FIXED: Slightly more flexible password regex
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        
        if (!password.value) {
            showError(password, 'Password is required');
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters');
            isValid = false;
        } else if (!passwordRegex.test(password.value)) {
            showError(password, 'Password must contain at least one uppercase letter, one lowercase letter, and one number');
            isValid = false;
        }

        /* ---------- CONFIRM PASSWORD ---------- */
        if (!cpassword.value) {
            showError(cpassword, 'Please confirm your password');
            isValid = false;
        } else if (password.value !== cpassword.value) {
            showError(cpassword, 'Passwords do not match');
            isValid = false;
        }

        /* ---------- SUBMIT ---------- */
        if (isValid) {
            // Optional: Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;
            
            // Submit the form
            form.submit();
            
            // Reset button after 3 seconds (in case submission fails)
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 3000);
        }
    });

    function showError(input, message) {
        // Remove any existing error for this input
        const existingError = input.parentNode.querySelector('.client-error');
        if (existingError) {
            existingError.remove();
        }
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'client-error';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        errorDiv.style.marginBottom = '10px';
        errorDiv.textContent = message;

        // Insert after the input element
        input.parentNode.appendChild(errorDiv);
        
        // Focus on the input with error
        input.focus();
        input.style.borderColor = 'red';
        
        // Remove red border on input
        input.addEventListener('input', function() {
            this.style.borderColor = '';
            const error = this.parentNode.querySelector('.client-error');
            if (error) error.remove();
        }, { once: true });
    }
    
    // 3. FIX: Add real-time validation for all inputs
    const inputs = ['full-name', 'email', 'phone', 'address', 'password', 'confirm-password'];
    
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function() {
                // Clear error styling
                this.style.borderColor = '';
                
                // Remove any existing client error
                const errorDiv = this.parentNode.querySelector('.client-error');
                if (errorDiv) errorDiv.remove();
                
                // For password confirmation, check if it matches
                if (inputId === 'confirm-password') {
                    const password = document.getElementById('password').value;
                    const confirmPassword = this.value;
                    
                    if (confirmPassword && password !== confirmPassword) {
                        showError(this, 'Passwords do not match');
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>