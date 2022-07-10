<?php
class SliderController extends Controller
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

		// Show list
		$this->_view->items = $this->_model->listItems($this->_arrParam);

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function formAction()
	{
		$this->_view->setTitle(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setTitlePageHeader(ucfirst($this->_arrParam['controller']) . ' - ' . ucfirst($this->_arrParam['action']));
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->pictureXHTML = '';

		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {
			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);

			$pictureImg			= '';
			$hiddenPictureName 	= '';
			if (!empty($this->_view->data['picture'])) {
				$pictureImg = '<img src="' . FILES_URL . $this->_arrParam['controller'] . DS . $this->_view->data['picture'] . '" style="width:700px">';

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

			$validate->addRule('name', 'string', ['min' => 10, 'max' => 100])
				->addRule('status', 'status', ['active', 'inactive']);

			if (!empty($this->_arrParam['form']['description'])) {
				$validate->addRule('description', 'string', ['min' => 50, 'max' => 500]);
			}

			if (!empty($this->_arrParam['form']['link'])) {
				$validate->addRule('link', 'url');
			}

			if (!empty($this->_arrParam['form']['ordering'])) {
				$validate->addRule('ordering', 'int', ['min' => 1, 'max' => 100]);
			}
			if (!empty($this->_arrParam['form']['picture']['name'])) {
				$pictureOptions =
					[
						'min' => 100,
						'max' => 1500000,
						'extension' => ['jpg', 'jpeg', 'png'],
						'fileType'	=> 'image'
					];
				$validate->addRule('picture', 'file', $pictureOptions, false);
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
				$this->redirect('slider-index');
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
