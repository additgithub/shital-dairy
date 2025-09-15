<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends CI_Controller
{

    public $table_name = TBL_SETTING;
    public $controllers = 'setting';
    public $view_name = 'setting';
    public $title = 'Setting';
    public $PrimaryKey = 'ID';

    function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else if ($this->tank_auth->get_user_role_id() != '1') {
            if($this->tank_auth->get_user_id() != 1544){
                redirect('/');
            }
        }
    }

    function index()
    {

        $data['page_title'] = "Manage " . $this->title;
        $data["data_info"] = array();
        $data_obj = $this->Common->get_info(1, $this->table_name, $this->PrimaryKey);
        //        print_r($data_obj);die;
        if (is_object($data_obj) && count((array) $data_obj) > 0) {
            $data["data_info"] = $data_obj;
            $data_found = 1;
        }
        $data['main_content'] = $this->view_name . '/form';
        $this->load->view('main_content', $data);
    }

    function add()
    {
        $data['page_title'] = "Add New " . $this->title;
        $this->load->view($this->view_name . '/form', $data);
    }

    function edit($id)
    {

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
        //        print_r($data);die;
        $this->load->view($this->view_name . '/form', $data);
    }

    function submit_form()
    {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                ->set_rules('Address', 'Address', 'required')
                ->set_rules('ContactName1', 'Contact Name 1', 'required')
                ->set_rules('ContactName2', 'Contact Name 1', 'required')
                ->set_rules('ContactMobile1', 'Contact 1 Mobile No.', 'required')
                ->set_rules('ContactMobile2', 'Contact 2 Mobile No.', 'required')
                ->set_rules('EmailID', 'Email ID', 'required')
                ->set_rules('TelephoneNo', 'Telephone No.', 'required')
                ->set_rules('RegisterAmount', 'Register Amount', 'required')
                ->set_rules('BankAmount', 'Bank Amount', 'required')
                ->set_rules('InstituteAmount', 'Institute Amount', 'required')
                ->set_rules('GST', 'GST', 'required')
                ->set_rules('ValuationAmount', 'Valuation Amount', 'required')
                ->set_rules('SRIncentive', 'Sales Representative Amount', 'required')
                // ->set_rules('YardIncentive', 'Yard Representative Amount', 'required')
                ->set_rules('BidLimit', 'Bid Limit Per Car', 'required');

            if (empty($_FILES['image']['name']) && $this->input->post('DefaultImage') == '') {
                $this->form_validation->set_rules('image', 'Car Default Image', 'required');
            }


            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {
                

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    "RegisterBothAmount" => $this->input->post('RegisterAmount'),
                    "BankAmount" => $this->input->post('BankAmount'),
                    "InstituteAmount" => $this->input->post('InstituteAmount'),
                    "ValuationAmount" => $this->input->post('ValuationAmount'),
                    "GST" => $this->input->post('GST'),
                    "SRIncentive" => $this->input->post('SRIncentive'),
                    "BidLimit" => $this->input->post('BidLimit'),
                    "Address" => $this->input->post('Address'),
                    "ContactName1" => $this->input->post('ContactName1'),
                    "ContactName2" => $this->input->post('ContactName2'),
                    "ContactMobile1" => $this->input->post('ContactMobile1'),
                    "ContactMobile2" => $this->input->post('ContactMobile2'),
                    "EmailID" => $this->input->post('EmailID'),
                    "TelephoneNo" => $this->input->post('TelephoneNo'),
                );
                if (!empty($_FILES['image']['name'])) {

                    $file_data = upload_file('image', CAR_DIR, '');
                    //                $file_data1 = upload_multiple_file('image1', CAR_VALUATION_DIR, '');
                    //                $file_data2 = upload_multiple_file('image2', CAR_VALUATION_DIR, '');
                    if (isset($file_data) && is_array($file_data) && $file_data['file_name'] != "") {
                        $response['status'] = "ok";
                        $response['heading'] = 'Image Uploaded';
                        $response['message'] = 'Image Uploaded';
                        $post_data['CarDefaultImage'] = $file_data['file_name'];
                        //                    echo json_encode($response);
                        //                    die;
                    } else {
                        $response['message'] = $file_data;
                        echo json_encode($response);
                        die;
                    }
                } else if ($this->input->post('DefaultImage') == '') {
                    $response['status'] = "error";
                    $response['heading'] = 'Image Missing';
                    $response['message'] = 'Image Missing';
                    echo json_encode($response);
                    die;
                }

                if ($id > 0) :
                    //$post_data['ModifiedBy'] = $this->tank_auth->get_user_id();
                    //$post_data['ModifiedOn'] = date("Y-m-d H:i:s");
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)) :
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else :
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                else :
                    //$post_data['CreatedOn'] = date("Y-m-d H:i:s");
                    //$post_data['CreatedBy'] = $this->tank_auth->get_user_id();
                    if ($this->Common->add_info($this->table_name, $post_data)) :
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                    else :
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
}
