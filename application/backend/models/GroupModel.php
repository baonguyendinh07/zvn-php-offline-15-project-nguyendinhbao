<?php
class GroupModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function countItems()
	{
		$query = "SELECT SUM(`group_id` = '1') as `Admin`, SUM(`group_id` = '2') as `Manager`, SUM(`group_id` = '3') as `Member`, SUM(`group_id` = '4') as `Register` FROM `user`";
		return $this->listRecord($query)[0];
	}

	public function listItems()
	{
		$query = 'SELECT `id`, `name`, `group_acp`, `status` FROM `group`';	
		return $this->listRecord($query);
	}
}
