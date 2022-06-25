<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
if (!empty($this->data['id'])) $inputIdHidden = Form::input('hidden', 'form[id]', $this->data['id'] ?? '');


$lblId       = Form::label('Id', 'form-label fw-bold', false);
$lblUsername = Form::label('Username', 'form-label fw-bold', false);
$lblEmail    = Form::label('Email', 'form-label fw-bold', false);
$lblFullname = Form::label('Fullname', 'form-label fw-bold', false);
$lblPassword = Form::label('Password', 'form-label fw-bold', false);

$idXhtml         = '<p class="form-control btn-blue">' . $this->data['id'] . '</p>';
$usernameXhtml   = '<p class="form-control btn-blue">' . $this->data['username'] . '</p>';
$emailXhtml      = '<p class="form-control btn-blue">' . $this->data['email'] . '</p>';
$fullnameXhtml   = '<p class="form-control btn-blue">' . $this->data['fullname'] . '</p>';

$randomPassword  = Helper::randomString(12);
$inputPassword   = Form::input('text', 'form[password]', $randomPassword, 'random-password');

$randomPasswordLink = URL::createLink($this->params['module'], $this->params['controller'], 'randomPassword');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="" method="post">
            <?= $inputToken ?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblId . $idXhtml . $inputIdHidden ?>
                    </div>
                    <div class="form-group">
                        <?= $lblUsername . $usernameXhtml ?>
                    </div>
                    <div class="form-group">
                        <?= $lblEmail . $emailXhtml ?>
                    </div>
                    <div class="form-group">
                        <?= $lblFullname . $fullnameXhtml ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPassword ?>
                        <div style="width:100%">
                            <button type="button" class="btn btn-info btn-ajax-pw" value="<?= $randomPasswordLink ?>"><i class="fas fa-sync-alt"></i> Generate</button>
                            <div style="width:89%;float:right"><?= $inputPassword ?></div>
                        </div>
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