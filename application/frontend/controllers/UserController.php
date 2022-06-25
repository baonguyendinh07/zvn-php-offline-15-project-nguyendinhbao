<?php
class UserController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function loginAction()
	{
		$this->_view->setTitle('BOOKSTORE');
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
				$userInfo = $this->_model->getUserInfo($result);

				$arrSessionUser = [
					'userInfo' => $userInfo,
					'group_acp' => $userInfo['group_acp'],
					'login_time' => time() + LOGIN_TIME
				];

				Session::set('user', $arrSessionUser);
				$returnLink = URL::createLink($this->_arrParam['module'], 'index', 'index');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors(false);
			}
			$this->_view->username = $this->_arrParam['form']['username'] ?? '';
			$this->_view->password = $this->_arrParam['form']['password'] ?? '';
		}

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

		//$this->_view->params = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function orderHistoryAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function changePasswordAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function registerAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			$passwordOptions = ['min' => 12, 'max' => 24];
			$fullNameOptions = ['min' => 8, 'max' => 50];

			$usernameQuery = $this->_model->registerQuery('username', $this->_arrParam['form']['username']);
			$emailQuery    = $this->_model->registerQuery('email', $this->_arrParam['form']['email']);

			$validate = new Validate($this->_arrParam['form']);
			$validate->addRule('username', 'string-notExistRecord', ['database' => $this->_model, 'query' => $usernameQuery, 'min' => 12, 'max' => 24])
				->addRule('email', 'email-notExistRecord', ['database' => $this->_model, 'query' => $emailQuery])
				->addRule('password', 'password', $passwordOptions)
				->addRule('fullname', 'string', $fullNameOptions);

			$validate->run();

			if ($validate->isValid()) {
				$results = $validate->getResult();
				$results['password'] = md5($results['password']);
				$this->_model->saveItem($results);

				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'notice');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}

		$this->_view->data = $this->_arrParam['form'];
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function noticeAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
