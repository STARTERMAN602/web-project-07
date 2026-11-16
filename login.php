<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login to your account - HealthyCare</title>
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
          <h1 class="card-title">Login to your account</h1>

          <?php 
          if (isset($_GET["status"])) { 
            if($_GET["status"] == "registed") {
            ?>
            <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(52, 211, 153, 0.4); color: #34d399; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; text-align: center;">
            Account succesfully created, please login to continue.   
            </div>
            <?php
            } else if($_GET["status"] == "not_loged"){
            ?>
            <div style="background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(251, 113, 133, 0.4); color: #fda4af; padding: 10px 12px; border-radius: 6px; font-size: 13px; margin-bottom: 16px; text-align: center;">
            You need to login to your account.   
            </div>
            <?php
            }
          } 
          ?>

          <form action="cekLogin.php" method="POST">
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
              <a href="forgotPassword.php" class="link-accent">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 6px;">Login</button>
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