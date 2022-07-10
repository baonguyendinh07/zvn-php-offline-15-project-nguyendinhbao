<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" href="index.html" role="button">
                <i class="fas fa-eye"></i> View Site
            </a>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="<?= $this->_pathImg . 'avatar/' . $this->_userInfoParams['userInfo']['username'] ?>.jpg" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline"><?= $this->_userInfoParams['userInfo']['fullname'] ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <!-- User image -->
                <li class="user-header bg-info">
                    <img src="<?= $this->_pathImg . 'avatar/' . $this->_userInfoParams['userInfo']['username'] ?>.jpg" class="img-circle elevation-2" alt="User Image">

                    <p>
                    <?= $this->_userInfoParams['userInfo']['fullname'] ?>
                        <small><?= $this->_userInfoParams['userInfo']['name'] ?></small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="admin-profile" class="btn btn-default btn-flat">Profile</a>
                    <a href="admin-signout" class="btn btn-default btn-flat float-right">Sign out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>