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
                         <div class="col-md-2">
                            <a type="button" target="_blank" href="<?php echo base_url('ledger/download_report_all') ?>" class="btn btn-primary" style="margin-top: 25px;"><i class="fa fa-download"></i> Download All Customer Report</a>
                        </div>
                        <!-- <div class="form-group col-md-4">
                            <label>Customer<span class="spn_required">*</span></label>
                            <select name="customer_name" class="form-control select2 customer_select search_mq" id="customer_name">
                                <option value="">Select Customer</option>
                                <?php
                                if (isset($customers) && !empty($customers)) {
                                    foreach ($customers as $customer) {
                                        $selected = ($edit_mode && $data_info->customer_name == $customer->customer_id) ? 'selected' : '';
                                        echo "<option data-id=\"{$customer->customer_id}\" value=\"{$customer->customer_id}\" {$selected}>{$customer->customer_name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>From Date<span class="spn_required">*</span></label>
                            <input type="date" name="from_date" id="from_date" class="form-control datepicker search_mq" placeholder="From Date" value="">
                        </div>
                        <div class="col-md-3">
                            <label>End Date<span class="spn_required">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control datepicker search_mq" placeholder="End Date" value="">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="download_ledger" class="btn btn-primary" style="margin-top: 25px;"><i class="fa fa-download"></i> Download</button>
                        </div> -->
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>