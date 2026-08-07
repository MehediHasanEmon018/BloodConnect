<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$user_id = $_SESSION['user_id'];
$me = getCurrentUser($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BloodConnect | Messages</title>

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

        .subtitle {
            color: var(--ink-soft);
            margin-bottom: 26px;
            font-size: 14.5px;
        }

        /* ============================
           Chat layout
           ============================ */
        .chat-container {
            display: flex;
            gap: 22px;
            height: 75vh;
        }

        #left-panel,
        #right-panel {
            background: var(--panel);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-panel);
            border: 1px solid var(--mist);
        }

        #left-panel {
            width: 320px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        #left-panel h3 {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 19px;
            color: var(--ink);
            margin-bottom: 16px;
        }

        #left-panel input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--mist);
            background: var(--paper);
            border-radius: var(--radius-sm);
            outline: none;
            font-size: 14.5px;
            color: var(--ink);
            margin-bottom: 14px;
            transition: border-color .2s ease, background .2s ease;
        }

        #left-panel input::placeholder {
            color: #a9a5a0;
        }

        #left-panel input:focus {
            border-color: var(--crimson);
            background: #fff;
        }

        .new-chat-row {
            display: flex;
            gap: 10px;
            margin-bottom: 22px;
        }

        .new-chat-row input {
            margin-bottom: 0;
        }

        .new-chat-row button {
            padding: 0 18px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--ink);
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background .2s ease;
            white-space: nowrap;
        }

        .new-chat-row button:hover {
            background: var(--crimson);
        }

        #left-panel h4 {
            color: var(--ink-soft);
            font-weight: 600;
            margin-bottom: 13px;
            font-size: 12.5px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow-y: auto;
            min-height: 0;
        }

        .chat-item {
            padding: 13px 14px;
            background: var(--paper);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease, color .18s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid transparent;
            font-size: 14.5px;
            font-weight: 500;
        }

        .chat-item:hover {
            background: var(--crimson-soft);
            border-color: #f6d9d6;
        }

        .chat-item.active {
            background: var(--crimson);
            color: #fff;
            border-color: var(--crimson);
            box-shadow: 0 8px 16px -8px rgba(200, 16, 46, .5);
        }

        .chat-item .preview {
            display: block;
            font-size: 12px;
            opacity: .7;
            margin-top: 3px;
            font-weight: 400;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .empty-list {
            color: #a9a5a0;
            text-align: center;
            padding: 22px;
            font-size: 13.5px;
        }

        /* ============================
           Conversation panel
           ============================ */
        #right-panel {
            flex: 1;
            padding: 24px 26px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }

        .chat-header {
            border-bottom: 1px solid var(--mist);
            padding-bottom: 16px;
            color: var(--ink);
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 20px;
        }

        .messages {
            flex: 1;
            padding: 20px 4px;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .message {
            max-width: 68%;
            width: fit-content;
            padding: 13px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 12px;
            line-height: 1.55;
            font-size: 14.5px;

            /* keep every message strictly inside the bubble / chat column,
               regardless of long words, links, or filenames */
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: pre-wrap;
        }

        .received {
            background: var(--paper);
            color: var(--ink);
            border: 1px solid var(--mist);
            border-bottom-left-radius: 4px;
            align-self: flex-start;
        }

        .sent {
            background: var(--crimson);
            color: #fff;
            border-bottom-right-radius: 4px;
            align-self: flex-end;
            margin-left: auto;
        }

        .message-box {
            display: flex;
            gap: 12px;
            align-items: center;
            border-top: 1px solid var(--mist);
            padding-top: 20px;
        }

        .message-box input[type="text"] {
            flex: 1;
            min-width: 0;
            padding: 13px 15px;
            border: 1px solid var(--mist);
            background: var(--paper);
            border-radius: var(--radius-sm);
            outline: none;
            font-size: 14.5px;
            color: var(--ink);
            transition: border-color .2s ease, background .2s ease;
        }

        .message-box input[type="text"]::placeholder {
            color: #a9a5a0;
        }

        .message-box input[type="text"]:focus {
            border-color: var(--crimson);
            background: #fff;
        }

        .message-box input[type="file"] {
            padding: 9px;
            border: 1px solid var(--mist);
            border-radius: var(--radius-sm);
            background: var(--paper);
            max-width: 160px;
            font-size: 13px;
            color: var(--ink-soft);
        }

        .message-box button {
            padding: 13px 26px;
            border: none;
            border-radius: var(--radius-sm);
            background: var(--crimson);
            color: #fff;
            font-weight: 600;
            font-size: 14.5px;
            cursor: pointer;
            transition: background .2s ease, transform .15s ease;
            white-space: nowrap;
        }

        .message-box button:hover {
            background: var(--crimson-dark);
        }

        .message-box button:active {
            transform: scale(.97);
        }

        .message img {
            display: block;
            max-width: 220px;
            max-height: 220px;
            width: 100%;
            border-radius: var(--radius-sm);
            object-fit: cover;
        }

        .empty-chat {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a9a5a0;
            font-size: 15px;
        }

        /* ============================
           Scrollbars
           ============================ */
        ::-webkit-scrollbar {
            width: 7px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d9b9bd;
            border-radius: 20px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--crimson);
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ============================
           Responsive
           ============================ */
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

            .chat-container {
                flex-direction: column;
                height: auto;
            }

            #left-panel {
                width: 100%;
                max-height: 320px;
            }

            #right-panel {
                height: 65vh;
            }

            .message {
                max-width: 82%;
            }

            .message-box {
                flex-direction: column;
                align-items: stretch;
            }

            .message-box input[type="file"] {
                max-width: 100%;
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

                    <li class="active">
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

                    <h1>Messages</h1>

                </div>

            </header>

            <p class="subtitle">
                Chat with donors, patients, and hospitals across BloodConnect.
            </p>

            <div class="chat-container">

                <div id="left-panel">

                    <h3>Chats</h3>

                    <input type="text" id="searchInput" placeholder="Search Conversation">

                    <div class="new-chat-row">
                        <input type="text" id="newChatInput" placeholder="Start new chat with...">
                        <button id="newChatBtn">Add</button>
                    </div>

                    <h4>Active Conversations</h4>

                    <div class="chat-list" id="chatList"></div>

                </div>

                <div id="right-panel">

                    <div id="chatArea" style="display:flex; flex-direction:column; flex:1; min-height:0;">

                        <div class="chat-header" id="chatHeader">Select a conversation</div>

                        <div class="messages" id="messages"></div>

                    </div>

                    <div class="message-box">
                        <input type="text" id="messageInput" placeholder="Write a message...">
                        <input type="file" id="fileInput" accept="image/*">
                        <button id="sendButton">Send</button>
                    </div>

                </div>

            </div>

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

            const myId = <?php echo (int)$user_id; ?>;

            function escapeHtml(str) {
                const div = document.createElement("div");
                div.textContent = str == null ? "" : String(str);
                return div.innerHTML;
            }

            const chatListEl = document.getElementById("chatList");
            const chatHeader = document.getElementById("chatHeader");
            const messagesEl = document.getElementById("messages");
            const messageInput = document.getElementById("messageInput");
            const fileInput = document.getElementById("fileInput");
            const sendButton = document.getElementById("sendButton");
            const searchInput = document.getElementById("searchInput");
            const newChatInput = document.getElementById("newChatInput");
            const newChatBtn = document.getElementById("newChatBtn");

            let contacts = [];
            let currentChat = null; // { id, name, photo }
            let pollTimer = null;

            function loadContacts() {
                return fetch("api/messages_list.php?mode=contacts", { credentials: "same-origin" })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        contacts = data.success ? data.contacts : [];
                        return contacts;
                    });
            }

            function renderChatList(filter) {

                filter = (filter || "").toLowerCase();

                chatListEl.innerHTML = "";

                const visible = contacts.filter(c => (c.name || "").toLowerCase().includes(filter));

                if (visible.length === 0) {
                    chatListEl.innerHTML = `<div class="empty-list">No conversations found.</div>`;
                    return;
                }

                visible.forEach(function (c) {

                    const item = document.createElement("div");
                    item.className = "chat-item" + (currentChat && c.id == currentChat.id ? " active" : "");

                    item.innerHTML = `
                        <div>
                            <div>${escapeHtml(c.name)}</div>
                            <span class="preview">${c.unread > 0 ? "New message" : "Tap to open"}</span>
                        </div>
                    `;

                    item.addEventListener("click", function () {
                        loadChat(c.id, c.name, c.photo);
                    });

                    chatListEl.appendChild(item);

                });

            }

            function loadChat(id, name, photo) {

                currentChat = { id: id, name: name, photo: photo };

                chatHeader.textContent = name || "Chat";

                fetch("api/messages_list.php?userId=" + encodeURIComponent(id), { credentials: "same-origin" })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {

                        const msgs = data.success ? data.messages : [];
                        messagesEl.innerHTML = "";

                        msgs.forEach(function (msg) {

                            const div = document.createElement("div");
                            div.className = "message " + (msg.senderId == myId ? "sent" : "received");

                            if (msg.image) {
                                const img = document.createElement("img");
                                img.src = msg.image;
                                div.appendChild(img);
                            } else {
                                div.textContent = msg.message;
                            }

                            messagesEl.appendChild(div);

                        });

                        messagesEl.scrollTop = messagesEl.scrollHeight;

                    });

                renderChatList(searchInput.value);

            }

            function sendMessage() {

                if (!currentChat) {
                    alert("Please select or start a conversation first.");
                    return;
                }

                const text = messageInput.value.trim();
                const file = fileInput.files[0];

                if (text === "" && !file) {
                    alert("Please write a message or select an image.");
                    return;
                }

                function post(imageData) {
                    const formData = new FormData();
                    formData.append("receiverId", currentChat.id);
                    formData.append("message", text);
                    if (imageData) formData.append("image", imageData);

                    fetch("api/messages_send.php", { method: "POST", body: formData, credentials: "same-origin" })
                        .then(function (res) { return res.json(); })
                        .then(function () {
                            messageInput.value = "";
                            fileInput.value = "";
                            loadChat(currentChat.id, currentChat.name, currentChat.photo);
                            loadContacts().then(function () { renderChatList(searchInput.value); });
                        });
                }

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) { post(e.target.result); };
                    reader.readAsDataURL(file);
                } else {
                    post(null);
                }

            }

            sendButton.addEventListener("click", sendMessage);

            messageInput.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    sendMessage();
                }
            });

            searchInput.addEventListener("keyup", function () {
                renderChatList(this.value);
            });

            newChatBtn.addEventListener("click", function () {

                const query = newChatInput.value.trim();

                if (query === "") {
                    alert("Enter a name or email to start a conversation.");
                    return;
                }

                fetch("api/users_lookup.php?q=" + encodeURIComponent(query), { credentials: "same-origin" })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {

                        const match = data.success && data.users.length ? data.users[0] : null;

                        if (!match) {
                            alert("No BloodConnect user found with that name or email.");
                            return;
                        }

                        newChatInput.value = "";
                        loadChat(match.id, match.name, match.photo);

                    });

            });

            newChatInput.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    newChatBtn.click();
                }
            });

            function init() {

                loadContacts().then(function () {

                    renderChatList();

                    const params = new URLSearchParams(window.location.search);
                    const withId = params.get("with");

                    if (withId) {

                        const known = contacts.find(c => c.id == withId);

                        if (known) {
                            loadChat(known.id, known.name, known.photo);
                        } else {
                            // Not in the contact list yet (first message to this person) - look up their name.
                            fetch("api/users_lookup.php?id=" + encodeURIComponent(withId), { credentials: "same-origin" })
                                .then(function (res) { return res.json(); })
                                .then(function (data) {
                                    const u = data.success && data.users.length ? data.users[0] : null;
                                    loadChat(withId, u ? u.name : "New Contact", u ? u.photo : "images/user.png");
                                });
                        }

                    } else if (contacts.length > 0) {
                        loadChat(contacts[0].id, contacts[0].name, contacts[0].photo);
                    } else {
                        chatHeader.textContent = "No conversations yet";
                        messagesEl.innerHTML = `
                            <div style="text-align:center; color:#999; padding:60px 20px;">
                                <p>No conversations yet.</p>
                                <p style="font-size:13.5px; margin-top:8px;">Contact a donor from the Donors page, or start a new chat using the box on the left.</p>
                            </div>
                        `;
                    }

                });

            }

            init();

            // Light polling so new messages show up without a manual refresh.
            pollTimer = setInterval(function () {
                if (currentChat) loadChat(currentChat.id, currentChat.name, currentChat.photo);
                loadContacts().then(function () { renderChatList(searchInput.value); });
            }, 6000);

        });
    </script>

</body>

</html>