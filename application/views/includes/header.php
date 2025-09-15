<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <meta charset="utf-8" />

    <title><?php echo (isset($page_title) ? ucwords($page_title) : WEBSITE_NAME); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <meta content="" name="description" />

    <meta content="" name="author" />


    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet"> -->

    <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="<?php echo base_url(); ?>assets/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="<?php echo base_url(); ?>assets/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="<?php echo base_url(); ?>assets/plugins/jquery-notifications/css/location-sel.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />

    <?php
        if (isset($isyard) && $isyard == true) {
            echo '<link href="'.base_url().'assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" media="screen" />';
        }
        else{
            echo '<link href="'.base_url().'assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />';
        }
    ?>

    
    <!-- <link href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" media="screen" /> -->

    <link href="<?php echo base_url(); ?>assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/tabletools/2.2.4/css/dataTables.tableTools.min.css">
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/css/style.css?<?php echo rand(111,222); ?>" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>assets/plugins/alert/css/alert.min.css" rel="stylesheet" />

    <link href="<?php echo base_url(); ?>assets/plugins/alert/themes/default/theme.min.css" rel="stylesheet" />

    <link href="<?php echo base_url(); ?>assets/custom/css/custom.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
    <!--<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>-->


    <!--Multi Select CSS-->
    <link href="<?php echo base_url(); ?>assets/plugins/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />


    <?php
    if (isset($extra_css) && is_array($extra_css) && count($extra_css) > 0) {
        foreach ($extra_css as $css) {
            echo '<link href="'. base_url().'assets/custom/css/'.$css.'.css" rel="stylesheet" type="text/css" />';
        }
    }
    ?>
    <script>
        var BASE_URL = '<?php echo base_url(); ?>';
    </script>

</head>

<!-- END HEAD -->



<!-- BEGIN BODY -->

<body class="opens">

    <!-- BEGIN HEADER -->

    <div class="header navbar navbar-inverse ">

        <!-- BEGIN TOP NAVIGATION BAR -->

        <div class="navbar-inner">

            <div class="header-seperation" style="background-color: #ffffff !important;">

                <img src="<?php echo base_url(); ?>assets/img/logo.png" class="logo" height="auto" width="200" alt="" data-src="<?php echo base_url(); ?>assets/img/logo.png" data-src-retina="<?php echo base_url(); ?>assets/img/logo2x.png" />
            </div>

            <!-- END RESPONSIVE MENU TOGGLER -->

            <div class="header-quick-nav" style="color: white;">
            <a href="javascript:;" class="toggle_sidebar toggle_header_btn"><i class="fa fa-bars" aria-hidden="true"></i></a>
                <!-- BEGIN CHAT TOGGLER -->

                <div class="pull-right" style="color: #00acf0;">

                    <div class="chat-toggler" style="color: #00acf0;">

                        <a href="javascript:;">

                            <div class="user-details m-r-10" style="color: #00acf0;">

                                <div class="username">

                                    <span class="bold"><?php echo $this->tank_auth->get_username(); ?></span>

                                </div>

                            </div>

                        </a>

                        <div class="profile-pic">

                            <img src="<?php echo DEFAULT_AVATAR; ?>" alt="" data-src="<?php echo DEFAULT_AVATAR; ?>" data-src-retina="<?php echo DEFAULT_AVATAR; ?>" width="35" height="35" />

                        </div>

                    </div>

                    <ul class="nav quick-section ">

                        <li class="quicklinks">

                            <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">

                                <div class="iconset top-settings-dark "></div>

                            </a>

                            <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">

                                <li><a href="<?php echo base_url("auth/change-password/"); ?>"> Change Password</a>

                                </li>

                                <li class="divider"></li>

                                <li><a href="<?php echo base_url("auth/logout/"); ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>

                            </ul>

                        </li>

                        <li class="quicklinks"> <span class="h-seperate"></span></li>

                    </ul>

                </div>

                <!-- END CHAT TOGGLER -->

            </div>

            <!-- END TOP NAVIGATION MENU -->



        </div>

        <!-- END TOP NAVIGATION BAR -->

    </div>

    <!-- END HEADER -->

    <!-- BEGIN CONTAINER -->

    <div class="page-container row-fluid">

        <?php
        include 'sidebar.php';
        ?>

        <!-- BEGIN PAGE CONTAINER-->

        <div class="page-content">