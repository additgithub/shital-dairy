<?php

use Mpdf\Tag\Em;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nav_menu extends CI_Controller {

   
    function __construct() {
        parent::__construct();
        $this->load->model('Navmenu');
    }

    function index() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else {
            $data["extra_js"] = array("jquery.nestable","nestable","manage-menu");
            $data["extra_css"] = array("jquery.nestable");
            // $data["nav_menu"] = $this->Navmenu->displayMenu();
            $data["menu_info"] = $this->Navmenu->displayMenu(); 
            // $data["menu_info"] = $this->Navmenu->displayEmpMenu($this->tank_auth->get_user_id()); 
            // echo '<pre>';
            // print_r($data["menu_info"]); die;
            $data['page_title'] = "Manage Menu";
            $data['main_content'] = 'nav-menu/manage-nav-menu';
            $this->load->view('main_content', $data);
        }
    }

    /**
     * Add function
     * 
     * Add new menu item
     * 
     * @param all form data using post method
     * @return html
     */     
    function add() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else {
            $data["parentMenu"] = $this->Navmenu->dropdown();
            $data["userList"] = $this->Navmenu->user_dropdown();
            // echo '<pre>';
            // print_r($data["userList"]);die;
            if ($this->input->post()) {
                //$parent_menu
                $error_element = error_elements();
                $this->form_validation
                        ->set_rules('menu_title', 'Menu Name', 'required|min_length[2]|max_length[50]')
                        ->set_error_delimiters($error_element[0], $error_element[1]);

                if ($this->form_validation->run()) {
                    if ($this->Navmenu->add_menu()) {
                        // $this->session->set_flashdata('success_msg', 'Menu detail added successfully!!');
                        redirect("nav-menu/");
                    } else {
                        // $this->session->set_flashdata('error_msg', 'Unknown Error, '.UNKNOWN_ERROR);
                        redirect("nav-menu/add/");
                    }
                }
            }

            $data['page_title'] = "Add Menu";
            $data['main_content'] = 'nav-menu/nav-menu';
            $this->load->view('main_content', $data);
        }
    }

    /**
     * Update function
     * 
     * Update new menu item
     * 
     * @param post new menu id $id and all form data using post method
     * @return html
     */    
    function edit($id) {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else {
            $data_found = 0;
            $data["parentMenu"] = $this->Navmenu->dropdown($id);
            $data["userList"] = $this->Navmenu->user_dropdown();
            $selectedUsers = $this->Common->get_all_info($id,TBL_EMP_NAV_MENU,'menu_id');
            // echo $this->db->last_query();die;
            // echo '<pre>';
            // print_r($selectedUsers);die;
            $selected_Users = array();
            if(!empty($selectedUsers)){
                foreach($selectedUsers as $single_user){
                    $selected_Users[] = $single_user->UserID;
                }
            }
            $data['selected_Users'] = $selected_Users;
            if ($id > 0) {
                $menu_obj = $this->Navmenu->single_menu($id);
                if (is_object($menu_obj) && count((array) $menu_obj) > 0) {
                    $data["menu_data"] = $menu_obj;
                    $data_found = 1;
                }
            }
            if ($data_found == 0) {
                // $this->session->set_flashdata('error_msg', 'Unknown Error, '.UNKNOWN_ERROR);
                redirect('nav-menu/');
            }

            if ($this->input->post()) {
                $error_element = error_elements();
                $this->form_validation
                        ->set_rules('menu_title', 'Menu Title', 'required|min_length[2]|max_length[50]')
                        ->set_error_delimiters($error_element[0], $error_element[1]);

                if ($this->form_validation->run()) {
                    if ($this->Navmenu->edit_menu()) {
                        // $this->session->set_flashdata('success_msg', 'Menu detail updated successfully!!');
                        redirect("nav-menu");
                    } else {
                        // $this->session->set_flashdata('error_msg', 'Unknown Error, '.UNKNOWN_ERROR);
                        redirect("nav-menu/");
                    }
                }
            }

            $data['page_title'] = "Edit Menu";
            $data['main_content'] = 'nav-menu/nav-menu';
            $this->load->view('main_content', $data);
        }
    }

    /**
     * ordering new menu position
     * 
     * @param post new menu position array(data)
     * @return json object
     */    
    function ordering() {
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else {
            $response = array("status" => "error", "heading" => "Unknown Error", "message" => UNKNOWN_ERROR);
            if($this->input->post("data") && $this->input->post("data")!=""){
                $menu_array = json_decode($this->input->post("data"));
                if(is_array($menu_array) && count($menu_array)>0){
                    foreach ($menu_array as $key => $value):
                        $id = $value->id;
                        $this->Navmenu->update_order($id,$key,0);
                        if(isset($value->children) && is_array($value->children)):
                            $child = $value->children;
                            foreach ($child as $index => $val):
                                $this->Navmenu->update_order($val->id,$index,$id);
                            endforeach;
                        endif;
                        
                    endforeach;
                    $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Navigation updated successfully!!!");
                }
            }
            echo json_encode($response);
        }
    }

    /**
     * Remove new menu
     * 
     * @param post new menu id (id)
     * @return json object
     */      
    function remove() {
        $response_array["status"] = "error";
        $response_array["heading"] = "Not removed!!";
        $response_array["message"] = "Menu details not removed successfully!!";
        if ($this->input->post("id") > 0) {
            $id = $this->input->post("id");
            $state_obj = $this->Navmenu->single_menu($id);
            if (is_object($state_obj) && count($state_obj) > 0) {
                if ($this->Navmenu->remove_menu()) {
                    $response_array["status"] = "ok";
                    $response_array["heading"] = "Removed successfully!!";
                    $response_array["message"] = "Menu details removed successfully!!";
                }
            }
        }
        echo json_encode($response_array);
    }

    /**
     * new menu manage
     * 
     * Get new menu list
     * 
     * @param 
     * @return html
     */      
    function manage() {
        /*$this->datatables->select('m.menu_id, m.menu_title, mp.menu_title as parent_title, m.menu_controller')
                ->from(TBL_NAV_MENU . ' m')
                ->join(TBL_NAV_MENU . ' mp', 'm.menu_parent = mp.menu_id', 'left')
                ->add_column('action', $this->action_row('$1'), 'm.menu_id');
        // ->unset_column('');


        echo $this->datatables->generate();*/
		
		$this->Navmenu->manage_menu();
		
    }

    /*function action_row($id) {
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit Menu" data-placement="top" data-toggle="tooltip" class="btn btn-xs btn-default btn-equal" href="nav-menu/edit/{$id}" ><i class="fa fa-pencil"></i></a>
                <a data-original-title="Remove Menu" data-placement="top" data-toggle="tooltip" href="javascript:" class="btn btn-xs btn-default btn-equal remove-menu" data-menu-id="{$id}" id="menu_{$id}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }*/

}