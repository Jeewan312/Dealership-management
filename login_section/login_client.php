<?php
session_start();

/* =======================
   1. Database Connection
   ======================= */
// Based on your image: Database name is "dealership"
$conn = mysqli_connect("localhost", "root", "", "dealership");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* =======================
   2. Login Logic
   ======================= */
if (isset($_POST['login'])) {

    // Sanitize input
    $email    = trim($_POST['gmail']);
    $password = trim($_POST['password']);

    /* ---------- Prepared Statement ---------- */
    // Columns matched to your phpMyAdmin: email, password, user_type
    $sql = "SELECT email, password, user_type FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        /* ---------- Check if User exists ---------- */
        if ($result && mysqli_num_rows($result) === 1) {

            $user = mysqli_fetch_assoc($result);

            /* ---------- Verify Password ---------- */
            // Note: Your DB shows $2y$10... hashes, so password_verify is correct
            if (password_verify($password, $user['password'])) {

                /* ---------- Set Session ---------- */
                $_SESSION['username'] = $user['email'];
                $_SESSION['role']     = $user['user_type'];

                /* ---------- Role Based Redirect ---------- */
                // Logic based on 'user_type' column values (e.g., 'customer')
                switch ($user['user_type']) {

                    case 'admin':
                        header("Location: ../manager/admin.php");
                        break;

                    case 'customer':
                        header("Location: ../clientside/client_dashboard.php");
                        break;

                    default:
                        echo "<script>
                                alert('Unauthorized role: " . $user['user_type'] . "');
                                window.location.href='..clientside/client_dashboard.php';
                              </script>";
                        break;
                }
                exit;

            } else {
                /* ---------- Wrong Password ---------- */
                echo "<script>
                        alert('Invalid email or password');
                        window.location.href='../View/users/login.php';
                      </script>";
                exit;
            }

        } else {
            /* ---------- Email Not Found ---------- */
            echo "<script>
                    alert('No account found with that email');
                    window.location.href='../visitor/homepage.php';
                  </script>";
            exit;
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>