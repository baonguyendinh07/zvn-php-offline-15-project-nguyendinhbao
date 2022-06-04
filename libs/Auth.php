<?php
class Auth
{

	public static function checkLogin()
	{
		Session::init();
		if (Session::get('loggedIn') == false) {
			Session::destroy();
			header("location: index.php?module=auth&controller=auth&action=login");
			exit();
		}
	}
}
