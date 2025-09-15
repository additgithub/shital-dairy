<?php
if ($login_by_username AND $login_by_email) {
    $login_label = 'Email or login';
} else if ($login_by_username) {
    $login_label = 'Login';
} else {
    $login_label = 'Email';
}
$login = array(
    'name' => 'login',
    'id' => 'login',
    'class' => 'form-control',
    'value' => set_value('login'),
    'maxlength' => 80,
    'placeholder' => $login_label,
    'size' => 30,
);
$password = array(
    'name' => 'password',
    'class' => 'form-control',
    'id' => 'password',
    'placeholder' => 'Password',
    'size' => 30,
    'autocomplete'=>"off"
);
$remember = array(
    'name' => 'remember',
    'id' => 'remember',
    'value' => 1,
    'checked' => set_value('remember'),
    'style' => 'margin:0;padding:0',
);
$login_btn = array(
    'value' => 'Login',
    'type' => 'submit',
    'class' => 'btn btn-primary btn-cons',
    'autocomplete'=>"off"
);
$captcha = array(
    'name' => 'captcha',
    'id' => 'captcha',
    'maxlength' => 8,
);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title><?php echo WEBSITE_NAME; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN CORE CSS FRAMEWORK -->
        <link href="<?php echo base_url(); ?>assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <!-- END CORE CSS FRAMEWORK -->
        <!-- BEGIN CSS TEMPLATE -->
        <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
        <!-- END CSS TEMPLATE -->
    </head>
    <!-- END HEAD -->
    <!-- BEGIN BODY -->
    <body class="error-body no-top lazy"  data-original="<?php echo base_url(); ?>assets/img/jain.webp"  style="background-image: url('<?php echo base_url(); ?>assets/img/work.jpg');background-repeat:no-repeat;background-size: cover;"> 
        <div class="container">
            <div class="row login-container animated fadeInUp">  
                <div class="col-md-7 col-md-offset-2 tiles white no-padding">
                    <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10"> 
                        <h2 class="normal">Sign in to <?php echo WEBSITE_NAME; ?></h2>
                    </div>
                    <div class="tiles grey p-t-20 p-b-20 text-black">
                        <?php echo form_open($this->uri->uri_string(), array('class' => 'animated fadeIn', 'id' => 'frm_login','autocomplete'=>'off')); ?>
                        <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                            <div class="col-md-6 col-sm-6 ">
                                <?php echo form_input($login); ?>
                                <?php echo form_error($login['name'], '<p class="error">', '</p>'); ?><?php echo isset($errors[$login['name']]) ? '<p class="error">' . $errors[$login['name']] . '</p>' : ''; ?>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <?php echo form_password($password); ?>
                                <?php echo form_error($password['name'], '<p class="error">', '</p>'); ?><?php echo isset($errors[$password['name']]) ? '<p class="error">' . $errors[$password['name']] . '</p>' : ''; ?>
                            </div>
                        </div>
                        <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
                            <div class="control-group  col-md-8">
                                <div class="checkbox checkbox check-success"> <?php echo anchor('/auth/forgot_password/', 'Trouble login in?'); ?>&nbsp;&nbsp;
                                    <?php echo form_checkbox($remember); ?>
                                    <label for="remember">Keep me reminded </label>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <?php echo form_input($login_btn); ?>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>   
                </div>   
            </div>
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN CORE JS FRAMEWORK-->
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-lazyload/jquery.lazyload.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/js/login_v2.js" type="text/javascript"></script>
        <!-- BEGIN CORE TEMPLATE JS -->
        <!-- END CORE TEMPLATE JS -->
    </body>

</html>