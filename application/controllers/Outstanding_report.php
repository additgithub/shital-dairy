<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// require 'vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

class Outstanding_report extends CI_Controller
{

    public $table_name = TBL_LEDGER;
    public $controllers = 'outstanding_report';
    public $view_name = 'outstanding_report';
    public $title = 'Outstanding Report';
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
        $data['main_content'] = $this->view_name . '/list';
        $this->load->view('main_content', $data);
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
        $this->datatables->select($this->PrimaryKey . ', cus.customer_name,0 as closing_bal,0 as opening_bal,(SELECT CASE WHEN SUM(credit) IS NULL THEN 0 ELSE SUM(credit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0  '.$where.' ORDER BY ledger_id DESC LIMIT 1) as credit,(SELECT CASE WHEN SUM(debit) IS NULL THEN 0 ELSE SUM(debit) END FROM ' . $this->table_name . ' WHERE customer_id = cp.customer_id AND is_opening_bal=0 '.$where.' ORDER BY ledger_id DESC LIMIT 1) as debit,cus.customer_id')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp');
            // ->add_column('action', '$1', 'action_row(cus.customer_id)');

        // ->add_column('action', '$1', 'payment_action_row(' . $this->PrimaryKey . ')');
        $this->datatables->edit_column('opening_bal', '$1', 'ledger_opening_bal_row(' . $this->PrimaryKey . ',"'.$this->input->post('month').'")');
        $this->datatables->edit_column('closing_bal', '$1', 'ledger_closing_bal_row(' . $this->PrimaryKey . ',cus.customer_id,credit,debit,opening_bal,"'.$this->input->post('month').'",true)');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cus.customer_id');
        $this->datatables->unset_column('credit');
        $this->datatables->unset_column('debit');
        $this->datatables->unset_column('opening_bal');
        $this->datatables->group_by('cp.customer_id');
        // $this->datatables->order_by($this->PrimaryKey, 'DESC');
        // $this->datatables->order_by('cus.customer_name', 'ASC');
        echo $this->datatables->generate();
        //  echo $this->db->last_query();die;
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
           $row->closing_bal = ledger_closing_bal_row($row->ledger_id,$row->customer_id,$row->credit,$row->debit,$row->opening_bal,$month,true);

        }
        $data['reports']    = $report;
        $data['month']    = $month;
        // echo '<pre>';
        // print_r($report); die;
        $html = $this->load->view('outstanding_report/outstanding_list_pdf', $data, true);

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
}