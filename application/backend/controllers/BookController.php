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
		if (isset($this->_arrParam['group_id'])) 	 $linkParams['group_id'] = $this->_arrParam['group_id'];
		$pageURL = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], $this->_arrParam['action'], $linkParams);

		if (isset($this->_arrParam['filterStatus'])) $filterStatus = $this->_arrParam['filterStatus'];
		else 										 $filterStatus = 'all';

		$totalItems = $this->_view->arrCountItems[$filterStatus];

		//Pagination
		$this->_arrParam['page'] = isset($this->_arrParam['page']) ? $this->_arrParam['page'] : 1;
		$configPagination = [
			'totalItemsPerPage' => 3,
			'pageRange' => 3
		];
		$this->setPagination($configPagination);
		$this->_view->pagination = new Pagination($totalItems, $this->_pagination, $pageURL);

		// Show list
		$this->_view->items = $this->_model->listItems($this->_arrParam, $totalItems, $this->_pagination['totalItemsPerPage']);
		$this->_view->groupOptions = Helper::convertArrList($this->_model->getListGroup());

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function formAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->inputUsername = Form::input('text', 'form[username]', $this->_arrParam['form']['username'] ?? '');
		$this->_view->inputEmail    = Form::input('text', 'form[email]', $this->_arrParam['form']['email'] ?? '');
		$this->_view->lblPassword = Form::label('Password', 'form-label fw-bold');

		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {
			// Chỉ lấy id có group_id lớn hơn group_id hiện tại đăng nhập
			// => kiểm tra gruop_id arrParam['id'], nếu true thì tiếp tục, nếu false thì quay về trang error
			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);
			$this->_view->inputUsername = '<p class="form-control btn-blue">' . $this->_view->data['username'] . '</p>';
			$this->_view->inputEmail 	= '<p class="form-control btn-blue">' . $this->_view->data['email'] . '</p>';
			$this->_view->lblPassword = Form::label('Password', 'form-label fw-bold', false);
		} elseif (isset($this->_arrParam['id']) && empty($this->_model->getItem($this->_arrParam['id']))) {
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
		}

		$this->_view->groupOptions = Helper::convertArrList($this->_model->getListGroup());

		$i = 0;
		foreach ($this->_view->groupOptions as $key => $value) {
			$groupOptions[$i] = $key;
			$i++;
		}

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$this->_view->data = $this->_arrParam['form'];
			$validate = new Validate($this->_view->data);

			$passwordOptions = ['min' => 8, 'max' => 24];
			$fullNameOptions = ['min' => 3, 'max' => 50];

			if (!empty($this->_arrParam['form']['id'])) {
				if (!empty(trim($this->_arrParam['form']['password']))) {
					$validate->addRule('password', 'password', $passwordOptions);
				}
				$validate->addRule('fullname', 'string', $fullNameOptions)
					->addRule('status', 'status', ['active', 'inactive'])
					->addRule('group_id', 'group', $groupOptions);
			} else {
				$usernameOptions = ['min' => 12, 'max' => 24];
				$validate->addRule('username', 'username', $usernameOptions)
					->addRule('password', 'password', $passwordOptions)
					->addRule('email', 'email')
					->addRule('fullname', 'string', $fullNameOptions)
					->addRule('status', 'status', ['active', 'inactive'])
					->addRule('group_id', 'group', $groupOptions);
			}

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();
				$results['password'] = md5($results['password']);
				$task = 'add';
				if (!empty(trim($results['id']))) {
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

	public function changeGroupIdAction()
	{
		if (!empty($this->_arrParam['group_id'])) echo $this->_model->changeStatus($this->_arrParam, 'group_id');
	}

	public function deleteAction()
	{
		if (isset($_GET['id'])) $this->_model->deleteItem($_GET['id']);
		Session::set('notification', 'đã được xóa thành công!');

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}
}
