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



