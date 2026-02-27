<?php
session_start();

// If logout is confirmed
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Store username for message
    $user_name = $_SESSION['name'] ?? 'User';
    
    // Clear session
    session_unset();
    session_destroy();
    
    // Prevent caching
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    // Set success message
    session_start();
    $_SESSION['logout_success'] = "You have been logged out successfully!";
    
    // Redirect to homepage.php
    header("Location: ../visitor/homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .logout-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            width: 100%;
            max-width: 350px;
        }
        
        .logout-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 22px;
        }
        
        .message {
            color: #666;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .username {
            color: #3498db;
            font-weight: bold;
        }
        
        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: opacity 0.2s;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .cancel-btn {
            background: #e0e0e0;
            color: #333;
        }
        
        .logout-btn {
            background: #e74c3c;
            color: white;
        }
        
        .loading {
            display: none;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <div class="logout-icon">👋</div>
        
        <h2>Logout Confirmation</h2>
        
        <p class="message">
            Are you sure you want to logout, 
            <span class="username"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></span>?
        </p>
        
        <div class="buttons">
            <a href="?confirm=no" class="btn cancel-btn">Cancel</a>
            <a href="?confirm=yes" class="btn logout-btn" onclick="showLoading()">Yes, Logout</a>
        </div>
        
        <div class="loading" id="loading">
            Logging out...
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
        
        // Auto-redirect cancel to dashboard
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('confirm') === 'no') {
            window.location.href = '../clientside/client_dashboard.php';
        }
    </script>
</body>
</html>