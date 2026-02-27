<?php
include '../database/connection.php';

/* Confirm Booking */
if(isset($_GET['confirm'])){
    $id = $_GET['confirm'];
    mysqli_query($conn,"UPDATE bookings SET status='Confirmed' WHERE id=$id");
    header("Location: dashboard.php");
}

/* Complete Booking */
if(isset($_GET['complete'])){
    $id = $_GET['complete'];
    mysqli_query($conn,"UPDATE bookings SET status='Completed' WHERE id=$id");
    header("Location: dashboard.php");
}

/* Delete Booking */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM bookings WHERE id=$id");
    header("Location: dashboard.php");
}

/* COUNT TOTAL */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM bookings"))['total'];

$completed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as completed FROM bookings WHERE status='Completed'"))['completed'];

$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as pending FROM bookings WHERE status='Pending'"))['pending'];

$result = mysqli_query($conn,"SELECT * FROM bookings ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        .card{
            display:inline-block;
            padding:20px;
            margin:10px;
            width:200px;
            background:#f2f2f2;
            border-radius:10px;
            text-align:center;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        table, th, td{
            border:1px solid #ccc;
            padding:10px;
        }
        .btn{
            padding:5px 10px;
            text-decoration:none;
            color:white;
            border-radius:5px;
        }
        .confirm{ background:green; }
        .complete{ background:blue; }
        .delete{ background:red; }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>

<div class="card">
    <h3><?php echo $total; ?></h3>
    Total Bookings
</div>

<div class="card">
    <h3><?php echo $completed; ?></h3>
    Completed
</div>

<div class="card">
    <h3><?php echo $pending; ?></h3>
    Pending
</div>

<h3>Recent Bookings</h3>

<table>
<tr>
    <th>Name</th>
    <th>Service</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?php echo $row['customer_name']; ?></td>
    <td><?php echo $row['service_type']; ?></td>
    <td><?php echo $row['booking_date']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>

        <?php if($row['status']=="Pending"){ ?>
            <a class="btn confirm" href="?confirm=<?php echo $row['id']; ?>">Confirm</a>
        <?php } ?>

        <?php if($row['status']=="Confirmed"){ ?>
            <a class="btn complete" href="?complete=<?php echo $row['id']; ?>">Complete</a>
        <?php } ?>

        <a class="btn delete" href="?delete=<?php echo $row['id']; ?>">Delete</a>

    </td>
</tr>

<?php } ?>

</table>

</body>
</html>