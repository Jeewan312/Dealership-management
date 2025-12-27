<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Authentication</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../design/css/client_login.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Authentication</h1>
            <p>Login to your account</p>
        </div>
        
        <div class="content">
            <div class="form-container">
                <form class="form" id="login-form">
                    <h2 class="form-title">Login</h2>
                    
                    <div class="input-group">
                        <label for="login-email">Email Address *</label>
                        <input type="text" id="login-email" placeholder="Enter your email">
                        <div class="error-message" id="login-email-error">Valid email is required</div>
                        <div class="success-message" id="login-email-success">Email format is valid</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="login-password">Password *</label>
                        <div class="password-container">
                            <input type="password" id="login-password" placeholder="Enter your password">
                            <button type="button" class="password-toggle" id="login-password-toggle">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="login-password-error">Password is required</div>
                    </div>
                    
                    <button type="submit" class="btn">Login</button>
                    
                    <div class="form-footer">
                        <p>Don't have an account? <a href="client_signup.php">Sign up here</a></p>
                        <p><a href="../visitor/homepage.php">← Back to main page</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../access/javascript/client_login.js"></script>
</body>
</html>