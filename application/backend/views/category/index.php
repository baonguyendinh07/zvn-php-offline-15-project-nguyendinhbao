<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$pathImg = FILES_URL . $this->params['controller'] . DS;
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink($formActionLink, '<i class="fas fa-plus"></i> Add New', 'info');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege(
        'success',
        [
            Session::get('notificationElement') ?? 'Category' => Session::get('notification')
        ]
    );
    Session::unset('notificationElement');
    Session::unset('notification');
}

$inputFilterStatus = '';
$inputSearchKey = '';

if (isset($this->params['filterStatus'])) $inputFilterStatus = Form::input('hidden', 'filterStatus', $this->params['filterStatus']);

if (isset($this->params['search-key']))   $inputSearchKey = Form::input('hidden', 'search-key', $this->params['search-key']);

$searchValue = $this->params['search-key'] ?? '';

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = Helper::highlight($searchValue, $value['id']);
        $name         = Helper::highlight($searchValue, $value['name']);
        $picture      = empty($value['picture']) ? $pathImg . 'default.jpg' : $pathImg . $value['picture'];

        $linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status']]);
        $showStatus   = Helper::showStatus($value['status'], $linkStatus);

        $dataUrlLink  = URL::createLink($this->params['module'], $this->params['controller'], 'changeGroupId', ['id' => $value['id']]);
        $dataUrl      = "data-url='$dataUrlLink'";

        $editLink     = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
        $btnEdit      = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);

        $pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id, 'picture' => $value['picture']]);
        $btnDelete    = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger btn-delete', true, true);

        $xhtml .= '<tr>
                        <td><input type="checkbox"></td>
                        <td>' . $id . '</td>
                        <td class="text-left"><p class="mb-0">' . $name . '</p></td>
                        <td class="position-relative"><img src="' . $picture . '" style="width:60px"></td>
                        <td class="position-relative">' . $showStatus . '</td>
                        <td>' . $value['ordering'] . '</td>
                        <td>
                            <p class="mb-0"><i class="far fa-user"></i>' . $value['created_by'] . '</p>
                            <p class="mb-0"><i class="far fa-clock"></i>' . $value['created'] . '</p>
                        </td>
                        <td>
                            <p class="mb-0"><i class="far fa-user"></i>' . $value['modified_by'] . '</p>
                            <p class="mb-0"><i class="far fa-clock"></i>' . $value['modified'] . '</p>
                        </td>
                        <td>' . $btnEdit . $btnDelete . '</td>
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
                        <div class="area-search mb-2">
                            <form action="" method="GET">
                                <?= Form::input('hidden', 'module', $this->params['module']) ?>
                                <?= Form::input('hidden', 'controller', $this->params['controller']) ?>
                                <?= Form::input('hidden', 'action', $this->params['action']) ?>
                                <?= $inputFilterStatus ?>
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
                                <select class="form-control custom-select">
                                    <option>Bulk Action</option>
                                    <option>Delete</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info">Apply</button>
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
                                <th><input type="checkbox"></th>
                                <th>ID</th>
                                <th class="text-left">Name</th>
                                <th>Picture</th>
                                <th style="width: 30px">Status</th>
                                <th style="width: 50px">Ordering</th>
                                <th style="width: 120px">Created</th>
                                <th style="width: 120px">Modified</th>
                                <th style="width: 50px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- content here -->
                            <?= $xhtml; ?>
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