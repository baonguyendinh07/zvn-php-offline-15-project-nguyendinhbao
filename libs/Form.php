<?php
class Form
{
    public static function label($name, $required = true)
    {
        $required = $required === true ? '<span class="text-danger">*</span>' : '';
        return sprintf('<label class="form-label fw-bold">%s %s</label>', $name, $required);
    }

    public static function input($type, $name, $value = '')
    {
        return sprintf('<input type="%s" name="%s" class="form-control" value="%s">', $type, $name, $value);
    }

    public static function select($options, $name, $selectResult, $class = 'custom-select')
    {
        $optionsXHTML = '';
        foreach ($options as $key => $option) {
            $selected = strval($key) == $selectResult ? 'selected' : '';
            $optionsXHTML .= sprintf('<option value="%s" %s>%s</option>', $key, $selected, $option);
        }
        return sprintf('<select class="%s" name="%s">%s</select>', $class, $name, $optionsXHTML);
    }
}
