<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public $table_name = TBL_CUSTOMER_PAYMENT;
    public $controllers = 'payment';
    public $view_name = 'payment';
    public $title = 'Payment';
    public $PrimaryKey = 'payment_id';

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
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
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
                ->set_rules('payment_date', 'Payment Date', 'required')
                ->set_rules('customer_name', 'Customer Name', 'required')
                ->set_rules('payment_amount', 'Payment Amount', 'required|numeric');
            $this->form_validation->set_message('required', 'The %s field is required.');
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    "customer_id" => $this->input->post('customer_name'),
                    "payment_date" => $this->input->post('payment_date'),
                    "payment_type" => $this->input->post('payment_type'),
                    "amount" => $this->input->post('payment_amount'),
                    "remark" => ($this->input->post('remarks')) ? $this->input->post('remarks') : '',
                );



                if ($id > 0):
                    $post_data['modified_by'] = $this->tank_auth->get_user_id();
                    $post_data['modified_on'] = date("Y-m-d H:i:s");
                    $old_payment = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
                    $old_amount = $old_payment ? $old_payment->amount : 0;
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)):
                        credit_ledger($this->input->post('customer_name'),$this->input->post('payment_amount'),$id,$this->input->post('remarks'),$old_amount,$this->input->post('payment_type'),$this->input->post('payment_date'));
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                else:
                    $post_data['created_on'] = date("Y-m-d H:i:s");
                    $post_data['created_by'] = $this->tank_auth->get_user_id();
                    //print_r($post_data);                    die();
                    if ($temp_id = $this->Common->add_info($this->table_name, $post_data)):
                        credit_ledger($this->input->post('customer_name'),$this->input->post('payment_amount'),$temp_id,$this->input->post('remarks'),0,$this->input->post('payment_type'),$this->input->post('payment_date'));
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                    endif;
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

        $this->datatables->select($this->PrimaryKey . ', cus.customer_name,cp.payment_date,payment_type,amount,remark')
            ->join(TBL_CUSTOMER . ' cus', 'cus.customer_id = cp.customer_id	', 'LEFT')
            ->from($this->table_name . ' as cp')
            ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
        // ->add_column('action', '$1', 'payment_action_row(' . $this->PrimaryKey . ')');
        $this->datatables->unset_column($this->PrimaryKey);
        $this->datatables->order_by($this->PrimaryKey, 'DESC');
        echo $this->datatables->generate();
    }



    function action_row($id)
    {
        if ($id != 1) {


            $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
            return $action;
        } else {
            return '';
        }
    }
}
