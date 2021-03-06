<?php
class BookModel extends Model
{
	private $fromElement;
	private $where = '';
	private $arrSearch;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('book');
	}

	public function createWhereSearch($params)
	{
		foreach ($params as $param => $value) {
			$where[] = "AND $param LIKE '$value'";
		}
		$where = implode(' ', $where);
		$where = 'WHERE ' . substr($where, 4);
		return $this->where = $where;
	}

	public function countItems($params)
	{
		$query[] = "SELECT SUM(`status` = 'active') as `active` FROM `book`";

		if (isset($params['search']) && !empty(trim($params['search']))) {
			$this->arrSearch = [
				'name' => '%' . $params['search'] . '%'
			];
		} elseif (isset($params['category_id']) && is_numeric($params['category_id'])) {
			$this->arrSearch['category_id']	 = $params['category_id'] ?? 3;
		}

		if (isset($this->arrSearch)) $query[] = $this->createWhereSearch($this->arrSearch) ?? '';

		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($params, $totalItems, $totalItemsPerPage = 12)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `short_description`";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE `status`='active'";

		$query[] = (!empty($this->where)) ? 'AND' . substr($this->where, 5) : '';
		if (isset($params['sort']) && $params['sort'] != 'default') {
			$array = explode('_', $params['sort']);
			$query[] = "ORDER BY (`{$array[0]}`*(1-`sale_off`/100)) {$array[1]}";
		} else {
			$query[] = "ORDER BY `ordering` ASC";
		}

		$totalPage			= ceil($totalItems / $totalItemsPerPage);
		if (isset($params['page']) && $params['page'] >= 1 && $params['page'] <= $totalPage) {
			$currentPage = $params['page'];
		} else $currentPage = 1;

		$this->fromElement = ($currentPage - 1) * $totalItemsPerPage;
		if ($this->fromElement >= 0) {
			$query[] = 'LIMIT ' . $this->fromElement . ', ' . $totalItemsPerPage;
			$query = implode(' ', $query);
			return $this->listRecord($query);
		}
	}

	public function listSpecialBooks($where = "`status`='active' AND `special`='1' ORDER BY `ordering` ASC")
	{
		$query[] = "SELECT `id`, `name`, `picture`, `short_description`, `description`, `price`, `sale_off`, `category_id`";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE $where";
		$query = implode(' ', $query);
		return $this->listRecord($query);
	}

	public function getFromElement()
	{
		return $this->fromElement;
	}

	public function getItem($id)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `short_description`,  `description`, `category_id` FROM `$this->table`";
		$query[] = "WHERE `id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}

	public function getItemQuickView($params)
	{
		$id = $params['id'] ?? '';
		if (empty($id)) return null;


		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `short_description`,  `description`, `category_id` FROM `book`";
		$query[] = "WHERE `id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}
}
