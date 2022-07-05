<aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?= $this->_pathImg; ?>/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
        <span class="brand-text font-weight-light"><b>Book Store Admin</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $this->_pathImg . 'avatar/' . $this->_userInfoParams['userInfo']['username'] ?>.jpg" class="img-circle elevation-2" alt="User Image" />
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $this->_userInfoParams['userInfo']['fullname'] ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="index.php?module=backend&controller=dashboard&action=index" class="nav-link" data-active="dashboard">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?module=backend&controller=group&action=index" class="nav-link" data-active="group">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Group</p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link" data-active="user">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            User
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=user&action=index" class="nav-link" data-active="index">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=user&action=form" class="nav-link" data-active="form">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Add</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link" data-active="category">
                        <i class="nav-icon fas fa-thumbtack"></i>
                        <p>
                            Category
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=category&action=index" class="nav-link" data-active="index">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=category&action=form" class="nav-link" data-active="form">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Add</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link" data-active="book">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>
                            Book
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=book&action=index" class="nav-link" data-active="index">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=book&action=form" class="nav-link" data-active="form">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Add</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="" class="nav-link" data-active="slider">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>
                            Slider
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=slider&action=index" class="nav-link" data-active="index">
                                <i class="nav-icon fas fa-list-ul"></i>
                                <p>List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?module=backend&controller=slider&action=form" class="nav-link" data-active="form">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Add</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="index.php?module=backend&controller=cart&action=index" class="nav-link" data-active="cart">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Cart</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?module=backend&controller=user&action=changeAccountPassword" class="nav-link" data-active="changeAccountPassword">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>