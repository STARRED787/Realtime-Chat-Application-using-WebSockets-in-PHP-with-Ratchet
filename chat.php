<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <title>Realtime Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #chat-box {
            height: 300px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f8f9fa;
        }
    </style>
</head>

<body class="container py-4">
    <h2 class="text-center mb-4">Realtime Chat</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div id="chat-box" class="mb-3 p-3"></div>

            <div class="input-group mb-3">
                <input type="text" id="message" class="form-control" placeholder="Type a message">
                <button class="btn btn-success" onclick="sendMessage()">Send</button>
            </div>

            <h3 class="mt-4">Online Users:</h3>
            <ul id="users" class="list-group"></ul>

            <!-- Button to go back to Sign-In page -->
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary" id="back-to-signup">Back to Sign-In</a>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('back-to-signup').addEventListener('click', function () {
            // When user clicks back, clear session variables
            <?php session_start(); ?>
            <?php
            // Unset session variables (clear user credentials)
            session_unset();
            session_destroy();
            ?>
        });
    </script>

    <script>
        let ws, username;
        const urlParams = new URLSearchParams(window.location.search);
        username = urlParams.get('name');

        if (!username) {
            window.location.href = "index.php";
        }

        // Connect to WebSocket Server
        ws = new WebSocket("ws://127.0.0.1:8080");

        ws.onopen = function () {
            ws.send(JSON.stringify({ type: "join", username: username }));
        };

        ws.onmessage = function (event) {
            let data = JSON.parse(event.data);

            if (data.message) {
                let chatBox = document.getElementById('chat-box');
                chatBox.innerHTML += `<p><strong>${data.from}:</strong> ${data.message}</p>`;
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            let usersList = document.getElementById('users');
            usersList.innerHTML = "";
            data.users.forEach(user => {
                usersList.innerHTML += `<li class='list-group-item'>${user}</li>`;
            });
        };

        function playSound(url) {
            var audio = new Audio(url);
            audio.play().catch(function (error) {
                console.error("Error playing audio:", error);
            });
        }

        function sendMessage() {
            let message = document.getElementById('message').value;
            if (!message) return;

            // Send the message first
            ws.send(JSON.stringify({ type: "message", message: message }));

            // Play the sound after the message has been sent
            playSound('audio/success.mp3');

            // Clear the input field after sending the message
            document.getElementById('message').value = "";
        }

    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>

</html>