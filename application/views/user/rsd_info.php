<?php
$DataID = 'UserID';

//$ClientType = array('name' => 'ClientType', 'id' => 'ClientType', 'value' => (isset($data_info) && $data_info->ClientType != "") ? $data_info->ClientType : set_value('ClientType'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",);

$AdjustmentAmount = array('name' => 'AdjustmentAmount', 'id' => 'AdjustmentAmount', 'value' => set_value('AdjustmentAmount'), 'minlength' => 1, 'size' => 30, 'class' => "form-control");
$CreditAmount = array('name' => 'CreditAmount', 'id' => 'CreditAmount', 'value' => set_value('CreditAmount'), 'minlength' => 1, 'value' => '', 'size' => 30, 'class' => "form-control",);


if (isset($data_info)) {
    $data_id = array('name' => $DataID, 'id' => $DataID, 'value' => (isset($data_info) && $data_info->id > 0) ? $data_info->id : "", 'type' => 'hidden',);
    //$EmailID = array('name' => 'EmailID', 'id' => 'EmailID', 'value' => (isset($data_info) && $data_info->EmailID != "") ? $data_info->EmailID : set_value('EmailID'), 'minlength' => 1, 'size' => 30, 'class' => "form-control", 'readonly' =>(isset($data_info) && $data_info->EmailID != "") ? 'readonly' : '');
    //$ContactPerson = array('name' => 'ContactPerson','type'=>'ContactPerson', 'id' => 'ContactPerson', 'value' => (isset($data_info) && $data_info->ContactPerson != "") ? $data_info->ContactPerson : set_value('ContactPerson'), 'minlength' => 1, 'size' => 30, 'class' => "form-control",'readonly' =>(isset($data_info) && $data_info->ContactPerson != "") ? 'readonly' : '');
}

$ItemID = array('name' => 'ItemID', 'id' => 'ItemID', 'class' => "form-control select2");

$submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);
$reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);
$form_attr = array('class' => 'default_form', 'id' => 'course_frm', 'name' => 'course_frm', 'autocomplete' => "off");
//print_r($data_info);die;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo $data_info->first_name . ' ' . $data_info->last_name; ?> RSD Information</h3>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                
            </div>
            <div class="grid-body no-border">
                <?php
                $this->load->view("includes/messages");
                ?>

                <?php echo validation_errors(); ?>
                <div class="form">

                    <div class="row">
                        <div class="col-md-6">
                            <h4>RSD Information</h4>
                            <table class="table">
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Reference</th>
                                </tr>
                                <?php
                                if (!empty($rsd_info)) {
                                    $total_rsd = 0;
                                    foreach ($rsd_info as $value) {
                                        $total_rsd = $total_rsd + $value->RSDAmount;
                                ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value->CreatedOn)); ?></td>
                                            <td>&#8377; <?php echo number_format($value->RSDAmount, 2); ?></td>
                                            <td><?php echo $value->TransType; ?></td>
                                            <td><?php echo $value->Reference; ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                            <td>Total RSD</td>
                                            <td>&#8377; <?php echo number_format($total_rsd, 2); ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4">No RSD Found</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Adjustment Information</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($adjustment_info)) {
                                        foreach ($adjustment_info as $single_adjustment) {
                                    ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($single_adjustment->CreatedOn)); ?></td>
                                                <td><?php echo $single_adjustment->TransType; ?></td>
                                                <td><?php echo $single_adjustment->Amount; ?></td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="3">No Adjustments Found</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <h4>RSD Forfeit Information</h4>
                            <table class="table">
                                <tr>
                                    <th>Date</th>
                                    <th>Auction Details</th>
                                    <th>Vehicle No.</th>
                                    <th>Bid Amount</th>
                                    <th>Before Forfeit Amount</th>
                                    <th>After Forfeit Amount</th>
                                </tr>
                                <?php
                                if (!empty($forfeit_info)) {
                                    $total_rsd = 0;
                                    foreach ($forfeit_info as $value) {
                                ?>
                                        <tr>
                                            <td><?php echo date('d-m-Y', strtotime($value->CreatedOn)); ?></td>
                                            <td><?php echo auction_detail_row($value->EventID); ?></td>
                                            <td><?php echo $value->CarNo; ?></td>
                                            <!--<td><?php /*echo live_auction_monitor_car_detail_row($value->CarID);*/ ?></td>-->
                                            <td>&#8377; <?php echo number_format($value->BidAmount, 2); ?></td>
                                            <td>&#8377; <?php echo number_format($value->BeforeForfeitedWalletAmount, 2); ?></td>
                                            <td>&#8377; <?php echo number_format($value->AfterForfeitedWalletAmount, 2); ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4">No RSD Found</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>



                    <?php echo form_open_multipart(base_url($this->controllers . '/submit-adjustment-form'), $form_attr); ?>
                    <?php
                    if (isset($data_info)) {
                        echo form_input($data_id);
                    }
                    ?>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label class="form-label">Credit Limit Amount:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($AdjustmentAmount);
                                ?>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">Buying Limit:<span class="spn_required">*</span></label>
                            <div class="input-with-icon  right">

                                <?php
                                echo form_input($CreditAmount);
                                ?>
                                <span class="buying_limit_span hidden">Buying Limit should not be more than 200</span>
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
<script>
    $(document).ready(function() {

        $(document).on("keyup", "#CreditAmount", function(event) {
            var value = $(this).val();
           
            if (value > 200) {
                // $('.adj_amnt').val(500);
                $('#submit_btn').attr('disabled', 'disabled');
                $('.buying_limit_span').removeClass('hidden');
                value = 200
                // $('#CreditAmount').val(200);
            } else {
                $('.buying_limit_span').addClass('hidden');
                $('#submit_btn').removeAttr('disabled', 'disabled');
            }

        });

        //        $(".BrandAdmin").hide();
        $('#ClientType').each(function() {
            if ($(this).val() == 'Bank') {
                $(".BankService").show();
                $(".Agreement").show();
                $(".Password").show();
            } else {
                $(".BankService").hide();
                $(".Agreement").hide();
                $(".Password").hide();
            }
        });

        $("#ClientType").click(function() {
            if ($(this).val() == 'Bank') {
                $(".BankService").show();
                $(".Agreement").show();
                $(".Password").show();
            } else {
                $(".BankService").hide();
                $(".Agreement").hide();
                $(".Password").hide();
            }
        });

    });
</script>