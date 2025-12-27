<?php
require_once 'connection.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

$query = "SELECT * FROM users WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($status_filter != 'all') {
    $query .= " AND status = '$status_filter'";
}

$query .= " ORDER BY created_at DESC";
$data = mysqli_query($conn, $query);
$total = mysqli_num_rows($data);

// Statistics
$stats_query = "SELECT 
    COUNT(*) AS total_users,
    SUM(CASE WHEN status='active' THEN 1 ELSE 0 END) AS active_users,
    SUM(CASE WHEN status='blocked' THEN 1 ELSE 0 END) AS blocked_users,
    SUM(CASE WHEN status='inactive' THEN 1 ELSE 0 END) AS inactive_users
    FROM users";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>
