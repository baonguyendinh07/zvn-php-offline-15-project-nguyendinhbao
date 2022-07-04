<?php
class IndexController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function indexAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->listSpecialBooks 			= $this->_model->listItems('book');
		$this->_view->listSpecialCategories 	= $this->_model->listItems('category');

		if (!empty($this->_view->listSpecialCategories)) {
			foreach ($this->_view->listSpecialCategories as $value) {
				$categoryId = $value['id'];
				$where = "WHERE `status`='active' AND `category_id`='$categoryId' LIMIT 8";
				$this->_view->listTypeBooks[$categoryId] = $this->_model->listItems('book', $where);
				$where = "WHERE `status`='active' AND `category_id`='$categoryId'";
				$this->_view->countTypeBooks[$categoryId] = $this->_model->countItems('book', $where);
			}
		}

		$this->_view->_arrParam = $this->_arrParam;
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
