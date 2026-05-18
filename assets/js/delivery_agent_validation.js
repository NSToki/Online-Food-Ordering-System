function validateProfileForm() {
    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let vehicleType = document.getElementById("vehicle_type").value;
    let location = document.getElementById("current_location_text").value.trim();

    if (name === "") {
        alert("Name is required");
        return false;
    }

    if (!/^[a-zA-Z ]+$/.test(name)) {
        alert("Name can contain only letters and spaces");
        return false;
    }

    if (phone === "") {
        alert("Phone is required");
        return false;
    }

    if (!/^[0-9]{10,15}$/.test(phone)) {
        alert("Phone must be 10 to 15 digits");
        return false;
    }

    if (vehicleType === "") {
        alert("Please select vehicle type");
        return false;
    }

    if (location === "") {
        alert("Current location is required");
        return false;
    }

    return true;
}

function validateRegisterForm() {
    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let confirmPassword = document.getElementById("confirm_password").value.trim();
    let vehicleType = document.getElementById("vehicle_type").value;

    if (name === "") {
        alert("Name is required");
        return false;
    }

    if (!/^[a-zA-Z ]+$/.test(name)) {
        alert("Name can contain only letters and spaces");
        return false;
    }

    if (phone === "") {
        alert("Phone is required");
        return false;
    }

    if (!/^[0-9]{10,15}$/.test(phone)) {
        alert("Phone must be 10 to 15 digits");
        return false;
    }

    if (email === "") {
        alert("Email is required");
        return false;
    }

    if (!/^\S+@\S+\.\S+$/.test(email)) {
        alert("Invalid email format");
        return false;
    }

    if (vehicleType === "") {
        alert("Please select vehicle type");
        return false;
    }

    if (password === "") {
        alert("Password is required");
        return false;
    }

    if (password.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }

    if (!/[@#$%]/.test(password)) {
        alert("Password must contain at least one special character: @, #, $, or %");
        return false;
    }

    if (confirmPassword === "") {
        alert("Confirm password is required");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    return true;
}

function validateLoginForm() {
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();

    if (email === "") {
        alert("Email is required");
        return false;
    }

    if (!/^\S+@\S+\.\S+$/.test(email)) {
        alert("Invalid email format");
        return false;
    }

    if (password === "") {
        alert("Password is required");
        return false;
    }

    return true;
}