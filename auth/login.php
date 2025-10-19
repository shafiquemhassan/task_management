<?php
include '../config/db.php';
session_start();

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Task Management System</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">

  <style>
    body {
      height: 100vh;
      background: linear-gradient(135deg, #0d6efd 40%, #f8f9fa 40%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-wrapper {
      display: flex;
      flex-wrap: wrap;
      max-width: 900px;
      width: 90%;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .info-section {
      flex: 1;
      background: #0d6efd;
      color: #fff;
      padding: 3rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .info-section h1 {
      font-weight: 700;
      font-size: 2rem;
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

    .login-card {
      width: 100%;
      max-width: 380px;
      margin: 0 auto;
    }

    .login-card h3 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #0d6efd;
      font-weight: 600;
    }

    .form-label {
      font-weight: 600;
    }

    .form-control {
      border-radius: 10px;
      padding: 0.75rem;
    }

    .form-control:focus {
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
  <div class="login-wrapper">
    <!-- Left Info Section -->
    <div class="info-section">
      <h1>Welcome to Task Management System</h1>
      <p>Plan, organize, and track your work efficiently. Log in to access your dashboard and manage your daily tasks effortlessly.</p>
    </div>

    <!-- Right Login Section -->
    <div class="form-section">
      <div class="login-card">
        <h3>Sign In</h3>

        <?php if (!empty($error_msg)): ?>
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

          <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>

          <div class="link">
            <a href="register.php">Don’t have an account? Register</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
