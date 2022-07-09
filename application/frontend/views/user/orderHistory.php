<?php
include_once BLOCK_PATH . 'user.php';
$pathBookPicture = FILES_URL . 'book' . DS;

$xhtml = '';
if (!empty($this->items)) {
    foreach ($this->items as $key => $value) {
        $id           = $value['id'];
        $date         = $value['date'];

        $value['books']         = json_decode($value['books']);
        $value['prices']        = json_decode($value['prices']);
        $value['quantities']    = json_decode($value['quantities']);
        $value['names']         = json_decode($value['names']);
        $value['pictures']      = json_decode($value['pictures']);
        if (!empty($value['books'])) {
            $sum = 0;
            $details = '';
            foreach ($value['books'] as $key1 => $value1) {
                $picture    = !empty($value['pictures'][$key1]) ? $pathBookPicture . $value['pictures'][$key1] : $pathBookPicture . 'default.jpg';
                $bookLink   = URL::createLink('frontend', 'book', 'item', ['id' => $value1]);
                $bookName   = Helper::textCutting($value['names'][$key1], 30);
                $price      = $value['prices'][$key1];
                $quantities = $value['quantities'][$key1];
                $total      = $price * $quantities;
                $sum        += $total;
                $details     .= '
                <tbody>
                    <tr>
                        <td><a href="' . $bookLink . '"><img src="' . $picture . '" alt="' . $bookName . '" style="width:60px"></a></td>
                        <td style="min-width: 200px">' . $bookName . '</td>
                        <td style="min-width: 100px">' . number_format($price) . ' đ</td>
                        <td>' . $quantities . '</td>
                        <td style="min-width: 150px">' . number_format($total) . ' đ</td>
                    </tr>
                    <tr></tr>
                </tbody>';
            }
        }

        $xhtml .= '
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <button style="text-transform: none; width:250px" class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#' . $id . '">
                    Mã đơn hàng: ' . $id . '
                    </button>
                    &nbsp;&nbsp;Thời gian: ' . $date . '
                </h5>
            </div>
            <div id="' . $id . '" class="collapse" data-parent="#accordionExample" style="">
                <div class="card-body table-responsive">
                    <table class="table btn-table">
                        <thead>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Tên sách</td>
                                <td>Giá</td>
                                <td>Số lượng</td>
                                <td>Thành tiền</td>
                            </tr>
                        </thead>
                        ' . $details . '
                        <tfoot>
                            <tr class="my-text-primary font-weight-bold">
                                <td colspan="4" class="text-right">Tổng: </td>
                                <td>' . number_format($sum) . ' đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>';
    }
}
?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Lịch sử mua hàng</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="faq-section section-b-space">
    <div class="container">
        <div class="row">
            <?= $actionXHTML ?? '' ?>
            <div class="col-lg-9">
                <div class="accordion theme-accordion" id="accordionExample">
                    <div class="accordion theme-accordion" id="accordionExample">
                        <!-- content here -->
                        <?= $xhtml ?>
                        <!-- end content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>