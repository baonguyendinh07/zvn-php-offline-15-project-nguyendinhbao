<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'html/head.php'; ?>
</head>

<body>
    <?php require_once 'html/loader-skeleton.php'; ?>

    <!-- header start -->
    <?php require_once 'html/my-header.php'; ?>
    <!-- header end -->

    <!-- Content -->

    <?php
    require_once APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
    ?>

    <!-- Content end -->

    <!-- footer -->
    <?php require_once 'html/phonering.php'; ?>
    <?php require_once 'html/footer.php'; ?>

    <!-- footer end -->

    <!-- tap to top -->
    <div class="tap-top top-cls">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top end -->
    <?php require_once 'html/script.php'; ?>
</body>

</html>