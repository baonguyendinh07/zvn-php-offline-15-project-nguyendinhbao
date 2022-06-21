<?php
class LoginModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->setTable('user');
	}

	public function passwordQuery($username, $password, $backend = false){
		$query[] = "SELECT `$this->table`.`id`";
		$query[] = "FROM `$this->table` LEFT JOIN `group` ON `$this->table`.`group_id`=`group`.`id`";
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password' AND `$this->table`.`status`='active'";
		if($backend == true) $query[] = "AND `group`.`group_acp`='1'";

		return implode(' ', $query);
	}

	public function getUserInfo($params, $backend = false){
		$username = $params['username'];
		$password = md5($params['password']);
		$query[] = "SELECT `$this->table`.`id`, `$this->table`.`username`, `$this->table`.`fullname`, `$this->table`.`email`, `$this->table`.`group_id`, `group`.`name`, `group`.`group_acp`";
		$query[] = "FROM `$this->table` LEFT JOIN `group` ON `$this->table`.`group_id`=`group`.`id`";
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password'";
		if($backend == true) $query[] = "AND `group`.`group_acp`='1'";
		
		$query = implode(' ', $query);
		$result = $this->singleRecord($query);
		return $result;
	}
}
