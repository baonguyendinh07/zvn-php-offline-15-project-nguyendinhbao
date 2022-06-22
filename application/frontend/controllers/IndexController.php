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
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
