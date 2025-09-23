<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Remove extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else if ($this->tank_auth->get_user_role_id() != '1' && $this->tank_auth->get_user_id() != '1544') {
            redirect('/');
        }
        $this->load->model('Remove_records');
        if ($this->input->post('pass') && !empty($this->input->post('pass'))):
            if (!$this->check_pass($this->input->post('pass'))):
                $response = array('status' => 'error', 'message' => 'Password not matched.');
                $this->response($response);
            endif;
        else:
            $response = array('status' => 'error', 'message' => 'Password enter valid password.');
            $this->response($response);
        endif;
    }

    function check_pass($password) {
        $user = $this->users->get_user_by_username($this->tank_auth->get_username());
        if (count((array) $user) > 0):
            $hasher = new PasswordHash(
                    $this->config->item('phpass_hash_strength', 'tank_auth'), $this->config->item('phpass_hash_portable', 'tank_auth'));
            if ($hasher->CheckPassword($password, $user->password)):
                return TRUE;
            endif;
        endif;

        return FALSE;
    }

    function category($pid = 0, $where = 'CategoryID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CATEGORY);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function category_main($pid = 0, $where = 'CategoryID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CATEGORY);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function category_parent($pid = 0, $where = 'CategoryID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CATEGORY);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function cms($pid = 0, $where = 'CmsID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CMS);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function client($pid = 0, $where = 'ClientID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CLIENT);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function user($pid = 0, $where = 'id') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USERS);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USER_WALLET);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USER_INFOMATION);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USER_MEMBERSHIP);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USER_PAN_VERIFY_DATA);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function dealer($pid = 0, $where = 'id') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USERS);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function contact($pid = 0, $where = 'ContactID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_CONTACT_US);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function car($pid = 0, $where = 'CarID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $car_details = $this->Common->get_info($id,TBL_T_CARS,'CarID');
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_T_CARS);
            $this->Remove_records->remove_data($id, $where, TBL_T_CARIMAGES);
            $car_count = $this->Common->get_all_info($car_details->EventID,TBL_T_CARS,'EventID',false,'*',true);
            $update_car_count = array(
                "NumberOfVehical" => $car_count
            );
            $this->Common->update_info($car_details->EventID,TBL_EVENT,$update_car_count,'AuctionID');
            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function make($pid = 0, $where = 'MakeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_MAKE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function model($pid = 0, $where = 'ModelID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_MODEL);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function body_type($pid = 0, $where = 'BodyTypeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_BODYTYPE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function fuel_type($pid = 0, $where = 'FuelTypeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_FUELTYPE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function owner($pid = 0, $where = 'OwnerID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_OWNER);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function role($pid = 0, $where = 'RoleID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_ROLE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function price_range($pid = 0, $where = 'PriceRangeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_PRICERANGE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function car_age($pid = 0, $where = 'CarAgeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_CARAGE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function km_range($pid = 0, $where = 'KMRangeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_KMRANGE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function state_registration($pid = 0, $where = 'StateRegID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_STATEREG);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function transmission_type($pid = 0, $where = 'TransmissionID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_TRANSMISSION);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function vehicle_type($pid = 0, $where = 'VehicalTypeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_VEHICLE_TYPE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function auction($pid = 0, $where = 'AuctionID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_EVENT);
            $where = 'EventID';
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_T_CARS);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function home_feature($pid = 0, $where = 'FeatureID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_HOME_FEATURE);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function banner($pid = 0, $where = 'BannerID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_M_BANNER);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function blog($pid = 0, $where = 'BlogID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_BLOG);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function newsletter($pid = 0, $where = 'SubscriptionID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_NEWSLETTER);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function feedback($pid = 0, $where = 'FeedbackID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_FEEDBACK);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function faq($pid = 0, $where = 'FaqID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_TFAQ);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function city($pid = 0, $where = 'CityID  ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_CITY);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    
    function car_loan_request($pid = 0, $where = 'CarLoanID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_CARLOAN);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function sales_representative($pid = 0, $where = 'SRID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_SALES_REPRESENTATIVE);
//            $data_remove = $this->Remove_records->remove_data($id, 'id', TBL_USERS);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function customer_requirement($pid = 0, $where = 'CustomReqID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_APP_CUSTOMERRREQUIREMENT);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function car_inquiry($pid = 0, $where = 'CarInquiryID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_T_CARINQUIRY);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function rsd($pid = 0, $where = 'RsdID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_RSD);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
    function withdraw_req($pid = 0, $where = 'WindrawID ') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_USER_WITHDRAW_REQ);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function yard($pid = 0, $where = 'YardID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $this->Remove_records->remove_data($id, $where, TBL_YARD_LOCATION);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_YARD);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function delete_team($pid = 0, $where = 'YardTeamMemberID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $YardTeamMember = $this->Common->get_info($id,TBL_YARD_TEAM_MEMBER,$where,'',false,false,'UserID');

            $this->Remove_records->remove_data($YardTeamMember->UserID, 'id', TBL_USERS);
            $this->Remove_records->remove_data($YardTeamMember->UserID, 'UserID', TBL_USER_PAN_VERIFY_DATA);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_YARD_TEAM_MEMBER);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function annual_report($pid = 0, $where = 'ReportID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $pdf = $this->Common->get_info($id,TBL_ANNUAL_REPORT,$where,'','PDFURL');
            if(file_exists(UPLOAD_DIR.ANNUAL_REPORT_DIR.$pdf->PDFURL)){
                unlink(UPLOAD_DIR.ANNUAL_REPORT_DIR.$pdf->PDFURL);
            }
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_ANNUAL_REPORT);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }

    function employee($pid = 0, $where = 'EmployeeID') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            $Employee = $this->Common->get_info($id,TBL_EMPLOYEES,$where,'',false,false,'UserID');

            $this->Remove_records->remove_data($Employee->UserID, 'id', TBL_USERS);
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_EMPLOYEES);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
     function orders($pid = 0, $where = 'order_hdr_id') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):
            
            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_ORDER_HDR);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }
     function wadi($pid = 0, $where = 'wadi_id') {
        $id = ($pid > 0) ? $pid : (($this->input->post('id')) ? $this->input->post('id') : 0);
        if ($id > 0):

            $data_remove = $this->Remove_records->remove_data($id, $where, TBL_WADI);

            if ($pid > 0):
                return ($data_remove) ? TRUE : FALSE;
            else:
                $response = ($data_remove) ? array('status' => 'ok', 'message' => 'Details removed successfully.!') : array('status' => 'ok', 'message' => 'Details not removed successfully.!');
                $this->response($response);
            endif;

        endif;
    }


    function response($response) {
        echo json_encode($response);
        die;
    }

}
