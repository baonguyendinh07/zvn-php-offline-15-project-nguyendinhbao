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

    public static function select($name, $options, $optionTitle, $selectResult = '')
    {
        $optionsXHTML = '<option value="">' . $optionTitle . '</option>';
        foreach ($options as $option) {
            if ($option == $selectResult) {
                $optionsXHTML .= '<option value="' . $option . '" selected="selected">' . ucfirst($option) . '</option>';
            } else {
                $optionsXHTML .= '<option value="' . $option . '">' . ucfirst($option) . '</option>';
            }
        }
        return sprintf('<select class="form-select" name="%s">%s</select>', $name, $optionsXHTML);
    }
}
