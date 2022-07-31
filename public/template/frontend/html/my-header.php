<?php
include_once BLOCK_PATH . 'category.php';
if (isset($this->_userInfoParams['userInfo']['username'])) {
    $avatarLink = $this->_pathImg . 'avatar/' . $this->_userInfoParams['userInfo']['username'] . '.jpg';
    $userActionButton = '
            <li><a href="profile.html">Profile</a></li>
            <li><a href="logout.html">Đăng xuất</a></li>
            ';
} else {
    $avatarLink = $this->_pathImg . 'avatar.png';
    $userActionButton = '
            <li><a href="login.html">Đăng nhập</a></li>
            <li><a href="register.html">Đăng ký</a></li>
            ';
}

$totalQuantities = 0;
if (!empty(Session::get('cart'))) $totalQuantities = array_sum(Session::get('cart'));
?>
<header class="my-header sticky">
    <div class="mobile-fix-option"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu">
                    <div class="menu-left">
                        <div class="brand-logo">
                            <a href="index.html">
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
                                    <li>
                                        <a href="index.html" class="my-menu-link" data-active="index">
                                            Trang chủ
                                        </a>
                                    </li>
                                    <li>
                                        <a href="book.html" class="my-menu-link" data-active="list">
                                            Sách
                                        </a>
                                    </li>
                                    <li>
                                        <a href="category.html" class="my-menu-link" data-active="category">
                                            Danh mục
                                        </a>
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
                                    <img src="<?= $avatarLink ?>" alt="avatar" style="width:35px; height:35px;">
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
                                                                <form action="book.html" method="GET">
                                                                    <div class="form-group">
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
                                                <span class="badge badge-warning" id="totalQuantities"><?= $totalQuantities ?></span>
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