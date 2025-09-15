<?php
if ($login_by_username and $login_by_email) {
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
    'autocomplete' => "off"
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
    'class' => 'btn btn-primary btn-cons btn-login',
    'autocomplete' => "off"
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?php echo base_url(); ?>assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <style>
        body {
            background:
                linear-gradient(rgba(201, 200, 200, 0.6), rgba(228, 228, 228, 0.6)),
                url('<?php echo base_url(); ?>assets/img/dairy-bg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px 30px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative milk splash top */
        .login-card::before {
            content: "";
            position: absolute;
            top: -60px;
            left: -40px;
            width: 160px;
            height: 160px;
            background: url('<?php echo base_url(); ?>assets/img/milk-splash.png') no-repeat center/contain;
            opacity: 0.15;
        }

        .login-card h2 {
            font-weight: 700;
            margin-bottom: 25px;
            color: #333;
        }

        .login-card h2 i {
            color: #28a745;
            margin-right: 8px;
        }

        .form-control {
            border-radius: 30px;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ccc;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #aaa;
        }

        .btn-login {
            width: 50%;
            /* reduce width (instead of full width) */
            padding: 10px 20px;
            /* less padding */
            border-radius: 8px;
            /* smaller rounded corners */
            background: linear-gradient(135deg, #f9f9f9, #00acf0);
            border: 2px solid #00acf0;
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            /* slightly smaller font */
            letter-spacing: 0.3px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05), 0 3px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin: 0 auto;
            /* center align inside container */
            display: block;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #00acf0, #a1d8ffff);
            color: #fff;
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }


        .login-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .error {
            color: #d9534f;
            font-size: 13px;
            margin-top: 5px;
            text-align: left;
        }

        input.btn.btn-primary.btn-cons.btn-login {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="login-card">
            <div class="logo">
                <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="" height="auto" width="250">
            </div>
            <h2 style="color: #00acf0;"> Sign in to <?php echo WEBSITE_NAME; ?></h2>

            <?php echo form_open($this->uri->uri_string(), array('id' => 'frm_login', 'autocomplete' => 'off')); ?>

            <div class="form-group input-icon">
                <i class="fa fa-user"></i>
                <?php echo form_input($login + ['class' => 'form-control', 'placeholder' => 'Email / Username']); ?>
                <?php echo form_error($login['name'], '<p class="error">', '</p>'); ?>
                <?php echo isset($errors[$login['name']]) ? '<p class="error">' . $errors[$login['name']] . '</p>' : ''; ?>
            </div>

            <div class="form-group input-icon">
                <i class="fa fa-lock"></i>
                <?php echo form_password($password + ['class' => 'form-control', 'placeholder' => 'Password']); ?>
                <?php echo form_error($password['name'], '<p class="error">', '</p>'); ?>
                <?php echo isset($errors[$password['name']]) ? '<p class="error">' . $errors[$password['name']] . '</p>' : ''; ?>
            </div>

            <div class="login-footer">
                <div><?php echo anchor('/auth/forgot_password/', 'Forgot Password?'); ?></div>
                <div>
                    <?php echo form_checkbox($remember); ?>
                    <label for="remember">Remember me</label>
                </div>
            </div>

            <div class="form-group mt-3">
                <?php echo form_input($login_btn + ['class' => 'btn btn-login']); ?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/plugins/jquery-1.8.3.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/boostrapv3/js/bootstrap.min.js"></script>
</body>

</html>