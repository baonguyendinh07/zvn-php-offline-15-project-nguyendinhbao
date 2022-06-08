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
		$this->_view->setTitle('Index');
		//$this->_view->totalItems = $this->_model->countItems($this->_arrParam);
		//$this->_arrParam['status'] = 'active';
		//$this->_view->totalActiveStatus = $this->_model->countItems();
		//$this->_arrParam['status'] = 'inactive';
		$this->_view->arrCountItems = $this->_model->countItems();
		$this->_view->items = $this->_model->listItems($this->_arrParam);
		$this->_view->params = $this->_arrParam;

		$this->_view->render('group/index');
	}

	public function formAction()
	{
		$this->_view->setTitle('Form');
		$task = 'add';
		if (!empty($this->_arrParam['id'])) $this->_view->data = $this->_model->getItem($this->_arrParam);

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$source = $this->_arrParam['form'];

			$validate = new Validate($source);
			$stringOptions = ['min' => 3, 'max' => 100];

			$validate->addRule('name', 'string', $stringOptions)
				->addRule('group_acp', 'status', [0, 1])
				->addRule('status', 'status', ['active', 'inactive']);

			$validate->run();

			$this->_view->errors = $validate->getError();

			$this->_view->data = $source;

			if ($validate->isValid()) {
				$results = $validate->getResult();

				if (!empty($results['id'])) {
					$task = 'edit';
					$this->_model->saveItem($results, $task);
					$this->_arrParam['action'] = 'index';
					$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
					$this->redirect($returnLink);
				} else {
					$this->_model->saveItem($results, $task);
				}
				$this->_view->data = [];
			}
		}

		$this->_view->params = $this->_arrParam;

		$this->_view->render('group/form');
	}

	public function changeStatusAction()
	{
		if (!empty($this->_arrParam['status'])  && Session::get('token') == $this->_arrParam['token']) {
			$this->_model->changeStatus($this->_arrParam, 'status');
		}

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}

	public function changeGroupAcpAction()
	{
		if (is_numeric($this->_arrParam['status'])  && Session::get('token') == $this->_arrParam['token']) {
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
