<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?= $title ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog') ?>">Blog</a></li>
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog/categories') ?>">Categories</a></li>
          <li class="breadcrumb-item active"><?= $category ? 'Edit' : 'Create' ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Category Details</h3>
          </div>
          <form method="POST" action="<?= $category ? url('/admin/blog/categories/' . $category->id) : url('/admin/blog/categories') ?>">
            <?= csrf_field() ?>
            <div class="card-body">
              <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $category ? e($category->name) : '' ?>" required>
              </div>
              
              <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" value="<?= $category ? e($category->slug) : '' ?>" placeholder="auto-generated from name">
                <small class="form-text text-muted">Leave empty to auto-generate from name</small>
              </div>
              
              <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the category"><?= $category ? e($category->description) : '' ?></textarea>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="color">Color</label>
                    <input type="color" class="form-control" id="color" name="color" value="<?= $category ? e($category->color) : '#007bff' ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= $category ? $category->sort_order : 0 ?>" min="0">
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= $category && $category->is_active ? 'checked' : '' ?>>
                  <label class="form-check-label" for="is_active">Active</label>
                </div>
              </div>
            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary"><?= $category ? 'Update Category' : 'Create Category' ?></button>
              <a href="<?= url('/admin/blog/categories') ?>" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Preview</h3>
          </div>
          <div class="card-body">
            <div class="category-preview">
              <span class="badge" id="preview-badge" style="background-color: <?= $category ? e($category->color) : '#007bff' ?>; font-size: 14px;">
                <?= $category ? e($category->name) : 'Category Name' ?>
              </span>
              <p class="mt-2 text-muted" id="preview-description"><?= $category ? e($category->description) : 'Category description will appear here...' ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
  const name = this.value;
  const slug = name.toLowerCase()
    .replace(/[^a-z0-9 -]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim('-');
  
  if (!document.getElementById('slug').value) {
    document.getElementById('slug').value = slug;
  }
  
  // Update preview
  document.getElementById('preview-badge').textContent = name || 'Category Name';
});

// Update color preview
document.getElementById('color').addEventListener('change', function() {
  document.getElementById('preview-badge').style.backgroundColor = this.value;
});

// Update description preview
document.getElementById('description').addEventListener('input', function() {
  const description = this.value || 'Category description will appear here...';
  document.getElementById('preview-description').textContent = description;
});
</script>
