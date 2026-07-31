<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BloodConnect | Hospitals</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>

:root{
    --red:#e0302f;
    --red-dark:#b91f1f;
    --red-light:#ffece9;
    --red-soft:#fff4f2;
    --ink:#1f2430;
    --muted:#6b7280;
    --border:#eef0f4;
    --bg:#fbf9f7;
    --surface:#ffffff;
    --accent-gold:#f4a340;
    --accent-teal:#0f9b8e;
    --radius:18px;
    --shadow:0 10px 30px rgba(31,36,48,.07);
    --shadow-soft:0 4px 16px rgba(31,36,48,.05);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

html{ scroll-behavior:smooth; }

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

img{ width:100%; display:block; }
a{ text-decoration:none; }
button{ font-family:'Poppins',sans-serif; cursor:pointer; border:none; }

section{
    width:90%;
    max-width:1350px;
    margin:auto;
}

header{
    position:sticky;
    top:0;
    width:100%;
    height:85px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 6%;
    background:var(--surface);
    box-shadow:0 5px 20px rgba(0,0,0,.08);
    z-index:1000;
}

.logo{
    display:flex;
    align-items:center;
    gap:14px;
}

.logo i{
    width:52px;
    height:52px;
    background:var(--red);
    color:#fff;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:21px;
    box-shadow:0 10px 20px rgba(214,40,40,.25);
}

.logo h2{
    color:var(--red);
    font-size:24px;
    font-weight:700;
}

.logo p{
    color:#888;
    font-size:12.5px;
}

nav{
    display:flex;
    align-items:center;
    gap:30px;
}

nav a{
    color:#444;
    font-size:14.5px;
    font-weight:600;
    position:relative;
    transition:.3s;
}

nav a:hover{ color:var(--red); }
nav a.active{ color:var(--red); }

nav a::after{
    content:"";
    position:absolute;
    left:0;
    bottom:-8px;
    width:0%;
    height:3px;
    background:var(--red);
    border-radius:20px;
    transition:.3s;
}

nav a:hover::after,
nav a.active::after{ width:100%; }

.profileBtn{
    padding:13px 26px;
    background:var(--red);
    color:#fff;
    border-radius:12px;
    font-size:14.5px;
    font-weight:600;
    transition:.3s;
    display:flex;
    align-items:center;
    gap:9px;
    box-shadow:0 12px 26px rgba(214,40,40,.25);
}

.profileBtn:hover{
    transform:translateY(-3px);
    background:var(--red-dark);
}

.hero{
    width:90%;
    max-width:1350px;
    margin:65px auto;
    display:grid;
    grid-template-columns:1.1fr .9fr;
    align-items:center;
    gap:60px;
}

.badge{
    display:inline-flex;
    align-items:center;
    gap:9px;
    padding:9px 20px;
    border-radius:40px;
    background:var(--red-light);
    color:var(--red);
    font-weight:600;
    font-size:13.5px;
    margin-bottom:20px;
}

.hero-left h1{
    font-size:52px;
    line-height:1.18;
    color:#222;
    font-weight:700;
    margin-bottom:20px;
}

.hero-left h1 span{ color:var(--red); }

.hero-left p{
    font-size:16.5px;
    color:#666;
    max-width:600px;
    margin-bottom:35px;
}

.hero-buttons{
    display:flex;
    gap:16px;
    margin-bottom:50px;
}

.hero-buttons button{
    padding:15px 30px;
    border-radius:12px;
    font-size:14.5px;
    font-weight:600;
    transition:.3s;
    display:flex;
    align-items:center;
    gap:9px;
}

#findHospital{
    background:var(--red);
    color:#fff;
    box-shadow:0 16px 28px rgba(214,40,40,.28);
}

#findHospital:hover{ transform:translateY(-3px); }

#requestHospitalBtn{
    background:#fff;
    border:2px solid var(--red);
    color:var(--red);
}

#requestHospitalBtn:hover{
    background:var(--red);
    color:#fff;
}

