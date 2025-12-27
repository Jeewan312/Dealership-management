// Password visibility toggle
function setupPasswordToggle(passwordFieldId, toggleButtonId) {
    const passwordField = document.getElementById(passwordFieldId);
    const toggleButton = document.getElementById(toggleButtonId);
    
    toggleButton.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
    });
}

// Validation functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Real-time validation for login email
const loginEmailField = document.getElementById('login-email');
const loginEmailError = document.getElementById('login-email-error');
const loginEmailSuccess = document.getElementById('login-email-success');

if (loginEmailField) {
    loginEmailField.addEventListener('input', function() {
        const email = this.value;
        
        if (!email) {
            loginEmailError.style.display = 'none';
            loginEmailSuccess.style.display = 'none';
            this.style.borderColor = '#ddd';
            return;
        }
        
        if (validateEmail(email)) {
            loginEmailError.style.display = 'none';
            loginEmailSuccess.style.display = 'block';
            this.style.borderColor = '#2ecc71';
        } else {
            loginEmailError.style.display = 'block';
            loginEmailSuccess.style.display = 'none';
            this.style.borderColor = '#e74c3c';
        }
    });
}

// Form submission
if (document.getElementById('login-form')) {
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        let isValid = true;
        
        // Reset errors
        document.getElementById('login-email-error').style.display = 'none';
        document.getElementById('login-password-error').style.display = 'none';
        
        // Validate email
        if (!email || !validateEmail(email)) {
            document.getElementById('login-email-error').style.display = 'block';
            document.getElementById('login-email').style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            document.getElementById('login-email').style.borderColor = '#2ecc71';
        }
        
        // Validate password
        if (!password) {
            document.getElementById('login-password-error').style.display = 'block';
            document.getElementById('login-password').style.borderColor = '#e74c3c';
            isValid = false;
        } else {
            document.getElementById('login-password').style.borderColor = '#2ecc71';
        }
        
        if (isValid) {
            alert('Login successful! (This is a demo)');
            // In a real application, you would submit the form to a server
        }
    });
}

// Initialize password toggle
document.addEventListener('DOMContentLoaded', function() {
    setupPasswordToggle('login-password', 'login-password-toggle');
});