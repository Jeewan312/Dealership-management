function formValidate() {
    // Get input values
    const fullname = document.getElementById("fullname").value.trim();
    const email = document.getElementById("email").value.trim();
    const contact = document.getElementById("contact").value.trim();
    const address = document.getElementById("address").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmpassword = document.getElementById("confirmpassword").value.trim();

    // Full Name Validation
    if (fullname.length < 3) {
        alert("Full name must be at least 3 characters long.");
        return false;
    }

    // Contact Validation (Nepali 10-digit number)
    const contactPattern = /^(98|97)\d{8}$/;
    if (!contactPattern.test(contact)) {
        alert("Contact number must be 10 digits and start with 97 or 98.");
        return false;
    }

    // Email Validation (Gmail only)
    const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid Gmail address.");
        return false;
    }

    // Address Validation
    if (address.length < 3) {
        alert("Address must be at least 3 characters long.");
        return false;
    }

    // Password Validation
    const passPattern = /^(?=.[A-Z])(?=.\d)(?=.[@$!%?&]).{8,}$/;
    if (!passPattern.test(password)) {
        alert("Password must be at least 8 characters, contain one uppercase letter, one number, and one special character.");
        return false;
    }

    // Confirm Password Validation
    if (password !== confirmpassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true; // All validations passed
}