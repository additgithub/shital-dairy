<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// require 'vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Sale_register extends CI_Controller
{

    public $table_name = TBL_LEDGER;
    public $controllers = 'sale_register';
    public $view_name = 'sale_register';
    public $title = 'Sale Register';
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
        $report = $this->Common->get_order_report($start_date, $end_date, $customer_id);
        // echo '<pre>';
        // print_r($report);
        // die;
        // Add remarks: replace with order summary if applicable
        foreach ($report as &$row) {
            if ($row['order_hdr_id'] > 0) {
                $row['remark'] = $this->Common->get_order_summary_html_without_customer_details($row['order_hdr_id']);
            } else {
                $row['remark'] = "<div>No Order Found</div>";
            }

            // else keep existing remark
        }

        $data['report'] = $report;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        // Load HTML view
        $html = $this->load->view($this->view_name . '/sale_register_report_pdf', $data, true);
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
}
