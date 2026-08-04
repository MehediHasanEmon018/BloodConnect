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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
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

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 270px;
            background: #ffffff;
            border-right: 1px solid #e5e5e5;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 25px;
            overflow-y: auto;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h2 {
            color: #d62828;
            margin-bottom: 8px;
        }

        .logo p {
            color: #777;
            font-size: 14px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 8px;
        }

        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: #444;
            padding: 14px;
            border-radius: 8px;
            transition: .3s;
        }

        .sidebar ul li a:hover {
            background: #ffe5e5;
            color: #d62828;
        }

        .sidebar ul li.active a {
            background: #d62828;
            color: #fff;
        }

        #logoutBtn {
            margin-top: 25px;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #d62828;
            color: #fff;
            cursor: pointer;
            font-size: 15px;
            transition: .3s;
        }

        #logoutBtn:hover {
            background: #b91d1d;
        }

        .main-content {
            margin-left: 270px;
            width: calc(100% - 270px);
            padding: 30px;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        #menuBtn {
            display: none;
            border: none;
            background: none;
            font-size: 24px;
            cursor: pointer;
        }

        .header-left h1 {
            font-size: 30px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 25px;
        }

        .chat-container {
            display: flex;
            gap: 25px;
            height: 75vh;
        }

        #left-panel,
        #right-panel {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        }

        #left-panel {
            width: 320px;
            padding: 25px;
            display: flex;
            flex-direction: column;
        }

        #left-panel h3 {
            color: #d62828;
            margin-bottom: 15px;
        }

        #left-panel input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            margin-bottom: 15px;
            transition: .3s;
        }

        #left-panel input:focus {
            border-color: #d62828;
        }

        .new-chat-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .new-chat-row input {
            margin-bottom: 0;
        }

        .new-chat-row button {
            padding: 0 18px;
            border: none;
            border-radius: 8px;
            background: #d62828;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
            white-space: nowrap;
        }

        .new-chat-row button:hover {
            background: #b91d1d;
        }

        #left-panel h4 {
            color: #d62828;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
        }

        .chat-item {
            padding: 14px;
            background: #fafafa;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #eee;
        }

        .chat-item:hover {
            background: #ffe5e5;
        }

        .chat-item.active {
            background: #d62828;
            color: #fff;
            border-color: #d62828;
        }

        .chat-item .preview {
            display: block;
            font-size: 12.5px;
            opacity: .75;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        .empty-list {
            color: #999;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        #right-panel {
            flex: 1;
            padding: 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chat-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            color: #d62828;
            font-weight: bold;
            font-size: 20px;
        }

        .messages {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .message {
            max-width: 70%;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .received {
            background: #fafafa;
            color: #333;
            border: 1px solid #eee;
        }

        .sent {
            background: #d62828;
            color: #fff;
            margin-left: auto;
        }

        .message-box {
            display: flex;
            gap: 12px;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .message-box input[type="text"] {
            flex: 1;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: .3s;
        }

        .message-box input[type="text"]:focus {
            border-color: #d62828;
        }

        .message-box input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 160px;
        }

        .message-box button {
            padding: 14px 26px;
            border: none;
            border-radius: 8px;
            background: #d62828;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: .3s;
        }

        .message-box button:hover {
            background: #b91d1d;
        }

        .message img {
            max-width: 220px;
            max-height: 220px;
            border-radius: 10px;
            object-fit: cover;
            display: block;
        }

        .empty-chat {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 16px;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d62828;
            border-radius: 20px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        @media(max-width:900px) {

            .sidebar {
                left: -270px;
                transition: .3s;
                z-index: 999;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }

            #menuBtn {
                display: block;
            }

            header {
                flex-direction: column;
                gap: 20px;
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
            }

            .message-box {
                flex-direction: column;
                align-items: stretch;
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
                <p>Save Lives Together</p>
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

                    <div id="chatArea" style="display:flex; flex-direction:column; flex:1;">

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