<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// require 'vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Ledger extends CI_Controller
{

    public $table_name = TBL_LEDGER;
    public $controllers = 'ledger';
    public $view_name = 'ledger';
    public $title = 'Ledger';
    public $PrimaryKey = 'ledger_id';
    public $Month = '';

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
        $data["extra_js"] = array("manage-sales-report");
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
                // if ($id == 0) {
                //     $customer_already_opening_record = $this->Common->get_info($customer_id, TBL_LEDGER, 'customer_id', 'is_opening_bal=1', 'balance', false, false);
                //     if (!empty($customer_already_opening_record)) {
                //         $response = array("status" => "error", "heading" => "Already Exists...", "message" => "Opening balance for this customer already exists.");
                //         echo json_encode($response);
                //         die;
                //     }
                // }

                $post_data = array(
                    "customer_id" => $this->input->post('customer_name'),
                    "txn_date" => $this->input->post('date').'-01',
                    "remark" => 'Opning Balance',
                );
                $balance = 0;
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
                $post_data['is_opening_bal'] = 1;

                $opening_month = date('Y-m',strtotime($post_data['txn_date'])); 
                $current_opening_bal_entry = $this->Common->get_info($customer_id, TBL_LEDGER, 'customer_id', "is_opening_bal=1 AND DATE_FORMAT(txn_date,'%Y-%m')='".$opening_month."'","ledger_id,txn_date");

                if(!empty($current_opening_bal_entry)){
                    if ($this->Common->update_info($current_opening_bal_entry->ledger_id, $this->table_name, $post_data, $this->PrimaryKey)):
                        recalculate_ledger($this->input->post('customer_name'),$post_data['txn_date']);
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                    endif;
                }else{
                    if ($temp_id = $this->Common->add_info($this->table_name, $post_data)):
                        recalculate_ledger($this->input->post('customer_name'),$post_data['txn_date']);
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                    endif;
                }
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

        if ($this->input->post('customer_name') && $this->input->post('customer_name') > 0) {
            $this->datatables->where('cp.customer_id', $this->input->post('customer_name'));
        }
        $where = '';
        if ($this->input->post('month') && $this->input->post('month') != '') {
            $this->datatables->where('DATE_FORMAT(cp.txn_date, "%Y-%m") = "'.$this->input->post('month').'"');
            $where = ' AND DATE_FORMAT(txn_date, "%Y-%m") = "'.$this->input->post('month').'"';
            $this->Month = $this->input->post('month');
        }else{
            $this->Month = '';
        }
        // $this->datatables->select($this->PrimaryKey . ', cus.customer_name,0 as opening_bal,(SELECT CASE WHEN balance >= 0 THEN balance ELSE 0 END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN balance <= 0 THEN balance * -1 ELSE 0 END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id,0 as closing_bal')
          $this->datatables->select($this->PrimaryKey . ', cus.customer_name,0 as opening_bal,(SELECT CASE WHEN SUM(credit) IS NULL THEN 0 ELSE SUM(credit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0  '.$where.' ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN SUM(debit) IS NULL THEN 0 ELSE SUM(debit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0 '.$where.' ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id,0 as closing_bal')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp')
            ->add_column('action', $this->action_row('$1'), 'cus.customer_id');
            // ->add_column('action', '$1', 'action_row(cus.customer_id)');

        // ->add_column('action', '$1', 'payment_action_row(' . $this->PrimaryKey . ')');
        $this->datatables->edit_column('opening_bal', '$1', 'ledger_opening_bal_row(' . $this->PrimaryKey . ',"'.$this->input->post('month').'")');
        $this->datatables->edit_column('closing_bal', '$1', 'ledger_closing_bal_row(' . $this->PrimaryKey . ',cus.customer_id,credit,debit,opening_bal,"'.$this->input->post('month').'")');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cus.customer_id');
        $this->datatables->group_by('cp.customer_id');
        // $this->datatables->order_by($this->PrimaryKey, 'DESC');
        // $this->datatables->order_by('cus.customer_name', 'ASC');
        echo $this->datatables->generate();
        //  echo $this->db->last_query();die;
    }

    function action_row($id)
    {
        $url = base_url() . $this->controllers . '/view_details/' . $id;
        if ($this->Month != '') {
            $url .= '?month=' . $this->Month;
        }

        $report_url = base_url() . $this->controllers . '/report_action';
        $icon_url = base_url("assets/img/icon/whatsapp.png");
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="{$url}" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-eye"></i></a>
                <a data-original-title="Send Whatsapp Message" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal send_whatsapp btn-mini" data-id="{$id}" data-url="{$report_url}"><img src="{$icon_url}" /></a>
                <a data-original-title="Download Report" data-placement="top" data-toggle="tooltip" data-id="{$id}" data-url="{$report_url}" class="btn btn-default btn-equal btn-mini btn_report_download"><i class="fa fa-download"></i></a>
               
            </div>
EOF;
        return $action;
    }

    function view_details($customer_id)
    {
        $data['customer_id'] = $customer_id;
        $data['month'] = $this->input->get('month') ? $this->input->get('month') : '';
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
        if ($this->input->post('month') && $this->input->post('month') != '') {
            $this->datatables->where('DATE_FORMAT(cp.txn_date, "%Y-%m") = "'.$this->input->post('month').'"');
        }
        $this->datatables->select($this->PrimaryKey . ', cus.customer_name,cp.txn_date,cp.credit,cp.debit,cp.balance,cp.remark,cp.order_id,cp.payment_id')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp')
            // ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
            ->add_column('action', '$1', 'ledger_detail_action_row(' . $this->PrimaryKey . ',cp.credit,cp.debit,cp.order_id,cp.payment_id)');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cp.order_id');
        $this->datatables->unset_column('cp.payment_id');
        $this->datatables->order_by('cp.txn_date', 'DESC');
        $this->datatables->order_by('cp.payment_id', 'DESC');
        $this->datatables->order_by('cp.order_id', 'DESC');
        $this->datatables->order_by('cp.is_opening_bal', 'ASC');
        echo $this->datatables->generate();
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

    public function ledger_report()
    {
        $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
        $data['page_title'] = "Manage Summary Report";
        $data['main_content'] = $this->view_name . '/report';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }

    public function download_report()
    {
        $start_date  = $this->input->get('start_date');
        $end_date    = $this->input->get('end_date');
        $customer_id = $this->input->get('customer_id');

        // Fetch ledger data
        $report = $this->Common->get_ledger_report($start_date, $end_date, $customer_id);
        // Add remarks: replace with order summary if applicable
        foreach ($report as &$row) {
            if ($row['order_id'] > 0) {
                $row['remark'] = $this->Common->get_order_summary_html($row['order_id']);
            } elseif ($row['payment_id'] > 0) {
                $payment = $this->Common->get_info($row['payment_id'], TBL_CUSTOMER_PAYMENT, 'payment_id');
                if (!empty($payment)) {

                    $row['remark'] = "<div>
                    <strong>Payment</strong><br>
                    Date: " . date("d-m-Y", strtotime($payment->payment_date)) . "<br>
                    Type: " . $payment->payment_type . "<br>
                    Amount: " . number_format($payment->amount, 2) . "<br>
                    Remark: " . $payment->remark . "
                    </div>";
                } else {
                    $row['remark'] = "Payment Received";
                }
            }
            // else keep existing remark
        }

        $data['report'] = $report;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        // Load HTML view
        $html = $this->load->view($this->view_name . '/ledger_report_pdf', $data, true);
        // print_r($html);die;
        $html = preg_replace('/<br>/i', '<br />', $html);
        $html = preg_replace('/<br[^>]*>/i', '<br />', $html);
        // sanitize HTML for mPDF
        $html = preg_replace('/<br\s*\/?>/i', '<br />', $html); // normalize
        $html = preg_replace_callback('/<table.*?<\/table>/is', function ($m) {
            return preg_replace('/<br\s*\/?>/i', '', $m[0]);
        }, $html);
        // Load mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'strictHTML' => true,
            'allow_html_optional_endtags' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true
        ]);

        $mpdf->SetTitle("Ledger Report");
        $mpdf->WriteHTML($html);

        // Direct output (no saving)
        $mpdf->Output("ledger_report.pdf", "I");
    }

    public function download_report_new()
    {
        $range = getMonthDateRange($this->input->get('month'));
        $start_date  = $range['first_date'];
        $end_date    = $range['last_date'];
        $customer_id = $this->input->get('customer_id');

        $report = $this->Common->get_ledger_report($start_date, $end_date, $customer_id);

        foreach ($report as &$row) {
            // --- Order summary short format ---
            if ($row['order_id'] > 0) {
                $items = $this->Common->get_all_info($row['order_id'], TBL_ORDER_DTL . ' oi', 'order_hdr_id', '', 'oi.*,it.item_name', false, [
                    ['table' => TBL_M_ITEMS . ' it', 'on' => 'it.item_id = oi.item_id', 'type' => 'LEFT']
                ]);

                $summary = [];
                foreach ($items as $it) {
                    $summary[] = $it->item_name . " (" . $it->qty . " × " . $it->price_per_item . ")";
                }
                $row['remark'] = implode(', ', $summary);
            }
            // --- Payment summary short format ---
            elseif ($row['payment_id'] > 0) {
                $payment = $this->Common->get_info($row['payment_id'], TBL_CUSTOMER_PAYMENT, 'payment_id');
                $row['remark'] = "Payment: " . $payment->payment_type . " - ₹" . number_format($payment->amount, 2);
            }
            // --- Default remark remains as-is ---
        }

        $data['report'] = $report;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['customer'] = $this->Common->get_info($customer_id, TBL_CUSTOMER, 'customer_id');

        $html = $this->load->view('ledger/ledger_report_pdf_new', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'strictHTML' => true,
            'allow_html_optional_endtags' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Ledger_Report.pdf', 'I'); // display directly in browser
    }


    public function download_report_all()
    {
        $start_date  = $this->input->get('start_date');
        $end_date    = $this->input->get('end_date');
        $customer_id = $this->input->get('customer_id');

        $data['start_date'] = $start_date;
        $data['end_date']   = $end_date;
        $data['reports']    = []; // array of multiple customers

        // If specific customer selected
        $customer_join = array(
            array(
                'table' => TBL_CUSTOMER . ' it',
                'on' => 'l.customer_id = it.customer_id',
                'type' => 'LEFT'
            )
        );
        $customers = $this->Common->get_all_info(1, $this->table_name . ' l', 1, '', 'DISTINCT(l.customer_id) as customer_id, it.customer_name', false, $customer_join, 'it.customer_name');
        foreach ($customers as $cust) {
            if (empty($cust)) continue;

            $report = $this->Common->get_ledger_report($start_date, $end_date, $cust->customer_id);

            foreach ($report as &$row) {
                // --- Order summary short format ---
                if ($row['order_id'] > 0) {
                    $items = $this->Common->get_all_info($row['order_id'], TBL_ORDER_DTL . ' oi', 'oi.order_hdr_id', '', 'oi.*,it.item_name,oh.wadi_id,wd.wadi_name,oh.remarks', false, [
                        ['table' => TBL_M_ITEMS . ' it', 'on' => 'it.item_id = oi.item_id', 'type' => 'LEFT'],
                        ['table' => TBL_ORDER_HDR . ' oh', 'on' => 'oh.order_hdr_id = oi.order_hdr_id', 'type' => 'LEFT'],
                        ['table' => TBL_WADI . ' wd', 'on' => 'wd.wadi_id = oh.wadi_id', 'type' => 'LEFT'],
                    ]);

                    $summary = [];
                    foreach ($items as $it) {
                        $summary[] = $it->item_name . " (" . $it->qty . " × " . $it->price_per_item . ")";
                    }
                    $summary = implode(', ', $summary);

                    if(count($items)>0 && !empty($items[0]->wadi_name) && trim($items[0]->wadi_name) != 'N/A'){
                        $summary .= '<br />Wadi:- '.trim($items[0]->wadi_name);
                    }
                    if(count($items)>0 && !empty($items[0]->remarks)){
                        $summary .= '<br />Remarks:- '.$items[0]->remarks;
                    }
                    $row['remark'] = $summary;
                }
                // --- Payment summary short format ---
                elseif ($row['payment_id'] > 0) {
                    $payment = $this->Common->get_info($row['payment_id'], TBL_CUSTOMER_PAYMENT, 'payment_id');
                    if (!empty($payment)) {
                        $row['remark'] = "Payment: " . $payment->payment_type . " - ₹" . number_format($payment->amount, 2);
                    }
                }
            }

            if (!empty($report)) {
                $data['reports'][] = [
                    'customer' => $cust,
                    'records'  => $report
                ];
            }
        }
        // echo '<pre>';
        // print_r($data['reports']); die;
        $html = $this->load->view('ledger/ledger_report_pdf_all', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'strictHTML' => true,
            'allow_html_optional_endtags' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Ledger_Report.pdf', 'I');
    }
    public function download_list_report()
    {
        
        $month    = $this->input->get('month');
        $customer_id = $this->input->get('customer_id');
        
        $where_con = "1=1";
        if(!empty($customer_id)){
            $where_con .= " AND cp.customer_id='".$customer_id."'";
        }

        if(!empty($month)){
            $where_con .= " AND DATE_FORMAT(txn_date,'%Y-%m') ='".$month."'";
            }
        $where = ' AND DATE_FORMAT(txn_date, "%Y-%m") = "'.$month.'"';
        $report = $this->Common->get_all_info(1,$this->table_name . ' cp','1', $where_con, $this->PrimaryKey . ', cus.customer_name,0 as opening_bal,(SELECT CASE WHEN SUM(credit) IS NULL THEN 0 ELSE SUM(credit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0  '.$where.' ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN SUM(debit) IS NULL THEN 0 ELSE SUM(debit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0 '.$where.' ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id,0 as closing_bal', false, [
            ['table' => TBL_CUSTOMER . ' cus', 'on' => 'cus.customer_id = cp.customer_id	', 'type' => 'LEFT']
        ],'cp.customer_id',array('field' => 'cus.customer_name', 'order' => 'ASC'));

        if(empty($report)){
            $this->session->set_flashdata('error_msg', 'No data found for download!');
            redirect(BASE_URL.'ledger');
        }
        
        foreach ($report as $row) {
            // --- Order summary short format ---
           $row->opening_bal = ledger_opening_bal_row($row->ledger_id,$month);
           $row->closing_bal = ledger_closing_bal_row($row->ledger_id,$row->customer_id,$row->credit,$row->debit,$row->opening_bal,$month);

        }
        $data['reports']    = $report;
        $data['month']    = $month;
        // echo '<pre>';
        // print_r($report); die;
        $html = $this->load->view('ledger/ledger_list_pdf', $data, true);

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'strictHTML' => true,
            'allow_html_optional_endtags' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('Ledger_Report.pdf', 'I');
    }

    public function report_action()
    {
        $customer_id  = $this->input->get('customer_id');
        $month    = $this->input->get('month');
        $is_whatsapp    = $this->input->get('is_whatsapp') ?? 0;
        
        $where_con = "1=1";
        if(!empty($customer_id)){
            $where_con .= " AND cp.customer_id='".$customer_id."'";
        }

        if(!empty($month)){
            $where_con .= " AND DATE_FORMAT(txn_date,'%Y-%m') ='".$month."'";
        }

        $where = ' AND DATE_FORMAT(txn_date, "%Y-%m") = "'.$month.'"';

        $report = $this->Common->get_info(1,$this->table_name . ' cp','1', $where_con, $this->PrimaryKey . ', cus.customer_name,0 as opening_bal,(SELECT CASE WHEN SUM(credit) IS NULL THEN 0 ELSE SUM(credit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0  '.$where.' ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN SUM(debit) IS NULL THEN 0 ELSE SUM(debit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0 '.$where.' ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id,0 as closing_bal', [
            ['table' => TBL_CUSTOMER . ' cus', 'on' => 'cus.customer_id = cp.customer_id	', 'type' => 'LEFT']
        ],'cp.customer_id',array('field' => 'cus.customer_name', 'order' => 'ASC'));

        if(empty($report)){
            $this->session->set_flashdata('error_msg', 'No data found for download!');
            redirect(BASE_URL.'ledger');
        }

        $report->opening_bal = ledger_opening_bal_row($report->ledger_id,$month);
        $report->closing_bal = ledger_closing_bal_row($report->ledger_id,$report->customer_id,$report->credit,$report->debit,$report->opening_bal,$month);

        if($is_whatsapp){

            $customer = $this->Common->get_info($customer_id,TBL_CUSTOMER,'customer_id','','customer_id,customer_name,customer_whatsapp_number');

            $to_number = format_whatsapp_number($customer->customer_whatsapp_number);

            if (!$to_number) {
                $response = array("status" => "error", "heading" => "Invalid number.", "message" => "Invalid WhatsApp number.");
                echo json_encode($response);
                die;
            }

            $url = generate_tiny_url(
                'ledger',
                [
                    'customer_id' => $customer_id ?? '',
                    'month' => $month ?? '',
                ]
            );

            $parsed = parse_url($url);
            
            $short_url = ltrim($parsed['path'], '/');

            $month_text = date('F, Y',strtotime($month));

            $payload = [
                [
                    "type" => "body",
                    "parameters" => [
                        ["type" => "text", "parameter_name" =>"customer_name", "text" => $customer->customer_name],
                        ["type" => "text", "parameter_name" =>"summary_month", "text" => $month_text],
                        ["type" => "text", "parameter_name" =>"opening_balance", "text" => formatAmount($report->opening_bal)],
                        ["type" => "text", "parameter_name" =>"total_purchases", "text" => formatAmount($report->debit)],
                        ["type" => "text", "parameter_name" =>"total_payments", "text" => formatAmount($report->credit)],
                        ["type" => "text", "parameter_name" =>"closing_balance", "text" => formatAmount($report->closing_bal)],
                    ]
                ],
                [
                    "type" => "button",
                    "sub_type" => "url",
                    "index" => "0",
                    "parameters" => [
                        ["type" => "text", "text" => $short_url]
                    ]
                ]
            ];

            $is_send = send_whatsapp_template($to_number,'ledger_summary',$payload);
    
            if($is_send['status']){
                $response = array("status" => "ok", "heading" => "Sent successfully.", "message" => "Whatsapp message send successfully.");
            }else{
                $response = array("status" => "error", "heading" => "Not Sent successfully", "message" => "Whatsapp message not send successfully.");
            }
            echo json_encode($response);
            die;
        }else{
            $url = generate_tiny_url(
                'ledger',
                [
                    'customer_id' => $customer_id ?? '',
                    'month' => $month ?? '',
                ]
            );

            $response = array("status" => "ok", "heading" => "Link generated.", "message" => "Link generated successfully.","data" => $url);
            echo json_encode($response);
            die;
        }
    }
}
