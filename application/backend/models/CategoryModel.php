<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
class CategoryModel extends Model
{
	private $_columns = ['id', 'name', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'special', 'ordering'];
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

		if (isset($this->arrSearch)) $query[] = $this->createWhereSearch($this->arrSearch) ?? '';

		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `special`, `ordering`";
		$query[] = "FROM `$this->table`";

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}

		$query[] = $this->where;
		$query[] = "ORDER BY `id` DESC";

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
		$query[] = "SELECT `id`, `name`, `picture`, `status`, `special`, `ordering` FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function saveItem($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['name'] 		= mysqli_real_escape_string($this->connect, $params['name']);
			$params['created'] 	  	= date('Y-m-d H:i:s');
			$params['created_by'] 	= Session::get('user')['userInfo']['username'];

			if (empty($params['picture']['name'])) {
				unset($params['picture']);
			} else {
				$params['picture'] = Upload::uploadFile($params['picture'], 'category');
			}

			$data	= array_intersect_key($params, array_flip($this->_columns));
			$this->insert($data);

			Session::set('notification', 'được thêm thành công!');
		} elseif ($options == 'edit') {
			$id = $params['id'];
			unset($params['id']);
			unset($params['token']);
			$params['name'] 		= mysqli_real_escape_string($this->connect, $params['name']);
			$params['modified'] 	= date('Y-m-d H:i:s');
			$params['modified_by']	= Session::get('user')['userInfo']['username'];

			if (empty($params['picture']['name'])) {
				unset($params['picture']);
			} else {
				Upload::removeFile('category', $params['hiddenPictureName']);
				$params['picture'] = Upload::uploadFile($params['picture'], 'category');
			}

			$data	= array_intersect_key($params, array_flip($this->_columns));
			$this->update($data, [['id', $id]]);

			Session::set('notification', 'được chỉnh sửa thành công');
		}
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'status') 	$status = $params['status'] == 'active' ? 'inactive' : 'active';
		if ($value == 'special') 	 $status = $params['special'] == 1 ? 0 : 1;
		if ($value == 'ordering') 	$status = $params['ordering'] ;
		$updateParams = [
			$value => $status,
			'modified' => date('Y-m-d H:i:s'),
			'modified_by' => Session::get('user')['userInfo']['username']
		];

		$this->update($updateParams, [['id', $params['id']]]);

		if ($value == 'status' || $value == 'special') {
			$linkParams = [
				'id' => $params['id'],
				$value => $status
			];
			$link = URL::createLink($params['module'], $params['controller'], $params['action'], $linkParams);
			$result = Helper::showStatus($status, $link);
		}elseif($value == 'ordering'){
			$result = $status;
		}
		return $result;
	}
}
