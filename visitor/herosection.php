<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../design/css/herosection.css">
</head>
<body>
<section class="features-section">
    <div class="container">
        <h2>Why Choose Navowheels?</h2>
        <p class="subheading">Premium quality, innovation, and reliability for every ride.</p>
        <div class="features-grid">
            <div class="feature-card">
                <img src="../uploads/images/footer/parts.jpg" alt="Genuine Parts">
                <h3>Genuine Spare Parts</h3>
                <p>We provide 100% genuine bike parts for optimal performance and safety.</p>
            </div>
            <div class="feature-card">
                <img src="../uploads/images/footer/service.jpg" alt="Service Network">
                <h3>Wide Service Network</h3>
                <p>Authorized dealers and service centers across the country for easy access.</p>
            </div>
            <div class="feature-card">
                <img src="../uploads/images/footer/customer-care.jpg" alt="Customer Support">
                <h3>Customer Support</h3>
                <p>Expert support to assist you in choosing the right parts and maintenance advice.</p>
            </div>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container">
        <h2>Our Popular Bike Parts</h2>
        <p class="subheading">High-quality parts to keep your bike running at its best.</p>
        <div class="products-grid">
            <div class="product-card">
                <img src="../uploads/images/brake.jpg" alt="Brake Shoes">
                <h4>Brake Shoes</h4>
                <p>Durable and reliable brake shoes for maximum safety.</p>
            </div>
            <div class="product-card">
               <?php
include '../database/connection.php'; // make sure path is correct

$sql = "SELECT * FROM images";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = htmlspecialchars($row['id']);
        $name = htmlspecialchars($row['image_name']);
        $imagePath = !empty($row['image_path']) ? $row['image_path'] : '../uploads/default.jpg';
        
        // Each image wrapped in its own container
        echo '<div class="images-item">';
        echo '  <div class="image-container">';
        echo '      <img src="'.$imagePath.'" alt="'.$name.'" loading="lazy">';
        echo '  </div>';
        echo '  <h3>'.$name.'</h3>';
        echo '</div>';
    }
} else {
    echo '<p>No images found.</p>';
}
?>

                <h4>Chain & Sprocket Kits</h4>
                <p>Precision-engineered kits for smooth power transfer.</p>
            </div>
            <div class="product-card">
                <img src="../uploads/images/clutch.jpg" alt="Clutch Plates">
                <h4>Clutch Plates</h4>
                <p>High-quality clutch plates for better engine control.</p>
            </div>
            <div class="product-card">
                <img src="../uploads/images/pistons.jpg" alt="Engine Pistons">
                <h4>Engine Pistons</h4>
                <p>Premium pistons to ensure optimal engine performance.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>Ready to Upgrade Your Ride?</h2>
        <p>Explore our range of genuine bike parts and enhance your riding experience today.</p>
        <a href="products.php" class="cta-btn">Explore Products</a>
    </div>
</section>
    
</body>
</html>
