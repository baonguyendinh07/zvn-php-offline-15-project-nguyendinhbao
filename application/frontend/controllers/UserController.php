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
		if (!empty(Session::get('user')['login_time']) && Session::get('user')['login_time'] >= time()) {
			$returnLink = URL::createLink($this->_arrParam['module'], 'index', 'index');
			$this->redirect($returnLink);
		}

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

				$query = $this->_model->passwordQuery($username, $password);

				$validateOptions = [
					'database' => $this->_model,
					'query'    => $query
				];

				$validate->addRule('username', 'existRecord', $validateOptions);

				$validate->run();
			}

			if ($validate->isValid()) {
				$result = $validate->getResult();
				$result['password'] = md5($result['password']);
				$userInfo = $this->_model->getUserInfo($result);

				$arrSessionUser = [
					'userInfo' => $userInfo,
					'group_acp' => $userInfo['group_acp'],
					'login_time' => time() + LOGIN_TIME
				];
				Session::unset('notification');
				Session::set('user', $arrSessionUser);
				$returnLink = URL::createLink($this->_arrParam['module'], 'index', 'index');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors(false);
			}
			$this->_view->data = $this->_arrParam['form'] ?? '';
		}

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function logoutAction()
	{
		Session::destroy();
		$this->redirect('login.html');
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

				$params['username'] = $this->_model->getItem($id, true)['username'];
				$params['password'] = $this->_model->getItem($id, true)['password'];
				$userInfo = $this->_model->getUserInfo($params, true);
				$arrSessionUser = [
					'userInfo' => $userInfo,
					'group_acp' => $userInfo['group_acp'],
					'login_time' => Session::get('user')['login_time']
				];
				Session::set('user', $arrSessionUser);

				$this->redirect('index.html');
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}
		$this->_view->params = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function orderHistoryAction()
	{
		$this->_view->setTitle('LỊCH SỬ MUA HÀNG');
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->items = $this->_model->orderHistories(Session::get('user')['userInfo']['username']);

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function cartAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->listCart = !empty(Session::get('cart')) ? $this->_model->listCart(Session::get('cart')) : [];

		if (isset($this->_arrParam['form']) && Session::get('token') == $this->_arrParam['token']) {
			$listCart = !empty($this->_arrParam['form']) ? $this->_model->listCart($this->_arrParam['form']) : [];

			foreach ($listCart as $value) {
				$bookId[]		= $value['id'];
				$prices[]       = $value['price'] * (1 - $value['sale_off'] / 100) . '';
				$quantities[]   = Session::get('cart')[$value['id']] . '';
				$names[]        = $value['name'];
				$pictures[]     = $value['picture'];
			}

			$id             = Helper::randomString(7);
			$username		= Session::get('user')['userInfo']['username'];

			$results = [
				'id' 		=> $id,
				'username' 	=> $username,
				'books'		=> json_encode($bookId),
				'prices'	=> json_encode($prices),
				'quantities' => json_encode($quantities),
				'names'		=> '["' . implode('","', $names) . '"]',
				'pictures'	=> json_encode($pictures)
			];

			$this->_model->saveCart($results, 'add');
			Session::unset('cart');
			Session::set('order', $id);
			$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'orderSuccess');
			$this->redirect($returnLink);
		}
		$this->_view->_arrParam = $this->_arrParam;

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function orderSuccessAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function changePasswordAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
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

			$passwordOptions = ['min' => 12, 'max' => 24];
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
				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'changePassword');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}

		$this->_view->params = $this->_arrParam;

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
				$task = 'add';
				$this->_model->saveItem($results, $task);
				Session::set('register', '1');
				$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'notice');
				$this->redirect($returnLink);
			} else {
				$this->_view->errors = $validate->showErrors();
			}
		}

		$this->_view->data = $this->_arrParam['form'] ?? '';
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function noticeAction()
	{
		$this->_view->setTitle('BOOKSTORE');
		Session::unset('register');
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function tempCartAction()
	{
		if (isset($this->_arrParam['id']) && !empty($this->_model->getBook($this->_arrParam['id']))) {
			$cart = Session::get('cart') ?? [];
			$id   = $this->_arrParam['id'];
			$quantities = $this->_arrParam['quantities'] ?? 1;
			$cart[$id] = $cart[$id] ?? 0;
			$cart[$id] += $quantities;
			if ($cart[$id] < 1) unset($cart[$id]);
			Session::set('cart', $cart);
			$totalQuantities = array_sum(Session::get('cart'));
			echo $totalQuantities;
		}
	}

	public function deleteItemCartAction()
	{
		if (isset($this->_arrParam['id']) && !empty($this->_model->getBook($this->_arrParam['id']))) {
			$cart = Session::get('cart');
			unset($cart[$this->_arrParam['id']]);
			Session::set('cart', $cart);
			$returnLink = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], 'cart');
			$this->redirect($returnLink);
		}
	}
}
