<?php
class RegisterModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->setTable('user');
	}

	public function registerQuery($key, $value)
	{
		$query[] = "SELECT `id`";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE `$key`='$value'";

		return implode(' ', $query);
	}

	public function saveItem($params, $options = null)
	{
		unset($params['token']);
		$params['register_date'] = date('Y-m-d H:i:s');
		$params['register_ip'] = $_SERVER['REMOTE_ADDR'];
		$params['status'] = 'inactive';
		$params['group_id'] = '4';
		$this->insert($params);
		Session::set('notification', 'Bạn đã đăng ký tài khoảng thành công!');
	}
}
