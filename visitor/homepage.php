<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../design/css/homepage.css" />
    <style> </style>
    <title>Navowheels automobile</title>
</head>

<body>
    <!-- this is for nav bar bar  -->
    <div> 
        <?php include 'navbar.php'; ?> 
    </div>
    <div id="hero">
        <img src="../uploads/images/bike.background.jpg" alt="Bike" class="background" />
        <div class="hero-text">
            <h1>
                Welcome to <span class="accent">Navowheels</span><br>
                Ride the <span class="accent2">Future</span>
            </h1>
            <p>Where <span class="accent">power</span> meets <span class="accent2">innovation</span> on two wheels.</p>
            <a href="products.php"><button>Discover More</button></a>
        </div>
    </div>
    <div>
    </div>
   <?php include 'herosection.php'; ?>
   <?php include 'footer.php'; ?>
</body>

</html>