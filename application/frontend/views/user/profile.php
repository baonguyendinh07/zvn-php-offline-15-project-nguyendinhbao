<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Thông tin tài khoản</h2>
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
                            <li class="active"><a href="index.php?module=frontend&controller=user&action=profile">Thông tin tài khoản</a></li>
                            <li><a href="index.php?module=frontend&controller=user&action=changePassword">Thay đổi mật khẩu</a></li>
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
                                <label for="email">Email</label>
                                <input type="text" name="form[email]" value="admin@gmail.com" class="form-control" id="email" readonly="1">
                            </div>

                            <div class="form-group">
                                <label for="fullname">Họ tên</label>
                                <input type="text" name="form[fullname]" value="Nguyễn Văn Linh" class="form-control" id="fullname">
                            </div>

                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" name="form[phone]" value="0336405077" class="form-control" id="phone">
                            </div>

                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="form[address]" value="Số 19, Đường 23, KDC Ấp 5, Phong Phú, Bình Chánh, HCM" class="form-control" id="address">
                            </div>
                            <input type="hidden" id="form[token]" name="form[token]" value="1599258345"><button type="submit" id="submit" name="submit" value="Cập nhật thông tin" class="btn btn-solid btn-sm">Cập nhật thông tin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>