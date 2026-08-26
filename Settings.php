<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$me = getCurrentUser($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BloodConnect | Settings</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>

/* ============================
   BloodConnect Design Tokens
   ============================ */
:root {
    --crimson: #c8102e;
    --crimson-dark: #8f0b21;
    --crimson-soft: #fdeceb;
    --ink: #23222a;
    --ink-soft: #6b6873;
    --paper: #faf6f3;
    --panel: #ffffff;
    --sand: #f3ece7;
    --mist: #e9e1db;
    --teal: #0e7c74;
    --teal-soft: #e6f3f1;

    --font-display: "Fraunces", Georgia, serif;
    --font-body: "Inter", Arial, Helvetica, sans-serif;

    --radius-lg: 20px;
    --radius-md: 14px;
    --radius-sm: 10px;

    --shadow-panel: 0 10px 30px -12px rgba(35, 34, 42, .12), 0 2px 8px rgba(35, 34, 42, .05);
    --shadow-soft: 0 4px 14px rgba(35, 34, 42, .06);
}

    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: var(--font-body);
}


body{
    color: var(--ink);
    line-height: 1.6;
    overflow-x: hidden;

    background:
        radial-gradient(circle at 10% 0%, rgba(224,48,47,.06) 0%, transparent 45%),
        radial-gradient(circle at 90% 15%, rgba(15,155,142,.05) 0%, transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(244,163,64,.05) 0%, transparent 50%),
        linear-gradient(180deg, #fffaf7 0%, #f8f6f3 40%, #f4f2ef 100%);
    background-attachment: fixed;
}



.container{

    display:flex;

    min-height:100vh;

}


/* ============================
   Sidebar
   ============================ */
.sidebar {
    width: 270px;
    background: var(--panel);
    border-right: 1px solid var(--mist);
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 26px 22px;
    overflow-y: auto;
}

.logo {
    text-align: center;
    margin-bottom: 22px;
}

.logo h2 {
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 24px;
    letter-spacing: .2px;
    color: var(--crimson);
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 4px;
}

.logo h2 i {
    font-size: 19px;
}

.logo p {
    color: var(--ink-soft);
    font-size: 12.5px;
    letter-spacing: .4px;
    text-transform: uppercase;
}

/* signature element: a quiet pulse line beneath the wordmark,
   standing in for BloodConnect's vitals / life-signal theme */
.pulse-divider {
    width: 100%;
    height: 22px;
    margin-top: 16px;
    opacity: .55;
}

.pulse-divider path {
    fill: none;
    stroke: var(--crimson);
    stroke-width: 1.6;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-dasharray: 220;
    stroke-dashoffset: 220;
    animation: draw-pulse 1.6s ease-out forwards .2s;
}

@keyframes draw-pulse {
    to { stroke-dashoffset: 0; }
}

.sidebar ul{

    list-style:none;

}



.sidebar ul li{

    margin-bottom: 6px;

}



.sidebar ul li a {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    color: var(--ink-soft);
    font-size: 14.5px;
    font-weight: 500;
    padding: 13px 14px;
    border-radius: var(--radius-sm);
    transition: background .2s ease, color .2s ease;
}

.sidebar ul li a i {
    width: 18px;
    text-align: center;
    color: #c9c5c0;
    transition: color .2s ease;
}

.sidebar ul li a:hover {
    background: var(--crimson-soft);
    color: var(--crimson-dark);
}

.sidebar ul li a:hover i {
    color: var(--crimson);
}

.sidebar ul li.active a {
    background: var(--crimson);
    color: #fff;
    box-shadow: 0 6px 14px -6px rgba(200, 16, 46, .55);
}

.sidebar ul li.active a i {
    color: #fff;
}



#logoutBtn {
    margin-top: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 13px;
    border: 1px solid var(--mist);
    border-radius: var(--radius-sm);
    background: var(--panel);
    color: var(--ink);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all .2s ease;
}



#logoutBtn:hover {
    background: var(--crimson);
    border-color: var(--crimson);
    color: #fff;
}



/* ============================
   Main content
   ============================ */
.main-content{

    margin-left: 270px;

    width: calc(100% - 270px);

    padding:30px;

}


header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:30px;

}



.header-left{

    display:flex;

    align-items:center;

    gap:20px;

}



.header-left h1 {
    font-family: var(--font-display);
    font-weight: 600;
    font-size:30px;
    letter-spacing: .2px;
}



