<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller
{

    public $table_name = TBL_USERS;
    public $controllers = 'user';
    public $view_name = 'user';
    public $title = 'Buyer';
    public $PrimaryKey = 'id';

    function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        }
        $this->load->Model('Remove_records');
    }

    function index()
    {
        $data['extra_js'] = array('manage-user');
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
        $data['state_list'] = $this->Common->get_list(TBL_STATE, 'StateID', 'StateName');
        $data['city_list'] = array();
        $data['sr_yard_list'] = $this->Common->get_list(TBL_USERS, 'id', 'first_name','role_id IN (4,6)','last_name','Code');
        $data['type'] = ($this->input->get('type')) ? $this->input->get('type') : '';
        $this->load->view('main_content', $data);
    }

    function add()
    {
        $data['page_title'] = "Add New " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    function edit($id)
    {

        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data["data_info"] = $data_obj;
                $str = '';
                $join = array(
                    array('table'=>TBL_YARD_TEAM_MEMBER.' ytm','on'=>'ytm.UserID=usr.SRID','type'=>'LEFT'),
                    array('table'=>TBL_YARD.' yd','on'=>'yd.YardID=ytm.YardID','type'=>'LEFT'),
                    // array('table'=>TBL_USERS.' srusr','on'=>'srusr.id=usr.SRID','type'=>'LEFT'),
                );
                $sr_data = $this->Common->get_info($id,TBL_USERS.' usr','id','','StockyardName,FirstName,LastName,first_name,last_name,usr.Code,usr.SRID',$join);
                // print_r($ci->db->last_query());die;
                if($sr_data->SRID == 0){
                    $str = 'Direct';
                }
                elseif (!empty($sr_data->StockyardName)) {
                    $str = $sr_data->FirstName.' '.$sr_data->LastName.'<br />'.$sr_data->StockyardName . '('.$sr_data->Code.')';
                }
                else{
                    $sr = $this->Common->get_info($sr_data->SRID,TBL_USERS,'id','','first_name,last_name,Code');
                    $str = $sr->first_name.' '.$sr->last_name. '('.$sr->Code.')';
                }
                $data["source_name"] = $str;

                $data_found = 1;
            }
        }
        if ($data_found == 0) {
            redirect('/');
        }

        $data['page_title'] = "Edit " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    // function approved($id)
    // {
    //     if ($id > 0) {

    //         $value = $this->input->post('value');
    //         $IsApproved = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'subscription');
    //         if ($value == 0) {
    //             $approved = 0;
    //             $status = "ok";
    //             $heading = "Success";
    //             $message = "User Unsubscribed";
    //         } else {
    //             $approved = 1;
    //             $status = "ok";
    //             $heading = "Success";
    //             $message = "User Subscribed";
    //         }
    //         $data = array(
    //             "subscription" => $value,
    //         );

    //         if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
    //             $MembershipNumber = randomNumber();
    //             $StartDate = date("Y-m-d");
    //             $membership_data = array(
    //                 "MembershipNumber" => $MembershipNumber,
    //                 "UserID" => $id,
    //                 "StartDate" => $StartDate,
    //                 "EndDate" => date("Y-m-d", strtotime("+1 year")),
    //                 "CreatedBy" => $this->tank_auth->get_user_id(),
    //                 "CreatedOn" => date("Y-m-d H:i:s"),
    //             );
    //             $this->Common->add_info(TBL_USER_MEMBERSHIP, $membership_data);
    //             // $Amount = 5000;
    //             // $wallet_data = array(
    //             //     "UserID" => $id,
    //             //     "Amount" => $Amount,
    //             //     "BuyingLimit" => $Amount * 100,
    //             //     "CreatedBy" => $this->tank_auth->get_user_id(),
    //             //     "CreatedOn" => date("Y-m-d H:i:s"),
    //             // );
    //             // $this->Common->add_info(TBL_USER_WALLET, $wallet_data);
    //             //                print_r($membership_data);die;
    //             $response = array("status" => $status, "heading" => $heading, "message" => $message);
    //             echo json_encode($response);
    //             die;
    //         }
    //     }
    // }

    //New approved function with payment paid condition - Hatim - 03-10-2023
    function approved($id)
    {
        if ($id > 0) {

            $value = $this->input->post('value');
            $join = array(
                array('table'=>TBL_TRANSACTION_LOG.' tr','on'=>'tr.UserID=u.id','type'=>'LEFT')
            );
            $IsApproved = $this->Common->get_info($id, $this->table_name.' u', $this->PrimaryKey, 'PaymentStatus="Paid"', '*',$join);
            if(!empty($IsApproved)){
                if ($value == 0) {
                    $approved = 0;
                    $status = "ok";
                    $heading = "Success";
                    $message = "User Unsubscribed";
                } else {
                    $approved = 1;
                    $status = "ok";
                    $heading = "Success";
                    $message = "User Subscribed";
                }
                $data = array(
                    "subscription" => $value,
                );
    
                if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                    $MembershipNumber = randomNumber();
                    $StartDate = date("Y-m-d");
                    $membership_data = array(
                        "MembershipNumber" => $MembershipNumber,
                        "UserID" => $id,
                        "StartDate" => $StartDate,
                        "EndDate" => date("Y-m-d", strtotime("+1 year")),
                        "CreatedBy" => $this->tank_auth->get_user_id(),
                        "CreatedOn" => date("Y-m-d H:i:s"),
                    );
                    $this->Common->add_info(TBL_USER_MEMBERSHIP, $membership_data);
                    // $Amount = 5000;
                    // $wallet_data = array(
                    //     "UserID" => $id,
                    //     "Amount" => $Amount,
                    //     "BuyingLimit" => $Amount * 100,
                    //     "CreatedBy" => $this->tank_auth->get_user_id(),
                    //     "CreatedOn" => date("Y-m-d H:i:s"),
                    // );
                    // $this->Common->add_info(TBL_USER_WALLET, $wallet_data);
                    //                print_r($membership_data);die;
                    $response = array("status" => $status, "heading" => $heading, "message" => $message);
                    echo json_encode($response);
                    die;
                }
            }
            else{
                $response = array("status" => 'error', "heading" => 'Payment Pending', "message" => 'User payment not done.');
                echo json_encode($response);
                die;
            }
        }
    }

    function view_rsd($id)
    {

        $data_found = 0;
        if ($id > 0) {
            $wallte_join = array(
                array("table" => TBL_USER_WALLET . " w", "on" => "u.id = w.UserID", "type" => "LEFT"),
            );
            $data_obj = $this->Common->get_info($id, $this->table_name . ' u', 'u.' . $this->PrimaryKey, '', '*', $wallte_join);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data["data_info"] = $data_obj;
                $join = array(
                    array("table" => TBL_USERS . " u", "on" => "t.UserID = u.id", "type" => "LEFT"),
                    array("table" => TBL_USER_WALLET . " w", "on" => "t.UserID = w.UserID", "type" => "LEFT"),
                );
                $data["rsd_info"] = $this->Common->get_all_info('', TBL_TRANSACTION_LOG . ' t', 't.TransLogID', 't.TransType = "RSD" AND  t.UserID ="' . $id . '"', '*,t.CreatedOn', false, $join, 't.TransLogID', array('field' => 't.TransLogID', 'order' => 'DESC'));

                $join = array(
                    array("table" => TBL_T_CARS . " c", "on" => "c.CarID=fh.CarID", "type" => "LEFT"),
                );
                $data["forfeit_info"] = $this->Common->get_all_info($id, TBL_USER_FORFEIT_HISTORY. ' fh', 'UserID', '', 'fh.BidAmount,fh.EventID,fh.CreatedOn,c.CarID,c.CarNo,BeforeForfeitedWalletAmount,AfterForfeitedWalletAmount', false, $join, false, array('field' => 'ForfeitID', 'order' => 'DESC'));

                // print_r($data["forfeit_info"]);die;
                $data["adjustment_info"] = $this->Common->get_all_info('', TBL_TRANSACTION_LOG . ' t', 't.TransLogID', 't.TransType != "RSD" AND t.TransType != "Registration" AND  t.UserID ="' . $id . '"', '*', false);
                // $data["adjustment_info"] = $data_obj;
                $data_found = 1;
            }
        }
        if ($data_found == 0) {
            redirect('/');
        }

        $data['page_title'] = "Edit " . $this->title;
        $this->load->view($this->view_name . '/rsd_info', $data);
    }

    function submit_adjustment_form()
    {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                ->set_rules('AdjustmentAmount', 'Adjustment Amount', 'required')
                ->set_rules('CreditAmount', 'Credit Amount', 'required')

                ->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {



                $id = ($this->input->post('UserID') && $this->input->post('UserID') > 0) ? $this->input->post('UserID') : 0;
                $wallet_limit = $this->Common->get_info($id, TBL_USER_WALLET, 'UserID', false, '*');
                if (!empty($wallet_limit)) {

                    if ($wallet_limit->Amount < 10000) {
                        $response = array("status" => "error", "message" => "User has not purchased RSD yet..");
                        echo json_encode($response);
                        die;
                    }
                    $available_wallet_limit = $wallet_limit->BuyingLimit + $this->input->post('AdjustmentAmount');
                    $available_vehicle_limit = $wallet_limit->VehicleLimit + $this->input->post('CreditAmount');
                    $wallet_data = array(
                        'BuyingLimit' => $available_wallet_limit,
                        'VehicleLimit' => $available_vehicle_limit
                    );

                    if ($update_wallet = $this->Common->update_info($id, TBL_USER_WALLET, $wallet_data, 'UserID')) {
                        if ($this->input->post('AdjustmentAmount') > 0) {


                            $user_transaction_log_data = array(
                                "UserID" => $id,
                                "TransactionID" => 'RSD',
                                "TransType" => 'Adjustment',
                                "Amount" => $this->input->post('AdjustmentAmount'),
                                "Reference" => 'Admin Recharge',
                                "CreatedOn" => date('Y-m-d H:i:s'),
                                "CreatedBy" => $this->tank_auth->get_user_id(),
                            );
                            if ($transaction_id = $this->Common->add_info(TBL_TRANSACTION_LOG, $user_transaction_log_data)) {
                                $user_rsd_log_data = array(
                                    "UserID" => $id,
                                    "TransLogID" => $transaction_id,
                                    "BeforeBuyingLimit" => $wallet_limit->BuyingLimit,
                                    "AfterBuyingLimit" => ($wallet_limit->BuyingLimit + $this->input->post('AdjustmentAmount')),
                                    "BeforeVehicleLimit" => $wallet_limit->VehicleLimit,
                                    "AfterVehicleLimit" => ($wallet_limit->VehicleLimit + $this->input->post('CreditAmount')),
                                    "Reason" => 'Admin Recharge',
                                    "CreatedOn" => date('Y-m-d H:i:s'),
                                    "CreatedBy" => $this->tank_auth->get_user_id(),
                                );
                                $this->Common->add_info(TBL_USER_RSD_HISTORY_LOG, $user_rsd_log_data);
                            }
                        }
                        $response = array("status" => "ok", "message" => "Adjustment Added sucessfully..");
                    } else {
                        $response = array("status" => "error", "message" => "Adjustment not Added sucessfully..");
                    }
                } else {
                    $response = array("status" => "error", "message" => "User has not purchased RSD yet..");
                    echo json_encode($response);
                    die;
                    $wallet_data = array(
                        'UserID' => $id,
                        'Amount' => 0,
                        'Mode' => 'Recharge',
                        'BuyingLimit' => $this->input->post('AdjustmentAmount'),
                        'AdjAmount' => 0,
                        'CreatedOn' => date('Y-m-d H:i:s'),
                        'CreatedBy' => $this->tank_auth->get_user_id(),
                    );

                    if ($update_wallet = $this->Common->add_info(TBL_USER_WALLET, $wallet_data)) {
                        if ($this->input->post('AdjustmentAmount') > 0) {
                            $user_transaction_log_data = array(
                                "UserID" => $id,
                                "TransactionID" => 'RSD',
                                "TransType" => 'Adjustment',
                                "Amount" => $this->input->post('AdjustmentAmount'),
                                "Reference" => 'Admin Recharge',
                                "CreatedOn" => date('Y-m-d H:i:s'),
                                "CreatedBy" => $this->tank_auth->get_user_id(),
                            );
                            if ($transaction_id = $this->Common->add_info(TBL_TRANSACTION_LOG, $user_transaction_log_data)) {
                                $user_rsd_log_data = array(
                                    "UserID" => $id,
                                    "TransLogID" => $transaction_id,
                                    "BeforeBuyingLimit" => $this->input->post('AdjustmentAmount'),
                                    "AfterBuyingLimit" => $this->input->post('AdjustmentAmount'),
                                    "BeforeVehicleLimit" =>  $this->input->post('CreditAmount'),
                                    "AfterVehicleLimit" => $this->input->post('CreditAmount'),
                                    "Reason" => 'Admin Recharge',
                                    "CreatedOn" => date('Y-m-d H:i:s'),
                                    "CreatedBy" => $this->tank_auth->get_user_id(),
                                );
                                $this->Common->add_info(TBL_USER_RSD_HISTORY_LOG, $user_rsd_log_data);
                            }
                        }
                        $response = array("status" => "ok", "message" => "Adjustment Added  sucessfully..");
                    } else {
                        $response = array("status" => "error", "message" => "Adjustment not Added sucessfully..");
                    }
                }
            } else {
                $errors = $this->form_validation->error_array();
                $response['error'] = $errors;
            }
            echo json_encode($response);
            die;
        }
    }
    function update_mobileno_form()
    {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                ->set_rules('Mobileno', 'Mobile No', 'required')
                ->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {
                $id = ($this->input->post('id') && $this->input->post('id') > 0) ? $this->input->post('id') : 0;
                $post_data = array(
                    "mobile_no" => $this->input->post('Mobileno')
                );
                if($id){
                    if ($this->Common->check_is_exists($this->table_name, $post_data['mobile_no'], "mobile_no", $id, $field = $this->PrimaryKey)) :
                        $response['heading'] = 'Dealer details already exists';
                        $response['message'] = 'Dealer Mobile No already exists, Pls Use another Number...!';
                        echo json_encode($response);
                        die;
                    endif;
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)) :
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else :
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                }else{
                    $response = array("status" => "error", "heading" => "Unknown Error", "message" => "User Not Found");
                }
            } else {
                $errors = $this->form_validation->error_array();
                $response['error'] = $errors;
            }
            echo json_encode($response);
            die;
        }
    }
    function submit_pan_form()
    {

        $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
        $user_id = $this->input->post('user_id');
        if (empty($_FILES['pan']['name'])) {
            $response = array("status" => "error", "message" => "Pan File Required..");
        } else {
            $post_data = array();
            //Aws file upload -Hatim - 27-09-2023
            $file_data = upload_file_aws_without_watermark('pan', USER_PAN_DIR, '');
            if (is_array($file_data) && $file_data['fileName'] != "" && $file_data['status'] == "ok") {
                $post_data['PANPDF'] = $file_data['fileName'];
                if ($this->Common->update_info($user_id, TBL_USER_INFOMATION, $post_data, 'UserID')) {
                }
            } else {
                $response['message'] = $file_data['message'];
                echo json_encode($response);
                die;
            }

            $response = array("status" => "ok", "message" => "Pan Uploaded  sucessfully..");
        }
        echo json_encode($response);
        die;
    }
    function activated($id)
    {
        if ($id > 0) {
            $IsFeatured = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'isApproved');
            if ($IsFeatured->isApproved == 0) {
                $activated = 1;
                $status = "ok";
                $heading = "Success";
                $message = "User Approved";
            } else {
                $activated = 0;
                $status = "ok";
                $heading = "Success";
                $message = "User Rejected";
            }
            $data = array(
                "isApproved" => $activated,
            );

            if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                $response = array("status" => $status, "heading" => $heading, "message" => $message);
                echo json_encode($response);
                die;
            }
        }
    }
    function subscribed($id)
    {
        if ($id > 0) {
            $IsFeatured = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'NewsLetterSubscribe');
            if ($IsFeatured->NewsLetterSubscribe == 0) {
                $activated = 1;
                $status = "ok";
                $heading = "Success";
                $message = "User News Letter Subscribed";
            } else {
                $activated = 0;
                $status = "ok";
                $heading = "Success";
                $message = "User News Letter Unsubscribed";
            }
            $data = array(
                "NewsLetterSubscribe" => $activated,
            );

            if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                $response = array("status" => $status, "heading" => $heading, "message" => $message);
                echo json_encode($response);
                die;
            }
        }
    }
    function manage()
    {

        if ($this->tank_auth->get_user_role_id() == 5) {
            $join = array(
                array("table" => TBL_EMPLOYEES . " e", "on" => "e.UserID = u.id", "type" => "LEFT"),
            );
            $employee_details = $this->Common->get_info($this->tank_auth->get_user_id(), TBL_USERS . ' u', 'u.id', '', '*', $join);
            // $this->datatables->where("StateID", $employee_details->StateID);
            $this->datatables->where("(StateID = ".$employee_details->StateID." OR StateID IN (SELECT StateID FROM ".TBL_EMPLOYEE_STATE." WHERE UserID=".$this->tank_auth->get_user_id().") OR StateID IS NULL)");
        }

        if ($this->input->post('State') != '') {
            $this->datatables->where("StateID", $this->input->post('State'));
        }
        if ($this->input->post('City') != '') {
            $this->datatables->where("City", $this->input->post('City'));
        }
        if ($this->input->post('SourceID') != '') {
            $this->datatables->where("SRID", $this->input->post('SourceID'));
        }
        if ($this->input->post('source_type') != '') {
            if($this->input->post('source_type') == 0){

                $this->datatables->where("SRID", 0);
            }else{
                $this->datatables->where("SRID > 0");

            }
        }
        if ($this->input->post('status') != '') {
            // if ($this->input->post('status') == 'processing') {
            //     $this->datatables->where("StepCompleted < 2");
            // } else if ($this->input->post('status') == 'completed') {
            //     $this->datatables->where("StepCompleted >= 2");
            // }

            // 13-12-2023 Ramiz 
            if ($this->input->post('status') == 'active') {
                $this->datatables->where("d.StepCompleted >= 3 AND d.id IN (SELECT UserID FROM ".TBL_USER_MEMBERSHIP." where DATE(EndDate) >= '".date('Y-m-d')."')");
            } else if ($this->input->post('status') == 'expired') {
                $this->datatables->where("d.StepCompleted >= 3 AND d.id IN (SELECT UserID FROM ".TBL_USER_MEMBERSHIP." where DATE(EndDate) < '".date('Y-m-d')."')");
            }else if ($this->input->post('status') == 'processing') {
                $this->datatables->where("d.StepCompleted < 3");
            }
            
        }
        // $this->datatables->select($this->PrimaryKey . ',(CASE WHEN Code IS NULL THEN CONCAT(first_name, " ", last_name) ELSE CONCAT(first_name, " ", last_name,"<br>",Code) END) as FullName,email,mobile_no');
        $this->datatables->select($this->PrimaryKey . ',(CASE WHEN Code IS NULL THEN CONCAT(first_name, " ", last_name) ELSE CONCAT(first_name, " ", last_name,"<br>",Code) END) as FullName,email,mobile_no,"" as Source,(CASE WHEN SRID = 0 THEN "Web" ELSE "App" END) as SourceType');
        $this->datatables->where('role_id = 2');
        $this->datatables->join(TBL_USER_INFOMATION . ' ui', 'ui.UserID = d.id', 'LEFT');
        $this->datatables->from($this->table_name . ' d')
            //                ->add_column('activated', $this->user_active_row('$1'), $this->PrimaryKey)
            //                ->add_column('Status', $this->user_subscription_row('$1'), $this->PrimaryKey)
            ->add_column('isApproved', '$1', 'user_approved_row(' . $this->PrimaryKey . ')')
            ->add_column('subscription', '$1', 'user_active_row(' . $this->PrimaryKey . ')')
            ->add_column('Status', '$1', 'user_subscription_row(' . $this->PrimaryKey . ')')
            ->add_column('id', $this->add_detail_row('$1'),  $this->PrimaryKey)
            ->add_column('user_rights', $this->add_user_rights_row('$1'),  $this->PrimaryKey)
            ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
        $this->datatables->edit_column('Source', '$1', 'dealer_source('.$this->PrimaryKey.')');
        $this->datatables->order_by('id', 'DESC');
        $this->datatables->unset_column($this->PrimaryKey);
        echo $this->datatables->generate();
    }

    //    function user_active_row($id) {
    //        $action = <<<EOF
    //        <a data-original-title="Approved Customer" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal approve_feature btn-mini" data-id="{$id}" data-value="1" data-control="user" data-method="approved"><i class="fa fa-check"></i></a>
    //EOF;
    //
    //        return $action;
    //    }
    function add_detail_row($id)
    {
        //         $action = <<<EOF
        //             <div class="tooltip-top">
        //                 <a data-original-title="See {$this->title} Details" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini user_details" data-id="{$id}"><i class="fa fa-user"></i></a>
        //             </div>
        // EOF;
        $url = base_url('user/get_details/' . $id);
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="See {$this->title} Details" target="_blank" data-placement="top" data-toggle="tooltip" href="{$url}" class="btn btn-xs btn-default btn-equal btn-mini" data-id="{$id}"><i class="fa fa-user"></i></a>
            </div>
EOF;
        return $action;
    }
    function add_user_rights_row($id)
    {
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="See {$this->title} Details" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini user_rights_details" data-id="{$id}"><i class="fa fa-info-circle"></i></a>
            </div>
EOF;
        return $action;
    }
    function action_row($id)
    {
        $action = <<<EOF
            <div class="tooltip-top">    
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>            
                <a data-original-title="View RSD {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_rsd_form_form" data-id="{$id}" data-control="{$this->controllers}" data-method="view_rsd"><i class="fa fa-eye"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }

    function get_details($user_id)
    {
        $UserID = $user_id;
        $join = array(
            array("table" => TBL_USER_INFORMATION . " i", "on" => "i.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_PINCODE . " pincode", "on" => "u.PinCode = pincode.PincodeID", "type" => "LEFT"),
            array("table" => TBL_CITY . " c", "on" => "c.CityID = u.City", "type" => "LEFT"),
            array("table" => TBL_STATE . " s", "on" => "s.StateID = u.StateID", "type" => "LEFT"),
            array("table" => TBL_USER_WALLET . " w", "on" => "w.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_USER_PAN_VERIFY_DATA . " pan_data", "on" => "u.id = pan_data.UserID", "type" => "LEFT"),
            array("table" => TBL_REFERAL_CODE . " ref", "on" => "u.ReferalCodeID = ref.ID", "type" => "LEFT"),
        );
        $data["details"] = $this->Common->get_info($UserID, TBL_USERS . " u", 'u.id', false, '*,c.CityName,s.StateName,pan_data.Name as NamePerPAN,ref.ReferalCode,pincode.PinCode as PinCode,u.Address,u.Address2', $join);
        // echo '<pre>';
        // print_r($data["details"]);die;
        $data['plan_details'] = $this->Common->get_info($UserID, TBL_USER_MEMBERSHIP . " u", 'u.UserID', false, '*');
        $data['UserID'] = $UserID;
        $selected_user_states = array();
        $data['user_states'] = $this->Common->get_all_info($UserID, TBL_USER_STATE . " u", 'u.UserID');
        if (!empty($data['user_states'])) {
            foreach ($data['user_states'] as $single_State) {
                $selected_user_states[] = $single_State->StateID;
            }
        }
        // echo '<pre>'; print_r($data);die;

        // $data['page_title'] = "Manage " . $this->title . " Image";
        // $this->load->view($this->view_name . '/view_details', $data);
        $data['selected_user_states'] = $selected_user_states;
        $data["StateName"] = $this->Common->get_list(TBL_STATE, 'StateID ', 'StateName', '', false, false, false, array('field' => 'StateName', 'order', 'ASC'));
        $data['extra_js'] = array('manage-user');
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/view_details';
        $data['state_list'] = $this->Common->get_list(TBL_STATE, 'StateID', 'StateName');
        $data['city_list'] = array();
        $this->load->view('main_content', $data);
    }
    function get_user_rights_details()
    {
        $join = array(
            array("table" => TBL_USER_INFORMATION . " i", "on" => "i.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_APP_CITY . " c", "on" => "c.CityID = u.City", "type" => "LEFT"),
            array("table" => TBL_APP_STATE . " s", "on" => "s.StateID = u.StateID", "type" => "LEFT"),
            array("table" => TBL_USER_WALLET . " w", "on" => "w.UserID = u.id", "type" => "LEFT"),
        );
        $data["details"] = $this->Common->get_info($this->input->post('UserID'), TBL_USERS . " u", 'u.id', false, 'u.id,u.user_rights', $join);
        $data['UserID'] = $this->input->post('UserID');
        //         echo '<pre>'; print_r($data);die;

        $data['page_title'] = "Manage " . $this->title . " Image";
        $this->load->view($this->view_name . '/view_rights_details', $data);
    }
    function submit_rights_form()
    {
        //         print_r($this->input->post());die;

        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $this->form_validation->set_rules('user_rights[]', 'Customer Rights', 'required');

            if ($this->form_validation->run()) {
                $id = ($this->input->post('UserID') && $this->input->post('UserID') > 0) ? $this->input->post('UserID') : 0;
                //                echo $id;die;   
                $post_data = array(
                    "user_rights" => implode(',', $this->input->post('user_rights[]')),
                );
                //                        print_r($post_data);die;
                if ($id > 0) {
                    if ($this->Common->update_info($id, TBL_USERS, $post_data, 'id')) {
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Users Rights Updated successfully.");
                    } else {
                        $response = array("status" => "ok", "heading" => "Not Updated successfully...", "message" => "Users Rights Not Updated successfully.");
                    }
                } else {
                    if ($this->Common->add_info(TBL_USERS, $post_data)) {
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Users Rights Added successfully.");
                    } else {
                        $response = array("status" => "ok", "heading" => "Not Added successfully...", "message" => "Users Rights Not Added successfully.");
                    }
                }
            } else {
                $errors = $this->form_validation->error_array();
                $response['error'] = $errors;
            }
            echo json_encode($response);
            die;
        }
    }
    function submit_state_form()
    {
        //         print_r($this->input->post());die;

        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $this->form_validation->set_rules('StateName[]', 'Additional Mapping State', 'required');

            if ($this->form_validation->run()) {
                $id = ($this->input->post('UserID') && $this->input->post('UserID') > 0) ? $this->input->post('UserID') : 0;

                if ($id > 0) {
                    $States = $this->input->post('StateName');
                    if (!empty($States)) {
                        $data_remove = $this->Remove_records->remove_data($id, 'UserID', TBL_USER_STATE);
                        foreach ($States as $single_state) {
                            $dealer_state_data = array(
                                "UserID" => $id,
                                "StateID" => $single_state,
                                "CreatedOn" => date("Y-m-d H:i:s"),
                                "CreatedBy" => $this->tank_auth->get_user_id()
                            );
                            if ($this->Common->add_info(TBL_USER_STATE, $dealer_state_data)) {
                                $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Users Rights Updated successfully.");
                            } else {
                                $response = array("status" => "ok", "heading" => "Not Updated successfully...", "message" => "Users Rights Not Updated successfully.");
                            }
                        }
                    }
                }
            } else {
                $errors = $this->form_validation->error_array();
                $response['error'] = $errors;
            }
            echo json_encode($response);
            die;
        }
    }

    function export_records()
    {
        $this->load->library('PHPExcel');
        $join = array(
            array("table" => TBL_USER_INFORMATION . " i", "on" => "i.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_PINCODE . " pincode", "on" => "u.PinCode = pincode.PincodeID", "type" => "LEFT"),
            array("table" => TBL_CITY . " c", "on" => "c.CityID = u.City", "type" => "LEFT"),
            array("table" => TBL_STATE . " s", "on" => "s.StateID = u.StateID", "type" => "LEFT"),
            // array("table" => TBL_USER_WALLET . " w", "on" => "w.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_USER_PAN_VERIFY_DATA . " pan_data", "on" => "u.id = pan_data.UserID", "type" => "LEFT"),
            array("table" => TBL_USER_MEMBERSHIP . " membership", "on" => "u.id = membership.UserID", "type" => "LEFT"),
            array('table'=>TBL_YARD_TEAM_MEMBER.' ytm','on'=>'ytm.UserID=u.SRID','type'=>'LEFT'),
            array('table'=>TBL_YARD.' yd','on'=>'yd.YardID=ytm.YardID','type'=>'LEFT'),
        );

        $user_where = ' AND 1=1';
        if ($this->tank_auth->get_user_role_id() == 3) {
        } else if ($this->tank_auth->get_user_role_id() == 5 && $this->tank_auth->get_user_id() != 1544) {
            $employee_details = $this->Common->get_info($this->tank_auth->get_user_id(), TBL_USERS, 'id');
            $user_where .= " AND (u.StateID=" . $employee_details->StateID . " OR u.StateID  IN (SELECT StateID FROM " . TBL_EMPLOYEE_STATE . " WHERE UserID=" . $this->tank_auth->get_user_id() . ") OR u.StateID IS NULL)";
        } else {
            // $where = ' AND e.isApproved=1';
            $where = ' AND 1=1';
        }
        $where = '1=1';
        // if ($this->input->get('status') != '') {
        //     if ($this->input->get('status') == 'processing') {
        //         $where .= ' AND u.StepCompleted < 2';
        //     } else if ($this->input->get('status') == 'completed') {
        //         $where .= ' AND u.StepCompleted >= 2';
        //     }
        // }
        //Ramiz Girach - 13-12-2023
        if ($this->input->get('status') != '') {
            if ($this->input->get('status') == 'active') {
                $where .= " AND u.StepCompleted >= 3 AND u.id IN (SELECT UserID FROM ".TBL_USER_MEMBERSHIP." where DATE(EndDate) >= '".date('Y-m-d')."')";
            } else if ($this->input->get('status') == 'expired') {
                $where .= " AND u.StepCompleted >= 3 AND u.id IN (SELECT UserID FROM ".TBL_USER_MEMBERSHIP." where DATE(EndDate) < '".date('Y-m-d')."')";
            }else if ($this->input->get('status') == 'processing') {
                $where .= " AND u.StepCompleted < 3";
            }
        }
        if ($this->input->get('source_type') != '') {
            if($this->input->get('source_type') == 0){
                $where .= ' AND u.SRID = 0';
            }else{
                $where .= ' AND u.SRID > 0';

            }
        }


        $users = $this->Common->get_all_info(2, TBL_USERS . ' u', 'role_id', $where . $user_where, '*,c.CityName,s.StateName,pan_data.Name as NamePerPAN,pincode.PinCode as PinCode,u.Address,u.Address2,yd.StockyardName,ytm.FirstName,ytm.LastName,u.Code as Code', false, $join,'u.id');
        
        
        if (!empty($users)) {
            // include PHPExcel
            //        require('PHPExcel.php');
            // create new PHPExcel object
            $objPHPExcel = new PHPExcel;
            // set default font
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
            // set default font size
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
            // create the writer
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

            /**
             * Define currency and number format.
             */
            // currency format, € with < 0 being in red color
            //                $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
            // number format, with thousands separator and two decimal points.
            //                $numberFormat = '#,#0.##;[Red]-#,#0.##';
            // writer already created the first sheet for us, let's get it
            $objSheet = $objPHPExcel->getActiveSheet();
            // rename the sheet
            $objSheet->setTitle('Buyer List');

            // let's bold and size the header font and write the header
            // as you can see, we can specify a range of cells, like here: cells from A1 to A4
            $objSheet->getStyle('A1:S1')->getFont()->setBold(true)->setSize(11);

            // write header
            $objSheet->getCell('A1')->setValue('Buyer Name');
            $objSheet->getCell('B1')->setValue('Buyer Email');
            $objSheet->getCell('C1')->setValue('Buyer Mobile No');
            $objSheet->getCell('D1')->setValue('Buyer Step Completed');
            $objSheet->getCell('E1')->setValue('Address 1');
            $objSheet->getCell('F1')->setValue('Address 2');
            $objSheet->getCell('G1')->setValue('State');
            $objSheet->getCell('H1')->setValue('City');
            $objSheet->getCell('I1')->setValue('Pincode');
            $objSheet->getCell('J1')->setValue('Entity');
            $objSheet->getCell('K1')->setValue('GST No');
            $objSheet->getCell('L1')->setValue('Company Name');
            $objSheet->getCell('M1')->setValue('PAN Number');
            $objSheet->getCell('N1')->setValue('Name As Per PAN');
            $objSheet->getCell('O1')->setValue('Membership Start Date');
            $objSheet->getCell('P1')->setValue('Membership End Date');
            $objSheet->getCell('Q1')->setValue('Willing To Participate');
            $objSheet->getCell('R1')->setValue('Source Type');
            $objSheet->getCell('S1')->setValue('Source Name');
            $i = 2;
            foreach ($users as $single) {
                $step_completed = "Personal Information";
                if ($single->StepCompleted == 1) {
                    $step_completed = 'Personal Information';
                } else if ($single->StepCompleted == 2) {
                    $step_completed = 'eKYC Verification';
                } else if ($single->StepCompleted == 3) {
                    $step_completed = 'eSign';
                } else if ($single->StepCompleted == 4) {
                    $step_completed = 'Registration Fees';
                }
                $SR_str = '';
                if($single->SRID == 0){
                    $SR_str = 'Direct';
                }
                elseif (!empty($single->StockyardName)) {
                    $SR_str = $single->FirstName.' '.$single->LastName.'<br />'.$single->StockyardName . '('.$single->Code.')';
                }
                else{
                    $sr = $this->Common->get_info($single->SRID,TBL_USERS,'id','','first_name,last_name,Code');
                    $SR_str = $sr->first_name.' '.$sr->last_name. '('.$sr->Code.')';
                }

                $objSheet->setCellValueExplicit('A' . $i, $single->first_name . ' ' . $single->last_name . ' ('.$single->Code.')', PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('B' . $i, $single->email, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('C' . $i, $single->mobile_no, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('D' . $i, $step_completed, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('E' . $i, $single->Address, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('F' . $i, $single->Address2, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('G' . $i, $single->StateName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('H' . $i, $single->CityName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('I' . $i, $single->PinCode, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('J' . $i, $single->Entity, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('K' . $i, $single->GST, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('L' . $i, $single->CompanyName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('M' . $i, $single->PAN, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('N' . $i, $single->NamePerPAN, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('O' . $i, date('d-m-Y', strtotime($single->StartDate)), PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('P' . $i, date('d-m-Y', strtotime($single->EndDate)), PHPExcel_Cell_DataType::TYPE_STRING);
                $TWO_WH = '2WH';
                $THREE_WH = '3WH';
                $FOUR_WH = '4WH';
                $willing_to_participate = '';
                if ($single->$TWO_WH == 1) {
                    $willing_to_participate = '2WH';
                }
                if ($single->$THREE_WH == 1) {
                    $willing_to_participate .= ' 3WH';
                }
                if ($single->$FOUR_WH == 1) {
                    $willing_to_participate .= ' 4WH';
                }
                if ($single->CV == 1) {
                    $willing_to_participate .= ' CV';
                }
                if ($single->CE == 1) {
                    $willing_to_participate .= ' CE';
                }
                if ($single->FE == 1) {
                    $willing_to_participate .= ' FE';
                }
                $objSheet->setCellValueExplicit('Q' . $i, $willing_to_participate, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('R' . $i, (($single->SRID > 0) ? 'App' : 'Web'), PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('S' . $i, $SR_str, PHPExcel_Cell_DataType::TYPE_STRING);

                $i++;
            }

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            //                $objSheet->getColumnDimension('A3')->setAutoSize(true);
            // write the file
            $fileName = 'Buyer_list_' . date('d-m-Y') . '.xlsx';
            //        $objWriter->save('test.xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect('commission-report');
        }
    }


    function upload_excel()
    {
        $data['page_title'] = "Add New " . $this->title;
        $this->load->view($this->view_name . '/upload_form', $data);
    }

    function submit_upload_form()
    {
        //        print_r($_FILES['incentive_excel']);die;
        if ($_FILES['sr_map_excel']['name'] == '') {
            $response = array(
                "status" => "error", "heading" => "Please Select Excel File...",
                "message" => "Please Select Excel File."
            );
        } else {
            //                    print_r($_FILES);die;

            $this->load->library('PHPExcel');
            $inputFileName = $_FILES["sr_map_excel"]["tmp_name"];
            $file_type = $_FILES['sr_map_excel']['type'];
            $config['upload_path'] = UPLOAD_DIR;
            $config['allowed_types'] = 'xls|xlsx';
            $config['max_size'] = '8192';
            $config['remove_spaces'] = TRUE;  //it will remove all spaces
            $config['encrypt_name'] = TRUE;   //it wil encrypte the original file name
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('sr_map_excel')) {
                $error = array('error' => $this->upload->display_errors());
                $response = array(
                    "status" => "error", "heading" => "Please Select Excel File...",
                    "message" => $this->upload->display_errors()
                );
                //               $this->session->set_flashdata('error',$error['error']);
                //               redirect('controller_name/function_name','refresh');
            } else {
                try {
                    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    //                        print_r($inputFileName);die;
                    $objPHPExcel = $objReader->load($inputFileName);
                    //                print_r($objPHPExcel);
                    //                die('576');
                    //                die('575');
                    //                print_r($objPHPExcel);
                    //                die;
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }

                $incentive_data = array();

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $row = 0;
                $logs = array();
                $invalidData = array();
                $invalidDataColor = array();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $invalidDataColor = array();

                    $error_in_cell = '';
                    $buyerCode = $sheet->getCellByColumnAndRow(0, $row)->getValue();
                    $buyerName = $sheet->getCellByColumnAndRow(1, $row)->getValue();
                    $buyerEmail = $sheet->getCellByColumnAndRow(2, $row)->getValue();
                    $buyerMobile = $sheet->getCellByColumnAndRow(3, $row)->getValue();
                    $buyerStep = $sheet->getCellByColumnAndRow(4, $row)->getValue();
                    $buyerAddress = $sheet->getCellByColumnAndRow(5, $row)->getValue();
                    $buyerAddress2 = $sheet->getCellByColumnAndRow(6, $row)->getValue();
                    $buyerState = $sheet->getCellByColumnAndRow(7, $row)->getValue();
                    $buyerCity = $sheet->getCellByColumnAndRow(8, $row)->getValue();
                    $buyerPincode = $sheet->getCellByColumnAndRow(9, $row)->getValue();
                    $buyerEntity = $sheet->getCellByColumnAndRow(10, $row)->getValue();
                    $buyerGST = $sheet->getCellByColumnAndRow(11, $row)->getValue();
                    $buyerCompany = $sheet->getCellByColumnAndRow(12, $row)->getValue();
                    $buyerPAN = $sheet->getCellByColumnAndRow(13, $row)->getValue();
                    $buyerPANName = $sheet->getCellByColumnAndRow(14, $row)->getValue();
                    $buyerMemberShipStart = $sheet->getCellByColumnAndRow(15, $row)->getValue();
                    $buyerMemberShipEnd = $sheet->getCellByColumnAndRow(16, $row)->getValue();
                    $buyerWillingToParti = $sheet->getCellByColumnAndRow(17, $row)->getValue();
                    $buyerSourceType = $sheet->getCellByColumnAndRow(18, $row)->getValue();
                    $buyerSourceName = $sheet->getCellByColumnAndRow(19, $row)->getValue();
                    $buyerSRCode = $sheet->getCellByColumnAndRow(20, $row)->getValue();


                    $Remark = $sheet->getCellByColumnAndRow(20, $row)->getValue();
                    if ($sheet->getCellByColumnAndRow(20, $row)->isFormula()) {
                        $error_in_cell .= 'SR Code ' . $row . ' has error <br> <br>';
                        $invalidDataColor['buyerSRCode'] = 'error';
                    }else{
                        $SR_details = $this->Common->get_info(1,TBL_SALES_REPRESENTATIVE,1,'(SalesCode = "'.$buyerSRCode.'" OR EmployeeCode = "'.$buyerSRCode.'")');
                        if(empty($SR_details)){
                            $error_in_cell .= 'SR Code ' . $row . ' has error <br> <br>';
                            $invalidDataColor['buyerSRCode'] = 'error';
                        }
                    }
                    if ($sheet->getCellByColumnAndRow(0, $row)->isFormula()) {
                        $error_in_cell .= 'SR Code ' . $row . ' has error <br> <br>';
                        $invalidDataColor['SRCode'] = 'error';
                    }else{
                        $Buyer_details = $this->Common->get_info($buyerCode,TBL_USERS,'Code','role_id=2');
                        if(empty($Buyer_details)){
                            $error_in_cell .= 'Buyer Code ' . $row . ' has error <br> <br>';
                            $invalidDataColor['buyerCode'] = 'error';
                        }
                    }

                    //DB Columns
                    $map_buyer_data[$row]['buyerCode'] = $buyerCode;
                    $map_buyer_data[$row]['buyerName'] = $buyerName;
                    $map_buyer_data[$row]['buyerEmail'] = $buyerEmail;
                    $map_buyer_data[$row]['buyerMobile'] = $buyerMobile;
                    $map_buyer_data[$row]['buyerStep'] = $buyerStep;
                    $map_buyer_data[$row]['buyerAddress'] = $buyerAddress;
                    $map_buyer_data[$row]['buyerAddress2'] = $buyerAddress2;
                    $map_buyer_data[$row]['buyerState'] = $buyerState;
                    $map_buyer_data[$row]['buyerCity'] = $buyerCity;
                    $map_buyer_data[$row]['buyerPincode'] = $buyerPincode;
                    $map_buyer_data[$row]['buyerEntity'] = $buyerEntity;
                    $map_buyer_data[$row]['buyerGST'] = $buyerGST;
                    $map_buyer_data[$row]['buyerCompany'] = $buyerCompany;
                    $map_buyer_data[$row]['buyerPAN'] = $buyerPAN;
                    $map_buyer_data[$row]['buyerPANName'] = $buyerPANName;
                    $map_buyer_data[$row]['buyerMemberShipStart'] = $buyerMemberShipStart;
                    $map_buyer_data[$row]['buyerMemberShipEnd'] = $buyerMemberShipEnd;
                    $map_buyer_data[$row]['buyerWillingToParti'] = $buyerWillingToParti;
                    $map_buyer_data[$row]['buyerSourceType'] = $buyerSourceType;
                    $map_buyer_data[$row]['buyerSourceName'] = $buyerSourceName;
                    $map_buyer_data[$row]['buyerSRCode'] = $buyerSRCode;
                    if ($error_in_cell != '') {
                        $logs[] = $error_in_cell;
                        $invalidData[$row]['data'] = $map_buyer_data[$row];
                        $invalidData[$row]['error'] = $invalidDataColor;
                    }
                  
                }
                // print_r($invalidData);die('259');
                // $logs = array();
                if (!empty($map_buyer_data)) {
                    if (!empty($logs)) {
                        $invalidDataFlag = true;
                        $this->session->set_userdata('invalidData', $invalidData);
                        $this->session->set_userdata('invalidDataColor', $invalidDataColor);
                        //                        echo '<pre>';print_r($logs);die;
                        $response = array("status" => "error", "heading" => "File Missing", "message" => 'Excel Validation Faild', "logs" => $logs, "invalidDataFlag" => $invalidDataFlag, "controller" => $this->controllers);
                        //                      return;
                        echo json_encode($response);
                        die;
                    }
                    // $logs = $this->validate_incentive_data($incentive_data);
                    // if (!empty($logs)) {
                    //     $response = array("status" => "error", "heading" => "File Missing", "message" => 'Excel Validation Faild', "logs" => $logs);
                    //     echo json_encode($response);
                    //     die;
                    // }
                    foreach ($map_buyer_data as $keys => $value) {
                        $SR_details = $this->Common->get_info(1,TBL_SALES_REPRESENTATIVE,1,'(SalesCode = "'. $value['buyerSRCode'].'" OR EmployeeCode = "'. $value['buyerSRCode'].'")');
                        if(!empty($SR_details)){
                            $update_buyer_map_data = array(
                                "SRID" => $SR_details->UserID,
                                "Remark" => 'Source change from web to app during SR App rollout',
                                "modified" => date('Y-m-d H:i:s'),
                            );
                            $this->Common->update_info($value['buyerCode'], TBL_USERS, $update_buyer_map_data, 'Code');
                        }
                        
                       
                        //                            echo $product_id;die;
                    }
                    $response = array("status" => "ok", "heading" => "Update successfully...", "message" => "Details updated successfully.");
                } else {
                    $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                }
            }
        }
        //                


        echo json_encode($response);
        die;
    }

    function export_web_buyer()
    {
        $this->load->library('PHPExcel');
        $join = array(
            array("table" => TBL_USER_INFORMATION . " i", "on" => "i.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_PINCODE . " pincode", "on" => "u.PinCode = pincode.PincodeID", "type" => "LEFT"),
            array("table" => TBL_CITY . " c", "on" => "c.CityID = u.City", "type" => "LEFT"),
            array("table" => TBL_STATE . " s", "on" => "s.StateID = u.StateID", "type" => "LEFT"),
            // array("table" => TBL_USER_WALLET . " w", "on" => "w.UserID = u.id", "type" => "LEFT"),
            array("table" => TBL_USER_PAN_VERIFY_DATA . " pan_data", "on" => "u.id = pan_data.UserID", "type" => "LEFT"),
            array("table" => TBL_USER_MEMBERSHIP . " membership", "on" => "u.id = membership.UserID", "type" => "LEFT"),
            array('table'=>TBL_YARD_TEAM_MEMBER.' ytm','on'=>'ytm.UserID=u.SRID','type'=>'LEFT'),
            array('table'=>TBL_YARD.' yd','on'=>'yd.YardID=ytm.YardID','type'=>'LEFT'),
            array('table'=>TBL_SALES_REPRESENTATIVE.' sr','on'=>'u.SRID=sr.UserID','type'=>'LEFT'),
        );

        $where = '1=1';
        $where .= ' AND (u.SRID = 0 OR u.Remark != "")';
        if ($this->input->get('status') != '') {
            if ($this->input->get('status') == 'processing') {
                $where .= ' AND u.StepCompleted <= 2';
            } else if ($this->input->get('status') == 'completed') {
                $where .= ' AND u.StepCompleted > 2';
            }
        }
        if ($this->input->get('source_type') != '') {
            if($this->input->get('source_type') == 0){
                $where .= ' AND u.SRID = 0';
            }else{
                $where .= ' AND u.SRID > 0';

            }
        }


        $users = $this->Common->get_all_info(2, TBL_USERS . ' u', 'role_id', $where, '*,c.CityName,s.StateName,pan_data.Name as NamePerPAN,pincode.PinCode as PinCode,u.Address,u.Address2,yd.StockyardName,ytm.FirstName,ytm.LastName,u.Code as Code,sr.SalesCode', false, $join);
        
        // echo $this->db->last_query();die;
        // echo '<pre>';
        // print_r($users);
        // die;
        if (!empty($users)) {
            // include PHPExcel
            //        require('PHPExcel.php');
            // create new PHPExcel object
            $objPHPExcel = new PHPExcel;
            // set default font
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
            // set default font size
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
            // create the writer
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

            /**
             * Define currency and number format.
             */
            // currency format, € with < 0 being in red color
            //                $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
            // number format, with thousands separator and two decimal points.
            //                $numberFormat = '#,#0.##;[Red]-#,#0.##';
            // writer already created the first sheet for us, let's get it
            $objSheet = $objPHPExcel->getActiveSheet();
            // rename the sheet
            $objSheet->setTitle('Buyer List');

            // let's bold and size the header font and write the header
            // as you can see, we can specify a range of cells, like here: cells from A1 to A4
            $objSheet->getStyle('A1:U1')->getFont()->setBold(true)->setSize(11);

            // write header
            $objSheet->getCell('A1')->setValue('Buyer Code');
            $objSheet->getCell('B1')->setValue('Buyer Name');
            $objSheet->getCell('C1')->setValue('Buyer Email');
            $objSheet->getCell('D1')->setValue('Buyer Mobile No');
            $objSheet->getCell('E1')->setValue('Buyer Step Completed');
            $objSheet->getCell('F1')->setValue('Address 1');
            $objSheet->getCell('G1')->setValue('Address 2');
            $objSheet->getCell('H1')->setValue('State');
            $objSheet->getCell('I1')->setValue('City');
            $objSheet->getCell('J1')->setValue('Pincode');
            $objSheet->getCell('K1')->setValue('Entity');
            $objSheet->getCell('L1')->setValue('GST No');
            $objSheet->getCell('M1')->setValue('Company Name');
            $objSheet->getCell('N1')->setValue('PAN Number');
            $objSheet->getCell('O1')->setValue('Name As Per PAN');
            $objSheet->getCell('P1')->setValue('Membership Start Date');
            $objSheet->getCell('Q1')->setValue('Membership End Date');
            $objSheet->getCell('R1')->setValue('Willing To Participate');
            $objSheet->getCell('S1')->setValue('Source Type');
            $objSheet->getCell('T1')->setValue('Source Name');
            $objSheet->getCell('U1')->setValue('SR Code');
            $i = 2;
            foreach ($users as $single) {
                $step_completed = "Personal Information";
                if ($single->StepCompleted == 1) {
                    $step_completed = 'Personal Information';
                } else if ($single->StepCompleted == 2) {
                    $step_completed = 'eKYC Verification';
                } else if ($single->StepCompleted == 3) {
                    $step_completed = 'eSign';
                } else if ($single->StepCompleted == 4) {
                    $step_completed = 'Registration Fees';
                }
                $SR_str = '';
                if($single->SRID == 0){
                    $SR_str = 'Direct';
                }
                elseif (!empty($single->StockyardName)) {
                    $SR_str = $single->FirstName.' '.$single->LastName.'<br />'.$single->StockyardName . '('.$single->Code.')';
                }
                else{
                    $sr = $this->Common->get_info($single->SRID,TBL_USERS,'id','','first_name,last_name,Code');
                    $SR_str = $sr->first_name.' '.$sr->last_name. '('.$sr->Code.')';
                }

                $objSheet->setCellValueExplicit('A' . $i, $single->Code, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('B' . $i, $single->first_name . ' ' . $single->last_name, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('C' . $i, $single->email, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('D' . $i, $single->mobile_no, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('E' . $i, $step_completed, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('F' . $i, $single->Address, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('G' . $i, $single->Address2, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('H' . $i, $single->StateName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('I' . $i, $single->CityName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('J' . $i, $single->PinCode, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('K' . $i, $single->Entity, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('L' . $i, $single->GST, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('M' . $i, $single->CompanyName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('N' . $i, $single->PAN, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('O' . $i, $single->NamePerPAN, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('P' . $i, date('d-m-Y', strtotime($single->StartDate)), PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('Q' . $i, date('d-m-Y', strtotime($single->EndDate)), PHPExcel_Cell_DataType::TYPE_STRING);
                $TWO_WH = '2WH';
                $THREE_WH = '3WH';
                $FOUR_WH = '4WH';
                $willing_to_participate = '';
                if ($single->$TWO_WH == 1) {
                    $willing_to_participate = '2WH';
                }
                if ($single->$THREE_WH == 1) {
                    $willing_to_participate .= ' 3WH';
                }
                if ($single->$FOUR_WH == 1) {
                    $willing_to_participate .= ' 4WH';
                }
                if ($single->CV == 1) {
                    $willing_to_participate .= ' CV';
                }
                if ($single->CE == 1) {
                    $willing_to_participate .= ' CE';
                }
                if ($single->FE == 1) {
                    $willing_to_participate .= ' FE';
                }
                $objSheet->setCellValueExplicit('R' . $i, $willing_to_participate, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('S' . $i, (($single->SRID > 0) ? 'App' : 'Web'), PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('T' . $i, $SR_str, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('U' . $i, $single->SalesCode, PHPExcel_Cell_DataType::TYPE_STRING);

                $i++;
            }

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            //                $objSheet->getColumnDimension('A3')->setAutoSize(true);
            // write the file
            $fileName = 'Buyer_list_' . date('d-m-Y') . '.xlsx';
            //        $objWriter->save('test.xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect('commission-report');
        }
    }

    public function downloadInvalidFile()
    {
        if (empty($_SESSION['invalidData'])) {
            redirect('/incentive-report');
        }
        // echo '<pre>';
        // print_r($_SESSION['invalidData']);
        // print_r($_SESSION['invalidDataColor']);
        // die;
        // create new PHPExcel object
        $this->load->library('PHPExcel');
        $objPHPExcel = new PHPExcel;
        // set default font
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
        // set default font size
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        // create the writer
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

        /**
         * Define currency and number format.
         */
        // currency format, â‚¬ with < 0 being in red color
        //                $currencyFormat = '#,#0.## \â‚¬;[Red]-#,#0.## \â‚¬';
        // number format, with thousands separator and two decimal points.
        //                $numberFormat = '#,#0.##;[Red]-#,#0.##';
        // writer already created the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('Invalid Buyer SR Map Data');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4

        // write header
        $objSheet->getStyle('A1:U1')->getFont()->setBold(true)->setSize(11);

        // write header
        $objSheet->getCell('A1')->setValue('Buyer Code');
        $objSheet->getCell('B1')->setValue('Buyer Name');
        $objSheet->getCell('C1')->setValue('Buyer Email');
        $objSheet->getCell('D1')->setValue('Buyer Mobile No');
        $objSheet->getCell('E1')->setValue('Buyer Step Completed');
        $objSheet->getCell('F1')->setValue('Address 1');
        $objSheet->getCell('G1')->setValue('Address 2');
        $objSheet->getCell('H1')->setValue('State');
        $objSheet->getCell('I1')->setValue('City');
        $objSheet->getCell('J1')->setValue('Pincode');
        $objSheet->getCell('K1')->setValue('Entity');
        $objSheet->getCell('L1')->setValue('GST No');
        $objSheet->getCell('M1')->setValue('Company Name');
        $objSheet->getCell('N1')->setValue('PAN Number');
        $objSheet->getCell('O1')->setValue('Name As Per PAN');
        $objSheet->getCell('P1')->setValue('Membership Start Date');
        $objSheet->getCell('Q1')->setValue('Membership End Date');
        $objSheet->getCell('R1')->setValue('Willing To Participate');
        $objSheet->getCell('S1')->setValue('Source Type');
        $objSheet->getCell('T1')->setValue('Source Name');
        $objSheet->getCell('U1')->setValue('SR Code');

        // we could get this data from database, but for simplicty, let's just write it
        $columnCount = 2;
        $i = 1;
        // echo '<pre>';
        // print_r($_SESSION['invalidData']);die;
        foreach ($_SESSION['invalidData'] as $data_main) {
                $data = $data_main['data'];
               
                $error = $data_main['error'];
                $styleArray = array(
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FF0000'),
                        'size'  => 8,
                        'name'  => 'Verdana'
                    )
                );
               

                $objSheet->setCellValueExplicit('A' . $columnCount, empty($data['buyerCode']) ? '' : $data['buyerCode'], PHPExcel_Cell_DataType::TYPE_STRING);
                if ($error['buyerCode']) {
                    $objSheet->getStyle('A' . $columnCount)->applyFromArray($styleArray);
                }
                $objSheet->setCellValueExplicit('B' . $columnCount, $data['buyerName'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('C' . $columnCount, $data['buyerEmail'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('D' . $columnCount, $data['buyerMobile'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('E' . $columnCount, $data['buyerStep'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('F' . $columnCount, $data['buyerAddress'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('G' . $columnCount, $data['buyerAddress2'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('H' . $columnCount, $data['buyerState'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('I' . $columnCount, $data['buyerCity'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('J' . $columnCount, $data['buyerPincode'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('K' . $columnCount, $data['buyerEntity'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('L' . $columnCount, $data['buyerGST'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('M' . $columnCount, $data['buyerCompany'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('N' . $columnCount, $data['buyerPAN'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('O' . $columnCount, $data['buyerPANName'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('P' . $columnCount, $data['buyerMemberShipStart'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('Q' . $columnCount, $data['buyerMemberShipEnd'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('R' . $columnCount, $data['buyerWillingToParti'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('S' . $columnCount, $data['buyerSourceType'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('T' . $columnCount, $data['buyerSourceName'], PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('U' . $columnCount, $data['buyerSRCode'], PHPExcel_Cell_DataType::TYPE_STRING);
                if ($error['buyerSRCode']) {
                    $objSheet->getStyle('U' . $columnCount)->applyFromArray($styleArray);
                }

                $columnCount++;
                $i++;
        }
        // autosize the columns
        $objSheet->getColumnDimension('A')->setAutoSize(true);
        $objSheet->getColumnDimension('B')->setAutoSize(true);
        $objSheet->getColumnDimension('C')->setAutoSize(true);
        $objSheet->getColumnDimension('D')->setAutoSize(true);
        $objSheet->getColumnDimension('E')->setAutoSize(true);

        $fileName = 'invalid_buyer_sr_map_info_' . date('d-m-Y') . '.xlsx';
        //        $objWriter->save('test.xlsx');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 15 Jul 2023 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}
