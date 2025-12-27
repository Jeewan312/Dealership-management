<?php
session_start();
require_once 'connection.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Email and password are required!";
        header("Location: ../login.php");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = "Invalid email format!";
        header("Location: ../login.php");
        exit();
    }
    
    // Database check
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, email, password, user_type FROM users WHERE email = :email OR username = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['logged_in'] = true;
                
                // Set last login time
                $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
                $updateStmt->bindParam(':id', $user['id']);
                $updateStmt->execute();
                
                // Redirect based on user type
                if ($user['user_type'] == 'admin') {
                    header("Location: ../manager/admin.php");
                } else {
                    header("Location: ../visitor/homepage.php");
                }
                exit();
            } else {
                $_SESSION['login_error'] = "Invalid password!";
                header("Location: ../login.php");
                exit();
            }
        } else {
            $_SESSION['login_error'] = "No account found with this email/username!";
            header("Location: ../login.php");
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
        header("Location: ../login.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Invalid request method!";
    header("Location: ../login.php");
    exit();
}
?>