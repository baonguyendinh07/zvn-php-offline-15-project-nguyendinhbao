<?php
class GroupModel extends Model
{
	private $where;

	public function __construct()
	{
		parent::__construct();
		$this->setTable('group');
	}

	public function listItems($params)
	{
		$query[] = 'SELECT * FROM `group`';
		if (isset($params['search-key']) && !empty(trim($params['search-key']))) {
			$searchKey = $params['search-key'];
			$query[] = "WHERE id LIKE '%" . $searchKey . "%' OR name LIKE '%" . $searchKey . "%' OR group_acp LIKE '%" . $searchKey . "%' OR status LIKE '%" . $searchKey . "%'";
		}

		$query = implode(' ', $query);

		$result		= $this->listRecord($query);
		return $result;
	}

	public function changeStatus($params, $value)
	{
		$status = $params['status'] == 'active' ? 'inactive' : 'active';
		$params['modified'] = date('Y-m-d H:i:s');
		$this->update([$value => $status], [['id', $params['id']]]);
		Session::set('notification', $value . ' được chỉnh sửa thành công!');
	}

	public function deleteItem($id, $options = null)
	{
		$this->delete(array($id));
	}
}
