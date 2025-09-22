<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Purchase extends CI_Controller
{

    public $table_name = TBL_PURCHASE_HDR;
    public $controllers = 'purchase';
    public $view_name = 'purchase';
    public $title = 'Purchase';
    public $PrimaryKey = 'purchase_hdr_id';

    function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        }
    }

    function index()
    {
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';

        $this->load->view('main_content', $data);
    }

    function add()
    {
        $data['page_title'] = "Add New " . $this->title;
        $data['items'] = $this->db->get('tbl_item')->result_array();
        $data['suppliers'] = $this->db->get('tbl_suppiler')->result_array();
        $date_part = date('Ymd');

        $this->load->view($this->view_name . '/form', $data);
    }
    function edit($id)
    {
        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            // print_r($data_obj);die;
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data["data_info"] = $data_obj;

                // ✅ get related order items
                $data["order_items"] = $this->Common->get_all_info('', TBL_PURCHASE_DTL, 'purchase_hdr_id', "purchase_hdr_id = $id");

                // ✅ get items for dropdown
                $data["items"] = $this->Common->get_all_info('', TBL_M_ITEMS, 'item_id');
                $data['suppliers'] = $this->db->get('tbl_suppiler')->result_array();

                $data_found = 1;
            }
        }
        if ($data_found == 0) {
            redirect('/');
        }

        $data['page_title'] = "Edit " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    public function submit_form()
    {
        if ($this->input->post()) {
            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "Please refresh and try again.");
            $error_element = error_elements();

            $this->form_validation
                ->set_rules('purchase_date', 'Purchase Date', 'required')
                ->set_rules('suppiler_id', 'Supplier Name', 'required')
                ->set_rules('item_id[]', 'Item', 'required')
                ->set_rules('item_qty[]', 'Qty', 'required');
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {
                $id = ($this->input->post($this->PrimaryKey)) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    'purchase_date'     => $this->input->post('purchase_date'),
                    'suppiler_id'  => $this->input->post('suppiler_id'),
                    'total_purchase_amount'         => $this->input->post('total_amount'),
                    'remarks'        => ($this->input->post('remarks')) ? $this->input->post('remarks') : '',
                );

                if ($id > 0) {
                    // Edit mode
                    $post_data['modified_on'] = date("Y-m-d H:i:s");
                    $post_data['modified_by'] = $this->tank_auth->get_user_id();

                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)) {
                        $this->Common->delete_info(TBL_PURCHASE_DTL, 'purchase_hdr_id', $id);
                        // _insert_purchase_items($id);
                        $items = [];
                        foreach ($this->input->post('item_id') as $index => $item_id) {
                            $items[] = [
                                'item_id' => $item_id,
                                'item_price' => $this->input->post('item_price')[$index],
                                'qty_pkt' => $this->input->post('item_total_pkt')[$index],
                                'qty_kg' => $this->input->post('item_qty')[$index],
                                'total_amount' => $this->input->post('item_total')[$index],
                                'purchase_price_per_kg' => $this->input->post('purchase_price_per_kg')[$index]
                            ];
                        }

                        purchase_item($id, $items);
                        $response = array("status" => "ok", "heading" => "Updated", "message" => "Order updated successfully.");
                    } else {
                        $response = array("status" => "error", "heading" => "Failed", "message" => "Order not updated.");
                    }
                } else {
                    // Add mode
                    $post_data['created_on']   = date("Y-m-d H:i:s");
                    $post_data['created_by']   = $this->tank_auth->get_user_id();
                    $post_data['modified_on']  = date("Y-m-d H:i:s");
                    $post_data['modified_by']  = $this->tank_auth->get_user_id();

                    // Temporarily insert to get the ID
                    $temp_id = $this->Common->add_info($this->table_name, $post_data);

                    if ($temp_id > 0) {
                        // _insert_purchase_items($temp_id);
                        $items = [];
                        foreach ($this->input->post('item_id') as $index => $item_id) {
                            $items[] = [
                                'item_id' => $item_id,
                                'item_price' => $this->input->post('item_price')[$index],
                                'qty_pkt' => $this->input->post('item_total_pkt')[$index],
                                'qty_kg' => $this->input->post('item_qty')[$index],
                                'total_amount' => $this->input->post('item_total')[$index],
                                'purchase_price_per_kg' => $this->input->post('purchase_price_per_kg')[$index]
                            ];
                        }

                        purchase_item($temp_id, $items);
                        $response = array("status" => "ok", "heading" => "Added", "message" => "Order added successfully.");
                    } else {
                        $response = array("status" => "error", "heading" => "Insert Failed", "message" => "Order not added.");
                    }
                }
            } else {
                $response['error'] = $this->form_validation->error_array();
            }

            echo json_encode($response);
            exit;
        }
    }



    function manage()
    {

        if ($this->tank_auth->get_user_role_id() == '2') {
            $this->datatables->where('created_by = ' . $this->tank_auth->get_user_id());
        }
        $this->datatables->join(TBL_SUPPLIERS . ' s', 's.suppiler_id = p.suppiler_id', 'left');
        $this->datatables->select('p.' . $this->PrimaryKey . ',p.purchase_date,s.suppiler_name,s.suppiler_mobile,p.total_purchase_amount')
            ->from($this->table_name . ' p')
            ->add_column('action', $this->action_row('$1'), 'p.' . $this->PrimaryKey);
        $this->datatables->unset_column('p.' . $this->PrimaryKey);
        $this->datatables->order_by('p.' . $this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
    }



    function action_row($id)
    {
        $invoice_url = base_url("orders/invoice_pdf/" . $id);
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }

    public function invoice_pdf($id)
    {
        $order = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
        $order_items = $this->Common->get_all_info('', TBL_PURCHASE_DTL, 'purchase_hdr_id', "purchase_hdr_id = $id");

        if (!$order) show_404();

        $data['order'] = $order;
        $data['order_items'] = $order_items;

        $html = $this->load->view('orders/invoice_template', $data, true);

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 0,
            'margin_footer' => 0,
            'fontDir' => [FCPATH . 'vendor/mpdf/mpdf/ttfonts'], // Direct path to your fonts
            'fontdata' => [
                'notosansgujarati' => [
                    'R' => 'NotoSansGujarati-Regular.ttf',
                    'useOTL' => 0x00, // Disable OTL features to avoid GPOS issues
                    'useKashida' => 0, // Disable kashida (Arabic justification)
                ],
            ],
            'default_font' => 'notosansgujarati',
            'useSubstitutions' => false, // Disable font substitutions
            'showImageErrors' => true, // Helpful for debugging

        ]);


        $mpdf->WriteHTML($html);
        ob_clean();
        $mpdf->Output('Invoice_' . $order->order_no . '.pdf', 'I');
    }

    public function item_summary()
    {
        if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }
        // Get filter input
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');

        if ($start_date && $end_date) {
            $data['summary'] = $this->Common->get_itemwise_order_summary($start_date, $end_date);
        } else {
            $data['summary'] = $this->Common->get_itemwise_order_summary($start_date, $end_date);
        }
        // $data['summary'] = $this->Common->get_itemwise_order_summary($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['page_title'] = 'Item Wise Order Summary';
        $data['main_content'] = $this->view_name . '/item_summary';

        $this->load->view('main_content', $data);
    }

    public function download_item_summary_pdf()
    {
        if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }
        // Get filter input
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $data['summary'] = $this->Common->get_itemwise_order_summary($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        // Load view to string
        $html = $this->load->view('orders/item_summary_template', $data, true);

        // Load mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 0,
            'margin_footer' => 0,
            'fontDir' => [FCPATH . 'vendor/mpdf/mpdf/ttfonts'], // Direct path to your fonts
            'fontdata' => [
                'notosansgujarati' => [
                    'R' => 'NotoSansGujarati-Regular.ttf',
                    'useOTL' => 0x00, // Disable OTL features to avoid GPOS issues
                    'useKashida' => 0, // Disable kashida (Arabic justification)
                ],
            ],
            'default_font' => 'notosansgujarati',
            'useSubstitutions' => false, // Disable font substitutions
            'showImageErrors' => true, // Helpful for debugging

        ]);


        $mpdf->WriteHTML($html);
        ob_clean();
        $mpdf->Output('item-summary.pdf', 'I');
    }

    public function stock_summary()
    {
        if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }

        // Get filter input
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');

        // Fetch item-wise summary including stock
        $data['summary'] = $this->Common->get_stock_summary($start_date, $end_date);

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['page_title'] = 'Item Wise Order Summary with Stock';
        $data['main_content'] = $this->view_name . '/stock_summary';

        $this->load->view('main_content', $data);
    }
    public function current_balance_summary()
    {
        if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }

        // Get filter input
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');

        // Fetch item-wise summary including stock
        $data['summary'] = $this->Common->current_balance_summary($start_date, $end_date);

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['page_title'] = 'Item Wise Current Balance Summary';
        $data['main_content'] = $this->view_name . '/current_balance_summary';

        $this->load->view('main_content', $data);
    }
}
