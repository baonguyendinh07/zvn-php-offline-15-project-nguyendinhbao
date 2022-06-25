<?php
class UserModel extends Model
{
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('user');
	}

	public function createWhereSearch($params)
	{
		$before = '';
		$after 	= '';
		$start = 0;
		foreach ($params as $param => $value) {
			$before = '';
			$after 	= '';
			if (count($params) > 1 && ($param == 'status' || $param == 'group_id')) {
				if ($value == 'default') continue;
				$operator = 'AND';
				$start = $start == 3 ? 3 : 4;
				$after = '';
			} else {
				$operator = 'OR';
				$start = 3;
			}
			if ($param == 'id') {
				$before = '(';
				$after 	= '';
			}
			if ($param == 'fullname') {
				$before = '';
				$after 	= ')';
			}
			$where[] = "$operator $before$this->table.$param LIKE '$value'$after";
		}
		$where = implode(' ', $where);
		$where = 'WHERE ' . substr($where, $start);
		return $this->where = $where;
	}

	public function countItems($params)
	{
		$query[] = "SELECT COUNT(`status`) as `all`, SUM(`status` = 'active') as `active`, SUM(`status` = 'inactive') as `inactive` FROM `$this->table`";

		if (isset($params['search-key']) && !empty(trim($params['search-key']))) {
			$this->arrSearch = [
				'id' => '%' . $params['search-key'] . '%',
				'username' => '%' . $params['search-key'] . '%',
				'email' => '%' . $params['search-key'] . '%',
				'fullname' => '%' . $params['search-key'] . '%'
			];
		}

		if (isset($params['group_id']) && is_numeric($params['group_id'])) {
			$this->arrSearch['group_id']	 = $params['group_id'];
		}

		$where = 'WHERE';
		if(isset($this->arrSearch)){
			$query[] = $this->createWhereSearch($this->arrSearch) ?? '';
			$where = 'AND';
		}
		$group_id = Session::get('user')['userInfo']['group_id'];
		$query[] = "$where `user`.`group_id`>'$group_id'";
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `user`.`id`, `user`.`username`, `user`.`email`, `user`.`fullname`, `user`.`password`, `user`.`created`, `user`.`created_by`, `user`.`modified`, `user`.`modified_by`, `user`.`register_date`, `user`.`register_ip`, `user`.`status`, `user`.`ordering`, `user`.`group_id`, `group`.`name` as `group_name`";
		$query[] = "FROM `$this->table`, `group`";

		$group_id = Session::get('user')['userInfo']['group_id'];
		$query[] = "WHERE `user`.`group_id`=`group`.`id` AND `user`.`group_id`>'$group_id'";

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}
		$this->where;
		$query[] = (!empty($this->where)) ? 'AND' . substr($this->where, 5) : '';

		$totalPage			= ceil($totalItems / $totalItemsPerPage);
		if ($params['page'] >= 1 && $params['page'] <= $totalPage) $currentPage = $params['page'];
		else 													   $currentPage = 1;
		$fromElement = ($currentPage - 1) * $totalItemsPerPage;
		if ($fromElement >= 0) {
			$query[] = 'LIMIT ' . $fromElement . ', ' . $totalItemsPerPage;
			$query = implode(' ', $query);
			return $this->listRecord($query);
		}
	}

	public function getListGroup()
	{
		$query[] = "SELECT `id`, `name`";
		$query[] = "FROM `group`";
		$query = implode(' ', $query);
		return $this->listRecord($query);
	}

	public function getItem($id, $currentUser = false)
	{
		$query[] = "SELECT * FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
		$operater = '>';
		if($currentUser == true) $operater = '=';
		$group_id = Session::get('user')['userInfo']['group_id'];
		$query[] = "AND `$this->table`.`group_id`$operater'$group_id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function saveItem($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['created'] 	  = date('Y-m-d H:i:s');
			$params['created_by'] = Session::get('user')['userInfo']['id'];

			$this->insert($params);
			Session::set('notification', 'được thêm thành công!');
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

	public function changeStatus($params, $value)
	{
		if ($value == 'status') 	$status = $params['status'] == 'active' ? 'inactive' : 'active';
		elseif ($value == 'group_id') 	$status = $params['group_id'];
		$updateParams = [
			$value => $status,
			'modified' => date('Y-m-d H:i:s'),
			'modified_by' => Session::get('user')['userInfo']['id']
		];

		$this->update($updateParams, [['id', $params['id']]]);

		if ($value == 'status') {
			$linkParams = [
				'id' => $params['id'],
				'status' => $status
			];
			$link = URL::createLink($params['module'], $params['controller'], $params['action'], $linkParams);
			$result = Helper::showStatus($status, $link);
		} elseif ($value == 'group_id') {
			$groupOptions = Helper::convertArrList($this->getListGroup());
			$dataUrlLink  = URL::createLink($params['module'], $params['controller'], 'changeGroupId', ['id' => $params['id']]);
			$dataUrl = "data-url='$dataUrlLink'";
			$result = Form::select($groupOptions, '', $status, 'btn-ajax-group-id', $dataUrl);
		}
		return $result;
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

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
