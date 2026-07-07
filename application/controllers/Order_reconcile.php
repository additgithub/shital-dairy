<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order_reconcile extends CI_Controller
{

    public $table_name = TBL_ORDER_HDR;
    public $controllers = 'order_reconcile';
    public $view_name = 'order_reconcile';
    public $title = 'Orders Reconcile';
    public $PrimaryKey = 'order_hdr_id';

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

        if ($this->input->post('customer_name') && $this->input->post('customer_name') > 0) {
            $this->datatables->where('ord.customer_name', $this->input->post('customer_name'));
        }

        if ($this->input->post('month') && $this->input->post('month') != '') {
            $this->datatables->where('DATE_FORMAT(ord.order_date, "%Y-%m") = "' . $this->input->post('month').'"');
        }

        if ($this->tank_auth->get_user_role_id() == '2') {
            $this->datatables->where('created_by = ' . $this->tank_auth->get_user_id());
        }
        $this->datatables->select('ord.'.$this->PrimaryKey . ',"" as checkbox, ord.order_no,ord.order_date,cus.customer_name,ord.amount');
        $this->datatables->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = ord.customer_name', 'LEFT')
            ->from($this->table_name . ' ord');
        $this->datatables->edit_column('checkbox', $this->show_gp_action_row('$1',  '$2'), 'ord.order_hdr_id, gp_check_chk_id(ord.order_hdr_id, "' . $temp_qt_id . '")');
        $this->datatables->unset_column('ord.'.$this->PrimaryKey);
        $this->datatables->order_by('ord.'.$this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
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

    function clear_orders()
    {
        if ($this->input->post()) {
            $isAllChecked = $this->input->post('isAllChecked');

            if ($isAllChecked == 'true') {
                $data_obj = $this->Common->get_all_info(1, $this->table_name, 1);
            } else if ($isAllChecked == 'false') {
                $order_ids = $this->input->post('order_ids[]');
                if (!empty($order_ids)) {
                    $data_obj = $this->Common->get_all_info(1, $this->table_name, 1,"order_hdr_id IN (".implode(',',$order_ids).")");
                }
            } else {
                $response = array("status" => "error", "heading" => "No order entries to clear", "message" => "No order entries to clear");
                echo json_encode($response);
                die;
            }

            if (empty($data_obj ?? [])){
                $response = array("status" => "error", "heading" => "No order entries to clear", "message" => "No order entries to clear");
                echo json_encode($response);
                die;
            }

            $grouped_data = [];

            foreach ($data_obj as $row) {

                $month = date('Y-m', strtotime($row->order_date));
                $key = $month . '_' . $row->customer_name;

                if (!isset($grouped_data[$key])) {
                    $grouped_data[$key] = (object)[
                        'month'         => $month,
                        'customer_name' => $row->customer_name,
                        'min_date'      => $row->order_date,
                        'order_ids'     => []
                    ];
                }

                // Update minimum date
                if (strtotime($row->order_date) < strtotime($grouped_data[$key]->min_date)) {
                    $grouped_data[$key]->min_date = $row->order_date;
                }

                // Store only order IDs
                $grouped_data[$key]->order_ids[] = $row->order_hdr_id;
            }

            // Convert to indexed array
            $grouped_data = array_values($grouped_data);
            
            // pre($grouped_data);
            foreach ($grouped_data as $single_data) {
                $data_remove = $this->Remove_records->remove_data_with_where($single_data->customer_name, 'customer_id', TBL_LEDGER, "order_id IN (".implode(',',$single_data->order_ids).")");

                $data_remove = $this->Remove_records->remove_data_with_where($single_data->customer_name, 'customer_name', TBL_ORDER_HDR, "order_hdr_id IN (".implode(',',$single_data->order_ids).")");

                recalculate_ledger($single_data->customer_name,$single_data->min_date);
            }
            
            $response = array("status" => "ok", "heading" => "Data cleared successfully...", "message" => "Data cleared successfully.");

            echo json_encode($response);
            die;
        }
    }
}