.hero-stats{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.hero-stats div{
    background:var(--surface);
    padding:18px 26px;
    border-radius:16px;
    box-shadow:var(--shadow);
    min-width:130px;
    text-align:center;
}

.hero-stats h2{
    color:var(--red);
    font-size:30px;
    margin-bottom:4px;
}

.hero-stats p{
    font-size:13px;
    color:#777;
}

.hero-right img{
    width:100%;
    border-radius:26px;
    object-fit:cover;
    box-shadow:0 30px 60px rgba(0,0,0,.12);
}

.search-section{
    background:var(--surface);
    padding:40px;
    border-radius:var(--radius);
    margin:50px auto;
    box-shadow:var(--shadow);
}

.search-section h2{
    color:var(--red);
    font-size:28px;
    margin-bottom:8px;
}

.search-section p{
    color:#666;
    margin-bottom:28px;
    font-size:15px;
}

.search-box{
    display:grid;
    grid-template-columns:2fr 1fr 1fr auto;
    gap:16px;
}

.search-box input,
.search-box select{
    height:52px;
    border:1px solid #ddd;
    border-radius:10px;
    padding:0 16px;
    font-size:14.5px;
    outline:none;
    transition:.3s;
}

.search-box input:focus,
.search-box select:focus{
    border-color:var(--red);
    box-shadow:0 0 0 4px rgba(214,40,40,.1);
}

.search-box button{
    height:52px;
    padding:0 30px;
    border-radius:10px;
    background:var(--red);
    color:#fff;
    font-size:14.5px;
    font-weight:600;
    transition:.3s;
}

.search-box button:hover{ background:var(--red-dark); }

.map-section{
    margin:60px auto;
}

.section-heading{
    margin-bottom:26px;
}

.section-heading span{
    display:inline-block;
    background:var(--red-light);
    color:var(--red);
    padding:7px 18px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.6px;
    margin-bottom:14px;
}

.section-heading h2{
    font-size:32px;
    color:#222;
    margin-bottom:8px;
}

.section-heading p{
    color:#666;
    font-size:15px;
    max-width:640px;
}

#hospitalMap{
    width:100%;
    height:440px;
    border-radius:var(--radius);
    box-shadow:var(--shadow);
    border:1px solid var(--border);
}

.hospital-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:26px;
    margin:40px auto;
}

.hospital-card{
    background:var(--surface);
    border-radius:var(--radius);
    overflow:hidden;
    box-shadow:var(--shadow);
    transition:.3s;
    border:1px solid var(--border);
}

.hospital-card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 42px rgba(214,40,40,.15);
}

.hospital-image{
    position:relative;
}

.hospital-image img{
    height:190px;
    object-fit:cover;
}

.status-tag{
    position:absolute;
    top:16px;
    right:16px;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
    color:#fff;
}

.status-tag.open{ background:#2f9e44; }
.status-tag.emergency{ background:var(--red); }

.hospital-content{
    padding:24px;
}

.title-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}

.title-row h3{
    color:var(--red);
    font-size:20px;
}

.rating{
    background:var(--red-light);
    color:var(--red);
    padding:5px 10px;
    border-radius:20px;
    font-size:12.5px;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:5px;
}

.hospital-content .location{
    color:#666;
    font-size:13.5px;
    margin-bottom:10px;
    display:flex;
    align-items:center;
    gap:7px;
}

.hospital-content .location i{ color:var(--red); }

.hospital-content .description{
    color:#666;
    font-size:14px;
    margin-bottom:16px;
}

.blood-list{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-bottom:20px;
}

.blood-list span{
    background:#f2f3f6;
    color:#4b5160;
    padding:6px 12px;
    border-radius:20px;
    font-size:12.5px;
    font-weight:600;
}

.hospital-buttons{
    display:flex;
    gap:12px;
}

.hospital-buttons button{
    flex:1;
    padding:12px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    transition:.3s;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:7px;
}

.callBtn{
    background:var(--red);
    color:#fff;
}

