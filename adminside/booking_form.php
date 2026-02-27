<?php
include 'connection.php';

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $service = $_POST['service'];
    $vehicle = $_POST['vehicle'];
    $date = $_POST['date'];

    $query = "INSERT INTO bookings 
              (customer_name, service_type, vehicle_number, booking_date, status)
              VALUES ('$name','$service','$vehicle','$date','Pending')";

    mysqli_query($conn,$query);

    echo "<script>alert('Booking Submitted Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Service</title>
</head>
<body>

<h2>Book Service</h2>

<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Service Type: <input type="text" name="service" required><br><br>
    Vehicle Number: <input type="text" name="vehicle" required><br><br>
    Date: <input type="date" name="date" required><br><br>

    <button type="submit" name="submit">Book Appointment</button>
</form>

</body>
</html>