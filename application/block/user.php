<?php
if (isset($_GET['module']) && $_GET['module'] == 'frontend' && $_GET['controller'] == 'user') {
    $option = [
        'profile' => 'Thông tin tài khoản',
        'changePassword' => 'Thay đổi mật khẩu',
        'orderHistory' => 'Lịch sử mua hàng'
    ];

    $module     = $_GET['module'] ?? '';
    $controller = $_GET['controller'] ?? '';
    $action     = $_GET['action'] ?? '';

    $actionXHTML = '<div class="col-lg-3">
    <div class="dashboard-left">
        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> Ẩn</span></div>
        <div class="block-content">
                    <ul>';

    foreach ($option as $key => $value) {
        $class = $key == $action ? ' class="active"' : '';
        $href = sprintf('index.php?module=%s&controller=%s&action=%s', $module, $controller, $key);
        $actionXHTML .= sprintf('<li%s><a href="%s">%s</a></li>', $class, $href, $value);
    }

    $actionXHTML .= '<li><a href="index.php?module=frontend&controller=user&action=logout">Đăng xuất</a>
    </li>
                </ul>
            </div>
        </div>
    </div>';
}
?>