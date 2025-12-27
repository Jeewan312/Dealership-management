alert("hello ");

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            
            // Get form values
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Clear previous errors
            clearErrors();
            
            // Validate inputs
            let isValid = true;
            
            // Email validation
            if (!validateEmail(email)) {
                showError('email', 'Please enter a valid email address');
                isValid = false;
            }
            
            // Password validation
            if (!validatePassword(password)) {
                showError('password', 'Password must be at least 6 characters');
                isValid = false;
            }
            
            // If validation passes, submit form to PHP
            if (isValid) {
                // Show loading state
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Logging in...';
                submitBtn.disabled = true;
                
                // Submit form via AJAX
                submitLoginForm(email, password, submitBtn, originalText);
            }
        });
    }
    
    // Real-time validation
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            validateEmailInput(this.value.trim());
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', function() {
            validatePasswordInput(this.value.trim());
        });
    }
});

// Validation functions
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateEmailInput(email) {
    clearError('email');
    
    if (email === '') {
        showError('email', 'Email is required');
        return false;
    }
    
    if (!validateEmail(email)) {
        showError('email', 'Invalid email format');
        return false;
    }
    
    return true;
}

function validatePasswordInput(password) {
    clearError('password');
    
    if (password === '') {
        showError('password', 'Password is required');
        return false;
    }
    
    if (!validatePassword(password)) {
        showError('password', 'Password must be at least 6 characters');
        return false;
    }
    
    return true;
}

// Error handling functions
function showError(fieldId, message) {
    // Remove existing error
    clearError(fieldId);
    
    // Create error element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.id = `${fieldId}-error`;
    errorDiv.textContent = message;
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '5px';
    
    // Insert after input
    const inputField = document.getElementById(fieldId);
    inputField.parentNode.insertBefore(errorDiv, inputField.nextSibling);
    
    // Add error class to input
    inputField.classList.add('error-border');
}

function clearError(fieldId) {
    const errorElement = document.getElementById(`${fieldId}-error`);
    if (errorElement) {
        errorElement.remove();
    }
    
    const inputField = document.getElementById(fieldId);
    if (inputField) {
        inputField.classList.remove('error-border');
    }
}

function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.remove());
    
    const errorBorders = document.querySelectorAll('.error-border');
    errorBorders.forEach(input => input.classList.remove('error-border'));
}

// AJAX form submission
function submitLoginForm(email, password, submitBtn, originalText) {
    // Create FormData object
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    
    // Make AJAX request
    fetch('../database/login.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Login successful - redirect
            window.location.href = data.redirect;
        } else {
            // Show error message
            showLoginError(data.message);
            
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showLoginError('An error occurred. Please try again.');
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

function showLoginError(message) {
    // Create or update error container
    let errorContainer = document.getElementById('login-error-container');
    
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.id = 'login-error-container';
        errorContainer.style.cssText = `
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        `;
        
        const formBox = document.querySelector('.form-box');
        formBox.insertBefore(errorContainer, formBox.firstChild);
    }
    
    errorContainer.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: none; border: none; color: #721c24; cursor: pointer; font-size: 18px;">
                ×
            </button>
        </div>
    `;
}

// Check if user exists (optional - can be used for real-time validation)
function checkUserExists(email) {
    // This would typically call an API endpoint
    return fetch(`../api/check-user.php?email=${encodeURIComponent(email)}`)
        .then(response => response.json())
        .then(data => {
            return data.exists;
        })
        .catch(error => {
            console.error('Error checking user:', error);
            return false;
        });
}
