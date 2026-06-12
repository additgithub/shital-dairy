<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reconcile extends CI_Controller
{

    public $table_name = TBL_LEDGER;
    public $controllers = 'reconcile';
    public $view_name = 'reconcile';
    public $title = 'Reconcile';
    public $PrimaryKey = 'ledger_id';

    function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }
    }

    function index()
    {
        $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }
    function opening_balance()
    {
        $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/opening_balance';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }

    function add()
    {
        $data['page_title'] = "Add New " . $this->title;
        $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
        $this->load->view($this->view_name . '/form', $data);
    }

    function edit($id)
    {

        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
                $data["data_info"] = $data_obj;
                $data_found = 1;
            }
        }
        if ($data_found == 0) {
            redirect('/');
        }

        $data['page_title'] = "Edit " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    function submit_form()
    {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                ->set_rules('customer_name', 'Customer Name', 'required')
                ->set_rules('date', 'Date', 'required')
                ->set_rules('type', 'Type', 'required')
                ->set_rules('total_amount', 'Amount', 'required|numeric');
            $this->form_validation->set_message('required', 'The %s field is required.');
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;
                $customer_id = $this->input->post('customer_name');
                $post_data = array(
                    "customer_id" => $this->input->post('customer_name'),
                    "txn_date" => $this->input->post('date'),
                    "remark" => 'Opning Balance',
                );
                $customer_last_entry = $this->Common->get_info($customer_id, TBL_LEDGER, 'customer_id', '', 'balance', false, false, array('field' => 'ledger_id', 'order' => 'DESC'));
                $balance = 0;
                if (!empty($customer_last_entry)) {
                    $balance = $customer_last_entry->balance;
                }
                if ($this->input->post('type') == 'debit') {
                    $post_data['credit'] = 0;
                    $post_data['debit'] = $this->input->post('total_amount');
                    $balance = $balance - $this->input->post('total_amount');
                    $post_data['balance'] = $balance;
                } else {
                    $post_data['debit'] = 0;
                    $post_data['credit'] = $this->input->post('total_amount');
                    $balance = $balance + $this->input->post('total_amount');
                    $post_data['balance'] = $balance;
                }

                if ($temp_id = $this->Common->add_info($this->table_name, $post_data)):

                    $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                else:
                    $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                endif;
            } else {
                $errors = $this->form_validation->error_array();
                $response['error'] = $errors;
            }
            echo json_encode($response);
            die;
        }
    }


    function manage()
    {

        $temp_qt_id = '';
        $temp_qt_id_array =  [];
        if ($this->input->post('customer_ids') != "") {
            $customer_ids = explode(",", $this->input->post('customer_ids'));
            for ($i = 0; $i < count($customer_ids); $i++) {
                $temp_qt_id_array[] = $customer_ids[$i];
            }
            if (!empty($temp_qt_id_array)) {
                $temp_qt_id = implode(",", $temp_qt_id_array);
            }
        }

        $where = '';
        if ($this->input->post('month') && $this->input->post('month') != '') {
            $where = ' AND DATE_FORMAT(txn_date, "%Y-%m") = "'.$this->input->post('month').'"';
        }
        // echo $temp_qt_id;die;

        if ($this->input->post('customer_name') && $this->input->post('customer_name') > 0) {
            $this->datatables->where('cp.customer_id', $this->input->post('customer_name'));
        }
        $this->datatables->select($this->PrimaryKey . ', "" as checkbox,cus.customer_name,0 as opening_bal,(SELECT CASE WHEN SUM(credit) IS NULL THEN 0 ELSE SUM(credit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0  '.$where.' ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN SUM(debit) IS NULL THEN 0 ELSE SUM(debit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0 '.$where.' ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id,0 as closing_bal')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp')
            ->add_column(
                'action',
                $this->action_row('$1', '$2'),
                'cus.customer_id,"' . $this->input->post('month') . '"'
            );
        $this->datatables->edit_column('checkbox', $this->show_gp_action_row('$1',  '$2'), 'cus.customer_id, gp_check_chk_id(cus.customer_id, "' . $temp_qt_id . '")');
        // ->add_column('action', '$1', 'payment_action_row(' . $this->PrimaryKey . ')');
        $this->datatables->edit_column('opening_bal', '$1', 'ledger_opening_bal_row(' . $this->PrimaryKey . ',"'.$this->input->post('month').'")');
        $this->datatables->edit_column('closing_bal', '$1', 'ledger_closing_bal_row(' . $this->PrimaryKey . ',cus.customer_id,credit,debit,opening_bal,"'.$this->input->post('month').'")');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cus.customer_id');
        $this->datatables->group_by('cp.customer_id');
        $this->datatables->order_by($this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
        //  echo $this->db->last_query();die;
    }
    public function show_gp_action_row($customer_id, $text)
    {
        $action = <<<EOF
            <div class="mdc-checkbox">
                <input type="checkbox" class="mdc-checkbox__native-control question_id_chk" name="question_ids[]" {$text} id="question_id_{$customer_id}" value="{$customer_id}">
                
            </div>
EOF;
        return $action;
    }
    function view_details($customer_id)
    {
        $data['customer_id'] = $customer_id;
        $data['filter_month'] = $this->input->get('month');
        $customer_info = $this->Common->get_info($customer_id, TBL_CUSTOMER, 'customer_id');
        $data['page_title'] = "Manage " . $customer_info->customer_name . ' Ledger';
        $data['main_content'] = $this->view_name . '/detail';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }
    function detail_manage()
    {

        if ($this->input->post('customer_name') && $this->input->post('customer_name') > 0) {
            $this->datatables->where('cp.customer_id', $this->input->post('customer_name'));
        }
        $this->datatables->select($this->PrimaryKey . ', cus.customer_name,cp.txn_date,cp.credit,cp.debit,cp.balance,cp.remark,cp.order_id,cp.payment_id')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp')
            // ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
            ->add_column('action', '$1', 'ledger_detail_action_row(' . $this->PrimaryKey . ',cp.credit,cp.debit,cp.order_id,cp.payment_id)');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cp.order_id');
        $this->datatables->unset_column('cp.payment_id');
        $this->datatables->order_by($this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
    }



    function action_row($id, $month = '')
    {
        $url = base_url() . $this->controllers . '/view_details/' . $id;

        if ($month != '') {
            $url .= '?month=' . $month;
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="{$url}" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-eye"></i></a>
               
            </div>
EOF;
        return $action;
    }

    function get_detail()
    {
        if ($this->input->post()) {

            $id = $this->input->post('id');
            $order_id = $this->input->post('order_id');
            $payment_id = $this->input->post('payment_id');
            $type = $this->input->post('type');
            $controller =  $this->view_name;

            if ($type == 'payment' && $payment_id != 0) {
                $join = array(
                    array(
                        'table' => TBL_CUSTOMER . ' it',
                        'on' => 'it.customer_id = oi.customer_id',
                        'type' => 'LEFT'
                    )
                );
                $data['payment'] = $this->Common->get_info($payment_id, TBL_CUSTOMER_PAYMENT . ' oi', 'payment_id', '', 'oi.*,it.customer_name', $join);
                $this->load->view($controller . '/payment_view', $data);
            } else if ($type == 'order' && $order_id != 0) {

                $data['data_info'] = $this->Common->get_info($order_id, TBL_ORDER_HDR, 'order_hdr_id');
                $join = array(
                    array(
                        'table' => TBL_CUSTOMER . ' it',
                        'on' => 'it.customer_id = oi.customer_name',
                        'type' => 'LEFT'
                    )
                );
                $data['order'] = $this->Common->get_info($order_id, TBL_ORDER_HDR . ' oi', 'order_hdr_id', '', '*', $join);
                $join = array(
                    array(
                        'table' => TBL_M_ITEMS . ' it',
                        'on' => 'it.item_id = oi.item_id',
                        'type' => 'LEFT'
                    )
                );
                $data['order_items'] = $this->Common->get_all_info($order_id, TBL_ORDER_DTL . ' oi', 'order_hdr_id', '', 'oi.*,it.item_name', false, $join);
                $this->load->view($controller . '/order_view', $data);
            }
        }
    }

    function clear_ledger()
    {
        if ($this->input->post()) {
            $isAllChecked = $this->input->post('isAllChecked');
            $hasAnyCustomer = $this->input->post('hasAnyCustomer');

            $opening_date = date('Y-m-01', strtotime($this->input->post('month') . '-01 +1 month'));
            if ($isAllChecked == 'true') {
                $customer_ids = $this->Common->get_all_info(1, TBL_LEDGER, 1, '', 'DISTINCT(customer_id)');
                if (!empty($customer_ids)) {
                    foreach ($customer_ids as $customer) {
                        $last_ledger = $this->Common->get_info($customer->customer_id, $this->table_name, 'customer_id','DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"','balance,txn_date',false,false,array('field'=>'txn_date','order'=>'desc')); 
                        
                        $last_ledger = $this->Common->get_info($customer->customer_id, $this->table_name, 'customer_id','','balance,txn_date',false,false,array('field'=>'txn_date','order'=>'desc'));

                        $next_ledger = $this->Common->get_info($customer->customer_id, $this->table_name, 'customer_id','DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($opening_date)).'" AND is_opening_bal=1','ledger_id,balance,txn_date',false,false,array('field'=>'txn_date','order'=>'desc'));

                        $data_remove = $this->Remove_records->remove_data_with_where($customer->customer_id, 'customer_name', TBL_ORDER_HDR, 'DATE_FORMAT(order_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where($customer->customer_id, 'customer_id', TBL_CUSTOMER_PAYMENT,'DATE_FORMAT(payment_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where($customer->customer_id, 'customer_id', TBL_LEDGER,'DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where(1,1,TBL_ORDER_DTL,'order_hdr_id NOT IN (SELECT order_hdr_id FROM '.TBL_ORDER_HDR.')');

                        $balance = !empty($last_ledger) ? (float)$last_ledger->balance : 0;

                        if(!empty($next_ledger)){

                            $new_balance = $balance + (!empty($next_ledger) ? (float)$next_ledger->balance : 0);

                            $ledger_entry = array(
                                "txn_date"    => $opening_date,
                                "remark"      => 'Opening Balance',
                                "credit"      => ($new_balance > 0) ? $new_balance : 0,
                                "debit"       => ($new_balance < 0) ? $new_balance : 0,
                                "balance"     => $new_balance
                            );

                            $this->Common->update_info($next_ledger->ledger_id, $this->table_name, $ledger_entry, 'ledger_id');

                            recalculate_ledger($customer->customer_id,$opening_date);
                        }else{
                            $ledger_entry = array(
                                "customer_id" => $customer->customer_id,
                                "txn_date"    => $opening_date,
                                "remark"      => 'Opening Balance',
                                "credit"      => ($balance > 0) ? $balance : 0,
                                "debit"       => ($balance < 0) ? $balance : 0,
                                "balance"     => $balance,
                                "is_opening_bal" => 1,
                            );

                            $this->Common->add_info($this->table_name, $ledger_entry);
                        }
                    }
                }
               $response = array("status" => "ok", "heading" => "Data cleared successfully...", "message" => "Data cleared successfully.");
            } else if ($isAllChecked == 'false') {
                $customer_ids = $this->input->post('customer_ids[]');
                if (!empty($customer_ids)) {
                    foreach ($customer_ids as $customer) {
                        $last_ledger = $this->Common->get_info($customer, $this->table_name, 'customer_id','DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"','balance,txn_date',false,false,array('field'=>'txn_date','order'=>'desc'));     
                        
                        $next_ledger = $this->Common->get_info($customer, $this->table_name, 'customer_id','DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($opening_date)).'" AND is_opening_bal=1','ledger_id,balance,txn_date',false,false,array('field'=>'txn_date','order'=>'desc'));

                        $data_remove = $this->Remove_records->remove_data_with_where($customer, 'customer_name', TBL_ORDER_HDR, 'DATE_FORMAT(order_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where($customer, 'customer_id', TBL_CUSTOMER_PAYMENT,'DATE_FORMAT(payment_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where($customer, 'customer_id', TBL_LEDGER,'DATE_FORMAT(txn_date,"%Y-%m")="'. date('Y-m',strtotime($this->input->post('month'))).'"');
                        $data_remove = $this->Remove_records->remove_data_with_where(1,1,TBL_ORDER_DTL,'order_hdr_id NOT IN (SELECT order_hdr_id FROM '.TBL_ORDER_HDR.')');

                        $balance = !empty($last_ledger) ? (float)$last_ledger->balance : 0;

                        if(!empty($next_ledger)){

                            $new_balance = $balance + (!empty($next_ledger) ? (float)$next_ledger->balance : 0);

                            $ledger_entry = array(
                                "txn_date"    => $opening_date,
                                "remark"      => 'Opening Balance',
                                "credit"      => ($new_balance > 0) ? $new_balance : 0,
                                "debit"       => ($new_balance < 0) ? $new_balance : 0,
                                "balance"     => $new_balance
                            );

                            $this->Common->update_info($next_ledger->ledger_id, $this->table_name, $ledger_entry, 'ledger_id');

                            recalculate_ledger($customer,$opening_date);
                        }else{
                            $ledger_entry = array(
                                "customer_id" => $customer,
                                "txn_date"    => $opening_date,
                                "remark"      => 'Opening Balance',
                                "credit"      => ($balance > 0) ? $balance : 0,
                                "debit"       => ($balance < 0) ? $balance : 0,
                                "balance"     => $balance,
                                "is_opening_bal" => 1,
                            );

                            $this->Common->add_info($this->table_name, $ledger_entry);
                        }
                    }
                }
                $response = array("status" => "ok", "heading" => "Data cleared successfully...", "message" => "Data cleared successfully.");
            } else {
                $response = array("status" => "error", "heading" => "No customer ledger entries to clear", "message" => "No customer ledger entries to clear");
            }
            echo json_encode($response);
            die;
        }
    }
}
