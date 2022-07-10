<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
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
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password' AND `$this->table`.`status`='active' AND `$this->table`.`group_id`<'4'";
		if ($backend == true) $query[] = "AND `group`.`group_acp`='1'";

		return implode(' ', $query);
	}

	public function getUserInfo($params, $backend = false)
	{
		$username = $params['username'];
		$password = $params['password'];
		$query[] = "SELECT `$this->table`.`id`, `$this->table`.`username`, `$this->table`.`fullname`, `$this->table`.`email`, `$this->table`.`group_id`, `group`.`name`, `group`.`group_acp`";
		$query[] = "FROM `$this->table` LEFT JOIN `group` ON `$this->table`.`group_id`=`group`.`id`";
		$query[] = "WHERE (`$this->table`.`username`='$username' OR `$this->table`.`email`='$username') AND `$this->table`.`password`='$password'";
		if ($backend == true) $query[] = "AND `group`.`group_acp`='1'";

		$query = implode(' ', $query);
		$result = $this->singleRecord($query);
		return $result;
	}

	public function listCart($arrCart = [])
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`";
		$query[] = "FROM `book`";
		$query[] = "WHERE `status`='active'";

		if (!empty($arrCart)) {
			$listItems = '';
			foreach ($arrCart as $key => $value) {
				$listItems .= " OR `id`='$key'";
			}
			$query[] = 'AND (' . substr($listItems, 4) . ')';
		}

		$query = implode(' ', $query);
		return $this->listRecord($query);
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
		if ($options == 'add') {
			unset($params['token']);
			unset($params['id']);
			$params['register_date'] = date('Y-m-d H:i:s');
			$params['register_ip'] = $_SERVER['REMOTE_ADDR'];
			$params['status'] = 'inactive';
			$params['group_id'] = '4';
			$this->insert($params);
			Session::set('notification', 'Bạn đã đăng ký tài khoảng thành công!');
		} elseif ($options == 'edit') {
			$id = $params['id'];
			unset($params['id']);
			unset($params['token']);
			unset($params['username']);
			unset($params['email']);
			unset($params['group_id']);
			$params['modified'] = date('Y-m-d H:i:s');
			$params['modified_by'] = Session::get('user')['userInfo']['id'];
			$this->update($params, [['id', $id]]);
			Session::set('notification', 'được chỉnh sửa thành công');
		}
	}

	public function saveCart($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['status'] = 'inactive';
			$params['date'] = date('Y-m-d H:i:s');
			$this->setTable('cart');
			$this->insert($params);
		}
	}

	public function getItem($id, $currentUser = false)
	{
		$query[] = "SELECT * FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
		$operater = '>';
		if ($currentUser == true) $operater = '=';
		$group_id = Session::get('user')['userInfo']['group_id'];
		$query[] = "AND `$this->table`.`group_id`$operater'$group_id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function orderHistories($username)
	{
		$query[] = "SELECT `id`, `username`, `books`, `pictures`, `names`, `quantities`, `prices`, `date`";
		$query[] = "FROM `cart`";
		$query[] = "WHERE `status`='active' && `username`='$username'";
		$query[] = "ORDER BY `date` DESC";
		$query = implode(' ', $query);
		return $this->listRecord($query);
	}

	public function getBook($id)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `short_description`,  `description`, `category_id` FROM `book`";
		$query[] = "WHERE `id`='$id' AND `status`='active'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}
}
