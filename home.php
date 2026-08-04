<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$me = getCurrentUser($conn);

$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalRequests = $conn->query("SELECT COUNT(*) c FROM blood_requests")->fetch_assoc()['c'];
$totalDonations = $conn->query("SELECT COUNT(*) c FROM posts WHERE post_type='Blood Available'")->fetch_assoc()['c'];
$hospitalCount = $conn->query("SELECT COUNT(DISTINCT LOWER(hospital)) c FROM blood_requests WHERE hospital <> ''")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Home</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
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

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 8%;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo h2 {
            color: #d62828;
            margin-bottom: 5px;
        }

        .logo p {
            color: #777;
            font-size: 14px;
        }

        nav {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: .3s;
        }

        nav a:hover,
        nav a.active {
            color: #d62828;
        }

        #logoutBtn {
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            background: #d62828;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        #logoutBtn:hover {
            background: #b91d1d;
        }

        main {
            width: 84%;
            margin: 40px auto;
        }

        .welcome {
            color: var(--ink);
    line-height: 1.6;
    overflow-x: hidden;

    background:
        radial-gradient(circle at 10% 0%, rgba(224,48,47,.06) 0%, transparent 45%),
        radial-gradient(circle at 90% 15%, rgba(15,155,142,.05) 0%, transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(244,163,64,.05) 0%, transparent 50%),
        linear-gradient(180deg, #fffaf7 0%, #f8f6f3 40%, #f4f2ef 100%);
            border-radius: 12px;
            padding: 60px;
            
            margin-bottom: 40px;
            text-align: center;
        }

        .welcome h1 {
            color: #d62828;
            font-size: 48px;
            margin-bottom: 20px;
        }

        .welcome p {
            max-width: 700px;
            margin: 0 auto 30px;
            font-size: 18px;
            line-height: 1.8;
            text-align: center;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .card {
            background: white;
            padding: 35px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card h2 {
            color: #d62828;
            font-size: 38px;
            margin-bottom: 10px;
        }

        .card p {
            color: #666;
        }

        .search {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            margin-bottom: 50px;
        }

        .search h2 {
            color: #d62828;
            margin-bottom: 25px;
        }

        .search-box {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-box select,
        .search-box input {
            flex: 1;
            min-width: 220px;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            font-size: 15px;
        }

        .search-box button {
            padding: 14px 30px;
            border: none;
            border-radius: 6px;
            background: #d62828;
            color: white;
            cursor: pointer;
            transition: .3s;
        }

        .search-box button:hover {
            background: #b91d1d;
        }

        .quick-access {
            margin-bottom: 50px;
        }

        .quick-access h2 {
            color: #d62828;
            margin-bottom: 25px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .item {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .item:hover {
            transform: translateY(-6px);
        }

        .item h3 {
            color: #d62828;
            margin-bottom: 15px;
        }

        .item p {
            margin-bottom: 20px;
            color: #666;
            line-height: 1.6;
        }

        .item button {
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            background: #d62828;
            color: white;
            cursor: pointer;
        }

        .item button:hover {
            background: #b91d1d;
        }

        .requests {
            margin-bottom: 50px;
        }

        .requests h2 {
            color: #d62828;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background: #d62828;
            color: white;
        }

        th,
        td {
            padding: 18px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background: #f8f8f8;
        }

        tbody tr:hover {
            background: #ffecec;
        }

        .inventory {
            margin-bottom: 50px;
        }

        .inventory h2 {
            color: #d62828;
            margin-bottom: 25px;
        }

        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .blood-card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .blood-card:hover {
            transform: translateY(-6px);
        }

        .blood-card h3 {
            color: #d62828;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .activity {
            margin-bottom: 60px;
        }

        .activity h2 {
            color: #d62828;
            margin-bottom: 20px;
        }

        .activity ul {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        .activity li {
            margin: 15px 0;
            line-height: 1.6;
        }

        footer {
            background: #d62828;
            color: white;
            text-align: center;
            padding: 35px;
        }

        footer h3 {
            margin-bottom: 10px;
        }

        footer p {
            margin-top: 8px;
        }

        @media(max-width:1000px) {

            header {
                flex-direction: column;
                gap: 20px;
            }

            nav {
                justify-content: center;
            }

            main {
                width: 92%;
            }

            .welcome {
                padding: 40px;
                text-align: center;
            }

            .welcome h1 {
                font-size: 38px;
            }

            .actions {
                justify-content: center;
            }

            .search-box {
                flex-direction: column;
            }
        }

        @media(max-width:600px) {

            .welcome h1 {
                font-size: 30px;
            }

            .welcome p {
                font-size: 16px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

    <header>

        <div class="logo">
            <h2>BloodConnect</h2>
            <p>Welcome Back</p>
        </div>

        <nav>
            <a href="home.php" class="active">Home</a>
            <a href="donors.php">Donors</a>
            <a href="blood-requests.php">Blood Requests</a>
            <a href="emergency-requests.php">Emergency</a>
            <a href="Hospitals.php">Hospitals</a>
            <a href="profile.php">Profile</a>
        </nav>

        <button id="logoutBtn">Logout</button>

    </header>

    <main>

        <section class="welcome">

            <div class="welcome-text">

                <h1>Welcome to BloodConnect</h1>

                <p>
                    Help save lives by donating blood, responding to emergency
                    requests, and connecting patients with verified donors.
                </p>


            </div>

        </section>

        <section class="stats">

            <div class="card">
                <h2>1,250+</h2>
                <p>Registered Donors</p>
            </div>

            <div class="card">
                <h2>320+</h2>
                <p>Blood Requests</p>
            </div>

            <div class="card">
                <h2>480+</h2>
                <p>Successful Donations</p>
            </div>

            <div class="card">
                <h2>65+</h2>
                <p>Partner Hospitals</p>
            </div>

        </section>

        <section class="quick-access">

            <h2>Quick Access</h2>

            <div class="grid">

                <div class="item">
                    <h3>Find Donors</h3>
                    <p>Browse available donors by blood group.</p>
                    <a href="donors.php"><button>Open</button></a>

                </div>

                <div class="item">
                    <h3>Blood Requests</h3>
                    <p>View and manage all blood requests.</p>
                    <a href="blood-requests.php"><button>Open</button></a>
                </div>

                <div class="item">
                    <h3>Emergency</h3>
                    <p>Respond to urgent blood requests.</p>
                    <a href="emergency-requests.php"><button>Open</button></a>
                </div>


                <div class="item">
                    <h3>Hospitals</h3>
                    <p>View nearby hospitals and blood banks.</p>
                    <a href="Hospitals.php"><button>Open</button></a>
                </div>

            </div>

        </section>


        <section class="inventory">

            <h2>Blood Inventory</h2>

            <div class="inventory-grid">

                <div class="blood-card">
                    <h3>A+</h3>
                    <p>Available: 45 Units</p>
                </div>

                <div class="blood-card">
                    <h3>B+</h3>
                    <p>Available: 30 Units</p>
                </div>

                <div class="blood-card">
                    <h3>AB+</h3>
                    <p>Available: 15 Units</p>
                </div>

                <div class="blood-card">
                    <h3>O+</h3>
                    <p>Available: 62 Units</p>
                </div>

                <div class="blood-card">
                    <h3>O-</h3>
                    <p>Available: 12 Units</p>
                </div>

                <div class="blood-card">
                    <h3>A-</h3>
                    <p>Available: 18 Units</p>
                </div>

                <div class="blood-card">
                    <h3>B-</h3>
                    <p>Available: 10 Units</p>
                </div>

                <div class="blood-card">
                    <h3>AB-</h3>
                    <p>Available: 6 Units</p>
                </div>

            </div>

        </section>


    </main>

    <footer>

        <h3>BloodConnect</h3>

        <p>Together We Save Lives.</p>

        <p>© 2026 BloodConnect. All Rights Reserved.</p>

    </footer>

    <script type="application/json" id="homeStats">
<?php echo json_encode([
    "users" => (int)$totalUsers,
    "requests" => (int)$totalRequests,
    "donations" => (int)$totalDonations,
    "hospitalCount" => (int)$hospitalCount
]); ?>
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const logoutBtn = document.getElementById("logoutBtn");

            if (logoutBtn) {
                logoutBtn.addEventListener("click", function () {
                    const confirmLogout = confirm("Are you sure you want to logout?");
                    if (confirmLogout) {
                        fetch("api/logout.php", { credentials: "same-origin" }).then(function () {
                            window.location.href = "index.php";
                        });
                    }
                });
            }

            const searchBtn = document.getElementById("searchBtn");

            if (searchBtn) {
                searchBtn.addEventListener("click", function () {

                    const bloodGroup = document.getElementById("bloodGroup").value;
                    const locationInput = document.querySelector(".search-box input");
                    const location = locationInput ? locationInput.value.trim() : "";

                    if (!bloodGroup || bloodGroup === "Select Blood Group") {
                        alert("Please select a blood group.");
                        return;
                    }

                    if (location === "") {
                        alert("Please enter a location.");
                        return;
                    }

                    alert("Searching for " + bloodGroup + " donors in " + location + ".");

                });
            }

            const quickButtons = document.querySelectorAll(".item button");

            const quickLinks = [
                "donors.php",
                "blood-requests.php",
                "emergency-requests.php",
                "Hospitals.php"
            ];

            quickButtons.forEach(function (button, index) {
                button.addEventListener("click", function () {
                    if (quickLinks[index]) {
                        window.location.href = quickLinks[index];
                    }
                });
            });

            function animateCounter(el, target, suffix) {

                let current = 0;
                const increment = Math.max(1, Math.ceil(target / 60));

                function step() {
                    current += increment;
                    if (current < target) {
                        el.textContent = current.toLocaleString() + suffix;
                        requestAnimationFrame(step);
                    } else {
                        el.textContent = target.toLocaleString() + suffix;
                    }
                }

                step();

            }

            function loadStats() {

                const stats = JSON.parse(document.getElementById("homeStats").textContent);

                const users = stats.users;
                const requests = stats.requests;
                const donations = stats.donations;
                const hospitalCount = stats.hospitalCount;

                const statCards = document.querySelectorAll(".stats .card h2");

                if (statCards[0]) animateCounter(statCards[0], users, "");
                if (statCards[1]) animateCounter(statCards[1], requests, "");
                if (statCards[2]) animateCounter(statCards[2], donations, "");
                if (statCards[3]) animateCounter(statCards[3], hospitalCount, "");

            }

            loadStats();

            function loadInventory() {

                fetch("api/posts_list.php?postType=" + encodeURIComponent("Blood Available"), { credentials: "same-origin" })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {

                        const availablePosts = data.success ? data.posts : [];

                        const groups = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"];
                        const counts = {};

                        groups.forEach(g => counts[g] = 0);

                        availablePosts.forEach(function (p) {
                            if (counts[p.blood_group] !== undefined) {
                                counts[p.blood_group]++;
                            }
                        });

                        const bloodCards = document.querySelectorAll(".blood-card");

                        bloodCards.forEach(function (card) {

                            const group = card.querySelector("h3").textContent.trim();
                            const countEl = card.querySelector("p");

                            if (countEl) {
                                countEl.textContent = "Available: " + (counts[group] || 0) + " Units";
                            }

                        });

                    });

            }

            loadInventory();

            const inventoryCards = document.querySelectorAll(".blood-card");

            inventoryCards.forEach(function (card) {
                card.addEventListener("mouseenter", function () {
                    card.style.transform = "translateY(-8px) scale(1.03)";
                });
                card.addEventListener("mouseleave", function () {
                    card.style.transform = "translateY(0)";
                });
            });

            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(function (link) {
                link.addEventListener("click", function () {
                    console.log("Opening " + link.textContent);
                });
            });

            window.addEventListener("focus", function () {
                loadStats();
                loadInventory();
            });

        });
    </script>

</body>
</html>