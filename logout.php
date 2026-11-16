<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout - HealthyCare</title>
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
    <a href="login.php" class="brand-header">
      <div class="brand-logo">
        <img src="assets/app-icon/appLogo.png" alt="app logo">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <div class="auth-card">
        <div class="card-body">
          <div class="status-icon success">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </div>

          <h1 class="card-title">You have been loged out</h1>
          <p class="card-desc">
            Thankyou for using HealthyCare service. Your session has been terminated.
          </p>

          <a href="login.php" class="btn-primary">Login Page</a>
        </div>
      </div>
    </div>
  </main>

</body>
</html>
