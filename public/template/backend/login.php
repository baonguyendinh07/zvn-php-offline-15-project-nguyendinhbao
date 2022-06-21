<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'html/head.php'; ?>
</head>

<body class="hold-transition login-page">
    <?php
    require_once APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
    ?>
    <!-- /.login-box -->

    <!-- jQuery -->
    <?php require_once 'html/script.php'; ?>
</body>

</html>