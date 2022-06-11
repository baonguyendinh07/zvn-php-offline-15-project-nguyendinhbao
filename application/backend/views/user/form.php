<?php
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
$idUrl = '';
if (!empty($this->data['id'])) {
    $idUrl = '&id=' . $this->data['id'] ?? '';
    $inputId = Form::input('hidden', 'form[id]', $this->data['id'] ?? '');
}

$groupOptions = [
    'default' => ' - Select Group ACP - ',
    "0" => 'Inactive',
    "1" => 'Active'
];

$statusOptions = [
    'default' => ' - Select Status - ',
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$lblUsername = Form::label('Username');
$lblPassword = Form::label('Password');
$lblEmail    = Form::label('Email');
$lblFullname = Form::label('Fullname');
$lblStatus   = Form::label('Status');
$lblGroup    = Form::label('Group');

$inputUsername   = Form::input('text', 'form[username]', '');
$inputPassword   = Form::input('password', 'form[password]', '');
$inputEmail      = Form::input('text', 'form[email]', $this->data['email'] ?? '');
$inputFullname   = Form::input('text', 'form[fullname]', $this->data['fullname'] ?? '');


//$groupSelect     = Form::select($groupOptions, 'form[group_acp]', $this->data['group_acp'] ?? 'default');
$statusSelect    = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'default');
//$inputOrdering   = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? '');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="<?= $formActionLink ?>" method="post">
            <?= $inputToken ?>
            <?= $inputId ?? '' ?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblUsername . $inputUsername ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPassword . $inputPassword ?>
                    </div>
                    <div class="form-group">
                        <?= $lblEmail . $inputEmail ?>
                    </div>
                    <div class="form-group">
                        <?= $lblFullname . $inputFullname ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblGroup ?>
                        <select class="custom-select">
                            <option selected> - Select Group - </option>
                            <option>Admin</option>
                            <option>Manager</option>
                            <option>Member</option>
                            <option>Register</option>
                        </select>
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