.callBtn:hover{ background:var(--red-dark); }

.mapBtn{
    background:#fff;
    color:var(--red);
    border:2px solid var(--red) !important;
}

.mapBtn:hover{
    background:var(--red);
    color:#fff;
}

.requests-section{
    margin:60px auto;
}

.hospital-request-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.hospital-request-card{
    background:var(--surface);
    border-radius:14px;
    padding:20px 24px;
    box-shadow:var(--shadow);
    border:1px solid var(--border);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.hospital-request-card .req-info h4{
    color:var(--ink);
    font-size:15.5px;
    margin-bottom:5px;
}

.hospital-request-card .req-info p{
    color:var(--muted);
    font-size:13.5px;
}

.hospital-request-card .req-tag{
    background:var(--red-light);
    color:var(--red);
    padding:6px 14px;
    border-radius:20px;
    font-size:12.5px;
    font-weight:700;
}

.empty-state{
    background:var(--surface);
    border:1px dashed var(--border);
    border-radius:var(--radius);
    padding:40px;
    text-align:center;
    color:var(--muted);
    font-size:14.5px;
}

.blood-network{
    margin:70px auto;
}

.blood-bank-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:26px;
}

.bank-card{
    background:var(--surface);
    padding:32px;
    border-radius:var(--radius);
    text-align:center;
    box-shadow:var(--shadow);
    transition:.3s;
}

.bank-card:hover{ transform:translateY(-6px); }

.bank-card i{
    width:70px;
    height:70px;
    line-height:70px;
    border-radius:50%;
    background:var(--red-light);
    color:var(--red);
    font-size:28px;
    margin:0 auto 18px;
}

.bank-card h3{
    color:var(--red);
    margin-bottom:10px;
    font-size:18px;
}

.bank-card h2{
    color:#222;
    font-size:26px;
    margin-bottom:6px;
}

.bank-card p{
    color:#777;
    font-size:13.5px;
}

footer{
    margin-top:80px;
    background:var(--red);
    color:#fff;
    padding:55px 8% 30px;
}

.footer-content{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:36px;
    margin-bottom:35px;
    max-width:1350px;
    margin-left:auto;
    margin-right:auto;
}

.footer-box h3{
    margin-bottom:16px;
    font-size:20px;
}

.footer-box p{
    color:#f6d6d6;
    margin-bottom:8px;
    line-height:1.8;
}

.footer-box a{
    display:block;
    color:#fff;
    margin-bottom:10px;
    transition:.2s;
    font-size:14.5px;
}

.footer-box a:hover{ padding-left:6px; }

.footer-bottom{
    border-top:1px solid rgba(255,255,255,.3);
    padding-top:22px;
    text-align:center;
    font-size:14px;
    max-width:1350px;
    margin:auto;
}

@media(max-width:1000px){

    header{ flex-direction:column; height:auto; padding:16px 6%; gap:14px; }
    nav{ flex-wrap:wrap; justify-content:center; gap:16px; }
    .hero{ grid-template-columns:1fr; text-align:center; }
    .hero-buttons{ justify-content:center; }
    .hero-stats{ justify-content:center; }
    .hero-left p{ margin-left:auto; margin-right:auto; }
    .search-box{ grid-template-columns:1fr; }

}

@media(max-width:600px){

    .hero-left h1{ font-size:34px; }
    .hospital-buttons{ flex-direction:column; }
    .hospital-request-card{ flex-direction:column; align-items:flex-start; }

}

</style>

</head>

<body>

<header>

    <div class="logo">
        <i class="fa-solid fa-droplet"></i>
        <div>
            <h2>BloodConnect</h2>
            <p>Saving Lives Together</p>
        </div>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="donors.php">Donors</a>
        <a href="blood-requests.php">Requests</a>
        <a href="emergency-requests.php">Emergency</a>
        <a href="Hospitals.php" class="active">Hospitals</a>
        <a href="profile.php">Profile</a>
    </nav>

    <button class="profileBtn" id="profileBtn">
        <i class="fa-solid fa-user"></i>
        My Profile
    </button>

