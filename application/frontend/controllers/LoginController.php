<?php
class LoginController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index2.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
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
}
