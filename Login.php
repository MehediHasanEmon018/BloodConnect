<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) { header("Location: profile.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Login</title>

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
        background-image: url("bg.png");

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
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:50px;
        }

        .login-box{
            width:420px;
            background:white;
            padding:40px;
            border-radius:12px;
            box-shadow:0 5px 20px rgba(0,0,0,.12);
        }

        .login-box h1{
            color:#d62828;
            text-align:center;
            margin-bottom:10px;
        }

        .login-box p{
            text-align:center;
            color:#666;
            margin-bottom:30px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        input{
            width:100%;
            padding:12px;
            margin-bottom:20px;
            border:1px solid #ccc;
            border-radius:6px;
            outline:none;
            font-size:15px;
        }

        input:focus{
            border:2px solid #d62828;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            background:#d62828;
            color:white;
            font-size:16px;
            border-radius:6px;
            cursor:pointer;
            transition:.3s;
        }

        button:hover{
            background:#b71c1c;
        }

        .extra{
            margin-top:20px;
            text-align:center;
        }

        .extra a{
            text-decoration:none;
            color:#d62828;
            font-weight:bold;
        }

        .extra a:hover{
            text-decoration:underline;
        }

        #message{
            text-align:center;
            margin-top:15px;
            font-weight:bold;
        }

        footer{
            background:#d62828;
            color:white;
            text-align:center;
            padding:20px;
        }

        @media(max-width:600px){

            header{
                flex-direction:column;
                gap:15px;
            }

            .login-box{
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
        <a href="Register.php">Register</a>
    </nav>

</header>

<div class="container">

    <div class="login-box">

        <h1>Login</h1>

        <p>Welcome Back to BloodConnect</p>

        <form id="loginForm">

            <label>Email</label>

            <input
                type="email"
                id="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                id="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

        <p id="message"></p>

        <div class="extra">

            Don't have an account?

            <a href="Register.php">
                Register
            </a>

            <br><br>

            <a href="Forgetpass.php">
                Forgot Password?
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

document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("loginForm");
    const message = document.getElementById("message");

    form.addEventListener("submit", function (e) {

        e.preventDefault();

        const email = document.getElementById("email").value.trim().toLowerCase();
        const password = document.getElementById("password").value;

        if (email === "" || password === "") {
            message.style.color = "red";
            message.innerHTML = "Please fill in all fields.";
            return;
        }

        const formData = new FormData();
        formData.append("email", email);
        formData.append("password", password);

        fetch("api/login.php", { method: "POST", body: formData, credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    message.style.color = "green";
                    message.innerHTML = "Login Successful! Redirecting...";
                    setTimeout(function () {
                        window.location.href = "profile.php";
                    }, 1200);
                } else {
                    message.style.color = "red";
                    message.innerHTML = data.message || "Login failed.";
                }
            })
            .catch(function () {
                message.style.color = "red";
                message.innerHTML = "Server error. Please try again.";
            });

    });

    const inputs = document.querySelectorAll("input");

    inputs.forEach(function (input) {

        input.addEventListener("focus", function () {
            input.style.background = "#fff7f7";
        });

        input.addEventListener("blur", function () {
            input.style.background = "white";
        });

    });

});

</script>

</body>
</html>