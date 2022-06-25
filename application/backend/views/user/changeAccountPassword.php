<?php
$profileActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'profile');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege(
        'success',
        'Thông báo',
        [
            Session::get('notificationElement') ?? 'Thông tin cá nhân của bạn' => Session::get('notification')
        ]
    );
    Session::unset('notificationElement');
    Session::unset('notification');
}

$changePasswordLink = URL::createLink($this->params['module'], $this->params['controller'], 'changeAccountPassword');
$lblOldPassword      = Form::label('Old Password', 'form-label fw-bold');
$lblPassword         = Form::label('Password', 'form-label fw-bold');
$lblConfirmPassword  = Form::label('Confirm Password', 'form-label fw-bold');

$inputOldPassword        = Form::input('password', 'form[old_password]', $this->data['old_password'] ?? '');
$inputPassword           = Form::input('password', 'form[password]');
$inputConfirmPassword    = Form::input('password', 'form[confirm_password]');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <?= $notification ?? '' ?>
        <form action="" method="post">
            <?= $inputToken ?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblOldPassword . $inputOldPassword ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPassword . $inputPassword ?>
                    </div>
                    <div class="form-group">
                        <?= $lblConfirmPassword . $inputConfirmPassword ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= $profileActionLink ?>" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>