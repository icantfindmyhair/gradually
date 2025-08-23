<?php
$pageTitle = 'Add Exercise';

// ✅ Auth check first (prevent partial rendering if unauthorized)
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/helpers.php';

// ✅ Include header after authentication passes
include __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-4">Add Exercise</h1>

<form method="post" action="/exercise/store.php" class="card p-4 shadow-sm" novalidate>
  <!-- ✅ CSRF token -->
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

  <div class="row g-3">
    <!-- Exercise Type -->
    <div class="col-md-6">
      <label for="exercise_type" class="form-label">Exercise Type</label>
      <input 
        type="text" 
        name="exercise_type" 
        id="exercise_type" 
        class="form-control" 
        placeholder="e.g. Jogging" 
        required 
        maxlength="100"  <!-- ✅ Limit length -->
      >
    </div>

    <!-- Duration -->
    <div class="col-md-3">
      <label for="duration_min" class="form-label">Duration (min)</label>
      <input 
        type="number" 
        min="1" 
        max="1440"   <!-- ✅ Prevent unrealistic values -->
        name="duration_min" 
        id="duration_min" 
        class="form-control" 
        required
      >
    </div>

    <!-- Calories -->
    <div class="col-md-3">
      <label for="calories_burned" class="form-label">Calories Burned</label>
      <input 
        type="number" 
        step="0.01" 
        min="0" 
        max="10000"  <!-- ✅ Reasonable upper bound -->
        name="calories_burned" 
        id="calories_burned" 
        class="form-control" 
        required
      >
    </div>

    <!-- Date -->
    <div class="col-md-4">
      <label for="date" class="form-label">Date</label>
      <input 
        type="date" 
        name="date" 
        id="date" 
        class="form-control" 
        required 
        value="<?= date('Y-m-d') ?>"
        max="<?= date('Y-m-d') ?>" <!-- ✅ Prevent future dates -->
      >
    </div>

    <!-- Notes -->
    <div class="col-12">
      <label for="notes" class="form-label">Notes (optional)</label>
      <textarea 
        name="notes" 
        id="notes" 
        rows="3" 
        class="form-control" 
        placeholder="Any details..."
        maxlength="500" <!-- ✅ Prevent abuse -->
      ></textarea>
    </div>
  </div>

  <!-- Buttons -->
  <div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">💾 Save</button>
    <a href="/exercise/index.php" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
