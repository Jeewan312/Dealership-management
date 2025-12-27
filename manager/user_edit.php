<?php
include "../database/connection.php";
$id = $_GET['id'];
$query = "SELECT * FROM users where id= $id";
$data = mysqli_query($conn, $query);

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
        .client-error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>User Authentication</h1>
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

                <button type="submit" name="Register" class="btn">
                    Edit Information
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    if (!form) return;

    // Hide pre-existing error messages on page load
    document.querySelectorAll('.error-message, .success-message').forEach(el => {
        el.style.display = 'none';
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('full-name');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const address = document.getElementById('address');

        let isValid = true;

        // Clear previous errors
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

        /* ---------- SUBMIT ---------- */
        if (isValid) {
            form.submit();
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
});
</script>
</body>
</html>