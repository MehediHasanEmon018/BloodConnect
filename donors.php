<?php require_once __DIR__ . '/includes/auth.php'; requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Donors</title>

    <style>
        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    color:#333;
    background:
        radial-gradient(circle at 15% 10%, rgba(99,102,241,.09) 0%, transparent 40%),
        radial-gradient(circle at 85% 25%, rgba(20,184,166,.08) 0%, transparent 45%),
        radial-gradient(circle at 30% 90%, rgba(236,72,153,.06) 0%, transparent 50%),
        radial-gradient(circle at 90% 85%, rgba(251,191,36,.07) 0%, transparent 45%),
        linear-gradient(135deg, #f7f8fc 0%, #f2f4fa 50%, #eef1f8 100%);
    background-attachment: fixed;
}

header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 8%;
    background:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    position:sticky;
    top:0;
    z-index:1000;
}

.logo h2{
    color:#d62828;
    margin-bottom:5px;
}

.logo p{
    color:#666;
    font-size:14px;
}

nav{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

nav a{
    text-decoration:none;
    color:#333;
    font-weight:600;
    transition:.3s;
}

nav a:hover,
nav a.active{
    color:#d62828;
}

#backBtn{
    padding:10px 22px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
    transition:.3s;
}

#backBtn:hover{
    background:#b91d1d;
}

main{
    width:84%;
    margin:40px auto;
}

.page-title{
    text-align:center;
    margin-bottom:40px;
}

.page-title h1{
    font-size:42px;
    color:#d62828;
    margin-bottom:10px;
}

.page-title p{
    color:#666;
    font-size:18px;
}

.search-section{
    background:#fff;
    padding:35px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    margin-bottom:45px;
}

.search-section h2{
    color:#d62828;
    margin-bottom:20px;
}

