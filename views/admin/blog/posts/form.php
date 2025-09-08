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
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog/posts') ?>">Posts</a></li>
          <li class="breadcrumb-item active"><?= $post ? 'Edit' : 'Create' ?></li>
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
            <h3 class="card-title">Post Details</h3>
          </div>
          <form method="POST" action="<?= $post ? url('/admin/blog/posts/' . $post->id) : url('/admin/blog/posts') ?>">
            <?= csrf_field() ?>
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= $post ? e($post->title) : '' ?>" required>
                  </div>
                  
                  <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="<?= $post ? e($post->slug) : '' ?>" placeholder="auto-generated from title">
                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                  </div>
                  
                  <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3" placeholder="Brief description of the post"><?= $post ? e($post->excerpt) : '' ?></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?= $post ? e($post->content) : '' ?></textarea>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                      <option value="draft" <?= $post && $post->status === 'draft' ? 'selected' : '' ?>>Draft</option>
                      <option value="published" <?= $post && $post->status === 'published' ? 'selected' : '' ?>>Published</option>
                      <option value="archived" <?= $post && $post->status === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="category_id">Category</label>
                    <select class="form-control" id="category_id" name="category_id">
                      <option value="">Select Category</option>
                      <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>" <?= $post && $post->category_id == $category->id ? 'selected' : '' ?>>
                          <?= e($category->name) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="featured_image">Featured Image URL</label>
                    <input type="url" class="form-control" id="featured_image" name="featured_image" value="<?= $post ? e($post->featured_image) : '' ?>" placeholder="https://example.com/image.jpg">
                  </div>
                  
                  <div class="form-group">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?= $post && $post->is_featured ? 'checked' : '' ?>>
                      <label class="form-check-label" for="is_featured">Featured Post</label>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Tags</label>
                    <?php foreach ($tags as $tag): ?>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="tag_<?= $tag->id ?>" name="tags[]" value="<?= $tag->id ?>" <?= in_array($tag->id, $postTagIds) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="tag_<?= $tag->id ?>">
                          <span class="badge" style="background-color: <?= e($tag->color) ?>"><?= e($tag->name) ?></span>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-12">
                  <h5>SEO Settings</h5>
                  <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= $post ? e($post->meta_title) : '' ?>" placeholder="SEO title for search engines">
                  </div>
                  
                  <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="2" placeholder="SEO description for search engines"><?= $post ? e($post->meta_description) : '' ?></textarea>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary"><?= $post ? 'Update Post' : 'Create Post' ?></button>
              <a href="<?= url('/admin/blog/posts') ?>" class="btn btn-secondary">Cancel</a>
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
