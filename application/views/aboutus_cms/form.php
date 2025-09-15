<?php
$DataID = $this->PrimaryKey;

$AboutUS = array('name' => 'AboutUS', 'id' => 'AboutUS', 'value' => (isset($data_info) && $data_info->AboutUS != "") ? $data_info->AboutUS : set_value('AboutUS'), 'minlength' => 1, 'class' => "form-control",);

$OurVision = array('name' => 'OurVision', 'id' => 'OurVision', 'value' => (isset($data_info) && $data_info->OurVision != "") ? $data_info->OurVision : set_value('OurVision'), 'minlength' => 1, 'class' => "form-control",);

$OurMission = array('name' => 'OurMission', 'id' => 'OurMission', 'value' => (isset($data_info) && $data_info->OurMission != "") ? $data_info->OurMission : set_value('OurMission'), 'minlength' => 1, 'class' => "form-control",);

$OurValue = array('name' => 'OurValue', 'id' => 'OurValue', 'value' => (isset($data_info) && $data_info->OurValue != "") ? $data_info->OurValue : set_value('OurValue'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);



$OurFounderCEO = array('name' => 'OurFounderCEO', 'id' => 'OurFounderCEO', 'value' => (isset($data_info) && $data_info->OurFounderCEO != "") ? $data_info->OurFounderCEO : set_value('OurFounderCEO'), 'minlength' => 1, 'class' => "form-control",);

//$Location = array('name' => 'Location', 'id' => 'Location', 'value' => (isset($data_info) && $data_info->Location != "") ? $data_info->Location : set_value('Location'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);
//$TelNO = array('name' => 'TelNO', 'id' => 'TelNO', 'value' => (isset($data_info) && $data_info->TelNO != "") ? $data_info->TelNO : set_value('TelNO'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);
//$Email = array('name' => 'Email', 'id' => 'Email', 'value' => (isset($data_info) && $data_info->Email != "") ? $data_info->Email : set_value('OldCarFeatureDesc1'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);

if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->$DataID > 0) ? $data_info->$DataID : "", 'type' => 'hidden',);
}

//$old_banner_image = array('name' => 'old_banner_image', 'id' => 'old_banner_image', 'value' => (isset($data_info) && $data_info->BannerImage != "") ? $data_info->BannerImage : '', 'type' => "hidden",);
$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);
$reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);
$form_attr = array('class' => 'default_form', 'id' => 'home_cms_frm', 'name' => 'user_frm','autocomplete'=>"off");
?>

<div class="content">
    <?php
    add_edit_form();
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <?php
                $this->load->view("includes/messages");
                ?>
                <div class="grid-title">
                    <h4><?php echo $page_title; ?></h4>
                </div>
                <div class="grid-body ">
                    <div class=" form">
                        <?php echo form_open_multipart(base_url($this->controllers . '/submit-form'), $form_attr); ?>
                        <?php
                        if (isset($data_info) && $data_info->$DataID > 0) {
                            echo form_input($data_id);
                        }
                        ?>
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label class="form-label">About US:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       

                                    <?php
                                    echo form_textarea($AboutUS);
                                    ?>                          
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">Our Vision:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       

                                    <?php
                                    echo form_textarea($OurVision);
                                    ?>                          
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">Our Mission:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       

                                    <?php echo form_textarea($OurMission); ?>                          
                                </div>
                            </div> 
                       
                            <div class="form-group col-md-12">
                                <label class="form-label">Our Value:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       
                                    <?php echo form_textarea($OurValue); ?>                            
                                </div>
                            </div> 

                            
                        </div>
                        
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label class="form-label">Our Founder CEO:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       

                                    <?php
                                    echo form_textarea($OurFounderCEO);
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
</div>

<script type="text/javascript">

    CKEDITOR.replace('AboutUS',
            {
                filebrowserBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                filebrowserUploadUrl: '<?php echo base_url(); ?>>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });
    CKEDITOR.replace('OurVision',
            {
                filebrowserBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                filebrowserUploadUrl: '<?php echo base_url(); ?>>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });
    CKEDITOR.replace('OurMission',
            {
                filebrowserBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                filebrowserUploadUrl: '<?php echo base_url(); ?>>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });
    CKEDITOR.replace('OurValue',
            {
                filebrowserBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                filebrowserUploadUrl: '<?php echo base_url(); ?>>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });
    
    CKEDITOR.replace('OurFounderCEO',
            {
                filebrowserBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html',
                filebrowserImageBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Images',
                filebrowserFlashBrowseUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                filebrowserUploadUrl: '<?php echo base_url(); ?>>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                filebrowserFlashUploadUrl: '<?php echo base_url(); ?>assets/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            });

</script>