#menuBtn {
    display: none;
    border: 1px solid var(--mist);
    background: var(--panel);
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    font-size: 17px;
    color: var(--ink);
    cursor: pointer;
}



.header-right{

    display:flex;

    align-items:center;

    gap:20px;

}



.header-right i{

    font-size:22px;

    cursor:pointer;

}



.header-right img{

    width:48px;

    height:48px;

    border-radius:50%;

    object-fit:cover;

}


.settings-container{

    display:flex;

    flex-direction:column;

    gap:25px;

}


.settings-card{

    background:#ffffff;

    padding:30px;

    border-radius:15px;

    box-shadow:0 4px 12px rgba(0,0,0,.08);

}



.settings-card h2{

    color:#d62828;

    margin-bottom:25px;

    display:flex;

    align-items:center;

    gap:12px;

    font-size:22px;

}


.profile-settings{

    display:flex;

    align-items:center;

    gap:20px;

    margin-bottom:25px;

}



.profile-settings img{

    width:90px;

    height:90px;

    border-radius:50%;

    object-fit:cover;

    border:4px solid #ffe5e5;

}



.profile-settings h3{

    font-size:22px;

    margin-bottom:8px;

}



.profile-settings p{

    color:#777;

}



#editProfileBtn{

    padding:12px 25px;

    border:none;

    border-radius:8px;

    background:#d62828;

    color:white;

    cursor:pointer;

    transition:.3s;

}



#editProfileBtn:hover{

    background:#b91d1d;

}


.setting-row{

    display:flex;

    justify-content:space-between;

    align-items:center;

    padding:20px 0;

    border-bottom:1px solid #eee;

}



.setting-row:last-child{

    border-bottom:none;

}



.setting-row h3{

    margin-bottom:7px;

    font-size:17px;

}



.setting-row p{

    color:#777;

    font-size:14px;

}


.switch{

    position:relative;

    width:55px;

    height:28px;

}



.switch input{

    opacity:0;

    width:0;

    height:0;

}



.slider{

    position:absolute;

    cursor:pointer;

    top:0;

    left:0;

    right:0;

    bottom:0;

    background:#ccc;

    border-radius:30px;

    transition:.3s;

}



.slider:before{

    content:"";

    position:absolute;

    width:22px;

    height:22px;

    left:3px;

    bottom:3px;

    background:white;

    border-radius:50%;

    transition:.3s;

}



.switch input:checked + .slider{

    background:#d62828;

}



.switch input:checked + .slider:before{

    transform:translateX(27px);

}


.info-grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fit,minmax(200px,1fr));

    gap:20px;

}



.info-grid div{

    background:#fafafa;

    padding:20px;

    border-radius:10px;

}



.info-grid strong{

    display:block;

    color:#d62828;

    margin-bottom:10px;

}



.info-grid p{

    color:#666;

}


.password-box{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

    gap:20px;

    margin-bottom:25px;

}



.password-box input{

    padding:15px;

    border:1px solid #ddd;

    border-radius:8px;

    outline:none;

    font-size:15px;

}



.password-box input:focus{

    border-color:#d62828;

}



#changePasswordBtn{

    padding:13px 25px;

    border:none;

    border-radius:8px;

    background:#d62828;

    color:#fff;

    cursor:pointer;

    transition:.3s;

}



#changePasswordBtn:hover{

    background:#b91d1d;

}


.danger-zone{

    background:#fff;

    padding:30px;

    border-radius:15px;

    border:1px solid #ffcccc;

    box-shadow:0 4px 12px rgba(0,0,0,.08);

}



.danger-zone h2{

    color:#d62828;

    margin-bottom:15px;

}



.danger-zone p{

    color:#777;

    margin-bottom:25px;

}



#deleteAccountBtn{

    padding:14px 28px;

    border:none;

    border-radius:8px;

    background:#8b0000;

    color:white;

    cursor:pointer;

    transition:.3s;

}



#deleteAccountBtn:hover{

    background:#5f0000;

}


.save-area{

    display:flex;

    justify-content:flex-end;

    margin-top:10px;

}



#saveSettings{

    padding:15px 35px;

    border:none;

    border-radius:8px;

    background:#d62828;

    color:white;

    cursor:pointer;

    font-size:16px;

    transition:.3s;

}



#saveSettings:hover{

    background:#b91d1d;

}


footer{

    background:#d62828;

    color:white;

    text-align:center;

    padding:35px;

    border-radius:15px;

    margin-top:25px;

}



footer h3{

    margin-bottom:10px;

}



footer p{

    margin-top:8px;

}


