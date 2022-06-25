<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Thay đổi mật khẩu</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="faq-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="dashboard-left">
                    <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> Ẩn</span></div>
                    <div class="block-content">
                        <ul>
                            <li><a href="index.php?module=frontend&controller=user&action=profile">Thông tin tài khoản</a></li>
                            <li class="active"><a href="index.php?module=frontend&controller=user&action=changePassword">Thay đổi mật khẩu</a></li>
                            <li><a href="index.php?module=frontend&controller=user&action=orderHistory">Lịch sử mua hàng</a></li>
                            <li><a href="index.php?module=frontend&controller=user&action=logout">Đăng xuất</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <form action="" method="post" id="admin-form" class="theme-form">
                            <div class="form-group">
                                <label for="fullname">Mật khẩu cũ</label>
                                <input type="password" name="form[old-password]" value="" class="form-control">
                            </div>
                            <input type="hidden" id="form[token]" name="form[token]" value="1599258345"><button type="submit" id="submit" name="submit" value="Cập nhật thông tin" class="btn btn-solid btn-sm">Cập nhật thông tin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>