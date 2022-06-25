<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');

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
$changePasswordLink = URL::createLink($this->params['module'], $this->params['controller'], 'changeAccountPassword');
$lblUsername       = Form::label('Username', '', false);
$lblEmail          = Form::label('Email', '', false);
$lblFullname       = Form::label('Họ và tên');
$lblBirthday       = Form::label('Ngày sinh', '', false);
$lblPhoneNumber    = Form::label('Số điện thoại', '', false);
$lblAddress        = Form::label('Địa chỉ', '', false);

$inputFullname     = Form::input('text', 'form[fullname]', $this->data['fullname'] ?? '');
$inputBirthday     = Form::input('date', 'form[birthday]', $this->data['birthday'] ?? '');
$inputPhoneNumber  = Form::input('number', 'form[phone_number]', $this->data['phone_number'] ?? '');
$inputAddress      = Form::input('text', 'form[address]', $this->data['address'] ?? '');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
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
                    <?= $this->errors ?? '' ?>
                    <?= $notification ?? '' ?>
                    <div class="dashboard">
                        <form action="" method="post" id="admin-form" class="theme-form">
                            <?= $inputToken ?>
                            <div class="form-group">
                                <?= $lblUsername . $this->inputUsername ?>
                            </div>
                            <div class="form-group">
                                <?= $lblEmail . $this->inputEmail ?>
                            </div>
                            <div class="form-group">
                                <?= $lblFullname . $inputFullname ?>
                            </div>
                            <div class="form-group">
                                <?= $lblBirthday . $inputBirthday ?>
                            </div>
                            <div class="form-group">
                                <?= $lblPhoneNumber . $inputPhoneNumber ?>
                            </div>
                            <div class="form-group">
                                <?= $lblAddress . $inputAddress ?>
                            </div>
                            <button type="submit" name="submit" value="Cập nhật thông tin" class="btn btn-solid btn-sm">Cập nhật thông tin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>