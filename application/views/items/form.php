<?php
$DataID = $this->PrimaryKey;

$item_name = array('name' => 'item_name', 'id' => 'item_name', 'value' => (isset($data_info) && $data_info->item_name != "") ? $data_info->item_name : set_value('item_name'), 'class' => "form-control",);
$item_code = array('name' => 'item_code', 'id' => 'item_code', 'value' => (isset($data_info) && $data_info->item_code != "") ? $data_info->item_code : set_value('item_code'), 'class' => "form-control",);
$selling_price = array('name' => 'selling_price', 'id' => 'selling_price', 'value' => (isset($data_info) && $data_info->selling_price != "") ? $data_info->selling_price : set_value('selling_price'), 'class' => "form-control",);
$size = array('name' => 'size', 'id' => 'size', 'value' => (isset($data_info) && $data_info->size != "") ? $data_info->size : set_value('size'), 'class' => "form-control",);
$factor = array('name' => 'factor', 'id' => 'factor', 'value' => (isset($data_info) && $data_info->factor != "") ? $data_info->factor : set_value('factor'), 'class' => "form-control",);


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
                <h4>Item Information</h4>
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
                            <label class="form-label">Item Name<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($item_name);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Item Code<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($item_code);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Size<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($size);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Factor<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($factor);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Selling Price<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($selling_price);
                                ?>
                            </div>
                        </div>

                        <!-- <div class="form-group col-md-6">
                            <label class="form-label">Reorder<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <input type="checkbox" class="form-check-input" id="custom_checkbox" name="reorder" value="1" <?php echo (isset($data_info) && $data_info->reorder == 1) ? 'checked' : ''; ?>>
                            </div>
                        </div> -->


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