<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$pathImg = FILES_URL . $this->params['controller'] . DS;
$multiDeleteURL = URL::createLink($this->params['module'], $this->params['controller'], 'multiDelete');
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink($formActionLink, '<i class="fas fa-plus"></i> Add New', 'info');

$categoryOptionsDefault = ['default' => ' - Select Category - '] + $this->categoryOptions;
$categorySelectDefault  = Form::select($categoryOptionsDefault, 'category_id', $this->params['category_id'] ?? 'default', 'custom-select filter-element');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege(
        'success',
        [
            Session::get('notificationElement') ?? 'Thông tin sách' => Session::get('notification')
        ]
    );
    Session::unset('notificationElement');
    Session::unset('notification');
}

if (isset($this->params['filterStatus'])) $inputFilterStatus = Form::input('hidden', 'filterStatus', $this->params['filterStatus']);

if (isset($this->params['search-key']))   $inputSearchKey = Form::input('hidden', 'search-key', $this->params['search-key']);

if (isset($this->params['category_id']))  $inputCategoryId = Form::input('hidden', 'category_id', $this->params['category_id']);

if (isset($this->params['special']))      $inputSpecial = Form::input('hidden', 'special', $this->params['special']);

$specialOptions = [
    'default' => ' - Select Special - ',
    "0" => 'No',
    "1" => 'Yes'
];

$specialSelect  = Form::select($specialOptions, 'special', $this->params['special'] ?? '', 'custom-select filter-element');

$searchValue = $this->params['search-key'] ?? '';

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = Helper::highlight($searchValue, $value['id']);
        $name         = Helper::highlight($searchValue, $value['name']);
        $saleOff      = $value['sale_off'] > 0 ? "<p class='mb-0'><b>Sale Off</b>: {$value['sale_off']}%</p>" : '';

        $picture      = empty($value['picture']) ? $pathImg . 'default.jpg' : $pathImg . $value['picture'];

        $linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status']]);
        $showStatus   = Helper::showStatus($value['status'], $linkStatus);

        $linkSpecial = URL::createLink($this->params['module'], $this->params['controller'], 'changeSpecial', ['id' => $id, 'special' => $value['special']]);
        $showSpecial = Helper::showStatus($value['special'], $linkSpecial, 'special');

        $dataUrlCategory  = 'data-url="' . URL::createLink($this->params['module'], $this->params['controller'], 'changeCategoryId', ['id' => $value['id']]) . '"';
        $categorySelect  = Form::select($this->categoryOptions, '', $value['category_id'] ?? '', 'custom-select btn-ajax-category-id', $dataUrlCategory);

        $dataUrlOrdering  = 'data-url="' . URL::createLink($this->params['module'], $this->params['controller'], 'changeOrdering', ['id' => $value['id']]) . '"';
        $inputOrdering  = '<input type="number" value="' . $value['ordering'] . '" name="ordering" class="btn-ajax-ordering form-control" style="width:70px;text-align:center" ' . $dataUrlOrdering . '>';

        $editLink     = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
        $btnEdit      = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);

        $pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id, 'picture' => $value['picture']]);
        $btnDelete    = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger btn-delete', true, true);

        $xhtml .= '<tr>
                        <td><input type="checkbox" name="form[id][]" value="'.$id.'"></td>
                        <td>' . $id . '</td>
                        <td class="text-left">
                            <p class="mb-0"><b>Name</b>: ' . $name . '</p>
                            <p class="mb-0"><b>Price</b>: ' . number_format($value['price']) . 'đ</p>'
            . $saleOff .
            '</td>
                        <td><img src="' . $picture . '" style="width:70px"></td>
                        <td class="position-relative">' . $categorySelect . '</td>
                        <td class="position-relative">' . $showStatus . '</td>
                        <td class="position-relative">' . $showSpecial . '</td>
                        <td class="position-relative">' . $inputOrdering . '</td>
                        <td>' . $btnEdit . $btnDelete . '
                        </td>
                    </tr>';
    }
}
?>
<div class="row">
    <div class="col-12">
        <!-- Search & Filter -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Search & Filter</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">
                        <div class="area-filter-status mb-2">
                            <?= Helper::areaFilterStatus($this->arrCountItems, $this->params) ?>
                        </div>
                        <div class="area-filter-attribute mb-2">
                            <form action="" method="GET" id="filter-form">
                                <?= Form::input('hidden', 'module', $this->params['module']) ?>
                                <?= Form::input('hidden', 'controller', $this->params['controller']) ?>
                                <?= Form::input('hidden', 'action', $this->params['action']) ?>
                                <?= $inputFilterStatus ?? '' ?>
                                <?= $inputSearchKey ?? '' ?>
                                <?= $categorySelectDefault ?>
                                <?= $specialSelect ?>
                            </form>
                        </div>
                        <div class="area-search mb-2">
                            <form action="" method="GET">
                                <?= Form::input('hidden', 'module', $this->params['module']) ?>
                                <?= Form::input('hidden', 'controller', $this->params['controller']) ?>
                                <?= Form::input('hidden', 'action', $this->params['action']) ?>
                                <?= $inputFilterStatus ?? '' ?>
                                <?= $inputCategoryId ?? '' ?>
                                <?= $inputSpecial ?? '' ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search-key" value="<?= $searchValue ?>">
                                    <span class="input-group-append">
                                        <button type="submit" class="btn btn-info">Search</button>
                                        <a href="<?= $indexActionLink ?>" class="btn btn-danger">Clear</a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- List -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">List</h3>

                <div class="card-tools">
                    <a href="<?= $indexActionLink ?>" class="btn btn-tool" data-card-widget="refresh">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <?= $notification ?? '' ?>
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-between mb-2">
                        <div>
                            <div class="input-group">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-danger" id="submit-main-form">Multi Delete</button>
                                </span>
                            </div>
                        </div>
                        <div><?= $btnAddNew ?></div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-center table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 30px"><input type="checkbox" id="checkAll"></th>
                                <th style="width: 30px">ID</th>
                                <th class="text-left">Info</th>
                                <th>Picture</th>
                                <th style="width: 150px">Category</th>
                                <th style="width: 45px">Status</th>
                                <th style="width: 45px">Special</th>
                                <th style="width: 45px">Ordering</th>
                                <th style="width: 50px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- content here -->
                            <form action="<?= $multiDeleteURL ?>" method="post" name="main-form" id="main-form"><?= $xhtml; ?></form>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer clearfix">
                <?= $this->pagination->showPagination() ?? '' ?>
            </div>
        </div>
    </div>
</div>