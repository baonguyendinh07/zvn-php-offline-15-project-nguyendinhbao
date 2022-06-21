<?php
class Pagination
{

	private $totalItems;					// Tổng số phần tử
	private $totalItemsPerPage		= 1;	// Tổng số phần tử xuất hiện trên một trang
	private $pageRange				= 5;	// Số trang xuất hiện
	private $totalPage;						// Tổng số trang
	private $currentPage			= 1;	// Trang hiện tại
	private $path;							// URL của trang

	public function __construct($totalItems, $pagination, $path = '')
	{
		$this->totalItems			= $totalItems;
		$this->totalItemsPerPage	= $pagination['totalItemsPerPage'];

		if ($pagination['pageRange'] % 2 == 0) $pagination['pageRange'] = $pagination['pageRange'] + 1;

		$this->pageRange			= $pagination['pageRange'];
		$this->totalPage			= ceil($totalItems / $pagination['totalItemsPerPage']);
		if (isset($pagination['page']) && $pagination['page'] >= 1 && $pagination['page'] <= $this->totalPage) {
			$this->currentPage = $pagination['page'];
		} else {
			$this->currentPage = 1;
		}

		$this->path					= $path;
	}

	public function getTotalItem() {
		return $this->totalItems;
	}

	public function showPagination()
	{
		// Pagination
		$paginationHTML = '';
		if ($this->totalPage > 1) {
			$start 	= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
			$prev 	= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-left"></i></a></li>';

			if ($this->currentPage > 1) {
				$start 	= '<li class="page-item"><a class="page-link" href="' . $this->path . '&page=1"><i class="fas fa-angle-double-left"></i></a></li>';
				$prev 	= '<li class="page-item"><a class="page-link" href="' . $this->path . '&page=' . ($this->currentPage - 1) . '"><i class="fas fa-angle-left"></i></a></li>';
			}

			$next = '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-right"></i></a></li></li>';
			$end  = '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';

			if ($this->currentPage < $this->totalPage) {
				$next     = '<li class="page-item"><a class="page-link" href="' . $this->path . '&page=' . ($this->currentPage + 1) . '"><i class="fas fa-angle-right"></i></a></li>';
				$end     = '<li class="page-item"><a class="page-link" href="' . $this->path . '&page=' . $this->totalPage . '"><i class="fas fa-angle-double-right"></i></a></li>';
			}

			if ($this->pageRange < $this->totalPage) {
				if ($this->currentPage == 1) {
					$startPage 	= 1;
					$endPage 	= $this->pageRange;
				} else if ($this->currentPage == $this->totalPage) {
					$startPage		= $this->totalPage - $this->pageRange + 1;
					$endPage		= $this->totalPage;
				} else {
					$startPage		= $this->currentPage - ($this->pageRange - 1) / 2;
					$endPage		= $this->currentPage + ($this->pageRange - 1) / 2;

					if ($startPage < 1) {
						$endPage	= $endPage + 1;
						$startPage = 1;
					}

					if ($endPage > $this->totalPage) {
						$endPage	= $this->totalPage;
						$startPage 	= $endPage - $this->pageRange + 1;
					}
				}
			} else {
				$startPage		= 1;
				$endPage		= $this->totalPage;
			}

			$listPages = '';
			for ($i = $startPage; $i <= $endPage; $i++) {
				if ($i == $this->currentPage) {
					$listPages .= '<li class="page-item active"><a class="page-link" href="' . $this->path . '&page=' . $i . '">' . $i . '</a>';
				} else {
					$listPages .= '<li class="page-item"><a class="page-link" href="' . $this->path . '&page=' . $i . '">' . $i . '</a>';
				}
			}

			$paginationHTML = '<ul class="pagination m-0 float-right">' . $start . $prev . $listPages . $next . $end . '</ul>';
		}
		return $paginationHTML;
	}
}