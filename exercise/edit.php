<?php
$pageTitle = 'Edit Exercise';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    redirect('/exercise/index.php');
}

$sql = "SELECT * FROM exercises WHERE exercise_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Edit prepare failed: " . $conn->error);
    exit("An unexpected error occurred. Please try again later.");
}

$stmt->bind_param('ii', $id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$exercise = $result->fetch_assoc();

if (!$exercise) {
    exit("Exercise not found or you don't have permission to edit it.");
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow-sm">
  <div class="card-header bg-primary text-white">
    <h4 class="mb-0">Edit Exercise</h4>
  </div>
  <div class="card-body">
    <form method="post" action="/exercise/update.php" class="needs-validation" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="id" value="<?= (int)$exercise['exercise_id'] ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Exercise Type</label>
          <input type="text" name="exercise_type" class="form-control" required 
                 value="<?= e($exercise['exercise_type']) ?>">
          <div class="invalid-feedback">Please enter the exercise type.</div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Duration (min)</label>
          <input type="number" min="1" name="duration_min" class="form-control" required 
                 value="<?= (int)$exercise['duration_min'] ?>">
          <div class="invalid-feedback">Duration must be at least 1 minute.</div>
        </div>

        <div class="col-md-3">
          <label class="form-label">Calories Burned</label>
          <input type="number" step="0.01" min="0" name="calories_burned" class="form-control" required
                 value="<?= number_format((float)$exercise['calories_burned'], 2, '.', '') ?>">
          <div class="invalid-feedback">Calories must be a non-negative number.</div>
        </div>

        <div class="col-md-4">
          <label class="form-label">Date</label>
          <input type="date" name="date" class="form-control" required 
                 value="<?= e($exercise['date']) ?>">
          <div class="invalid-feedback">Please select a valid date.</div>
        </div>

        <div class="col-12">
          <label class="form-label">Notes (optional)</label>
          <textarea name="notes" rows="3" class="form-control"><?= e($exercise['notes']) ?></textarea>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-success"><i class="bi bi-save"></i> Update</button>
        <a href="/exercise/index.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
// Bootstrap client-side validation
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
})();

// Warn if leaving with unsaved changes
let formChanged = false;
document.querySelectorAll('input, textarea').forEach(el => {
  el.addEventListener('change', () => formChanged = true);
});
window.addEventListener('beforeunload', function (e) {
  if (formChanged) {
    e.preventDefault();
    e.returnValue = '';
  }
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
