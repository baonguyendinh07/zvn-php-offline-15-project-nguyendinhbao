<?php
class CategoryModel extends Model
{
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('category');
	}

	public function createWhereSearch($params)
	{
		$before = '';
		$after 	= '';
		$start = 0;
		foreach ($params as $param => $value) {
			$before = '';
			$after 	= '';
			if (count($params) > 1 && $param == 'status') {
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
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `id`, `name`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`";
		$query[] = "FROM `$this->table`";

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}
		$query[] = $this->where ?? '';

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

 	public function getItem($id)
	{
		$query[] = "SELECT * FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
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
		}
		return $result;
	}

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
