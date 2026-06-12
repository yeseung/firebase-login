<?php









?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Firebase 채팅방</title>

    <style>
        :root {
            --bg: #f5f6f8;
            --panel: #ffffff;
            --line: #e5e7eb;
            --text: #222222;
            --muted: #8a8f98;
            --sent: #06254b;
            --received: #ffffff;
            --primary: #06254b;
            --shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Apple SD Gothic Neo", "Malgun Gothic", sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow: hidden;
        }

        .chat-app {
            width: 100%;
            max-width: 720px;
            height: 100vh;
            margin: 0 auto;
            background: var(--panel);
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
        }

        .chat-header {
            height: 50px;
            padding: 0 18px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #ffffff;
            flex-shrink: 0;
        }

        .chat-header h1 {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .player-cnt {
            min-width: 24px;
            height: 24px;
            padding: 0 8px;
            border-radius: 9999px;
            background: var(--primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
        }

        .character-container {
            height: 90px;
            padding: 10px;
            border-bottom: 1px solid var(--line);
            background: #fff;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            gap: 10px;
            align-items: center;
            overflow-x: auto;
            overflow-y: hidden;
            -ms-overflow-style: none;
        }

        .character-container::-webkit-scrollbar {
            display: none;
        }

        .character-item {
            width: 60px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .character-image img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }

        .character-nick {
            width: 60px;
            padding-top: 5px;
            font-size: 12px;
            font-weight: 500;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .chat-container {
            flex: 1;
            overflow-y: auto;
            padding: 18px 16px;
            background: #f8fafc;
        }

        .message {
            display: flex;
            margin-bottom: 12px;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .bubble-container {
            max-width: 78%;
        }

        .name {
            margin-bottom: 4px;
            font-size: 13px;
            font-weight: 700;
            color: var(--muted);
        }

        .message.sent .name,
        .message.sent .timestamp {
            text-align: right;
        }

        .bubble {
            padding: 10px 13px;
            border-radius: 16px;
            font-size: 15px;
            line-height: 1.45;
            word-break: break-word;
            white-space: pre-wrap;
        }

        .received .bubble {
            background: var(--received);
            border: 1px solid var(--line);
            border-top-left-radius: 4px;
        }

        .sent .bubble {
            background: var(--sent);
            color: #ffffff;
            border-top-right-radius: 4px;
        }

        .bubble a {
            word-break: break-all;
            text-decoration: underline;
        }

        .received .bubble a {
            color: #2563eb;
        }

        .sent .bubble a {
            color: #c7ddff;
        }

        .timestamp {
            margin-top: 4px;
            font-size: 11px;
            color: var(--muted);
        }

        .chat-input {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid var(--line);
            background: #ffffff;
            flex-shrink: 0;
        }

        .username-input {
            width: 140px;
            flex-shrink: 0;
        }

        .message-input {
            flex: 1;
        }

        .chat-input input {
            width: 100%;
            height: 44px;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0 12px;
            font-size: 15px;
            outline: none;
            background: #ffffff;
        }

        .chat-input input:focus {
            border-color: var(--primary);
        }

        .send-button {
            width: 64px;
            height: 44px;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            flex-shrink: 0;
        }

        .send-button:hover {
            opacity: 0.9;
        }

        @media (max-width: 480px) {
            .chat-app {
                max-width: none;
            }

            .chat-header {
                height: 52px;
            }

            .chat-header h1 {
                font-size: 16px;
            }

            .chat-container {
                padding: 14px 10px;
            }

            .bubble-container {
                max-width: 84%;
            }

            .chat-input {
                gap: 6px;
                padding: 10px;
            }

            .username-input {
                width: 82px;
            }

            .send-button {
                width: 54px;
            }
        }
    </style>
</head>

<body>
<div class="chat-app">
    <header class="chat-header">
        <h1>Firebase 채팅방</h1>
        <span class="player-cnt">0</span>
    </header>

    <nav id="characterBox" class="character-container"></nav>

    <main id="chatBox" class="chat-container"></main>

    <form id="chatForm" class="chat-input" autocomplete="off">
        <div class="username-input">
            <input type="text" id="username" value="" readonly />
        </div>

        <div class="message-input">
            <input type="text" id="message" maxlength="300" placeholder="메시지를 입력하세요. (최대 300자)" />
        </div>

        <button type="submit" class="send-button">전송</button>
    </form>
</div>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/12.14.0/firebase-app.js";
    import {
        getAuth,
        signInAnonymously,
        onAuthStateChanged
    } from "https://www.gstatic.com/firebasejs/12.14.0/firebase-auth.js";

    import {
        getDatabase,
        ref,
        push,
        query,
        limitToLast,
        set,
        onValue,
        onChildAdded,
        onChildRemoved,
        onDisconnect
    } from "https://www.gstatic.com/firebasejs/12.14.0/firebase-database.js";

    const loginUser = <?php echo $js_user; ?>;
    const loginNick = <?php echo $js_nick; ?>;
    const loginProfileImage = <?php echo $js_profile_image; ?>;

    const firebaseConfig = {
        apiKey: "AIzaSyA6AfyEYWK0kTUDPGBo5x4wCBmdy3OkIi8",
        authDomain: "kimtaja-1b64f.firebaseapp.com",
        databaseURL: "https://kimtaja-1b64f-default-rtdb.firebaseio.com",
        projectId: "kimtaja-1b64f",
        storageBucket: "kimtaja-1b64f.firebasestorage.app",
        messagingSenderId: "744544564676",
        appId: "1:744544564676:web:a4892201761da01962a091"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getDatabase(app);

    const characterBox = document.getElementById("characterBox");
    const chatBox = document.getElementById("chatBox");
    const chatForm = document.getElementById("chatForm");
    const usernameInput = document.getElementById("username");
    const messageInput = document.getElementById("message");
    const playerCnt = document.querySelector(".player-cnt");

    const allPlayersRef = ref(db, "players");

    let initialized = false;
    let playerRef = null;
    const playerElements = {};

    usernameInput.value = loginNick || "";

    function escapeHtml(text) {
        return String(text)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
    }

    function urlAutoLink(text) {
        let safeText = escapeHtml(text);

        safeText = safeText.replace(
            /(https?:\/\/[^\s<]+)/gi,
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
        );

        safeText = safeText.replace(
            /(^|[\s])www\.([^\s<]+)/gi,
            '$1<a href="https://www.$2" target="_blank" rel="noopener noreferrer">www.$2</a>'
        );

        safeText = safeText.replace(
            /[0-9a-z._%+-]+@[0-9a-z.-]+\.[a-z]{2,}/gi,
            '<a href="mailto:$&">$&</a>'
        );

        return safeText;
    }

    function formatTimeStamp(timeStamp) {
        const date = new Date(timeStamp || Date.now());

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const hour = String(date.getHours()).padStart(2, "0");
        const minute = String(date.getMinutes()).padStart(2, "0");
        const second = String(date.getSeconds()).padStart(2, "0");

        return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
    }

    function createCharacterElement(player) {
        const item = document.createElement("div");
        item.className = "character-item";

        const imageBox = document.createElement("div");
        imageBox.className = "character-image";

        const image = document.createElement("img");
        image.src = player.profileImage || "";
        image.alt = player.nick || "";
        image.title = player.nick || "";

        const nick = document.createElement("div");
        nick.className = "character-nick";
        nick.textContent = player.nick || "";

        imageBox.appendChild(image);
        item.appendChild(imageBox);
        item.appendChild(nick);

        return item;
    }

    function initPlayers() {
        onValue(allPlayersRef, (snapshot) => {
            const players = snapshot.val() || {};
            playerCnt.textContent = Object.keys(players).length;
        });

        onChildAdded(allPlayersRef, (snapshot) => {
            const playerKey = snapshot.key;
            const player = snapshot.val();

            if (!playerKey || !player || playerElements[playerKey]) {
                return;
            }

            const characterElement = createCharacterElement(player);
            playerElements[playerKey] = characterElement;
            characterBox.appendChild(characterElement);
        });

        onChildRemoved(allPlayersRef, (snapshot) => {
            const playerKey = snapshot.key;

            if (!playerElements[playerKey]) {
                return;
            }

            playerElements[playerKey].remove();
            delete playerElements[playerKey];
        });
    }

    function appendMessage(data) {
        const username = data.username || "";
        const message = data.message || "";

        if (!username || !message) {
            return;
        }

        const messageClass = username === usernameInput.value.trim() ? "sent" : "received";

        const item = document.createElement("div");
        item.className = `message ${messageClass}`;

        item.innerHTML = `
            <div class="bubble-container">
                <div class="name">${escapeHtml(username)}</div>
                <div class="bubble">${urlAutoLink(message)}</div>
                <div class="timestamp">${formatTimeStamp(data.timestamp)}</div>
            </div>
        `;

        chatBox.appendChild(item);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function loadMessages() {
        const messageQuery = query(ref(db, "messages"), limitToLast(50));

        onChildAdded(messageQuery, (snapshot) => {
            appendMessage(snapshot.val());
        });
    }

    async function sendMessage() {
        const username = usernameInput.value.trim();
        const message = messageInput.value.trim();

        if (!username) {
            alert("사용자 이름을 확인할 수 없습니다.");
            return;
        }

        if (!message) {
            return;
        }

        if (message.length > 300) {
            alert("메시지는 300자 이하만 입력 가능합니다.");
            messageInput.focus();
            return;
        }

        await push(ref(db, "messages"), {
            username,
            message: message,
            timestamp: Date.now()
        });

        messageInput.value = "";
        messageInput.focus();
    }

    function initChatEvents() {
        chatForm.addEventListener("submit", (event) => {
            event.preventDefault();
            sendMessage();
        });

        messageInput.addEventListener("input", () => {
            if (messageInput.value.length > 300) {
                messageInput.value = messageInput.value.slice(0, 300);
            }
        });
    }

    onAuthStateChanged(auth, (user) => {
        if (!user) {
            console.log("로그아웃");
            return;
        }

        playerRef = ref(db, `players/${user.uid}`);

        set(playerRef, {
            id: user.uid,
            user: loginUser,
            nick: loginNick,
            profileImage: loginProfileImage
        });

        usernameInput.value = loginNick || "";

        onDisconnect(playerRef).remove();

        if (!initialized) {
            initPlayers();
            loadMessages();
            initChatEvents();
            initialized = true;
        }
    });

    signInAnonymously(auth).catch((error) => {
        console.log(error.code, error.message);
    });
</script>
</body>
</html>