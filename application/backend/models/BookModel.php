<?php
class BookModel extends Model
{
	private $_columns = ['id', 'name', 'short_description', 'price', 'sale_off', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id'];
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
			if (count($params) > 1 && ($param == 'status' || $param == 'category_id' || $param == 'special')) {
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

		if (isset($params['special']) && is_numeric($params['special'])) {
			$this->arrSearch['special']	 = $params['special'];
		}

		if (isset($this->arrSearch)) $query[] = $this->createWhereSearch($this->arrSearch) ?? '';

		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `book`.`id`, `book`.`name`, `book`.`picture`, `book`.`price`, `book`.`sale_off`, `book`.`status`, `book`.`special`, `book`.`ordering`, `book`.`created`, `book`.`created_by`, `book`.`modified`, `book`.`modified_by`, `book`.`category_id`, `category`.`name` as `category_name`";
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

	public function getListCategory()
	{
		$query[] = "SELECT `id`, `name`";
		$query[] = "FROM `category`";
		$query = implode(' ', $query);
		return $this->listRecord($query);
	}

	public function getItem($id, $currentUser = false)
	{
		$query[] = "SELECT `id`, `name`, `short_description`, `picture`, `price`, `sale_off`, `status`, `special`, `ordering`, `category_id` FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function saveItem($params, $options = null)
	{
		if ($options == 'add') {
			unset($params['token']);
			$params['name'] 		= mysqli_real_escape_string($this->connect, $params['name']);
			$params['short_description'] 	= mysqli_real_escape_string($this->connect, $params['short_description']);
			$params['created'] 	  	= date('Y-m-d H:i:s');
			$params['created_by'] 	= Session::get('user')['userInfo']['username'];

			if (empty($params['picture']['name'])) {
				unset($params['picture']);
			} else {
				$params['picture'] = Upload::uploadFile($params['picture'], 'book');
			}

			$data	= array_intersect_key($params, array_flip($this->_columns));
			$this->insert($data);
			Session::set('notification', 'được thêm thành công!');
		} elseif ($options == 'edit') {
			$id = $params['id'];
			unset($params['id']);
			unset($params['token']);
			$params['name'] 		= mysqli_real_escape_string($this->connect, $params['name']);
			$params['short_description'] 	= mysqli_real_escape_string($this->connect,$params['short_description']);
			$params['modified'] = date('Y-m-d H:i:s');
			$params['modified_by'] = Session::get('user')['userInfo']['username'];

			if (empty($params['picture']['name'])) {
				unset($params['picture']);
			} else {
				Upload::removeFile('book', $params['hiddenPictureName']);
				$params['picture'] = Upload::uploadFile($params['picture'], 'book');
			}

			$data	= array_intersect_key($params, array_flip($this->_columns));
			$this->update($data, [['id', $id]]);
			Session::set('notification', 'được chỉnh sửa thành công');
		}
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'status') 		 $status = $params['status'] == 'active' ? 'inactive' : 'active';
		elseif ($value == 'category_id') $status = $params['category_id'];
		elseif ($value == 'special') 	 $status = $params['special'] == 1 ? 0 : 1;
		elseif ($value == 'ordering') 	 $status = $params['ordering'];
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
			$result = Helper::showStatus($status, $link, $value);
		} elseif ($value == 'category_id') {
			$categoryOptions = Helper::convertArrList($this->getListCategory());
			$dataUrlLink  = URL::createLink($params['module'], $params['controller'], 'changeCategoryId', ['id' => $params['id']]);
			$dataUrl = "data-url='$dataUrlLink'";
			$result = Form::select($categoryOptions, '', $status, 'btn-ajax-category-id', $dataUrl);
		}elseif($value == 'ordering'){
			$result = $status;
		}
		return $result;
	}
}
