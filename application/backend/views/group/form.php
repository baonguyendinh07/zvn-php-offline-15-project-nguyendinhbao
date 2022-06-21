<?php
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
$idUrl = '';
if (!empty($this->data['id'])) {
    $idUrl = '&id=' . $this->data['id'] ?? '';
    $inputId = Form::input('hidden', 'form[id]', $this->data['id'] ?? '');
}

$groupAcpOptions = [
    'default' => ' - Select Group ACP - ',
    "0" => 'Inactive',
    "1" => 'Active'
];

$statusOptions = [
    'default' => ' - Select Status - ',
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$lblName     = Form::label('Name', 'form-label fw-bold',);
$lblStatus   = Form::label('Status', 'form-label fw-bold',);
$lblGroupACP = Form::label('Group ACP', 'form-label fw-bold',);

$inputName       = Form::input('text', 'form[name]', $this->data['name'] ?? '');

$groupAcpSelect  = Form::select($groupAcpOptions, 'form[group_acp]', $this->data['group_acp'] ?? 'default');
$statusSelect    = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'default');
$inputOrdering   = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? '');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="<?= $formActionLink ?>" method="post">
            <?= $inputToken; ?>
            <?= $inputId ?? ''; ?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblName . $inputName ?>
                    </div>
                    <div class="form-group">
                        <?= $lblGroupACP . $groupAcpSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
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