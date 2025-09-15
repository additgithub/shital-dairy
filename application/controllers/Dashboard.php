<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //        echo $this->tank_auth->get_user_role_id();die;
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
    }

    function index()
    {

        $data = array();
        $data['page_title'] = "Dashboard";
        $data['main_content'] = 'dashboard/dashboard';
        $where = '';
        if ($this->tank_auth->get_user_role_id() == 2) {
            $where = 'created_by = ' . $this->tank_auth->get_user_id();
        }
        $data["total_items"] = $this->Common->get_all_info(1, TBL_M_ITEMS . ' e', 1, $where, 'item_id', true);
        $data["total_orders"] = $this->Common->get_all_info(1, TBL_ORDER_HDR . ' e', 1, $where, 'order_hdr_id', true);
        $this->load->view('main_content', $data);
    }

   
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */