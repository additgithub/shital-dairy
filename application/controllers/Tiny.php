<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// require 'vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

class Tiny extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index($hash)
    {
        $tiny_url = $this->Common->get_info($hash,TBL_TINY_URL,'hash');
        if(empty($tiny_url)){
            return $this->load->view('errors/html/error_404',['heading'=>'Data not Found.','message'=> 'Requested data not found or expired.']);
        }

        if($tiny_url->used_for == 'report'){
            $decoded_data = json_decode($tiny_url->data);
            $this->view_sales_report($decoded_data);
        }
    }

    function view_sales_report($url_data){
        $range = getMonthDateRange($url_data->month);
        // Fetch ledger data
        $report = $this->Common->get_order_report($range['first_date'], $range['last_date'], $url_data->customer_id);
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

        // echo '<pre>';
        // print_r($report);
        // die;

        $data['report'] = $report;
        $data['start_date'] = $range['first_date'];
        $data['end_date'] = $range['last_date'];
        // Load HTML view
        $html = $this->load->view('client_sale_register/sale_register_report_pdf', $data, true);
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

        $mpdf->SetTitle("Order Summary Report");
        $mpdf->WriteHTML($html);

        // Direct output (no saving)
        $mpdf->Output("order_summary_report.pdf", "I");
    }
}