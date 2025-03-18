<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lowry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin-top: 100px;
            padding: 30px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .input-group-text {
            background-color: #fff;
            border-right: 0;
        }
        .form-control {
            border-left: 0;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1 class="mb-4">LOWRY</h1>
        <form id="loginForm">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email Address:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
            </div>
            <div class="form-check text-start mb-3">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember">Please remember my password</label>
            </div>
            <button type="submit" class="btn btn-dark w-100">Sign In</button>
        </form>
        <a href="#" class="d-block mt-3" data-bs-toggle="modal" data-bs-target="#registerModal">New customer? Join now</a>
    </div>

    <!-- Registration Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" id="firstName" class="form-control" placeholder="Enter your first name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" id="lastName" class="form-control" placeholder="Enter your last name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" id="registerEmail" class="form-control" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Home Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="tel" id="phone" class="form-control" placeholder="Enter your phone number">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" id="birthday" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Login functionality not implemented yet!");
        });
        
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Registration functionality not implemented yet!");
        });
    </script>
</body>
</html>
