<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Blog Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item active">Blog</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $stats['total_posts'] ?></h3>
                <p>Total Posts</p>
              </div>
              <div class="icon">
                <i class="fas fa-newspaper"></i>
              </div>
              <a href="<?= url('/admin/blog/posts') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $stats['published_posts'] ?></h3>
                <p>Published Posts</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <a href="<?= url('/admin/blog/posts') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $stats['total_categories'] ?></h3>
                <p>Categories</p>
              </div>
              <div class="icon">
                <i class="fas fa-tags"></i>
              </div>
              <a href="<?= url('/admin/blog/categories') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $stats['total_pages'] ?></h3>
                <p>Pages</p>
              </div>
              <div class="icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <a href="<?= url('/admin/blog/pages') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Recent Posts -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Recent Posts</h3>
                <div class="card-tools">
                  <a href="<?= url('/admin/blog/posts/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Post
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($recent_posts)): ?>
                  <p class="text-muted">No posts found. <a href="<?= url('/admin/blog/posts/create') ?>">Create your first post</a></p>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Status</th>
                          <th>Author</th>
                          <th>Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($recent_posts as $post): ?>
                          <tr>
                            <td>
                              <strong><?= e($post->title) ?></strong>
                              <?php if ($post->is_featured): ?>
                                <span class="badge badge-warning">Featured</span>
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
                            <td><?= e($post->author()->name ?? 'Unknown') ?></td>
                            <td><?= date('M j, Y', strtotime($post->created_at)) ?></td>
                            <td>
                              <a href="<?= url('/admin/blog/posts/' . $post->id . '/edit') ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                              </a>
                              <a href="<?= url('/admin/blog/posts/' . $post->id . '/delete') ?>" 
                                 class="btn btn-sm btn-danger" 
                                 onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class="fas fa-trash"></i>
                              </a>
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
