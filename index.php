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

    <title>Realtime Chat Sign-Up</title>
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
    <h2 class="text-center mb-4">Realtime Chat Sign-Up</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Sign-Up Form -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" class="form-control" placeholder="Enter your name">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" placeholder="Enter your email">
            </div>

            <button class="btn btn-primary" onclick="signup()">Sign Up</button>


        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS (make sure it's loaded after jQuery and before custom scripts) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function signup() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;

            // Validate form fields
            if (!name || !email) {
                toastr.error("Both Name and Email are required!");
                return;
            }

            // Validate email format
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                toastr.error("Please enter a valid email!");
                return;
            }

            // If validation passes
            toastr.success("Sign-Up Successful! Redirecting to chat...");
            setTimeout(function () {
                window.location.href = "chat.php?name=" + encodeURIComponent(name) + "&email=" + encodeURIComponent(email);
            }, 2000);  // Delay before redirecting
        }
    </script>

</body>

</html>