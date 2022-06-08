<?php
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
$idUrl = '';
if (!empty($this->data['id'])) {
    $idUrl = '&id=' . $this->data['id'] ?? '';
    $inputId = Form::input('hidden', 'form[id]', $this->data['id'] ?? '');
}

if (!empty(Session::get('notification'))) {
    $notification = Form::showMessege('success', 'Thông báo', ['Thông tin thành viên' => Session::get('notification')]);
    Session::unset('notification');
} elseif (!empty($this->errors)) {
    $notification = Form::showMessege('danger', 'Lỗi', $this->errors);
}

$groupAcpOptions = [
    '2' => ' - Select Group ACP - ',
    "0" => 'Inactive',
    "1" => 'Active'
];

$statusOptions = [
    'default' => ' - Select Status - ',
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$inputLink       = Form::input('text', 'form[name]', $this->data['name'] ?? '');

$groupAcpSelect  = Form::select($groupAcpOptions, 'form[group_acp]', $this->data['group_acp'] ?? 2);
$statusSelect  = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'default');
$inputOrdering   = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? '');

$token = Helper::randomString(10);
Session::set('token', $token);
$inputToken = Form::input('hidden', 'form[token]', $token);
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?= $notification ?? '' ?>
                <form action="<?= $formActionLink ?>" method="post">
                    <?= $inputToken; ?>
                    <?= $inputId ?? ''; ?>
                    <div class="card card-outline card-info">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <?= $inputLink ?>
                            </div>
                            <div class="form-group">
                                <label>Group ACP <span class="text-danger">*</span></label>
                                <?= $groupAcpSelect ?>
                            </div>
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <?= $statusSelect ?>
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
    </div>
</div>