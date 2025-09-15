<?php
$DataID = $this->PrimaryKey;



if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->$DataID > 0) ? $data_info->$DataID : "", 'type' => 'hidden',);
}
$Mobileno = array('name' => 'Mobileno', 'type' => 'text', 'id' => 'Mobileno', 'value' => (isset($data_info) && $data_info->mobile_no != "") ? $data_info->mobile_no : set_value('EmployeeCode'), 'minlength' => 1, 'size' => 30, 'class' => "form-control");
$DealerCode = array('name' => 'DealerCode', 'type' => 'text', 'id' => 'DealerCode', 'value' => (isset($data_info) && $data_info->Code != "") ? $data_info->Code : set_value('DealerCode'), 'minlength' => 1, 'size' => 30, 'class' => "form-control","readonly"=>true);
$DealerFirstName = array('name' => 'DealerFirstName', 'type' => 'text', 'id' => 'DealerFirstName', 'value' => (isset($data_info) && $data_info->first_name != "") ? $data_info->first_name : set_value('DealerFirstName'), 'minlength' => 1, 'size' => 30, 'class' => "form-control","readonly"=>true);
$DealerLastName = array('name' => 'DealerLastName', 'type' => 'text', 'id' => 'DealerLastName', 'value' => (isset($data_info) && $data_info->last_name != "") ? $data_info->last_name : set_value('DealerLastName'), 'minlength' => 1, 'size' => 30, 'class' => "form-control","readonly"=>true);
$DealerSourceType = array('name' => 'DealerSourceType', 'type' => 'text', 'id' => 'DealerSourceType', 'value' => (isset($data_info) && $data_info->SRID > 0) ? "App" : "Web", 'minlength' => 1, 'size' => 30, 'class' => "form-control","readonly"=>true);
$DealerSourceName = array('name' => 'DealerSourceName', 'type' => 'text', 'id' => 'DealerSourceName', 'value' => (isset($source_name) && $source_name != '') ? $source_name : "", 'minlength' => 1, 'size' => 30, 'class' => "form-control","readonly"=>true);
$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);
$reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);
$form_attr = array('class' => 'default_form', 'id' => 'user_frm', 'name' => 'user_frm');
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $page_title; ?></h3>
</div> 
<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4<?php echo $page_title; ?></h4>
            </div>
            <div class="grid-body no-border">
                <?php
                $this->load->view("includes/messages");
                ?>

                <?php echo validation_errors(); ?>
                <div class=" form">
                    <?php echo form_open_multipart(base_url($this->controllers . '/update-mobileno-form'), $form_attr); ?>
                    <?php
                    if (isset($data_info) && $data_info->$DataID > 0) {
                        echo form_input($data_id);
                    }
                    ?>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="form-label">Dealer First Name:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($DealerFirstName);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Dealer Last Name:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($DealerLastName);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Source Type:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($DealerSourceType);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Source Name:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($DealerSourceName);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Dealer Code:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($DealerCode);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Dealer Mobile No:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($Mobileno);
                                ?>
                            </div>
                        </div>



                    </div>
                    <div class="row">

                        <div class="col-md-12" id="case_list">
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