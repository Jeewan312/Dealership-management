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
    <title>Sign Up - User Authentication</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../design/css/client_signup.css">

    <style>
        .error-message { color: red; font-size: 14px; }
        .success-message { color: green; font-size: 14px; }
    </style>
</head>
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
                    <input type="text" name="name" id="full-name" required>
                    <div class="error-message" id="full-name-error">Full name is required</div>
                </div>

                <div class="input-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" id="email" required>
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
                        <input type="password" name="password" id="password" required>
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
                        <input type="password" name="cpassword" id="confirm-password" required>
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
                    <p>Already have an account? <a href="client_login.php">Login here</a></p>
                    <p><a href="../visitor/homepage.php">← Back to main page</a></p>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="../access/javascript/client_signup.js"></script>
</body>
</html>
