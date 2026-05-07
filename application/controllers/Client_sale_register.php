<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// require 'vendor/autoload.php';
require_once FCPATH . 'vendor/autoload.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Client_sale_register extends CI_Controller
{

    public $table_name = TBL_ORDER_HDR;
    public $controllers = 'client_sale_register';
    public $view_name = 'client_sale_register';
    public $title = 'Client Wise Sale Register';
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
        $data["extra_js"] = array("manage-sales-report");

        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }

    function manage()
    {
        if ($this->input->post('customer_name') && $this->input->post('customer_name') > 0) {
            $this->datatables->where('ord.customer_name', $this->input->post('customer_name'));
        }
        if ($this->input->post('month') && $this->input->post('month') != '') {
            $this->datatables->where('DATE_FORMAT(ord.order_date, "%Y-%m") = "'.$this->input->post('month').'"');
            $this->Month = $this->input->post('month');
        }
        
        $this->datatables->select($this->PrimaryKey . ',cus.customer_id,cus.customer_name,COUNT('.$this->PrimaryKey.') as total_order');
        $this->datatables->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = ord.customer_name', 'LEFT')
            ->from($this->table_name . ' ord')
            // ->add_column('is_paid', '$1', 'order_paid_row(' . $this->PrimaryKey . ')')
            // ->add_column('id_delivered', '$1', 'order_delivered_row(' . $this->PrimaryKey . ')')
            ->add_column('action', $this->action_row('$1'), 'cus.customer_id');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->unset_column('cus.customer_id');
        $this->datatables->group_by('ord.customer_name');
        // $this->datatables->order_by($this->PrimaryKey, 'DESC');
        // $this->datatables->order_by('cus.customer_name', 'ASC');
        echo $this->datatables->generate();
    }

    function action_row($id)
    {
        $report_url = base_url("client_sale_register/report_action");
        $icon_url = base_url("assets/img/icon/whatsapp.png");
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Download Report" data-placement="top" data-toggle="tooltip" data-id="{$id}" data-url="{$report_url}" class="btn btn-default btn-equal btn-mini btn_report_download"><i class="fa fa-download"></i></a>
                <a data-original-title="Send Whatsapp Message" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal send_whatsapp btn-mini" data-id="{$id}" data-url="{$report_url}"><img src="{$icon_url}" /></a>
            </div>
        EOF;
        return $action;
    }

    function generate_tiny_url($data){
        $used_for = 'report';
        $json_data = $this->normalize_json($data);

        // check existing
        $existing = $this->db
            ->where('used_for', $used_for)
            ->where('data', $json_data)
            ->get(TBL_TINY_URL)
            ->row();

        if ($existing) {
            // ✅ Already exists → reuse hash
            $hash = $existing->hash;
        } else {
            // ❌ Not exists → create new
            $hash = generateUniqueId();

            $post_data = [
                'hash'       => $hash,
                'used_for'   => $used_for,
                'data'       => $json_data,
                'created_on' => date("Y-m-d H:i:s"),
                'created_by' => $this->tank_auth->get_user_id()
            ];

            $this->Common->add_info(TBL_TINY_URL, $post_data);
        }

        return base_url('tiny/'.$hash);
    }

    function normalize_json($data) {
        ksort($data);
        return json_encode($data);
    }

    public function report_action()
    {
        $customer_id  = $this->input->get('customer_id');
        $month    = $this->input->get('month');
        $is_whatsapp    = $this->input->get('is_whatsapp') ?? 0;

        if($is_whatsapp){

            $customer = $this->Common->get_info($customer_id,TBL_CUSTOMER,'customer_id','','customer_id,customer_name,customer_whatsapp_number');

            $to_number = $this->format_whatsapp_number($customer->customer_whatsapp_number);

            if (!$to_number) {
                $response = array("status" => "error", "heading" => "Invalid number.", "message" => "Invalid WhatsApp number.");
                echo json_encode($response);
                die;
            }

            $url = $this->generate_tiny_url([
                'customer_id' => $customer_id ?? '',
                'month' => $month ?? '',
            ]);

            $parsed = parse_url($url);
            
            $short_url = ltrim($parsed['path'], '/');

            $month_text = date('F, Y',strtotime($month));

            $total_order = $this->Common->get_all_info($customer_id,TBL_ORDER_HDR,'customer_name','DATE_FORMAT(order_date, "%Y-%m") = "'.$month.'"','order_hdr_id',true);

            $payload = [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => base_url("assets/img/logo.png")
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        ["type" => "text", "parameter_name" =>"customer_name", "text" => $customer->customer_name],
                        ["type" => "text", "parameter_name" =>"summary_month", "text" => $month_text],
                        ["type" => "text", "parameter_name" =>"total_orders", "text" => $total_order]
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

            $is_send = send_whatsapp_template($customer->customer_whatsapp_number,'order_summary',$payload);
    
            if($is_send['status']){
                $response = array("status" => "ok", "heading" => "Sent successfully.", "message" => "Whatsapp message send successfully.");
            }else{
                $response = array("status" => "error", "heading" => "Not Sent successfully", "message" => "Whatsapp message not send successfully.");
            }
            echo json_encode($response);
            die;
        }else{
            $url = $this->generate_tiny_url([
                'customer_id' => $customer_id ?? '',
                'month' => $month ?? '',
            ]);

            $response = array("status" => "ok", "heading" => "Link generated.", "message" => "Link generated successfully.","data" => $url);
            echo json_encode($response);
            die;
        }
    }

    function format_whatsapp_number($number)
    {
        // Remove spaces, +, -, etc.
        $number = preg_replace('/\D/', '', $number);

        // Case 1: starts with 91 and total 12 digits
        if (strlen($number) == 12 && substr($number, 0, 2) == '91') {
            return $number;
        }

        // Case 2: starts with +91 (already removed + above, so same as 91 case)
        if (strlen($number) == 12 && substr($number, 0, 2) == '91') {
            return $number;
        }

        // Case 3: pure 10 digit number → add 91
        if (strlen($number) == 10) {
            return '91' . $number;
        }

        // ❌ Invalid number
        return false;
    }
}