::-webkit-scrollbar{

    width:8px;

}



::-webkit-scrollbar-thumb{

    background:#d62828;

    border-radius:20px;

}



::-webkit-scrollbar-track{

    background:#f1f1f1;

}


@media(max-width:900px){


    .sidebar{

        left: -270px;

        transition: left .3s ease;

        z-index: 999;

        box-shadow: var(--shadow-panel);

    }


    .sidebar.show{

        left:0;

    }


    .main-content{

        width:100%;

        margin-left:0;

    }


    #menuBtn{

        display:block;

    }


    header{

        flex-direction:column;

        align-items:flex-start;

        gap:20px;

    }


}



@media(max-width:600px){


    .main-content{

        padding:20px;

    }



    .profile-settings{

        flex-direction:column;

        text-align:center;

    }



    .setting-row{

        flex-direction:column;

        align-items:flex-start;

        gap:15px;

    }



    .save-area{

        justify-content:center;

    }



    .password-box{

        grid-template-columns:1fr;

    }



    .header-right{

        display:none;

    }


}

</style>

</head>


<body>


<div class="container">


<aside class="sidebar">


<div class="logo">

<h2>
<i class="fa-solid fa-droplet"></i>
BloodConnect
</h2>

<p>
Save Lives Together
</p>

<svg class="pulse-divider" viewBox="0 0 260 22" preserveAspectRatio="none" aria-hidden="true">
<path d="M0 11 H90 L102 2 L114 20 L126 6 L136 11 H260" />
</svg>

</div>



<ul>


<li>

<a href="home.php">

<i class="fa-solid fa-house"></i>

Home

</a>

</li>



<li>

<a href="profile.php">

<i class="fa-solid fa-user"></i>

Profile

</a>

</li>



<li>

<a href="createpost.php">

<i class="fa-solid fa-square-plus"></i>

Create Post

</a>

</li>



<li>

<a href="donors.php">

<i class="fa-solid fa-users"></i>

Donors

</a>

</li>



<li>

<a href="blood-requests.php">

<i class="fa-solid fa-tint"></i>

Blood Requests

</a>

</li>



<li>

<a href="emergency-requests.php">

<i class="fa-solid fa-triangle-exclamation"></i>

Emergency

</a>

</li>



<li>

<a href="Chat.php">

<i class="fa-solid fa-comments"></i>

Messages

</a>

</li>



<li class="active">

<a href="Settings.php">

<i class="fa-solid fa-gear"></i>

Settings

</a>

</li>


</ul>



<button id="logoutBtn">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</button>


</aside>





<main class="main-content">


<header>


<div class="header-left">


<button id="menuBtn">

<i class="fa-solid fa-bars"></i>

</button>


<h1>
Settings
</h1>


</div>



<div class="header-right">


<i class="fa-regular fa-bell"></i>

<i class="fa-solid fa-envelope"></i>


<img src="images/user.png"
id="headerPhoto">


</div>


</header>

<section class="settings-container">


<div class="settings-card">


<h2>

<i class="fa-solid fa-user"></i>

Account Settings

</h2>



<div class="profile-settings">


<img src="images/user.png"
id="profilePhoto">



<div>

<h3 id="userName">

User Name

</h3>


<p id="userEmail">

example@email.com

</p>


</div>



</div>



<button id="editProfileBtn">

<i class="fa-solid fa-pen"></i>

Edit Profile

</button>



</div>





<div class="settings-card">


<h2>

<i class="fa-solid fa-bell"></i>

Notification Settings

</h2>



<div class="setting-row">


<div>

<h3>Email Notifications</h3>

<p>
Receive important updates through email.
</p>

</div>


<label class="switch">

<input 
type="checkbox"
id="emailNotification"
checked>


<span class="slider"></span>


</label>


</div>





<div class="setting-row">


<div>

<h3>SMS Notifications</h3>

<p>
Receive emergency blood alerts.
</p>

</div>


<label class="switch">


<input 
type="checkbox"
id="smsNotification">


<span class="slider"></span>


</label>


</div>





<div class="setting-row">


<div>

<h3>Emergency Alerts</h3>

<p>
Get urgent blood request notifications.
</p>

</div>


<label class="switch">


<input 
type="checkbox"
id="emergencyAlert"
checked>


<span class="slider"></span>


</label>


</div>


</div>



<div class="settings-card">


<h2>

<i class="fa-solid fa-lock"></i>

Privacy Settings

</h2>



<div class="setting-row">


<div>

