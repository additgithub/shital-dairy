<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 20px;
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
        height: 13px;
        width: 19px;
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
</style>
<div class="content ">

    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple ">
                <?php

                use Mpdf\Tag\Em;

                $this->load->view("includes/messages");
                ?>
                <div class="grid-title">
                    <h4>Buyer Name: <?php echo $details->first_name . ' ' . $details->last_name; ?></h4>

                    <!--                <div class="pull-right buttom_rights pos_btn"><div class="table-tools-actions"><a class="btn btn-primary open_my_property_image_form" href="javascript:;" data-control="property">Add New <i class="fa fa-plus"></i></a></div></div>-->
                </div>
                <div class="grid-body">
                    <input type="hidden" name="UserID" class="UserID" id="UserID" value="<?php echo $UserID; ?>">
                    <div class="row user-details">
                        <div class="col-md-6">
                            <div class="row-fluid">
                                <p> <b>First Name </b>: <span> <?php echo $details->first_name; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Last Name </b>: <span> <?php echo $details->last_name; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Email ID </b>: <span> <?php echo $details->email; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>MobileNo </b>: <span> <?php echo $details->mobile_no . ' - Verified'; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Address</b>: <span> <?php echo $details->Address; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Address 2 </b>: <span> <?php echo $details->Address2; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>City </b>: <span> <?php echo $details->CityName; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>State </b>: <span> <?php echo $details->StateName; ?> </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Pin Code </b>: <span> <?php echo $details->PinCode; ?> </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>RSD Amount </b>: <span><?php echo $details->Amount; ?> </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Referal Code </b>: <span><?php echo $details->ReferalCode; ?> </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Step Completed </b>: <span>
                                        <?php if ($details->StepCompleted == 1) {
                                            echo 'Personal Information';
                                        } else if ($details->StepCompleted == 2) {
                                            echo 'eKYC Verification';
                                        } else if ($details->StepCompleted == 3) {
                                            echo 'eSign';
                                        } else if ($details->StepCompleted == 4) {
                                            echo 'Registration Fees';
                                        }
                                        ?>
                                    </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Plan Name </b>: <span><?php echo (!empty($plan_details)) ? $plan_details->PlanName : '';  ?>  </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Buyer Code </b>: <span><?php echo (!empty($details)) ? $details->Code : '';  ?>  </span> </p>
                            </div>
                            <!-- <div class="row-fluid">
                                <p><b>Buying Interest</b>: <span> <?php if ($details->BuyingInterest == 1) {
                                                                        echo "Yes";
                                                                    } else {
                                                                        echo "No";
                                                                    } ?> </span></p>
                            </div> -->

                        </div>
                        <div class="col-md-6">
                            <!-- <div class="row-fluid">
                                <p><b>Selling Interest</b>:<span> <?php if ($details->SellingInterest == 1) {
                                                                        echo "Yes";
                                                                    } else {
                                                                        echo "No";
                                                                    } ?> </p>
                            </div> -->
                            <div class="row-fluid">
                                <p><b>Entity </b>:<span> <?php echo $details->Entity; ?> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Subscribe SMS </b>:<span>

                                        <?php
                                        if ($details->NewsLetterSubscribe == 1) {
                                            $st = "checked";
                                        } else {
                                            $st = "";
                                        }
                                        ?>

                                        <label class="switch user_admin_subscribe_feature" data-id="<?php echo $details->id ?>" data-control="user">
                                            <input type="checkbox" <?php echo $st ?>>
                                            <span class="slider round"></span>
                                        </label>

                                    </span>
                                </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Willing to Participate in </b> : <span><?php
                                                                                $TWO_WH = '2WH';
                                                                                $THREE_WH = '3WH';
                                                                                $FOUR_WH = '4WH';
                                                                                if ($details->$TWO_WH == 1) {
                                                                                    echo '2WH';
                                                                                } ?>
                                        <?php if ($details->$THREE_WH == 1) {
                                            echo '3WH';
                                        } ?>
                                        <?php if ($details->$FOUR_WH == 1) {
                                            echo '4WH';
                                        } ?>
                                        <?php if ($details->CV == 1) {
                                            echo 'CV';
                                        } ?>
                                        <?php if ($details->CE == 1) {
                                            echo 'CE';
                                        } ?>
                                        <?php if ($details->FE == 1) {
                                            echo 'FE';
                                        } ?></span> </p>
                            </div>

                            <!-- <div class="row-fluid">
                                <p><b> Address Proof </b>: <span><?php echo $details->AddressProof; ?> </span></p>
                            </div>
                            <div class="row-fluid">
                                <p><b>ID Proof </b>: <span><?php echo $details->IDProof; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>ID Proof Number :</b>:<span> <?php echo $details->IDProofNumber; ?></span> </p>
                            </div> -->
                            <div class="row-fluid">
                                <p><b>GST No </b>: <span><?php echo $details->GST; ?></span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Company Name </b> :<span><?php echo $details->CompanyName; ?> </span></p>
                            </div>
                            <!-- <div class="row-fluid">
                                <p><b>Remarks </b>: <span><?php echo $details->Remarks; ?></span> </p>
                            </div> -->

                            <div class="row-fluid">
                                <p><b>PAN Number </b>: <span><?php echo strtoupper($details->PAN); ?> </span> </p>
                            </div>
                            <div class="row-fluid">
                                <p><b>Name As Per PAN </b>: <span><?php echo strtoupper($details->NamePerPAN); ?> </span><a class="btn btn-primary" href="<?php echo BASE_URL . UPLOAD_FRONT_DIR . USER_PAN_DIR . $details->PANPDF; ?>" target="_blank" style="height: 23px;padding: 2px;">View PAN</a> </p>
                            </div>
                            <div class="row-fluid">
                                <?php
                                $submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);
                                $reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);
                                $form_attr = array('class' => 'default_form', 'id' => 'bid_edit_frm', 'name' => 'user_frm');

                                echo form_open_multipart(base_url($this->controllers . '/submit-pan-form'), $form_attr); ?>
                                <input type="hidden" name="user_id" value="<?php echo $details->id; ?>">
                                <p>
                                    <b>Upload Buyer PAN </b>: <b><input type="file" name="pan" id="pan" class="pan uploadpan form-control" style="line-height:12px !important; width:250px !important;"> </b>
                                </p>
                                <p>
                                    <span class="upload_pan_span"></span>
                                </p>
                                <?php echo form_close(); ?>

                            </div>
                            <?php
                            if ($details->DigiSignPDF != '') {
                            ?>
                                <div class="row-fluid">
                                    <p><b>eSigned Document </b>: <a class="btn btn-primary" href="<?php echo BASE_URL . UPLOAD_FRONT_DIR . USER_DIGISIGN_DOC . $details->DigiSignPDF; ?>" target="_blank" style="height: 23px;padding: 2px;">View eSigned Document</a> </p>
                                </div>
                            <?php
                            }
                            ?>

                            <?php
                            $submit_btn = array('name' => 'submit_btn', 'id' => 'submit_btn', 'value' => 'Submit', 'class' => 'btn btn-success btn-cons',);
                            $reset_btn = array('name' => 'cancel_btn', 'id' => 'cancel_btn', 'content' => 'Cancel', 'type' => 'reset', 'class' => 'btn btn-default',);
                            $form_attr = array('class' => 'default_form', 'id' => 'bid_edit_frm', 'name' => 'user_frm');

                            echo form_open_multipart(base_url($this->controllers . '/submit-state-form'), $form_attr); ?>

                            <div class="row-fluid">
                                <input type="hidden" name="UserID" class="UserID" id="UserID" value="<?php echo $UserID; ?>">
                                <p><b>Additional Mapping State </b>: <?php
                                                                        echo form_dropdown('StateName[]', $StateName, (isset($selected_user_states) && !empty($selected_user_states)) ? $selected_user_states : set_value('StateName'), 'style="line-height:12px !important; width:250px !important;" class="form-control select2" id="StateNameList" multiple');
                                                                        ?></p>
                                <?php echo form_submit($submit_btn); ?>
                            </div>
                            <?php echo form_close(); ?>

                            <?php
                            if (!empty($plan_details)) {
                            ?>
                                <div class="row-fluid">
                                    <p><b>Plan Start Date </b>: <?php echo date('d-m-Y', strtotime($plan_details->StartDate)); ?></p>
                                    <p><b>Plan End Date </b>: <?php echo date('d-m-Y', strtotime($plan_details->EndDate)); ?></p>
                                </div>
                            <?php
                            }
                            ?>



                        </div>

                    </div>

                </div>

                <!--                <div class="grid-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Profile Image :</b> 
                            <img src="<?php echo ROOT_URL; ?>/uploads/astrologer/<?php echo $details->ProfileImage; ?>" height="100" width="100">
                        </div>
                    </div>
                </div>-->

            </div>
        </div>
    </div>
</div>

<script>

</script>