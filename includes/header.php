<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PHP To-Do Tracker</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
  <h1>PHP To-Do List / Task Tracker</h1>
  <nav>
    <a href="../index.php">Home</a>
    <?php if (isset($_SESSION['role'])): ?>
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="../admin/dashboard.php">Admin Dashboard</a>
      <?php else: ?>
        <a href="../employee/dashboard.php">My Tasks</a>
      <?php endif; ?>
      <a href="../auth/logout.php">Logout</a>
    <?php else: ?>
      <a href="../auth/login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>
<main>
