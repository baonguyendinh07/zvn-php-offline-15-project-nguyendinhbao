<?php
$indexActionLink = URL::createLink($this->params['module'], $this->params['controller'], 'index');

$cartInfoXHTML = '<h4 class="card-title">
                    <span><b>Mã đơn hàng:</b> ' . $this->data['id'] . '</span> ||
                    <span><b>Ngày dặt:</b> ' . $this->data['date'] . '</span> ||
                    <span><b>Khách hàng:</b> ' . $this->data['name'] . '</span> ||
                    <span><b>Trạng thái:</b> ' . ucfirst($this->data['status']) . '</span>
                </h4>
                ';

$this->data['books']         = json_decode($this->data['books']);
$this->data['prices']        = json_decode($this->data['prices']);
$this->data['quantities']    = json_decode($this->data['quantities']);
$this->data['names']         = json_decode($this->data['names']);
$this->data['pictures']      = json_decode($this->data['pictures']);
if (!empty($this->data['books'])) {
    $xhtml = '';
    $pathImg = FILES_URL . 'book' . DS;
    foreach ($this->data['books'] as $key => $value) {
        $picture    = $this->data['pictures'][$key];
        $bookName   = $this->data['names'][$key];
        $price      = $this->data['prices'][$key];
        $quantities = $this->data['quantities'][$key];
        $total      = $price * $quantities;

        $xhtml .= '<tr>
        <td><img src="' . $pathImg . $picture . '" style="width:70px"></p>
        <td class="text-left">' . $bookName . '</p>
        <td>' . number_format($price) . 'đ</td>
        <td>' . $quantities . '</td>
        <td>' . number_format($total) . 'đ</td>
    </tr>';
    }
}

?>
<div class="row">
    <div class="col-12">
        <!-- List -->
        <div class="card card-outline card-info">
            <div class="card-header">
                <?= $cartInfoXHTML ?>
                <div class="card-tools">
                    <a href="<?= $indexActionLink ?>" class="btn btn-info"><i class="fas fa-arrow-circle-left"></i> Quay về</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle text-center table-bordered">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th class="text-left">Tên sách</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
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