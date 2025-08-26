<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= isset($pageTitle) ? e($pageTitle) : 'Exercise Tracker' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Optional: Chart.js for the dashboard chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <style>
    .card-stat { border-radius: 1rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/exercise/index.php">Student Routine • Exercise</a>
    <div class="ms-auto d-flex align-items-center">

      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="text-light me-3">
          👤 <?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
        </span>
        <a href="/exercise/create.php" class="btn btn-sm btn-success me-2">+ Add Exercise</a>
        <a href="/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
      <?php else: ?>
        <a href="/login.php" class="btn btn-sm btn-outline-light">Login</a>
      <?php endif; ?>

    </div>
  </div>
</nav>
<main class="container my-4">
