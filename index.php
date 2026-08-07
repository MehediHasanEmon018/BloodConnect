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
        /* Reset */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}



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
    --shadow:0 10px 30px rgba(31,36,48,.07);
    --shadow-soft:0 4px 16px rgba(31,36,48,.05);
}

body{
    color: var(--ink);

    background:
        radial-gradient(circle at 8% 0%, rgba(224,48,47,.06) 0%, transparent 45%),
        radial-gradient(circle at 92% 20%, rgba(15,155,142,.05) 0%, transparent 40%),
        radial-gradient(circle at 50% 100%, rgba(244,163,64,.05) 0%, transparent 50%),
        linear-gradient(180deg, #fffaf7 0%, #f8f6f3 40%, #f4f2ef 100%);
    background-attachment: fixed;
}
/* Header */
header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 8%;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    position:sticky;
    top:0;
    z-index:100;
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

header button{
    padding:10px 18px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    margin-left:10px;
    background:#d62828;
    color:white;
}

/* Hero Section */
section{
    padding:60px 8%;
}

.hero{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:60px;
    padding:90px 8% 70px;
    position:relative;
    overflow:hidden;
}

.hero-content{
    flex:1;
    min-width:320px;
    animation:fadeInUp .8s ease both;
}

.hero-badge{
    display:inline-flex;
    align-items:center;
    gap:8px;
    background:var(--red-light);
    color:var(--red-dark);
    font-weight:600;
    font-size:13px;
    padding:8px 16px;
    border-radius:999px;
    margin-bottom:22px;
    letter-spacing:.3px;
}

.hero-content h1{
    font-size:56px;
    line-height:1.15;
    color:var(--ink);
    margin-bottom:22px;
}

.hero-content h1 .highlight{
    color:var(--red);
    position:relative;
    white-space:nowrap;
}

.hero-content h1 .highlight::after{
    content:"";
    position:absolute;
    left:0;
    right:0;
    bottom:6px;
    height:12px;
    background:var(--red-light);
    z-index:-1;
    border-radius:6px;
}

.hero-content p{
    font-size:18px;
    line-height:1.7;
    color:var(--muted);
    max-width:520px;
    margin-bottom:32px;
}

.hero-actions{
    display:flex;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:40px;
}

.btn-primary,
.btn-outline{
    padding:15px 30px;
    border-radius:8px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    border:2px solid transparent;
    transition:.25s;
}

.btn-primary{
    background:var(--red);
    color:white;
    box-shadow:0 10px 24px rgba(224,48,47,.28);
}

.btn-primary:hover{
    background:var(--red-dark);
    transform:translateY(-3px);
    box-shadow:0 14px 30px rgba(224,48,47,.35);
}

.btn-outline{
    background:transparent;
    color:var(--red);
    border-color:var(--red);
}

.btn-outline:hover{
    background:var(--red-light);
    transform:translateY(-3px);
}

.hero-trust{
    display:flex;
    gap:36px;
    flex-wrap:wrap;
}

.hero-trust div{
    display:flex;
    flex-direction:column;
}

.hero-trust strong{
    font-size:24px;
    color:var(--ink);
}

.hero-trust span{
    font-size:13px;
    color:var(--muted);
}

.hero-visual{
    flex:1;
    min-width:320px;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    min-height:380px;
    z-index:1;
}

.hero-glow{
    position:absolute;
    width:340px;
    height:340px;
    background:radial-gradient(circle, rgba(224,48,47,.18) 0%, transparent 70%);
    border-radius:50%;
    filter:blur(10px);
}

.hero-drop{
    width:220px;
    height:auto;
    position:relative;
    animation:floatDrop 4s ease-in-out infinite;
    filter:drop-shadow(0 20px 30px rgba(224,48,47,.25));
}

.floating-card{
    position:absolute;
    background:var(--surface);
    padding:12px 18px;
    border-radius:12px;
    box-shadow:var(--shadow);
    font-size:14px;
    font-weight:600;
    color:var(--ink);
    animation:floatCard 5s ease-in-out infinite;
}

.floating-card.card-1{
    top:12%;
    left:0;
}

.floating-card.card-2{
    bottom:10%;
    right:0;
    animation-delay:1.5s;
}

@keyframes floatDrop{
    0%,100%{ transform:translateY(0); }
    50%{ transform:translateY(-14px); }
}

@keyframes floatCard{
    0%,100%{ transform:translateY(0); }
    50%{ transform:translateY(-10px); }
}

@keyframes fadeInUp{
    from{ opacity:0; transform:translateY(24px); }
    to{ opacity:1; transform:translateY(0); }
}

/* About */
section h2{
    margin-bottom:20px;
    color:#d62828;
}

/* Quick Access */
section:nth-of-type(3) div{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

section:nth-of-type(3) a{
    text-decoration:none;
    color:#333;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    transition:.3s;
}

section:nth-of-type(3) a:hover{
    transform:translateY(-5px);
}

section:nth-of-type(3) h3{
    color:#d62828;
    margin-bottom:10px;
}

/* Statistics */
section:nth-of-type(4){
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:35px;
    text-align:center;
}

section:nth-of-type(4) h2{
    grid-column:1/-1;
    margin-bottom:35px;
}

section:nth-of-type(4) div{
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

section:nth-of-type(4) h3{
    font-size:35px;
    color:#d62828;
    margin-bottom:10px;
}

/* How It Works */
ol{
    margin-left:20px;
}

ol li{
    margin:15px 0;
    font-size:17px;
}

/* Footer */
footer{
    background:#d62828;
    color:white;
    text-align:center;
    padding:30px;
}

footer h3{
    margin-bottom:10px;
}

footer p{
    margin:5px 0;
}
header h2{ color: var(--red); }

nav a:hover{ color: var(--red); }

header button{ background: var(--red); }

section h2{ color: var(--red); }

section:nth-of-type(3) h3{ color: var(--red); }

section:nth-of-type(4) h3{ color: var(--red); }

footer{ background: var(--red); }
header{ background: var(--surface); }

section:nth-of-type(3) a{ background: var(--surface); }

section:nth-of-type(4) div{ background: var(--surface); }

/* Responsive */
@media(max-width:900px){

    header{
        flex-direction:column;
        gap:15px;
    }

    .hero{
        flex-direction:column;
        text-align:center;
        padding-top:60px;
    }

    .hero-content h1{
        font-size:40px;
    }

    .hero-content p{
        margin-left:auto;
        margin-right:auto;
    }

    .hero-actions,
    .hero-trust{
        justify-content:center;
    }

    .hero-visual{
        min-height:280px;
    }

    section:nth-of-type(4){
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:500px){
    section:nth-of-type(4){
        grid-template-columns:1fr;
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
            <a href="home.php" class="active">Home</a>
            <a href="donors.php">Donors</a>
            <a href="blood-requests.php">Blood Requests</a>
            <a href="emergency-requests.php">Emergency</a>
            <a href="Hospitals.php">Hospitals</a>
        </nav>

        <div>
            <?php if ($me): ?>
            <button type="button" id="dashboardBtn">Dashboard</button>
            <button type="button" id="logoutBtn">Logout</button>
            <?php else: ?>
            <button type="button" id="loginBtn">Login</button>
            <button type="button" id="registerBtn">Register</button>
            <?php endif; ?>
        </div>
    </header>

    
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">🩸 Trusted Blood Donation Network</span>

            <h1>Donate Blood, <span class="highlight">Save Lives</span></h1>

            <p>
                BloodConnect helps donors, patients, hospitals and blood banks
                connect quickly during emergencies — matching the right donor
                to the right request in minutes.
            </p>

            <div class="hero-actions">
                <button type="button" id="donorCta" class="btn-primary">Become a Donor</button>
                <button type="button" id="requestCta" class="btn-outline">Request Blood</button>
            </div>

            <div class="hero-trust">
                <div>
                    <strong><?php echo (int)$totalUsers; ?>+</strong>
                    <span>Registered Donors</span>
                </div>
                <div>
                    <strong><?php echo (int)$hospitalCount; ?>+</strong>
                    <span>Partner Hospitals</span>
                </div>
                <div>
                    <strong>24/7</strong>
                    <span>Emergency Support</span>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-glow"></div>

            <svg class="hero-drop" viewBox="0 0 200 240" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 10 C 60 70, 20 120, 20 165 C 20 205, 56 230, 100 230 C 144 230, 180 205, 180 165 C 180 120, 140 70, 100 10 Z" fill="#e0302f"/>
                <path d="M100 10 C 60 70, 20 120, 20 165 C 20 205, 56 230, 100 230" fill="none" stroke="#b91f1f" stroke-width="2" opacity="0.3"/>
                <ellipse cx="75" cy="150" rx="18" ry="26" fill="#ffffff" opacity="0.25"/>
            </svg>

            <div class="floating-card card-1">🏥 <?php echo (int)$hospitalCount; ?>+ Hospitals</div>
            <div class="floating-card card-2">❤️ <?php echo (int)$totalDonations; ?>+ Lives Helped</div>
        </div>
    </section>

    
    <section>
        <h2>About BloodConnect</h2>

        <p>
            BloodConnect is a blood donation management system
            designed to connect blood donors with patients in need.
            The platform provides emergency blood requests,
            smart donor matching, donor reliability scores,
            hospital integration and analytics.
        </p>
    </section>

    
    <section>

        <h2>Quick Access</h2>

        <div>

            <a href="donors.php">
                <h3>Donors</h3>
                <p>Browse available donors</p>
            </a>

            <a href="blood-requests.php">
                <h3>Blood Requests</h3>
                <p>Create and manage requests</p>
            </a>

            <a href="emergency-requests.php">
                <h3>Emergency Requests</h3>
                <p>View urgent blood needs</p>
            </a>

          

            <a href="donors.php">
                <h3>Donor Reliability</h3>
                <p>Check donor score</p>
            </a>

            <a href="Hospitals.php">
                <h3>Hospitals</h3>
                <p>Blood bank information</p>
            </a>

           

        </div>

    </section>

    
    <section>

        <h2>Platform Overview</h2>

        <div>
            <h3><?php echo (int)$totalUsers; ?>+</h3>
            <p>Registered Donors</p>
        </div>

        <div>
            <h3><?php echo (int)$totalDonations; ?>+</h3>
            <p>Successful Donations</p>
        </div>

        <div>
            <h3><?php echo (int)$totalRequests; ?>+</h3>
            <p>Blood Requests</p>
        </div>

        <div>
            <h3><?php echo (int)$hospitalCount; ?>+</h3>
            <p>Partner Hospitals</p>
        </div>

    </section>

    
    <section>

        <h2>How It Works</h2>

        <ol>
            <li>Register as a donor or patient.</li>
            <li>Create or search blood requests.</li>
            <li>Smart Matching finds suitable donors.</li>
            <li>Contact donor and complete donation.</li>
        </ol>

    </section>

   
    <footer>

        <h3>BloodConnect</h3>

        <p>
            Every donation can save up to three lives.
        </p>

        <p>
            © 2026 BloodConnect. All Rights Reserved.
        </p>

    </footer>

    <script type="application/json" id="homeStats">
<?php echo json_encode([
    "users"         => (int)$totalUsers,
    "donations"     => (int)$totalDonations,
    "requests"      => (int)$totalRequests,
    "hospitalCount" => (int)$hospitalCount
]); ?>
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

    const loginBtn = document.getElementById("loginBtn");
    const registerBtn = document.getElementById("registerBtn");
    const dashboardBtn = document.getElementById("dashboardBtn");
    const logoutBtn = document.getElementById("logoutBtn");
    const donorBtn = document.getElementById("donorCta");
    const requestBtn = document.getElementById("requestCta");

    if (loginBtn) loginBtn.addEventListener("click", function () {
        window.location.href = "Login.php";
    });

    if (registerBtn) registerBtn.addEventListener("click", function () {
        window.location.href = "Register.php";
    });

    if (dashboardBtn) dashboardBtn.addEventListener("click", function () {
        window.location.href = "home.php";
    });

    if (logoutBtn) logoutBtn.addEventListener("click", function () {
        fetch("api/logout.php", { credentials: "same-origin" }).then(function () {
            window.location.href = "index.php";
        });
    });

    if (donorBtn) donorBtn.addEventListener("click", function () {
        window.location.href = "donors.php";
    });

    if (requestBtn) requestBtn.addEventListener("click", function () {
        window.location.href = "blood-requests.php";
    });

    const navLinks = document.querySelectorAll("nav a");

    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            console.log("Opening: " + link.textContent);
        });
    });

    // Animates from 0 up to the real, server-computed count for each
    // stat card. The card already shows the correct final number
    // (rendered by PHP) before JS even runs, so there is no flash of
    // fake placeholder data — this animation is purely a visual effect
    // layered on top of real numbers, not the source of truth for them.
    function animateCounter(el, target) {

        let count = 0;
        const increment = Math.max(1, Math.ceil(target / 100));

        function updateCounter() {
            count += increment;
            if (count < target) {
                el.textContent = count + "+";
                requestAnimationFrame(updateCounter);
            } else {
                el.textContent = target + "+";
            }
        }

        if (target === 0) {
            el.textContent = "0+";
        } else {
            updateCounter();
        }
    }

    function loadStats() {

        const statsEl = document.getElementById("homeStats");
        if (!statsEl) return;

        const stats = JSON.parse(statsEl.textContent);
        const counters = document.querySelectorAll("section:nth-of-type(4) h3");

        // Order matches the markup: Donors, Donations, Requests, Hospitals
        const values = [stats.users, stats.donations, stats.requests, stats.hospitalCount];

        counters.forEach(function (counter, index) {
            if (values[index] !== undefined) {
                animateCounter(counter, values[index]);
            }
        });
    }

    loadStats();

    const cards = document.querySelectorAll("section:nth-of-type(3) a");

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {
            card.style.backgroundColor = "#ffe5e5";
        });

        card.addEventListener("mouseleave", function () {
            card.style.backgroundColor = "white";
        });

    });

    window.addEventListener("focus", function () {
        loadStats();
    });

});
</script>

</body>
</html>
