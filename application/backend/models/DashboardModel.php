<?php
class DashboardModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function countItems($table, $column = 'id')
	{
		$query = "SELECT COUNT(`$column`) as `all` FROM `$table`";
		return $this->listRecord($query)[0];
	}
}
