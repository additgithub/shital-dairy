<?php
$DataID = $this->PrimaryKey;

$edit_mode = isset($data_info) && isset($data_info->$DataID) && $data_info->$DataID > 0;
$payment_date = array(
    'name' => 'payment_date',
    'id' => 'payment_date',
    'type' => 'date',
    'value' => $edit_mode ? $data_info->payment_date : (set_value('payment_date') ?: ''),
    'class' => "form-control",
    'required' => true
);
$payment_amount = array(
    'name' => 'payment_amount',
    'id' => 'payment_amount',
    'type' => 'number',
    'value' => $edit_mode ? $data_info->amount : (set_value('payment_amount') ?: ''),
    'class' => "form-control",
    'required' => true
);
$remarks = array(
    'name' => 'remarks',
    'id' => 'remarks',
    'value' => $edit_mode ? $data_info->remark : set_value('remarks'),
    'class' => "form-control"
);
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
                <h4>Payment Information</h4>
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
                            <label>Customer<span class="spn_required">*</span></label>
                            <select name="customer_name" class="form-control select2 customer_select" required>
                                <option value="">Select Customer</option>
                                <?php
                                if (isset($customers) && !empty($customers)) {
                                    foreach ($customers as $customer) {
                                        $selected = ($edit_mode && $data_info->customer_id == $customer->customer_id) ? 'selected' : '';
                                        echo "<option data-id=\"{$customer->customer_id}\" value=\"{$customer->customer_id}\" {$selected}>{$customer->customer_name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Payment Date<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?= form_input($payment_date); ?>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Payment Type<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <select name="payment_type" class="form-control select2 payment_type_select" required>
                                    <option value="">Select Payment Type</option>
                                    <option value="Cash" <?php echo ($edit_mode && $data_info->payment_type == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                    <option value="Cheque" <?php echo ($edit_mode && $data_info->payment_type == 'Cheque') ? 'selected' : ''; ?>>Cheque</option>
                                    <option value="Online" <?php echo ($edit_mode && $data_info->payment_type == 'Online') ? 'selected' : ''; ?>>Online</option>
                                </select>
                            </div>
                        </div>
                       
                    </div>
                    <div class="row">
                         <div class="form-group col-md-4">
                            <label class="form-label">Amount<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?= form_input($payment_amount); ?>
                            </div>
                        </div>
                        <div class="form-group col-md-8">
                            <label>Remarks</label>
                            <?= form_textarea($remarks); ?>
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