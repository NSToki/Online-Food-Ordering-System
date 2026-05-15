<?php
$name = $name ?? "";
$email = $email ?? "";
$phone = $phone ?? "";
$vehicle_type = $vehicle_type ?? "";
$errors = $errors ?? [];
$successMessage = $successMessage ?? "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delivery Agent Register</title>

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container{
            width: 430px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align: center;
            margin-bottom: 22px;
            color: #333;
        }

        .form-group{
            margin-bottom: 15px;
        }

        label{
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        input, select{
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input:focus, select:focus{
            border-color: #007bff;
            outline: none;
        }

        .error{
            color: red;
            font-size: 13px;
        }

        .success{
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }

        .register-btn{
            width: 100%;
            padding: 12px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .register-btn:hover{
            background-color: #0056b3;
        }

        .bottom-text{
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
        }

        .bottom-text a{
            color: #007bff;
            text-decoration: none;
        }

        .bottom-text a:hover{
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="register-container">

    <h2>Delivery Agent Registration</h2>

    <?php if (!empty($successMessage)): ?>
        <p class="success">
            <?php echo htmlspecialchars($successMessage); ?>
        </p>
    <?php endif; ?>

    <form id="registerForm"
          action="../../controllers/DeliveryAgentController.php?action=register"
          method="post">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text"
                   name="name"
                   id="name"
                   value="<?php echo htmlspecialchars($name); ?>">

            <span class="error" id="nameError">
                <?php echo $errors["name"] ?? ""; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   value="<?php echo htmlspecialchars($email); ?>">

            <span class="error" id="emailError">
                <?php echo $errors["email"] ?? ""; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text"
                   name="phone"
                   id="phone"
                   value="<?php echo htmlspecialchars($phone); ?>">

            <span class="error" id="phoneError">
                <?php echo $errors["phone"] ?? ""; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Vehicle Type</label>
            <select name="vehicle_type" id="vehicle_type">
                <option value="">Select Vehicle</option>
                <option value="Bike" <?php if ($vehicle_type == "Bike") echo "selected"; ?>>Bike</option>
                <option value="Cycle" <?php if ($vehicle_type == "Cycle") echo "selected"; ?>>Cycle</option>
                <option value="Motorbike" <?php if ($vehicle_type == "Motorbike") echo "selected"; ?>>Motorbike</option>
                <option value="Car" <?php if ($vehicle_type == "Car") echo "selected"; ?>>Car</option>
            </select>

            <span class="error" id="vehicleError">
                <?php echo $errors["vehicle_type"] ?? ""; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password"
                   name="password"
                   id="password">

            <span class="error" id="passwordError">
                <?php echo $errors["password"] ?? ""; ?>
            </span>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password"
                   name="confirm_password"
                   id="confirm_password">

            <span class="error" id="confirmPasswordError">
                <?php echo $errors["confirm_password"] ?? ""; ?>
            </span>
        </div>

        <button type="submit" class="register-btn">
            Register
        </button>

    </form>

    <div class="bottom-text">
        Already have an account?
        <a href="login.php">Login Here</a>
    </div>

</div>

<script>
    document.getElementById("registerForm").addEventListener("submit", function(event){

        let isValid = true;

        let name = document.getElementById("name").value.trim();
        let email = document.getElementById("email").value.trim();
        let phone = document.getElementById("phone").value.trim();
        let vehicle = document.getElementById("vehicle_type").value;
        let password = document.getElementById("password").value;
        let confirmPassword = document.getElementById("confirm_password").value;

        document.getElementById("nameError").innerHTML = "";
        document.getElementById("emailError").innerHTML = "";
        document.getElementById("phoneError").innerHTML = "";
        document.getElementById("vehicleError").innerHTML = "";
        document.getElementById("passwordError").innerHTML = "";
        document.getElementById("confirmPasswordError").innerHTML = "";

        let namePattern = /^[A-Za-z ]+$/;
        let phonePattern = /^[0-9]{10,15}$/;

        if(name === ""){
            document.getElementById("nameError").innerHTML = "Name is required";
            isValid = false;
        }
        else if(!namePattern.test(name)){
            document.getElementById("nameError").innerHTML = "Only letters and spaces allowed";
            isValid = false;
        }

        if(email === ""){
            document.getElementById("emailError").innerHTML = "Email is required";
            isValid = false;
        }
        else if(!email.includes("@") || !email.includes(".")){
            document.getElementById("emailError").innerHTML = "Enter valid email";
            isValid = false;
        }

        if(phone === ""){
            document.getElementById("phoneError").innerHTML = "Phone is required";
            isValid = false;
        }
        else if(!phonePattern.test(phone)){
            document.getElementById("phoneError").innerHTML = "Phone must be 10 to 15 digits";
            isValid = false;
        }

        if(vehicle === ""){
            document.getElementById("vehicleError").innerHTML = "Vehicle type is required";
            isValid = false;
        }

        if(password === ""){
            document.getElementById("passwordError").innerHTML = "Password is required";
            isValid = false;
        }
        else if(password.length < 8){
            document.getElementById("passwordError").innerHTML = "Password must be at least 8 characters";
            isValid = false;
        }
        else if(!password.includes("@") && !password.includes("#") && !password.includes("$") && !password.includes("%")){
            document.getElementById("passwordError").innerHTML = "Password must contain @, #, $, or %";
            isValid = false;
        }

        if(confirmPassword === ""){
            document.getElementById("confirmPasswordError").innerHTML = "Confirm password is required";
            isValid = false;
        }
        else if(password !== confirmPassword){
            document.getElementById("confirmPasswordError").innerHTML = "Passwords do not match";
            isValid = false;
        }

        if(!isValid){
            event.preventDefault();
        }

    });
</script>

</body>
</html>