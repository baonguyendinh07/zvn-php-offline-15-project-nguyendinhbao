<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
if (!empty($this->data['id'])) $inputId = Form::input('hidden', 'form[id]', $this->data['id']);

$statusOptions = [
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$lblName         = Form::label('Name', 'form-label fw-bold');
$lblDescription  = Form::label('Description', 'form-label fw-bold', false);
$lblLink         = Form::label('Link', 'form-label fw-bold', false);
$lblStatus       = Form::label('Status', 'form-label fw-bold');
$lblOrdering     = Form::label('Ordering', 'form-label fw-bold', false);
$lblPicture      = Form::label('Picture', 'form-label fw-bold', false);

$inputName          = Form::input('text', 'form[name]', $this->data['name'] ?? '');
$inputDescription   = Form::input('text', 'form[description]', $this->data['description'] ?? '');
$inputLink          = Form::input('text', 'form[link]', $this->data['link'] ?? '');
$statusSelect       = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'inactive', 'custom-select');
$inputOrdering   = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? 10);
$inputPicture    = Form::input('file', 'picture', '', '', '', 'style="width:220px; border:none"');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?= $inputToken ?>
            <?= $inputId ?? ''?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblName . $inputName ?>
                    </div>
                    <div class="form-group">
                        <?= $lblDescription . $inputDescription ?>
                    </div>
                    <div class="form-group">
                        <?= $lblLink . $inputLink ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblOrdering . $inputOrdering ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPicture . $inputPicture . $this->pictureXHTML ?>
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