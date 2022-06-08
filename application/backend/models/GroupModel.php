<?php
class GroupModel extends Model
{
	//private $where = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTable('group');
	}

	private function createWhere($params, $searchKey)
	{
		$where = '';
		foreach ($params as $value) {
			$where .= " OR $value LIKE '$searchKey'";
		}
		$where = 'WHERE' . substr($where, 3);
		return $where;
	}

	public function countItems()
	{

		//$this->where = "WHERE status LIKE '%" . $params['status'] . "%'";
		$query = "SELECT COUNT(`status`) as `all`, SUM(`status` = 'active') as `active`, SUM(`status` = 'inactive') as `inactive` FROM `$this->table`";
		//$query 	= "SELECT COUNT(*) FROM `group`" . $this->where;
		$result		= $this->listRecord($query)[0]/*['COUNT(*)'] */;
		return $result;
	}

	public function getItem($params)
	{
		return $this->singleRecord("SELECT * FROM `$this->table` WHERE `id` = {$params['id']}");
	}
	
	public function listItems($params = [])
	{
		$query[] = 'SELECT * FROM `group`';
		if (isset($params['search-key']) && !empty(trim($params['search-key']))) {
			$searchKey = '%' . trim($params['search-key']) . '%';
			$query[] = $this->createWhere(['id', 'name'], $searchKey);
		} elseif (isset($params['filterStatus']) && !empty(trim($params['filterStatus']))) {
			$searchKey = trim($params['filterStatus']);
			$query[] = $this->createWhere(['status'], $searchKey);
		}
		$query = implode(' ', $query);

		$result		= $this->listRecord($query);
		return $result;
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
