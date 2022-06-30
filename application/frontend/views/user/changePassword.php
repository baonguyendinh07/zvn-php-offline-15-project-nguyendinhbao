<?php
include_once BLOCK_PATH . 'user.php';

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

$lblOldPassword      = Form::label('Mật khẩu hiện tại');
$lblPassword         = Form::label('Mật khẩu mới');
$lblConfirmPassword  = Form::label('Xác nhận lại mật khẩu mới');

$inputOldPassword     = Form::input('password', 'form[old_password]', $this->data['old_password'] ?? '');
$inputPassword        = Form::input('password', 'form[password]');
$inputConfirmPassword = Form::input('password', 'form[confirm_password]');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<section class="faq-section section-b-space">
    <div class="container">
        <div class="row">
            <?= $actionXHTML ?? '' ?>
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