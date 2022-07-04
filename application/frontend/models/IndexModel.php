<?php
class IndexModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

 	public function countItems($table, $where)
	{
		$query[] = "SELECT COUNT(id) as `all`";
		$query[] = "FROM `$table`";
		$query[] = "$where";
		$query = implode(' ', $query);
		return $this->listRecord($query)[0];
	}

	public function listItems($table, $where = "WHERE `status`='active' AND `special`='1'")
	{
		// id, name, price, sale off, picture,status = 1, special = 1
		$query[] = "SELECT *";
		$query[] = "FROM `$table`";
		$query[] = "$where";
		$query = implode(' ', $query);
		return $this->listRecord($query);
	}

	public function getItem($id)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `short_description`,  `description`, `category_id` FROM `book`";
		$query[] = "WHERE `id`='$id'";
		$query = implode(' ', $query);
		return $this->singleRecord($query);
	}
}
