<?php
class IndexController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function indexAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function loginAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		if (isset($this->_arrParam['form']) && !empty(trim($this->_arrParam['form']['username'])) && !empty(trim($this->_arrParam['form']['password'])) && Session::get('token') == $this->_arrParam['form']['token']) {
			$validate = new Validate($this->_arrParam['form']);
			$username = $this->_arrParam['form']['username'];
			$password = md5($this->_arrParam['form']['password']);
			$query = $this->_model->passwordQuery($username, $password);
			$validateOptions = [
				'database' => $this->_model,
				'query'    => $query
			];
			$validate->addRule('username', 'existRecord', $validateOptions);

			$validate->run();

			if ($validate->isValid()) {
				$result = $validate->getResult();
				$userInfo = $this->_model->getUserInfo($result);
				$arrSessionUser = [
					'login' => true,
					'userInfo' => $userInfo,
					'group_acp' => $userInfo['group_acp'],
					'time' => time()
				];

				Session::set('user', $arrSessionUser);
				$returnLink = URL::createLink($this->_arrParam['module'], 'index', 'index');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors(false);
			}
		}
		$this->_view->username = $this->_arrParam['form']['username'] ?? '';
		$this->_view->password = $this->_arrParam['form']['password'] ?? '';

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function logoutAction()
	{
		Session::unset('user');
		$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'login');
		$this->redirect($returnLink);
	}

	public function registerAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['form']['token']) {
			// username và email chưa tồn tại
			// username và email đủ đk
			// họ tên và password đủ đk

			// Nếu tất cả đúng theo yêu cầu thì:
			// Lưu thông tin vào db
			// status là inactive
			// group_id là 3

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

		$this->_view->username = $this->_arrParam['form']['username'] ?? '';
		$this->_view->fullname = $this->_arrParam['form']['fullname'] ?? '';
		$this->_view->email    = $this->_arrParam['form']['email'] ?? '';
		$this->_view->password = $this->_arrParam['form']['password'] ?? '';

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function noticeAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