<h3>Show Email</h3>

<p>
Allow others to see your email.
</p>

</div>



<label class="switch">


<input 
type="checkbox"
id="showEmail">


<span class="slider"></span>


</label>


</div>





<div class="setting-row">


<div>

<h3>Show Phone Number</h3>

<p>
Allow donors to contact you.
</p>

</div>



<label class="switch">


<input 
type="checkbox"
id="showPhone">


<span class="slider"></span>


</label>


</div>





<div class="setting-row">


<div>

<h3>Show Location</h3>

<p>
Display your donation location.
</p>

</div>



<label class="switch">


<input 
type="checkbox"
id="showLocation"
checked>


<span class="slider"></span>


</label>


</div>



</div>
<div class="settings-card">


<h2>

<i class="fa-solid fa-key"></i>

Change Password

</h2>



<div class="password-box">


<input 
type="password"
id="oldPassword"
placeholder="Current Password">



<input 
type="password"
id="newPassword"
placeholder="New Password">



<input 
type="password"
id="confirmPassword"
placeholder="Confirm New Password">


</div>


<button id="changePasswordBtn">

<i class="fa-solid fa-lock"></i>

Update Password

</button>



</div>







<div class="settings-card">


<h2>

<i class="fa-solid fa-circle-info"></i>

Account Information

</h2>



<div class="info-grid">


<div>

<strong>
Member Since
</strong>

<p id="memberSince">

July 2026

</p>

</div>



<div>

<strong>
Total Posts
</strong>

<p id="totalPosts">

0

</p>

</div>



<div>

<strong>
Donations
</strong>

<p id="totalDonationsInfo">

0

</p>

</div>



<div>

<strong>
Lives Saved
</strong>

<p id="livesSavedInfo">

0

</p>

</div>


</div>



</div>







<div class="danger-zone">


<h2>

<i class="fa-solid fa-triangle-exclamation"></i>

Danger Zone

</h2>



<p>

Deleting your account is permanent and cannot be undone.

</p>



<button id="deleteAccountBtn">


<i class="fa-solid fa-trash"></i>

Delete Account


</button>



</div>







<div class="save-area">


<button id="saveSettings">


<i class="fa-solid fa-floppy-disk"></i>

Save Settings

</button>


</div>





<footer>


<h3>
BloodConnect
</h3>


<p>
Connect. Donate. Save Lives.
</p>


<p>
© 2026 BloodConnect. All Rights Reserved.
</p>


</footer>



</main>


</div>


