<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink($formActionLink, '<i class="fas fa-plus"></i> Add New', 'info');

$groupOptionsDefault = ['default' => ' - Select Group - '] + $this->groupOptions;
$groupSelectDefault  = Form::select($groupOptionsDefault, 'group_id', $this->params['group_id'] ?? 'default', 'filter-element');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege('success', 'Thông báo', [Session::get('notificationElement') ?? 'Thông tin thành viên' => Session::get('notification')]);
    Session::unset('notificationElement');
    Session::unset('notification');
}

$inputFilterStatus = '';
$inputSearchKey = '';
$inputGroupId = '';

if (isset($this->params['filterStatus'])) $inputFilterStatus = Form::input('hidden', 'filterStatus', $this->params['filterStatus']);

if (isset($this->params['search-key']))   $inputSearchKey = Form::input('hidden', 'search-key', $this->params['search-key']);

if (isset($this->params['group_id']))     $inputGroupId = Form::input('hidden', 'group_id', $this->params['group_id']);

$searchValue = $this->params['search-key'] ?? '';

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = Helper::highlight($searchValue, $value['id']);
        $userName     = Helper::highlight($searchValue, $value['username']);
        $fullName     = Helper::highlight($searchValue, $value['fullname']);
        $email        = Helper::highlight($searchValue, $value['email']);

        $linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status']]);
        $showStatus   = Helper::showStatus($value['status'], $linkStatus);

        $dataUrlLink  = URL::createLink($this->params['module'], $this->params['controller'], 'changeGroupId', ['id' => $value['id']]);
        $dataUrl      = "data-url='$dataUrlLink'";
        $groupSelect  = Form::select($this->groupOptions, '', $value['group_id'] ?? '', 'btn-ajax-group-id', $dataUrl);

        $resetPasswordLink     = URL::createLink($this->params['module'], $this->params['controller'], 'changePassword', ['id' => $value['id']]);
        $btnResetPassword      = Helper::createButtonLink($resetPasswordLink, '<i class="fas fa-key "></i>', 'secondary', true, true);

        $editLink     = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
        $btnEdit      = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);

        $pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id]);
        $btnDelete    = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger', true, true);

        $xhtml .= '<tr>
                        <td><input type="checkbox"></td>
                        <td>' . $id . '</td>
                        <td class="text-left">
                            <p class="mb-0">Username: ' . $userName . '</p>
                            <p class="mb-0">FullName: ' . $fullName . '</p>
                            <p class="mb-0">Email: ' . $email . '</p>
                        </td>
                        <td>' . $groupSelect . '</td>
                        <td>' . $showStatus . '</td>
                        <td>
                            <p class="mb-0"><i class="far fa-user"></i>' . $value['created_by'] . '</p>
                            <p class="mb-0"><i class="far fa-clock"></i>' . $value['created'] . '</p>
                        </td>
                        <td>
                            <p class="mb-0"><i class="far fa-user"></i>' . $value['modified_by'] . '</p>
                            <p class="mb-0"><i class="far fa-clock"></i>' . $value['modified'] . '</p>
                        </td>
                        <td>' . $btnResetPassword . $btnEdit . $btnDelete . '
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
                                <?= $inputFilterStatus ?>
                                <?= $inputSearchKey ?>
                                <?= $groupSelectDefault ?>
                            </form>
                        </div>
                        <div class="area-search mb-2">
                            <form action="" method="GET">
                                <?= Form::input('hidden', 'module', $this->params['module']) ?>
                                <?= Form::input('hidden', 'controller', $this->params['controller']) ?>
                                <?= Form::input('hidden', 'action', $this->params['action']) ?>
                                <?= $inputFilterStatus ?>
                                <?= $inputGroupId ?>
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
                                <th class="text-left">Info</th>
                                <th>Group</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Modified</th>
                                <th>Action</th>
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