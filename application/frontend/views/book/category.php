<?php
include_once BLOCK_PATH . 'category.php';
?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">Danh mục sách</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="ratio_asos j-box pets-box section-b-space" id="category">
    <div class="container">
        <div class="no-slider five-product row">
            <?= $listCategory ?>
        </div>
    </div>
</section>