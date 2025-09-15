<?php
$DataID = $this->PrimaryKey;

$first_name = array('name' => 'first_name', 'id' => 'first_name', 'value' => (isset($data_info) && $data_info->first_name != "") ? $data_info->first_name : set_value('first_name'), 'class' => "form-control",);
$last_name = array('name' => 'last_name', 'id' => 'last_name', 'value' => (isset($data_info) && $data_info->last_name != "") ? $data_info->last_name : set_value('last_name'), 'class' => "form-control",);
$email = array('name' => 'email', 'id' => 'email', 'value' => (isset($data_info) && $data_info->email != "") ? $data_info->email : set_value('email'), 'class' => "form-control",);
$mobile_no = array('name' => 'mobile_no', 'id' => 'mobile_no', 'value' => (isset($data_info) && $data_info->mobile_no != "") ? $data_info->mobile_no : set_value('mobile_no'), 'class' => "form-control",);
$password = array('type'=> 'password','name' => 'password', 'id' => 'password', 'value' => (isset($data_info) && $data_info->password != "") ? '' : set_value('password'), 'class' => "form-control",);
$confirm_password = array('type'=> 'password','name' => 'confirm_password', 'id' => 'confirm_password', 'value' => (isset($data_info) && $data_info->password != "") ? '' : set_value('confirm_password'), 'class' => "form-control",);

if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->$DataID > 0) ? $data_info->$DataID : "", 'type' => 'hidden',);
}

$ItemID = array('name' => 'ItemID', 'id' => 'ItemID', 'class' => "form-control select2");

$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);

$reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);

$form_attr = array('class' => 'default_form', 'id' => 'course_frm', 'name' => 'course_frm', 'autocomplete' => "off");
//print_r($data_info);die;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $page_title; ?></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4>Employee Information</h4>
            </div>
            <div class="grid-body no-border">
                <?php
                $this->load->view("includes/messages");
                ?>

                <?php echo validation_errors(); ?>
                <div class=" form">
                    <?php echo form_open_multipart(base_url($this->controllers . '/submit-form'), $form_attr); ?>
                    <?php
                    if (isset($data_info) && $data_info->$DataID > 0) {
                        echo form_input($data_id);
                    }
                    ?>
                    <div class="row">


                        <div class="form-group col-md-6">
                            <label class="form-label">First Name<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($first_name);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Last Name<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($last_name);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Email<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($email);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Mobile No<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($mobile_no);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Password<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($password);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Confirm Password<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($confirm_password);
                                ?>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <div class="pull-right">
                            <?php echo form_submit($submit_btn); ?>
                            <a class="btn btn-white btn-cons cancel_button" href="javascript:;">Cancel</a>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>