<?php
$pathCategoryImg = FILES_URL . 'category' . DS;

$data	= XML::getContentXML('categories.xml');

if (isset($_GET['category_id'])) {
	$cateID	= $_GET['category_id'];
}

if (!empty($data)) {
	$headerCatarogy 	= '';
	$sidebarCategory 	= '';
	$listCategory		= '';
	foreach ($data as $key => $value) {
		$id			= $value->id;
		$name	 	= $value->name;
		$nameURL	= URL::filterURL($name);
		$picture 	= !empty($value->picture) ? $pathCategoryImg . $value->picture : $pathCategoryImg . 'default.jpg';
		$link	 	= URL::createLink('frontend', 'book', 'list', ['category_id' => $id], "$nameURL-$id.html");
		$sidebarClass = 'text-dark';
		if (isset($cateID) && $cateID == $value->id) {
			$headerClass = ' class="active"';
			$sidebarClass	 = 'my-text-primary';
		}
		$headerCatarogy	.= sprintf('<li><a%s href="%s">%s</a></li>', $headerClass ?? '', $link, $name);
		$sidebarCategory	.= '
		<div class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
			<a class="' . $sidebarClass . '" href="' . $link . '">' . $name . '</a>
		</div>
		';

		$listCategory .= '
		<div class="product-box">
			<div class="img-wrapper">
				<div class="front">
					<a href="' . $link . '"><img src="' . $picture . '" class="img-fluid blur-up lazyload bg-img" alt=""></a>
				</div>
			</div>
			<div class="product-detail">
				<a href="' . $link . '">
					<h4>' . $name . '</h4>
				</a>
			</div>
		</div>';
	}
}
