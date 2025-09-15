<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Navmenu extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Remove_records');
    }

    /**
     * add_menu
     * 
     * add new menu
     * 
     * @param array
     * @return numeric
     */      
    function add_menu() {
        $menu_role = ($this->input->post('menu_role') && $this->input->post('menu_role')!="")?implode(",", $this->input->post('menu_role')):"1";
        $data = array(
            "menu_title" => $this->input->post('menu_title'),
            "menu_parent" => $this->input->post('menu_parent'),
            "menu_controller" => $this->input->post('menu_controller'),
            "menu_link" => $this->input->post('menu_link'),
            "menu_icon" => $this->input->post('menu_icon'),
            "menu_role" => $menu_role,
            "menu_is_acrive" => $this->input->post('is_active'),
            "menu_add_date" => date("Y-m-d h:i:s"),
        );
        $this->db->insert(TBL_NAV_MENU, $data);
        $nav_id = $this->db->insert_id();
        $employe_role = $this->input->post('user_role');
        if(!empty($employe_role)){
            $this->db->where('menu_id', $nav_id);
            $this->db->delete(TBL_EMP_NAV_MENU);
            foreach($employe_role as $single_user){
                $user_menuData = array(
                    "UserID" => $single_user,
                    "menu_id" => $nav_id,
                    "menu_add_date" => date("Y-m-d h:i:s"),
                );
                $this->db->insert(TBL_EMP_NAV_MENU, $user_menuData);
            }
        }
        return $nav_id;
    }

    /**
     * edit_menu
     * 
     * Update menu data
     * 
     * @param numeric, array
     * @return numeric
     */     
    function edit_menu() {
        if ($this->single_menu($this->input->post('menu_id'), 1) > 0) {
            $menu_role = ($this->input->post('menu_role') && $this->input->post('menu_role')!="")?implode(",", $this->input->post('menu_role')):"1";
            $employe_roles = ($this->input->post('user_role') && $this->input->post('user_role')!="")?implode(",", $this->input->post('user_role')):"";
            $employe_role = $this->input->post('user_role');
            
            $data = array(
                "menu_title" => $this->input->post('menu_title'),
                "menu_parent" => $this->input->post('menu_parent'),
                "menu_controller" => $this->input->post('menu_controller'),
                "menu_link" => $this->input->post('menu_link'),
                "menu_icon" => $this->input->post('menu_icon'),
                "menu_role" => $menu_role,
                // "user_ids" => $employe_roles,
                "menu_is_acrive" => $this->input->post('is_active'),
            );
            
            $this->db->where('menu_id = ', $this->input->post('menu_id'));
            $this->db->update(TBL_NAV_MENU, $data);
            // echo $this->db->last_query();die;
            if(!empty($employe_role)){
                $this->db->where('menu_id', $this->input->post("menu_id"));
                $this->db->delete(TBL_EMP_NAV_MENU);
                foreach($employe_role as $single_user){

                    $user_menuData = array(
                        "UserID" => $single_user,
                        "menu_id" => $this->input->post('menu_id'),
                        "menu_add_date" => date("Y-m-d h:i:s"),
                    );
                    // print_r($user_menuData);die;
                    $this->db->insert(TBL_EMP_NAV_MENU, $user_menuData);
                }
            }else{
                $this->db->where('menu_id', $this->input->post("menu_id"));
                $this->db->delete(TBL_EMP_NAV_MENU);
            }
            return $this->input->post('menu_id');
        }
        return 0;
    }

    /**
     * update_order 
     * 
     * Update menu order 
     * 
     * @param numeric, numeric, numeric
     * @return void
     */    
    function update_order($menu_id, $order, $parent) {
        //echo $menu_id; die;
        if ($this->single_menu($menu_id, 1) > 0) {
            $data = array(
                "menu_index_no" => $order,
                "menu_parent" => $parent,
            );
            $this->db->where('menu_id', $menu_id);
            if($this->db->update(TBL_NAV_MENU, $data)):
                //return TRUE; 
            else:
               // return FALSE;
            endif;
        }
        return 0;
    }

    /**
     * remove_menu
     * 
     * Delete menu item
     * 
     * @param numeric
     * @return true / false
     */     
    function remove_menu() {
        $this->db->where('menu_id', $this->input->post("id"));
        return $this->db->delete(TBL_NAV_MENU);
        $this->db->where('menu_parent', $this->input->post("id"));
        return $this->db->delete(TBL_NAV_MENU);
    }

    /**
     * Get single menu data
     * 
     * Get number of row and row data
     * 
     * @param menu id $id and only row $only_row
     * @return object / number
     */       
    function single_menu($id, $only_row = 0) {
        $this->db->select('*');
        $this->db->where('menu_id', $id);
        $query = $this->db->get(TBL_NAV_MENU);
        if ($query->num_rows() > 0) {
            if ($only_row > 0)
                return $query->num_rows();
            else
                return $query->row();
        }
        return 0;
    }

    /**
     * dropdown
     * 
     * Get menu items dropdown list
     * 
     * @param numeric
     * @return array
     */     
    function dropdown($id = 0) {
        $this->db->select('menu_id,menu_title')->from(TBL_NAV_MENU);
        if ($id > 0)
            $this->db->where('menu_id != ', $id);
        $query = $this->db->get();
        $menu_array = $query->result_array();
        //$response_array = array("0" => "Select State");
        $response_array = array("" => "--- Main Menu ---");
        if (count($menu_array) > 0) {
            foreach ($menu_array as $menu_arr) {
                $response_array[$menu_arr["menu_id"]] = ucwords($menu_arr["menu_title"]);
            }
        }
        return $response_array;
    }

    /**
     * displayMenu
     * 
     * Get display menu list oder wise
     * 
     * @param numeric
     * @return array
     */    
    function displayMenu() {
        $this->db->where('menu_parent', 0);
        // $this->db->where('menu_id !=', 68);
        $this->db->order_by('menu_index_no');
        $query = $this->db->get(TBL_NAV_MENU);
        $result = $query->result_array();
        foreach ($result as $key => $menu_row) {
            $this->db->order_by('menu_index_no');
            $row = array();
            $sub_query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $menu_row['menu_id']));
            $row['menu_id'] = $menu_row["menu_id"];
            $row['title_name'] = $menu_row["menu_title"];
            $row['menu_controller'] = $menu_row["menu_controller"];
            $row['menu_icon'] = $menu_row["menu_icon"];
            $row['menu_role'] = $menu_row["menu_role"];
            $row['menu_link'] = $menu_row["menu_link"];
            $row['user_ids'] = $menu_row["user_ids"];
            // $row['subMenu'] = $query->result_array();
            $subMenuArr = $sub_query->result_array();
            
            $sub_row = array();
            foreach ($subMenuArr as $sub_key => $sub_menu_row) {
                $this->db->order_by('menu_index_no');
                $sub_sub_query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $sub_menu_row['menu_id']));
                $sub_row[$sub_key]['menu_id'] = $sub_menu_row["menu_id"];
                $sub_row[$sub_key]['menu_title'] = $sub_menu_row["menu_title"];
                $sub_row[$sub_key]['menu_controller'] = $sub_menu_row["menu_controller"];
                $sub_row[$sub_key]['menu_icon'] = $sub_menu_row["menu_icon"];
                $sub_row[$sub_key]['menu_role'] = $sub_menu_row["menu_role"];
                $sub_row[$sub_key]['menu_link'] = $sub_menu_row["menu_link"];
                $sub_row[$sub_key]['user_ids'] = $sub_menu_row["user_ids"];
                $sub_row[$sub_key]['subSubMenu'] = $sub_sub_query->result_array();
            }
            $row['subMenu'] = $sub_row;
            $result[$key] = $row;
        }
    //    echo '<pre>';
    //    print_r($result);die;
        return $result;

    }

    /**
     * manage_menu
     * 
     * Get parent menu list
     * 
     * @param numeric
     * @return object
     */    
    function manage_menu($id = 0) {
        $this->db->where('menu_parent', 0);
        $query = $this->db->get(TBL_NAV_MENU);
        return $query->result();
    }
    
    function check_menu_permission($url, $user_role) {
        
        $where = "FIND_IN_SET('".$user_role."', menu_role)";  

        $this->db->where( $where ); 
        $this->db->where('menu_link', $url);
//        $this->db->like('menu_role', $user_role);
        $this->db->order_by('menu_index_no');
        $query = $this->db->get(TBL_NAV_MENU);
        return $query->num_rows();
    }


    /**
     * dropdown
     * 
     * Get user dropdown list
     * Ramiz Girach 03-03-2023
     * @param numeric
     * @return array
     */     
    function user_dropdown() {
        $this->db->select('id,first_name,last_name,Code')->where('role_id','5')->from(TBL_USERS);
        
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $user_array = $query->result_array();
        //$response_array = array("0" => "Select State");
        if (count($user_array) > 0) {
            foreach ($user_array as $user_arr) {
                $response_array[$user_arr["id"]] = ucwords($user_arr["first_name"]) . ' ' . ucwords($user_arr["last_name"]). ' (' . ucwords($user_arr["Code"]).')';
            }
        }
        return $response_array;
    }

    /**
     * displayMenu for Emp - Ramiz Girach - 06-03-2023
     * 
     * Get display menu list oder wise
     * 
     * @param numeric
     * @return array
     */    
    function displayEmpMenu($user_id) {
        $this->db->where('nv.menu_parent', 0);
        $this->db->where('enm.UserID', $user_id);
        $this->db->order_by('nv.menu_index_no');
        $this->db->join(TBL_NAV_MENU . ' nv', 'enm.menu_id=nv.menu_id', 'LEFT');
        $query = $this->db->get(TBL_EMP_NAV_MENU . ' enm');
        $result = $query->result_array();
        $i = 0;
        $all_result = array();
        // foreach ($result as $key => $menu_row) {
        //     // $query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $menu_row['menu_id']));
        //     $this->db->where('nv.menu_parent', $menu_row['menu_id']);
        //     $this->db->where('enm.UserID', $user_id);
        //     $this->db->order_by('nv.menu_index_no');
        //     $this->db->join(TBL_NAV_MENU . ' nv', 'enm.menu_id=nv.menu_id', 'LEFT');
        //     $query = $this->db->get(TBL_EMP_NAV_MENU . ' enm');
        //     // echo $this->db->last_query();die;
        //     $row['menu_id'] = $menu_row["menu_id"];
        //     $row['title_name'] = $menu_row["menu_title"];
        //     $row['menu_controller'] = $menu_row["menu_controller"];
        //     $row['menu_icon'] = $menu_row["menu_icon"];
        //     $row['menu_role'] = $menu_row["menu_role"];
        //     $row['menu_link'] = $menu_row["menu_link"];
        //     $row['user_ids'] = $menu_row["user_ids"];
        //     $row['subMenu'] = $query->result_array();
        //     $all_result[$i] = $row;
        //     $i++;
        // }
        foreach ($result as $key => $menu_row) {
            // $query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $menu_row['menu_id']));
            $this->db->where('nv.menu_parent', $menu_row['menu_id']);
            $this->db->where('enm.UserID', $user_id);
            $this->db->order_by('nv.menu_index_no');
            $this->db->join(TBL_NAV_MENU . ' nv', 'enm.menu_id=nv.menu_id', 'LEFT');
            $sub_query = $this->db->get(TBL_EMP_NAV_MENU . ' enm');
            // echo $this->db->last_query();die;
            $row['menu_id'] = $menu_row["menu_id"];
            $row['title_name'] = $menu_row["menu_title"];
            $row['menu_controller'] = $menu_row["menu_controller"];
            $row['menu_icon'] = $menu_row["menu_icon"];
            $row['menu_role'] = $menu_row["menu_role"];
            $row['menu_link'] = $menu_row["menu_link"];
            $row['user_ids'] = $menu_row["user_ids"];
            // $row['subMenu'] = $query->result_array();
            $subMenuArr = $sub_query->result_array();
            
            $sub_row = array();
            foreach ($subMenuArr as $sub_key => $sub_menu_row) {
                $this->db->order_by('menu_index_no');
                $sub_sub_query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $sub_menu_row['menu_id']));
                $sub_row[$sub_key]['menu_id'] = $sub_menu_row["menu_id"];
                $sub_row[$sub_key]['menu_title'] = $sub_menu_row["menu_title"];
                $sub_row[$sub_key]['menu_controller'] = $sub_menu_row["menu_controller"];
                $sub_row[$sub_key]['menu_icon'] = $sub_menu_row["menu_icon"];
                $sub_row[$sub_key]['menu_role'] = $sub_menu_row["menu_role"];
                $sub_row[$sub_key]['menu_link'] = $sub_menu_row["menu_link"];
                $sub_row[$sub_key]['user_ids'] = $sub_menu_row["user_ids"];
                $sub_row[$sub_key]['subSubMenu'] = $sub_sub_query->result_array();
            }
            $row['subMenu'] = $sub_row;

            $all_result[$i] = $row;
            $i++;
        }
