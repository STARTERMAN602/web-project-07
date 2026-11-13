<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in to your account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="auth-wrapper">
    <!-- Logo & Nama Brand -->
    <a href="#" class="brand-header">
      <div class="brand-logo">
        <!-- SVG Ikon Placeholder (bisa diganti <img> logo webmu) -->
        <img src="precision-medicine.png">
      </div>
      <span class="brand-title">HealthyCare</span>
    </a>

    <div class="card-area">
      <!-- Gambar karakter di belakang kiri card -->
      <div class="bg-illustration"></div>

      <div class="auth-card">
        <div class="card-body">
          <h1 class="card-title">Sign in to your account</h1>

          <form action="#" method="POST">
            <div class="form-group">
              <label for="username">Username or email</label>
              <input type="text" id="username" name="username" class="form-control" required autofocus>
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 6px;">Sign In</button>

          </form>
        </div>

        <div class="card-footer">
          <span>New user?</span>
          <a href="register.html" class="link-accent">Register</a>
        </div>
      </div>
    </div>
  </main>

</body>
</html>