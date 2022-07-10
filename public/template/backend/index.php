<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'html/head.php'; ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once 'html/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once 'html/sidebar.php'; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <?php require_once 'html/page-header.php'; ?>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?php
                    require_once APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
                    ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->

        <?php require_once 'html/footer.php'; ?>
    </div>
    <!-- ./wrapper -->
    <?php require_once 'html/script.php'; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            let controller = '<?= $_GET['controller'] ?>';
            let action = '<?= $_GET['action'] ?>';

            if (controller == 'user' && action == 'changeAccountPassword') controller = action;

            let $currentMenuItemLevel1 = $('.nav-sidebar > .nav-item > [data-active="' + controller + '"]');
            $currentMenuItemLevel1.addClass('active');

            let $navTreeview = $currentMenuItemLevel1.next();
            if ($navTreeview.length > 0) {
                let $currentMenuItemLevel2 = $navTreeview.find('[data-active="' + action + '"]');
                $currentMenuItemLevel2.addClass('active');
                $currentMenuItemLevel1.parent().addClass('menu-open');
            } else {
                $('.nav-sidebar > .nav-item > [data-active="' + action + '"]').addClass('active');
            }
        });
    </script>
</body>

</html>