<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - User Authentication</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../design/css/client_signup.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Authentication</h1>
            <p>Create a new account</p>
        </div>
        
        <div class="content">
            <div class="form-container">
                <form class="form" id="signup-form">
                    <h2 class="form-title">Create Account</h2>
                    
                    <div class="input-group">
                        <label for="full-name">Full Name *</label>
                        <input type="text" id="full-name" placeholder="Enter your full name">
                        <div class="error-message" id="full-name-error">Full name is required</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="email">Email Address *</label>
                        <input type="text" id="email" placeholder="Enter your email">
                        <div class="error-message" id="email-error">Valid email is required</div>
                        <div class="success-message" id="email-success">Email format is valid</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="phone">Phone Number *</label>
                        <input type="text" id="phone" placeholder="Enter your phone number">
                        <div class="error-message" id="phone-error">Valid phone number is required</div>
                        <div class="success-message" id="phone-success">Phone format is valid</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="address">Address *</label>
                        <input type="text" id="address" placeholder="Enter your address">
                        <div class="error-message" id="address-error">Address is required</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">Password *</label>
                        <div class="password-container">
                            <input type="password" id="password" placeholder="Create a password">
                            <button type="button" class="password-toggle" id="signup-password-toggle">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="password-error">Password must be at least 8 characters</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="confirm-password">Confirm Password *</label>
                        <div class="password-container">
                            <input type="password" id="confirm-password" placeholder="Confirm your password">
                            <button type="button" class="password-toggle" id="confirm-password-toggle">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="confirm-password-error">Passwords do not match</div>
                    </div>
                    
                    <button type="submit" class="btn">Create Account</button>
                    
                    <div class="form-footer">
                        <p>Already have an account? <a href="client_login.php">Login here</a></p>
                        <p><a href="../visitor/homepage.php">← Back to main page</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../access/javascript/client_signup.js"></script>
</body>
</html>