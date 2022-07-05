<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
if (!empty($this->data['id'])) $inputId = Form::input('hidden', 'form[id]', $this->data['id']);

$statusOptions = [
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$lblUsername = Form::label('Username', 'form-label fw-bold', false);
$lblEmail    = Form::label('Email', 'form-label fw-bold', false);
$lblFullname = Form::label('Fullname', 'form-label fw-bold');
$lblStatus   = Form::label('Status', 'form-label fw-bold');
$lblGroup    = Form::label('Group', 'form-label fw-bold');

$inputFullname   = Form::input('text', 'form[fullname]', $this->data['fullname'] ?? '');
$statusSelect    = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'active', 'custom-select');
$groupIdSelect   = Form::select($this->groupOptions, 'form[group_id]', $this->data['group_id'] ?? '4', 'custom-select');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="" method="post">
            <?= $inputToken ?>
            <?= $inputId ?? ''?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblUsername . $this->inputUsername ?>
                    </div>
                    <div class="form-group">
                        <?= $lblEmail . $this->inputEmail ?>
                    </div>
                    <div class="form-group">
                        <?= $this->lblPassword . $this->inputPassword ?>
                    </div>
                    <div class="form-group">
                        <?= $lblFullname . $inputFullname ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblGroup . $groupIdSelect ?>
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