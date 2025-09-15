<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home_cms extends CI_Controller {

    public $table_name = TBL_HOMECMS;
    public $controllers = 'home-cms';
    public $view_name = 'home_cms';
    public $title = 'Home CMS';
    public $PrimaryKey = 'ID';

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
//        print_r($data);die;
        $this->load->view($this->view_name . '/form', $data);
    }

    function submit_form() {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                    ->set_rules('BannerTagLine', 'Banner Tag Line', 'required');

            if (empty($_FILES['Image']['name']) && $this->input->post('BannerImage') == '') {
                $this->form_validation->set_rules('Image', 'Banner Image', 'required');
            }
            $this->form_validation->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    "BannerTagLine" => $this->input->post('BannerTagLine'),
                );
                if (!empty($_FILES['Image']['name'])) {
                    $file_data = upload_file('Image', BANNER_DIR, ($this->input->post('BannerImage')) ? $this->input->post('BannerImage') : '');
                    if (is_array($file_data) && $file_data['file_name'] != "") {
                        $post_data['BannerImage'] = $file_data['file_name'];
                    } else {
                        $response['message'] = $file_data;
                        echo json_encode($response);
                        die;
                    }
                }

                if ($id > 0):
                    //$post_data['ModifiedBy'] = $this->tank_auth->get_user_id();
                    //$post_data['ModifiedOn'] = date("Y-m-d H:i:s");
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)):
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                else:
                    //$post_data['CreatedOn'] = date("Y-m-d H:i:s");
                    //$post_data['CreatedBy'] = $this->tank_auth->get_user_id();
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

}
