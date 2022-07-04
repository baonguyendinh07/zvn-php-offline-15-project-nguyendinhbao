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
        return sprintf('<a href="%s" class="btn %s rounded-circle btn-sm btn-ajax-%s"><i class="fas fa-check"></i></a>', $link, $aClass, $column);
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
            $xhtml .= sprintf('<a href="%s" class="btn %s">%s<span class="badge badge-pill badge-light">%s</span></a> ', $url, $aClass, ucfirst($option), $countItems ?? 0);
        }
        return $xhtml;
    }

    public static function textCutting($text, $length){
        $text = trim($text);
        $result = strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
        return $result;
    }
}
