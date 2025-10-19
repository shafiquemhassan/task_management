<?php
include '../config/db.php';
session_start();

$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password_plain = trim($_POST['password']);
    $role = $_POST['role'];

    if (empty($username) || empty($password_plain)) {
        $error_msg = "Please fill in all fields.";
    } else {
        $check_sql = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "Username already exists. Please choose another.";
        } else {
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Task Management System</title>

  <link href="../assets/css/bootstrap.css" rel="stylesheet">

  <style>
    body {
      height: 100vh;
      background: linear-gradient(135deg, #0d6efd 40%, #f8f9fa 40%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .register-wrapper {
      display: flex;
      flex-wrap: wrap;
      max-width: 950px;
      width: 90%;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .info-section {
      flex: 1;
      background-color: #0d6efd;
      color: #fff;
      padding: 3rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .info-section h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .info-section p {
      font-size: 1rem;
      opacity: 0.9;
    }

    .form-section {
      flex: 1;
      padding: 3rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .register-card {
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
    }

    .register-card h3 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #0d6efd;
      font-weight: 600;
    }

    .form-label {
      font-weight: 600;
    }

    .form-control, .form-select {
      border-radius: 10px;
      padding: 0.75rem;
    }

    .form-control:focus, .form-select:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
      border-radius: 10px;
      padding: 0.75rem;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
    }

    .alert {
      font-size: 0.9rem;
      border-radius: 10px;
    }

    .link {
      text-align: center;
      margin-top: 1rem;
    }

    .link a {
      color: #0d6efd;
      text-decoration: none;
      font-weight: 600;
    }

    .link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .info-section {
        display: none;
      }
      .form-section {
        flex: 1 1 100%;
      }
    }
  </style>
</head>
<body>
  <div class="register-wrapper">
    <!-- Left Info Section -->
    <div class="info-section">
      <h1>Welcome to Task Management System</h1>
      <p>Create your account to organize, track, and complete tasks efficiently. Collaboration and productivity start here!</p>
    </div>

    <!-- Right Registration Section -->
    <div class="form-section">
      <div class="register-card">
        <h3>Create Account</h3>

        <?php if (!empty($success_msg)): ?>
          <div class="alert alert-success text-center py-2" role="alert">
            <?php echo htmlspecialchars($success_msg); ?>
          </div>
        <?php elseif (!empty($error_msg)): ?>
          <div class="alert alert-danger text-center py-2" role="alert">
            <?php echo htmlspecialchars($error_msg); ?>
          </div>
        <?php endif; ?>

        <form method="POST" novalidate>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
              type="text" 
              class="form-control" 
              id="username" 
              name="username" 
              placeholder="Enter username"
              value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
              required 
              aria-required="true"
            >
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              placeholder="Enter password"
              required 
              aria-required="true"
            >
          </div>

          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required aria-required="true">
              <option value="employee" <?php echo (isset($_POST['role']) && $_POST['role'] == 'employee') ? 'selected' : ''; ?>>Employee</option>
              <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-2">Register</button>

          <div class="link">
            <a href="login.php">Already have an account? Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
