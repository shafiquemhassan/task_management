<?php
include '../includes/functions.php';
checkLogin('employee');
$tasks = getTasksByUser($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Task Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
        }
        .navbar {
            background-color: #0d6efd;
        }
        .navbar .navbar-brand,
        .navbar .nav-link,
        .navbar .navbar-text {
            color: #fff !important;
        }
        .table th {
            background-color: #e9ecef;
        }
        .completed {
            color: green;
            font-weight: bold;
        }
        .pending {
            color: #dc3545;
            font-weight: 500;
        }
        .card {
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .btn-complete {
            background-color: #198754;
            color: #fff;
        }
        .btn-complete:hover {
            background-color: #157347;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Task Management System</a>
    <div class="d-flex">
      <span class="navbar-text me-3">👋 Welcome, <?= htmlspecialchars($_SESSION['username']); ?></span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard Content -->
<div class="container my-5">
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0">My Tasks</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 1;
                    while ($row = $tasks->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++; ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td class="<?= $row['status'] == 'completed' ? 'completed' : 'pending' ?>">
                            <?= ucfirst($row['status']) ?>
                        </td>
                        <td><?= $row['due_date'] ?></td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <button class="btn btn-sm btn-complete" onclick="markComplete(<?= $row['id'] ?>)">Mark Complete</button>
                            <?php else: ?>
                                <span class="text-success">✅ Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery (must load before any JS that uses $) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS (bundle) -->
<script src="../assets/js/bootstrap.js"></script>

<!-- Custom JS -->
<script>
function markComplete(taskId) {
    if (confirm('Mark this task as complete?')) {
        $.post('update_status.php', { id: taskId }, function(response) {
            console.log(response); // Debugging
            if (response.trim() === 'success') {
                alert('✅ Task marked as completed!');
                location.reload();
            } else {
                alert('❌ Failed to update task. Please try again.');
            }
        }).fail(function(xhr) {
            alert('⚠️ AJAX Error: ' + xhr.status + ' ' + xhr.statusText);
        });
    }
}
</script>

</script>

</body>
</html>
