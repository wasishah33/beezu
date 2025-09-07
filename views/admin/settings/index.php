<div class="row">
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">System Settings</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="/admin/settings">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" 
                        value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="maintenance_mode" name="maintenance_mode" 
                            <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="maintenance_mode">
                            Maintenance Mode
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="debug_mode" name="debug_mode" 
                            <?= ($settings['debug_mode'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="debug_mode">
                            Debug Mode
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </form>
        </div>
    </div>
</div>
</div>