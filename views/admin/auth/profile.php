<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Account</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item">Account</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="card mb-4">
      <div class="card-header">Change Password</div>
      <div class="card-body">
      <?php if (!empty($flash_error)): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($flash_error) ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= url('/admin/profile/password') ?>">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control" minlength="8" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
          </div>
          <button class="btn btn-primary">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>



