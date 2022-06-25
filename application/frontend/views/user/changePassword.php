<?php
if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege(
        'success',
        [
            Session::get('notificationElement') ?? 'Thông tin tài khoản của bạn' => Session::get('notification')
        ]
    );
    Session::unset('notificationElement');
    Session::unset('notification');
}

$lblOldPassword      = Form::label('Mật khẩu cũ');
$lblPassword         = Form::label('Mật khẩu mới');
$lblConfirmPassword  = Form::label('Xác nhận lại mật khẩu mới');

$inputOldPassword     = Form::input('password', 'form[old_password]', $this->data['old_password'] ?? '');
$inputPassword        = Form::input('password', 'form[password]');
$inputConfirmPassword = Form::input('password', 'form[confirm_password]');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
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
                    <?= $this->errors ?? '' ?>
                    <?= $notification ?? '' ?>
                    <div class="dashboard">
                        <form action="" method="post" id="admin-form" class="theme-form">
                            <?= $inputToken ?>
                            <div class="form-group">
                                <?= $lblOldPassword . $inputOldPassword ?>
                            </div>
                            <div class="form-group">
                                <?= $lblPassword . $inputPassword ?>
                            </div>
                            <div class="form-group">
                                <?= $lblConfirmPassword . $inputConfirmPassword ?>
                            </div>
                            <button type="submit" id="submit" name="submit" value="Cập nhật thông tin" class="btn btn-solid btn-sm">Cập nhật thông tin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>