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
		$where = '';
		$p = ')';
		foreach ($params as $param => $value) {
			$beforeValue = $params[$param - 1][0] ?? '';
			if ($beforeValue == 'fullname' && $value[0] == 'status') {
				$operator = "$p AND";
				$p = '';
			} else $operator = 'OR';
			$where .= " $operator $value[0] LIKE '$value[1]'";
		}
		$where = '(' . substr($where, 4) . $p;
		return $this->where = "WHERE $where";
	}

	public function countItems($params)
	{
		$query[] = "SELECT COUNT(`status`) as `all`, SUM(`status` = 'active') as `active`, SUM(`status` = 'inactive') as `inactive` FROM `$this->table`";
		if (isset($params['search-key']) && !empty(trim($params['search-key']))) {
			$this->arrSearch[] = ['id', '%' . $params['search-key'] . '%'];
			$this->arrSearch[] = ['name', '%' . $params['search-key'] . '%'];
			$query[] = $this->createWhereSearch($this->arrSearch);
		}
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = 'SELECT * FROM `group`';
		if (isset($params['filterStatus']) || !empty($params['filterStatus'])) {
			$this->arrSearch[]	 = ['status', $params['filterStatus']];
			$this->createWhereSearch($this->arrSearch);
		}
		$query[] = $this->where;

		$totalPage			= ceil($totalItems / $totalItemsPerPage);
		if ($params['page'] >= 1 && $params['page'] <= $totalPage) $currentPage = $params['page'];
		else 													   $currentPage = $totalPage;

		$query[] = ' LIMIT ' . ($currentPage - 1) * $totalItemsPerPage . ', ' . $totalItemsPerPage;
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
