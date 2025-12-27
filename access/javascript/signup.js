alert("hello");
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('full-name');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const address = document.getElementById('address');
        const password = document.getElementById('password');
        const cpassword = document.getElementById('confirm-password');

        let isValid = true;

        // Clear previous errors
        document.querySelectorAll('.client-error').forEach(el => el.remove());

        /* ---------- NAME ---------- */
        if (!name.value.trim()) {
            showError(name, 'Full name is required');
            isValid = false;
        } else if (name.value.trim().length < 3) {
            showError(name, 'Name must be at least 3 characters');
            isValid = false;
        }

        /* ---------- EMAIL ---------- */
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            showError(email, 'Email is required');
            isValid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            showError(email, 'Enter a valid email address');
            isValid = false;
        }

        /* ---------- NEPALI PHONE NUMBER ---------- */
        // FIXED: Phone must be exactly 10 digits and start with 98 or 97
        const nepaliPhoneRegex = /^(98|97)\d{8}$/; // Changed from the previous regex

        if (!phone.value.trim()) {
            showError(phone, 'Phone number is required');
            isValid = false;
        } else {
            // Remove any spaces, dashes, etc.
            const cleanPhone = phone.value.trim().replace(/[\s\-\(\)]/g, '');
            
            // Check if it's exactly 10 digits and starts with 98 or 97
            if (!/^\d{10}$/.test(cleanPhone)) {
                showError(phone, 'Phone number must be exactly 10 digits');
                isValid = false;
            } else if (!nepaliPhoneRegex.test(cleanPhone)) {
                showError(phone, 'Phone number must start with 98 or 97');
                isValid = false;
            }
        }

        /* ---------- ADDRESS ---------- */
        if (!address.value.trim()) {
            showError(address, 'Address is required');
            isValid = false;
        }

        /* ---------- PASSWORD ---------- */
        // FIXED: Password must be at least 8 digits with regex
        // This regex requires at least 8 characters with at least one uppercase, 
        // one lowercase, one number, and one special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (!password.value) {
            showError(password, 'Password is required');
            isValid = false;
        } else if (password.value.length < 8) {
            showError(password, 'Password must be at least 8 characters');
            isValid = false;
        } else if (!passwordRegex.test(password.value)) {
            showError(password, 'Password must contain: uppercase, lowercase, number, and special character (@$!%*?&)');
            isValid = false;
        }

        /* ---------- CONFIRM PASSWORD ---------- */
        if (!cpassword.value) {
            showError(cpassword, 'Please confirm your password');
            isValid = false;
        } else if (password.value !== cpassword.value) {
            showError(cpassword, 'Passwords do not match');
            isValid = false;
        }

        /* ---------- SUBMIT ---------- */
        if (isValid) {
            form.submit(); // ✅ REAL PHP SUBMIT
        }
    });

    function showError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'client-error';
        errorDiv.style.color = 'red';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;

        input.parentNode.appendChild(errorDiv);
    }
    
    // Add real-time validation for better UX
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            const errorDiv = this.parentNode.querySelector('.client-error');
            if (errorDiv) errorDiv.remove();
        });
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const errorDiv = this.parentNode.querySelector('.client-error');
            if (errorDiv) errorDiv.remove();
        });
    }
});