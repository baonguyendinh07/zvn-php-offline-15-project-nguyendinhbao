<?php
class Form
{
    public static function label($name)
    {
        return sprintf('<label class="form-label fw-bold">%s</label>', $name);
    }

    public static function input($type, $name, $value = '')
    {
        return sprintf('<input type="%s" name="%s" class="form-control" value="%s">', $type, $name, $value);
    }

    public static function select($options, $name, $selectResult, $class = 'custom-select')
    {
        $optionsXHTML = '';
        foreach ($options as $key => $option) {
            $selected = $key == $selectResult ? 'selected' : '';
            $optionsXHTML .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $option);
        }
        return sprintf('<select class="%s" name="%s">%s</select>', $class, $name, $optionsXHTML);
    }

    public static function areaFilterStatus($link, $options, $status){
        $xhtml = '';
        foreach($options as $option => $countItems){
            if($option == $status) $aClass = 'btn-info';
            else                   $aClass = 'btn-secondary';

            if($option != 'all') $url = $link . '&filterStatus=' . $option;
            else                 $url = $link;
            $xhtml .= sprintf('<a href="%s" class="btn %s">%s<span class="badge badge-pill badge-light">%s</span></a> ', $url, $aClass, ucfirst($option), $countItems);
        }
        return $xhtml;
    }

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
}
