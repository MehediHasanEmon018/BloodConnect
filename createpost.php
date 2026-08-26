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

    <title>BloodConnect | Create Post</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-body);
        }

        body {
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 8%, rgba(200, 16, 46, .05) 0%, transparent 40%),
                radial-gradient(circle at 88% 18%, rgba(14, 124, 116, .06) 0%, transparent 45%),
                radial-gradient(circle at 25% 92%, rgba(200, 16, 46, .04) 0%, transparent 50%),
                var(--paper);
            background-attachment: fixed;
        }

        .container {
            display: flex;
            min-height: 100vh;
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

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
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
        .main-content {
            margin-left: 270px;
            width: calc(100% - 270px);
            padding: 34px 38px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        #menuBtn {
            display: none;
            border: none;
            background: var(--panel);
            border: 1px solid var(--mist);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            font-size: 17px;
            color: var(--ink);
            cursor: pointer;
        }

        .header-left h1 {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 32px;
            letter-spacing: .2px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .header-right i {
            font-size: 20px;
            color: var(--ink-soft);
            cursor: pointer;
        }

        .header-right img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--crimson);
        }

        .subtitle {
            color: var(--ink-soft);
            margin-bottom: 26px;
            font-size: 14.5px;
        }

        /* ============================
           Post form + preview
           ============================ */
        .post-container {
            background: var(--panel);
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-panel);
        }

        .profile-box {
            display: flex;
            align-items: center;
            gap: 18px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--mist);
            margin-bottom: 25px;
        }

        .profile-box img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--crimson);
        }

        .profile-box h3 {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 22px;
            color: var(--ink);
        }

        .profile-box p {
            color: var(--ink-soft);
            margin-top: 5px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 22px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 15px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 13px 15px;
            border: 1px solid var(--mist);
            border-radius: var(--radius-sm);
            outline: none;
            font-size: 15px;
            font-family: var(--font-body);
            transition: .2s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--crimson);
            box-shadow: 0 0 0 3px var(--crimson-soft);
        }

        .form-group textarea {
            resize: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .image-upload {
            border: 2px dashed var(--crimson);
            padding: 25px;
            border-radius: var(--radius-md);
            text-align: center;
        }

        .image-upload label {
            cursor: pointer;
            color: var(--crimson);
            font-size: 17px;
            font-weight: 700;
        }

        .image-upload i {
            margin-right: 8px;
        }

        #postImage {
            display: none;
        }

        .preview-image {
            width: 250px;
            height: 180px;
            margin-top: 20px;
            border-radius: var(--radius-md);
            object-fit: cover;
            display: none;
        }

        .checkbox-box {
            background: var(--crimson-soft);
            padding: 15px;
            border-radius: var(--radius-sm);
            margin: 20px 0;
        }

        .checkbox-box input {
            margin-right: 10px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .button-group button {
            padding: 13px 25px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: .2s ease;
        }

        #publishBtn {
            background: var(--crimson);
            color: #fff;
        }

        #publishBtn:hover {
            background: var(--crimson-dark);
        }

        #clearBtn {
            background: var(--sand);
            color: var(--ink);
        }

        #clearBtn:hover {
            background: var(--mist);
        }

        .preview-section {
            margin-top: 35px;
            background: var(--panel);
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-panel);
        }

        .preview-section h2 {
            font-family: var(--font-display);
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 25px;
            font-size: 24px;
        }

        .post-preview-card {
            border: 1px solid var(--mist);
            border-radius: var(--radius-md);
            padding: 25px;
            background: var(--paper);
        }

        .preview-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .preview-header img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
        }

        .preview-header h3 {
            font-family: var(--font-display);
            font-weight: 600;
            color: var(--ink);
        }

        .preview-header p {
            color: var(--ink-soft);
            font-size: 14px;
        }

        .preview-content h3 {
            font-size: 21px;
            margin-bottom: 12px;
        }

        .preview-content p {
            color: var(--ink-soft);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .preview-info {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .preview-info span {
            background: var(--crimson-soft);
            color: var(--crimson-dark);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13.5px;
        }

        .preview-post-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: var(--radius-md);
            display: none;
        }

        footer {
            margin-top: 40px;
            background: var(--crimson);
            color: #fff;
            text-align: center;
            padding: 25px;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        footer h3 {
            font-family: var(--font-display);
            font-size: 24px;
            margin-bottom: 10px;
        }

        footer p {
            color: rgba(255, 255, 255, .85);
            margin: 8px;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--crimson);
            border-radius: 20px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        @media(max-width:1100px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width:900px) {

            .sidebar {
                left: -270px;
                transition: left .3s ease;
                z-index: 999;
                box-shadow: var(--shadow-panel);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                width: 100%;
                margin-left: 0;
                padding: 26px 20px;
            }

            #menuBtn {
                display: block;
            }

            header {
                flex-direction: column;
                gap: 18px;
                align-items: flex-start;
            }

        }

        @media(max-width:768px) {

            .post-container {
                padding: 20px;
            }

            .header-right i {
                display: none;
            }

        }

        @media(max-width:600px) {

            .header-left h1 {
                font-size: 24px;
            }

            .profile-box {
                flex-direction: column;
                text-align: center;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group button {
                justify-content: center;
            }

            .preview-info {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

    <div class="container">

        <aside class="sidebar">

            <div>

                <div class="logo">
                    <h2>
                        <i class="fa-solid fa-droplet"></i>
                        BloodConnect
                    </h2>
                    <p>Save Lives Together</p>

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

                    <li class="active">
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

                    <li>
                        <a href="Settings.php">
                            <i class="fa-solid fa-gear"></i>
                            Settings
                        </a>
                    </li>

                </ul>

            </div>

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

                    <h1>Create Post</h1>

                </div>

                <div class="header-right">

                    <i class="fa-regular fa-bell"></i>
                    <i class="fa-regular fa-envelope"></i>

                    <img src="images/user.png" id="headerPhoto" alt="Profile">

                </div>

            </header>

            <p class="subtitle">
                Share your blood availability, request or donation story with the community.
            </p>

            <section class="post-container">

                <form id="postForm">

                    <div class="profile-box">

                        <img src="images/user.png" id="userPhoto" alt="User">

                        <div>

                            <h3 id="userName">
                                User Name
                            </h3>

                            <p>
                                Creating a new post
                            </p>

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="postType">

                            Post Type

                        </label>

                        <select id="postType" required>

                            <option value="">
                                Select Post Type
                            </option>

                            <option value="Blood Available">
                                Blood Available
                            </option>

                            <option value="Blood Request">
                                Blood Request
                            </option>

                            <option value="Donation Story">
                                Donation Story
                            </option>

                        </select>

                    </div>

                    <div class="form-grid">

                        <div class="form-group">

                            <label for="bloodGroup">

                                Blood Group

                            </label>

                            <select id="bloodGroup" required>

                                <option value="">
                                    Select Blood Group
                                </option>

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

                            <label for="requiredDate">

                                Required / Available Date

                            </label>

                            <input type="date" id="requiredDate" required>

                        </div>

                    </div>

                    <div class="form-grid">

                        <div class="form-group">

                            <label for="hospital">

                                Hospital Name

                            </label>

                            <input type="text" id="hospital" placeholder="Hospital Name">

                        </div>

                        <div class="form-group">

                            <label for="location">

                                Location

                            </label>

                            <input type="text" id="location" placeholder="City / District">

                        </div>

                    </div>

                    <div class="form-grid">

                        <div class="form-group">

                            <label for="contact">

                                Contact Number

                            </label>

                            <input type="tel" id="contact" placeholder="01XXXXXXXXX" required>

                        </div>

                        <div class="form-group">

                            <label for="urgency">

                                Urgency Level

                            </label>

                            <select id="urgency">

                                <option>Normal</option>
                                <option>Urgent</option>
                                <option>Emergency</option>

                            </select>

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="description">

                            Description

                        </label>

                        <textarea id="description" rows="6"
                            placeholder="Write details about your blood donation, request or story..."
                            required></textarea>

                    </div>

                    <div class="form-group">

                        <label>

                            Upload Image

                        </label>

                        <div class="image-upload">

                            <label for="postImage">

                                <i class="fa-solid fa-image"></i>

                                Choose Image

                            </label>

                            <input type="file" id="postImage" accept="image/*">

                        </div>

                        <img src="" id="imagePreview" class="preview-image" alt="Preview">

                    </div>

                    <div class="checkbox-box">

                        <label>

                            <input type="checkbox" id="emergency">

                            Mark as Emergency Request

                        </label>

                    </div>

                    <div class="button-group">

                        <button type="submit" id="publishBtn">

                            <i class="fa-solid fa-paper-plane"></i>

                            Publish Post

                        </button>

                        <button type="reset" id="clearBtn">

                            <i class="fa-solid fa-rotate-left"></i>

                            Clear

                        </button>

                    </div>

                </form>

            </section>

            <section class="preview-section">

                <h2>

                    Live Post Preview

                </h2>

                <div class="post-preview-card">

                    <div class="preview-header">

                        <img src="images/user.png" id="previewUserPhoto" alt="User">

                        <div>

                            <h3 id="previewUserName">

                                User Name

                            </h3>

                            <p id="previewDate">

                                Today

                            </p>

                        </div>

                    </div>

                    <div class="preview-content">

                        <h3 id="previewTitle">

                            Blood Post

                        </h3>

                        <p id="previewDescription">

                            Your description will appear here...

                        </p>

                        <div class="preview-info">

                            <span id="previewType">

                                Blood Available

                            </span>

                            <span id="previewBlood">

                                O+

                            </span>

                            <span id="previewLocation">

                                Location

                            </span>

                            <span id="previewUrgency">

                                Normal

                            </span>

                        </div>

                        <img src="" id="previewPostImage" class="preview-post-image" alt="Post Image">

                    </div>

                </div>

            </section>

            <footer>

                <h3>BloodConnect</h3>

                <p>
                    Connecting donors and patients to save lives.
                </p>

                <p>
                    © 2026 BloodConnect. All Rights Reserved.
                </p>

            </footer>

        </main>

    </div>

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

            document.addEventListener("click", function (e) {
                if (
                    window.innerWidth < 900 &&
                    !sidebar.contains(e.target) &&
                    !menuBtn.contains(e.target)
                ) {
                    sidebar.classList.remove("show");
                }
            });

            if (logoutBtn) {
                logoutBtn.addEventListener("click", function () {
                    if (confirm("Are you sure you want to logout?")) {
                        fetch("api/logout.php", { credentials: "same-origin" }).then(function () {
                            window.location.replace("index.php");
                        });
                    }
                });
            }

            const postForm = document.getElementById("postForm");

            const userName = document.getElementById("userName");
            const userPhoto = document.getElementById("userPhoto");
            const headerPhoto = document.getElementById("headerPhoto");

            const postType = document.getElementById("postType");
            const bloodGroup = document.getElementById("bloodGroup");
            const requiredDate = document.getElementById("requiredDate");
            const hospital = document.getElementById("hospital");
            const location = document.getElementById("location");
            const contact = document.getElementById("contact");
            const urgency = document.getElementById("urgency");
            const description = document.getElementById("description");
            const emergency = document.getElementById("emergency");

            const postImage = document.getElementById("postImage");
            const imagePreview = document.getElementById("imagePreview");

            const clearBtn = document.getElementById("clearBtn");

            const previewUserPhoto = document.getElementById("previewUserPhoto");
            const previewUserName = document.getElementById("previewUserName");
            const previewDate = document.getElementById("previewDate");
            const previewTitle = document.getElementById("previewTitle");
            const previewDescription = document.getElementById("previewDescription");
            const previewType = document.getElementById("previewType");
            const previewBlood = document.getElementById("previewBlood");
            const previewLocation = document.getElementById("previewLocation");
            const previewUrgency = document.getElementById("previewUrgency");
            const previewPostImage = document.getElementById("previewPostImage");

            let currentUser = <?php echo json_encode(["name" => $me['name'], "photo" => $me['photo']]); ?>;

            let currentImageData = "";

            if (requiredDate) {

                requiredDate.value =
                    new Date().toISOString().split("T")[0];

            }

            function loadUser() {

                userName.textContent =
                    currentUser.name || "User Name";

                previewUserName.textContent =
                    currentUser.name || "User Name";

                const photo =
                    currentUser.photo || "images/user.png";

                userPhoto.src = photo;
                headerPhoto.src = photo;
                previewUserPhoto.src = photo;

            }

            loadUser();

            previewDate.textContent =
                new Date().toLocaleString();

            function updatePreview() {

                previewType.textContent =
                    postType.value || "Blood Post";

                previewBlood.textContent =
                    bloodGroup.value || "Blood Group";

                previewLocation.textContent =
                    location.value || "Location";

                previewUrgency.textContent =
                    urgency.value || "Normal";

                previewDescription.textContent =
                    description.value || "Your description will appear here...";

                if (postType.value === "Blood Available") {

                    previewTitle.textContent =
                        "Blood Available";

                }

                else if (postType.value === "Blood Request") {

                    previewTitle.textContent =
                        "Blood Request";

                }

                else if (postType.value === "Donation Story") {

                    previewTitle.textContent =
                        "Donation Story";

                }

                else {

                    previewTitle.textContent =
                        "Blood Post";

                }

            }

            postType.addEventListener("change", updatePreview);

            bloodGroup.addEventListener("change", updatePreview);

            location.addEventListener("input", updatePreview);

            urgency.addEventListener("change", updatePreview);

            description.addEventListener("input", updatePreview);

            function compressImage(file, maxWidth, quality, callback) {

                const reader = new FileReader();

                reader.onload = function (e) {

                    const img = new Image();

                    img.onload = function () {

                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth) {
                            height = Math.round(height * (maxWidth / width));
                            width = maxWidth;
                        }

                        const canvas = document.createElement("canvas");
                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, width, height);

                        callback(canvas.toDataURL("image/jpeg", quality));

                    };

                    img.onerror = function () {
                        callback("");
                    };

                    img.src = e.target.result;

                };

                reader.onerror = function () {
                    callback("");
                };

                reader.readAsDataURL(file);

            }

            postImage.addEventListener("change", function () {

                const file = this.files[0];

                if (!file) {

                    currentImageData = "";
                    imagePreview.style.display = "none";
                    previewPostImage.style.display = "none";
                    return;

                }

                compressImage(file, 900, 0.7, function (dataUrl) {

                    currentImageData = dataUrl;

                    if (!dataUrl) {

                        imagePreview.style.display = "none";
                        previewPostImage.style.display = "none";
                        alert("This image could not be processed. Please try a different file.");
                        return;

                    }

                    imagePreview.src = dataUrl;
                    imagePreview.style.display = "block";

                    previewPostImage.src = dataUrl;
                    previewPostImage.style.display = "block";

                });

            });

            clearBtn.addEventListener("click", function () {

                setTimeout(function () {

                    currentImageData = "";

                    imagePreview.style.display = "none";
                    previewPostImage.style.display = "none";

                    previewTitle.textContent = "Blood Post";
                    previewDescription.textContent = "Your description will appear here...";
                    previewType.textContent = "Blood Post";
                    previewBlood.textContent = "Blood Group";
                    previewLocation.textContent = "Location";
                    previewUrgency.textContent = "Normal";

                }, 100);

            });

            postForm.addEventListener("submit", function (e) {

                e.preventDefault();

                if (postType.value === "") {

                    alert("Please select a post type.");
                    return;

                }

                if (bloodGroup.value === "") {

                    alert("Please select a blood group.");
                    return;

                }

                if (location.value.trim() === "") {

                    alert("Please enter a location.");
                    return;

                }

                if (contact.value.trim() === "") {

                    alert("Please enter a contact number.");
                    return;

                }

                if (description.value.trim() === "") {

                    alert("Please enter a description.");
                    return;

                }

                function savePost(imageData) {

                    const formData = new FormData();
                    formData.append("postType", postType.value);
                    formData.append("bloodGroup", bloodGroup.value);
                    formData.append("hospital", hospital.value);
                    formData.append("location", location.value);
                    formData.append("contact", contact.value);
                    formData.append("urgency", urgency.value);
                    formData.append("requiredDate", requiredDate.value);
                    formData.append("description", description.value);
                    formData.append("emergency", emergency.checked ? "1" : "");
                    if (imageData) formData.append("image", imageData);

                    fetch("api/posts_create.php", { method: "POST", body: formData, credentials: "same-origin" })
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data.success) {
                                alert("Post published successfully!");
                                window.location.href = "profile.php";
                            } else {
                                alert(data.message || "Could not publish the post.");
                            }
                        })
                        .catch(function () {
                            alert("Server error. Please try again.");
                        });

                }

                savePost(currentImageData);

            });

            updatePreview();

        });
    </script>
</body>

</html>