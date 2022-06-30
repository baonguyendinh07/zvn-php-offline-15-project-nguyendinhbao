<?php
class BookModel extends Model
{
	public function __construct()
	{
		parent::__construct();
		$this->setTable('book');
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
			$params['modified'] = date('Y-m-d H:i:s');
			$params['modified_by'] = Session::get('user')['userInfo']['id'];
			$this->update($params, [['id', $id]]);
			Session::set('notification', 'được chỉnh sửa thành công');
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
}
