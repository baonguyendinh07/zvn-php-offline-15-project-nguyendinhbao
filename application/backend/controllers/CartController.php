<?php
class CartController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$templateFile = 'login.php';
		if (isset(Session::get('user')['login_time'])) $templateFile = 'index.php';
		$this->_templateObj->setFileTemplate($templateFile);
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function indexAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - List');
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->arrCountItems = $this->_model->countItems($this->_arrParam);
		$this->_view->params = $this->_arrParam;
		$linkParams = [];
		if (isset($this->_arrParam['search-key'])) 	 $linkParams['search-key'] = $this->_arrParam['search-key'];
		if (isset($this->_arrParam['filterStatus'])) $linkParams['filterStatus'] = $this->_arrParam['filterStatus'];

		$pageURL = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], $this->_arrParam['action'], $linkParams);

		if (isset($this->_arrParam['filterStatus'])) $filterStatus = $this->_arrParam['filterStatus'];
		else 										 $filterStatus = 'all';

		$totalItems = $this->_view->arrCountItems[$filterStatus];

		//Pagination
		$this->_arrParam['page'] = isset($this->_arrParam['page']) ? $this->_arrParam['page'] : 1;
		$configPagination = [
			'totalItemsPerPage' => 10,
			'pageRange' => 3,
			'page' => $this->_arrParam['page']
		];

		$this->_view->pagination = new Pagination($totalItems, $configPagination, $pageURL);

		// Show list
		$this->_view->items = $this->_model->listItems($this->_arrParam, $totalItems, $configPagination['totalItemsPerPage']);
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function detailAction()
	{
		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {

			$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
			$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - Detail');
			$this->_view->setUserInfo(Session::get('user'));

			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);

			$this->_view->params = $this->_arrParam;
			$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
		} else {
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
		}
	}

	public function changeStatusAction()
	{
		if (!empty($this->_arrParam['status'])) echo $this->_model->changeStatus($this->_arrParam, 'status');
	}
}
