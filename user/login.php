<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../manager/admin.php");
    } else {
        header("Location: ../clientside/client_dashboard.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dealership</title>
    <link rel="stylesheet" href="../design/css/login.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Login</h2>

            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="error-message" style="color: red; margin-bottom: 10px;">
                    <?php 
                        echo htmlspecialchars($_SESSION['login_error']); 
                        unset($_SESSION['login_error']);
                    ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="../database/logindb.php">
                <div class="input-group">
                    <input type="email" id="email" name="email" required>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                </div>

                <div class="input-group">
                    <button type="submit" name="login" class="btn">Login</button>
                </div>

                <div class="form-footer">
                    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                event.preventDefault();
                alert('Please enter a valid email address.');
            } else if (password.length < 6) {
                event.preventDefault();
                alert('Password must be at least 6 characters long.');
            }
        });
    </script>
</body>
</html>