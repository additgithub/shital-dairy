<?php
$DataID = $this->PrimaryKey;

$customer_name = array('name' => 'customer_name', 'id' => 'customer_name', 'value' => (isset($data_info) && $data_info->customer_name != "") ? $data_info->customer_name : set_value('customer_name'), 'class' => "form-control",);
$customer_email = array('name' => 'customer_email', 'id' => 'customer_email', 'value' => (isset($data_info) && $data_info->customer_email != "") ? $data_info->customer_email : set_value('customer_email'), 'class' => "form-control",);
$customer_mobile = array('name' => 'customer_mobile', 'id' => 'customer_mobile', 'value' => (isset($data_info) && $data_info->customer_mobile != "") ? $data_info->customer_mobile : set_value('customer_mobile'), 'class' => "form-control",);
$customer_whatsapp_number = array('name' => 'customer_whatsapp_number', 'id' => 'customer_whatsapp_number', 'value' => (isset($data_info) && $data_info->customer_whatsapp_number != "") ? $data_info->customer_whatsapp_number : set_value('customer_whatsapp_number'), 'class' => "form-control",);
$address = array('name' => 'address', 'id' => 'address', 'value' => (isset($data_info) && $data_info->address != "") ? $data_info->address : set_value('address'), 'class' => "form-control",);
$GST = array('name' => 'GST', 'id' => 'GST', 'value' => (isset($data_info) && $data_info->GST != "") ? $data_info->GST : set_value('GST'), 'class' => "form-control",);
$OwnerName = array('name' => 'OwnerName', 'id' => 'OwnerName', 'value' => (isset($data_info) && $data_info->OwnerName != "") ? $data_info->OwnerName : set_value('OwnerName'), 'class' => "form-control",);


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
                <h4>Customer Information</h4>
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


                        <div class="form-group col-md-4">
                            <label class="form-label">Customer Name<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($customer_name);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Owner Name</label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($OwnerName);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Customer Email</label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($customer_email);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Customer Mobile<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($customer_mobile);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Customer WhatsApp Number<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($customer_whatsapp_number);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">GST Number</label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($GST);
                                ?>
                            </div>
                        </div>

                        <div class="form-group col-md-8">
                            <label>Address</label>
                            <?= form_textarea($address); ?>
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