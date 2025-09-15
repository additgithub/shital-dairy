<?php
$DataID = $this->PrimaryKey;

$banner_text = array('name' => 'banner_text', 'id' => 'banner_text', 'value' => (isset($data_info) && $data_info->BannerText != "") ? $data_info->BannerText : set_value('banner_text'), 'class' => "form-control",);

//$car_id = array('name' => 'car_id', 'id' => 'car_id', 'value' => (isset($data_info) && $data_info->CarID != "") ? $data_info->CarID : set_value('car_id'), 'class' => "form-control",);

$display_order = array('name' => 'display_order', 'id' => 'display_order', 'value' => (isset($data_info) && $data_info->DisplayOrder != "") ? $data_info->DisplayOrder : set_value('display_order'), 'class' => "form-control",);

if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->$DataID > 0) ? $data_info->$DataID : "", 'type' => 'hidden',);
}

$old_banner_image = array('name' => 'old_banner_image', 'id' => 'old_banner_image', 'value' => (isset($data_info) && $data_info->BannerImage != "") ? $data_info->BannerImage : '', 'type' => "hidden",);

$ItemID = array('name' => 'ItemID', 'id' => 'ItemID', 'class' => "form-control select2");

$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);

$reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);

$form_attr = array('class' => 'default_form', 'id' => 'course_frm', 'name' => 'course_frm','autocomplete'=>"off");
//print_r($data_info);die;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $page_title; ?></h3>
</div> 
<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4>Banner Information</h4>
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
                            <label class="form-label">Banner Image<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">                                       
                                <i class=""></i>
                                <input type="file" name="Image" id="Image" class="Image form-control" multiple="multiple" accept="image/x-png,image/jpeg" />

                                <?php
                                if (!empty($data_info->BannerImage)) {
                                    echo '<img src="' . UPLOAD_DIR . BANNER_DIR . $data_info->BannerImage . '" class="remove_image1" data-id="' . $data_info->BannerID . '"/ style="width:100px ;height: 100px;">';
                                    echo form_input($old_banner_image);
                                }
                                ?>  
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Banner Text<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">                                       

                                <?php
                                echo form_input($banner_text);
                                ?>                          
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Car:</label>
                            <div class="input-with-icon  right">                                       

                                <?php
                                echo form_dropdown('CarID', array("" => "Select Car") + $Car, (isset($data_info) && $data_info->CarID != "") ? $data_info->CarID : set_value('CarID'), 'class="form-control select2" id="MakeNameList"');
                                ?>                          
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Display Order:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">                                       

                                <?php
                                echo form_input($display_order);
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

