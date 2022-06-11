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

/*     public static function showMessege($class, $notification)
    {
        $xhtml = '';
        if(!empty(Session::get('notification'))){
            $xhtml = sprintf('
            <div class="alert alert-%s alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> %s!</h5>
                %s
            </div>', $class, $notification, Session::get('notification'));
            Session::unset('notification');
        }
        
        return $xhtml;
    } */

    public static function showMessege($class, $notification, $arrElements){
        $xhtml= '<div class="alert alert-'.$class.' alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> '.$notification.'!</h5>
                    <ul class="list-unstyled mb-0">';
        foreach($arrElements as $key =>$value){
            $xhtml .= '<li class="text-white"><b>'.ucfirst($key).'</b> '.$value.'!</li>';
        }
        $xhtml .= '</ul></div>';
        return $xhtml;
    }

    public static function areaFilterStatus($link, $options, $status){
        $xhtml = '';
        foreach($options as $option => $countItems){
            $aClass = $option == $status ? 'btn-info' : 'btn-secondary';
            $url    = ($option != 'all') ? "$link&filterStatus=$option" : $link;
            $xhtml .= sprintf('<a href="%s" class="btn %s">%s<span class="badge badge-pill badge-light">%s</span></a> ', $url, $aClass, ucfirst($option), $countItems);
        }
        return $xhtml;
    }
}
