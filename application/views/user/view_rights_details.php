<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
<style>
    .delete {
        padding: 5px 7px;
        position: absolute;
        right: 0;
        top: 0;
        display: none;
        background: #ff6f85;
        border-radius: 3px;
    }
    .delete a {
        color: #000;
    }
    .upload-image-content .file-upload.btn.btn-default.form-control {
        width: 100%;
        height: 56px;
        padding: 17px 0;
        font-family: 'Myriad Pro';
        font-size: 15px;
        color: #666666;
        border: 1px dashed #123f76;
        background-color: #f9f9f9;
    }
    .profile-pic {
        position: relative;
        display: inline-block;
    }
    .profile-pic:hover .delete {
        display: block;
        cursor: pointer;
    }
    .upload-image-content input[type="file"] {display: none;}
    .upload-image-content .file-upload.btn.btn-default.form-control {width: 100%; height: 56px; padding: 17px 0; font-family: 'Myriad Pro'; font-size: 15px; color: #666666; border: 1px dashed #123f76; background-color: #f9f9f9;} 
    .button-next-stap {margin: 0 auto;	text-align: center;}
</style>
<?php
$DataID = $this->PrimaryKey;

//$Subcategory = array("" => "Select Category") + $Subcategory;
//$Subcategory = array("" => "Select Category");
//$SubcategoryID = array('name' => 'SubcategoryID', 'id' => 'SubcategoryID', 'class' => "form-control select2",);
$user_rights = array('name' => 'user_rights', 'id' => 'user_rights', 'value' => (isset($data_info) && $data_info->user_rights != "") ? $data_info->user_rights : set_value('user_rights'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);
if (isset($data_info) && $data_info->id > 0) {
    $data_id = array('name' => 'id', 'id' => 'id', 'value' => (isset($data_info) && $data_info->id > 0) ? $data_info->id : "", 'type' => 'hidden',);
}
//if (isset($data_info) && $data_info->$DataID > 0) {
//    $data_id = array('name' => $DataID,'id' => $DataID,'value' => (isset($data_info)
//        && $data_info->$DataID > 0) ? $data_info->$DataID : "",'type' => 'hidden',);
//}

$submit_btn = array('name' => 'submit_btn','id' => 'submit_btn','value' => 'Submit',
    'class' => 'btn btn-success btn-cons',);
$reset_btn = array('name' => 'cancel_btn','id' => 'cancel_btn','content' => 'Cancel',
    'type' => 'reset','class' => 'btn btn-default',);
$form_attr = array('class' => 'default_form','id' => 'user_rights_frm','name' => 'course_frm');
?>

<!--<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $details->Code; ?></h3>
</div> -->
<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4<?php // echo $page_title;  ?></h4>
            </div>
            <div class="grid-body no-border">
<?php
$this->load->view("includes/messages");
?>

                <?php echo validation_errors(); ?>
                <div class=" form">
                <?php
                    echo form_open_multipart(base_url($this->controllers . '/submit-rights-form'),
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
                        <div class="form-group col-md-6">
                            <label class="form-label">Customer Rights:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">                                       
                                <select name="user_rights[]" id="user_rights" class="form-control select2" multiple="multiple">
                                    <?php $user_rights = explode(',',$details->user_rights);?>
                                    <option value="">Select Rights</option>
                                    <option value="Bank"<?php if (in_array('Bank', $user_rights)) { ?>selected="selected"<?php } ?>>Bank</option>
                                    <option value="Insurance"<?php if (in_array('Insurance', $user_rights)) { ?>selected="selected"<?php } ?>>Insurance</option>                            
                                    <option value="End User"<?php if (in_array('End User', $user_rights)) { ?>selected="selected"<?php } ?>>End User</option>                            
                                </select>  
                               <input type="hidden" name="UserID" id="UserID" value="<?php echo $UserID;?>">

                            </div>
                        </div>
                        
                    </div>


                   

                    <div>

                    <div class="col-md-12">
                        <div class="form-actions text-center">  
                            <div ><?php echo form_submit($submit_btn); ?>
                                <a class="btn btn-white btn-cons user_righst_detail_cancel_button" href="javascript:;">Cancel</a>
                            </div>
                        </div>
                    </div>


<?php echo form_close(); ?>
                    <hr>
                </div>
            </div>
        </div>		
    </div>
</div>

<script>
    $(document).ready(function () {

        $("select.select2").select2();
        $(".only_numeric").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) {
                return false;
            }
        });
    });
    $(function () {
        // Multiple images preview in browser
        var imagesPreview = function (input, placeToInsertImagePreview) {

            if (input.files) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function (event) {
                        $($.parseHTML('<img style="border: solid 3px white;" width="95" height="95">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
//                    $($.parseHTML('<img>')).attr('style', event.target.result).appendTo(placeToInsertImagePreview);
                    }

                    reader.readAsDataURL(input.files[i]);
                }
            }

        };

        $('#image').on('change', function () {
            $('div#preview-pic').html('');
            imagesPreview(this, 'div#preview-pic');
        });
    });
    function calCost() {
        var metal_weight = $('.metal_weight').val();
        var inr_PGM = $('.inr_PGM').val();
        var cost = metal_weight * inr_PGM;
        $('.cost').val(cost);
        calFinalCost();
    }
    function calStoneCost($id) {
        var stone_weight = $('#stone_weight_' + $id).val();
        var stone_rate = $('#stone_rate_' + $id).val();
        var cost = stone_weight * stone_rate;
        $('#stone_cost_' + $id).val(cost);
        calFinalCost();
    }
    function calFinalCost() {
        var cost = $('.cost').val();
        var charges = $('.charges').val();
        var Total = 0;
        $(".stone_cost").each(function () {
            console.log($(this).val(), ' value');
            if ($(this).val() > 0) {
                Total = Total + parseInt($(this).val());
            }
        });
        if (charges != '' && charges > 0) {
            Total = Total + parseInt(cost) + parseInt(charges);
        } else {
            Total = Total + parseInt(cost);
        }

        $('.total_cost').val(Total);
        $('.final_cost').val(Total);
    }
</script>