</header>

<section class="hero">

    <div class="hero-left">

        <span class="badge">
            <i class="fa-solid fa-heart-pulse"></i>
            Trusted Healthcare Network
        </span>

        <h1>Find Hospitals & <span>Blood Banks</span> Near You</h1>

        <p>
            BloodConnect connects patients, donors and hospitals across
            Bangladesh. Search verified hospitals, emergency blood banks,
            and post a real blood request directly to any hospital on the platform.
        </p>

        <div class="hero-buttons">

            <button id="findHospital">
                <i class="fa-solid fa-hospital"></i>
                Find Hospital
            </button>

            <button id="requestHospitalBtn">
                <i class="fa-solid fa-droplet"></i>
                Request Blood
            </button>

        </div>

        <div class="hero-stats">

            <div>
                <h2 id="statHospitals">4</h2>
                <p>Partner Hospitals</p>
            </div>

            <div>
                <h2 id="statRequests">0</h2>
                <p>Active Requests</p>
            </div>

            <div>
                <h2>24/7</h2>
                <p>Emergency</p>
            </div>

        </div>

    </div>

    <div class="hero-right">
        <img src="images/hospital-banner.png" alt="Hospital" onerror="this.style.display='none'">
    </div>

</section>

<section class="search-section">

    <h2>Search Hospitals</h2>
    <p>Search hospitals by city, blood group availability, or type.</p>

    <div class="search-box">

        <input type="text" id="location" placeholder="Enter city or area">

        <select id="bloodGroup">
            <option>Blood Group</option>
            <option>A+</option>
            <option>A-</option>
            <option>B+</option>
            <option>B-</option>
            <option>AB+</option>
            <option>AB-</option>
            <option>O+</option>
            <option>O-</option>
        </select>

        <select id="hospitalType">
            <option>Hospital Type</option>
            <option>Government</option>
            <option>Private</option>
        </select>

        <button id="searchHospital">
            <i class="fa-solid fa-magnifying-glass"></i>
            Search
        </button>

    </div>

</section>

<section class="map-section">

    <div class="section-heading">
        <span>LIVE MAP</span>
        <h2>Hospitals Near You</h2>
        <p>Your current location is centered on the map — nearby hospitals are highlighted around it.</p>
    </div>

    <div id="hospitalMap"></div>

</section>

<section>

    <div class="section-heading">
        <span>TOP RATED</span>
        <h2>Featured Hospitals</h2>
        <p>Verified hospitals with emergency blood support and 24-hour medical services.</p>
    </div>

    <div class="hospital-grid" id="hospitalGrid"></div>

</section>

<section class="requests-section">

    <div class="section-heading">
        <span>LIVE REQUESTS</span>
        <h2>Active Hospital Blood Requests</h2>
        <p>Real requests posted through Create Post, tagged to a specific hospital.</p>
    </div>

    <div class="hospital-request-list" id="hospitalRequestList"></div>

</section>

<section class="blood-network">

    <div class="section-heading">
        <span>BLOOD NETWORK</span>
        <h2>Partner Blood Banks</h2>
        <p>Blood banks connected with BloodConnect for faster emergency response.</p>
    </div>

    <div class="blood-bank-grid">

        <div class="bank-card">
            <i class="fa-solid fa-droplet"></i>
            <h3>Quantum Blood Bank</h3>
            <h2>420+</h2>
            <p>Available Blood Units</p>
        </div>

        <div class="bank-card">
            <i class="fa-solid fa-heart-circle-plus"></i>
            <h3>Red Crescent</h3>
            <h2>350+</h2>
            <p>Available Blood Units</p>
        </div>

        <div class="bank-card">
            <i class="fa-solid fa-kit-medical"></i>
            <h3>National Blood Centre</h3>
            <h2>510+</h2>
            <p>Available Blood Units</p>
        </div>

        <div class="bank-card">
            <i class="fa-solid fa-hand-holding-medical"></i>
            <h3>Central Blood Bank</h3>
            <h2>275+</h2>
            <p>Available Blood Units</p>
        </div>

    </div>

