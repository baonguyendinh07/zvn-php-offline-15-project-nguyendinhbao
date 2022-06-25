<?php
$lblUsername     = Form::label('Tên đăng nhập hoặc email', 'required', false);
$lblPassword     = Form::label('Mật khẩu', 'required', false);

$inputUsername   = Form::input('text', 'form[username]', $this->username ?? '');
$inputPassword   = Form::input('password', 'form[password]', $this->password ?? '');
Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">
                        Đăng nhập </h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="login-page section-b-space">

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3>Đăng nhập</h3>
                <div class="theme-card">
                    <?= $this->errors ?? '' ?>
                    <form action="" method="post" id="admin-form" class="theme-form">
                        <?= $inputToken ?>
                        <div class="form-group">
                            <?= $lblUsername ?>
                            <?= $inputUsername ?>
                        </div>
                        <div class="form-group">
                            <?= $lblPassword ?>
                            <?= $inputPassword ?>
                        </div>
                        <button type="submit" id="submit" name="submit" value="Đăng nhập" class="btn btn-solid">Đăng nhập</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 right-login">
                <h3>Khách hàng mới</h3>
                <div class="theme-card authentication-right">
                    <h6 class="title-font">Đăng ký tài khoản</h6>
                    <p>Sign up for a free account at our store. Registration is quick and easy. It allows you to be
                        able to order from our shop. To start shopping click register.</p>
                    <a href="index.php?module=frontend&controller=user&action=register" class="btn btn-solid">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
</section>