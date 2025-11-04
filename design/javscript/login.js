document.addEventListener("DOMContentLoaded", () => {
  const signupForm = document.getElementById("signupForm");
  const loginForm = document.getElementById("loginForm");

  // Handle Sign Up
  if (signupForm) {
    signupForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const username = document.getElementById("signupUsername").value.trim();
      const password = document.getElementById("signupPassword").value.trim();

      if (username && password) {
        localStorage.setItem("username", username);
        localStorage.setItem("password", password);
        alert("Sign up successful! Please log in.");
        window.location.href = "index.html";
      } else {
        alert("Please fill in all fields!");
      }
    });
  }

  // Handle Login
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const username = document.getElementById("loginUsername").value.trim();
      const password = document.getElementById("loginPassword").value.trim();

      const savedUser = localStorage.getItem("username");
      const savedPass = localStorage.getItem("password");

      if (username === savedUser && password === savedPass) {
        alert("Login successful!");
        window.location.href = "welcome.html";
      } else {
        alert("Invalid username or password!");
      }
    });
  }
});
