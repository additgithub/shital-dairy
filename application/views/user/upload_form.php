<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<?php
$DataID = $this->PrimaryKey;

//$Subcategory = array("" => "Select Category") + $Subcategory;
//$Subcategory = array("" => "Select Category");
//$SubcategoryID = array('name' => 'SubcategoryID', 'id' => 'SubcategoryID', 'class' => "form-control select2",);

if (isset($data_info) && $data_info->$DataID > 0) {
    $data_id = array('name' => $DataID,'id' => $DataID,'value' => (isset($data_info)
        && $data_info->$DataID > 0) ? $data_info->$DataID : "",'type' => 'hidden',);
}

$submit_btn = array('name' => 'submit_btn','id' => 'submit_btn','value' => 'Submit',
    'class' => 'btn btn-success btn-cons',);
$reset_btn = array('name' => 'cancel_btn','id' => 'cancel_btn','content' => 'Cancel',
    'type' => 'reset','class' => 'btn btn-default',);
$form_attr = array('class' => 'default_form','id' => 'car_upload_frm','name' => 'course_frm');
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
                    <?php
                    echo form_open_multipart(base_url($this->controllers . '/submit-upload-form'),
                            $form_attr);
                    ?>
                    <?php
                    if (isset($data_info) && $data_info->$DataID > 0) {
                        echo form_input($data_id);
                    }
                    ?>

                    <div class="row">
                        
                    </div>
                    <div class="row">


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">SR Map Buyer Excel Upload</label>
                                <div class="input-with-icon  right">                                       
                                    <i class=""></i>
                                    <input type="file" name="sr_map_excel" id="sr_map_excel" class="sr_map_excel form-control" accept=".csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">
                                </div>
                            </div>
                        </div>
                       
                        
                    </div>
                    <div class="row" >
                        <div class="col-md-6" id="car_excel_log_div" style="color: red;">

                        </div>
                    </div>

                   

                    <div>
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


