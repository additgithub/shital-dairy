<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remove_records extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function remove_data($id, $field, $table) {
        $this->db->where($field, $id);
        return $this->db->delete($table);
    }

    function remove_data_with_where($id, $field, $table,$where) {
        $this->db->where($field, $id);
        if($where != ''){
            $this->db->where($where);            
        }
        return $this->db->delete($table);
    }

}
