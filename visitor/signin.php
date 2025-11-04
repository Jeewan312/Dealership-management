<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="../design/css/signin.css">
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2>Registration Form</h2>
      <form id="registerForm">
        <div class="input-box">
          <input type="text" id="name" required>
          <label>Name</label>
        </div>

        <div class="input-box">
          <input type="email" id="email" required>
          <label>Email Address</label>
        </div>

        <div class="input-box">
          <input type="password" id="password" required>
          <label>Password</label>
        </div>

        <div class="input-box">
          <input type="password" id="confirmPassword" required>
          <label>Confirm Password</label>
        </div>

       

        <button type="submit" class="btn">Create Account</button>

        <p class="signin">Already have an account? <a href="login.php">Log In</a></p>
      </form>
    </div>
  </div>
  <script src="../design/javascript/signin.js"></script>
</body>
</html>
