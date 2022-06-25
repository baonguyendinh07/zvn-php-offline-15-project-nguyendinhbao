<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
if (!empty($this->data['id'])) $inputId = Form::input('hidden', 'form[id]', $this->data['id']);

$statusOptions = [
    'default' => ' - Select Status - ',
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$lblName         = Form::label('Name', 'form-label fw-bold');
$lblStatus       = Form::label('Status', 'form-label fw-bold');
$lblOrdering     = Form::label('Ordering', 'form-label fw-bold');
$lblPicture      = Form::label('Picture', 'form-label fw-bold');

$inputName       = Form::input('text', 'form[name]', $this->data['name'] ?? '');
$statusSelect    = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'default');
$inputOrdering   = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? '');

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
                        <?= $lblName . $inputName ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblOrdering . $inputOrdering ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPicture . $inputName ?>
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