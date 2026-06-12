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

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
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

    .custom_form_row input,
    .custom_form_row select {
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
                    <div class="row">
                        <?php
                            $currentMonth = date('Y-m', strtotime('-1 months'));
                            $minMonth = date('Y-m', strtotime('-3 months'));
                        ?>

                        <div class="form-group col-md-4">
                            <label>Month</label>
                            <input 
                                type="month" 
                                class="form-control search_mq" 
                                name="month" 
                                id="month"
                                value="<?php echo $currentMonth; ?>"
                                min="<?php echo $minMonth; ?>"
                                max="<?php echo $currentMonth; ?>"
                            >
                        </div>
                        <div class="form-group col-md-4">
                            <button type="button" class="btn btn-primary" id="clear_customer_ledger" style="margin-top: 25px;">Clear All</button>
                            
                        </div>
                    </div>
                    <table class="table common_datatable" id="reconcile_table" data-control="reconcile" data-mathod="manage">
                        <thead>
                            <tr>
                                <th width="10%"><input type="checkbox" class="mdc-checkbox__native-control question_id_chk_all" name="customer_ids" id="customer_id_all" value="all"> Select All</th>
                                <th width="20%">Customer Name</th>
                                <th width="15%">Opening Bal</th>
                                <th width="15%">Credit</th>
                                <th width="15%">Debit</th>
                                <th width="15%">Closing Bal</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                     <form class="customer_frm" id="customer_frm" name="customer_frm">
                            <div id="customer_div" class="hidden">
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>