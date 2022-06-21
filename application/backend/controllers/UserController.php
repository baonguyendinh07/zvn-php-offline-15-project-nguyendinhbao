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

		if (!empty($this->_arrParam['id']) || !empty($this->_arrParam['form']['id'])) {
			$paramId = !empty($this->_arrParam['id']) ? $this->_arrParam : $this->_arrParam['form'];

			$this->_view->data = $this->_model->getItem($paramId);
			$this->_view->inputUsername = '<p class="form-control btn-light">' . $this->_view->data['username'] . '</p>';
			$this->_view->inputEmail 	= '<p class="form-control btn-light">' . $this->_view->data['email'] . '</p>';
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
			$usernameOptions = ['min' => 12, 'max' => 24];
			$passwordOptions = ['min' => 12, 'max' => 24];
			$fullNameOptions = ['min' => 3, 'max' => 50];

			if (!empty($this->_arrParam['form']['id'])) {
				$validate->addRule('password', 'password', $passwordOptions)
					->addRule('fullname', 'string', $fullNameOptions)
					->addRule('status', 'status', ['active', 'inactive'])
					->addRule('group_id', 'group', $groupOptions);
			} else {
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

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function changePasswordAction()
	{
		if ($this->_arrParam['action'] == 'changePassword' && isset($this->_arrParam['id']) && !empty(trim(($this->_arrParam['id'])))) {

			$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - Reset Password');
			$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - Reset Password');
			$this->_view->setUserInfo(Session::get('user'));
			
			$this->_view->data = $this->_model->getItem($this->_arrParam);

			if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
				$validate = new Validate($this->_arrParam['form']);
				$passwordOptions = ['min' => 12, 'max' => 24];

				$validate->addRule('password', 'password', $passwordOptions);

				$validate->run();

				if ($validate->isValid()) {
					$results = $validate->getResult();
					$results['password'] = md5($results['password']);
					$task = 'edit';
					$this->_model->saveItem($results, $task);
					$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
					$this->redirect($returnLink);
				} else {
					$this->_view->errors = $validate->showErrors();
				}
			}

			$this->_view->params = $this->_arrParam;
			$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
		} else {
			$this->redirect(URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index'));
		}
	}

	public function randomPasswordAction()
	{
		echo Helper::randomString(12);
	}

	public function changeStatusAction()
	{
		if (!empty($this->_arrParam['status'])) echo $this->_model->changeStatus($this->_arrParam, 'status');
	}

	public function changeGroupIdAction()
	{
		if (is_numeric($this->_arrParam['group_id'])) {
			echo $this->_model->changeStatus($this->_arrParam, 'group_id');
		}
	}

	public function deleteAction()
	{
		if (isset($_GET['id'])) $this->_model->deleteItem($_GET['id']);
		Session::set('notification', 'đã được xóa thành công!');

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}
}
