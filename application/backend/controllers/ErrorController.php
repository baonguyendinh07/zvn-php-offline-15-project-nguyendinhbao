<?php
class ErrorController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function errorAction()
	{
		$this->_view->setTitle('BACKEND - ERROR');
		$this->_view->setTitlePageHeader('BACKEND - ERROR');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render('error/error');
	}
}
?>