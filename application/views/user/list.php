<style>
    .modal-content {
        padding-left: 0px !important; 
        padding-right: 0px !important; 
        padding-top: 0px !important; 
    }

    .modal-dialog {
        width: 100% !important;
        max-width: 95% !important;
        margin: 3% auto !important;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }


    .custom_form_row {
        display: flex;
        overflow: hidden !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        margin-left: -4px;
        margin-right: -4px;
        margin-bottom: 10px;
    }
    .custom_form_row input, .custom_form_row select {
        min-width: 130px;
    }

    .custom_form_row .form-group.cus_filds {
        padding: 0px 4px;
        margin: 0px;
    }
    .btn.btn-danger.cus_filds {
        margin: 0px !important;
        padding: 8px 10px !important;
        height: 36px !important;
        margin-top: 27px !important;
        margin-left: 4px !important;
    }






</style>
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
                    <div class="pull-right">
                        <?php
                        // if($this->tank_auth->get_user_role_id() == 1){
                        ?>
                        <a class="export-bn btn btn-primary" data-controller="user" style="font-size: 12px;">Export <i class="fa fa-file-excel-o"></i> </a>
                        <?php
                        if($this->tank_auth->get_user_id() == 1544){
                        ?>
                            <a class="export-user-bn btn btn-primary" data-controller="user" data-method="export_web_buyer" style="font-size: 12px;">Export SR Map <i class="fa fa-file-excel-o"></i> </a>
                            <a class="btn btn-primary open_my_form_form" href="javascript:;" data-control="user" data-method="upload_excel" style="">Import SR Map Excel <i class="fa fa-file-excel-o"></i></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="grid-body ">
                <div class="row" id="display_update_form">
                    <form id="">
                        <input type="hidden" name="type" class="status" id="status" value="<?php echo (isset($type) && $type != '') ? $type : ''; ?>">
                        <div class="form-group col-md-3">
                            <label class="form-label">State:</label>
                            <div class="input-with-icon  right">
                                <!-- <select class="form-control select2 search_mq" name="active" id="state">
                                    <option value="">All</option>
                                    <?php
                                    if(!empty($state_list)){
                                        foreach($state_list as $single_state){
                                    ?>
                                    <option value="<?php echo $single_state->StateID; ?>"><?php echo $single_state->StateName; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select> -->
                                <?php
                                echo form_dropdown('StateName', array("" => "Select State") + $state_list, (isset($data_info) && $data_info->StateID != "") ? $data_info->StateID : set_value('StateName'), 'class="form-control select2 search_mq" id="FilterStateNameList"');
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">City:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">
                            <?php
                                echo form_dropdown('CityName', array("" => "Select City") + $city_list, (isset($data_info) && $data_info->CityID != "") ? $data_info->CityID : set_value('CityName'), 'class="form-control select2 search_mq" id="CityNameList"');
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">Source Name:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">
                            <?php
                                echo form_dropdown('SourceName', array("" => "Select SR/Yard") + $sr_yard_list, (isset($data_info) && $data_info->CityID != "") ? $data_info->CityID : set_value('SourceName'), 'class="form-control select2 search_mq" id="SourceName"');
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="form-label">Source Type:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">
                                    <select name="source_type" class="select2 search_mq source_type form-control" id="source_type">
                                        <option value="">All</option>
                                        <option value="0">Web</option>
                                        <option value="1">App</option>
                                    </select>
                            </div>
                        </div>
                        </form>    
                    </div>

                    <table class="table common_datatable" data-control="user" data-mathod="manage" >
                        <thead>
                            <tr>
                                <th width="10%">Buyer Name</th>
                                <th width="10%">Email</th>
                                <th width="10%">Mobile No</th>
                                <th width="20%">Source Name</th>
                                <th width="20%">Source Type</th>
                                <th width="5%">Active</th>
                                <th width="5%">Subscription</th>
                                <th width="10%">Status</th>
                                <th width="5%">Details</th>
                                <th width="2%">Rights</th>
                                <th width="10%">Action</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" style="max-width: 75% !important;">
        <div class="modal-content" id="timetable_model_main" style="width: 1000px !important;">

            <div class="modal-header">
                <button type="button" class="close close_property_detail" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <h4 class="modal-title model_title" id="myModalLabel2">Customer details</h4>
            </div>
            <div class="modal-body" id="timetable-slot">
                <div class="model_content_area" >

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="user_rights_details" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" style="max-width: 75% !important;">
        <div class="modal-content" id="timetable_model_main" style="width: 1000px !important;">

            <div class="modal-header">
                <button type="button" class="close close_property_detail" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <h4 class="modal-title model_title" id="myModalLabel2">Customer Rights details</h4>
            </div>
            <div class="modal-body" id="timetable-slot">
                <div class="model_content_area" >

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    //  $(document).ready(function() {
    //     $("select.select2").select2();
    //  });
</script>

