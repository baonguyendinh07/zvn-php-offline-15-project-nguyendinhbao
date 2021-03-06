<?php
class Form
{
    public static function label($name, $class = '', $required = true)
    {
        $required = $required === true ? '<span class="text-danger">*</span>' : '';
        return sprintf('<label class="%s">%s %s</label>', $class, $name, $required);
    }

    public static function input($type, $name, $value = '', $id = '', $placeholder = '', $style = '')
    {
        return sprintf('<input type="%s" name="%s" class="form-control" id="%s" value="%s" placeholder="%s" %s>', $type, $name, $id, $value, $placeholder, $style);
    }

    public static function select($options, $name, $selectResult, $class = '', $attr = '')
    {
        $optionsXHTML = '';
        foreach ($options as $key => $option) {
            $selected = strval($key) == $selectResult ? 'selected' : '';
            $optionsXHTML .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $option);
        }
        return sprintf('<select class="%s" name="%s" %s>%s</select>', $class, $name, $attr, $optionsXHTML);
    }
}
