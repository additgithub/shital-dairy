<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //     function get_info($id, $table = "", $field = "id", $whereCon = "") {
    //         if (!empty($id) && !empty($table)) {
    //             $this->db->select('*');
    //             if (!empty($whereCon)) {
    //                 $this->db->where($whereCon);
    //             }
    //             $this->db->where($field, $id);
    //             $query = $this->db->get($table);
    // //            echo $this->db->last_query(); die;
    //             if ($query->num_rows() > 0) {
    //                 return $query->row();
    //             }
    //         }
    //         return NULL;
    //     }

    function get_info($id, $table = "", $field = "id", $whereCon = "", $all_field = '*', $join = false, $GroupBy = false, $OrderBy = false, $having = false, $limit = false, $offset = false, $db_config = 'db')
    {

        if (!empty($id) && !empty($table)) {
            $this->$db_config->select($all_field);
            if ($join) {
                if (isset($join['table'])) {
                    $this->$db_config->join($join['table'], $join['on'], $join['type']);
                } else {
                    foreach ($join as $j) {
                        $this->$db_config->join($j['table'], $j['on'], $j['type']);
                    }
                }
            }
            if ($OrderBy) {
                if (isset($OrderBy['field']))
                    $this->$db_config->order_by($OrderBy['field'], $OrderBy['order']);
                else
                    foreach ($OrderBy as $Order)
                        $this->$db_config->order_by($Order['field'], $Order['order']);
            }
            if (!empty($whereCon)) {
                $this->$db_config->where($whereCon);
            }
            if ($having)
                $this->$db_config->having($having);
            if ($GroupBy)
                $this->$db_config->group_by($GroupBy);
            if ($id && $field) {
                $this->$db_config->where($field, $id);
            }
            if ($limit)
                $this->db->limit($limit);
            if ($offset)
                $this->db->offset($offset);
            $query = $this->$db_config->get($table);
            // echo $this->$db_config->last_query(); die;
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return NULL;
    }

    function get_info_with_sub_query($id, $table = "", $field = "id", $whereCon = "", $all_field = '*', $sub_field = '*', $join = false, $GroupBy = false, $OrderBy = false, $having = false, $limit = false, $offset = false, $db_config = 'db')
    {

        if (!empty($id) && !empty($table)) {
            $this->$db_config->select($all_field);
            if ($join) {
                if (isset($join['table'])) {
                    $this->$db_config->join($join['table'], $join['on'], $join['type']);
                } else {
                    foreach ($join as $j) {
                        $this->$db_config->join($j['table'], $j['on'], $j['type']);
                    }
                }
            }
            if ($OrderBy) {
                if (isset($OrderBy['field']))
                    $this->$db_config->order_by($OrderBy['field'], $OrderBy['order']);
                else
                    foreach ($OrderBy as $Order)
                        $this->$db_config->order_by($Order['field'], $Order['order']);
            }
            if (!empty($whereCon)) {
                $this->$db_config->where($whereCon);
            }
            if ($having)
                $this->$db_config->having($having);
            if ($GroupBy)
                $this->$db_config->group_by($GroupBy);
            if ($id && $field) {
                $this->$db_config->where($field, $id);
            }
            if ($limit)
                $this->db->limit($limit);
            if ($offset)
                $this->db->offset($offset);
            $query = $this->$db_config->get($table);
            // echo $this->$db_config->last_query(); die;
            if ($sub_field) {
                $sub_query_from = '(' . $this->$db_config->last_query() . ') as x';
                $this->db->select($sub_field);
                $this->db->from($sub_query_from);
                $query = $this->db->get();
            }
            if ($query->num_rows() > 0) {
                return $query->row();
            }
        }
        return NULL;
    }

    function add_info($table = "", $data)
    {
        if (!empty($data) && !empty($table)) {
            $this->db->insert($table, $data);
            //  echo $this->db->last_query();die();
            return $this->db->insert_id();
        }
        return NULL;
    }

    function add_batch_info($table = "", $data)
    {
        if (!empty($data) && !empty($table)) {
            print_r($this->db->insert_batch($table, $data));
            die;
            //  echo $this->db->last_query();die();
            return true;
        }
        return NULL;
    }


    function update_info($id, $table = "", $data, $field = "id", $whereCon = "")
    {
        if (!empty($id) && !empty($table)) {
            if (!empty($whereCon)) {
                $this->db->where($whereCon);
            }
            $this->db->where($field, $id);
            return $this->db->update($table, $data);
        }
        return NULL;
    }

    function check_is_exists($table = "", $name, $field_name = "", $id = 0, $field = "id", $spec_char = true, $where = '')
    {
        // if ($spec_char) {

        //     $name = strtolower(preg_replace('#[^\w()/.%\-&]#', " ", $name));
        // }
        $this->db->select($field_name);
        if ($id > 0)
            $this->db->where($field . ' != ', $id);

        if ($where != '') {
            $this->db->where($where);
        }
        $this->db->where($field_name, $name);
        $query = $this->db->get($table);
        // echo $this->db->last_query();die;
        return $query->num_rows();
    }

    function get_list($table = "", $ValueField = 'id', $NameField = 'name', $whereCon = '', $ExtraField = false, $StaticVal = false, $join = false, $OrderBy = false)
    {
        if (!empty($table)) {
            $this->db->select("$ValueField, $NameField , $ExtraField,$StaticVal");
            if ($join) {
                if (isset($join['table'])) {
                    $this->db->join($join['table'], $join['on'], $join['type']);
                } else {
                    foreach ($join as $j) {
                        $this->db->join($j['table'], $j['on'], $j['type']);
                    }
                }
            }
            if ($OrderBy) {
                if (isset($OrderBy['field']))
                    $this->db->order_by($OrderBy['field'], $OrderBy['order']);
                else
                    foreach ($OrderBy as $Order)
                        $this->db->order_by($Order['field'], $Order['order']);
            }
            $data_array = array();
            if (!empty($whereCon)) {
                $this->db->where($whereCon);
            }
            $query = $this->db->get($table);
            //            echo $this->db->last_query();die;
            if ($query->num_rows() > 0) {
                $NValueField = str_replace('.', '', trim(substr($ValueField, strpos($ValueField, '.'))));
                $NExtraField = str_replace('.', '', trim(substr($ExtraField, strpos($ExtraField, '.'))));
                $NNameField = str_replace('.', '', trim(substr($NameField, strpos($NameField, '.'))));
                foreach ($query->result() as $row) {
                    if ($ExtraField && $StaticVal) :
                        $data_array[$row->$NValueField] = $row->$NNameField . ' ' . $row->$NExtraField . ' (' . $row->$StaticVal . ')';
                    elseif ($ExtraField) :
                        $data_array[$row->$NValueField] = $row->$NNameField . ' ' . $row->$NExtraField . '';
                    elseif ($StaticVal) :
                        $data_array[$row->$NValueField] = $row->$NNameField . ' (' . $StaticVal . ')';
                    else :
                        $data_array[$row->$NValueField] = $row->$NNameField;
                    endif;
                }
            }
            return $data_array;
        }
        return NULL;
    }

    function get_all_info($id = '', $table = "", $field = "id", $whereCon = "", $all_field = '*', $count = false, $join = false, $GroupBy = false, $OrderBy = false, $limit = false, $offset = false, $having = false, $QueryGet = false)
    {

        if (!empty($table)) {
            $this->db->select($all_field);
            if (!empty($whereCon))
                $this->db->where($whereCon);
            if ($join && count($join) > 0) {
                if (isset($join['table']))
                    $this->db->join($join['table'], $join['on'], $join['type']);
                else
                    foreach ($join as $j)
                        $this->db->join($j['table'], $j['on'], $j['type']);
            }
            if ($OrderBy) {
                if (isset($OrderBy['field']))
                    $this->db->order_by($OrderBy['field'], $OrderBy['order']);
                else
                    foreach ($OrderBy as $Order)
                        $this->db->order_by($Order['field'], $Order['order']);
            }
            if ($limit)
                $this->db->limit($limit);
            if ($offset)
                $this->db->offset($offset);
            if ($having)
                $this->db->having($having);
            if ($id != "")
                $this->db->where($field, $id);
            if ($GroupBy)
                $this->db->group_by($GroupBy);
            $query = $this->db->get($table);
            if ($count)
                if ($QueryGet)
                    return array($this->db->last_query(), $query->num_rows());
                else
                    return $query->num_rows();
            if ($query->num_rows() > 0) {
                if ($QueryGet) {
                    return array($this->db->last_query(), $query->result());
                } else {
                    return $query->result();
                }
            }
        }
        if ($QueryGet) {
            return array($this->db->last_query(), array());
        } else {
            return array();
        }
    }

    function commission_report_query($id)
    {
        //        $final_query = "select e.EventName,c.ClientCode,c.FullName, tc.CarNo,tc.SoldAmount,vt.VehicalType,cc.Commission from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID left join tbl_client as c on e.ClientID = c.ClientID where e.EndTime <= CURRENT_DATE AND e.AuctionID = ".$id;
        $final_query = "select e.EventName,c.ClientCode,c.FullName, tc.CarNo,tc.SoldAmount,vt.VehicalType,(tc.SoldAmount * cc.Commission/100) as TotComm from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID left join tbl_client as c on e.ClientID = c.ClientID where e.AuctionID = " . $id;
        //        echo $final_query; die;
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }
    function commission_invoice_query($id)
    {
        //        $final_query = "select e.EventName,c.ClientCode,c.FullName, tc.CarNo,tc.SoldAmount,vt.VehicalType,cc.Commission from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID left join tbl_client as c on e.ClientID = c.ClientID where e.EndTime <= CURRENT_DATE AND e.AuctionID = ".$id;
        $final_query = "select e.EventName,c.ClientCode,c.FullName, tc.CarNo,tc.SoldAmount,vt.VehicalType,(tc.SoldAmount * cc.Commission/100) as TotComm from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID left join tbl_client as c on e.ClientID = c.ClientID where tc.SoldAmount !=0 AND e.ClientID = " . $id;
        //        echo $final_query; die;
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }
    //    function commission_report_list_query()
    //    {
    ////        $final_query = "select e.EventName,c.ClientCode,c.FullName, tc.CarNo,tc.SoldAmount,vt.VehicalType,cc.Commission from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID left join tbl_client as c on e.ClientID = c.ClientID where e.EndTime <= CURRENT_DATE AND e.AuctionID = ".$id;
    //        $final_query = "select e.EventName,c.ClientCode,c.FullName, x.TotComm from tbl_event as e inner join ( select e.AuctionID,SUM(tc.SoldAmount * cc.Commission/100) as TotComm from tbl_event as e left join tbl_t_cars as tc on e.AuctionID = tc.EventID left join tbl_vehicle_type as vt on tc.VehicleTypeID=vt.VehicalTypeID left join tbl_client_commision as cc on e.ClientID = cc.ClientID and tc.VehicleTypeID = cc.VehicleTypeID group by e.AuctionID ) as x on e.AuctionID=x.AuctionID left join tbl_client as c on e.ClientID = c.ClientID where e.EndTime >= CURRENT_DATE";
    ////        echo $final_query; die;
    //        $query = $this->db->query($final_query);
    //        if ($query->num_rows() > 0) {
    //            return $query->result();
    //        }
    //        return array();
    //    }

    function get_max_bid_total_by_event_id($event_id)
    {
        $final_query = "SELECT CASE WHEN SUM(MAXBid) IS NULL THEN 0 ELSE SUM(MAXBid) END as FinalBidPriceTotal FROM(SELECT MAX(BidAmount) as MAXBid FROM tbl_bid WHERE EventID=" . $event_id . " GROUP BY CarID) t";
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return array();
    }
    function get_max_bid_total_by_event_ids($event_id)
    {
        $final_query = "SELECT CASE WHEN SUM(MAXBid) IS NULL THEN 0 ELSE SUM(MAXBid) END as FinalBidPriceTotal FROM(SELECT MAX(BidAmount) as MAXBid FROM tbl_bid WHERE EventID IN (" . $event_id . ") GROUP BY CarID) t";
        // echo $final_query;die;
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return array();
    }
    function get_max_bid_total_by_car_ids($event_id)
    {
        $final_query = "SELECT CASE WHEN SUM(MAXBid) IS NULL THEN 0 ELSE SUM(MAXBid) END as FinalBidPriceTotal FROM(SELECT MAX(BidAmount) as MAXBid FROM tbl_bid WHERE CarID =" . $event_id . " GROUP BY CarID) t";
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return array();
    }
    function get_max_bid_by_car_ids_with_user_id($event_id)
    {
        // $final_query = "SELECT MAX(BidAmount),UserID  FROM `tbl_bid` WHERE `EventID` IN( ".$event_id.") GROUP By CarID";
        $final_query = "SELECT MAX(bid.BidAmount),bid.CarID,(SELECT UserID FROM tbl_bid as b WHERE bid.CarID=b.CarID AND b.EventID=bid.EventID ORDER BY BidAmount DESC limit 1) as UserID  FROM `tbl_bid` as bid WHERE bid.`EventID` IN( " . $event_id . ")   GROUP By bid.CarID";
        $query = $this->db->query($final_query);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return array();
    }

    function check_is_vehicle_exists($table = "", $name, $field_name = "", $id = 0, $field = "id", $where = '')
    {
        // if ($spec_char) {

        //     $name = strtolower(preg_replace('#[^\w()/.%\-&]#', " ", $name));
        // }
        $this->db->select($field_name);
        if ($id > 0)
            $this->db->where($field . ' != ', $id);

        if ($where != '') {
            $this->db->where($where);
        }
        $date = date('Y-m-d H:i:s');
        $this->db->where('(e.EndTime >= "' . $date . '" OR e.CutOffTime >= "' . $date . '")');
        $this->db->where($field_name, $name);
        $this->db->join(TBL_EVENT . ' e', 'car.EventID=e.AuctionID', 'LEFT');
        $query = $this->db->get($table . ' car');
        // echo $this->db->last_query();die;
        return $query->num_rows();
    }

    //Nav Menu Parent and sub - 09-02-2023
    function get_nav_menu_list()
    {
        $this->db->select("menu_id, menu_title");
        $this->db->where('menu_parent', 0);
        $data_array = array();
        $query = $this->db->get(TBL_NAV_MENU);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            foreach ($result as $key => $s_data) {
                $this->db->select("menu_id, menu_title");
                $this->db->order_by('menu_id');
                $query_sub = $this->db->get_where(TBL_NAV_MENU, array('menu_parent' => $s_data['menu_id']));
                $row['menu_id'] = $s_data["menu_id"];
                $row['menu_title'] = $s_data["menu_title"];
                if ($query_sub->num_rows() > 0) {
                    $row['SubMenu'] = $query_sub->result_array();
                } else {
                    $row['SubMenu'] = array();
                    $row['SubMenu'][0] = array("menu_id" => $s_data["menu_id"], "menu_title" => $s_data["menu_title"]);
                }
                $result[$key] = $row;
            }
            return $result;
        }
        return array();
    }

    function update_free_user_membership($newEndDate)
    {
        $final_query = "UPDATE tbl_user_membership as um SET um.EndDate='" . $newEndDate . "' WHERE um.UserID NOT IN (SELECT UserID FROM tbl_transaction_log as tl WHERE tl.`PlanID` > 0 AND PaymentStatus = 'Paid')
        ";
        $query = $this->db->query($final_query);

        return true;
    }
    function update_paid_user_membership($days)
    {

        $final_query = "UPDATE tbl_user_membership as tum INNER JOIN tbl_transaction_log as tl ON tum.UserID=tl.UserID AND tl.`PlanID` > 0 AND tl.PaymentStatus = 'Paid' SET tum.EndDate=DATE_ADD(EndDate, INTERVAL " . $days . " DAY)";

        // $final_query = "UPDATE tbl_user_membership as um JOIN (SELECT
        // um.MembershipID,tl.TransLogID, tl.UserID, u.Code, sp.Months, um.StartDate, um.EndDate,
        //     (sp.Months * 30) as Days,
        //     ADDDATE(
        //         '".$newEndDate."',
        //         INTERVAL (sp.Months * 30) DAY
        //     ) NewUpdateEndDate

        // FROM
        //     tbl_transaction_log AS tl
        // LEFT JOIN tbl_subscription_plan AS sp
        // ON
        //     tl.PlanID = sp.PlanID
        //     INNER JOIN tbl_user_membership AS um
        // ON
        //     tl.UserID = um.UserID
        // LEFT JOIN users AS u
        // ON
        //     tl.UserID = u.id
        // WHERE
        //     tl.`PlanID` > 0 AND PaymentStatus = 'Paid') as  x ON um.MembershipID=x.MembershipID SET um.EndDate=x.NewUpdateEndDate;";
        $query = $this->db->query($final_query);

        return true;
    }
    function update_paid_user_membership_new()
    {

        $final_query = "UPDATE tbl_user_membership as um JOIN (SELECT
        um.MembershipID,tl.TransLogID, tl.UserID, u.Code, sp.Months, um.StartDate, um.EndDate,
            (sp.Months * 30) as Days,
            ADDDATE(
                '" . date('Y-m-d') . "',
                INTERVAL (sp.Months * 30) DAY
            ) NewUpdateEndDate
            
        FROM
            tbl_transaction_log AS tl
        LEFT JOIN tbl_subscription_plan AS sp
        ON
            tl.PlanID = sp.PlanID
            INNER JOIN tbl_user_membership AS um
        ON
            tl.UserID = um.UserID
        LEFT JOIN users AS u
        ON
            tl.UserID = u.id
        WHERE
            tl.`PlanID` > 0 AND PaymentStatus = 'Paid') as  x ON um.MembershipID=x.MembershipID SET um.EndDate=x.NewUpdateEndDate;";
        $query = $this->db->query($final_query);

        return true;
    }
    public function get_last_auto_id($table, $primary_key)
    {
        $this->db->select($primary_key);
        $this->db->order_by($primary_key, 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return (int) $query->row()->$primary_key;
        }
        return 0;
    }

    public function delete_info($table, $where_key, $where_val)
    {
        $this->db->where($where_key, $where_val);
        return $this->db->delete($table);
    }

    public function _oldget_itemwise_order_summary()
    {
        $where = '';
        if (!empty($start_date) && !empty($end_date)) {
            $where = "WHERE hdr.order_date BETWEEN '{$start_date}' AND '{$end_date}'";
        }
        $sql = "
        SELECT
            i.item_name,
            SUM(dtl.qty) AS qty,
            SUM(dtl.qty * i.selling_price) AS totalamt
        FROM
            tbl_order_dtl AS dtl
        INNER JOIN tbl_item AS i ON i.item_id = dtl.item_id
        GROUP BY
            i.item_name

        UNION ALL

        SELECT
            'Total' AS item_name,
           NULL AS qty,
            SUM(dtl.qty * i.selling_price) AS totalamt
        FROM
            tbl_order_dtl AS dtl
        INNER JOIN tbl_item AS i ON i.item_id = dtl.item_id
    ";

        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_itemwise_order_summary($start_date = null, $end_date = null)
    {
        $where = "";
        if (!empty($start_date) && !empty($end_date)) {
            $start = $this->db->escape($start_date);
            $end = $this->db->escape($end_date);
            $where = "WHERE h.order_date BETWEEN $start AND $end";
        }

        $sql = "
        SELECT
            i.item_name AS item_name,
            SUM(d.qty) AS qty_pkt,
            FORMAT(SUM(d.qty * i.factor), 3) AS qty_kg,
            SUM(d.qty * i.selling_price) AS totalamt
        FROM
            tbl_order_dtl d
        JOIN tbl_order_hdr h ON h.order_hdr_id = d.order_hdr_id
        JOIN tbl_item i ON i.item_id = d.item_id
        $where
        GROUP BY i.item_name

        UNION ALL

        SELECT
            'Total' AS item_name,
            SUM(d.qty) AS qty_pkt,
            FORMAT(SUM(d.qty * i.factor),3) AS qty_kg,
            SUM(d.qty * i.selling_price) AS totalamt
        FROM
            tbl_order_dtl d
        JOIN tbl_order_hdr h ON h.order_hdr_id = d.order_hdr_id
        JOIN tbl_item i ON i.item_id = d.item_id
        $where
    ";

        return $this->db->query($sql)->result();
    }

    public function get_stock_summary($start_date = null, $end_date = null)
{
    $where = "";
    if (!empty($start_date) && !empty($end_date)) {
        $start = $this->db->escape($start_date);
        $end = $this->db->escape($end_date);
        $where = "WHERE h.order_date BETWEEN $start AND $end";
    }

    $sql = "
    SELECT
        CONCAT(i.item_name, ' (', i.size, ')') AS item_name,
        SUM(d.qty) AS qty_pkt,
        FORMAT(SUM(d.qty * i.factor), 3) AS qty_kg,
        SUM(d.qty * i.selling_price) AS totalamt,
        s.total_purchase_qty_pkt,
        s.total_sells_qty_pkt,
        (s.total_purchase_qty_pkt - s.total_sells_qty_pkt) AS stock_pkt,
        FORMAT(s.total_purchase_qty_kg, 3) AS total_purchase_qty_kg,
        FORMAT(s.total_sells_qty_kg, 3) AS total_sells_qty_kg,
        FORMAT(s.total_purchase_qty_kg - s.total_sells_qty_kg, 3) AS stock_kg
    FROM
        tbl_order_dtl d
    JOIN tbl_order_hdr h ON h.order_hdr_id = d.order_hdr_id
    JOIN tbl_item i ON i.item_id = d.item_id
    LEFT JOIN tbl_item_stock s ON s.item_id = i.item_id
    $where
    GROUP BY i.item_id

    UNION ALL

    SELECT
        'Total' AS item_name,
        SUM(d.qty) AS qty_pkt,
        FORMAT(SUM(d.qty * i.factor), 3) AS qty_kg,
        SUM(d.qty * i.selling_price) AS totalamt,
        NULL, NULL, NULL,
        NULL, NULL, NULL
    FROM
        tbl_order_dtl d
    JOIN tbl_order_hdr h ON h.order_hdr_id = d.order_hdr_id
    JOIN tbl_item i ON i.item_id = d.item_id
    $where
    ";

    return $this->db->query($sql)->result();
}
    public function current_balance_summary($start_date = null, $end_date = null)
{
    $where = "";
    if (!empty($start_date) && !empty($end_date)) {
        $start = $this->db->escape($start_date);
        $end = $this->db->escape($end_date);
        $where = "WHERE h.order_date BETWEEN $start AND $end";
    }

    $sql = "
    SELECT
    i.item_name,
    IFNULL(pqry.purchaseqty,0) as purchaseqty,
    IFNULL(dqty.deliverdqty,0) as deleiveredqty,
    IFNULL(pqry.purchaseqty, 0) - IFNULL(dqty.deliverdqty, 0) AS currentbalanceform,
    i.factor,
    IFNULL(pqry.purchaseqty*i.factor, 0) as purchaseqtyKg,
    IFNULL(dqty.deliverdqty*i.factor, 0) as deleiveredqtykg,
    FORMAT(
        (IFNULL(pqry.purchaseqty, 0) - IFNULL(dqty.deliverdqty, 0)) * i.factor,
        3
    ) AS qty_kg
FROM
    tbl_item AS i
LEFT JOIN (
    SELECT
        item_id,
        SUM(qty) AS purchaseqty
    FROM
        tbl_purchase_dtl
    GROUP BY
        item_id
) AS pqry ON i.item_id = pqry.item_id
LEFT JOIN (
    SELECT
        od.item_id,
        SUM(od.qty) AS deliverdqty
    FROM
        tbl_order_dtl od
    WHERE
        od.order_hdr_id IN (
            SELECT order_hdr_id
            FROM tbl_order_hdr
            WHERE id_delivered = 1
        )
    GROUP BY
        od.item_id
) AS dqty ON i.item_id = dqty.item_id;
    ";

    return $this->db->query($sql)->result();
}
public function get_last_order_date($table, $primary_key,$order_date)
    {
        $this->db->select($order_date);
        $this->db->order_by($primary_key, 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            return $query->row()->$order_date;
        }
        return 0;
    }

}