.search-box{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.search-box select,
.search-box input{
    flex:1;
    min-width:220px;
    padding:14px;
    border:1px solid #ddd;
    border-radius:6px;
    font-size:15px;
    outline:none;
}

.search-box select:focus,
.search-box input:focus{
    border-color:#d62828;
}

.search-box button{
    padding:14px 30px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
    transition:.3s;
}

.search-box button:hover{
    background:#b91d1d;
}

.donor-list h2{
    color:#d62828;
    margin-bottom:25px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:25px;
}

.card{
    background:#fff;
    border-radius:12px;
    padding:30px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    transition:.3s;
}

.card:hover{
    transform:translateY(-8px);
}

.card img{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:20px;
    border:4px solid #f4d3d3;
}

.card h3{
    color:#d62828;
    margin-bottom:15px;
}

.card p{
    margin:8px 0;
    color:#555;
    font-size:15px;
    line-height:1.5;
}

.card button{
    margin-top:20px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
    font-size:15px;
    transition:.3s;
}

.card button:hover{
    background:#b91d1d;
}

footer{
    margin-top:60px;
    background:#d62828;
    color:#fff;
    text-align:center;
    padding:35px;
}

footer h3{
    margin-bottom:10px;
}

footer p{
    margin-top:8px;
}

@media(max-width:1000px){

    header{
        flex-direction:column;
        gap:20px;
    }

    nav{
        justify-content:center;
    }

    main{
        width:92%;
    }

    .search-box{
        flex-direction:column;
    }
}

@media(max-width:768px){

    .page-title h1{
        font-size:34px;
    }

    .page-title p{
        font-size:16px;
    }

    .search-section{
        padding:25px;
    }

    .card{
        padding:25px;
    }
}

@media(max-width:500px){

    .page-title h1{
        font-size:28px;
    }

    .card img{
        width:75px;
        height:75px;
    }
}
    </style>
</head>
<body>

    <header>

        <div class="logo">
            <h2>BloodConnect</h2>
            <p>Verified Blood Donors</p>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="donors.php" class="active">Donors</a>
            <a href="blood-requests.php">Blood Requests</a>
            <a href="emergency-requests.php">Emergency</a>
            <a href="Hospitals.php">Hospitals</a>
            <a href="profile.php">Profile</a>
        </nav>

        <button id="backBtn">Dashboard</button>

    </header>

    <main>

        <section class="page-title">
            <h1>Find Blood Donors</h1>
            <p>Search verified donors by blood group and location.</p>
        </section>

        <section class="search-section">

            <h2>Search Donors</h2>

            <div class="search-box">

                <select id="bloodGroup">
                    <option>Select Blood Group</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                    <option>O+</option>
                    <option>O-</option>
                </select>

                <input
                    type="text"
                    id="location"
                    placeholder="Enter City or Area">

                <button id="searchBtn">Search</button>

            </div>

        </section>

        <section class="donor-list">

            <h2>Available Donors</h2>

            <div class="grid" id="donorGrid"></div>

        </section>

    </main>

    <footer>

        <h3>BloodConnect</h3>

        <p>Connecting Donors with Patients, Saving Lives.</p>

        <p>© 2026 BloodConnect. All Rights Reserved.</p>

    </footer>

    <script>
document.addEventListener("DOMContentLoaded", function () {

    const backBtn = document.getElementById("backBtn");
    const searchBtn = document.getElementById("searchBtn");
    const bloodGroup = document.getElementById("bloodGroup");
    const location = document.getElementById("location");
    const donorGrid = document.getElementById("donorGrid");

    if (backBtn) {
        backBtn.addEventListener("click", function () {
            window.location.href = "index.php";
        });
    }

    function escapeHtml(str) {
        const div = document.createElement("div");
        div.textContent = str == null ? "" : String(str);
        return div.innerHTML;
    }

    function getDonationPosts(params) {
        const query = new URLSearchParams(Object.assign({ postType: "Blood Available" }, params || {}));
        return fetch("api/posts_list.php?" + query.toString(), { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) { return data.success ? data.posts : []; });
    }

    function renderDonors(donors) {

        donorGrid.innerHTML = "";

        if (donors.length === 0) {

            donorGrid.innerHTML = `
                <div style="grid-column:1/-1;text-align:center;padding:50px;">
                    <h2>No blood donors available.</h2>
                </div>
            `;

            return;
        }

        donors.forEach(function (donor, index) {

            const card = document.createElement("div");
            card.className = "card";

            // All values escaped before insertion to prevent stored-XSS from post data.
            card.innerHTML = `

                <img src="${escapeHtml(donor.userPhoto || "images/user.png")}" alt="Donor">

                <h3>${escapeHtml(donor.userName || "Unknown User")}</h3>

                <p><strong>Blood Group:</strong> ${escapeHtml(donor.blood_group || "N/A")}</p>

                <p><strong>Location:</strong> ${escapeHtml(donor.location || "Unknown")}</p>

                <p><strong>Hospital:</strong> ${escapeHtml(donor.hospital || "Not Provided")}</p>

                <p><strong>Contact:</strong> ${escapeHtml(donor.contact || "Not Provided")}</p>

                <p><strong>Status:</strong> Available</p>

                <button class="contactBtn">Contact</button>

            `;

            donorGrid.appendChild(card);

            card.style.opacity = "0";
            card.style.transform = "translateY(30px)";

            setTimeout(function () {

                card.style.transition = "0.5s";
                card.style.opacity = "1";
                card.style.transform = "translateY(0)";

            }, index * 100);

            card.addEventListener("mouseenter", function () {

                card.style.transform = "translateY(-8px) scale(1.02)";

            });

            card.addEventListener("mouseleave", function () {

                card.style.transform = "translateY(0)";

            });

            card.querySelector(".contactBtn").addEventListener("click", function () {

                window.location.href = "Chat.php?with=" + encodeURIComponent(donor.user_id);

            });

        });

    }

    function searchDonors() {

        const group = bloodGroup.value.trim();
        const city = location.value.trim();

        const params = {};
        if (group && group !== "Select Blood Group") params.bloodGroup = group;
        if (city) params.location = city;

        getDonationPosts(params).then(renderDonors);

    }

    if (searchBtn) {

        searchBtn.addEventListener("click", searchDonors);

    }

    const navLinks = document.querySelectorAll("nav a");

    navLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            console.log("Opening " + link.textContent);

        });

    });

    getDonationPosts().then(renderDonors);

});
</script>
</body>
</html>