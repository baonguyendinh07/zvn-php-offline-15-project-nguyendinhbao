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

		$query[] = isset($this->arrSearch) ? $this->createWhereSearch($this->arrSearch) : '';
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `user`.`id`, `user`.`username`, `user`.`email`, `user`.`fullname`, `user`.`password`, `user`.`created`, `user`.`created_by`, `user`.`modified`, `user`.`modified_by`, `user`.`register_date`, `user`.`register_ip`, `user`.`status`, `user`.`ordering`, `user`.`group_id`, `group`.`name` as `group_name`";
		$query[] = "FROM `$this->table`, `group`";
		$query[] = "WHERE `user`.`group_id`=`group`.`id`";

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

	public function getItem($params)
	{
		return $this->singleRecord("SELECT * FROM `$this->table` WHERE `id` = {$params['id']}");
	}

	public function saveItem($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['created'] = date('Y-m-d H:i:s');
			$params['modified'] = date('Y-m-d H:i:s');
			$this->insert($params);
			Session::set('notification', 'được thêm thành công!');
		} elseif ($options == 'edit') {
			$id = $params['id'];
			unset($params['id']);
			unset($params['token']);
			$params['modified'] = date('Y-m-d H:i:s');
			$this->update($params, [['id', $id]]);
			Session::set('notification', 'được chỉnh sửa thành công!');
		}
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'status') 	$status = $params['status'] == 'active' ? 'inactive' : 'active';
		elseif ($value == 'group_id') 	$status = $params['group_id'];
		$updateParams = [
			$value => $status,
			'modified' => date('Y-m-d H:i:s')
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
		//Session::set('notificationElement', $value);
		//Session::set('notification', 'được chỉnh sửa thành công!');
	}

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
