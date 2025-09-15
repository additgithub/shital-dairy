<?php
$DataID = $this->PrimaryKey;

$BannerTagLine = array('name' => 'BannerTagLine', 'id' => 'BannerTagLine', 'value' => (isset($data_info) && $data_info->BannerTagLine != "") ? $data_info->BannerTagLine : set_value('BannerTagLine'), 'minlength' => 1, 'class' => "form-control",);


if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->$DataID > 0) ? $data_info->$DataID : "", 'type' => 'hidden',);
}
$BannerImage = array('name' => 'BannerImage', 'id' => 'BannerImage', 'value' => (isset($data_info) && $data_info->BannerImage != "") ? $data_info->BannerImage : '', 'type' => "hidden",);

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

                            <div class="form-group col-md-6">
                                <label class="form-label">Banner Tag Line:<span class="spn_required">*</span></label>
                                <div class="input-with-icon  right">                                       

                                    <?php
                                    echo form_input($BannerTagLine);
                                    ?>                          
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                            <label class="form-label">Banner Image:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">                                       
                                <i class=""></i>
                                <input type="file" name="Image" id="Image" class="Image form-control" multiple="multiple" accept="image/x-png,image/jpeg" />

                                <?php
                                if (!empty($data_info->BannerImage)) {
                                    echo '<img src="' . UPLOAD_DIR . BANNER_DIR . $data_info->BannerImage . '" class="remove_image1" data-id="' . $data_info->ID . '"/ style="width:100px ;height: 100px;">';
                                    echo form_input($BannerImage);
                                }
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

