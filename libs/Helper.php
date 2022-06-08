<?php
class Helper
{
    public static function showStatus($status, $link)
    {
        if ($status == 'active' || $status == 1) {
            $aClass = "btn-success";
        } elseif ($status == 'inactive' || $status == 0) {
            $aClass = "btn-danger";
        }
        return sprintf('<a href="%s" class="btn %s rounded-circle btn-sm"><i class="fas fa-check"></i></a>', $link, $aClass);
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
            $searchKey = trim($searchKey);
            preg_match_all("#$searchKey#i", $subject, $matches);
            $matches = array_unique($matches[0]);
            foreach($matches as $value){
                $subject = str_replace($value, "<mark>$value</mark>", $subject);
            }
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
}
