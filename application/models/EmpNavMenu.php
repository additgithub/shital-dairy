<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EmpNavMenu extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    /**
     * displayMenu
     * 
     * Get display menu list oder wise
     * 
     * @param numeric
     * @return array
     */    
    function displayEmpMenu() {
        $this->db->where('UserID', $this->tank_auth->get_user_id());
        $this->db->where('menu_parent', 0);
        $this->db->order_by('menu_index_no');
        $query = $this->db->get(TBL_EMP_NAV_MENU);
        $result = $query->result_array();
        // print_r($result);die;
        foreach ($result as $key => $menu_row) {
            $this->db->order_by('menu_index_no');
            // $this->db->where('UserID', $this->tank_auth->get_user_id());
            $query = $this->db->get_where(TBL_EMP_NAV_MENU, array('menu_parent' => $menu_row['menu_id'],'UserID' => $menu_row['UserID']));

            $row['menu_id'] = $menu_row["menu_id"];
            $row['title_name'] = $menu_row["menu_title"];
            $row['menu_controller'] = $menu_row["menu_controller"];
            $row['menu_icon'] = $menu_row["menu_icon"];
            $row['menu_role'] = $menu_row["menu_role"];
            $row['menu_link'] = $menu_row["menu_link"];
            $row['subMenu'] = $query->result_array();
            $result[$key] = $row;
        }
       
        return $result;
    }

    function get_nav_menu_list($UserID)
    {
        $this->db->select("GROUP_CONCAT(menu_id) as selected");
        // $this->db->where('menu_parent', 0);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('menu_id');
        $data_array = array();
        $query = $this->db->get(TBL_EMP_NAV_MENU);
        $result = $query->row();
        return $result;
    }

}
