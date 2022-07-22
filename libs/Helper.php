<?php
class Helper
{
    public static function showStatus($status, $link, $column = 'status')
    {
        if ($status === 'active' || $status == 1) {
            $aClass = "btn-success";
        } elseif ($status === 'inactive' || $status == 0) {
            $aClass = "btn-danger";
        }
        return sprintf('<a href="%s" class="btn %s rounded-circle btn-sm btn-ajax-%s"><i class="fas fa-check""></i></a>', $link, $aClass, $column);
    }

    public static function createButtonLink($link, $content, $color, $isCircle = false, $isSmall = false)
    {
        $circle = $isCircle ? 'rounded-circle' : '';
        $small = $isSmall ? 'btn-sm' : '';

        return sprintf('<a href="%s" class="btn btn-%s %s %s">%s</a>', $link, $color, $circle, $small, $content);
    }

    public static function highlight($searchKey, $subject)
    {
        if (!empty(trim($searchKey))) {
            $searchKey = preg_quote(trim($searchKey));
            $subject = preg_replace("#$searchKey#i", "<mark>\\0</mark>", $subject);
        }
        return $subject;
    }

    public static function randomString($length = 5)
    {
        $arrCharacter = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $arrCharacter = implode('', $arrCharacter);
        $arrCharacter = str_shuffle($arrCharacter);

        $result        = substr($arrCharacter, 0, $length);
        return $result;
    }

    public static function convertArrList($array)
    {
        $result = [];
        foreach ($array as $value) {
            $result[$value['id']] = $value['name'];
        }
        return $result;
    }

    public static function showMessege($class, $arrElements)
    {
        $xhtml = '<div class="alert alert-' . $class . '">
                    <ul class="list-unstyled mb-0">';
        foreach ($arrElements as $key => $value) {
            $xhtml .= '<li class="text-white"><b>' . ucfirst($key) . '</b> ' . $value . '!</li>';
        }
        $xhtml .= '</ul></div>';
        return $xhtml;
    }

    public static function areaFilterStatus($options, $params)
    {
        $xhtml = '';
        $keySelected = $params['filterStatus'] ?? 'all';
        $linkParams = [];

        if (isset($params['group_id']))     $linkParams['group_id'] = $params['group_id'];
        if (isset($params['category_id']))  $linkParams['category_id'] = $params['category_id'];
        if (isset($params['special']))      $linkParams['special'] = $params['special'];
        if (isset($params['search-key']))   $linkParams['search-key'] = $params['search-key'];

        foreach ($options as $option => $countItems) {
            $aClass = $option == $keySelected ? 'btn-info' : 'btn-secondary';
            $linkParams['filterStatus'] = $option;
            $url = URL::createLink($params['module'], $params['controller'], $params['action'], $linkParams);
            $xhtml .= sprintf('<a href="%s" class="btn %s">%s <span class="badge badge-pill badge-light">%s</span></a> ', $url, $aClass, ucfirst($option), $countItems ?? 0);
        }
        return $xhtml;
    }

    public static function textCutting($text, $length)
    {
        $text = trim($text);
        $result = strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
        return $result;
    }

    public static function showProductBox($arrData, $arrParam, $pathPicture, $strlen, $boxHeight = '', $heightTextBox = '', $openDiv = '', $closeDiv = '')
    {
        $xhtmlTypeBooks = '';
        $searchValue = $arrParam['search'] ?? '';
        foreach ($arrData as $value) {
            $id         = $value['id'];
            $name       = Helper::highlight($searchValue, Helper::textCutting($value['name'], $strlen));
            $picture    = !empty($value['picture']) ? $pathPicture . $value['picture'] : $pathPicture . 'default.jpg';
            $itemURL      = URL::filterURL($value['name']) . '-b' . $id;
            $quickViewURL = URL::filterURL($value['name']) . '-qv' . $id;
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

            $xhtmlTypeBooks .=
                $openDiv . '
                <div class="product-box" ' . $boxHeight . '">
                    <div class="img-wrapper">
                        <div class="lable-block">
                            ' . $saleOffXhtml . '
                        </div>
                        <div class="front">
                            <a href="' . $itemURL . '">
                                <img src="' . $picture . '" class="img-fluid blur-up lazyload bg-img" alt="">
                            </a>
                        </div>
                        <div class="cart-info cart-wrap">
                            <a href="index.php?module=frontend&controller=user&action=tempCart&id=' . $id . '&quantities=1" title="Add to cart" class="btn-ajax-addOneToCart"><i class="ti-shopping-cart"></i></a>
                            <a href="' . $quickViewURL . '" title="Quick View" class="btn-ajax-quick-view"><i class="ti-search" data-toggle="modal" data-target="#quick-view"></i></a>
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
                        <a href="' . $itemURL . '" title="' . $value['name'] . '" style="display:block;height:' . $heightTextBox . '">
                            <h6>' . $name . '</h6>
                        </a>
                        <h4 class="text-lowercase" style="">' . $price . '</h4>
                    </div>
                </div>'
                . $closeDiv;
        }
        return $xhtmlTypeBooks;
    }
}
