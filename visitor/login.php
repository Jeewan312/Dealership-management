<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../design/css/login.css" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <div class="auth-container">
    <div class="auth-box">
      <h2><i class="fas fa-sign-in-alt"></i> Login</h2>

      <form id="loginForm">
        <div class="input-group">
          <i class="fas fa-user"></i>
          <input type="text" id="loginUsername" placeholder="Username" required />
        </div>

        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" id="loginPassword" placeholder="Password" required />
        </div>

        <button type="submit" class="btn">Log In</button>

        <p class="switch-text">
          Don’t have an account? <a href="signin.php">Sign Up</a>
        </p>
      </form>
    </div>
  </div>

  <script src="../design/javascript/login.js"></script>
</body>
</html>
