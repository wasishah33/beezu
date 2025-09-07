<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<?php partial('admin.includes.headtag') ?>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <?php partial('admin.includes.nav') ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php partial('admin.includes.sidebar') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?=$title?></h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
          {{content}}
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>

    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <?php partial('admin.includes.footer') ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <?php partial('admin.includes.foottag') ?>
</body>

</html>