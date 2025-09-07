<?php $user = $user ?? null; ?>

<?php if (!$user): ?>
    <h1>User not found</h1>
<?php else: ?>
    <h1><?= e($title ?? 'User Profile') ?></h1>
    <dl>
        <dt>ID</dt>
        <dd><?= e(is_object($user) ? ($user->id ?? '') : ($user['id'] ?? '')) ?></dd>
        <dt>Name</dt>
        <dd><?= e(is_object($user) ? ($user->name ?? '') : ($user['name'] ?? '')) ?></dd>
        <dt>Email</dt>
        <dd><?= e(is_object($user) ? ($user->email ?? '') : ($user['email'] ?? '')) ?></dd>
    </dl>
    <p><a href="<?= e(url('/users')) ?>">Back to users</a></p>
<?php endif; ?>


