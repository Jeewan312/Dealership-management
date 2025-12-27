<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: ../manager/admin.php");
    } else {
        header("Location: ../visitor/homepage.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="../design/css/login.css">
    <style>
        .error { 
            color: red; 
            font-size: 14px; 
            margin-bottom: 10px; 
            padding: 10px;
            border-radius: 4px;
            background-color: rgba(255, 0, 0, 0.1);
            text-align: center;
        }
        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            background-color: rgba(0, 255, 0, 0.1);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
            
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_SESSION['login_error']); unset($_SESSION['login_error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success"><?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="../database/login.php">
                <div class="input-box">
                    <input type="text" id="username" name="username" >
                    <label for="username">Username or Email</label>
                </div>
                
                <div class="input-box">
                    <input type="password" id="password" name="password" >
                    <label for="password">Password</label>
                </div>
                
                <button type="submit" name="login" class="btn">Login</button>
                
                <p class="register">Don't have an account? <a href="signup.php">Sign Up</a></p>
            </form>
        </div>
    </div>
    < <script src="../access/javascript/login.js"></></body>
</html>