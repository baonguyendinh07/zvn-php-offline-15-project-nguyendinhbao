<?php
class URL
{
	public static function createLink($module, $controller, $action, $params = [])
	{
		$extend = '';
		if (!empty($params)) {
			foreach ($params as $keyParam => $param) {
				$extend .= "&$keyParam=$param";
			}
		}
		$link = sprintf('index.php?module=%s&controller=%s&action=%s%s', $module, $controller, $action, $extend);
		return $link;
	}

	public function redirect($location)
	{
		header("location: $location");
		exit();
	}
}
