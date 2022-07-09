<div class="breadcrumb-section" style="margin-top: 107px;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">
                        Đặt hàng thành công
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="success-text"><i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>Cảm ơn bạn đã mua hàng</h2>
                    <p>Hàng hóa sẽ được chuyển đến bạn trong thời gian sớm nhất</p>
                    <p>Mã đơn hàng: <b><?= Session::get('order'); Session::unset('order'); ?></b></p>
                </div>
            </div>
        </div>
    </div>
</section>