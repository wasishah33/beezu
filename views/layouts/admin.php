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
    {{content}}
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?php partial('admin.includes.footer') ?>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <?php partial('admin.includes.foottag') ?>
</body>

</html>