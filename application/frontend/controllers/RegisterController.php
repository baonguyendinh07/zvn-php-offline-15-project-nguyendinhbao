<?php
class RegisterController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index2.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
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
