<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$pathImg = FILES_URL . $this->params['controller'] . DS;
$formActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'form');
$btnAddNew = Helper::createButtonLink('slider-form', '<i class="fas fa-plus"></i> Add New', 'info');

if (!empty(Session::get('notificationElement')) || !empty(Session::get('notification'))) {
    $notification = Helper::showMessege(
        'success',
        [
            Session::get('notificationElement') ?? 'Slider' => Session::get('notification')
        ]
    );
    Session::unset('notificationElement');
    Session::unset('notification');
}

$inputFilterStatus = '';

if (isset($this->params['filterStatus'])) $inputFilterStatus = Form::input('hidden', 'filterStatus', $this->params['filterStatus']);

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = $value['id'];
        $name         = $value['name'];
        $description  = $value['description'];
        $link         = $value['link'];

        $picture      = empty($value['picture']) ? $pathImg . 'default.jpg' : $pathImg . $value['picture'];

        $linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status']]);
        $showStatus   = Helper::showStatus($value['status'], $linkStatus);

        $dataUrlOrdering  = 'data-url="' . URL::createLink($this->params['module'], $this->params['controller'], 'changeOrdering', ['id' => $value['id']]) . '"';
        $inputOrdering  = '<input type="number" value="' . $value['ordering'] . '" name="ordering" class="btn-ajax-ordering form-control" style="width:70px;text-align:center" ' . $dataUrlOrdering . '>';

        $editLink     = URL::createLink($this->params['module'], $this->params['controller'], 'form', ['id' => $value['id']]);
        $btnEdit      = Helper::createButtonLink($editLink, '<i class="fas fa-pen"></i>', 'info', true, true);

        $pathDelete   = URL::createLink($this->params['module'], $this->params['controller'], 'delete', ['id' => $id, 'picture' => $value['picture']]);
        $btnDelete    = Helper::createButtonLink($pathDelete, '<i class="fas fa-trash "></i>', 'danger btn-delete', true, true);

        $xhtml .=   '<tr>
                        <td>' . $id . '</td>
                        <td class="text-left">
                        <p class="mb-0"><b>Name</b>: ' . $name . '</p>
                        <p class="mb-0"><b>Description</b>: ' . $description . '</p>
                        <img src="' . $picture . '" style="width:350px">
                    </td>
                        <td class="position-relative">' . $showStatus . '</td>
                        <td class="position-relative">' . $inputOrdering . '</td>
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
                    <div style="float: right; margin-bottom:7px;margin-right:-8px"><?= $btnAddNew ?></div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle text-center table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 30px">ID</th>
                                <th class="text-left" style="width:350px">Info</th>
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
        </div>
    </div>
</div>