<?php
class UserController extends Controller
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

		$this->_view->groupOptions = Helper::convertArrList($this->_model->getListGroup());

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

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$this->_view->data = $this->_arrParam['form'];
			$validate = new Validate($this->_view->data);

			$passwordOptions = ['min' => 8, 'max' => 24];
			$fullNameOptions = ['min' => 3, 'max' => 50];

			$i = 0;
			foreach ($this->_view->groupOptions as $key => $value) {
				$groupOptions[$i] = $key;
				$i++;
			}

			if (!empty($this->_arrParam['form']['id'])) {
				if (!empty(trim($this->_arrParam['form']['password']))) {
					$validate->addRule('password', 'password', $passwordOptions);
				}
			} else {
				$usernameOptions = ['min' => 12, 'max' => 24];
				$validate->addRule('username', 'username', $usernameOptions)
					->addRule('password', 'password', $passwordOptions)
					->addRule('email', 'email');
			}

			$validate->addRule('fullname', 'string', $fullNameOptions)
				->addRule('status', 'status', ['active', 'inactive'])
				->addRule('group_id', 'group', $groupOptions);

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();
				$results['password'] = md5($results['password']);
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

	public function changePasswordAction()
	{
		$id = $this->_arrParam['id'];
		$this->_view->data = $this->_model->getItem($this->_arrParam['id']);
		if (!empty($this->_view->data)) {

			$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - Reset Password');
			$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - Reset Password');
			$this->_view->setUserInfo(Session::get('user'));

			if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
				$validate = new Validate($this->_arrParam['form']);
				$passwordOptions = ['min' => 8, 'max' => 24];

				$validate->addRule('password', 'password', $passwordOptions);

				$validate->run();

				if ($validate->isValid()) {
					$results = $validate->getResult();
					$results['id'] = $id;
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
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
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
		if (!empty($this->_arrParam['group_id'])) echo $this->_model->changeStatus($this->_arrParam, 'group_id');
	}

	public function loginAction()
	{
		if (!empty(Session::get('user')['login_time']) && Session::get('user')['login_time'] >= time()) {
			$returnLink = URL::createLink($this->_arrParam['module'], 'user', 'index');
			$this->redirect($returnLink);
		}

		$this->_view->setTitle('Amin login');
		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$validate = new Validate($this->_arrParam['form']);

			if (empty(trim($this->_arrParam['form']['username']))) {
				$validate->setError('username', 'Username không được bỏ trống');
			}

			if (empty(trim($this->_arrParam['form']['password']))) {
				$validate->setError('password', 'Password không được bỏ trống');
			}

			if ($validate->isValid()) {
				$username = $this->_arrParam['form']['username'];
				$password = md5($this->_arrParam['form']['password']);

				$query = $this->_model->passwordQuery($username, $password, true);
				$validateOptions = [
					'database' => $this->_model,
					'query'    => $query
				];
				$validate->addRule('username', 'existRecord', $validateOptions);

				$validate->run();
			}

			if ($validate->isValid()) {
				$result = $validate->getResult();
				$userInfo = $this->_model->getUserInfo($result, true);
				$arrSessionUser = [
					'userInfo' => $userInfo,
					'group_acp' => $userInfo['group_acp'],
					'login_time' => time() + LOGIN_TIME
				];
				Session::set('user', $arrSessionUser);
				$returnLink = URL::createLink($this->_arrParam['module'], 'user', 'index');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors(false);
			}
		}
		$this->_view->data = $this->_arrParam['form'] ?? '';
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function logoutAction()
	{
		Session::unset('user');
		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login');
		$this->redirect($returnLink);
	}

	public function profileAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$id = Session::get('user')['userInfo']['group_id'];
		$this->_view->data = $this->_model->getItem($id, true);
		$this->_view->inputUsername = '<p class="form-control btn-blue">' . $this->_view->data['username'] . '</p>';
		$this->_view->inputEmail 	= '<p class="form-control btn-blue">' . $this->_view->data['email'] . '</p>';

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$this->_view->data = $this->_arrParam['form'];
			$validate = new Validate($this->_view->data);
			$fullNameOptions = ['min' => 3, 'max' => 50];
			$birthdayOptions = ['start' => '1900-01-01', 'end' => '2015-01-01'];
			$phoneNumberOptions  = ['min' => 9, 'max' => 15];
			$addressOptions  = ['min' => 10, 'max' => 500];

			$validate->addRule('fullname', 'string', $fullNameOptions);
			if (!empty(trim($this->_arrParam['form']['birthday']))) {
				$validate->addRule('birthday', 'date', $birthdayOptions);
			}
			if (!empty(trim($this->_arrParam['form']['phone_number']))) {
				$validate->addRule('phone_number', 'string', $phoneNumberOptions);
			}
			if (!empty(trim($this->_arrParam['form']['address']))) {
				$validate->addRule('address', 'string', $addressOptions);
			}

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();
				$results['id'] = $id;
				unset($results['password']);
				$task = 'edit';
				$this->_model->saveItem($results, $task);
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}
		$this->_view->params = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function changeAccountPasswordAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - Change Password');
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - Change Password');
		$this->_view->setUserInfo(Session::get('user'));

		if (isset($this->_arrParam['form']) && !empty($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$id = Session::get('user')['userInfo']['group_id'];
			$currentPassword = $this->_model->getItem($id, true)['password'];
			$oldPassword = md5($this->_arrParam['form']['old_password']);
			$password = $this->_arrParam['form']['password'];
			$confirmPassword = $this->_arrParam['form']['confirm_password'];

			$this->_view->data = $this->_arrParam['form'];
			$validate = new Validate($this->_view->data);

			if (empty(trim($oldPassword))) {
				$validate->setError('Mật khẩu cũ', 'không được bỏ trống');
			} elseif ($oldPassword != $currentPassword) {
				$validate->setError('Mật khẩu cũ', 'không đúng');
			}

			$passwordOptions = ['min' => 8, 'max' => 24];
			$validate->addRule('password', 'password', $passwordOptions);
			$validate->run();

			if (empty(trim($confirmPassword))) {
				$validate->setError('Mật khẩu xác nhận', 'không được bỏ trống');
			} elseif ($confirmPassword != $password) {
				$validate->setError('Mật khẩu xác nhận', 'không chính xác');
			}

			if ($validate->isValid()) {
				$results['password'] = md5($validate->getResult()['password']);
				$results['id'] = $id;
				$task = 'edit';
				$this->_model->saveItem($results, $task);
				Session::set('notificationElement', 'Mật khẩu của bạn');
				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'profile');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}
		$this->_view->params = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function deleteAction()
	{
		if (isset($this->_arrParam['id'])) $this->_model->delete([$this->_arrParam['id']]);
		Session::set('notification', 'đã được xóa thành công!');

		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
		$this->redirect($returnLink);
	}
}
