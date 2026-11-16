<?php
session_start();
include("koneksi.php");

$error = "";
$username = "";
$email = "";
$role = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";
    $email    = trim($_POST["email"] ?? "");
    $role     = $_POST["role"] ?? "";

    if ($username === "" || $password === "" || $email === "" || ($role !== "Admin" && $role !== "Customer")) {
      $error = "Insert all fields corectly.";
    } elseif ($password !== $confirm) {
      $error = "Password and confirmed password do not match.";
    } else {
      $query = mysqli_query($connect, "select * from accounts where email = '$email' or username = '$username'");
      $count = mysqli_num_rows($query);

      if($count > 0) {
        $error = "Username or email alredy registered.";
      } else { 
        mysqli_query($connect, "insert into accounts (username, email, password, role) values ('$username', '$email','$password', '$role')");
        header("location: login.php?status=registed");
      }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - HealthyCare</title>
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
          <div class="required-note">
            <span class="required-star">*</span> Required fields
          </div>

          <h1 class="card-title">Register</h1>
          
          <?php if ($error !== ""){ ?>
            <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(251, 113, 133, 0.4); color: #fda4af; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
              <?= $error ?>
            </div>
          <?php } ?>

          <form action="" method="POST">
            <div class="form-group">
              <label for="reg-username">Username <span class="required-star">*</span></label>
              <input type="text" id="reg-username" name="username" class="form-control" value="<?= $username ?>" autofocus>
            </div>

            <div class="form-group">
              <label for="reg-password">Password <span class="required-star">*</span></label>
              <div class="password-wrapper">
                <input type="password" id="reg-password" name="password" class="form-control">
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

            <div class="form-group">
              <label for="reg-confirm">Confirm password <span class="required-star">*</span></label>
              <div class="password-wrapper">
                <input type="password" id="reg-confirm" name="confirm_password" class="form-control">
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

            <div class="form-group">
              <label for="reg-email">Email <span class="required-star">*</span></label>
              <input type="email" id="reg-email" name="email" class="form-control" value="<?= $email ?>">
            </div>

            <div class="form-group">
              <label for="reg-role">Role <span class="required-star">*</span></label>
              <select id="reg-role" name="role" class="form-control">
                <option value="" disabled <?= ($role === "") ? "selected" : "" ?>>-- Choose Role --</option>
                <option value="Customer" <?= ($role === "Customer") ? "selected" : "" ?>>Customer</option>
                <option value="Admin" <?= ($role === "Admin") ? "selected" : "" ?>>Admin</option>
              </select>
            </div>

            <div class="back-link-wrap">
              <a href="login.php" class="link-accent">&laquo; Back to Login</a>
            </div>

            <button type="submit" class="btn-primary">Register</button>
          </form>
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