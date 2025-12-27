<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get & escape inputs
    $name      = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $email     = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $phone     = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
    $address   = mysqli_real_escape_string($conn, trim($_POST['address'] ?? ''));
    $password  = $_POST['password'] ?? '';
    $cpassword = $_POST['cpassword'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password) || empty($cpassword)) {
        echo "All fields are required";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit();
    }

    // Clean phone number
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (!preg_match('/^(98|97|96)\d{8}$/', $phone)) {
        echo "Invalid Nepali phone number";
        exit();
    }

    if ($password !== $cpassword) {
        echo "Passwords do not match";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters";
        exit();
    }

    // Check email exists
    $emailCheck = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($emailCheck) > 0) {
        echo "Email already registered";
        exit();
    }

    // Check phone exists
    $phoneCheck = mysqli_query($conn, "SELECT id FROM users WHERE phone='$phone'");
    if (mysqli_num_rows($phoneCheck) > 0) {
        echo "Phone number already registered";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $insertQuery = "
        INSERT INTO users (name, email, phone, address, password, user_type, created_at)
        VALUES ('$name', '$email', '$phone', '$address', '$hashedPassword', 'customer', NOW())
    ";

    if (mysqli_query($conn, $insertQuery)) {
        echo "User registered successfully";
    } else {
        echo "Database error: " . mysqli_error($conn);
    }

} else {
    echo "Invalid request method";
}
?>
