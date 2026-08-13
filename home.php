<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$me = getCurrentUser($conn);

$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalRequests = $conn->query("SELECT COUNT(*) c FROM blood_requests")->fetch_assoc()['c'];
$totalDonations = $conn->query("SELECT COUNT(*) c FROM donations WHERE status='Completed'")->fetch_assoc()['c'];
$hospitalCount = $conn->query("SELECT COUNT(*) c FROM hospitals")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect - Home</title>

    <style>
        :root {
            --primary: #d62828;
            --primary-dark: #b91d1d;
            --primary-light: #ff6b6b;
            --teal: #0f9b8e;
            --amber: #f4a340;
            --ink: #2b2320;
            --muted: #6b6560;
            --card: #ffffff;
            --border: rgba(43, 35, 32, .08);
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, .06);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, .08);
            --shadow-lg: 0 20px 45px rgba(214, 40, 40, .14);
            --radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden;

            background:
                radial-gradient(circle at 10% 0%, rgba(224, 48, 47, .06) 0%, transparent 45%),
                radial-gradient(circle at 90% 15%, rgba(15, 155, 142, .05) 0%, transparent 40%),
                radial-gradient(circle at 50% 100%, rgba(244, 163, 64, .05) 0%, transparent 50%),
                linear-gradient(180deg, #fffaf7 0%, #f8f6f3 40%, #f4f2ef 100%);
            background-attachment: fixed;
        }

        /* ============ NAV (unchanged) ============ */

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

        /* ============ LAYOUT ============ */

        main {
            width: 84%;
            max-width: 1200px;
            margin: 40px auto;
        }

        section {
            opacity: 0;
            transform: translateY(18px);
            animation: rise .7s ease forwards;
        }

        section:nth-of-type(1) { animation-delay: .02s; }
        section:nth-of-type(2) { animation-delay: .08s; }
        section:nth-of-type(3) { animation-delay: .14s; }
        section:nth-of-type(4) { animation-delay: .2s; }

        @keyframes rise {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            section {
                opacity: 1;
                transform: none;
                animation: none;
            }
        }

        /* ============ WELCOME / HERO ============ */

        .welcome {
            position: relative;
            border-radius: 20px;
            padding: 70px 60px;
            margin-bottom: 44px;
            text-align: center;
            overflow: hidden;
            color: #fff;
            background:
                radial-gradient(circle at 15% 20%, rgba(255, 255, 255, .14) 0%, transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(255, 255, 255, .10) 0%, transparent 45%),
                linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 65%, #8f1717 100%);
            box-shadow: var(--shadow-lg);
        }

        .welcome::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(rgba(255, 255, 255, .18) 1.5px, transparent 1.5px);
            background-size: 26px 26px;
            opacity: .25;
            pointer-events: none;
        }

        .welcome-text {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: rgba(255, 255, 255, .16);
            border: 1px solid rgba(255, 255, 255, .3);
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 22px;
        }

        .welcome h1 {
            color: #fff;
            font-size: 48px;
            margin-bottom: 20px;
            letter-spacing: -.5px;
        }

        .welcome p {
            max-width: 700px;
            margin: 0 auto 34px;
            font-size: 18px;
            line-height: 1.8;
            text-align: center;
            color: rgba(255, 255, 255, .92);
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-actions a {
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: .25s;
        }

        .btn-light {
            background: #fff;
            color: var(--primary);
        }

        .btn-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, .18);
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, .6);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, .12);
            transform: translateY(-3px);
        }

        /* ============ STAT CARDS ============ */

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin-bottom: 50px;
        }

        .card {
            position: relative;
            background: var(--card);
            padding: 32px 28px;
            text-align: left;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: .3s;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary), var(--amber));
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
        }

        .card .stat-icon {
            font-size: 22px;
            margin-bottom: 14px;
        }

        .card h2 {
            color: var(--primary);
            font-size: 36px;
            margin-bottom: 6px;
            letter-spacing: -.5px;
        }

        .card p {
            color: var(--muted);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ============ SEARCH ============ */

        .search {
            background: var(--card);
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            margin-bottom: 50px;
        }

        .search h2 {
            color: var(--primary);
            margin-bottom: 6px;
        }

        .search .sub {
            color: var(--muted);
            font-size: 14px;
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
            padding: 14px 16px;
            border: 1px solid #e2ddd8;
            background: #fbfaf9;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
            transition: .2s;
        }

        .search-box select:focus,
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(214, 40, 40, .12);
            background: #fff;
        }

        .search-box button {
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: white;
            cursor: pointer;
            font-weight: 700;
            transition: .25s;
        }

        .search-box button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(214, 40, 40, .28);
        }

        /* ============ QUICK ACCESS ============ */

        .quick-access {
            margin-bottom: 50px;
        }

        .section-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .quick-access h2,
        .requests h2,
        .inventory h2,
        .activity h2 {
            color: var(--primary);
        }

        .section-head .tag {
            font-size: 13px;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .item {
            background: var(--card);
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: .3s;
        }

        .item:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: rgba(214, 40, 40, .25);
        }

        .item .item-icon {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(214, 40, 40, .08);
            color: var(--primary);
            font-size: 20px;
            margin-bottom: 16px;
        }

        .item h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .item p {
            margin-bottom: 22px;
            color: var(--muted);
            line-height: 1.6;
        }

        .item button {
            padding: 10px 22px;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            cursor: pointer;
            font-weight: 600;
            transition: .25s;
        }

        .item button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* ============ REQUESTS TABLE ============ */

        .requests {
            margin-bottom: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card);
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        thead {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        th,
        td {
            padding: 18px;
            text-align: left;
        }

        th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        tbody tr {
            transition: .2s;
        }

        tbody tr:nth-child(even) {
            background: #faf8f7;
        }

        tbody tr:hover {
            background: #ffecec;
        }

        /* ============ INVENTORY ============ */

        .inventory {
            margin-bottom: 50px;
        }

        .inventory-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .blood-card {
            position: relative;
            background: var(--card);
            padding: 30px;
            text-align: center;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: .3s;
        }

        .blood-card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--amber));
            border-radius: 0 0 var(--radius) var(--radius);
        }

        .blood-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: var(--shadow-md);
        }

        .blood-card h3 {
            color: var(--primary);
            font-size: 32px;
            margin-bottom: 12px;
        }

        .blood-card p {
            color: var(--muted);
            font-size: 14px;
        }

        /* ============ ACTIVITY ============ */

        .activity {
            margin-bottom: 60px;
        }

        .activity ul {
            list-style: none;
            background: var(--card);
            padding: 10px 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }

        .activity li {
            margin: 0;
            padding: 16px 0;
            line-height: 1.6;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity li:last-child {
            border-bottom: none;
        }

        .activity li::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            flex-shrink: 0;
        }

        /* ============ FOOTER ============ */

        footer {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            text-align: center;
            padding: 40px;
            border-radius: 20px 20px 0 0;
        }

        footer h3 {
            margin-bottom: 10px;
            font-size: 22px;
        }

        footer p {
            margin-top: 8px;
            color: rgba(255, 255, 255, .85);
        }

        /* ============ RESPONSIVE ============ */

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
                padding: 45px 30px;
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

                <span class="eyebrow">Every Drop Counts</span>

                <h1>Welcome to BloodConnect</h1>

                <p>
                    Help save lives by donating blood, responding to emergency
                    requests, and connecting patients with verified donors.
                </p>

                <div class="hero-actions">
                    <a href="donors.php"><button class="btn btn-light">Find a Donor</button></a>
                    <a href="emergency-requests.php"><button class="btn btn-outline">View Emergencies</button></a>
                </div>

            </div>

        </section>

        <section class="stats">

            <div class="card">
                <div class="stat-icon">🩸</div>
                <h2><?php echo (int)$totalUsers; ?>+</h2>
                <p>Registered Donors</p>
            </div>

            <div class="card">
                <div class="stat-icon">📋</div>
                <h2><?php echo (int)$totalRequests; ?>+</h2>
                <p>Blood Requests</p>
            </div>

            <div class="card">
                <div class="stat-icon">✅</div>
                <h2><?php echo (int)$totalDonations; ?>+</h2>
                <p>Successful Donations</p>
            </div>

            <div class="card">
                <div class="stat-icon">🏥</div>
                <h2><?php echo (int)$hospitalCount; ?>+</h2>
                <p>Partner Hospitals</p>
            </div>

        </section>

        <section class="search">

            <h2>Find a Donor</h2>
            <p class="sub">Search verified donors by blood group and location.</p>

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
                <input type="text" placeholder="Enter location">
                <button id="searchBtn">Search</button>
            </div>

        </section>

        <section class="quick-access">

            <div class="section-head">
                <h2>Quick Access</h2>
                <span class="tag">Jump to what you need</span>
            </div>

            <div class="grid">

                <div class="item">
                    <div class="item-icon">🔍</div>
                    <h3>Find Donors</h3>
                    <p>Browse available donors by blood group.</p>
                    <a href="donors.php"><button>Open</button></a>
                </div>

                <div class="item">
                    <div class="item-icon">📄</div>
                    <h3>Blood Requests</h3>
                    <p>View and manage all blood requests.</p>
                    <a href="blood-requests.php"><button>Open</button></a>
                </div>

                <div class="item">
                    <div class="item-icon">🚨</div>
                    <h3>Emergency</h3>
                    <p>Respond to urgent blood requests.</p>
                    <a href="emergency-requests.php"><button>Open</button></a>
                </div>

                <div class="item">
                    <div class="item-icon">🏥</div>
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
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>B+</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>AB+</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>O+</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>O-</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>A-</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>B-</h3>
                    <p>Available: 0 Units</p>
                </div>

                <div class="blood-card">
                    <h3>AB-</h3>
                    <p>Available: 0 Units</p>
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

            // Animates from 0 up to the real, server-computed count for each
            // stat card. The card already shows the correct final number
            // (rendered by PHP) before JS even runs, so there is no flash of
            // fake placeholder data — this animation is purely a visual effect
            // layered on top of real numbers, not the source of truth for them.
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

                if (target === 0) {
                    el.textContent = "0" + suffix;
                } else {
                    step();
                }

            }

            function loadStats() {

                const stats = JSON.parse(document.getElementById("homeStats").textContent);

                const users = stats.users;
                const requests = stats.requests;
                const donations = stats.donations;
                const hospitalCount = stats.hospitalCount;

                const statCards = document.querySelectorAll(".stats .card h2");

                if (statCards[0]) animateCounter(statCards[0], users, "+");
                if (statCards[1]) animateCounter(statCards[1], requests, "+");
                if (statCards[2]) animateCounter(statCards[2], donations, "+");
                if (statCards[3]) animateCounter(statCards[3], hospitalCount, "+");

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