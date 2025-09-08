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
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog/pages') ?>">Pages</a></li>
          <li class="breadcrumb-item active"><?= $page ? 'Edit' : 'Create' ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Page Details</h3>
          </div>
          <form method="POST" action="<?= $page ? url('/admin/blog/pages/' . $page->id) : url('/admin/blog/pages') ?>">
            <?= csrf_field() ?>
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $page ? e($page->title) : '' ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?= $page ? e($page->slug) : '' ?>" placeholder="auto-generated from title">
                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                  </div>
                  
                  <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief description of the page"><?= $page ? e($page->excerpt) : '' ?></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?= $page ? e($page->content) : '' ?></textarea>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                      <option value="draft" <?= $page && $page->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                      <option value="published" <?= $page && $page->status === 'published' ? 'selected' : '' ?>>Published</option>
                      <option value="archived" <?= $page && $page->status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="template">Template</label>
                    <select class="form-control" id="template" name="template">
                      <option value="default" <?= $page && $page->template === 'default' ? 'selected' : '' ?>>Default</option>
                      <option value="full-width" <?= $page && $page->template === 'full-width' ? 'selected' : '' ?>>Full Width</option>
                      <option value="sidebar" <?= $page && $page->template === 'sidebar' ? 'selected' : '' ?>>With Sidebar</option>
                      <option value="landing" <?= $page && $page->template === 'landing' ? 'selected' : '' ?>>Landing Page</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="is_homepage" name="is_homepage" value="1" <?= $page && $page->is_homepage ? 'checked' : '' ?>>
                      <label class="form-check-label" for="is_homepage">Set as Homepage</label>
                    </div>
                    <small class="form-text text-muted">Only one page can be the homepage</small>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-12">
                  <h5>SEO Settings</h5>
                  <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= $page ? e($page->meta_title) : '' ?>" placeholder="SEO title for search engines">
                  </div>
                  
                  <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2" placeholder="SEO description for search engines"><?= $page ? e($page->meta_description) : '' ?></textarea>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary"><?= $page ? 'Update Page' : 'Create Page' ?></button>
              <a href="<?= url('/admin/blog/pages') ?>" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
  const title = this.value;
  const slug = title.toLowerCase()
    .replace(/[^a-z0-9 -]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim('-');
  
  if (!document.getElementById('slug').value) {
    document.getElementById('slug').value = slug;
  }
});
</script>
