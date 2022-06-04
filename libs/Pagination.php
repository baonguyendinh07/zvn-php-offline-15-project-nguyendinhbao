<?php
class Pagination
{

	private $totalItems;					// Tổng số phần tử
	private $totalItemsPerPage		= 1;	// Tổng số phần tử xuất hiện trên một trang
	private $pageRange				= 5;	// Số trang xuất hiện
	private $totalPage;						// Tổng số trang
	private $currentPage			= 1;	// Trang hiện tại
	private $path;							// URL của trang

	public function __construct($totalItems, $totalItemsPerPage = 1, $pageRange = 3, $currentPage = 1, $path = '')
	{
		$this->totalItems			= $totalItems;
		$this->totalItemsPerPage	= $totalItemsPerPage;

		if ($pageRange % 2 == 0) $pageRange = $pageRange + 1;

		$this->pageRange			= $pageRange;
		$this->totalPage			= ceil($totalItems / $totalItemsPerPage);
		if ($currentPage >= 1 && $currentPage <= $this->totalPage) {
			$this->currentPage = $currentPage;
		} else {
			$this->currentPage = $this->totalPage;
		}

		$this->path					= $path;
	}

	public function showPagination()
	{
		// Pagination
		$paginationHTML = '';
		if ($this->totalPage > 1) {
			$start 	= '<li class="page-item disabled"><a class="page-link">Start</a></li>';
			$prev 	= '<li class="page-item disabled"><a class="page-link">Previous</a></li>';
			if ($this->currentPage > 1) {
				$start 	= '<li class="page-item"><a class="page-link" href="' . $this->path . 'page=1">Start</a></li>';
				$prev 	= '<li class="page-item"><a class="page-link" href="' . $this->path . 'page=' . ($this->currentPage - 1) . '">Previous</a></li>';
			}

			$next     = '<li class="page-item disabled"><a class="page-link">Next</a></li></li>';
			$end     = '<li class="page-item disabled"><a class="page-link">End</a></li>';
			if ($this->currentPage < $this->totalPage) {
				$next     = '<li class="page-item"><a class="page-link" href="' . $this->path . 'page=' . ($this->currentPage + 1) . '">Next</a></li>';
				$end     = '<li class="page-item"><a class="page-link" href="' . $this->path . 'page=' . $this->totalPage . '">End</a></li>';
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
					$listPages .= '<li class="page-item active"><a class="page-link" href="' . $this->path . 'page=' . $i . '">' . $i . '</a>';
				} else {
					$listPages .= '<li class="page-item"><a class="page-link" href="' . $this->path . 'page=' . $i . '">' . $i . '</a>';
				}
			}

			$paginationHTML = '<ul class="pagination mb-0">' . $start . $prev . $listPages . $next . $end . '</ul>';
		}
		return $paginationHTML;
	}
}
