<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], $this->params['action']);
$detailActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'detail');

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

$searchValue = $this->params['search-key'] ?? '';

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = Helper::highlight($searchValue, $value['id']);
        $name         = $value['name'];
        $username     = Helper::highlight($searchValue, $value['username']);
        $phoneNumber  = $value['phone_number'];
        $address      = $value['address'];
        $date         = $value['date'];

        $value['books']         = json_decode($value['books']);
        $value['prices']        = json_decode($value['prices']);
        $value['quantities']    = json_decode($value['quantities']);
        $value['names']         = json_decode($value['names']);
        if (!empty($value['books'])) {
            $sum = 0;
            $detail = '';
            foreach ($value['books'] as $key1 => $value1) {
                $bookName   = Helper::textCutting($value['names'][$key1], 30);
                $price      = $value['prices'][$key1];
                $quantities = $value['quantities'][$key1];
                $total      = $price * $quantities;
                $sum        += $total;
                $detail     .= sprintf('<p>- %s x <span class="badge badge-info badge-pill">%s</span> = %sđ</p>', $bookName, $quantities, number_format($total));
            }
        }

        $linkStatus   = URL::createLink($this->params['module'], $this->params['controller'], 'changeStatus', ['id' => $id, 'status' => $value['status']]);
        $showStatus   = Helper::showStatus($value['status'], $linkStatus);
        $viewDetail = '';

        $xhtml .= '<tr>
                        <td>' . $id . '</td>
                        <td class="text-left">
                            <p class="mb-0"><b>Name</b>: ' . $name . '</p>
                            <p class="mb-0"><b>Username</b>: ' . $username . '</p>
                            <p class="mb-0"><b>Sđt</b>: ' . $phoneNumber . '</p>
                            <p class="mb-0"><b>Địa chỉ</b>: ' . $address . '</p>
                        <td class="position-relative">' . $showStatus . '</td>
                        <td class="text-left">' . $detail . '</td>
                        <td class="position-relative">' . number_format($sum) . 'đ</td>
                        <td class="position-relative">' . $date . '</td>
                        <td class="text-center"><a href="' . $detailActionLink . '&id=' . $id . '"><i class="fas fa-eye"><i></a></td>
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
                                <?= $inputFilterStatus ?? '' ?>
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
                <div class="table-responsive">
                    <table class="table align-middle text-center table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 30px">ID</th>
                                <th class="text-left">Thông tin</th>
                                <th style="width: 45px">Trang thái</th>
                                <th>Chi tiết</th>
                                <th style="width: 45px">Tổng tiền</th>
                                <th style="width: 120px">Ngày đặt</th>
                                <th style="width: 30px"></th>
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