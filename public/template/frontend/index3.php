<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'html/head.php'; ?>
</head>

<body>
    <?php require_once 'html/loader-skeleton.php'; ?>

    <!-- header start -->
    <?php require_once 'html/header.php'; ?>
    <!-- header end -->
    <!-- Tab product -->

    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <h2 class="py-2">Lịch sử mua hàng</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="faq-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="account-sidebar">
                        <a class="popup-btn">Menu</a>
                    </div>
                    <h3 class="d-lg-none">Lịch sử mua hàng</h3>
                    <div class="dashboard-left">
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> Ẩn</span></div>
                        <div class="block-content">
                            <ul>
                                <li class=""><a href="index.php?module=frontend&controller=profile&action=accountForm">Thông tin tài khoản</a></li>
                                <li class=""><a href="index.php?module=frontend&controller=profile&action=changePassword">Thay đổi mật khẩu</a></li>
                                <li class="active"><a href="index.php?module=frontend&controller=profile&action=orderHistory">Lịch sử mua hàng</a></li>
                                <li class=""><a href="index.php?module=frontend&controller=login&action=logout">Đăng xuất</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <?php
                    require_once APPLICATION_PATH . $this->_moduleName . DS . 'views' . DS . $this->_fileView . '.php';
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Tab product end -->

    <!-- Quick-view modal popup start-->
    <!-- Quick-view modal popup end-->

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