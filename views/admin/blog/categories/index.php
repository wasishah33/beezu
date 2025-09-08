<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Categories</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog') ?>">Blog</a></li>
          <li class="breadcrumb-item active">Categories</li>
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
                <h3 class="card-title">All Categories</h3>
                <div class="card-tools">
                  <a href="<?= url('/admin/blog/categories/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Category
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($categories)): ?>
                  <div class="text-center py-4">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No categories found</h4>
                    <p class="text-muted">Get started by creating your first category.</p>
                    <a href="<?= url('/admin/blog/categories/create') ?>" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Create First Category
                    </a>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Slug</th>
                          <th>Color</th>
                          <th>Posts</th>
                          <th>Status</th>
                          <th>Sort Order</th>
                          <th>Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($categories as $category): ?>
                          <tr>
                            <td>
                              <strong><?= e($category->name) ?></strong>
                              <?php if ($category->description): ?>
                                <br>
                                <small class="text-muted"><?= e($category->description) ?></small>
                              <?php endif; ?>
                            </td>
                            <td>
                              <code><?= e($category->slug) ?></code>
                            </td>
                            <td>
                              <span class="badge" style="background-color: <?= e($category->color) ?>">
                                <?= e($category->color) ?>
                              </span>
                            </td>
                            <td>
                              <span class="badge badge-info"><?= $category->getPublishedPostsCount() ?> posts</span>
                            </td>
                            <td>
                              <?php if ($category->is_active): ?>
                                <span class="badge badge-success">Active</span>
                              <?php else: ?>
                                <span class="badge badge-secondary">Inactive</span>
                              <?php endif; ?>
                            </td>
                            <td><?= $category->sort_order ?></td>
                            <td><?= date('M j, Y', strtotime($category->created_at)) ?></td>
                            <td>
                              <div class="btn-group">
                                <a href="<?= url('/admin/blog/categories/' . $category->id . '/edit') ?>" class="btn btn-sm btn-primary">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= url('/admin/blog/categories/' . $category->id . '/delete') ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this category?')">
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
