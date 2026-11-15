<?php
session_start();
include("koneksi.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginInput = trim($_POST["username"] ?? "");
    $password   = $_POST["password"] ?? "";

    $stmt = $connect->prepare("SELECT ID, username, email, password, role FROM accounts WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $loginInput, $loginInput);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Cocokkan password (support password_hash maupun teks biasa dari phpMyAdmin)
        if (password_verify($password, $user["password"]) || $password === $user["password"]) {
            $_SESSION["id"]       = $user["ID"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"]    = $user["email"];
            $_SESSION["role"]     = $user["role"];

            if ($user["role"] === "Admin") {
                header("Location: indexAdmin.php");
            } else {
                header("Location: indexCustomer.php");
            }
            exit;
        }
    }

    // Jika username/email tidak ketemu atau password salah -> arahkan ke nouser.php
    header("Location: nouser.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in to your account - HealthyCare</title>
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
      <div class="bg-illustration"></div>

      <div class="auth-card">
        <div class="card-body">
          <h1 class="card-title">Sign in to your account</h1>

          <?php if (isset($_GET["registered"])): ?>
            <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; text-align: center;">
              Akun berhasil dibuat! Silakan login.
            </div>
          <?php endif; ?>

          <form action="" method="POST">
            <div class="form-group">
              <label for="username">Username or email</label>
              <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <div class="password-wrapper">
                <input type="password" id="password" name="password" class="form-control" required>
                <button type="button" class="toggle-password" aria-label="Tampilkan password">
                  <svg class="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                  <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="form-options" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; font-size:13px;">
              <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color: var(--accent); width:15px; height:15px;"> Remember me
              </label>
              <a href="forgotPassword.php" class="link-accent">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 6px;">Sign In</button>
          </form>
        </div>

        <div class="card-footer">
          <span>New user?</span>
          <a href="regist.php" class="link-accent">Register</a>
        </div>
      </div>
    </div>
  </main>

  <script>
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input  = btn.previousElementSibling;
        const eye    = btn.querySelector('.icon-eye');
        const eyeOff = btn.querySelector('.icon-eye-off');
        const hidden = input.type === 'password';

        input.type           = hidden ? 'text' : 'password';
        eye.style.display    = hidden ? 'none' : 'block';
        eyeOff.style.display = hidden ? 'block' : 'none';
      });
    });
  </script>

</body>
</html>