<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Blog Posts</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog') ?>">Blog</a></li>
          <li class="breadcrumb-item active">Posts</li>
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
                <h3 class="card-title">All Posts</h3>
                <div class="card-tools">
                  <a href="<?= url('/admin/blog/posts/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Post
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($posts)): ?>
                  <div class="text-center py-4">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No posts found</h4>
                    <p class="text-muted">Get started by creating your first blog post.</p>
                    <a href="<?= url('/admin/blog/posts/create') ?>" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Create First Post
                    </a>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Category</th>
                          <th>Status</th>
                          <th>Featured</th>
                          <th>Author</th>
                          <th>Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($posts as $post): ?>
                          <tr>
                            <td>
                              <strong><?= e($post->title) ?></strong>
                              <br>
                              <small class="text-muted"><?= e($post->slug) ?></small>
                            </td>
                            <td>
                              <?php if ($post->category()): ?>
                                <span class="badge" style="background-color: <?= e($post->category()->color) ?>">
                                  <?= e($post->category()->name) ?>
                                </span>
                              <?php else: ?>
                                <span class="text-muted">No category</span>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php
                              $statusClass = match($post->status) {
                                'published' => 'success',
                                'draft' => 'secondary',
                                'archived' => 'danger',
                                default => 'secondary'
                              };
                              ?>
                              <span class="badge badge-<?= $statusClass ?>"><?= ucfirst($post->status) ?></span>
                            </td>
                            <td>
                              <?php if ($post->is_featured): ?>
                                <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td><?= e($post->author()->name ?? 'Unknown') ?></td>
                            <td><?= date('M j, Y', strtotime($post->created_at)) ?></td>
                            <td>
                              <div class="btn-group">
                                <a href="<?= url('/admin/blog/posts/' . $post->id . '/edit') ?>" class="btn btn-sm btn-primary">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= url('/admin/blog/posts/' . $post->id . '/delete') ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this post?')">
                                  <i class="fas fa-trash"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
