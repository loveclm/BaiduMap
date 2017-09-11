<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/logo.png" type="image/x-icon"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons 2.0.0 -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/dist/css/custom.css" rel="stylesheet" type="text/css"/>

    <style>
        .error {
            color: red;
            font-weight: normal;
        }
    </style>
    <!-- jQuery 2.1.4 -->

    <script src="<?php echo base_url(); ?>assets/js/jQuery-2.1.4.min.js"></script>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body id="main_page_body" class="skin-blue sidebar-mini">
<div>
</div>
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>YBC</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>A游不错</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="user-image"
                                 alt="User Image"/>
                            <span class="hidden-xs"><?php echo $name; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo base_url(); ?>assets/dist/img/avatar.png" class="img-circle"
                                     alt="User Image"/>

                                <p>
                                    <?php echo $name; ?>
                                    <small><?php echo $role_text; ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo base_url(); ?>loadChangePass"
                                       class="btn btn-default btn-flat"><i class="fa fa-key"></i>修改密码</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat"><i
                                            class="fa fa-sign-out"></i>登出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <?php
        $menu_acc = isset($menu_access) ? json_decode($menu_access) : '';
        ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_10 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>area">
                        <i class="fa fa-dashboard"></i> <span>景区管理</span></i>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_20 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>course">
                        <i class="fa fa-plane"></i>
                        <span>旅游线路管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_30 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>shop">
                        <i class="fa fa-ticket"></i>
                        <span>商家管理</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="<?php echo base_url(); ?>qrmanage">
                        <i class="fa fa-edit"></i>
                        <span>二维码管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_40 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>authmanage">
                        <i class="fa fa-th"></i>
                        <span>授权码管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_50 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>ordermanage">
                        <i class="fa fa-files-o"></i>
                        <span>订单管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_61 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>settlemanage">
                        <i class="fa fa-pie-chart"></i>
                        <span>结算管理</span>
                    </a>
                </li>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_70 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>usermanage">
                        <i class="fa fa-book"></i>
                        <span>用户管理</span>
                    </a>
                </li>

                <?php
                if ($role == ROLE_ADMIN) {
                    ?>
                    <li class="treeview">
                        <a href="<?php echo base_url(); ?>userListing">
                            <i class="fa fa-laptop"></i>
                            <span class="pull-right-container">系统管理
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="display: none;">
                            <li style="<?php echo(($menu_acc != '') ? ($menu_acc->p_80 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                                <a href="<?php echo base_url(); ?>userListing">
                                    <i class="fa fa-users"></i>
                                    人员管理
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>roleListing">
                                    <i class="fa fa-circle-o"></i>
                                    角色管理
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
                <li class="treeview"
                    style="<?php echo(($menu_acc != '') ? ($menu_acc->p_90 == '1' ? '' : 'display:none;') : 'display:none'); ?>">
                    <a href="<?php echo base_url(); ?>changePassword">
                        <i class="fa fa-files-o"></i>
                        <span>修改密码</span>
                    </a>
                </li>
            </ul>
            <input id="page_type_name" value="<?php echo isset($page_type_name)?$page_type_name:'';?>" style="display: none;"/>
        </section>
        <!-- /.sidebar -->
    </aside>