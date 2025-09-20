<div class="login-box">
  <div class="login-logo">
    <img width="100px" src="<?= asset('admin/img/logo.png') ?>">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flash_error) ?></div>
      <?php endif; ?>
      <form method="POST" action="<?= url('/admin/login') ?>">
        <?= csrf_field() ?>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" value="admin@example.com" placeholder="Email" required autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" value="admin123" placeholder="Password" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>