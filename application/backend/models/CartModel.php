<?php
class CartModel extends Model
{
	//private $_columns = ['id', 'name', 'short_description', 'description', 'price', 'sale_off', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id'];
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('cart');
	}

	public function createWhereSearch($params)
	{
		$before = '';
		$after 	= '';
		$start = 0;
		foreach ($params as $param => $value) {
			$before = '';
			$after 	= '';
			if (count($params) > 1 && ($param == 'status')) {
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
			if ($param == 'username') {
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
				'username' => '%' . $params['search-key'] . '%'
			];
		}

		if (isset($this->arrSearch)) $query[] = $this->createWhereSearch($this->arrSearch) ?? '';

		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage)
	{
		$query[] = "SELECT `cart`.`id`, `cart`.`username`, `cart`.`books`, `cart`.`prices`, `cart`.`quantities`, `cart`.`names`, `cart`.`status`, `cart`.`date`, `user`.`fullname` as `name`, `user`.`phone_number` as `phone_number`, `user`.`address` as `address`";
		$query[] = "FROM `$this->table`, `user`";
		$query[] = "WHERE `cart`.`username`=`user`.`username`";

		if (isset($params['filterStatus']) && ($params['filterStatus'] == 'active' || $params['filterStatus'] == 'inactive')) {
			$this->arrSearch['status']	 = $params['filterStatus'];
			$this->createWhereSearch($this->arrSearch);
		}

		$query[] = (!empty($this->where)) ? 'AND' . substr($this->where, 5) : '';
		$query[] = "ORDER BY `date` DESC";

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
		$query[] = "SELECT `cart`.`id`, `cart`.`username`, `cart`.`books`, `cart`.`prices`, `cart`.`quantities`, `cart`.`names`, `cart`.`pictures`, `cart`.`status`, `cart`.`date`, `user`.`fullname` as `name`";
		$query[] = "FROM `$this->table`, `user`";
		$query[] = "WHERE `cart`.`username`=`user`.`username`";
		$query[] = "AND `cart`.`id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function changeStatus($params, $value)
	{
		if ($value == 'status') $status = $params['status'] == 'active' ? 'inactive' : 'active';

		$updateParams = [
			$value => $status
		];

		$this->update($updateParams, [['id', $params['id']]]);

		if ($value == 'status') {
			$linkParams = [
				'id' => $params['id'],
				$value => $status
			];
			$link = URL::createLink($params['module'], $params['controller'], $params['action'], $linkParams);
			$result = Helper::showStatus($status, $link, $value);
		}
		return $result;
	}
}
