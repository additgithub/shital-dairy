<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class Orders extends CI_Controller
{

    public $table_name = TBL_ORDER_HDR;
    public $controllers = 'orders';
    public $view_name = 'orders';
    public $title = 'Orders';
    public $PrimaryKey = 'order_hdr_id';

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
        // $data['items'] = $this->db->get('tbl_item')->result_array();
        $this->db->select("i.*,(CONCAT(i.item_code,' - ',i.item_name)) as item_name, s.total_purchase_qty_pkt, s.total_sells_qty_pkt")
            ->from("tbl_item i")
            ->join("tbl_item_stock s", "i.item_id = s.item_id", "left")
            ->order_by("i.item_code", "ASC");
        $data['items'] = $this->db->get()->result();
        $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
        // print_r($data['customers']);die;
        // print_r($data['items']);die;
        $date_part = date('Ymd');
        // Get last auto-increment ID
        $last_id = $this->Common->get_last_auto_id($this->table_name, $this->PrimaryKey); // e.g. 5
        $next_id = $last_id + 1;
        $generated_order_no = 'ORD' . $date_part . str_pad($next_id, 4, '0', STR_PAD_LEFT);

        $data['generated_order_no'] = $generated_order_no;
        $this->load->view($this->view_name . '/form', $data);
    }
    function edit($id)
    {
        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data["data_info"] = $data_obj;

                // ✅ get related order items
                $data["order_items"] = $this->Common->get_all_info('', TBL_ORDER_DTL, 'order_hdr_id', "order_hdr_id = $id");

                // ✅ get items for dropdown
                $data["items"] = $this->Common->get_all_info('', TBL_M_ITEMS, 'item_id','','*,(CONCAT(item_code," - ",item_name)) as item_name',false,false,false,array('field' => 'item_code','order' => 'ASC'));
                $data_found = 1;
                $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
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
        // print_r($this->input->post());
        // die('13233');
        if ($this->input->post()) {
            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "Please refresh and try again.");
            $error_element = error_elements();

            $this->form_validation
                ->set_rules('order_date', 'Order Date', 'required')
                ->set_rules('customer_name', 'Customer Name', 'required')
                ->set_rules('contact_no', 'Contact No', 'required')
                ->set_rules('delivery_time', 'Delivery Time', 'required')
                ->set_rules('item_id[]', 'Item', 'required')
                ->set_rules('item_qty[]', 'Qty', 'required');
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {
                $id = ($this->input->post($this->PrimaryKey)) ? $this->input->post($this->PrimaryKey) : 0;
                foreach ($this->input->post('item_id') as $index => $item_id) {
                    $qty = $this->input->post('item_qty')[$index];
                    $item = $this->db->select('i.item_name, s.total_purchase_qty_pkt, s.total_sells_qty_pkt,i.reorder')
                        ->from('tbl_item i')
                        ->join('tbl_item_stock s', 'i.item_id = s.item_id')
                        ->where('i.item_id', $item_id)
                        ->get()->row();
                    $available = $item->total_purchase_qty_pkt - $item->total_sells_qty_pkt;

                    if ($item && $qty > $available && $item->reorder == 0) {
                        if ($available == 0) {
                            $error_msg = $item->item_name . ' is out of stock.';
                        } else {
                            $error_msg = $item->item_name . ' has only ' . $available . ' pkt available.';
                        }
                        $response['message'] = $error_msg;
                        echo json_encode($response);
                        exit;
                    }
                }
                $post_data = array(
                    'order_date'     => $this->input->post('order_date'),
                    'customer_name'  => $this->input->post('customer_name'),
                    'contact_no'     => $this->input->post('contact_no'),
                    'amount'         => $this->input->post('total_amount'),
                    'delivery_charges'         => $this->input->post('delivery_charges'),
                    'dry_ice_box_charges'         => $this->input->post('dry_ice_box_charges'),
                    'other_charges'         => $this->input->post('other_charges'),
                    'delivery_time'         => $this->input->post('delivery_time'),
                    'remarks'        => $this->input->post('remarks'),
                );

                if ($id > 0) {
                    // Edit mode
                    $post_data['modified_on'] = date("Y-m-d H:i:s");
                    $post_data['modified_by'] = $this->tank_auth->get_user_id();

                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)) {
                        $this->Common->delete_info('tbl_order_dtl', 'order_hdr_id', $id);
                        // _insert_order_items($id);
                        $items = []; // Build this from POSTed form data
                        foreach ($this->input->post('item_id') as $index => $item_id) {
                            $items[] = [
                                'item_id' => $item_id,
                                'qty' => $this->input->post('item_qty')[$index],
                                'price_per_item' => $this->input->post('price_per_kg')[$index],
                                'amount' => $this->input->post('item_total')[$index],
                                'item_name' => $this->input->post('item_name')[$index] ?? '' // only for error message
                            ];
                        }

                        order_item($id, $items);
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
                    $post_data['is_paid']      = 0;
                    $post_data['paid_date']    = '0000-00-00';
                    $post_data['id_delivered'] = 0;
                    $post_data['delivery_date'] = '0000-00-00';

                    // Temporarily insert to get the ID
                    $temp_id = $this->Common->add_info($this->table_name, $post_data);

                    if ($temp_id > 0) {
                        $date_part = date('Ymd');
                        $order_no = 'ORD' . $date_part . str_pad($temp_id, 4, '0', STR_PAD_LEFT);
                        $this->Common->update_info($temp_id, $this->table_name, array('order_no' => $order_no), $this->PrimaryKey);
                        $items = []; // Build this from POSTed form data
                        foreach ($this->input->post('item_id') as $index => $item_id) {
                            $items[] = [
                                'item_id' => $item_id,
                                'qty' => $this->input->post('item_qty')[$index],
                                'price_per_item' => $this->input->post('price_per_kg')[$index],
                                'amount' => $this->input->post('item_total')[$index],
                                'item_name' => $this->input->post('item_name')[$index] ?? '' // only for error message
                            ];
                        }

                        order_item($temp_id, $items);
                        // _insert_order_items($temp_id);
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
        $this->datatables->select($this->PrimaryKey . ', ord.order_no,ord.order_date,cus.customer_name,ord.contact_no,ord.amount');
        $this->datatables->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = ord.customer_name', 'LEFT')
            ->from($this->table_name . ' ord')
            // ->add_column('is_paid', '$1', 'order_paid_row(' . $this->PrimaryKey . ')')
            // ->add_column('id_delivered', '$1', 'order_delivered_row(' . $this->PrimaryKey . ')')
            ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->order_by($this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
    }



    function action_row($id)
    {
        $invoice_url = base_url("orders/invoice_pdf/" . $id);
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_return_popup" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                <a data-original-title="Download Invoice" data-placement="top" data-toggle="tooltip" href="{$invoice_url}"  class="btn btn-default btn-equal btn-mini" target="_blank"><i class="fa fa-download"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }

    public function invoice_pdf($id)
    {
        $order = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);

        $join = array(
            array('table' => TBL_RETURN_QTY . ' r', 'on' => 'r.order_dtl_id = d.order_dtl_id', 'type' => 'LEFT')
        );

        $order_items = $this->Common->get_all_info('', TBL_ORDER_DTL.' d', 'd.order_hdr_id', "d.order_hdr_id = $id",'d.*,r.return_qty', false, $join);

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

    function paid($id)
    {
        if ($id > 0) {
            $IsFeatured = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'is_paid');
            if ($IsFeatured->is_paid == 0) {
                $isActive = 1;
                $status = "ok";
                $heading = "Success";
                $message = "Order Updated";
            } else {
                $isActive = 0;
                $status = "ok";
                $heading = "Success";
                $message = "Order Updated";
            }
            $data = array(
                "is_paid" => $isActive,
            );

            if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                $response = array("status" => $status, "heading" => $heading, "message" => $message);
                echo json_encode($response);
                die;
            }
        }
    }
    function delivered($id)
    {
        if ($id > 0) {
            $IsFeatured = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'id_delivered');
            if ($IsFeatured->id_delivered == 0) {
                $isActive = 1;
                $status = "ok";
                $heading = "Success";
                $message = "Order Updated";
            } else {
                $isActive = 0;
                $status = "ok";
                $heading = "Success";
                $message = "Order Updated";
            }
            $data = array(
                "id_delivered" => $isActive,
            );

            if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                $response = array("status" => $status, "heading" => $heading, "message" => $message);
                echo json_encode($response);
                die;
            }
        }
    }
    function get_return_form($id)
    {
        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
                $data["data_info"] = $data_obj;

                $join = array(
                    array('table' => TBL_RETURN_QTY . ' r', 'on' => 'r.order_dtl_id = d.order_dtl_id', 'type' => 'LEFT')
                );

                // ✅ get related order items
                $data["order_items"] = $this->Common->get_all_info(1, TBL_ORDER_DTL . ' d', 1, "d.order_hdr_id = $id", 'd.*,r.return_qty', false, $join);

                // ✅ get items for dropdown
                $data["items"] = $this->Common->get_all_info('', TBL_M_ITEMS, 'item_id','','*,(CONCAT(item_code," - ",item_name)) as item_name',false,false,false,array('field' => 'item_code','order' => 'ASC'));
                $data_found = 1;
                $data['customers'] = $this->Common->get_all_info(1, TBL_CUSTOMER, 1, '', 'customer_id,customer_name,');
            }
        }
        if ($data_found == 0) {
            redirect('/');
        }

        $data['page_title'] = "Edit " . $this->title;
        $this->load->view($this->view_name . '/return_form', $data);
    }
    function return_qty_submit()
    {
        $exist = $this->Common->check_is_exists(TBL_RETURN_QTY, $this->input->post('order_hdr_id'), 'order_hdr_id');
        $total = 0;
        if ($exist > 0) {
            foreach ($this->input->post('item_id') as $index => $item_id) {
                $items = array(
                    'item_id' => $item_id,
                    // 'qty' => $this->input->post('item_qty')[$index],
                    'return_qty' => $this->input->post('return_qty')[$index],
                    'order_dtl_id' => $index,
                    'order_hdr_id'     => $this->input->post('order_hdr_id'),
                    'created_on'   => date("Y-m-d H:i:s"),
                    'created_by'   => $this->tank_auth->get_user_id(),
                    // 'item_name' => $this->input->post('item_name')[$index] ?? '' // only for error message
                );
                $this->Common->update_info($index, TBL_RETURN_QTY, $items, 'order_dtl_id');
                
                $price_per_item = $this->input->post('price_per_item')[$index];
                $return_qty = $this->input->post('return_qty')[$index];
                $item_qty = $this->input->post('item_qty')[$index];

                $sub_total = (float)$price_per_item * ((float)$item_qty - (float)$return_qty);
                $this->Common->update_info($index, TBL_ORDER_DTL, [
                    'amount' => $sub_total,
                    'modified_on' => date("Y-m-d H:i:s")
                ], 'order_dtl_id');

                $total += $sub_total;
            }
        } else {
            foreach ($this->input->post('item_id') as $index => $item_id) {
                $items = array(
                    'item_id' => $item_id,
                    // 'qty' => $this->input->post('item_qty')[$index],
                    'return_qty' => $this->input->post('return_qty')[$index],
                    'order_dtl_id' => $index,
                    'order_hdr_id'     => $this->input->post('order_hdr_id'),
                    'created_on'   => date("Y-m-d H:i:s"),
                    'created_by'   => $this->tank_auth->get_user_id(),
                    // 'item_name' => $this->input->post('item_name')[$index] ?? '' // only for error message
                );
                $this->Common->add_info(TBL_RETURN_QTY, $items);

                $price_per_item = $this->input->post('price_per_item')[$index];
                $return_qty = $this->input->post('return_qty')[$index];
                $item_qty = $this->input->post('item_qty')[$index];

                $sub_total = (float)$price_per_item * ((float)$item_qty - (float)$return_qty);
                $this->Common->update_info($index, TBL_ORDER_DTL, [
                    'amount' => $sub_total,
                    'modified_on' => date("Y-m-d H:i:s")
                ], 'order_dtl_id');

                $total += $sub_total;
            }
        }

        $order_hdr_id = $this->input->post('order_hdr_id');
        $order_header = $this->Common->get_info($order_hdr_id, $this->table_name, $this->PrimaryKey);
        
        $total = $total + (float)$order_header->delivery_charges + (float)$order_header->dry_ice_box_charges + (float)$order_header->other_charges;

        $this->Common->update_info($order_hdr_id, TBL_ORDER_HDR, [
            'amount' => $total,
            'modified_on'   => date("Y-m-d H:i:s"),
            'modified_by'   => $this->tank_auth->get_user_id(),
        ], $this->PrimaryKey);

        $response = array("status" => "ok", "heading" => "Updated", "message" => "Return Qty updated successfully.");
        echo json_encode($response);
        exit;
    }
}
