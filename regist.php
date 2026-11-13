<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <main class="auth-wrapper">
    <!-- Logo & Nama Brand -->
    <a href="#" class="brand-header">
      <div class="brand-logo">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="9" fill="#ff6740"/>
          <path d="M8 14C9.5 11.5 14.5 11.5 16 14" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>
        </svg>
      </div>
      <span class="brand-title">MangaDex</span>
    </a>

    <div class="card-area">
      <!-- Gambar karakter di belakang kiri card -->
      <div class="bg-illustration"></div>

      <div class="auth-card">
        <div class="card-body">
          <div class="required-note">
            <span class="required-star">*</span> Required fields
          </div>

          <h1 class="card-title">Register</h1>

          <form action="#" method="POST">
            <div class="form-group">
              <label for="reg-username">Username <span class="required-star">*</span></label>
              <input type="text" id="reg-username" name="username" class="form-control" required autofocus>
            </div>

            <div class="form-group">
              <label for="reg-password">Password <span class="required-star">*</span></label>
              <input type="password" id="reg-password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="reg-confirm">Confirm password <span class="required-star">*</span></label>
              <input type="password" id="reg-confirm" name="confirm_password" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="reg-email">Email <span class="required-star">*</span></label>
              <input type="email" id="reg-email" name="email" class="form-control" required>
            </div>

            <div class="back-link-wrap">
              <a href="login.html" class="link-accent">&laquo; Back to Login</a>
            </div>

            <button type="submit" class="btn-primary">Register</button>
          </form>
        </div>
      </div>
    </div>
  </main>

</body>
</html>