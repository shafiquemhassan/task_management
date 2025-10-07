<?php
include '../config/db.php';
session_start();

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_plain = trim($_POST['password']);
    $role = $_POST['role'];

    // Basic validation
    if (empty($username) || empty($password_plain)) {
        $error_msg = "Please fill in all fields.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "Username already exists. Please choose another.";
        } else {
            // Insert new user
            $password = password_hash($password_plain, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                $success_msg = "User registered successfully!";
            } else {
                $error_msg = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Management System</title>

    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">

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

        .register-card {
            max-width: 450px;
            width: 100%;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-center">
    <!-- Left side - Title -->
    <div class="left-side d-flex flex-column justify-content-center align-items-center p-5 flex-fill text-center text-md-start">
        <h1>Task Management System</h1>
        <p class="mt-2">Create a new account and start tracking your tasks.</p>
    </div>

    <!-- Right side - Register Form -->
    <div class="d-flex justify-content-center align-items-center flex-fill p-5">
        <div class="register-card bg-white p-4 rounded shadow-sm">
            <h3 class="text-center mb-4">Register New User</h3>

            <!-- Display Messages -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success text-center py-2"><?php echo $success_msg; ?></div>
            <?php elseif (!empty($error_msg)): ?>
                <div class="alert alert-danger text-center py-2"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                           placeholder="Enter username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter password" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="employee" <?php echo (isset($_POST['role']) && $_POST['role'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
                        <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">Register</button>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-primary">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="../assets/js/bootstrap.js"></script>
</body>
</html>