<script type="application/json" id="currentUserData">
<?php echo json_encode([
    "name" => $me['name'], "email" => $me['email'], "photo" => $me['photo'] ?: 'images/user.png',
    "memberSince" => date("F Y", strtotime($me['created_at'])),
    "emailNotification" => (bool)$me['email_notification'], "smsNotification" => (bool)$me['sms_notification'],
    "emergencyAlert" => (bool)$me['emergency_notification'], "showEmail" => (bool)$me['show_email'],
    "showPhone" => (bool)$me['show_phone'], "showLocation" => (bool)$me['show_location']
]); ?>
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const menuBtn = document.getElementById("menuBtn");
    const sidebar = document.querySelector(".sidebar");
    const logoutBtn = document.getElementById("logoutBtn");

    if (menuBtn) {
        menuBtn.addEventListener("click", function () {
            sidebar.classList.toggle("show");
        });
    }

    let currentUser = JSON.parse(document.getElementById("currentUserData").textContent);

    const userName = document.getElementById("userName");
    const userEmail = document.getElementById("userEmail");
    const profilePhoto = document.getElementById("profilePhoto");
    const headerPhoto = document.getElementById("headerPhoto");
    const memberSinceEl = document.getElementById("memberSince");
    const totalDonationsEl = document.getElementById("totalDonationsInfo");
    const livesSavedEl = document.getElementById("livesSavedInfo");

    function loadUser() {

        if (userName) userName.textContent = currentUser.name || "User Name";
        if (userEmail) userEmail.textContent = currentUser.email || "example@email.com";
        if (profilePhoto) profilePhoto.src = currentUser.photo || "images/user.png";
        if (headerPhoto) headerPhoto.src = currentUser.photo || "images/user.png";
        if (memberSinceEl) memberSinceEl.textContent = currentUser.memberSince || "July 2026";

        fetch("api/donations_list.php", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                const myDonations = data.success ? data.donations : [];
                if (totalDonationsEl) totalDonationsEl.textContent = myDonations.length;
                if (livesSavedEl) livesSavedEl.textContent = myDonations.length * 3;
            });

    }

    loadUser();

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function () {
            if (confirm("Are you sure you want to logout?")) {
                fetch("api/logout.php", { credentials: "same-origin" }).then(function () {
                    window.location.href = "index.php";
                });
            }
        });
    }

    const editProfileBtn = document.getElementById("editProfileBtn");
    if (editProfileBtn) {
        editProfileBtn.addEventListener("click", function () {
            window.location.href = "editProfile.php";
        });
    }

    const emailNotification = document.getElementById("emailNotification");
    const smsNotification = document.getElementById("smsNotification");
    const emergencyAlert = document.getElementById("emergencyAlert");
    const showEmail = document.getElementById("showEmail");
    const showPhone = document.getElementById("showPhone");
    const showLocation = document.getElementById("showLocation");

    function loadSettings() {
        if (emailNotification) emailNotification.checked = currentUser.emailNotification ?? true;
        if (smsNotification) smsNotification.checked = currentUser.smsNotification ?? false;
        if (emergencyAlert) emergencyAlert.checked = currentUser.emergencyAlert ?? true;
        if (showEmail) showEmail.checked = currentUser.showEmail ?? true;
        if (showPhone) showPhone.checked = currentUser.showPhone ?? true;
        if (showLocation) showLocation.checked = currentUser.showLocation ?? true;
    }

    loadSettings();

    const saveSettings = document.getElementById("saveSettings");

    if (saveSettings) {
        saveSettings.addEventListener("click", function () {

            const formData = new FormData();
            if (emailNotification.checked) formData.append("emailNotification", "1");
            if (smsNotification.checked) formData.append("smsNotification", "1");
            if (emergencyAlert.checked) formData.append("emergencyAlert", "1");
            if (showEmail.checked) formData.append("showEmail", "1");
            if (showPhone.checked) formData.append("showPhone", "1");
            if (showLocation.checked) formData.append("showLocation", "1");

            fetch("api/settings_update.php", { method: "POST", body: formData, credentials: "same-origin" })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        alert("Settings saved successfully.");
                    } else {
                        alert(data.message || "Could not save settings.");
                    }
                });

        });
    }

    const totalPosts = document.getElementById("totalPosts");

    function updatePostCount() {
        fetch("api/posts_list.php?mine=1", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (totalPosts) totalPosts.textContent = data.success ? data.posts.length : 0;
            });
    }

    updatePostCount();

    const changePasswordBtn = document.getElementById("changePasswordBtn");
    const oldPassword = document.getElementById("oldPassword");
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");

    if (changePasswordBtn) {
        changePasswordBtn.addEventListener("click", function () {

            if (oldPassword.value === "") {
                alert("Enter your current password.");
                return;
            }

            if (newPassword.value.trim() === "") {
                alert("Enter a new password.");
                return;
            }

            if (newPassword.value !== confirmPassword.value) {
                alert("Passwords do not match.");
                return;
            }

            const formData = new FormData();
            formData.append("currentPassword", oldPassword.value);
            formData.append("newPassword", newPassword.value);
            formData.append("confirmPassword", confirmPassword.value);

            fetch("api/profile_password.php", { method: "POST", body: formData, credentials: "same-origin" })
                .then(function (res) { return res.json(); })
                .then(function (data) {

                    if (data.success) {
                        oldPassword.value = "";
                        newPassword.value = "";
                        confirmPassword.value = "";
                        alert("Password updated successfully.");
                    } else {
                        alert(data.message || "Could not update the password.");
                    }

                });

        });
    }

    const deleteAccountBtn = document.getElementById("deleteAccountBtn");

    if (deleteAccountBtn) {
        deleteAccountBtn.addEventListener("click", function () {

            const confirmDelete = confirm("Are you sure? This action cannot be undone.");

            if (!confirmDelete) return;

            fetch("api/account_delete.php", { method: "POST", credentials: "same-origin" })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        alert("Account deleted successfully.");
                        window.location.href = "index.php";
                    } else {
                        alert("Could not delete the account. Please try again.");
                    }
                });

        });
    }

    document.addEventListener("click", function (e) {
        if (
            sidebar &&
            sidebar.classList.contains("show") &&
            !sidebar.contains(e.target) &&
            !menuBtn.contains(e.target)
        ) {
            sidebar.classList.remove("show");
        }
    });

    window.addEventListener("focus", function () {
        loadUser();
        updatePostCount();
    });

});
</script>


</body>



</html>