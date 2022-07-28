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

	public static function redirect($location)
	{
		header("location: $location");
		exit();
	}

	public static function replaceSpace($value)
	{
		$value = trim($value);
		$value = str_replace(' ', '-', $value);
		$value = preg_replace('#(-)+#', '-', $value);
		return $value;
	}

	public static function removeCircumflex($str)
	{
		/*a à ả ã á ạ ă ằ ẳ ẵ ắ ặ â ầ ẩ ẫ ấ ậ b c d đ e è ẻ ẽ é ẹ ê ề ể ễ ế ệ
		 f g h i ì ỉ ĩ í ị j k l m n o ò ỏ õ ó ọ ô ồ ổ ỗ ố ộ ơ ờ ở ỡ ớ ợ
		p q r s t u ù ủ ũ ú ụ ư ừ ử ữ ứ ự v w x y ỳ ỷ ỹ ý ỵ z*/
		if (!$str) return false;
		$unicode = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
			'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'd' => 'đ', 'D' => 'Đ',
			'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'i' => 'í|ì|ỉ|ĩ|ị', 'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
			'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ'
		);
		foreach ($unicode as $khongdau => $codau) {
			$arr = explode("|", $codau);
			$str = str_replace($arr, $khongdau, $str);
		}

		$charaterSpecial = '#(,|$|\(|\|\?))#imsU';
		$replaceSpecial = '';
		$value = preg_replace($charaterSpecial, $replaceSpecial, $str);
		$value = strtolower($value);
		return $value;
	}


	public static function filterURL($value)
	{
		//$value = URL::removeSpace($value);
		$value = URL::replaceSpace($value);
		$value = URL::removeCircumflex($value);
		return $value;
	}
}
