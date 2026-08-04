<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) { header("Location: profile.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BloodConnect - Register</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    color: #333;
    line-height: 1.6;
    overflow-x: hidden;

    background:
        radial-gradient(circle at 10% 0%, rgba(224,48,47,.06) 0%, transparent 45%),
        radial-gradient(circle at 90% 15%, rgba(15,155,142,.05) 0%, transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(244,163,64,.05) 0%, transparent 50%),
        linear-gradient(180deg, #fffaf7 0%, #f8f6f3 40%, #f4f2ef 100%);
    background-attachment: fixed;
}

header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 8%;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

header h2{
    color:#d62828;
}

nav a{
    text-decoration:none;
    color:#333;
    margin:0 12px;
    font-weight:500;
}

nav a:hover{
    color:#d62828;
}

.container{
    display:flex;
    justify-content:center;
    align-items:center;
    padding:50px 20px;
}

.register-box{
    width:700px;
    background:white;
    padding:40px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.12);
}

.register-box h1{
    color:#d62828;
    text-align:center;
    margin-bottom:10px;
}

.register-box p{
    text-align:center;
    color:#666;
    margin-bottom:30px;
}

.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:6px;
    outline:none;
    font-size:15px;
}

input:focus,
select:focus{
    border:2px solid #d62828;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

button{
    width:100%;
    padding:14px;
    background:#d62828;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:17px;
    transition:.3s;
}

button:hover{
    background:#b71c1c;
}

#message{
    margin-top:20px;
    text-align:center;
    font-weight:bold;
}

.extra{
    text-align:center;
    margin-top:20px;
}

.extra a{
    color:#d62828;
    text-decoration:none;
    font-weight:bold;
}

.extra a:hover{
    text-decoration:underline;
}

footer{
    margin-top:40px;
    background:#d62828;
    color:white;
    text-align:center;
    padding:25px;
}

@media(max-width:700px){

    header{
        flex-direction:column;
        gap:15px;
    }

    .grid{
        grid-template-columns:1fr;
    }

    .register-box{
        width:100%;
    }

}

</style>

</head>

<body>

<header>

<div>
<h2>BloodConnect</h2>
<p>Save a Life</p>
</div>

<nav>
<a href="index.php">Home</a>
<a href="Login.php">Login</a>
</nav>

</header>

<div class="container">

<div class="register-box">

<h1>Create Account</h1>

<p>Join BloodConnect and help save lives.</p>

<form id="registerForm">

<div class="grid">

<div class="form-group">
<label>Full Name</label>
<input type="text" id="name" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" id="email" required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="tel" id="phone" required>
</div>

<div class="form-group">
<label>Blood Group</label>

<select id="bloodGroup" required>

<option value="">Select</option>

<option>A+</option>
<option>A-</option>
<option>B+</option>
<option>B-</option>
<option>AB+</option>
<option>AB-</option>
<option>O+</option>
<option>O-</option>

</select>

</div>

<div class="form-group">
<label>Gender</label>

<select id="gender" required>

<option value="">Select</option>

<option>Male</option>
<option>Female</option>
<option>Other</option>

</select>

</div>

<div class="form-group">
<label>Date of Birth</label>
<input type="date" id="dob" required>
</div>

<div class="form-group">
<label>Division</label>
<input type="text" id="division" required>
</div>

<div class="form-group">
<label>District</label>
<input type="text" id="district" required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" id="password" required>
</div>

<div class="form-group">
<label>Confirm Password</label>
<input type="password" id="confirmPassword" required>
</div>

<div class="form-group">
<label>Last Blood Donation Date</label>
<input type="date" id="lastDonation">
</div>

</div>

<button type="submit">
Register
</button>

</form>

<p id="message"></p>

<div class="extra">

Already have an account?

<a href="Login.php">
Login
</a>

</div>

</div>

</div>

<footer>

<p>
© 2026 BloodConnect. All Rights Reserved.
</p>

</footer>

<script>

function calculateAge(dobString) {

    const dob = new Date(dobString);

    if (isNaN(dob.getTime())) return null;

    const today = new Date();

    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    return age;

}

document.getElementById("registerForm").addEventListener("submit", function (e) {

    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim().toLowerCase();
    const phone = document.getElementById("phone").value.trim();
    const bloodGroup = document.getElementById("bloodGroup").value;
    const gender = document.getElementById("gender").value;
    const dob = document.getElementById("dob").value;
    const division = document.getElementById("division").value.trim();
    const district = document.getElementById("district").value.trim();
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("confirmPassword").value;
    const lastDonation = document.getElementById("lastDonation").value;

    const message = document.getElementById("message");

    if (password.length < 6) {
        message.style.color = "red";
        message.innerHTML = "Password must be at least 6 characters.";
        return;
    }

    if (password !== confirm) {
        message.style.color = "red";
        message.innerHTML = "Passwords do not match.";
        return;
    }

    const age = calculateAge(dob);

    if (age === null) {
        message.style.color = "red";
        message.innerHTML = "Please enter a valid date of birth.";
        return;
    }

    if (age < 18) {
        message.style.color = "red";
        message.innerHTML = "You must be at least 18 years old to register as a blood donor.";
        return;
    }

    if (age > 65) {
        message.style.color = "red";
        message.innerHTML = "Blood donors must generally be 65 years old or younger. Please contact support if you believe this is an error.";
        return;
    }

    const phoneDigits = phone.replace(/\D/g, "");

    if (phoneDigits.length < 10) {
        message.style.color = "red";
        message.innerHTML = "Please enter a valid phone number.";
        return;
    }

    const formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("phone", phone);
    formData.append("bloodGroup", bloodGroup);
    formData.append("gender", gender);
    formData.append("dob", dob);
    formData.append("division", division);
    formData.append("district", district);
    formData.append("password", password);
    formData.append("confirmPassword", confirmPassword);
    formData.append("lastDonation", lastDonation);

    fetch("api/register.php", { method: "POST", body: formData, credentials: "same-origin" })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                message.style.color = "green";
                message.innerHTML = "Registration Successful! Redirecting...";
                setTimeout(function () {
                    window.location.href = "profile.php";
                }, 1500);
            } else {
                message.style.color = "red";
                message.innerHTML = data.message || "Registration failed.";
            }
        })
        .catch(function () {
            message.style.color = "red";
            message.innerHTML = "Server error. Please try again.";
        });

});

const inputs = document.querySelectorAll("input,select");

inputs.forEach(function (input) {

    input.addEventListener("focus", function () {
        this.style.background = "#fff7f7";
    });

    input.addEventListener("blur", function () {
        this.style.background = "white";
    });

});

</script>

</body>
</html>