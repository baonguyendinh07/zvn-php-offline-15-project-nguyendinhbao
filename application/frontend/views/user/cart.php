<?php
$pathBookPicture = FILES_URL . 'book' . DS;

Session::set('token', time());
$inputToken = Form::input('hidden', 'token', time());

$listCartXhtml = '';
$totalBill = 0;
foreach ($this->listCart as $value) {
    $id             = $value['id'];
    $name           = $value['name'];
    $picture        = $pathBookPicture . $value['picture'];
    $price          = $value['price'] * (1 - $value['sale_off'] / 100);
    $quantities     = Session::get('cart')[$id];
    $total          = $price * $quantities;
    $totalBill      += $total;

    $itemURL        = URL::createLink($this->_arrParam['module'], 'book', 'item', ['id' => $id]);
    $tempCartURL    = URL::createLink($this->_arrParam['module'], 'user', 'tempCart', ['id' => $id]);
    $deleteItemURL  = URL::createLink($this->_arrParam['module'], 'user', 'deleteItemCart', ['id' => $id]);

    $listCartXhtml .= '
        <tr>
            <td>
                <a href="' . $itemURL . '"><img src="' . $picture . '" alt="' . $name . '"></a>
            </td>
            <td>
                <a href="' . $itemURL . '">' . $name . '</a>
            </td>
            <td>
                <h2 class="text-lowercase">' . number_format($price) . ' đ</h2>
            </td>
            <td>
                <div class="qty-box">
                    <div class="input-group">
                        <input type="number" value="' . $quantities . '" class="form-control input-number change-quantities" min="1" data-url="' . $tempCartURL . '"  data-quantities-saved="' . $quantities . '">
                    </div>
                </div>
            </td>
            <td><a href="' . $deleteItemURL . '" class="icon delete-item-cart"><i class="ti-close"></i></a></td>
            <td>
                <h2 class="td-color text-lowercase">' . number_format($total) . ' đ</h2>
            </td>
        </tr>
            <input type="hidden" name="form[' . $id . ']" value="' . $quantities . '">';
}

if (empty($this->listCart)) {
    $xhtml = '
        <section class="cart-section section-b-space">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <i class="fa fa-cart-plus fa-5x my-text-primary"></i>
                        <h5 class="my-3">Không có sản phẩm nào trong giỏ hàng của bạn</h5>
                        <a href="index.html" class="btn btn-solid">Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </section>';
} else {
    $xhtml = '
        <form action="" method="POST" name="admin-form" id="admin-form">
            <section class="cart-section section-b-space">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table cart-table table-responsive-xs">
                                <thead>
                                    <tr class="table-head">
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Tên sách</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số Lượng</th>
                                        <th scope="col"></th>
                                        <th scope="col">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>' . $listCartXhtml . '</tbody>
                            </table>
                            <table class="table cart-table table-responsive-md">
                                <tfoot>
                                    <tr>
                                        <td>Tổng :</td>
                                        <td>
                                            <h2 class="text-lowercase">' . number_format($totalBill) . ' đ</h2>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row cart-buttons">
                        <div class="col-6"><a href="index.html" class="btn btn-solid">Tiếp tục mua sắm</a></div>
                        '.$inputToken.'
                        <div class="col-6"><button type="submit" class="btn btn-solid">Đặt hàng</button></div>
                    </div>
                </div>
            </section>
        </form>';
}
?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Giỏ hàng</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $xhtml ?>