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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #212529;
    }

    /* Navbar */
    .navbar {
      background: linear-gradient(90deg, #0d6efd, #0b5ed7);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand {
      font-weight: 600;
      color: #fff !important;
    }
    .navbar .navbar-text {
      color: #e9ecef !important;
    }

    /* Card */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.08);
      background-color: #fff;
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-2px);
    }

    /* Table */
    table {
      border-radius: 10px;
      overflow: hidden;
    }
    .table th {
      background-color: #0d6efd;
      color: white;
      font-weight: 600;
    }
    .table td {
      vertical-align: middle;
    }
    .completed {
      color: #198754;
      font-weight: 600;
    }
    .pending {
      color: #dc3545;
      font-weight: 600;
    }

    /* Buttons */
    .btn-complete {
      background-color: #198754;
      color: white;
      border-radius: 20px;
      padding: 5px 14px;
      transition: 0.3s;
    }
    .btn-complete:hover {
      background-color: #157347;
      transform: scale(1.03);
    }
    .btn-outline-light:hover {
      background-color: #fff;
      color: #0d6efd;
    }

    /* Feedback Toast */
    #toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1055;
      display: none;
      padding: 12px 18px;
      border-radius: 8px;
      background-color: #198754;
      color: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      font-weight: 500;
    }
  </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="#">
      <i class="bi bi-kanban-fill me-2"></i>Task Management
    </a>
    <div class="d-flex align-items-center">
      <span class="navbar-text me-3">👋 Welcome, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard -->
<div class="container my-5">
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold text-primary mb-0">
        <i class="bi bi-list-task me-2"></i>My Tasks
      </h4>
      <span class="text-muted small">Last updated: <?= date("d M Y, h:i A"); ?></span>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead>
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
          if ($tasks->num_rows > 0):
            while ($row = $tasks->fetch_assoc()): ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td class="<?= $row['status'] == 'completed' ? 'completed' : 'pending' ?>">
                  <?= ucfirst($row['status']) ?>
                </td>
                <td><?= date('d M Y', strtotime($row['due_date'])) ?></td>
                <td>
                  <?php if ($row['status'] == 'pending'): ?>
                    <button class="btn btn-sm btn-complete" onclick="markComplete(<?= $row['id'] ?>)">
                      <i class="bi bi-check-circle"></i> Mark Complete
                    </button>
                  <?php else: ?>
                    <span class="text-success"><i class="bi bi-check2-circle"></i> Completed</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile;
          else: ?>
            <tr>
              <td colspan="6" class="text-muted py-4">
                <i class="bi bi-inbox fs-3 d-block mb-2"></i>No tasks assigned yet.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Feedback Toast -->
<div id="toast">✅ Task marked as completed!</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.js"></script>

<script>
function showToast(message, success = true) {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.style.backgroundColor = success ? '#198754' : '#dc3545';
  toast.style.display = 'block';
  setTimeout(() => toast.style.display = 'none', 3000);
}

function markComplete(taskId) {
  if (confirm('Are you sure you want to mark this task as completed?')) {
    $.post('update_status.php', { id: taskId }, function(response) {
      if (response.trim() === 'success') {
        showToast('✅ Task marked as completed!');
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast('❌ Failed to update task.', false);
      }
    }).fail(function(xhr) {
      showToast('⚠️ Error: ' + xhr.statusText, false);
    });
  }
}
</script>

</body>
</html>
