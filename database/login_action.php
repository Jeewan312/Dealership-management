<?php
include '../database/connection.php';

if(isset($_GET['action'], $_GET['id'])){
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if($action === 'confirm'){
        mysqli_query($conn, "UPDATE bookings SET status='Confirmed' WHERE id=$id");
    } elseif($action === 'cancel'){
        mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE id=$id");
    }
}

header('Location: admin_dashboard.php'); // redirect back to dashboard
exit;
?>
