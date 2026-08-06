<?php require_once __DIR__ . '/includes/auth.php'; requireLogin(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Blood Requests</title>

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

#dashboardBtn{
    padding:10px 22px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
    transition:.3s;
}

#dashboardBtn:hover{
    background:#b91d1d;
}

main{
    width:84%;
    margin:40px auto;
}

.hero{
    text-align:center;
    margin-bottom:45px;
}

.hero h1{
    font-size:44px;
    color:#d62828;
    margin-bottom:15px;
}

.hero p{
    color:#666;
    font-size:18px;
    max-width:700px;
    margin:auto;
    line-height:1.7;
}

.request-form{
    background:#fff;
    padding:35px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    margin-bottom:40px;
}

.request-form h2{
    color:#d62828;
    margin-bottom:25px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}

.input-box{
    display:flex;
    flex-direction:column;
}

.input-box label{
    margin-bottom:8px;
    font-weight:600;
}

.input-box input,
.input-box select,
textarea{
    padding:14px;
    border:1px solid #ddd;
    border-radius:6px;
    outline:none;
    font-size:15px;
}

.input-box input:focus,
.input-box select:focus,
textarea:focus{
    border-color:#d62828;
}

.textarea{
    margin-top:20px;
}

textarea{
    width:100%;
    resize:vertical;
}

#submitBtn{
    margin-top:25px;
    padding:14px 30px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
    font-size:16px;
    transition:.3s;
}

#submitBtn:hover{
    background:#b91d1d;
}

