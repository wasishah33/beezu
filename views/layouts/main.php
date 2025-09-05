<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Beezu Framework' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/assets/front/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Front CSS -->
    <link href="/assets/front/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Beezu Framework</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/users">Users</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Beezu Framework. All rights reserved.</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="/assets/front/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/assets/front/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Front JS -->
    <script src="/assets/front/js/script.js"></script>
</body>
</html>

