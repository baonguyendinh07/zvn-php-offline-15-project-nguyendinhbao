<?php
class GroupController extends Controller{
	
	public function __construct($arrParams){
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('backend/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}
	
	public function indexAction(){
		$this->_view->setTitle('Index');
		$this->_view->items = $this->_model->listItems($this->_arrParam);
		$this->_view->pathchangeGroupAcp = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changeGroupAcp');
		$this->_view->pathchangeStatus = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changeStatus');
		$this->_view->pathDelete = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'delete');

		$this->_view->render('group/index');
	}

	public function changeStatusAction()
	{
		if (!empty($this->_arrParam['status'])/*  && Session::get('token') == $this->_arrParam['token']*/) {
			$this->_model->changeStatus($this->_arrParam, 'status');
		}

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}

	public function changeGroupAcpAction()
	{
		if (!empty($this->_arrParam['status'])/*  && Session::get('token') == $this->_arrParam['token']*/) {
			$this->_model->changeStatus($this->_arrParam, 'group_acp');
		}

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}

	public function deleteAction()
	{
		if (isset($_GET['id'])) $this->_model->deleteItem($_GET['id']);
		Session::set('notification', 'Bài viết đã được xóa thành công!');
		
		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}
}