<?php
include_once BLOCK_PATH . 'category.php';
if (isset($this->_userInfoParams['userInfo']['username'])) {
    $avatarLink = $this->_pathImg . 'avatar/' . $this->_userInfoParams['userInfo']['username'] . '.jpg';
    $userActionButton = '
            <li><a href="index.php?module=frontend&controller=user&action=profile">Profile</a></li>
            <li><a href="index.php?module=frontend&controller=user&action=logout">Đăng xuất</a></li>
            ';
} else {
    $avatarLink = $this->_pathImg . 'avatar.png';
    $userActionButton = '
            <li><a href="index.php?module=frontend&controller=user&action=login">Đăng nhập</a></li>
            <li><a href="index.php?module=frontend&controller=user&action=register">Đăng ký</a></li>
            ';
}
?>
<header class="my-header sticky">
    <div class="mobile-fix-option"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu">
                    <div class="menu-left">
                        <div class="brand-logo">
                            <a href="index.php?module=frontend&controller=index&action=index">
                                <h2 class="mb-0" style="color: #5fcbc4">BookStore</h2>
                            </a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-right">Back<i class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                                    </li>
                                    <li><a href="index.php?module=frontend&controller=index&action=index" class="my-menu-link active">Trang chủ</a></li>
                                    <li><a href="index.php?module=frontend&controller=book&action=list">Sách</a></li>
                                    <li>
                                        <a href="index.php?module=frontend&controller=book&action=category">Danh mục</a>
                                        <ul>
                                            <?= $headerCatarogy ?? '' ?>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="top-header">
                            <ul class="header-dropdown">
                                <li class="onhover-dropdown mobile-account">
                                    <img src="<?= $avatarLink ?>" alt="avatar" style="width:39px; height:39px;">
                                    <ul class="onhover-show-div">
                                        <?= $userActionButton ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <div class="icon-nav">
                                <ul>
                                    <li class="onhover-div mobile-search">
                                        <div>
                                            <img src="<?= $this->_pathImg ?>search.png" onclick="openSearch()" class="img-fluid blur-up lazyload" alt="">
                                            <i class="ti-search" onclick="openSearch()"></i>
                                        </div>
                                        <div id="search-overlay" class="search-overlay">
                                            <div>
                                                <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                                <div class="overlay-content">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <form method="GET">
                                                                    <div class="form-group">
                                                                        <input type="hidden" name="module" value="frontend">
                                                                        <input type="hidden" name="controller" value="book">
                                                                        <input type="hidden" name="action" value="list">
                                                                        <input type="text" class="form-control" name="search" id="search-input" placeholder="Tìm kiếm sách...">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="onhover-div mobile-cart">
                                        <div>
                                            <a href="cart.html" id="cart" class="position-relative">
                                                <img src="<?= $this->_pathImg ?>cart.png" class="img-fluid blur-up lazyload" alt="cart">
                                                <i class="ti-shopping-cart"></i>
                                                <span class="badge badge-warning">0</span>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>