</section>

<footer>

    <div class="footer-content">

        <div class="footer-box">
            <h2>BloodConnect</h2>
            <p>Connecting blood donors, hospitals and blood banks to save lives through one secure platform.</p>
        </div>

        <div class="footer-box">
            <h3>Quick Links</h3>
            <a href="index.php">Home</a>
            <a href="donors.php">Donors</a>
            <a href="blood-requests.php">Blood Requests</a>
            <a href="profile.php">Profile</a>
        </div>

        <div class="footer-box">
            <h3>Support</h3>
            <a href="#">Help Center</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
        </div>

        <div class="footer-box">
            <h3>Emergency</h3>
            <p>24/7 Blood Support</p>
            <h2>📞 999</h2>
        </div>

    </div>

    <div class="footer-bottom">
        © 2026 BloodConnect. All Rights Reserved.
    </div>

</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    if (profileBtn) {
        profileBtn.addEventListener("click", function () {
            window.location.href = "profile.php";
        });
    }

    const findHospitalBtn = document.getElementById("findHospital");
    if (findHospitalBtn) {
        findHospitalBtn.addEventListener("click", function () {
            document.getElementById("hospitalGrid").scrollIntoView({ behavior: "smooth" });
        });
    }

    const requestHospitalBtn = document.getElementById("requestHospitalBtn");
    if (requestHospitalBtn) {
        requestHospitalBtn.addEventListener("click", function () {
            window.location.href = "createpost.php";
        });
    }

    const hospitals = [
        {
            name: "Sylhet MAG Osmani Medical College Hospital",
            location: "Kajolshah, Sylhet",
            type: "Government",
            status: "emergency",
            statusLabel: "Emergency",
            rating: "4.6",
            desc: "The largest government hospital in Sylhet with a dedicated emergency blood bank and trauma center.",
            bloodGroups: ["A+", "B+", "O+", "AB+"],
            phone: "tel:+880821-713736",
            photo: "samples/somc.jpg",
            lat: 24.9004,
            lng: 91.8687
        },
        {
            name: "Mount Adora Hospital",
            location: "Amberkhana, Sylhet",
            type: "Private",
            status: "open",
            statusLabel: "Open 24/7",
            rating: "4.7",
            desc: "Leading private hospital in Sylhet offering advanced diagnostics, surgery, and blood transfusion services.",
            bloodGroups: ["O+", "O-", "A+", "B-"],
            phone: "tel:+880821-711750",
            photo: "samples/adora.png",

            lat: 24.8996,
            lng: 91.8710
        },
        {
            name: "North East Medical College Hospital",
            location: "Toltikor, Sylhet",
            type: "Private",
            status: "open",
            statusLabel: "Available",
            rating: "4.5",
            desc: "Teaching hospital with a growing blood donation program and 24-hour emergency services.",
            bloodGroups: ["A+", "AB-", "O+", "B+"],
            phone: "tel:+880821-761020",
            photo: "samples/nemc.webp",

            lat: 24.8811,
            lng: 91.8493
        },
        {
            name: "Ibn Sina Hospital, Sylhet",
            location: "Subid Bazar, Sylhet",
            type: "Private",
            status: "open",
            statusLabel: "Open",
            rating: "4.6",
            desc: "Modern private hospital with ICU, surgical care, and an on-site blood bank for patients and donors.",
            bloodGroups: ["A-", "AB+", "O+", "B+"],
            phone: "tel:+880821-2871644",
                        photo: "samples/ibn.jpg",

            lat: 24.8935,
            lng: 91.8630
        },
       {
            name: "Combined Military Hospital (CMH) Sylhet",
            location: "Sylhet Cantonment, Sylhet",
            type: "Government",
            status: "open",
            statusLabel: "Open 24/7",
            rating: "4.8",
            desc: "Military hospital serving both armed forces personnel and civilians, with a well-equipped blood bank and trauma unit.",
            bloodGroups: ["A+", "B+", "O+", "O-"],
            phone: "tel:+880821-716317",
            photo: "samples/cmh.jfif",
            lat: 24.9382901,
            lng: 91.9790164
        }
    ];

    const grid = document.getElementById("hospitalGrid");

    hospitals.forEach(function (h, index) {

        const card = document.createElement("div");
        card.className = "hospital-card";
        card.dataset.index = index;

        card.innerHTML = `
            <div class="hospital-image">
                <img src="${h.photo}" alt="${h.name}" onerror="this.src='images/user.png'">
                <span class="status-tag ${h.status}">${h.statusLabel}</span>
            </div>
            <div class="hospital-content">
                <div class="title-row">
                    <h3>${h.name}</h3>
                    <span class="rating"><i class="fa-solid fa-star"></i> ${h.rating}</span>
                </div>
                <p class="location"><i class="fa-solid fa-location-dot"></i> ${h.location}</p>
                <p class="description">${h.desc}</p>
                <div class="blood-list">
                    ${h.bloodGroups.map(g => `<span>${g}</span>`).join("")}
                </div>
                <div class="hospital-buttons">
                    <button class="callBtn"><i class="fa-solid fa-phone"></i> Call</button>
                    <button class="mapBtn"><i class="fa-solid fa-location-crosshairs"></i> Directions</button>
                </div>
            </div>
        `;

        grid.appendChild(card);

        card.querySelector(".callBtn").addEventListener("click", function () {
            window.location.href = h.phone;
        });

        card.querySelector(".mapBtn").addEventListener("click", function () {
            window.open("https://www.google.com/maps/dir/?api=1&destination=" + h.lat + "," + h.lng, "_blank");
        });

    });

    document.getElementById("statHospitals").textContent = hospitals.length;

    let map;
    const hospitalMarkers = [];

    function initMap(centerLat, centerLng) {

        map = L.map("hospitalMap").setView([centerLat, centerLng], 13);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors"
        }).addTo(map);

        const userIcon = L.divIcon({
            className: "",
            html: '<div style="background:#1a73e8;width:16px;height:16px;border-radius:50%;border:3px solid #fff;box-shadow:0 0 0 4px rgba(26,115,232,.3);"></div>',
            iconSize: [16, 16]
        });

        L.marker([centerLat, centerLng], { icon: userIcon })
            .addTo(map)
            .bindPopup("You are here")
            .openPopup();

        hospitals.forEach(function (h) {

            const hospitalIcon = L.divIcon({
                className: "",
                html: '<div style="background:#d62828;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(214,40,40,.4);"><i class="fa-solid fa-hospital" style="transform:rotate(45deg);color:#fff;font-size:13px;"></i></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });

            const marker = L.marker([h.lat, h.lng], { icon: hospitalIcon }).addTo(map);

            marker.bindPopup(
                "<strong>" + h.name + "</strong><br>" +
                h.location + "<br>" +
                "Blood: " + h.bloodGroups.join(", ")
            );

            marker.hospitalName = h.name;

            hospitalMarkers.push(marker);

        });

        loadNearbyHospitals(centerLat, centerLng);

    }

    function loadNearbyHospitals(centerLat, centerLng) {

        const radius = 8000;

        const query = `[out:json];(
            node["amenity"="hospital"](around:${radius},${centerLat},${centerLng});
            way["amenity"="hospital"](around:${radius},${centerLat},${centerLng});
            relation["amenity"="hospital"](around:${radius},${centerLat},${centerLng});
        );out center;`;

        const url = "https://overpass-api.de/api/interpreter?data=" + encodeURIComponent(query);

        fetch(url)
            .then(function (res) { return res.json(); })
            .then(function (data) {

                data.elements.forEach(function (el) {

                    const elLat = el.lat || (el.center && el.center.lat);
                    const elLng = el.lon || (el.center && el.center.lon);

                    if (!elLat || !elLng) return;

                    const name = (el.tags && el.tags.name) || "Unnamed Hospital";

                    const isFeatured = hospitals.some(function (h) {
                        return Math.abs(h.lat - elLat) < 0.003 && Math.abs(h.lng - elLng) < 0.003;
                    });

                    if (isFeatured) return;

                    const nearbyIcon = L.divIcon({
                        className: "",
                        html: '<div style="background:#6b7280;width:24px;height:24px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 3px 8px rgba(0,0,0,.3);"><i class="fa-solid fa-hospital" style="transform:rotate(45deg);color:#fff;font-size:10px;"></i></div>',
                        iconSize: [24, 24],
                        iconAnchor: [12, 24]
                    });

                    L.marker([elLat, elLng], { icon: nearbyIcon })
                        .addTo(map)
                        .bindPopup("<strong>" + name + "</strong><br>Nearby hospital");

                });

            })
            .catch(function (err) {
                console.log("Could not load nearby hospitals:", err);
            });

    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                initMap(position.coords.latitude, position.coords.longitude);
            },
            function () {
                initMap(24.8949, 91.8687);
            }
        );
    } else {
        initMap(24.8949, 91.8687);
    }

    const locationInput = document.getElementById("location");
    const bloodGroupSelect = document.getElementById("bloodGroup");
    const hospitalTypeSelect = document.getElementById("hospitalType");
    const searchBtn = document.getElementById("searchHospital");
    const cards = document.querySelectorAll(".hospital-card");

    function runSearch() {

        const locationVal = (locationInput.value || "").toLowerCase().trim();
        const groupVal = bloodGroupSelect.value;
        const typeVal = hospitalTypeSelect.value;

        let matchedName = null;

        cards.forEach(function (card) {

            const h = hospitals[card.dataset.index];

            const locationMatch = locationVal === "" || h.location.toLowerCase().includes(locationVal) || h.name.toLowerCase().includes(locationVal);
            const groupMatch = groupVal === "Blood Group" || h.bloodGroups.includes(groupVal);
            const typeMatch = typeVal === "Hospital Type" || h.type === typeVal;

            if (locationMatch && groupMatch && typeMatch) {
                card.style.display = "block";
                if (!matchedName) matchedName = h.name;
            } else {
                card.style.display = "none";
            }

        });

        if (matchedName && map) {
            const marker = hospitalMarkers.find(m => m.hospitalName === matchedName);
            if (marker) {
                map.setView(marker.getLatLng(), 15);
                marker.openPopup();
            }
        }

    }

    if (searchBtn) searchBtn.addEventListener("click", runSearch);

    function renderHospitalRequests() {

        const list = document.getElementById("hospitalRequestList");
        const allRequests = JSON.parse(localStorage.getItem("bloodRequests")) || [];

        const hospitalNames = hospitals.map(h => h.name.toLowerCase());

        const matched = allRequests.filter(function (r) {
            const h = (r.hospital || "").toLowerCase();
            return hospitalNames.some(name => h.includes(name.split(" ")[0].toLowerCase())) || r.hospital;
        }).filter(r => r.status !== "Completed");

        list.innerHTML = "";

        document.getElementById("statRequests").textContent = matched.length;

        if (matched.length === 0) {
            list.innerHTML = `<div class="empty-state">No active hospital blood requests right now. Create one from Create Post.</div>`;
            return;
        }

        matched.slice().reverse().forEach(function (r) {

            const card = document.createElement("div");
            card.className = "hospital-request-card";

            card.innerHTML = `
                <div class="req-info">
                    <h4>${r.bloodGroup} needed at ${r.hospital}</h4>
                    <p>${r.patientName || "Patient"} &middot; ${r.location || "Location not specified"} &middot; ${r.units || 1} unit(s)</p>
                </div>
                <span class="req-tag">${r.urgency || "Normal"}</span>
            `;

            list.appendChild(card);

        });

    }

    renderHospitalRequests();

    window.addEventListener("focus", renderHospitalRequests);

});
</script>

</body>
</html>