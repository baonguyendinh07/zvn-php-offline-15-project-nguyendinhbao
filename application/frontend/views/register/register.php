<?php
if (!empty($this->data['id'])) $inputId = Form::input('hidden', 'form[id]', $this->data['id']);

$lblUsername = Form::label('Tên tài khoản', 'required', false);
$lblFullname = Form::label('Họ và tên', 'required', false);
$lblEmail    = Form::label('Email', 'required', false);
$lblPassword = Form::label('Mật khẩu', 'required', false);

$inputUsername   = Form::input('text', 'form[username]', $this->username);
$inputFullname   = Form::input('text', 'form[fullname]', $this->fullname);
$inputEmail      = Form::input('text', 'form[email]', $this->email);
$inputPassword   = Form::input('password', 'form[password]', $this->password);

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>

<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Đăng ký tài khoản</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="register-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Đăng ký tài khoản</h3>
                <div class="theme-card">
                    <?= $this->errors ?? '' ?>
                    <form action="" method="post" id="admin-form" class="theme-form">
                        <?= $inputToken ?>
                        <div class="form-row">
                            <div class="col-md-6">
                                <?= $lblUsername ?>
                                <?= $inputUsername ?>
                            </div>
                            <div class="col-md-6">
                                <?= $lblFullname ?>
                                <?= $inputFullname ?>
                            </div>
                            <div class="col-md-6">
                                <?= $lblEmail ?>
                                <?= $inputEmail ?>
                            </div>
                            <div class="col-md-6">
                                <?= $lblPassword ?>
                                <?= $inputPassword ?>
                            </div>
                        </div>
                        <button type="submit" id="submit" name="submit" value="Tạo tài khoản" class="btn btn-solid">Tạo tài khoản</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>