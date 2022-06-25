<?php
class BookModel extends Model
{
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('book');
	}

	public function createWhereSearch($params)
	{
		$before = '';
		$after 	= '';
		$start = 0;
		foreach ($params as $param => $value) {
			$before = '';
			$after 	= '';
			if (count($params) > 1 && ($param == 'status' || $param == 'category_id')) {
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
				'name' => '%' . $params['search-key'] . '%'
			];
		}

		if (isset($params['category_id']) && is_numeric($params['category_id'])) {
			$this->arrSearch['category_id']	 = $params['category_id'];
		}

		$where = 'WHERE';
		if (isset($this->arrSearch)) {
			$query[] = $this->createWhereSearch($this->arrSearch) ?? '';
			$where = 'AND';
		}

		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
        // id name picture price saleoff caterogy status special ordering created modifier
		$query[] = "SELECT `book`.`id`, `book`.`name`, `book`.`picture`, `book`.`price`, `book`.`sale_off`, `book`.`status`, `book`.`ordering`, `book`.`created`, `book`.`created_by`, `book`.`modified`, `book`.`modified_by`, `book`.`category_id`, `category`.`name` as `category_name`";
		$query[] = "FROM `$this->table`, `category`";
        $query[] = "WHERE `book`.`category_id`=`category`.`id`";

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}

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
		if ($currentUser == true) $operater = '=';
		$category_id = Session::get('user')['userInfo']['category_id'];
		$query[] = "AND `$this->table`.`category_id`$operater'$category_id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function saveItem($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['created'] 	  = date('Y-m-d H:i:s');
			$params['created_by'] = Session::get('user')['userInfo']['username'];

			$this->insert($params);
			Session::set('notification', 'được thêm thành công!');
		} elseif ($options == 'edit') {
			$id = $params['id'];
			unset($params['id']);
			unset($params['token']);
			unset($params['username']);
			unset($params['email']);
			$params['modified'] = date('Y-m-d H:i:s');
			$params['modified_by'] = Session::get('user')['userInfo']['username'];
			$this->update($params, [['id', $id]]);
			Session::set('notification', 'được chỉnh sửa thành công');
		}
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'status') 	$status = $params['status'] == 'active' ? 'inactive' : 'active';
		elseif ($value == 'category_id') 	$status = $params['category_id'];
		$updateParams = [
			$value => $status,
			'modified' => date('Y-m-d H:i:s'),
			'modified_by' => Session::get('user')['userInfo']['username']
		];

		$this->update($updateParams, [['id', $params['id']]]);

		if ($value == 'status') {
			$linkParams = [
				'id' => $params['id'],
				'status' => $status
			];
			$link = URL::createLink($params['module'], $params['controller'], $params['action'], $linkParams);
			$result = Helper::showStatus($status, $link);
		} elseif ($value == 'category_id') {
			$groupOptions = Helper::convertArrList($this->getListGroup());
			$dataUrlLink  = URL::createLink($params['module'], $params['controller'], 'changeGroupId', ['id' => $params['id']]);
			$dataUrl = "data-url='$dataUrlLink'";
			$result = Form::select($groupOptions, '', $status, 'btn-ajax-group-id', $dataUrl);
		}
		return $result;
	}

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
