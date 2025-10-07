function markComplete(taskId) {
  fetch("update_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "task_id=" + taskId,
  })
    .then((res) => res.text())
    .then((data) => {
      if (data === "success") {
        alert("Task marked complete!");
        location.reload();
      } else {
        alert("Failed to update!");
      }
    });
}
