<?php
class BookController extends Controller
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
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->arrCountItems = $this->_model->countItems($this->_arrParam);
		$this->_view->params = $this->_arrParam;
		$linkParams = [];
		if (isset($this->_arrParam['search-key'])) 	 $linkParams['search-key'] = $this->_arrParam['search-key'];
		if (isset($this->_arrParam['filterStatus'])) $linkParams['filterStatus'] = $this->_arrParam['filterStatus'];
		if (isset($this->_arrParam['category_id']))  $linkParams['category_id'] = $this->_arrParam['category_id'];
		if (isset($this->_arrParam['special'])) {
			$linkParams['special'] = $this->_arrParam['special'];
		}

		$pageURL = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], $this->_arrParam['action'], $linkParams);

		if (isset($this->_arrParam['filterStatus'])) $filterStatus = $this->_arrParam['filterStatus'];
		else 										 $filterStatus = 'all';

		$totalItems = $this->_view->arrCountItems[$filterStatus];

		//Pagination
		$this->_arrParam['page'] = isset($this->_arrParam['page']) ? $this->_arrParam['page'] : 1;
		$configPagination = [
			'totalItemsPerPage' => 10,
			'pageRange' => 3
		];
		$this->setPagination($configPagination);
		$this->_view->pagination = new Pagination($totalItems, $this->_pagination, $pageURL);

		// Show list
		$this->_view->items = $this->_model->listItems($this->_arrParam, $totalItems, $this->_pagination['totalItemsPerPage']);
		$this->_view->categoryOptions = Helper::convertArrList($this->_model->getListCategory());

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function formAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->categoryOptions = Helper::convertArrList($this->_model->getListCategory());

		$this->_view->pictureXHTML = '';

		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {
			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);

			$pictureImg			= '';
			$hiddenPictureName 	= '';
			if (!empty($this->_view->data['picture'])) {
				$pictureImg = '<img src="' . FILES_URL . $this->_arrParam['controller'] . DS . $this->_view->data['picture'] . '" style="width: 60px">';

				$hiddenPictureName = Form::input('hidden', 'form[hiddenPictureName]', $this->_view->data['picture']);
			}

			$this->_view->pictureXHTML = $pictureImg . $hiddenPictureName;
		} elseif (isset($this->_arrParam['id']) && empty($this->_model->getItem($this->_arrParam['id']))) {
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
		}

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			if (!empty($_FILES['picture'])) $this->_arrParam['form']['picture'] = $_FILES['picture'];

			$this->_view->data = $this->_arrParam['form'];

			$validate = new Validate($this->_view->data);

			$i = 0;
			foreach ($this->_view->categoryOptions as $key => $value) {
				$categoryOptions[$i] = $key;
				$i++;
			}

			$validate->addRule('name', 'string', ['min' => 10, 'max' => 100])
				->addRule('status', 'status', ['active', 'inactive'])
				->addRule('price', 'int', ['min' => 1, 'max' => 100000000])
				->addRule('category_id', 'group', $categoryOptions);

			if (!empty($this->_arrParam['form']['picture']['name'])) {
				$pictureOptions =
					[
						'min' => 100,
						'max' => 1000000,
						'extension' => ['jpg', 'jpeg', 'png'],
						'fileType'	=> 'image'
					];
				$validate->addRule('picture', 'file', $pictureOptions, false);
			}

			if (!empty($this->_arrParam['form']['sale_off'])) {
				$validate->addRule('sale_off', 'int', ['min' => 1, 'max' => 100]);
			}

			if ($this->_arrParam['form']['special'] != 'default') {
				$validate->addRule('special', 'status', [0, 1]);
			}

			if (!empty($this->_arrParam['form']['ordering'])) {
				$validate->addRule('ordering', 'int', ['min' => 1, 'max' => 100]);
			}

			if (!empty($this->_arrParam['form']['short_description'])) {
				$validate->addRule('short_description', 'string', ['min' => 10, 'max' => 1000]);
			}

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();

				$task = 'add';
				if (isset($results['id']) && !empty(trim($results['id']))) {
					$results['id'] = $id;
					$task = 'edit';
				}

				$this->_model->saveItem($results, $task);
				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
				$this->redirect($returnLink);
				$this->_view->data = [];
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}
		$this->_view->params = $this->_arrParam;
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function changeStatusAction()
	{
		if (!empty($this->_arrParam['status'])) echo $this->_model->changeStatus($this->_arrParam, 'status');
	}

	public function changeCategoryIdAction()
	{
		if (!empty($this->_arrParam['category_id'])) echo $this->_model->changeStatus($this->_arrParam, 'category_id');
	}

	public function changeSpecialAction()
	{
		if (isset($this->_arrParam['special'])) echo $this->_model->changeStatus($this->_arrParam, 'special');
	}

	public function changeOrderingAction()
	{
		if (!empty($this->_arrParam['ordering'])) echo $this->_model->changeStatus($this->_arrParam, 'ordering');
	}

	public function deleteAction()
	{
		if (isset($this->_arrParam['id'])) $this->_model->delete([$this->_arrParam['id']]);

		if (isset($this->_arrParam['picture']) && !empty($this->_arrParam['picture'])) {
			Upload::removeFile($this->_arrParam['controller'], $this->_arrParam['picture']);
		}
		Session::set('notification', 'đã được xóa thành công!');

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}
}
