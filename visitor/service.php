<?php
// Include database connection
include 'navbar.php';
require_once '../database/connection.php';  // adjust path if needed

$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $bike_model = htmlspecialchars($_POST['bike_model'] ?? '');
    $service_type = htmlspecialchars($_POST['service_type'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Basic validation
    if ($name && $email && $service_type) {
        // Prepare and execute insert using the $conn from connection.php
        $stmt = $conn->prepare("INSERT INTO service_requests (name, email, phone, bike_model, service_type, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $bike_model, $service_type, $message);
        
        if ($stmt->execute()) {
            $success_message = "Thank you, $name! Your request has been saved.";
        } else {
            $success_message = "Database error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $success_message = "Please fill in all required fields.";
    }
    
    $conn->close(); // close connection after use
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Servicing | Navowheels</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Play:wght@700&display=swap" rel="stylesheet">
    <style>
        /* ... your existing CSS (same as before, with orange #ff6a00) ... */
        /* Paste the full CSS from the previous answer here */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #0b0f17; color: #e0e0e0; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .features-section { padding: 80px 0; background: #101624; }
        .features-section h2, .products-section h2, .cta-section h2 { font-size: 2.5rem; font-weight: 700; font-family: 'Play', sans-serif; text-align: center; margin-bottom: 1rem; color: #fff; }
        .features-section .subheading, .products-section .subheading { text-align: center; color: #9ca3af; max-width: 600px; margin: 0 auto 50px; font-size: 1.1rem; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .feature-card { background: #1b212f; border-radius: 20px; padding: 30px 20px; text-align: center; border: 1px solid #2a2f3f; transition: transform 0.3s, border-color 0.3s; }
        .feature-card:hover { transform: translateY(-8px); border-color: #ff6a00; }
        .feature-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 20px; border: 2px solid #2a2f3f; }
        .feature-card h3 { font-size: 1.5rem; margin-bottom: 12px; color: #ff6a00; }
        .feature-card p { color: #b0b7ca; }
        .service-packages { padding: 80px 0; background: #0b0f17; }
        .section-title { text-align: center; font-size: 2.5rem; font-weight: 700; font-family: 'Play', sans-serif; margin-bottom: 1rem; }
        .section-title span { color: #ff6a00; }
        .packages-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 50px; }
        .package-card { background: #1b212f; border-radius: 24px; padding: 30px 20px; border: 1px solid #2a2f3f; transition: 0.3s; text-align: center; }
        .package-card:hover { border-color: #ff6a00; transform: translateY(-10px); }
        .package-icon { font-size: 3rem; color: #ff6a00; margin-bottom: 20px; }
        .package-card h3 { font-size: 1.8rem; margin-bottom: 10px; }
        .package-price { font-size: 2rem; font-weight: 800; color: #ff6a00; margin: 15px 0; }
        .package-features { list-style: none; margin: 20px 0; text-align: left; padding-left: 20px; }
        .package-features li { margin: 10px 0; display: flex; align-items: center; gap: 10px; color: #cbd5e1; }
        .package-features i { color: #ff6a00; }
        .products-section { padding: 80px 0; background: #101624; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; }
        .product-card { background: #1b212f; border-radius: 20px; padding: 20px; border: 1px solid #2a2f3f; transition: 0.3s; }
        .product-card:hover { border-color: #ff6a00; }
        .product-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 15px; }
        .product-card h4 { font-size: 1.3rem; margin-bottom: 8px; color: #fff; }
        .product-card p { color: #9ca3af; font-size: 0.95rem; }
        .booking-section { padding: 80px 0; background: #0b0f17; }
        .booking-container { max-width: 700px; margin: 0 auto; background: #1b212f; padding: 40px; border-radius: 30px; border: 1px solid #2a2f3f; }
        .booking-container h2 { text-align: center; margin-bottom: 30px; font-size: 2rem; font-family: 'Play', sans-serif; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #cbd5e1; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; background: #101624; border: 1px solid #2a2f3f; border-radius: 10px; font-family: 'Poppins', sans-serif; color: white; font-size: 1rem; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #ff6a00; }
        .submit-btn { width: 100%; padding: 14px; background: #ff6a00; border: none; border-radius: 40px; font-weight: 700; font-size: 1.2rem; color: #fff; cursor: pointer; transition: background 0.3s; border: 2px solid #ff6a00; }
        .submit-btn:hover { background: transparent; color: #ff6a00; }
        .success-message { background: #1a3b2e; border: 1px solid #2e7d5e; color: #d1fae5; padding: 15px; border-radius: 10px; margin-bottom: 25px; text-align: center; }
        .cta-section { padding: 80px 0; background: linear-gradient(145deg, #0b0f17 0%, #1a1f2e 100%); text-align: center; }
        .cta-section h2 { font-size: 2.5rem; margin-bottom: 20px; }
        .cta-section p { color: #9ca3af; max-width: 600px; margin: 0 auto 30px; font-size: 1.2rem; }
        .cta-btn { display: inline-block; background: #ff6a00; color: #fff; padding: 14px 40px; border-radius: 50px; font-weight: 700; text-decoration: none; transition: 0.3s; border: 2px solid #ff6a00; font-size: 1.1rem; }
        .cta-btn:hover { background: transparent; color: #ff6a00; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255, 106, 0, 0.2); }
    </style>
</head>
<body>

<!-- FEATURES SECTION -->
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

<!-- SERVICE PACKAGES -->
<section class="service-packages">
    <div class="container">
        <h2 class="section-title">Our <span>Service</span> Packages</h2>
        <p class="subheading">Choose the perfect plan for your bike's needs.</p>

        <div class="packages-grid">
            <div class="package-card">
                <div class="package-icon"><i class="fas fa-oil-can"></i></div>
                <h3>Basic Service</h3>
                <div class="package-price">RS:-350</div>
                <ul class="package-features">
                    <li><i class="fas fa-check-circle"></i> Engine oil change</li>
                    <li><i class="fas fa-check-circle"></i> Chain cleaning & lube</li>
                    <li><i class="fas fa-check-circle"></i> Brake inspection</li>
                    <li><i class="fas fa-check-circle"></i> Tire pressure check</li>
                </ul>
            </div>
            <div class="package-card">
                <div class="package-icon"><i class="fas fa-cogs"></i></div>
                <h3>Standard Service</h3>
                <div class="package-price">RS:-690</div>
                <ul class="package-features">
                    <li><i class="fas fa-check-circle"></i> Everything in Basic</li>
                    <li><i class="fas fa-check-circle"></i> Air filter cleaning</li>
                    <li><i class="fas fa-check-circle"></i> Spark plug check</li>
                    <li><i class="fas fa-check-circle"></i> Battery test</li>
                    <li><i class="fas fa-check-circle"></i> Coolant top-up</li>
                </ul>
            </div>
            <div class="package-card">
                <div class="package-icon"><i class="fas fa-tools"></i></div>
                <h3>Premium Service</h3>
                <div class="package-price">RS:-1500</div>
                <ul class="package-features">
                    <li><i class="fas fa-check-circle"></i> Everything in Standard</li>
                    <li><i class="fas fa-check-circle"></i> Valve clearance check</li>
                    <li><i class="fas fa-check-circle"></i> Wheel balancing</li>
                    <li><i class="fas fa-check-circle"></i> Brake pad inspection</li>
                    <li><i class="fas fa-check-circle"></i> Full diagnostic scan</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- POPULAR BIKE PARTS -->
<section class="products-section">
    <div class="container">
        <h2>Our Popular Bike Parts</h2>
        <p class="subheading">High-quality parts to keep your bike running at its best.</p>
        <div class="products-grid">
            <div class="product-card">
                <img src="../uploads/images/brake.jpg" alt="Clutch Plates">
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

<!-- SERVICE REQUEST FORM -->
<section class="booking-section">
    <div class="booking-container">
        <h2>Request a <span style="color:#ff6a00;">Service</span></h2>

        <?php if ($success_message): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="../user/login.php">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" placeholder="John Doe" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="+1 234 567 890">
            </div>
            <div class="form-group">
                <label for="bike_model">Bike Model</label>
                <input type="text" id="bike_model" name="bike_model" placeholder="e.g., Yamaha R15">
            </div>
            <div class="form-group">
                <label for="service_type">Service Package *</label>
                <select id="service_type" name="service_type" required>
                    <option value="">-- Select a service --</option>
                    <option value="Basic Service">Basic Service (RS:-350)</option>
                    <option value="Standard Service">Standard Service (RS:-690)</option>
                    <option value="Premium Service">Premium Service (Rs:-1500)</option>
                    <option value="Custom Repair">Custom Repair / Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message">Additional Notes</label>
                <textarea id="message" name="message" rows="3" placeholder="Describe any specific issues..."></textarea>
            </div>
            <button type="submit" class="submit-btn">Book Appointment <i class="fas fa-calendar-check"></i></button>
        </form>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Upgrade Your Ride?</h2>
        <p>Explore our range of genuine bike parts and enhance your riding experience today.</p>
        <a href="products.php" class="cta-btn">Explore Products</a>
    </div>
</section>
<?php
include 'footer.php';
?>
</body>
</html>