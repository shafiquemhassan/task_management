<?php
include '../config/db.php';
session_start();

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($username) || empty($password)) {
        $error_msg = "Please enter both username and password.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect by role
                if ($user['role'] == 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../employee/dashboard.php");
                }
                exit;
            } else {
                $error_msg = "Invalid password!";
            }
        } else {
            $error_msg = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management System</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.css">

    <style>
        body {
            height: 100vh;
            background-color: #f8f9fa;
        }

        .container-fluid {
            height: 100%;
        }

        .left-side {
            background-color: #0d6efd;
            color: #fff;
        }

        .left-side h1 {
            font-size: 2rem;
            font-weight: 600;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-center">
    <!-- Left side - Title -->
    <div class="left-side d-flex flex-column justify-content-center align-items-center p-5 flex-fill text-center text-md-start">
        <h1>Task Management System</h1>
        <p class="mt-2">Stay organized and manage your tasks efficiently.</p>
    </div>

    <!-- Right side - Login Form -->
    <div class="d-flex justify-content-center align-items-center flex-fill p-5">
        <div class="login-card bg-white p-4 rounded shadow-sm">
            <h3 class="text-center mb-4">Login</h3>

            <!-- Bootstrap Alert -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger text-center py-2">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           placeholder="Enter username" 
                           required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Enter password" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>

                <div class="text-center mt-3">
                    <a href="register.php" class="text-primary">For Registration</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
