<?php
$DataID = $this->PrimaryKey;

$expense_date = array('name' => 'expense_date', 'type' => 'date', 'id' => 'expense_date', 'value' => (isset($data_info) && $data_info->expense_date != "") ? $data_info->expense_date : set_value('expense_date'), 'class' => "form-control",);
$total_expense = array('name' => 'total_expense', 'id' => 'total_expense', 'value' => (isset($data_info) && $data_info->total_expense != "") ? $data_info->total_expense : set_value('total_expense'), 'class' => "form-control",);
$remark = array('name' => 'remark', 'id' => 'remark', 'value' => (isset($data_info) && $data_info->remark != "") ? $data_info->remark : set_value('remark'), 'class' => "form-control",);


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
                <h4>Expense Information</h4>
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
                            <label class="form-label">Expense Date<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($expense_date);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Total Expense<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($total_expense);
                                ?>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Remark</label>
                            <?= form_textarea($remark); ?>
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