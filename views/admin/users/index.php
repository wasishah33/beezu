<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Management</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
