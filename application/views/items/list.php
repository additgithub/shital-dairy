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
                </div>
                <div class="grid-body ">
                    <table class="table common_datatable" data-control="items" data-mathod="manage" data-add-button="1">
                        <thead>
                            <tr>

                                <th width="20%">Item Code</th>
                                <th width="20%">Item Name</th>
                                <th width="20%">Size</th>
                                <th width="20%">Factor</th>
                                <th width="20%">Selling Price</th>
                                <th width="20%" class="hidden-phone">Action</th>
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


