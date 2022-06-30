<?php
$controller = 'user';
$action = 'index';
$option = [
    'group' => ['name' => 'Group'],
    'user' => [
        'name' => 'User',
        'children' => [
            'index' => ['name' => 'List'],
            'form'  => ['name' => 'Add']
        ]
    ],
    'category' => [
        'name' => 'Category',
        'children' => [
            'index' => ['name' => 'List'],
            'form'  => ['name' => 'Add']
        ]
    ],
    'book' => [
        'name' => 'Book',
        'children' => [
            'index' => ['name' => 'List'],
            'form'  => ['name' => 'Add']
        ]
    ],
    'changeAccountPassword' => ['name' => 'Change Account Password']
];

function recursive1($array, &$result, $controller = '', $action = '')
{
    foreach ($array as $key => $value) {

        if($key == $controller){
            $result .= "<b>{$value['name']}</b>";
            if(isset($value['children'])){
                recursive1($value['children'], $result, $action);
            }
        }else{
            $result .= $value['name'];
            if(isset($value['children'])){
                recursive1($value['children'], $result);
            }
        }


    }
    return $result;
}

$a = recursive1($option, $result, $controller, $action);
echo $a;

