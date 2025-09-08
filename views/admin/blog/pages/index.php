<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Pages</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= url('/admin/blog') ?>">Blog</a></li>
          <li class="breadcrumb-item active">Pages</li>
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
                <h3 class="card-title">All Pages</h3>
                <div class="card-tools">
                  <a href="<?= url('/admin/blog/pages/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Page
                  </a>
                </div>
              </div>
              <div class="card-body">
                <?php if (empty($pages)): ?>
                  <div class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No pages found</h4>
                    <p class="text-muted">Get started by creating your first page.</p>
                    <a href="<?= url('/admin/blog/pages/create') ?>" class="btn btn-primary">
                      <i class="fas fa-plus"></i> Create First Page
                    </a>
                  </div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Slug</th>
                          <th>Template</th>
                          <th>Status</th>
                          <th>Homepage</th>
                          <th>Author</th>
                          <th>Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($pages as $page): ?>
                          <tr>
                            <td>
                              <strong><?= e($page->title) ?></strong>
                              <?php if ($page->excerpt): ?>
                                <br>
                                <small class="text-muted"><?= e(substr($page->excerpt, 0, 100)) ?>...</small>
                              <?php endif; ?>
                            </td>
                            <td>
                              <code><?= e($page->slug) ?></code>
                            </td>
                            <td>
                              <span class="badge badge-secondary"><?= e($page->template) ?></span>
                            </td>
                            <td>
                              <?php
                              $statusClass = match($page->status) {
                                'published' => 'success',
                                'draft' => 'secondary',
                                'archived' => 'danger',
                                default => 'secondary'
                              };
                              ?>
                              <span class="badge badge-<?= $statusClass ?>"><?= ucfirst($page->status) ?></span>
                            </td>
                            <td>
                              <?php if ($page->is_homepage): ?>
                                <span class="badge badge-warning"><i class="fas fa-home"></i> Homepage</span>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                            <td><?= e($page->author()->name ?? 'Unknown') ?></td>
                            <td><?= date('M j, Y', strtotime($page->created_at)) ?></td>
                            <td>
                              <div class="btn-group">
                                <a href="<?= url('/admin/blog/pages/' . $page->id . '/edit') ?>" class="btn btn-sm btn-primary">
                                  <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= url('/admin/blog/pages/' . $page->id . '/delete') ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this page?')">
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
