<?php
//// name* picture* price* saleoff status* special* caterogy* ordering* desciption
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');
if (!empty($this->data['id'])) $inputId = Form::input('hidden', 'form[id]', $this->data['id']);

$statusOptions = [
    'inactive' => 'Inactive',
    'active' => 'Active'
];

$specialOptions = [
    "0" => 'No',
    "1" => 'Yes'
];

$categoryOptionsDefault = ['default' => ' - Select Category - '] + $this->categoryOptions;

$lblName        = Form::label('Name', 'form-label fw-bold');
$lblPicture     = Form::label('Picture', 'form-label fw-bold', false);
$lblPrice       = Form::label('Price', 'form-label fw-bold');
$lblSaleOff     = Form::label('Sale Off', 'form-label fw-bold', false);
$lblStatus      = Form::label('Status', 'form-label fw-bold');
$lblSpecial     = Form::label('Special', 'form-label fw-bold', false);
$lblCategoryId  = Form::label('Category', 'form-label fw-bold');
$lblOrdering    = Form::label('Ordering', 'form-label fw-bold', false);
$lblShortDescription = Form::label('Short Description', 'form-label fw-bold', false);
$lblDescription = Form::label('Description', 'form-label fw-bold', false);

$inputName      = Form::input('text', 'form[name]', $this->data['name'] ?? '');
$inputPicture   = Form::input('file', 'picture', '', '', '', 'style="width:220px; border:none"');
$inputPrice     = Form::input('number', 'form[price]', $this->data['price'] ?? '');
$inputSaleOff   = Form::input('number', 'form[sale_off]', $this->data['sale_off'] ?? '');
$inputOrdering  = Form::input('number', 'form[ordering]', $this->data['ordering'] ?? 10);
$shortDescription       = $this->data['short_description'] ?? '';
$inputShortDescription  = '<textarea name="form[short_description]" class="form-control" rows="5">' . $shortDescription . '</textarea>';

$description       = $this->data['description'] ?? '';
$inputDescription  = '<textarea name="form[description]" class="form-control" id="editor" rows="5">' . $description . "</textarea>";

$statusSelect       = Form::select($statusOptions, 'form[status]', $this->data['status'] ?? 'active', 'custom-select');
$categoryIdSelect   = Form::select($categoryOptionsDefault, 'form[category_id]', $this->data['category_id'] ?? 'default', 'custom-select');
$specialSelect      = Form::select($specialOptions, 'form[special]', $this->data['special'] ?? 0, 'custom-select');

Session::set('token', time());
$inputToken = Form::input('hidden', 'form[token]', time());
?>
<div class="row">
    <div class="col-12">
        <?= $this->errors ?? '' ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?= $inputToken ?>
            <?= $inputId ?? '' ?>
            <div class="card card-outline card-info">
                <div class="card-body">
                    <div class="form-group">
                        <?= $lblName . $inputName ?>
                    </div>
                    <div class="form-group">
                        <?= $lblShortDescription . $inputShortDescription ?>
                    </div>
                    <div class="form-group">
                        <?= $lblDescription . $inputDescription ?>
                    </div>
                    <div class="form-group">
                        <?= $lblPrice . $inputPrice ?>
                    </div>
                    <div class="form-group">
                        <?= $lblSaleOff . $inputSaleOff ?>
                    </div>
                    <div class="form-group">
                        <?= $lblStatus . $statusSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblSpecial . $specialSelect ?>
                    </div>
                    <div class="form-group">
                        <?= $lblCategoryId . $categoryIdSelect ?>
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
                    <a href="book-index" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
