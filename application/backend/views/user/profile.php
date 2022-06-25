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
$lblUsername       = Form::label('Username', 'form-label fw-bold', false);
$lblPassword       = Form::label("<a href='$changePasswordLink'>Change Password</a>", 'form-label fw-bold', false);
$lblEmail          = Form::label('Email', 'form-label fw-bold', false);
$lblFullname       = Form::label('Fullname', 'form-label fw-bold');
$lblBirthday       = Form::label('Birthday', 'form-label fw-bold', false);
$lblPhonenumber    = Form::label('Phone Number', 'form-label fw-bold', false);
$lblAddress        = Form::label('Address', 'form-label fw-bold', false);

$inputFullname      = Form::input('text', 'form[fullname]', $this->data['fullname'] ?? '');
$inputBirthday      = Form::input('date', 'form[birthday]', $this->data['birthday'] ?? '');
$inputPhonenumber   = Form::input('number', 'form[phone_number]', $this->data['phone_number'] ?? '');
$inputAddress       = Form::input('text', 'form[address]', $this->data['address'] ?? '');

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
                        <?= $lblUsername . $this->inputUsername ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPassword ?>
                        <p class="form-control btn-blue">************</p>
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
                        <?= $lblPhonenumber . $inputPhonenumber ?>
                    </div>
                    <div class="form-group">
                        <?= $lblAddress . $inputAddress ?>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?= $indexActionLink ?>" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>