//        echo '<pre>';
//        print_r($result);die;
        if($user_id == 1544){
            $this->db->where('nv.menu_parent', 0);
            $this->db->where('nv.menu_id', 68);
            $this->db->order_by('nv.menu_index_no');
            $query = $this->db->get(TBL_NAV_MENU . ' nv');
            $result = $query->result_array();
            foreach ($result as $key => $menu_row) {
                // $query = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $menu_row['menu_id']));
                $this->db->where('nv.menu_parent', $menu_row['menu_id']);
                $this->db->order_by('nv.menu_index_no');
                $query = $this->db->get(TBL_NAV_MENU . ' nv');
                // echo $this->db->last_query();die;
                $row['menu_id'] = $menu_row["menu_id"];
                $row['title_name'] = $menu_row["menu_title"];
                $row['menu_controller'] = $menu_row["menu_controller"];
                $row['menu_icon'] = $menu_row["menu_icon"];
                $row['menu_role'] = $menu_row["menu_role"];
                $row['menu_link'] = $menu_row["menu_link"];
                $row['user_ids'] = $menu_row["user_ids"];
                $row['subMenu'] = $query->result_array();
                $all_result[$i] = $row;
                $i++;
            }
        }
//        echo '<pre>';
//        print_r($result);die;
        return $all_result;

    }

}
