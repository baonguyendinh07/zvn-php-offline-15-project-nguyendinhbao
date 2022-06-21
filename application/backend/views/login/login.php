<?php
$inputUsername   = Form::input('text', 'form[username]', $this->username ?? '', '', 'Username or Email');
$inputPassword   = Form::input('password', 'form[password]', $this->password ?? '', '', 'Password');
Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="../../index2.html" class="h1"><b>Admin</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?= $this->errors ?? '' ?>
            <form action="" method="post">
                <?= $inputToken ?>
                <div class="input-group mb-3">
                    <?= $inputUsername ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= $inputPassword ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>