<h1><?= e($title ?? 'Users') ?></h1>

<?php $users = $users ?? []; ?>

<?php if (empty($users)): ?>
    <p>No users found.</p>
<?php else: ?>
    <p>Total: <?= count($users) ?></p>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <?php $id = is_object($u) ? ($u->id ?? null) : ($u['id'] ?? null); ?>
            <tr>
                <td><?= e($id) ?></td>
                <td><?= e(is_object($u) ? ($u->name ?? '') : ($u['name'] ?? '')) ?></td>
                <td><?= e(is_object($u) ? ($u->email ?? '') : ($u['email'] ?? '')) ?></td>
                <td>
                    <?php if ($id !== null): ?>
                        <a href="<?= e(url('/users/' . $id)) ?>">View</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>


