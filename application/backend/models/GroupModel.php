<?php
class GroupModel extends Model
{
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('group');
	}

	public function createWhereSearch($params)
	{
		$before = '';
		$after 	= '';
		$start = 0;
		foreach ($params as $param => $value) {
			if (count($params) > 1 && ($param == 'status' || $param == 'group_acp')) {
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
			if ($param == 'name') {
				$before = '';
				$after 	= ')';
			}
			$where[] = "$operator $before$param LIKE '$value'$after";
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
				'name' => '%' . $params['search-key'] . '%'
			];
		}

		if (isset($params['group_acp']) && trim($params['group_acp']) != '') {
			$this->arrSearch['group_acp']	 = $params['group_acp'];
		}

		$query[] = isset($this->arrSearch) ? $this->createWhereSearch($this->arrSearch) : '';
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = 'SELECT * FROM `group`';

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}

		$this->where;
		$query[] = $this->where;

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
			Session::set('notification', 'Thông tin thành viên được chỉnh sửa thành công!');
		}
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'group_acp') $status = $params['status'] == 1 ? 0 : 1;
		elseif ($value == 'status') $status = $params['status'] == 'active' ? 'inactive' : 'active';
		$updateParams = [
			$value => $status,
			'modified' => date('Y-m-d H:i:s')
		];

		$this->update($updateParams, [['id', $params['id']]]);
		Session::set('notificationElement', $value);
		Session::set('notification', 'được chỉnh sửa thành công!');
	}

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
