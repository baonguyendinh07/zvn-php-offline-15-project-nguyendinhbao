<?php
include_once BLOCK_PATH . 'category.php';

$pathBookPicture = FILES_URL . 'book' . DS;

$itemURL = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'item');

$xhtmlSpecialBooks = '';
if (!empty($this->listSpecialBooks)) {
    foreach ($this->listSpecialBooks as $key => $value) {
        $id       = $value['id'];
        $name     = Helper::textCutting($value['name'], 35);
        $picture  = !empty($value['picture']) ? $pathBookPicture . $value['picture'] : $pathBookPicture . 'default.jpg';
        $saleOffXhtml = '';
        $price    = number_format($value['price']) . 'đ';
        if ($value['sale_off'] > 0) {
            $price = number_format($value['price'] * (100 - $value['sale_off']) / 100) .'đ';
        }

        if ($key % 5 == 0) $xhtmlSpecialBooks .= '<div>';
        $xhtmlSpecialBooks .= '
                <div class="media">
                    <a href="' . $itemURL . '&id=' . $id . '">
                        <img class="img-fluid blur-up lazyload" src="' . $picture . '" alt="' . $name . '" style="width: 110px">
                    </a>
                    <div class="media-body align-self-center">
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <a href="' . $itemURL . '&id=' . $id . '" title="' . $name . '" style="display:block;height:70px">
                            <h6>' . $name . '</h6>
                        </a>
                        <h4 class="text-lowercase">' . $price . '</h4>
                    </div>
                </div>';
        if ($key % 5 == 4) {
            $xhtmlSpecialBooks .= '</div>';
        }
    }
    if ($key % 5 != 4 && $key % 5 != 0) {
        $xhtmlSpecialBooks .= "</div>";
    }
}

$xhtmlTypeBooks = '';
if (!empty($this->listTypeBooks)) {
    $quickViewURL = URL::createLink($this->_arrParam['module'], 'book', 'quickView');
    foreach ($this->listTypeBooks as $key => $value) {
        $index      = $key;
        $id         = $value['id'];
        $name     = Helper::textCutting($value['name'], 55);
        $picture    = !empty($value['picture']) ? $pathBookPicture . $value['picture'] : $pathBookPicture . 'default.jpg';
        $saleOffXhtml = '';
        if ($value['sale_off'] > 0) {
            $saleOffXhtml = '
			<div class="lable-block">
				<span class="lable4 badge badge-danger"> -' . $value['sale_off'] . '%</span>
			</div>';
        }
        if ($value['sale_off'] > 0) {
            $price     = number_format($value['price'] * (100 - $value['sale_off']) / 100) . 'đ <del>' . number_format($value['price']) . 'đ</del>';
        } else {
            $price    = number_format($value['price']) . 'đ';
        }

        $xhtmlTypeBooks .= '
        <div class="col-xl-3 col-6 col-grid-box">
            <div class="product-box" style="height:380px">
                <div class="img-wrapper">
                    <div class="lable-block">
                        ' . $saleOffXhtml . '
                    </div>
                    <div class="front">
                        <a href="' . $itemURL . '&id=' . $id . '">
                            <img src="' . $picture . '" class="img-fluid blur-up lazyload bg-img" alt="">
                        </a>
                    </div>
                    <div class="cart-info cart-wrap">
                        <a href="#" title="Add to cart"><i class="ti-shopping-cart"></i></a>
                        <a href="' . $quickViewURL . '&id=' . $id . '" title="Quick View" class="btn-ajax-quick-view"><i class="ti-search" data-toggle="modal" data-target="#quick-view"></i></a>
                    </div>
                </div>
                <div class="product-detail">
                    <div class="rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <a href="' . $itemURL . '&id=' . $id . '" title="' . $name . '" style="display:block; height:75px">
                        <h6>' . $name . '</h6>
                    </a>
                    <p>' . $value['short_description'] . '</p>
                    <h4 class="text-lowercase">' . $price . '</h4>
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
                    <h2 class="py-2"><?= $pageTitle ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="section-b-space j-box ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <!-- side-bar colleps block stat -->
                    <div class="collection-filter-block">
                        <!-- brand filter start -->
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                        <div class="collection-collapse-block open">
                            <h3 class="collapse-block-title">Danh mục</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    <?= $sidebarCategory ?>
                                    <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 text-center">
                                        <span class="text-dark font-weight-bold" id="btn-view-more">Xem thêm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="theme-card">
                        <h5 class="title-border">Sách nổi bật</h5>
                        <div class="offer-slider slide-1">
                            <?= $xhtmlSpecialBooks ?>
                        </div>
                    </div>
                    <!-- silde-bar colleps block end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i> Filter</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div class="collection-grid-view">
                                                        <ul>
                                                            <li class="my-layout-view active" data-number="4">
                                                                <img src="<?= $this->_pathImg ?>icon/4.png" alt="" class="product-4-layout-view">
                                                            </li>
                                                            <li class="my-layout-view" data-number="6">
                                                                <img src="<?= $this->_pathImg ?>icon/6.png" alt="" class="product-6-layout-view">
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-page-filter">
                                                        <form action="" id="sort-form" method="GET">
                                                            <select id="sort" name="sort">
                                                                <option value="default" selected> - Sắp xếp - </option>
                                                                <option value="price_asc">Giá tăng dần</option>
                                                                <option value="price_desc">Giá giảm dần</option>
                                                                <option value="latest">Mới nhất</option>
                                                            </select>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-wrapper-grid" id="my-product-list">
                                        <div class="row margin-res">
                                            <!-- content -->
                                            <?= $xhtmlTypeBooks ?>
                                            <!-- end content -->
                                        </div>
                                    </div>
                                    <div class="product-pagination">
                                        <div class="theme-paggination-block">
                                            <div class="container-fluid p-0">
                                                <div class="row">
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <nav aria-label="Page navigation">
                                                            <nav>
                                                                <?= $this->pagination->showPagination() ?? '' ?>
                                                            </nav>
                                                        </nav>
                                                    </div>
                                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                                        <div class="product-search-count-bottom">
                                                            <?= $this->countResults ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick-view modal popup start-->
<div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                <div class="row"  id="quick-view-content">
                </div>
            </div>
        </div>
    </div>
</div>