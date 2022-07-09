<?php
class DashboardController extends Controller
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
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - Reset Password');
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->sum['group'] 		= $this->_model->countItems('group')['all'];
		$this->_view->sum['user'] 		= $this->_model->countItems('user')['all'];
		$this->_view->sum['category'] 	= $this->_model->countItems('category')['all'];
		$this->_view->sum['book'] 		= $this->_model->countItems('book')['all'];
		$this->_view->sum['slider'] 	= $this->_model->countItems('slider')['all'];

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
