<?php
class ErrorController extends Controller
{
	public function __construct($arrParams)
	{
		$arrParams['controller'] = 'error';
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('frontend/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function errorAction()
	{
		$this->_view->setTitle('BOOKSTORE - ERROR');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render('error/error');
	}
}
?>