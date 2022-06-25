<?php
class Bootstrap
{

	private $_params;
	private $_controllerObject;
	private $filePath;

	public function init()
	{
		$this->setParam();

		$controllerName	= ucfirst($this->_params['controller']) . 'Controller';
		$this->filePath	= APPLICATION_PATH . $this->_params['module'] . DS . 'controllers' . DS . $controllerName . '.php';

		if (file_exists($this->filePath)) {
			$this->loadExistingController($this->filePath, $controllerName);
		}
		$this->callMethod();
	}

	// CALL METHODE
	private function callMethod()
	{
		$actionName = $this->_params['action'] . 'Action';
		$module 	= $this->_params['module'];
		$controller = $this->_params['controller'];
		$action 	= $this->_params['action'];

		$userInfo = Session::get('user') ?? '';
		$logged = false;
		if (!empty($userInfo)) {
			if($userInfo['login_time'] >= time()) $logged = true;
			if ($logged == false) Session::unset('user');
		}

		if ($logged == true && $userInfo['group_acp'] == 0) {
			if ($module != 'frontend' || !file_exists($this->filePath) || !method_exists($this->_controllerObject, $actionName)) {
				$this->_params['module'] = 'frontend';
				$this->_error();
			}
		} elseif ($logged == true) {
			if (!method_exists($this->_controllerObject, $actionName) || $action == 'login' || $action == 'register') {
				$this->_error();
			}
		} else {
			if ($module == 'backend' && ($controller != 'user' || $action != 'login')) {
				$this->_params['module'] = 'frontend';
				$this->_error();
			} elseif (!file_exists($this->filePath) || !method_exists($this->_controllerObject, $actionName)) {
				$this->_error();
			} elseif ($controller == 'user' && $action != 'login' && $action != 'register') {
				$this->_error();
			}
		}

		$this->_controllerObject->$actionName();
		exit();
	}

	// SET PARAMS
	public function setParam()
	{
		$this->_params 	= array_merge($_GET, $_POST);
		$this->_params['module'] 		= isset($this->_params['module']) ? $this->_params['module'] : DEFAULT_MODULE;
		$this->_params['controller'] 	= isset($this->_params['controller']) ? $this->_params['controller'] : DEFAULT_CONTROLLER;
		$this->_params['action'] 		= isset($this->_params['action']) ? $this->_params['action'] : DEFAULT_ACTION;
	}

	// LOAD EXISTING CONTROLLER
	private function loadExistingController($filePath, $controllerName)
	{
		require_once $filePath;
		$this->_controllerObject = new $controllerName($this->_params);
	}

	// ERROR CONTROLLER
	public function _error()
	{
		if ($this->_params['module'] != 'frontend' && $this->_params['module'] != 'backend') {
			$this->_params['module'] = 'frontend';
		}
		require_once APPLICATION_PATH . $this->_params['module'] . DS . 'controllers' . DS . 'ErrorController.php';
		$this->_controllerObject = new ErrorController($this->_params);
		$this->_controllerObject->errorAction();
		exit();
	}
}