.search-section{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    margin-bottom:40px;
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

.search-box input{
    flex:1;
    padding:14px;
    border:1px solid #ddd;
    border-radius:6px;
    outline:none;
}

.search-box button{
    padding:14px 28px;
    border:none;
    border-radius:6px;
    background:#d62828;
    color:#fff;
    cursor:pointer;
}

.search-box button:hover{
    background:#b91d1d;
}

.request-list{
    margin-bottom:45px;
}

.request-list h2{
    color:#d62828;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

thead{
    background:#d62828;
    color:#fff;
}

th,
td{
    padding:16px;
    text-align:left;
}

tbody tr:nth-child(even){
    background:#f9f9f9;
}

tbody tr:hover{
    background:#ffeaea;
}

.pending,
.matched,
.completed{
    color:#fff;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:bold;
}

.pending{
    background:#f39c12;
}

.matched{
    background:#3498db;
}

.completed{
    background:#27ae60;
}

.statistics{
    margin-bottom:50px;
}

.statistics h2{
    color:#d62828;
    margin-bottom:25px;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card{
    background:#fff;
    padding:30px;
    text-align:center;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    transition:.3s;
}

.card:hover{
    transform:translateY(-6px);
}

.card h3{
    font-size:38px;
    color:#d62828;
    margin-bottom:10px;
}

.card p{
    color:#666;
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

    .hero h1{
        font-size:34px;
    }

    .request-form{
        padding:25px;
    }

    table{
        display:block;
        overflow-x:auto;
        white-space:nowrap;
    }
}

@media(max-width:500px){

    .hero h1{
        font-size:28px;
    }

    .hero p{
        font-size:16px;
    }
}
    </style>
</head>



<body>

    <header>

        <div class="logo">
            <h2>BloodConnect</h2>
            <p>Blood Request Management</p>
        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="donors.php">Donors</a>
            <a href="blood-requests.php" class="active">Blood Requests</a>
            <a href="emergency-requests.php">Emergency</a>
            <a href="Hospitals.php">Hospitals</a>
            <a href="profile.php">Profile</a>
        </nav>

        <button id="dashboardBtn">Dashboard</button>

    </header>

    <main>

        <section class="hero">

            <h1>Blood Requests</h1>

            <p>
                Create, manage, and track blood requests quickly.
                Connect patients with suitable donors during emergencies.
            </p>

        </section>

        <section class="request-form">

            <h2>Create Blood Request</h2>

            <form id="requestForm">

                <div class="grid">

                    <div class="input-box">
                        <label>Patient Name</label>
                        <input type="text" id="patientName" placeholder="Enter patient name">
                    </div>

                    <div class="input-box">
                        <label>Blood Group</label>
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
                    </div>

                    <div class="input-box">
                        <label>Units Required</label>
                        <input type="number" id="units" placeholder="Units">
                    </div>

                    <div class="input-box">
                        <label>Hospital</label>
                        <input type="text" id="hospital" placeholder="Hospital name">
                    </div>

                    <div class="input-box">
                        <label>Location</label>
                        <input type="text" id="location" placeholder="City / Area">
                    </div>

                    <div class="input-box">
                        <label>Contact Number</label>
                        <input type="tel" id="phone" placeholder="01XXXXXXXXX">
                    </div>

                    <div class="input-box">
                        <label>Urgency</label>
                        <select id="urgency">
                            <option>Normal</option>
                            <option>Urgent</option>
                            <option>Emergency</option>
                        </select>
                    </div>

                    <div class="input-box">
                        <label>Required Date</label>
                        <input type="date" id="date">
                    </div>

                </div>

                <div class="input-box textarea">

                    <label>Additional Notes</label>

                    <textarea id="notes" rows="5"
                        placeholder="Write additional information..."></textarea>

                </div>

                <button type="submit" id="submitBtn">
                    Submit Blood Request
                </button>

            </form>

        </section>

        <section class="search-section">

            <h2>Search Requests</h2>

            <div class="search-box">

                <input type="text" id="searchInput"
                    placeholder="Search by patient, blood group or hospital">

                <button id="searchBtn">
                    Search
                </button>

            </div>

        </section>

        <section class="request-list">

            <h2>Recent Blood Requests</h2>

            <table>

                <thead>

                    <tr>
                        <th>Patient</th>
                        <th>Blood Group</th>
                        <th>Units</th>
                        <th>Hospital</th>
                        <th>Location</th>
                        <th>Urgency</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody id="requestTable">

                    <tr>
                        <td>Ahmed Rahman</td>
                        <td>O+</td>
                        <td>2</td>
                        <td>City Hospital</td>
                        <td>Dhaka</td>
                        <td>Emergency</td>
                        <td><span class="pending">Pending</span></td>
                    </tr>

                    <tr>
                        <td>Fatema Khan</td>
                        <td>A+</td>
                        <td>1</td>
                        <td>Central Medical</td>
                        <td>Chattogram</td>
                        <td>Urgent</td>
                        <td><span class="matched">Matched</span></td>
                    </tr>

                    <tr>
                        <td>Rakib Hasan</td>
                        <td>B-</td>
                        <td>3</td>
                        <td>Green Hospital</td>
                        <td>Sylhet</td>
                        <td>Normal</td>
                        <td><span class="completed">Completed</span></td>
                    </tr>

                    <tr>
                        <td>Nusrat Jahan</td>
                        <td>AB+</td>
                        <td>2</td>
                        <td>Red Crescent</td>
                        <td>Khulna</td>
                        <td>Urgent</td>
                        <td><span class="pending">Pending</span></td>
                    </tr>

                    <tr>
                        <td>Tanvir Islam</td>
                        <td>O-</td>
                        <td>4</td>
                        <td>Popular Hospital</td>
                        <td>Rajshahi</td>
                        <td>Emergency</td>
                        <td><span class="matched">Matched</span></td>
                    </tr>

                </tbody>

            </table>

        </section>

        <section class="statistics">

            <h2>Request Overview</h2>

            <div class="cards">

                <div class="card">
                    <h3>325</h3>
                    <p>Total Requests</p>
                </div>

                <div class="card">
                    <h3>140</h3>
                    <p>Pending</p>
                </div>

                <div class="card">
                    <h3>120</h3>
                    <p>Matched</p>
                </div>

                <div class="card">
                    <h3>65</h3>
                    <p>Completed</p>
                </div>

            </div>

        </section>

    </main>

    <footer>

        <h3>BloodConnect</h3>

        <p>Every Blood Request Brings Hope.</p>

        <p>© 2026 BloodConnect. All Rights Reserved.</p>

    </footer>

    <script>

        document.addEventListener("DOMContentLoaded", function () {

    const dashboardBtn = document.getElementById("dashboardBtn");
    const requestForm = document.getElementById("requestForm");
    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("requestTable");

    dashboardBtn.addEventListener("click", function () {
        window.location.href = "index.php";
    });

    function escapeHtml(str) {
        const div = document.createElement("div");
        div.textContent = str == null ? "" : String(str);
        return div.innerHTML;
    }

    function loadRequests() {

        fetch("api/requests_list.php", { credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {

                const requests = data.success ? data.requests : [];

                tableBody.innerHTML = "";

                if (requests.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align:center; color:#888;">
                                No blood requests yet.
                            </td>
                        </tr>
                    `;
                    updateStats(requests);
                    return;
                }

                requests.slice().reverse().forEach(function (r) {

                    const row = document.createElement("tr");
                    row.dataset.id = r.id;

                    row.innerHTML = `
                        <td>${escapeHtml(r.patient_name)}</td>
                        <td>${escapeHtml(r.blood_group)}</td>
                        <td>${escapeHtml(r.units)}</td>
                        <td>${escapeHtml(r.hospital)}</td>
                        <td>${escapeHtml(r.location)}</td>
                        <td>${escapeHtml(r.urgency)}</td>
                        <td><span class="${escapeHtml((r.status || "").toLowerCase())}">${escapeHtml(r.status)}</span></td>
                    `;

                    tableBody.appendChild(row);

                });

                updateStats(requests);

            });

    }

    function updateStats(requests) {

        const total = requests.length;
        const pending = requests.filter(r => r.status === "Pending").length;
        const matched = requests.filter(r => r.status === "Matched").length;
        const completed = requests.filter(r => r.status === "Completed").length;

        const cardValues = document.querySelectorAll(".card h3");

        if (cardValues[0]) cardValues[0].textContent = total;
        if (cardValues[1]) cardValues[1].textContent = pending;
        if (cardValues[2]) cardValues[2].textContent = matched;
        if (cardValues[3]) cardValues[3].textContent = completed;

    }

    requestForm.addEventListener("submit", function (e) {

        e.preventDefault();

        const patientName = document.getElementById("patientName").value.trim();
        const bloodGroup = document.getElementById("bloodGroup").value;
        const units = document.getElementById("units").value;
        const hospital = document.getElementById("hospital").value.trim();
        const location = document.getElementById("location").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const urgency = document.getElementById("urgency").value;
        const date = document.getElementById("date").value;
        const notes = document.getElementById("notes").value.trim();

        if (
            patientName === "" ||
            bloodGroup === "Select Blood Group" ||
            units === "" ||
            hospital === "" ||
            location === "" ||
            phone === "" ||
            date === ""
        ) {
            alert("Please fill in all required fields.");
            return;
        }

        const formData = new FormData();
        formData.append("patientName", patientName);
        formData.append("bloodGroup", bloodGroup);
        formData.append("units", units);
        formData.append("hospital", hospital);
        formData.append("location", location);
        formData.append("phone", phone);
        formData.append("urgency", urgency);
        formData.append("date", date);
        formData.append("notes", notes);

        fetch("api/requests_create.php", { method: "POST", body: formData, credentials: "same-origin" })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    alert("Blood request submitted successfully.");
                    requestForm.reset();
                    loadRequests();
                } else {
                    alert(data.message || "Could not submit the request.");
                }
            })
            .catch(function (err) {
                console.error(err);
                alert("Server error. Could not submit the request. Check the browser console for details.");
            });

    });

    searchBtn.addEventListener("click", function () {

        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach(function (row) {

            const text = row.textContent.toLowerCase();

            if (text.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }

        });

    });

    searchInput.addEventListener("keyup", function () {
        searchBtn.click();
    });

    const cards = document.querySelectorAll(".card");

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {
            card.style.transform = "translateY(-8px)";
        });

        card.addEventListener("mouseleave", function () {
            card.style.transform = "translateY(0)";
        });

    });

    const navLinks = document.querySelectorAll("nav a");

    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            console.log("Opening: " + link.textContent);
        });
    });

    loadRequests();

});
    </script>

</body>

</html>