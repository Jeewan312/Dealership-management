<?php
// Include your DB connection
include "../database/connection.php";

// Get user ID from URL safely
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    die("User ID not provided.");
}

// Fetch user info securely using prepared statement
$stmt = $conn->prepare("SELECT * FROM users WHERE id =$id ");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();
$stmt->close();

if (!$user_info) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information</title>
    <link rel="stylesheet" href="../design/css/signup.css">
</head>
<body>
    <form action="../database/updateUser.php" method="POST" onsubmit="return validateForm()" autocomplete="off">
        <input type="hidden" name="id" value="<?php echo $user_info['id']; ?>">

        <div class="details">
            <h1>Edit User Information</h1>
            <p>Update your details below:</p>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter full name"
                    value="<?php echo htmlspecialchars($user_info['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="example@gmail.com"
                    value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" placeholder="98XXXXXXXX"
                    value="<?php echo htmlspecialchars($user_info['phone']); ?>">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" placeholder="Enter address"
                    value="<?php echo htmlspecialchars($user_info['address']); ?>">
            </div>

            <div class="button-container">
                <button type="submit" name="submit">Update</button>
            </div>
        </div>
    </form>

    <script src="../assets/javascript/pro_register.js"></script>
</body>
</html>
