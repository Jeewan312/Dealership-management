<?php
session_start();
include "../database/connection.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = (int) $_GET['id'];
$action = $_GET['action'];

if ($action == 'confirm') {
    $status = 'Confirmed';
} elseif ($action == 'cancel') {
    $status = 'Cancelled';
} else {
    header("Location: pending.php");
    exit();
}

$sql = "UPDATE booking SET status=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $id);
mysqli_stmt_execute($stmt);

header("Location: pending.php");
exit();
