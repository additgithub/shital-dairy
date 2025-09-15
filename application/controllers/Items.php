<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends CI_Controller {

    public $table_name = TBL_M_ITEMS;
    public $controllers = 'items';
    public $view_name = 'items';
    public $title = 'Items';
    public $PrimaryKey = 'item_id';

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }
    }

    function index() {
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
        // print_r($data);die();
        $this->load->view('main_content', $data);
    }

    function add() {
        $data['page_title'] = "Add New " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    function edit($id) {

        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if (is_object($data_obj) && count((array) $data_obj) > 0) {
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

    function submit_form() {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                    ->set_rules('item_name', 'Item Name', 'required')
                    ->set_rules('item_code', 'Item Code', 'required')
                    // ->set_rules('size', 'Item Size', 'required')
                    // ->set_rules('factor', 'Factor', 'required')
                    ->set_rules('selling_price', 'Item Selling Price', 'required');
            $this->form_validation->set_message('required', 'The %s field is required.');
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    "item_code" => $this->input->post('item_code'),
                    "item_name" => $this->input->post('item_name'),
                    // "size" => $this->input->post('size'),
                    // "factor" => $this->input->post('factor'),
                    "selling_price" => $this->input->post('selling_price'),
                    // "reorder" => ($this->input->post('reorder') == 1) ? 1 : 0,
                );

              

                if ($id > 0):
                    $post_data['modified_by'] = $this->tank_auth->get_user_id();
                    $post_data['modified_on'] = date("Y-m-d H:i:s");
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)):
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                else:
                    $post_data['created_on'] = date("Y-m-d H:i:s");
                    $post_data['created_by'] = $this->tank_auth->get_user_id();
                    //print_r($post_data);                    die();
                    if ($this->Common->add_info($this->table_name, $post_data)):
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


    function manage() {

        $this->datatables->select($this->PrimaryKey . ', item_code,item_name,selling_price')
                ->from($this->table_name)
                ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
        $this->datatables->unset_column($this->PrimaryKey);
        echo $this->datatables->generate();
    }

   

    function action_row($id) {
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }

}
