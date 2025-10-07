<?php
include '../includes/functions.php';
checkLogin('admin');
$tasks = getAllTasks($conn);
$employees = $conn->query("SELECT * FROM users WHERE role='employee'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Task Management System</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #0d6efd;
    }
    .navbar-brand, .nav-link, .navbar-text {
      color: #fff !important;
    }
    .table-container {
      margin-top: 50px;
    }
    .table thead {
      background-color: #0d6efd;
      color: white;
    }
    .btn-action {
      text-decoration: none;
      font-weight: 500;
    }
    .btn-action:hover {
      text-decoration: underline;
    }
    .modal-header {
      background-color: #0d6efd;
      color: white;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="#">Admin Dashboard</a>
    <div class="d-flex align-items-center">
      <span class="navbar-text me-3">
        Welcome, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
      </span>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-semibold">Task List</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
      + Add New Task
    </button>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $tasks->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td>
                <span class="badge bg-<?= $row['status'] == 'completed' ? 'success' : 'warning'; ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </td>
              <td><?= $row['due_date'] ?></td>
              <td>
                <a href="#" class="btn-action text-primary edit-btn"
                   data-id="<?= $row['id'] ?>"
                   data-title="<?= htmlspecialchars($row['title']) ?>"
                   data-description="<?= htmlspecialchars($row['description']) ?>"
                   data-assigned="<?= $row['assigned_to'] ?>"
                   data-due="<?= $row['due_date'] ?>"
                   data-status="<?= $row['status'] ?>">Edit</a> |
                <a href="delete_task.php?id=<?= $row['id'] ?>" 
                   class="btn-action text-danger"
                   onclick="return confirm('Are you sure you want to delete this task?');">
                   Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="add_task.php">
        <div class="modal-header">
          <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Assign To</label>
            <select name="assigned_to" class="form-select">
              <?php
              $employees->data_seek(0);
              while ($emp = $employees->fetch_assoc()):
              ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['username']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Due Date</label>
            <input type="date" name="due_date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Task</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editTaskForm" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" id="edit_description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Assign To</label>
            <select name="assigned_to" id="edit_assigned" class="form-select">
              <?php
              $employees->data_seek(0);
              while ($emp = $employees->fetch_assoc()):
              ?>
                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['username']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Due Date</label>
            <input type="date" name="due_date" id="edit_due" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" id="edit_status" class="form-select">
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Task</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/bootstrap.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Handle Edit button click
$('.edit-btn').click(function() {
  $('#edit_id').val($(this).data('id'));
  $('#edit_title').val($(this).data('title'));
  $('#edit_description').val($(this).data('description'));
  $('#edit_assigned').val($(this).data('assigned'));
  $('#edit_due').val($(this).data('due'));
  $('#edit_status').val($(this).data('status'));
  $('#editTaskModal').modal('show');
});

// AJAX submit Edit form
$('#editTaskForm').submit(function(e) {
  e.preventDefault();
  $.ajax({
    url: 'update_task.php',
    type: 'POST',
    data: $(this).serialize(),
    success: function() {
      location.reload(); // Refresh dashboard after update
    }
  });
});
</script>

</body>
</html>
