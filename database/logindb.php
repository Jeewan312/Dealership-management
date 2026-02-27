<?php
session_start();
require_once 'connection.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please fill in all fields";
        header("Location: ../login.php");
        exit();
    }
    
    // Prepare SQL statement
    $sql = "SELECT id, name, email, password, user_type FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['user_type'];
            
            // Redirect based on user role
            if ($user['user_type'] == 'admin') {
                header("Location: ../manager/admin.php");
            } else {
                header("Location: ../clientside/client_dashboard.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password";
        header("Location: ../login.php");
        exit();
    }
    
    $stmt->close();
} else {
    // If accessed directly, redirect to login
    header("Location: ../login.php");
    exit();
}

$conn->close();
?>