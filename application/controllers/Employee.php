<?php

use Mpdf\Tag\Em;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee extends CI_Controller
{

    public $table_name = TBL_USERS;
    public $controllers = 'employee';
    public $view_name = 'employee';
    public $title = 'Employee';
    public $PrimaryKey = 'id';

    function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        }
        if ($this->tank_auth->get_user_role_id() != '1') {
            redirect('/');
        }
        $this->load->Model('Remove_records');
    }

    function index()
    {
        $data['extra_js'] = array('manage-client', 'manage-employee');
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
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
            
            $data_obj = $this->Common->get_info($id, $this->table_name . ' sr', $this->PrimaryKey, '', '*');
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


    function submit_form()
    {
        if ($this->input->post()) {
            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                ->set_rules('first_name', 'First Name', 'required')
                ->set_rules('last_name', 'Last Name', 'required')
                ->set_rules('email', 'Email Id', 'trim|required|valid_email')
                ->set_rules('mobile_no', 'Mobile No', 'trim|required')
                ->set_rules('password', 'Password', 'trim|required')
                ->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]')
                ->set_message('required', 'The %s field is required.')
                ->set_error_delimiters($error_element[0], $error_element[1]);

            $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;
            if ($id == 0) {
                $this->form_validation->set_rules('password', 'Password', 'required');
            }
            if ($this->form_validation->run()) {


                $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'tank_auth'),
                    $this->config->item('phpass_hash_portable', 'tank_auth')
                );
                $hashed_password = $hasher->HashPassword($this->input->post('password'));
                $SalesCode = randomNumber();
                $post_data = array(
                    "first_name" => ($this->input->post('first_name')) ? $this->input->post('first_name') : '',
                    "last_name" => ($this->input->post('last_name')) ? $this->input->post('last_name') : '',
                    "email" => $this->input->post('email'),
                    "mobile_no" => ($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : '',
                    "is_active" => 1,
                );
                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;


                if ($this->Common->check_is_exists(TBL_USERS, $post_data['email'], "email", $id, $field = 'id')) :
                    $response['heading'] = 'Employee Email details already exists';
                    $response['message'] = 'Employee Email already exists, Pls Use another Number...!';
                    echo json_encode($response);
                    die;
                endif;
                if ($this->input->post('mobile_no') != '') {
                    if ($this->Common->check_is_exists(TBL_USERS, $post_data['mobile_no'], "mobile_no", $id, $field = 'id')) :
                        $response['heading'] = 'Employee Mobile Number details already exists';
                        $response['message'] = 'Employee Mobile Number already exists, Pls Use another Number...!';
                        echo json_encode($response);
                        die;
                    endif;
                }

                $employee_user_data = array(
                    "role_id" => 2,
                    "username" => $this->input->post('email'),
                    "first_name" => ($this->input->post('first_name')) ? $this->input->post('first_name') : '',
                    "last_name" => ($this->input->post('last_name')) ? $this->input->post('last_name') : '',
                    "email" => $this->input->post('email'),
                    "mobile_no" => ($this->input->post('mobile_no')) ? $this->input->post('mobile_no') : '',
                );
                if ($id > 0) {
                    $post_data['modified'] = date("Y-m-d H:i:s");

                    if ($user_id = $this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)) {
                        $employee_info = $this->Common->get_info($id, $this->table_name, 'EmployeeID');
                        if ($this->input->post('password') != '') {
                            $employee_user_data["password"] = $hashed_password;
                        }
                        // print_r($employee_user_data);die;
                        $this->Common->update_info($id, TBL_USERS, $employee_user_data, 'id');
                      
                        
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    } else {
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    }
                } else {
                    
                    $post_data['created'] = date("Y-m-d H:i:s");
                    $employee_user_data["password"] = $hashed_password;
                    if ($user_id = $this->Common->add_info(TBL_USERS, $employee_user_data)) {
                      
                        $response = array("status" => "ok", "heading" => "Add successfully...", "message" => "Details added successfully.");
                    } else {
                        $response = array("status" => "error", "heading" => "Not Added successfully...", "message" => "Details not added successfully.");
                    }
                }
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
       
        $this->datatables->select($this->PrimaryKey . ',first_name,last_name,email,mobile_no');
               $this->datatables->where('role_id = 2');

        $this->datatables->from($this->table_name)
            ->add_column('action', $this->action_row('$1'), $this->PrimaryKey);
        $this->datatables->unset_column($this->PrimaryKey);
        echo $this->datatables->generate();
    }

    function action_row($id)
    {
        if ($this->tank_auth->get_user_role_id() == 1) {
            $action = <<<EOF
            <div class="tooltip-top">
                
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
               
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
            return $action;
        } else {
            $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Add {$this->title} Commision" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini client_details" data-id="{$id}" data-control="{$this->controllers}" data-method="commission"><i class="fa fa-plus"></i></a>
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                
            </div>
EOF;
            return $action;
        }
    }
   
}
