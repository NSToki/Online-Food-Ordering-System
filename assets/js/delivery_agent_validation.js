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