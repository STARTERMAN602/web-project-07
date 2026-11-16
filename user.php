<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION["userID"])) {
    header("location: login.php?status=not_loged");
    exit;
}
$username = $_SESSION["username"];
$email    = $_SESSION["email"];
$role     = $_SESSION["role"];
$dashboard = $role == "Admin" ? "indexAdmin.php" : "indexCustomer.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Profile - HealthyCare</title>
  <link rel="stylesheet" href="style.css">
  <script src="theme.js"></script>
</head>
<body>

  <div style="position: absolute; top: 20px; right: 24px; z-index: 10;">
    <button type="button" class="theme-btn-top" id="btn-theme" title="Ganti Mode Terang / Gelap" style="width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--border); background: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer;">
      <img src="assets/app-icon/sun.png" id="icon-theme" alt="Mode" style="width: 20px; height: 20px; object-fit: contain;">
    </button>
  </div>

  <main class="auth-wrapper">
    <a href="<?= $dashboard ?>" class="brand-header">
      <div class="brand-logo">
        <img src="assets/app-icon/appLogo.png" alt="app logo">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <div class="auth-card">
        <div class="card-body">
          <div class="profile-header">
            <h1 class="profile-name"><?= $username ?></h1>
          </div>

          <div class="profile-info">
            <div class="info-row">
              <span class="info-label">Username</span>
              <span class="info-value">@<?= $username ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value"><?= $email ?></span>
            </div>
            <div class="info-row">
              <span class="info-label">Role</span>
              <span class="info-value"><?= $role ?></span>
            </div>
          </div>

          <div class="btn-stack">
            <a href="<?= $dashboard ?>" class="btn-primary">Return to dashboard</a>

            <div style="display: flex; gap: 10px;">
              <a href="logout.php" class="btn-danger-outline" style="flex: 1;">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>