<?php
require_once 'define.php';

function myAutoload($clasName)
{
	require_once LIBRARY_PATH . "{$clasName}.php";
}

spl_autoload_register('myAutoload');
Session::init();
$bootstrap = new Bootstrap();
$bootstrap->init();
