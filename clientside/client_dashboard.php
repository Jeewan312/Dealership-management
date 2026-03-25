<?php
session_start();
include '../database/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";

/* =========================
   INSERT BOOKING
========================= */
if (isset($_POST['book_service'])) {

    $make = $_POST['vehicle_make'];
    $model = $_POST['vehicle_model'];
    $year = $_POST['vehicle_year'];
    $plate = $_POST['license_plate'];
    $color = $_POST['vehicle_color'];
    $vin = $_POST['vin'];
    $issues = $_POST['issues'];
    $service = $_POST['service_type'];
    $date = $_POST['appointment_date'];

    $sql = "INSERT INTO bookings 
            (user_id, vehicle_make, vehicle_model, vehicle_year, license_plate, vehicle_color, vin, issues, service_type, appointment_date, status)
            VALUES ('$user_id','$make','$model','$year','$plate','$color','$vin','$issues','$service','$date','pending')";

    if (mysqli_query($conn, $sql)) {
        $success = "Booking Successful!";
    }
}

/* =========================
   DELETE BOOKING
========================= */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM bookings WHERE id='$id' AND user_id='$user_id'");
}

/* =========================
   FETCH BOOKINGS
========================= */
$result = mysqli_query($conn, "SELECT * FROM bookings WHERE user_id='$user_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Client Dashboard</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f6f9;
}
.sidebar{
    width:220px;
    height:100vh;
    background:#111827;
    color:white;
    position:fixed;
    padding:20px;
}
.sidebar h2{
    text-align:center;
}
.sidebar a{
    display:block;
    color:white;
    padding:10px;
    margin-top:10px;
    text-decoration:none;
    border-radius:5px;
}
.sidebar a:hover{
    background:#2563eb;
}
.main{
    margin-left:240px;
    padding:30px;
}
.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:30px;
}
input,select,textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:6px;
}
button{
    padding:10px 20px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
.btn-primary{
    background:#2563eb;
    color:white;
}
.btn-danger{
    background:#dc2626;
    color:white;
}
table{
    width:100%;
    border-collapse:collapse;
}
table th, table td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
.success{
    background:#16a34a;
    color:white;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>AutoCare</h2>
    <a href="#book">Book Service</a>
    <a href="#my">My Bookings</a>
    <a href="?logout=1">Logout</a>
</div>

<div class="main">

<?php if($success){ ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

<!-- ================= BOOK SERVICE ================= -->
<div class="card" id="book">
<h3>Book Service</h3>

<form method="POST">

<label>Vehicle Make *</label>
<select name="vehicle_make" required>
<option value="">Select</option>
<option>Toyota</option>
<option>Honda</option>
<option>Ford</option>
<option>BMW</option>
</select>

<label>Vehicle Model *</label>
<input type="text" name="vehicle_model" required>

<label>Year *</label>
<input type="number" name="vehicle_year" required>

<label>License Plate *</label>
<input type="text" name="license_plate" required>

<label>Color</label>
<input type="text" name="vehicle_color">

<label>VIN</label>
<input type="text" name="vin">

<label>Issues</label>
<textarea name="issues"></textarea>

<label>Service Type *</label>
<select name="service_type" required>
<option value="">Select</option>
<option>Oil Change</option>
<option>Brake Service</option>
<option>Full Service</option>
</select>

<label>Appointment Date *</label>
<input type="date" name="appointment_date" required>

<button type="submit" name="book_service" class="btn-primary">Book Now</button>

</form>
</div>

<!-- ================= MY BOOKINGS ================= -->
<div class="card" id="my">
<h3>My Bookings</h3>

<table>
<tr>
<th>ID</th>
<th>Vehicle</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['vehicle_make']." ".$row['vehicle_model']; ?></td>
<td><?php echo $row['appointment_date']; ?></td>
<td><?php echo $row['status']; ?></td>
<td>
<a href="?delete=<?php echo $row['id']; ?>" 
onclick="return confirm('Delete booking?')" 
class="btn-danger" style="padding:5px 10px;">Delete</a>
</td>
</tr>
<?php } ?>

</table>
</div>

</div>

</body>
</html>