<?php
class GroupController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('backend/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function indexAction()
	{
		$this->_view->setTitle('Manager List');
		$this->_view->setTitlePageHeader('Manager List');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->arrCountItems = $this->_model->countItems();
		$this->_view->items = $this->_model->listItems();
		$this->_view->render($this->_arrParam['controller'] . '/index');
	}
}
