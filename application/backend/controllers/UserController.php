<?php
class UserController extends Controller
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

		$totalItemsPerPage = 3;
		$pageRange = 3;

		$this->_view->arrCountItems = $this->_model->countItems($this->_arrParam);
		$this->_view->params = $this->_arrParam;
		if ($this->_view->arrCountItems['all'] > 0) {
			$searchURL = '';
			$this->_arrParam['search-key'] = $_GET['search-key'] ?? '';
			if (isset($this->_arrParam['search-key']) && !empty(trim($this->_arrParam['search-key']))) {
				$searchURL = 'search-key=' . $this->_arrParam['search-key'] . '&';
			}

			$this->_arrParam['page'] = $this->_arrParam['page'] ?? 1;
			$filterStatusURL = '';
			if (isset($this->_arrParam['filterStatus']) && !empty(trim($this->_arrParam['filterStatus']))) {
				$filterStatusURL = 'filterStatus=' . $this->_arrParam['filterStatus'] . '&';
			}

			$path = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index') . '&' . $searchURL . $filterStatusURL;
			$filterStatus = $this->_arrParam['filterStatus'] ?? 'all';
			$totalItems = $this->_view->arrCountItems[$filterStatus];

			$pagination = new Pagination($totalItems, $totalItemsPerPage, $pageRange, $this->_arrParam['page'], $path);
			$this->_view->pagination = $pagination->showPagination();

			$this->_view->items = $this->_model->listItems($this->_arrParam, $totalItems, $totalItemsPerPage);
		}
		$this->_view->render($this->_arrParam['controller'] . '/index');
	}

	public function formAction()
	{
		$this->_view->setTitle('Form');
		if (!empty($this->_arrParam['id'])) $this->_view->data = $this->_model->getItem($this->_arrParam);

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$this->_view->data = $this->_arrParam['form'];

			$validate = new Validate($this->_view->data);
			$stringOptions = ['min' => 3, 'max' => 100];

			$validate->addRule('name', 'string', $stringOptions)
				->addRule('group_id', 'status', [0, 1])
				->addRule('status', 'status', ['active', 'inactive']);

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();
				$task = !empty($results['id']) ? 'edit' : 'add';
				$this->_model->saveItem($results, $task);
				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
				$this->redirect($returnLink);
				$this->_view->data = [];
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}
		$this->_view->params = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/form');
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