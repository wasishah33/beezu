<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - Admin' : 'Admin - Beezu Framework' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/assets/admin/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE CSS (optional) -->
    <link href="/assets/admin/css/adminlte.min.css" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link href="/assets/admin/css/style.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/admin" class="nav-link">Dashboard</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/admin" class="brand-link">
                <span class="brand-text font-weight-light">Beezu Admin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="/admin" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/users" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/settings" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                                <li class="breadcrumb-item active"><?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?= $content ?>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; <?= date('Y') ?> <a href="/">Beezu Framework</a>.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="/assets/admin/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/assets/admin/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="/assets/admin/js/adminlte.min.js"></script>
    <!-- Font Awesome -->
    <script src="/assets/admin/js/fontawesome.min.js"></script>
    <!-- Custom Admin JS -->
    <script src="/assets/admin/js/script.js"></script>
</body>
</html>
