<?php
class BookController extends Controller
{
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate($this->_arrParam['module'] . '/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	public function listAction()
	{
		$this->_view->setTitle('BOOKSTORE - LIST');
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->listSpecialBooks = $this->_model->listSpecialBooks();
		if(isset($this->_arrParam['sort']) && $this->_arrParam['sort'] == 'default') unset($this->_arrParam['sort']);
		$this->_view->totalItems = $this->_model->countItems($this->_arrParam)['active'];

		$linkParams = [];
		if (isset($this->_arrParam['search'])) 	 	$linkParams['search'] = $this->_arrParam['search'];
		if (isset($this->_arrParam['category_id']))	$linkParams['category_id'] = $this->_arrParam['category_id'];
		if (isset($this->_arrParam['sort']))  		$linkParams['sort'] = $this->_arrParam['sort'];

		$pageURL = URL::createLink($this->_arrParam['module'], $this->_arrParam['controller'], $this->_arrParam['action'], $linkParams);

		//Pagination
		$this->_arrParam['page'] = isset($this->_arrParam['page']) ? $this->_arrParam['page'] : 1;

		$configPagination = [
			'totalItemsPerPage' => 6,
			'pageRange' => 5,
			'page' => $this->_arrParam['page']
		];

		$this->_view->pagination = new Pagination($this->_view->totalItems, $configPagination, $pageURL);

		// Show list
		$this->_view->listTypeBooks = $this->_model->listItems($this->_arrParam, $this->_view->totalItems, $configPagination['totalItemsPerPage']);

		$this->_view->countResults = '<h5>0 of Result</h5>';
		if (!empty($this->_view->totalItems)) {
			$fromElement = $this->_model->getFromElement() + 1;
			$toElement   = $this->_model->getFromElement() + count($this->_view->listTypeBooks);

			$this->_view->countResults = "<h5>Showing Items $fromElement - $toElement of {$this->_view->totalItems} Results</h5>";
		}

		$this->_view->_arrParam = $this->_arrParam;
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function itemAction()
	{
		$this->_view->setTitle('BOOKSTORE - ITEM');
		$this->_view->setUserInfo(Session::get('user'));
		$this->_view->setTitlePageHeader('Sản phẩm');

		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {
			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);
			$whereSpecialBooks = "`status`='active' AND `special`='1' AND `id`!='$id'";
			$this->_view->listSpecialBooks = $this->_model->listSpecialBooks($whereSpecialBooks);

			$whereNewBooks = "`status`='active' AND `id`!='$id' ORDER BY `id` DESC LIMIT 6";
			$this->_view->listNewBooks = $this->_model->listSpecialBooks($whereNewBooks);

			$categoryId =  $this->_view->listSpecialBooks[0]['category_id'];

			$whereTypeBooks = "`status`='active' AND `category_id`='$categoryId' AND `id`!='$id' ORDER BY RAND() LIMIT 6";
			$this->_view->listTypeBooks = $this->_model->listSpecialBooks($whereTypeBooks);
		} elseif (isset($this->_arrParam['id']) && empty($this->_model->getItem($this->_arrParam['id']))) {
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
		}

		$this->_view->_arrParam = $this->_arrParam;
		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}

	public function quickViewAction()
	{
		if (isset($this->_arrParam['id']) && !empty($this->_model->getItem($this->_arrParam['id']))) {
			$id = $this->_arrParam['id'];
			$this->_view->data = $this->_model->getItem($id);
			$id = $this->_view->data['id'];
			$name = $this->_view->data['name'];
			$shortDescription = $this->_view->data['short_description'];

			$pathBookPicture = FILES_URL . 'book' . DS;
			$picture = $pathBookPicture . $this->_view->data['picture'];

			$price = $this->_view->data['price'];
			$saleOff = $this->_view->data['sale_off'];
			$itemLink = URL::createLink($this->_arrParam['module'], 'book', 'item', ['id' => $id]);

			if ($saleOff > 0) {
				$price     = '
				<h3 class="book-price">
				' . number_format($price * (100 - $saleOff) / 100) . ' ₫ 
				<del>' . number_format($price) . ' ₫</del>
				</h3>
				';
			} else {
				$price    = '<h3 class="book-price">' . number_format($price) . ' đ</h3>';
			}

			$result = '
				<div class="col-lg-6 col-xs-12">
					<div class="quick-view-img"><img src="' . $picture . '" alt="" class="w-100 img-fluid blur-up lazyload book-picture">
					</div>
				</div>
				<div class="col-lg-6 rtl-text">
					<div class="product-right">
						<h2 class="book-name">' . $name . '</h2>
						' . $price . '
						<div class="border-product">
							<div class="book-description">' . $shortDescription . '</div>
						</div>
						<div class="product-description border-product">
							<h6 class="product-title">Số lượng</h6>
							<div class="qty-box">
								<div class="input-group">
									<span class="input-group-prepend">
										<button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
											<i class="ti-angle-left"></i>
										</button>
									</span>
									<input type="text" name="quantity" class="form-control input-number" value="1">
									<span class="input-group-prepend">
										<button type="button" class="btn quantity-right-plus" data-type="plus" data-field="">
											<i class="ti-angle-right"></i>
										</button>
									</span>
								</div>
							</div>
						</div>
						<div class="product-buttons">
							<a href="" class="btn btn-solid mb-1 btn-add-to-cart">Chọn Mua</a>
							<a href="' . $itemLink . '" class="btn btn-solid mb-1 btn-view-book-detail">Xem chi tiết</a>
						</div>
					</div>
				</div>';
			echo $result;
		} elseif (isset($this->_arrParam['id']) && empty($this->_model->getItem($this->_arrParam['id']))) {
			require_once APPLICATION_PATH . $this->_arrParam['module'] . DS . 'controllers' . DS . 'ErrorController.php';
			$this->error = new ErrorController($this->_arrParam);
			$this->error->errorAction();
		}
	}

	public function categoryAction()
	{
		$this->_view->setTitle('DANH MỤC SÁCH');
		$this->_view->setUserInfo(Session::get('user'));

		$this->_view->render($this->_arrParam['controller'] . '/' . $this->_arrParam['action']);
	}
}
