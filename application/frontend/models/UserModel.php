<?php
class UserModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->setTable('user');
	}

	public function passwordQuery($username, $password, $backend = false)
	{
		$query[] = "SELECT `$this->table`.`id`";
		$query[] = "FROM `$this->table` LEFT JOIN `group` ON `$this->table`.`group_id`=`group`.`id`";
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password' AND `$this->table`.`status`='active'";
		if ($backend == true) $query[] = "AND `group`.`group_acp`='1'";

		return implode(' ', $query);
	}

	public function getUserInfo($params, $backend = false)
	{
		$username = $params['username'];
		$password = md5($params['password']);
		$query[] = "SELECT `$this->table`.`id`, `$this->table`.`username`, `$this->table`.`fullname`, `$this->table`.`email`, `$this->table`.`group_id`, `group`.`name`, `group`.`group_acp`";
		$query[] = "FROM `$this->table` LEFT JOIN `group` ON `$this->table`.`group_id`=`group`.`id`";
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password'";
		if ($backend == true) $query[] = "AND `group`.`group_acp`='1'";

		$query = implode(' ', $query);
		$result = $this->singleRecord($query);
		return $result;
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
