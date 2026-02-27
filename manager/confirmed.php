<?php
session_start();
include "../database/connection.php";

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT b.*, u.name
          FROM booking b
          JOIN users u ON b.user_id = u.id
          WHERE b.status = 'Confirmed'
          ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<h2>Confirmed Bookings</h2>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>User</th>
    <th>Vehicle</th>
    <th>Service</th>
    <th>Date</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['name']; ?></td>
    <td><?= $row['vehicle_make']; ?> <?= $row['vehicle_model']; ?></td>
    <td><?= $row['service_type']; ?></td>
    <td><?= $row['appointment_date']; ?></td>
</tr>
<?php endwhile; ?>
</table>
