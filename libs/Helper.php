<?php
class Helper{
    public static function showStatus($status, $link){
        if($status == 'active'){
            $aClass = "btn-success";
        }elseif($status == 'inactive'){
            $aClass = "btn-danger";
        }
        return sprintf('<a href="%s" class="btn %s rounded-circle btn-sm"><i class="fas fa-check"></i></a>', $link, $aClass);
    }
}