<?php

function success_elements()
{
    return array('<div class="alert alert-block alert-success fade in"><button type="button" class="close close-sm" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>');
}

function error_elements()
{
    return array('<div class="alert alert-block alert-danger fade in"><button type="button" class="close close-sm" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>');
}

function add_edit_form()
{
    echo '<div id="add_edit_form" style="display: none;"><div id="display_update_form"></div></div>';
}

function randomNumber()
{
    return rand(111, 9999);
}

function get_otp($DefaultOTP)
{
    $OTP = rand(111111, 999999);
    if ($OTP == $DefaultOTP) {
        get_otp($DefaultOTP);
    }
    return $OTP;
}

function get_random_code($length)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    if ($length > 0) {
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    }
    return $randomString;
}

function dealer_source($id)
{
    $ci = &get_instance();
    $join = array(
        array('table' => TBL_YARD_TEAM_MEMBER . ' ytm', 'on' => 'ytm.UserID=usr.SRID', 'type' => 'LEFT'),
        array('table' => TBL_YARD . ' yd', 'on' => 'yd.YardID=ytm.YardID', 'type' => 'LEFT'),
        // array('table'=>TBL_USERS.' srusr','on'=>'srusr.id=usr.SRID','type'=>'LEFT'),
    );
    $data = $ci->Common->get_info($id, TBL_USERS . ' usr', 'id', '', 'StockyardName,FirstName,LastName,first_name,last_name,usr.Code,usr.SRID', $join);
    // print_r($ci->db->last_query());die;
    if ($data->SRID == 0) {
        $str = 'Direct';
    } elseif (!empty($data->StockyardName)) {
        $str = $data->FirstName . ' ' . $data->LastName . '<br />' . $data->StockyardName . '(' . $data->Code . ')';
    } else {
        $sr = $ci->Common->get_info($data->SRID, TBL_USERS, 'id', '', 'first_name,last_name,Code');
        $str = $sr->first_name . ' ' . $sr->last_name . '(' . $sr->Code . ')';
    }
    return $str;
}

//function order_invoice_number($invoice) {
//    return substr($invoice, 0, 16);
//}
//
//function order_edit($order_id, $invoice) {
//    $url = base_url() . 'order/view-details/' . $order_id;
//    $invoice_url = BASE_URL . 'uploads/' . ORDER_DIR . $invoice;
//    $html = '<div class="tooltip-top">
//                <a data-original-title="View Order" data-placement="top" data-toggle="tooltip" href="' . $url . '" class="btn btn-xs btn-default btn-equal btn-mini" target="_blank" data-control="{$this->controllers}"><i class="fa fa-eye"></i></a>';
//
//
//    if (file_get_contents('../uploads/' . ORDER_DIR . $invoice)) {
//        $html .= '  <a data-original-title="Download Invoice" data-placement="top" data-toggle="tooltip" class="btn btn-xs btn-default btn-equal btn-mini" href="' . $invoice_url . '" target="_blank"><i class="fa fa-download"></i> </a>';
//    }
//    $html .= '</div>';
//    return $html;
//}
//
//function order_order_total($total) {
//    return '₹' . $total;
//}

function upload_zip_file($post_file_name, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_ZIP_DIR;

    if (!file_exists(UPLOAD_ZIP_DIR)) : mkdir(UPLOAD_ZIP_DIR, 0777, TRUE);
    endif;
    // if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    // endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'zip';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    $config['encrypt_name'] = FALSE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    // $ci->load->library('image_lib');
    // $config['image_library'] = 'GD2';
    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {

        if ($ci->upload->do_upload($post_file_name)) {
            $upload_data = $ci->upload->data();
            if (!empty($old_file_name)) {

                $file_path = UPLOAD_ZIP_DIR . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}
function upload_file($post_file_name, $inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'jpeg|jpg|png|mp4|webp|pdf';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    //    $config['encrypt_name'] = TRUE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    $ci->load->library('image_lib');
    $config['image_library'] = 'GD2';
    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {

        if ($ci->upload->do_upload($post_file_name)) {
            $upload_data = $ci->upload->data();
            $config['source_image'] = $upload_data['full_path'];
            $config['wm_overlay_path'] = FCPATH . 'assets/img/logo2.jpg';
            $config['wm_type'] = 'overlay';
            $config['width'] = 10;
            $config['height'] = 10;
            $config['padding'] = 30;
            $config['wm_opacity'] = 10;
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'right';
            $config['wm_vrt_offset'] = 20;
            $config['orig_height'] = 20;
            $config['orig_width'] = 20;
            $watermark_array = array(
                array('horizontal' => 'center', 'vertical' => 'middle'),
                array('horizontal' => 'left', 'vertical' => 'top'),
                array('horizontal' => 'right', 'vertical' => 'top'),
                array('horizontal' => 'left', 'vertical' => 'bottom'),
                array('horizontal' => 'right', 'vertical' => 'bottom')
            );


            // ADD YOUR WATERMARKS

            foreach ($watermark_array as $row) {
                $config['wm_vrt_alignment'] = $row['vertical'];
                $config['wm_hor_alignment'] = $row['horizontal'];

                $ci->image_lib->initialize($config);
                $ci->image_lib->watermark();
            }
            $ci->image_lib->initialize($config);
            if (!empty($old_file_name)) {

                $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}
function upload_default_image_file($post_file_name, $inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'jpeg|jpg|png|mp4|webp|pdf';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    //    $config['encrypt_name'] = TRUE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    $ci->load->library('image_lib');
    $config['image_library'] = 'GD2';
    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {

        if ($ci->upload->do_upload($post_file_name)) {

            $upload_data = $ci->upload->data();
            // echo $upload_data['full_path'];die;
            // Image Webp Creation
            $quality = 100;
            $dir = $upload_path;
            $name = pathinfo($upload_data['full_path'], PATHINFO_FILENAME);
            $file_name = $name . '_' . rand(111, 999) . '.webp';
            $destination = $dir . $file_name;
            $info = getimagesize($upload_data['full_path']);
            $isAlpha = false;
            if ($info['mime'] == 'image/jpeg')
                $image = imagecreatefromjpeg($upload_data['full_path']);
            elseif ($isAlpha = $info['mime'] == 'image/gif') {
                $image = imagecreatefromgif($upload_data['full_path']);
            } elseif ($isAlpha = $info['mime'] == 'image/png') {
                $image = imagecreatefrompng($upload_data['full_path']);
            } else {
                return $upload_data['full_path'];
            }
            if ($isAlpha) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
            imagewebp($image, $destination, $quality);
            unlink($upload_data['full_path']);
            $upload_data['full_path'] = $destination;
            $upload_data['file_name'] = $file_name;
            // echo $upload_data['full_path'];die;
            $config['source_image'] = $upload_data['full_path'];
            $config['wm_overlay_path'] = FCPATH . 'assets/img/logo2.jpg';
            $config['wm_type'] = 'overlay';
            $config['width'] = 10;
            $config['height'] = 10;
            $config['padding'] = 30;
            $config['wm_opacity'] = 10;
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'right';
            $config['wm_vrt_offset'] = 20;
            $config['orig_height'] = 20;
            $config['orig_width'] = 20;
            $watermark_array = array(
                array('horizontal' => 'center', 'vertical' => 'middle'),
                array('horizontal' => 'left', 'vertical' => 'top'),
                array('horizontal' => 'right', 'vertical' => 'top'),
                array('horizontal' => 'left', 'vertical' => 'bottom'),
                array('horizontal' => 'right', 'vertical' => 'bottom')
            );


            // ADD YOUR WATERMARKS

            foreach ($watermark_array as $row) {
                $config['wm_vrt_alignment'] = $row['vertical'];
                $config['wm_hor_alignment'] = $row['horizontal'];

                $ci->image_lib->initialize($config);
                $ci->image_lib->watermark();
            }
            $ci->image_lib->initialize($config);

            if (!empty($old_file_name)) {

                $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}
function upload_file_without_watermark($post_file_name, $inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'jpeg|jpg|png|mp4|webp|pdf';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    $config['encrypt_name'] = TRUE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {

        if ($ci->upload->do_upload($post_file_name)) {
            $upload_data = $ci->upload->data();
            if (!empty($old_file_name)) {

                $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}

function upload_file_without_watermark_single($post_file_name, $inner_dir, $old_file_name = '', $count = "")
{
    // print_r($count);
    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'jpeg|jpg|png|mp4|webp|pdf';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    $config['encrypt_name'] = TRUE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    if (isset($_FILES[$post_file_name]["name"][$count]) && $_FILES[$post_file_name]["name"] != "") {
        $files = $_FILES;
        $_FILES[$post_file_name]['name'] = $files[$post_file_name]['name'][$count];
        $_FILES[$post_file_name]['type'] = $files[$post_file_name]['type'][$count];
        $_FILES[$post_file_name]['tmp_name'] = $files[$post_file_name]['tmp_name'][$count];
        $_FILES[$post_file_name]['error'] = $files[$post_file_name]['error'][$count];
        $_FILES[$post_file_name]['size'] = $files[$post_file_name]['size'][$count];
        if ($ci->upload->do_upload($post_file_name)) {
            $upload_data = $ci->upload->data();
            if (!empty($old_file_name)) {

                $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}

function upload_file_pdf($post_file_name, $inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'pdf';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    //    $config['encrypt_name'] = TRUE;
    // print_r($config);die;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {
        if ($ci->upload->do_upload($post_file_name)) {
            $upload_data = $ci->upload->data();
            if (!empty($old_file_name)) {

                $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                if ($file_path != "" && file_exists($file_path)) :
                    unlink($file_path);
                endif;
            }

            return $upload_data;
        } else {
            return $ci->upload->display_errors();
        }
    }
    return FALSE;
}

function delete_file($inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!empty($old_file_name)) {

        $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
        if ($file_path != "" && file_exists($file_path)) :
            unlink($file_path);
        endif;
        return TRUE;
    }

    return FALSE;
}

function one_singal_notification($playerIds, $msg)
{

    $key = ''; // add one single key
    $message = $msg;

    $title = '';
    $ids = array($playerIds);
    $content = array(
        "en" => $message,
        "title" => $title,
        "message" => $msg,
    );
    $fields = array(
        'app_id' => "", // add one single app_id
        // 'included_segments' => array('All'),
        'large_icon' => "ic_launcher.png",
        'small_icon' => "ic_launcher_small.png",
        'include_player_ids' => $ids,
        'contents' => $content
    );

    $fields = json_encode($fields);
    //var_dump($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);
    //print("\nJSON sent:\n");
    //print($response);
    return $response;
}

function upload_multiple_file($post_file_name, $inner_dir, $old_file_name = '')
{
    $ci = &get_instance();
    $upload_path = UPLOAD_DIR . $inner_dir;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;
    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = 'pdf|doc|jpg|jpeg|png';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    //    $config['encrypt_name'] = TRUE;

    $ci->load->library('upload');
    $ci->load->library('image_lib');
    $ci->upload->initialize($config);
    $config = array();
    $config['image_library'] = 'GD2';

    if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {
        $dataInfo = array();
        $files = $_FILES;
        $cpt = count($_FILES[$post_file_name]['name']);
        for ($i = 0; $i < $cpt; $i++) {

            $_FILES[$post_file_name]['name'] = $files[$post_file_name]['name'][$i];
            $_FILES[$post_file_name]['type'] = $files[$post_file_name]['type'][$i];
            $_FILES[$post_file_name]['tmp_name'] = $files[$post_file_name]['tmp_name'][$i];
            $_FILES[$post_file_name]['error'] = $files[$post_file_name]['error'][$i];
            $_FILES[$post_file_name]['size'] = $files[$post_file_name]['size'][$i];


            //            print_r($_FILES);die;

            if ($ci->upload->do_upload($post_file_name)) {
                $upload_data = $ci->upload->data();
                // print_r($upload_data['full_path']);die;
                $config['source_image'] = $upload_data['full_path'];
                $config['wm_overlay_path'] = FCPATH . 'assets/img/logo2.jpg';
                $config['wm_type'] = 'overlay';
                $config['width'] = 10;
                $config['height'] = 10;
                $config['padding'] = 30;
                $config['wm_opacity'] = 10;
                $config['wm_vrt_alignment'] = 'bottom';
                $config['wm_hor_alignment'] = 'right';
                $config['wm_vrt_offset'] = 20;
                $config['orig_height'] = 20;
                $config['orig_width'] = 20;
                $watermark_array = array(
                    array('horizontal' => 'center', 'vertical' => 'middle'),
                    array('horizontal' => 'left', 'vertical' => 'top'),
                    array('horizontal' => 'right', 'vertical' => 'top'),
                    array('horizontal' => 'left', 'vertical' => 'bottom'),
                    array('horizontal' => 'right', 'vertical' => 'bottom')
                );


                // ADD YOUR WATERMARKS

                foreach ($watermark_array as $row) {
                    $config['wm_vrt_alignment'] = $row['vertical'];
                    $config['wm_hor_alignment'] = $row['horizontal'];

                    $ci->image_lib->initialize($config);
                    $ci->image_lib->watermark();
                }
                $ci->image_lib->initialize($config);
                // $ci->image_lib->watermark();
                // if (! $ci->image_lib->watermark())
                // {
                //     echo $ci->image_lib->display_errors();
                //     return false;
                // }
                if (!empty($old_file_name)) {
                    $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                    if ($file_path != "" && file_exists($file_path)) :
                        unlink($file_path);
                    endif;
                }
                $dataInfo[] = $upload_data;
            }
        }
        return $dataInfo;
    }
    return FALSE;
}

function upload_multi_file_without_watermark($post_file_name, $inner_dir, $old_file_name = '')
{

    $ci = &get_instance();

    $upload_path = UPLOAD_DIR . $inner_dir;

    if (!file_exists(UPLOAD_DIR)) : mkdir(UPLOAD_DIR, 0777, TRUE);
    endif;
    if (!file_exists($upload_path)) : mkdir($upload_path, 0777, TRUE);
    endif;

    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = '*';
    $config['max_width'] = 0;
    $config['max_height'] = 0;
    $config['max_size'] = 0;
    $config['encrypt_name'] = TRUE;

    $ci->load->library('upload', $config);
    $dataInfo = array();
    $files = $_FILES;
    $count = count($_FILES[$post_file_name]['name']);

    if ($count != 0) {
        for ($i = 0; $i < $count; $i++) {
            $_FILES[$post_file_name]['name'] = $files[$post_file_name]['name'][$i];
            $_FILES[$post_file_name]['type'] = $files[$post_file_name]['type'][$i];
            $_FILES[$post_file_name]['tmp_name'] = $files[$post_file_name]['tmp_name'][$i];
            $_FILES[$post_file_name]['error'] = $files[$post_file_name]['error'][$i];
            $_FILES[$post_file_name]['size'] = $files[$post_file_name]['size'][$i];
            $ci->upload->do_upload($post_file_name);
            $dataInfo[] = $ci->upload->data();
        }
        return $dataInfo;
    } else {
        if (isset($_FILES[$post_file_name]["name"]) && $_FILES[$post_file_name]["name"] != "") {
            if ($ci->upload->do_upload($post_file_name)) {
                $upload_data[] = $ci->upload->data();
                if (!empty($old_file_name)) {

                    $file_path = UPLOAD_DIR . $inner_dir . "/" . $old_file_name;
                    if ($file_path != "" && file_exists($file_path)) :
                        unlink($file_path);
                    endif;
                }

                return $upload_data;
            } else {
                return $ci->upload->display_errors();
            }
        }
        return FALSE;
    }
}

function verify_pan($pan)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_PORT => "",
        CURLOPT_URL => "https://api.digio.in/v3/client/kyc/fetch_id_data/PAN",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>  "{\r\n    \"id_no\": \"$pan\"\r\n}\r\n",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic QUlOTjZXTks5R1FFQThBWVBZTk9ZRVRCWEFNUjJERFY6WVNPWlpaOVNIOUlXTURHNlNYSjlYQ0dOTzFaRkVVUjg=",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: e1e98d5b-54ea-c187-c7cb-139aba2cf301"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return $response;
    }
}

function get_gst_per_product($product_dtl_id)
{
    $ci = &get_instance();
    $join = array(
        array('table' => TBL_PRODUCT . ' p', 'on' => 'pd.ProductID=p.ProductID')
    );
    $gst = $ci->Common->get_info($product_dtl_id, TBL_PRODUCT_DTL . ' pd', 'pd.ProductDTLID', false, '*', $join)->GST;
    return $gst;
}

function car_certified_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_T_CARS, 'CarID', FALSE, 'isCertified');
    //echo $ci->db->last_query();
    //die;
    if ($IsActive) {
        if ($IsActive->isCertified == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch product_feature" data-id="{$id}" data-control="car">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function car_signature_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_T_CARS, 'CarID', FALSE, 'isSignature');
    //echo $ci->db->last_query();
    //die;
    if ($IsActive) {
        if ($IsActive->isSignature == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="car">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

//function car_approved_row($id) {
//   
//    $ci = & get_instance();
//    $action="";
//    $IsActive = $ci->Common->get_info($id, TBL_T_CARS, 'CarID', FALSE, 'isApproved');
//    //echo $ci->db->last_query();
//    //die;
//    if ($IsActive) {
//        if ($IsActive->isApproved == 1) {
//            $st = "checked";
//        } else {
//            $st = "";
//        }
//        $action = <<<EOF
//            <div class="tooltip-top">
//                <label class="switch approve_feature" data-id="{$id}" data-control="dealer-car-list">
//                    <input type="checkbox" {$st}>
//                    <span class="slider round"></span>
//                </label>
//            </div>
//EOF;
//    }
//    return $action;
//}

function banner_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_BANNER, 'BannerID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="banner">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function client_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_CLIENT, 'ClientID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="client">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function sales_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_SALES_REPRESENTATIVE, 'SRID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="sales_representative">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function employee_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_EMPLOYEES, 'EmployeeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="employee">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function referal_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_REFERAL_CODE, 'ID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="referal_code">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function user_approved_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_USERS, 'id', FALSE, 'isApproved');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isApproved == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="user">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function user_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_USERS, 'id', FALSE, 'subscription');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->subscription == 1) {
            $action = "";
        } else {
            $action = '<a data-original-title="Approved Customer" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal approve_feature btn-mini" data-id="' . $id . '" data-value="1" data-control="user" data-method="approved"><i class="fa fa-check"></i></a>';
        }
    }
    return $action;
}
function user_subscription_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_USERS, 'id', FALSE, 'subscription');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->subscription == 1) {
            $st = "Subscribed";
            $status = 'success';
        } else {
            $st = "Not Subscribed";
            $status = 'danger';
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="label label-{$status}" data-id="{$id}" data-control="user">
                     {$st} 
                    
                </label>
            </div>
EOF;
    }
    return $action;
}

function blog_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_APP_BLOG, 'BlogID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="blog">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function newsletter_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_APP_NEWSLETTER, 'SubscriptionID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="newsletter">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function faq_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_TFAQ, 'FaqID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="faq">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function owner_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_OWNER, 'OwnerID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="owner">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function price_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_PRICERANGE, 'PriceRangeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="price_range">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function car_age_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_CARAGE, 'CarAgeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="car_age">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function km_range_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_KMRANGE, 'KMRangeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="km_range">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function state_reg_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_STATEREG, 'StateRegID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="state_registration">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function role_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_ROLE, 'RoleID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="role">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function make_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_MAKE, 'MakeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="make">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function model_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_MODEL, 'ModelID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="model">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function city_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_APP_CITY, 'CityID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="city">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function body_type_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_BODYTYPE, 'BodyTypeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="body_type">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function transmission_type_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_TRANSMISSION, 'TransmissionID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="transmission_type">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function fuel_type_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_M_FUELTYPE, 'FuelTypeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="fuel_type">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function State()
{

    $ci = &get_instance();
    // $action="";
    $State_list = $ci->Common->get_list(TBL_APP_STATE, 'StateID', 'State_Name');
    //   echo $ci->db->last_query();
    //    die;

    return $State_list;
}
function create_slug($text)
{
    //    $slug = trim($string);
    //    $slug = strtolower($slug);
    //    $slug = str_replace(' ', '-', $slug);
    //    $slug = str_replace('/', '-', $slug);
    //    $slug = str_replace("�", "-", $slug);
    //    $slug = str_replace("'", "-", $slug);
    //    $string = strtolower(url_title(convert_accented_characters($string), '-'));
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}
function vehicle_type_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_VEHICLE_TYPE, 'VehicalTypeID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="vehicle_type">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
function car_inquiry_action_row($id)
{

    $ci = &get_instance();
    $html = '';
    $inquiry_dtl = $ci->Common->get_info($id, TBL_T_CARINQUIRY, 'CarInquiryID');
    if ($inquiry_dtl->Status == 0) {
        $html .= '<div class="tooltip-top">
                <a data-original-title="Approved Inquiry" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal inquiry_approve_feature btn-mini" data-id="' . $id . '" data-value="1" data-method="approved"><i class="fa fa-check"></i></a>
                <a data-original-title="Reject Inquiry" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal inquiry_approve_feature btn-mini" data-id="' . $id . '" data-value="2" data-method="approved"><i class="fa fa-ban"></i></a>
            </div>';
    }

    return $html;
}
function send_for_approval($id)
{

    $ci = &get_instance();
    $html = '';
    $event_info = $ci->Common->get_info($id, TBL_EVENT, 'AuctionID', FALSE, 'Status');
    if ($event_info->Status == "Work in process" || $event_info->Status == "Rejected") {
        $html .= '<div class="tooltip-top">
                <a data-original-title="Send for Approval" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal send_for_approval btn-mini" data-id="' . $id . '" data-value="Send for approval" data-method="approved_auction"><i class="fa fa-check"></i></a>
                
            </div>';
    }

    return $html;
}
function admin_auction_action($id)
{

    $ci = &get_instance();
    $html = '';
    $event_info = $ci->Common->get_info($id, TBL_EVENT, 'AuctionID', FALSE, 'Status');
    if ($event_info->Status == "Send for approval") {
        $html .= '<div class="tooltip-top">
            <a data-original-title="Approved Auction" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal approve_auction btn-mini" data-id="' . $id . '" data-value="Approved" data-method="approved_auction"><i class="fa fa-check"></i></a>
            <a data-original-title="Reject Auction" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal approve_auction btn-mini" data-id="' . $id . '" data-value="Rejected" data-method="approved_auction"><i class="fa fa-ban"></i></a>
                
            </div>';
    }

    return $html;
}

function car_inquiry_status_row($Status)
{
    if ($Status == 0) {
        return 'Pending';
    } else if ($Status == 1) {
        return 'Accepted';
    } else if ($Status == 2) {
        return 'Rejected';
    }
}

function car_add_active_row($event_id)
{
    $ci = &get_instance();
    $event_info = $ci->Common->get_info($event_id, TBL_EVENT, 'AuctionID', FALSE, '*');
    //    if($event_info->isApproved == 1){
    $html = '';
    $url = base_url() . 'car?auction_id=' . base64_encode($event_id);
    if ($event_id > 0) {
        $html = '<div class="tooltip-top">
                    <b>' . $event_info->NumberOfVehical . '</b>  <a data-original-title="Add Auction Car" data-placement="top" data-toggle="tooltip" href="' . $url . '" class="btn btn-xs btn-default btn-equal btn-mini" style="background-color: white;padding-left: 0px !important;padding-top: 0px !important;padding-bottom: 5px !important;font-size: 11px !important;" ><b style="font-size:17px;"></b> <i class="fa fa-plus"></i></a>                
                </div>';
    }
    return $html;
    //    }
}
function bank_car_add_active_row($event_id)
{
    //    $ci = & get_instance();
    //    $event_info = $ci->Common->get_info($event_id, TBL_EVENT, 'AuctionID', FALSE, 'isApproved');
    //    if($event_info->isApproved == 1){
    $html = '';
    $url = base_url() . 'car?auction_id=' . base64_encode($event_id) . '&type=' . base64_encode('bank_car');
    if ($event_id > 0) {
        $html = '<div class="tooltip-top">
                    <a data-original-title="Add Auction Car" data-placement="top" data-toggle="tooltip" href="' . $url . '" class="btn btn-xs btn-default btn-equal btn-mini"  ><i class="fa fa-eye"></i></a>                
                </div>';
    }
    return $html;
    //    }
}
function auction_export_row($id)
{
    //    $ci = & get_instance();
    //    $event_info = $ci->Common->get_info($id, TBL_EVENT, 'TBL_EVENT', FALSE, 'isApproved');
    //    if($event_info->isApproved == 1){
    $html = '<div class="tooltip-top">
                <a data-original-title="Export Auction Bid Details" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini export-event-bid" data-id="' . $id . '"><i class="fa fa-download"></i></a>
            </div>';

    return $html;
    //    }
}
function display_commission_total($id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_CLIENT_COMMISSION . " c", "on" => "c.ClientID=e.ClientID", "type" => "LEFT"),
        array("table" => TBL_T_CARS . " car", "on" => "car.EventID=e.AuctionID AND car.VehicleTypeID = c.VehicleTypeID", "type" => "LEFT"),
    );
    $commission = $ci->Common->get_info($id, TBL_EVENT . ' e', 'e.AuctionID', false, 'SUM(car.SoldAmount * c.Commission/100) as TotalCommission', $join);
    //    echo $ci->db->last_query();die;
    //    print_r($commission);die;
    return '₹' . number_format($commission->TotalCommission, 2);
}
function display_invoice_total($id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_EVENT . " e", "on" => "cl.ClientID=e.ClientID", "type" => "LEFT"),
        array("table" => TBL_CLIENT_COMMISSION . " c", "on" => "c.ClientID=cl.ClientID", "type" => "LEFT"),
        array("table" => TBL_T_CARS . " car", "on" => "car.EventID=e.AuctionID AND car.VehicleTypeID = c.VehicleTypeID", "type" => "LEFT"),
    );
    $commission = $ci->Common->get_info($id, TBL_CLIENT . ' cl', 'cl.ClientID', false, 'SUM(car.SoldAmount * c.Commission/100) as TotalCommission', $join);
    //    echo $ci->db->last_query();die;
    //    print_r($commission);die;
    return '₹' . number_format($commission->TotalCommission, 2);
}
//function display_commission_total($id) {
//    $ci = & get_instance();
//    $join=array(
//        array("table" => TBL_CLIENT_COMMISSION . " c", "on" => "c.ClientID=e.ClientID", "type" => "LEFT"),
//    );
//    $commission = $ci->Common->get_info($id, TBL_EVENT . ' e','e.AuctionID',false,'SUM(c.Commission) as TotalCommission',$join);
////    echo $ci->db->last_query();die;
////    print_r($commission);die;
//    return '₹' . number_format($commission->TotalCommission,2);
//}

function auctional_vehical($id)
{
    $ci = &get_instance();

    // $auctional_vehical = $ci->Common->get_info($id, TBL_T_CARS . ' cl','cl.EventID','CarID IN (SELECT CarID FROM '.TBL_BID.' where EventID='.$id.')','COUNCT(DISTINCT(cl.CarID)) as TotalCars');
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'b.EventID', '', 'COUNT(DISTINCT(CarID)) as TotalCars');
    return $auctional_vehical->TotalCars;
}
function approved_vehical($id)
{
    $ci = &get_instance();

    // $auctional_vehical = $ci->Common->get_info($id, TBL_T_CARS . ' cl','cl.EventID','CarID IN (SELECT CarID FROM '.TBL_BID.' where EventID='.$id.')','COUNCT(DISTINCT(cl.CarID)) as TotalCars');
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'b.EventID', '(StatusID = 3 OR StatusID = 5 OR StatusID = 6)', 'COUNT(DISTINCT(CarID)) as TotalCars');
    return $auctional_vehical->TotalCars;
}
function fullfilled_vehical($id)
{
    $ci = &get_instance();

    // $auctional_vehical = $ci->Common->get_info($id, TBL_T_CARS . ' cl','cl.EventID','CarID IN (SELECT CarID FROM '.TBL_BID.' where EventID='.$id.')','COUNCT(DISTINCT(cl.CarID)) as TotalCars');
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'b.EventID', 'StatusID = 6', 'COUNT(DISTINCT(CarID)) as TotalCars');
    return $auctional_vehical->TotalCars;
}
function total_approved_bid_total($id)
{
    $ci = &get_instance();

    // $auctional_vehical = $ci->Common->get_info($id, TBL_T_CARS . ' cl','cl.EventID','CarID IN (SELECT CarID FROM '.TBL_BID.' where EventID='.$id.')','COUNCT(DISTINCT(cl.CarID)) as TotalCars');
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'b.EventID', 'StatusID = 3', 'SUM(BidAmount) as TotalBidAmount');
    return $auctional_vehical->TotalBidAmount;
}
function total_fulfilled_bid_total($id)
{
    $ci = &get_instance();

    // $auctional_vehical = $ci->Common->get_info($id, TBL_T_CARS . ' cl','cl.EventID','CarID IN (SELECT CarID FROM '.TBL_BID.' where EventID='.$id.')','COUNCT(DISTINCT(cl.CarID)) as TotalCars');
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'b.EventID', 'StatusID = 6', 'SUM(BidAmount) as TotalBidAmount');
    return $auctional_vehical->TotalBidAmount;
}
function get_client_auction($id)
{
    $ci = &get_instance();
    $get_client_auction = $ci->Common->get_info($id, TBL_EVENT, 'CreatedBy', '', 'COUNT(AuctionID) as TotalAuctions');
    return $get_client_auction->TotalAuctions;
}
function get_client_vehical_auction($id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_T_CARS . " car", "on" => "b.CarID=car.CarID", "type" => "LEFT"),
    );
    $auctional_vehical = $ci->Common->get_info($id, TBL_BID . ' b', 'car.CreatedBy', '', 'COUNT(DISTINCT(b.CarID)) as TotalCars', $join);
    return $auctional_vehical->TotalCars;
}

function windraw_amount($withdraw_id)
{
    $ci = &get_instance();

    $auctional_vehical = $ci->Common->get_info($withdraw_id, TBL_USER_WITHDRAW_REQ . ' wr', 'wr.WindrawID', '', '*');
    $html = '';
    if ($auctional_vehical->Type == 'RSD') {
        $html .= 'Buying Limit : ' . $auctional_vehical->BuyingLimit . '<br>';
        $html .= 'Vehicle Limit : ' . $auctional_vehical->VehicleLimit . '<br>';
    } else {
        $html .= '';
    }
    return $html;
    if ($auctional_vehical->StatusID == 1) {
        $wallet_limit = $ci->Common->get_info($auctional_vehical->UserID, TBL_USER_WALLET, 'UserID', false, '*');
        return $wallet_limit->Amount;
    } else if ($auctional_vehical->StatusID == 3) {
        return $auctional_vehical->WindrawAmount;
    }
}
function windraw_action($withdraw_id)
{
    $ci = &get_instance();

    $auctional_vehical = $ci->Common->get_info($withdraw_id, TBL_USER_WITHDRAW_REQ . ' wr', 'wr.WindrawID', '', '*');
    $html = '';
    if ($auctional_vehical->StatusID == 1) {
        $html .= '<div class="tooltip-top">
        <a data-original-title="Approve Withdraw Request" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_withdraw_form" data-id="' . $withdraw_id . '" data-control="withdraw-req" data-action="approve"><i class="fa fa-check"></i></a>
        <a data-original-title="Reject Withdraw Request" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-danger btn-equal btn-mini open_my_withdraw_form" data-id="' . $withdraw_id . '" data-control="withdraw-req" data-action="reject"><i class="fa fa-close"></i></a>
    </div>';
    } else {
        $html .= $auctional_vehical->Comment;
    }
    return $html;
}

function get_auction_code($eventCode)
{
    $ci = &get_instance();
    $old_code = $ci->Common->get_info(1, TBL_EVENT, 1, 'SUBSTRING(EventCode FROM 1 FOR CHAR_LENGTH(EventCode) - 3) = "' . $eventCode . '"', '*', false, false, array('field' => 'AuctionID', 'order' => 'DESC'));
    if (!empty($old_code)) {

        // return $old_code->EventCode.str_pad((((int)substr($eventCode, -3))+1),3,'0',STR_PAD_LEFT);
        $event_code = $eventCode . str_pad((((int)substr($old_code->EventCode, -3)) + 1), 3, '0', STR_PAD_LEFT);
        // print_r($event_code);die;
        return $event_code;
    } else {
        return $eventCode . '001';
    }
}

function get_car_noc_form($car_id, $type)
{
    $ci = &get_instance();
    return $old_code = $ci->Common->get_info($car_id, TBL_T_CAR_NOC, 'CarID', 'Type = "' . $type . '"', '*');
}

function auction_acr_row($id)
{
    $ci = &get_instance();
    $current_date_time = date('Y-m-d H:i:s');
    // $where = 'iq.StartTime <="' . $current_date_time . '" AND iq.EndTime <= "' . $current_date_time . '"';
    $event_info = $ci->Common->get_info($id, TBL_EVENT, 'AuctionID', false, '*');
    // if ($event_info->CutOffTime <= $current_date_time) {
    if ($event_info->isACRReportGenerated == 1) {
        $html = '<div class="tooltip-top">
                        <a data-original-title="Download ACR Report" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini export-event-bid" data-id="' . $id . '"><i class="fa fa-download"></i></a>
                    </div>';

        return $html;
    } else {
        $html = '<div class="tooltip-top">
                    <a data-original-title="Download ACR Bank Report" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini generate-acr-report" data-id="' . $id . '"><i class="fa fa-download"></i></a> &nbsp;
                    <a data-original-title="Download ACR Admin Report" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini generate-acr-admin-report" data-id="' . $id . '"><i class="fa fa-download"></i></a>&nbsp;
                    <a data-original-title="Download ACR Summary Report" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini generate-acr-summery-report" data-id="' . $id . '"><i class="fa fa-download"></i></a>
                </div>';

        return $html;
    }
    // } else {
    // }
}

function car_image_row($default_image, $car_id)
{
    $ci = &get_instance();
    if ($default_image != '' && file_exists(UPLOAD_DIR . CAR_DIR . $default_image)) {

        $url = BASE_URL . UPLOAD_DIR_NAME . CAR_DIR . $default_image;
    } else {
        $setting = $ci->Common->get_info(1, TBL_SETTING, 'ID');
        $url = BASE_URL . UPLOAD_DIR_NAME . CAR_DIR . $setting->CarDefaultImage;
    }
    $action = '<img src="' . $url . '" width="80" height="60">';
    return $action;
}


function event_check_chk_id($question_id, $selected_question_id, $select_all)
{

    $check_attr = ($select_all == 1) ? 'checked="checked"' : '';
    if ($select_all == 1) {
        return 'checked="checked"';
    } else {
        if ($question_id > 0 && !empty($selected_question_id)) {
            $selected_question_id = explode(",", $selected_question_id);
            if (in_array($question_id, $selected_question_id)) {
                return 'checked="checked"';
            }
        }
    }
    return '';
}

function get_invoice_checkbox($car_id, $selected_car_id, $select_all)
{

    $check_attr = '';

    if ($select_all == 1) {
        $check_attr = 'checked="checked"';
    } else {
        if ($car_id > 0 && !empty($selected_car_id)) {
            $selected_car_id = explode(",", $selected_car_id);

            if (in_array($car_id, $selected_car_id)) {
                $check_attr = 'checked="checked"';
            }
        }
    }
    // echo $check_attr;die('1557');
    return '
    <input type="checkbox" class="mdc-checkbox__native-control invoice_id_chk" name="car_ids[]" ' . $check_attr . ' id="event_checkbox_id_' . $car_id . '" value="' . $car_id . '" >';
}

function edit_image($image)
{
    $ci = &get_instance();
    if ($image != '' && file_exists(UPLOAD_DIR . CAR_DIR . $image)) {

        $url = BASE_URL . UPLOAD_DIR_NAME . CAR_DIR . $image;
    } else {
        $setting = $ci->Common->get_info(1, TBL_SETTING, 'ID');
        $url = BASE_URL . UPLOAD_DIR_NAME . CAR_DIR . $setting->CarDefaultImage;
    }
    $html = '<img src="' . $url . '" width="80" height="60">';
    return $html;
}

function live_auction_monitor_car_row($CarID)
{
    $ci = &get_instance();
    $total_bid = $ci->Common->get_all_info($CarID, TBL_BID . ' b', 'b.CarID', false, '*,(SELECT COUNT(DISTINCT(UserID)) FROM ' . TBL_BID . ' WHERE CarID=b.CarID) as UniqueBidder');
    $html = '';
    if (!empty($total_bid)) {

        $html .= 'Total Bid :' . count($total_bid) . '<br>';
        $html .= 'Unique Bidder :' . $total_bid[0]->UniqueBidder;
    }
    return $html;
}

function bulk_bid_car_row($bid_id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_BID_STATUS . " bs", "on" => "bid.StatusID=bs.StatusID", "type" => "LEFT"),
    );
    $bid_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', false, '*', $join);
    $status = 'warning';
    if ($bid_details->Status == 'Pending For Approval') {
        $status = 'warning';
    } else if ($bid_details->Status == 'Rejected') {
        $status = 'danger';
    } else if ($bid_details->Status == 'Approved' || $bid_details->Status == 'Paid') {
        $status = 'success';
    }

    return '<label class="label label-' . $status . '">' . $bid_details->Status . '</label>';
}
function bulk_bid_car_approve_row($bid_id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_BID_STATUS . " bs", "on" => "bid.StatusID=bs.StatusID", "type" => "LEFT"),
    );
    $bid_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', false, '*', $join);
    $status = 'warning';
    if ($bid_details->Status == 'Pending For Approval') {
        $status = 'warning';
    } else if ($bid_details->Status == 'Rejected') {
        $status = 'danger';
    } else if ($bid_details->Status == 'Approved' || $bid_details->Status == 'Paid') {
        $status = 'success';
    }
    if ($bid_details->Status == 'Pending For Approval') {
        return '<a data-original-title="Approved Customer" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal bid_approve_feature btn-mini" data-value="3" data-bid-id="' . $bid_details->BidID . '" data-car-id="' . $bid_details->CarID . '" data-event-id="' . $bid_details->EventID . '" data-controller="auction" data-method="approved"><i class="fa fa-check"></i></a>';
    }
}

function bulk_bid_car_approve_row_checkbox($bid_id, $selected_question_id)
{
    $ci = &get_instance();
    if ($bid_id > 0 && !empty($selected_question_id)) {
        $selected_question_id = explode(",", $selected_question_id);
        if (in_array($bid_id, $selected_question_id)) {
            $text = 'checked="checked"';
        } else {

            $text = '';
        }
    } else {

        $text = '';
    }
    $join = array(
        array("table" => TBL_BID_STATUS . " bs", "on" => "bid.StatusID=bs.StatusID", "type" => "LEFT"),
    );
    $bid_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', false, '*', $join);
    $status = 'warning';
    if ($bid_details->Status == 'Pending For Approval') {
        return '<div class="mdc-checkbox" id="rgp_' . $bid_id . '">
    <input type="checkbox" class="mdc-checkbox__native-control bid_id_chk" name="bid_ids[]" ' . $text . ' id="bid_checkbox_id_' . $bid_id . '" value="' . $bid_id . '" >
    <div class="mdc-checkbox__background">
      <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
        <path class="mdc-checkbox__checkmark-path" fill="none" d=""></path>
      </svg>
      <div class="mdc-checkbox__mixedmark"></div>
    </div>
</div>';
    } else {
        return '';
    }
}

function auction_bid_bulk_row($id)
{
    return '<a class="btn btn-primary open_rsd_form_form" href="javascript:;" data-control="auction" data-method="bid_winner_upload" style="margin-top:0px;" data-id="' . $id . '" data-control="auction" data-method="bid_winner_upload"><i class="fa fa-file-excel-o"></i></a>';
}

function live_auction_monitor_car_detail_row($CarID)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_EVENT . " e", "on" => "e.AuctionID=c.EventID", "type" => "LEFT"),
        array("table" => TBL_M_MAKE . " make", "on" => "c.MakeID=make.MakeID", "type" => "LEFT"),
        array("table" => TBL_VEHICLE_TYPE . " vt", "on" => "c.VehicleTypeID=vt.VehicalTypeID", "type" => "LEFT"),
        array("table" => TBL_CLIENT . " client", "on" => "e.ClientID=client.ClientID", "type" => "LEFT"),
    );
    $car_details = $ci->Common->get_info($CarID, TBL_T_CARS . ' c', 'c.CarID', false, '*', $join);
    // print_r($car_details);die;
    $html = '';
    if (!empty($car_details)) {
        $html = 'Title :' . $car_details->MakeName . ' ' . $car_details->Model . '<br>';
        $html .= 'Reg
         No :' . $car_details->CarNo . '<br>';
        $html .= 'LAN :' . $car_details->LAN . '<br>';
        $html .= 'Make Year :' . $car_details->MfgYear . '<br>';
        $html .= 'Auction ID :' . str_pad($car_details->CarID, 6, '0', STR_PAD_LEFT) . '<br>';
        // $html .= 'Contact No :'. $car_details->MfgYear.'<br>';
    }
    $car_detail_url = base_url('car/view_details/' . base64_encode($CarID));
    return '<a href="' . $car_detail_url . '" target="_blank">' . $html . '</a>';
}
function live_auction_monitor_car_sp_row($CarID)
{
    $ci = &get_instance();
    $car_details = $ci->Common->get_info($CarID, TBL_T_CARS . ' c', 'c.CarID', false, '*');
    // print_r($car_details);die;
    $html = '';
    if (!empty($car_details)) {
        $html = 'Base Price :' . $car_details->SP . '<br>';
        $html .= 'INCR :' . $car_details->INCR . '<br>';
        $html .= 'RP :' . $car_details->ReservePrice;
    }
    return $html;
}
function live_auction_top_bidder_car_row($CarID)
{
    $ci = &get_instance();

    $join = array(
        array("table" => TBL_USERS . " user", "on" => "b.UserID=user.id", "type" => "LEFT"),
        array("table" => TBL_STATE . " state", "on" => "user.StateID=state.StateID", "type" => "LEFT"),
    );
    // $winner_first_detail = $this->Common->get_info($single->CarID,TBL_BID . ' b','b.CarID','b.StatusID=3','*,b.CreatedOn as CreatedOn',$join);

    $winner_first_detail = $ci->Common->get_all_info($CarID, TBL_BID . ' b', 'b.CarID', 'StatusID != 4', '*,b.CreatedOn as CreatedOn', false, $join, false, array(array('field' => 'BidAmount', 'order' => 'DESC'), array('field' => 'b.CreatedOn', 'order' => 'ASC')), 1);
    $html = '';
    if (!empty($winner_first_detail)) {
        if (isset($winner_first_detail[0])) {
            $winner_first_detail = $winner_first_detail[0];
            $html = (!empty($winner_first_detail)) ? 'Winner Name : ' . $winner_first_detail->first_name . ' ' . $winner_first_detail->last_name : '';
            $html .= '<br>';
            $html .= (!empty($winner_first_detail)) ? 'Mobile : ' . $winner_first_detail->mobile_no : '';
            $html .= '<br>';
            $html .= (!empty($winner_first_detail)) ? 'Dealer Code : ' . $winner_first_detail->Code : '';
            $html .= '<br>';
            $html .= (!empty($winner_first_detail)) ? 'State : ' . $winner_first_detail->StateName : '';
            // $html .= '<br>';
            // $html .= (!empty($winner_first_detail)) ? 'Bid Amount : ' . $winner_first_detail->BidAmount : '';
            $html .= '<br>';
            $html .= (!empty($winner_first_detail)) ? 'Bid Time : ' . date('d-m-Y h:i A', strtotime($winner_first_detail->CreatedOn)) : '';
            $html .= '<br>';
            $html .= '<a class="btn btn-primary" style="height: 23px;padding: 2px;" href="' . base_url('auction/view_bid/' . $CarID) . '" target="_blank">View All Bids</a>';
        }
    } else {

        $html .= '<a class="btn btn-primary" style="height: 23px;padding: 2px;" href="' . base_url('auction/view_bid/' . $CarID) . '" target="_blank">View All Bids</a>';
    }

    return $html;
}
function live_auction_top_bidder_amount_row($CarID)
{
    $ci = &get_instance();

    $join = array(
        array("table" => TBL_USERS . " user", "on" => "b.UserID=user.id", "type" => "LEFT"),
        array("table" => TBL_T_CARS . " car", "on" => "b.CarID=car.CarID", "type" => "LEFT"),
    );
    // $winner_first_detail = $this->Common->get_info($single->CarID,TBL_BID . ' b','b.CarID','b.StatusID=3','*,b.CreatedOn as CreatedOn',$join);

    $winner_first_detail = $ci->Common->get_all_info($CarID, TBL_BID . ' b', 'b.CarID', 'StatusID != 4', '*,b.CreatedOn as CreatedOn', false, $join, false, array(array('field' => 'BidAmount', 'order' => 'DESC'), array('field' => 'b.CreatedOn', 'order' => 'ASC')), 1);
    // print_r($winner_first_detail);die;
    // echo $ci->db->last_query();die;
    $html = '';
    if (!empty($winner_first_detail)) {
        if (isset($winner_first_detail[0])) {
            $winner_first_detail = $winner_first_detail[0];
            $html .= (!empty($winner_first_detail)) ? 'Bid Amount :' . $winner_first_detail->BidAmount : '';
            $html .= '<br>';
            $bid_per = ($winner_first_detail->BidAmount * 100) / $winner_first_detail->ReservePrice;
            $html .= '(' . round($bid_per) . '%)';
        }
    }

    return $html;
}

function auction_monitor_name_row($event_id, $EventName)
{
    $ci = &get_instance();
    $current_date_time = date('Y-m-d H:i:s');
    $current_date_time = strtotime($current_date_time);
    $event_details = $ci->Common->get_info($event_id, TBL_EVENT . ' e', 'e.AuctionID', false, 'e.NumberOfVehical,(SELECT COUNT(DISTINCT(CarID)) FROM ' . TBL_BID . ' WHERE EventID=e.AuctionID) as TotalBid,StartTime,EndTime,CutOffTime,e.AuctionID');
    $add = '';

    if (strtotime($event_details->EndTime) > $current_date_time && strtotime($event_details->StartTime) < $current_date_time) {
        $add = '<span style="animation: blinker 1s linear infinite; border:1px solid;border-color:red;padding:1px 10px 0px 10px; font-size:20px;border-radius:5px;background-color:red;color:white;">Live</span> <p  style="color:red;" class="exiry_p_tag p_tag_event_' . $event_details->AuctionID . '" data-expiry-date="' . $event_details->EndTime . '" data-event-id="' . $event_details->AuctionID . '" data-cutoff-date="' . $event_details->CutOffTime . '"></p>';
    } else if (strtotime($event_details->EndTime) < $current_date_time && strtotime($event_details->CutOffTime) > $current_date_time) {
        $add = '<br><span style="animation: blinker 1s linear infinite; border:1px solid;border-color:red;padding:1px 10px 0px 10px; font-size:14px;border-radius:5px;background-color:red;color:white;">Buffer Time Activated</span> <p  style="color:red;" class="exiry_p_tag p_tag_event_' . $event_details->AuctionID . '" data-expiry-date="' . $event_details->CutOffTime . '" data-event-id="' . $event_details->AuctionID . '" data-cutoff-date="' . $event_details->CutOffTime . '"></p>';
    }

    // $event_Details = $ci->Common->get_info($event_id,TBL_EVENT,'AuctionID');
    $url = base_url('auction-monitor/details/' . $event_id);
    return '<a href="' . $url . '" class="" target="_blank">' . $EventName .  '</a> ' . $add;
}
function auction_monitor_bid_count_row($event_id)
{
    $ci = &get_instance();
    $event_details = $ci->Common->get_info($event_id, TBL_EVENT . ' e', 'e.AuctionID', false, 'e.NumberOfVehical,(SELECT COUNT(DISTINCT(CarID)) FROM ' . TBL_BID . ' WHERE EventID=e.AuctionID) as TotalBid');
    // print_r($car_details);die;
    $html = '';
    if (!empty($event_details)) {
        $html = 'Total Bidded Vehicle :' . $event_details->TotalBid . '<br>';
        $html .= 'No Bid Vehicle :' . ($event_details->NumberOfVehical - $event_details->TotalBid) . '<br>';
    }
    return $html;
}
function auction_monitor_bid_percentage_row($event_id)
{
    $ci = &get_instance();
    $event_cars = $ci->Common->get_all_info($event_id, TBL_T_CARS . ' c', 'c.EventID', false, 'c.ReservePrice,c.CarID');
    $below_50 = 0;
    $below_51_to_70 = 0;
    $below_71_to_80 = 0;
    $below_81_to_90 = 0;
    $below_91_to_100 = 0;
    $above_100 = 0;
    $html = '';
    if (!empty($event_cars)) {
        // echo '<pre>';
        // print_r($event_cars);
        // die('1419');
        foreach ($event_cars as $single_car) {
            $car_id = 0;
            $car_id = $single_car->CarID;
            $max_car_bid = $ci->Common->get_info($car_id, TBL_BID . ' bid', 'bid.CarID', false, 'MAX(BidAmount) as MaxBidAmount');
            if (!empty($max_car_bid)) {
                if ($max_car_bid->MaxBidAmount != '') {

                    $max_car_bid = $max_car_bid->MaxBidAmount;
                    $bid_per = ($max_car_bid * 100) / $single_car->ReservePrice;
                    if ($bid_per <= 50) {
                        $below_50 = $below_50 + 1;
                    } else if ($bid_per > 50 && $bid_per <= 70) {
                        $below_51_to_70 = $below_51_to_70 + 1;
                    } else if ($bid_per > 70 && $bid_per <= 80) {
                        $below_71_to_80 = $below_71_to_80 + 1;
                    } else if ($bid_per > 80 && $bid_per <= 90) {
                        $below_81_to_90 = $below_81_to_90 + 1;
                    } else if ($bid_per > 90 && $bid_per <= 100) {
                        $below_91_to_100 = $below_91_to_100 + 1;
                    } else if ($bid_per > 100) {
                        $above_100 = $above_100 + 1;
                    }
                }
            }
        }
        $html .= 'Below 50% - ' . $below_50 . '<br>';
        $html .= '51% to 70% - ' . $below_51_to_70 . '<br>';
        $html .= '71% to 80% - ' . $below_71_to_80 . '<br>';
        $html .= '81% to 90% - ' . $below_81_to_90 . '<br>';
        $html .= '91% to 100% - ' . $below_91_to_100 . '<br>';
        $html .= 'Above 100% - ' . $above_100;
        return $html;
    } else {
        return '';
    }
}

function bid_winner_name_row($bid_id)
{

    $ci = &get_instance();

    $join = array(
        array('table' => TBL_USERS . ' u', 'on' => 'bid.UserID=u.id', 'type' => 'LEFT')
    );
    $user_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', false, '*', $join);
    $html = '';
    if (!empty($user_details)) {
        $html = $user_details->first_name . ' ' . $user_details->last_name . '<br>';
        $html .= $user_details->Code . '<br>';
        $html .= $user_details->mobile_no;
    }
    return $html;
}
function get_event_details($bid_id)
{

    $ci = &get_instance();

    $join = array(
        array('table' => TBL_EVENT . ' e', 'on' => 'bid.EventID=e.AuctionID', 'type' => 'LEFT'),
        array('table' => TBL_T_CARS . ' c', 'on' => 'bid.CarID=c.CarID', 'type' => 'LEFT')
    );
    $event_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', false, '*,bid.ModifiedOn as ModifiedOn', $join);
    $html = '';
    if (!empty($event_details)) {
        $html = "Winning Price : " . $event_details->BidAmount . '<br>';
        $html .= "Auction End Date : " . date('d-m-Y', strtotime($event_details->EndTime)) . '<br>';
        $html .= "Approval Date : " . date('d-m-Y', strtotime($event_details->ModifiedOn)) . '<br>';
    }
    return $html;
}

function send_sms($mobileNumber, $sms_type, $buyername, $otp, $auction_id = 0)
{

    $ci = &get_instance();
    $setting = $ci->Common->get_info(1, TBL_SETTING, 'ID');
    $template_details = $ci->Common->get_info($sms_type, TBL_SMS_TEMPLATES, 'TemplateType');
    $authKey = '';
    $mobileNumber = $mobileNumber;
    $senderId = "";
    $username = $setting->SMS_USERNAME;
    $password = $setting->SMS_PASSWORD;
    $senderId = $setting->SMS_SENDER_ID;
    $entityId = $setting->SMS_ENTITY_ID;
    $templateId = $template_details->DTLTemplateID;
    $auction_details = array();
    if ($auction_id > 0) {
        $auction_details = $ci->Common->get_info($auction_id, TBL_EVENT, 'AuctionID');
    }
    $contact_number = '9624049054';
    $message = '';
    $message = $template_details->Template;
    $message = str_replace('$buyername', $buyername, $template_details->Template);
    $message = str_replace('$otp', $otp, $message);
    if ($sms_type == 'closing_auction') {
        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $date = date('Y-m-d H:i:s');
            $d1 = new DateTime($date); // first date
            $d2 = new DateTime($auction_details->EndTime); // second date
            $interval = $d1->diff($d2); // get difference between two dates
            $message = str_replace('$hour', $interval->h, $message);
        }
    } else if ($sms_type == 'closing_auction_information') {
        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $date = date('Y-m-d H:i:s');
            $d1 = new DateTime($date); // first date
            $d2 = new DateTime($auction_details->EndTime); // second date
            $diff  = $d1->diff($d2); // get difference between two dates
            $diffDays = (int)$diff->format("%R%a"); // Extract days count in interval
            $day = "Tomorrow";
            switch ($diffDays) {
                case 0:
                    $day = "Today";
                    break;
                case -1:
                    $day = "Yesterday";
                    break;
                case +1:
                    $day = "Tomorrow";
                    break;
                default:
                    $day = "Sometime";
            }
            $message = str_replace('$day', $day, $message);
            $message = str_replace('$end_time', date('H:i A', strtotime($auction_details->EndTime)), $message);
            $message = str_replace('$contact_number', $contact_number, $message);
        }
    } else if ($sms_type == 'auction_pre_intimation') {

        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $message = str_replace('$start_date',  date('d-m-Y', strtotime($auction_details->StartTime)), $message);
            $message = str_replace('$start_time', date('h:i A', strtotime($auction_details->StartTime)), $message);
            $message = str_replace('$contact_number', $contact_number, $message);
        }
    }



    $sms_url = $setting->SMS_URL;
    $sms_api_key = $setting->SMS_API_KEY;
    $sms_sender_id = $setting->SMS_SENDER_ID;
    $message = urlencode($message);
    $url = $sms_url . "apikey=" . $sms_api_key . "=&type=TEXT&sender=" . $sms_sender_id . "&entityId=" . $entityId . "&templateId=" . $templateId . "&mobile=" . $mobileNumber . "&message=" . $message;
    // echo $url.'<br>';
    // die;
    $sms_history_data = array(
        "SMSBody" => $message,
        "MobileNo" => $mobileNumber,
        "SMSTemplateID" => $templateId,
        "CreatedOn" => date('Y-m-d H:i:s'),
        "Status" => 'Pending',
        "SMSURL" => $url,
    );
    $sms_id = $ci->Common->add_info(TBL_USER_SMS_HISTORY, $sms_history_data);
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        //CURLOPT_POSTFIELDS => $postData

    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        log_message('ERROR', 'error:' . curl_error($ch));
    }
    curl_close($ch);
    $output = json_decode($output);
    if (!empty($output) && $output->status == 'success') {
        if ($sms_id) {
            $sms_history_update_data = array(
                "Status" => 'Delivered',
            );
            $sms_id = $ci->Common->update_info($sms_id, TBL_USER_SMS_HISTORY, $sms_history_update_data, 'SMSHistoryID');
        }
        return true;
    } else {
        if ($sms_id) {
            $sms_history_update_data = array(
                "Status" => 'Falied',
            );
            $sms_id = $ci->Common->update_info($sms_id, TBL_USER_SMS_HISTORY, $sms_history_update_data, 'SMSHistoryID');
        }
        return false;
    }
}

function send_sms_yard($mobileNumber, $sms_type, $buyername, $otp, $auction_id = 0)
{

    $ci = &get_instance();
    $setting = $ci->Common->get_info(1, TBL_SETTING, 'ID');
    $template_details = $ci->Common->get_info($sms_type, TBL_SMS_TEMPLATES, 'TemplateType');
    $authKey = '';
    $mobileNumber = $mobileNumber;
    $senderId = "";
    $username = $setting->SMS_USERNAME;
    $password = $setting->SMS_PASSWORD;
    $senderId = $setting->SMS_SENDER_ID;
    $entityId = $setting->SMS_ENTITY_ID;
    $templateId = $template_details->DTLTemplateID;
    $auction_details = array();
    if ($auction_id > 0) {
        $auction_details = $ci->Common->get_info($auction_id, TBL_EVENT, 'AuctionID');
    }
    $contact_number = '8828820306';
    $message = '';
    $message = $template_details->Template;
    $message = str_replace('$buyername', $buyername, $template_details->Template);
    $message = str_replace('$otp', $otp, $message);
    $message = str_replace('$contact_number', $contact_number, $message);
    if ($sms_type == 'closing_auction') {
        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $date = date('Y-m-d H:i:s');
            $d1 = new DateTime($date); // first date
            $d2 = new DateTime($auction_details->EndTime); // second date
            $interval = $d1->diff($d2); // get difference between two dates
            $message = str_replace('$hour', ($interval->h + 1) . ' hours', $message);
        }
    } else if ($sms_type == 'closing_auction_information') {
        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $date = date('Y-m-d H:i:s');
            $d1 = new DateTime($date); // first date
            $d2 = new DateTime($auction_details->EndTime); // second date
            $diff  = $d1->diff($d2); // get difference between two dates
            $diffDays = (int)$diff->format("%R%a"); // Extract days count in interval
            $day = "Tomorrow";
            // switch ($diffDays) {
            //     case 0:
            //         $day = "Today";
            //         break;
            //     case -1:
            //         $day = "Yesterday";
            //         break;
            //     case +1:
            //         $day = "Tomorrow";
            //         break;
            //     default:
            //         $day = "Sometime";
            // }
            $message = str_replace('$day', $day, $message);
            $message = str_replace('$end_time', date('h:i A', strtotime($auction_details->EndTime)), $message);
            $message = str_replace('$contact_number', $contact_number, $message);
        }
    } else if ($sms_type == 'auction_pre_intimation') {

        if (!empty($auction_details)) {
            $message = str_replace('$buyername', $buyername, $message);
            $message = str_replace('$auction_name', $auction_details->EventName, $message);
            $message = str_replace('$start_date',  date('d-m-Y', strtotime($auction_details->StartTime)), $message);
            $message = str_replace('$start_time', date('h:i A', strtotime($auction_details->StartTime)), $message);
            $message = str_replace('$contact_number', $contact_number, $message);
        }
    }



    $sms_url = $setting->SMS_URL;
    $sms_api_key = $setting->SMS_API_KEY;
    $sms_sender_id = $setting->SMS_SENDER_ID;
    $message = urlencode($message);
    $url = $sms_url . "apikey=" . $sms_api_key . "=&type=TEXT&sender=" . $sms_sender_id . "&entityId=" . $entityId . "&templateId=" . $templateId . "&mobile=" . $mobileNumber . "&message=" . $message;
    // echo $url.'<br>';
    // die;
    $sms_history_data = array(
        "SMSBody" => $message,
        "MobileNo" => $mobileNumber,
        "SMSTemplateID" => $templateId,
        "CreatedOn" => date('Y-m-d H:i:s'),
        "Status" => 'Pending',
        "SMSURL" => $url,
    );
    $sms_id = $ci->Common->add_info(TBL_USER_SMS_HISTORY, $sms_history_data);
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        //CURLOPT_POSTFIELDS => $postData

    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        log_message('ERROR', 'error:' . curl_error($ch));
    }
    curl_close($ch);
    $output = explode('|', $output);
    // print_r($output);
    if (!empty($output) && trim($output[0]) == 'SUCCESS') {
        if ($sms_id) {
            $sms_history_update_data = array(
                "Status" => 'Delivered',
            );
            $sms_id = $ci->Common->update_info($sms_id, TBL_USER_SMS_HISTORY, $sms_history_update_data, 'SMSHistoryID');
        }
        // die('123');
        return true;
    } else {
        if ($sms_id) {
            $sms_history_update_data = array(
                "Status" => 'Falied',
            );
            $sms_id = $ci->Common->update_info($sms_id, TBL_USER_SMS_HISTORY, $sms_history_update_data, 'SMSHistoryID');
        }
        return false;
    }
}

function moneyFormatIndia($num)
{
    $explrestunits = "";
    $num = round($num);
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits

        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end

            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer

            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}


function get_bid_betterment_price($BidID)
{
    $ci = &get_instance();
    $bid_details = $ci->Common->get_info($BidID, TBL_BID, 'BidID');
    if (!empty($bid_details)) {
        if ($bid_details->isBetterMenPrice == 1) {
            $last_user_bid_details = $ci->Common->get_info($bid_details->CarID, TBL_BID . " bid", "bid.CarID", ' bid.UserID=' . $bid_details->UserID, '*',  false, false, array('field' => 'bid.BidID', 'order' => 'DESC'), false, 1, 1);
            $html = ' Last Bid : ' . $last_user_bid_details->BidAmount . '<br>' . ' Betterment Price : <b><span class="text-warning">' . $bid_details->BidAmount . '</span></b>';
            return $html;
        } else {
            return $bid_details->BidAmount;
        }
    }
}
function bulk_full_fillment_action($BidID)
{
    $ci = &get_instance();
    $bid_details = $ci->Common->get_info($BidID, TBL_BID, 'BidID');
    $html = '';
    if ($bid_details->StatusID != 6) {
        if (($bid_details->isDisputs == 0 && $bid_details->isBackout == 0)) {
            $html .= '<div class="tooltip-top">
                
                <a data-original-title="Edit Fullfilment" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_popup_form" data-id="' . $BidID . '" data-control="auction-bulk-fulfillment" data-method="edit">Update Details</a>
            </div>';
        }
    } else if ($bid_details->StatusID == 6) {
        $html .= '<div class="tooltip-top">
                
        <a data-original-title="Edit Fullfilment" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_popup_form" data-id="' . $BidID . '" data-control="auction-bulk-fulfillment" data-method="get_history" data-view-type="history" style="margin-bottom:5px;">See History</a>
        <a data-original-title="Approval Reversal" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_popup_form" data-id="' . $BidID . '" data-control="auction-bulk-fulfillment" data-method="get_history" data-view-type="reversal">Approval Reversal</a>
    </div>';
    }

    return $html;
}

function buyer_fee_action($BidID, $Status, $Remark)
{
    $ci = &get_instance();
    if ($Status == "Pending") {
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$ci->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$BidID}" data-control="{$ci->controllers}"><i class="fa fa-pencil"></i></a>
            </div>
        EOF;
        return $action;
    } else {
        return $Remark;
    }
}

function auction_detail_row($EventID)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_STATE . " st", "on" => "e.StateID=st.StateID", "type" => "LEFT"),
        array("table" => TBL_CITY . " ct", "on" => "e.CityID = ct.CityID", "type" => "LEFT"),
    );
    $event_details = $ci->Common->get_info($EventID, TBL_EVENT . ' e', 'AuctionID', '', '*', $join);
    $html = '';
    if (!empty($event_details)) {
        $html .= 'Event Code : ' . $event_details->EventCode . '<br>';
        $html .= 'Event Name : ' . $event_details->EventName . '<br>';
        // $html .= 'State Name : ' . $event_details->StateName . '<br>';
        // $html .= 'City Name : ' . $event_details->CityName . '<br>';
        // $html .= 'Start Date : ' . $event_details->StartTime . '<br>';
        // $html .= 'End Date : ' . $event_details->EndTime . '<br>';
        // $html .= 'No of Vehicle : ' . $event_details->NumberOfVehical . '<br>';
        return $html;
    }
    return $html;
}
function auction_date_row($EventID, $dateField = 'start')
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_STATE . " st", "on" => "e.StateID=st.StateID", "type" => "LEFT"),
        array("table" => TBL_CITY . " ct", "on" => "e.CityID = ct.CityID", "type" => "LEFT"),
    );
    $event_details = $ci->Common->get_info($EventID, TBL_EVENT . ' e', 'AuctionID', '', '*', $join);
    $html = '';
    if (!empty($event_details)) {
        if ($dateField == 'end') {
            $html .= date('d-M-Y', strtotime($event_details->EndTime)) . '<br>';
            $html .= date('h:i A', strtotime($event_details->EndTime));
        } else if ($dateField == 'start') {
            $html .= date('d-M-Y', strtotime($event_details->StartTime)) . '<br>';
            $html .= date('h:i A', strtotime($event_details->StartTime));
        }
        return $html;
    }
    return $html;
}

/* Convert Amount In Words 25-01-2023 */
function convert_amount_in_words($number)
{
    $no = floor($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine',
        '10' => 'ten',
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' => 'nineteen',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety'
    );
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';
    echo ucwords($result) . ucwords($points);
}

function get_bank_comission($bid_id)
{
    $ci = &get_instance();
    $join = array(
        array("table" => TBL_EVENT . " e", "on" => "e.AuctionID=bid.EventID", "type" => "LEFT"),
        array("table" => TBL_T_CARS . " car", "on" => "car.CarID=bid.CarID", "type" => "LEFT"),
        array("table" => TBL_CLIENT_COMMISSION . " cc", "on" => "cc.ClientID = e.ClientID AND cc.VehicleTypeID=car.VehicleTypeID", "type" => "LEFT"),
    );
    $bid_details = $ci->Common->get_info($bid_id, TBL_BID . ' bid', 'bid.BidID', '', '*', $join);
    if (!empty($bid_details)) {
        if ($bid_details->CommissionType == 'f') {
            return $bid_details->Commission;
        } else if ($bid_details->CommissionType == 'p') {
            return round(($bid_details->BidAmount * $bid_details->Commission) / 100);
        }
    }
    return 0;
}

function invoice_maker_action_row($invoice_id)
{

    $ci = &get_instance();
    $title = 'Invoice Maker';
    $encrypted_id = base64_encode($invoice_id);
    $edit_link = base_url() . 'invoice-maker/edit/' . $encrypted_id;
    $html = '';
    $bank_invoice_details = $ci->Common->get_info($invoice_id, TBL_BANK_INVOICE_MAKER . ' i', 'i.MakerID', '', '*');
    if (!empty($bank_invoice_details)) {
        //Invoice View Button
        // if($bank_invoice_details->InvoiceID > 0){
        //     $invoice_detail = $ci->Common->get_info($bank_invoice_details->InvoiceID, TBL_INVOICE . ' i', 'i.InvoiceID', '', '*');
        //     if(!empty($invoice_detail)){
        //         $invoice_url = BASE_URL . UPLOAD_FRONT_DIR . BANK_INVOICE_DIR . $invoice_detail->InvoiceURL . '?invoice_no='.randomNumber();
        //         $html .= '<a data-original-title="View '.$title.'" data-placement="top" data-toggle="tooltip" href="'.$invoice_url.'" target="_blank" class="btn btn-xs btn-default btn-equal btn-mini" target="_blank" data-control="invoice-maker" data-method="commission"><i class="fa fa-eye"></i></a> ';
        //     }
        // }

        //Only Draft Mode Invoice
        $invoice_url = base_url('invoice-maker/view-draft-invoice/' . $invoice_id);
        $html .= '<a data-original-title="View ' . $title . '" data-placement="top" data-toggle="tooltip" href="' . $invoice_url . '" target="_blank" class="btn btn-xs btn-default btn-equal btn-mini" data-control="invoice-maker" data-method="commission"><i class="fa fa-eye"></i></a> ';

        //Edit Button
        if ($bank_invoice_details->StatusID != 3) {
            $html .= '<a data-original-title="Edit ' . $title . '" data-placement="top" data-toggle="tooltip" href="' . $edit_link . '" class="btn btn-xs btn-default btn-equal btn-mini " data-id="' . $invoice_id . '" data-control="' . $invoice_id . '"><i class="fa fa-pencil"></i></a> ';
        }
        //Edit Button
        $html .= '<a data-original-title="Remove ' . $title . '" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="' . $invoice_id . '" data-method="invoice-maker"><i class="fa fa-trash-o"></i></a>';
    }
    return $html;
}

function invoice_maker_invoice_edit($maker_id)
{

    $ci = &get_instance();

    $bank_invoice_details = $ci->Common->get_info($maker_id, TBL_BANK_INVOICE_MAKER . ' i', 'i.MakerID', '', '*');
    if (!empty($bank_invoice_details)) {
        $edit_url = base_url('invoice-checker/edit/' . base64_encode($maker_id));
        return '<a href="' . $edit_url . '" target="_blank">' . $bank_invoice_details->LotNo . '</a>';
    }
}


//Ramiz Invoice No Sequence change - 21-02-2023
function get_invoice_no($type)
{
    $ci = &get_instance();
    $ci->load->model('Common');

    if ($type == 'MemberShip' || $type == 'MemberShipUpdate') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, '(Type="MemberShip" OR Type="MemberShipUpdate")', '*', false, false, array('field' => 'InvoiceID', 'order' => 'DESC'));
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    } else if ($type == 'RSD') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, 'Type="RSD"', '*', false, false, array('field' => 'InvoiceID', 'order' => 'DESC'));
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    } else if ($type == 'BuyerFees') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, 'Type="BuyerFees"', '*', false, false, array('field' => 'InvoiceID', 'order' => 'DESC'));
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    }
}

//Ramiz Invoice No Sequence change - 31-05-2023
function get_invoice_no_new($type, $transaction_log_id)
{
    $ci = &get_instance();
    $ci->load->model('Common');
    $month = date('m');
    $year = date('y');
    if ($month > 3) {
        $year = (date('y') + 1);
    } else {
        $year = date('y');
    }
    if ($type == 'MemberShip' || $type == 'MemberShipUpdate') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, '(Type="MemberShip" OR Type="MemberShipUpdate") AND BusinessYear=' . $year, '*', false, false, array('field' => 'SequenceNo', 'order' => 'DESC'));
        // if($transaction_log_id > 2704){
        //     echo $ci->db->last_query();die;
        //     log_message('ERROR',$ci->db->last_query());
        // }
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    } else if ($type == 'RSD') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, 'Type="RSD" AND BusinessYear=' . $year, '*', false, false, array('field' => 'SequenceNo', 'order' => 'DESC'));
        // echo $ci->db->last_query();die;
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    } else if ($type == 'BuyerFees') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, 'Type="BuyerFees" AND BusinessYear=' . $year, '*', false, false, array('field' => 'SequenceNo', 'order' => 'DESC'));
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    } else if ($type == 'RSDForfeit') {
        $latest_invoice_details = $ci->Common->get_info(1, TBL_INVOICE, 1, 'Type="RSDForfeit" AND BusinessYear=' . $year, '*', false, false, array('field' => 'SequenceNo', 'order' => 'DESC'));
        if (!empty($latest_invoice_details) && $latest_invoice_details->SequenceNo > 0) {
            return ($latest_invoice_details->SequenceNo + 1);
        } else {
            return 1;
        }
    }
}


//22-02-2023 Invoice Download Ramiz

function invoice_active_row($InvoiceID)
{
    $ci = &get_instance();
    $ci->load->model('Common');
    $invoice_details = $ci->Common->get_info($InvoiceID, TBL_INVOICE, 'InvoiceID', '', '*');
    if (!empty($invoice_details)) {
        return '<a href="' . BASE_URL . UPLOAD_FRONT_DIR . INVOICE_DIR . $invoice_details->InvoiceURL . '" download><i class="fa fa-download"></i></a>';
    }
    return '';
}


//24-02-2023 Invoice Download Ramiz

function get_yard_team_member_incentive($TemaMemberID, $type = 'incentive')
{
    $ci = &get_instance();
    $ci->load->model('Common');
    if ($type == 'incentive') {
        return $ci->Common->get_info($TemaMemberID, TBL_USER_REFERAL_INCENTIVE, 'ToUserID', 'Type="Dealer" AND isPaid=0', 'IF(SUM(RefAmt) != "",SUM(RefAmt),"--") as TotalAmt')->TotalAmt;
    } else if ($type == 'dealer') {
        return $ci->Common->get_info($TemaMemberID, TBL_USERS, 'SRID', 'StepCompleted=4', 'IF(COUNT(id) != "",COUNT(id),"--") as TotalDealer')->TotalDealer;
    }
    return '';
}


function get_month_alphabet($month = 1)
{
    $array = array(1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L');
    return $array[$month];
}

function subscription_active_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_SUBSCRIPTION_PLAN, 'PlanID', FALSE, 'isActive');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->isActive == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="subscription_plan">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function get_transaction_type($transaction_log_id, $invoice_type)
{
    $ci = &get_instance();
    $transaction_log_details = $ci->Common->get_info($transaction_log_id, TBL_TRANSACTION_LOG, 'TransLogID');
    if (!empty($transaction_log_details)) {
        if ($transaction_log_details->Reference == 'Registration') {
            if ($invoice_type != '') {
                if ($invoice_type != 'MemberShip') {
                    return $invoice_type;
                }
                if ($invoice_type == 'MemberShip') {
                    return 'Membership';
                } else if ($invoice_type == 'MembershipRenewals') {
                    return 'Membership Renewals';
                } else {
                    return $transaction_log_details->Reference;
                }
            } else {
                return $transaction_log_details->Reference;
            }
        } else if ($transaction_log_details->Reference == 'MemberShip') {
            return 'Membership';
        } else if ($transaction_log_details->Reference == 'MembershipRenewals') {
            return 'Membership Renewals';
        }
    }
    return $transaction_log_details->TransType;
}
function get_transaction_type_accounts($transaction_log_id, $invoice_type)
{
    $ci = &get_instance();
    $transaction_log_details = $ci->Common->get_info($transaction_log_id, TBL_TRANSACTION_LOG, 'TransLogID');

    if (!empty($transaction_log_details)) {
        if ($transaction_log_details->Reference == 'Registration') {
            if ($invoice_type != '') {

                if ($invoice_type != 'MemberShip') {
                    return $invoice_type;
                }
                if ($invoice_type == 'MemberShip') {
                    return 'Registration';
                } else if ($invoice_type == 'MembershipRenewals') {
                    return 'Membership Renewals';
                } else {
                    return $transaction_log_details->Reference;
                }
            } else {
                return $transaction_log_details->Reference;
            }
        } else if ($transaction_log_details->Reference == 'MemberShip') {
            return 'Registration';
        } else if ($transaction_log_details->Reference == 'MembershipRenewals') {
            return 'Membership Renewals';
        }
    }
    return $transaction_log_details->TransType;
}
function get_transaction_amount($transaction_log_id, $invoice_type)
{
    $ci = &get_instance();
    $transaction_log_details = $ci->Common->get_info($transaction_log_id, TBL_TRANSACTION_LOG, 'TransLogID');

    if (!empty($transaction_log_details)) {
        if ($transaction_log_details->Reference == 'Registration') {
            if ($invoice_type != '') {
                if (strtolower($invoice_type) != 'membership') {
                    return $transaction_log_details->RSDAmount;
                }
                if (strtolower($invoice_type) == 'membership') {
                    return ($transaction_log_details->Amount - $transaction_log_details->RSDAmount);
                } else {
                    return $transaction_log_details->Amount;
                }
            }
        }
    }
    return $transaction_log_details->Amount;
}

function get_memberhandovername_row($MarketingKitID)
{
    $ci = &get_instance();
    $get_marketing_kit = $ci->Common->get_info($MarketingKitID, TBL_MARKETING_KIT, 'MarketingKitID');
    if (!empty($get_marketing_kit)) {
        if ($get_marketing_kit->YardTeamMemberID > 0) {
            $join =  array(
                array('table' => TBL_YARD_TEAM_MEMBER_TYPE . ' ytmt', 'on' => 'ytm.Type=ytmt.TeamMemberRoleID', 'type' => 'LEFT'),
            );
            $member_details = $ci->Common->get_info($get_marketing_kit->YardTeamMemberID, TBL_YARD_TEAM_MEMBER . ' ytm', 'YardTeamMemberID', '', '*', $join);
            return $member_details->FirstName . ' ' . $member_details->LastName . '(' . $member_details->Code . ')';
        } else {
            return $get_marketing_kit->ManagerName;
        }
    }
    return '';
}
function get_membertype_row($MarketingKitID)
{
    $ci = &get_instance();
    $get_marketing_kit = $ci->Common->get_info($MarketingKitID, TBL_MARKETING_KIT, 'MarketingKitID');
    if (!empty($get_marketing_kit)) {
        if ($get_marketing_kit->YardTeamMemberID > 0) {
            $join =  array(
                array('table' => TBL_YARD_TEAM_MEMBER_TYPE . ' ytmt', 'on' => 'ytm.Type=ytmt.TeamMemberRoleID', 'type' => 'LEFT'),
            );
            $member_details = $ci->Common->get_info($get_marketing_kit->YardTeamMemberID, TBL_YARD_TEAM_MEMBER . ' ytm', 'YardTeamMemberID', '', '*', $join);
            return $member_details->TeamMemberRoleName;
        }
    }
    return 'Manager';
}
function get_marketing_kit_selfie_row($selfie)
{
    $ci = &get_instance();
    $url = BASE_URL . UPLOAD_DIR_NAME . MARKETING_KIT . $selfie;
    return '<a href="' . $url . '" target="_blank">View Selfie</a>';
    // $get_marketing_kit = $ci->Common->get_info($MarketingKitID, TBL_MARKETING_KIT, 'MarketingKitID');
    // if(!empty($get_marketing_kit)){
    //     if($get_marketing_kit->YardTeamMemberID > 0){
    //         $join =  array(
    //             array('table'=>TBL_YARD_TEAM_MEMBER_TYPE.' ytmt','on'=>'ytm.Type=ytmt.TeamMemberRoleID','type'=>'LEFT'),
    //         );
    //         $member_details = $ci->Common->get_info($get_marketing_kit->YardTeamMemberID, TBL_YARD_TEAM_MEMBER . ' ytm', 'YardTeamMemberID','','*',$join);
    //         return $member_details->TeamMemberRoleName;
    //     }
    // }
    // return 'Manager';
}

function get_invoice_type($invoice_type)
{
    if ($invoice_type == 'MemberShip') {
        return 'Membership';
    } else if ($invoice_type == 'MembershipRenewals') {
        return 'Membership Renewals';
    } else if ($invoice_type == 'MemberShipUpdate') {
        return 'Membership Renewals';
    } else if ($invoice_type == 'BuyerFees') {
        return 'Buyer Fees';
    }
    return $invoice_type;
}

function getMonthDates($DateTime)
{
    $start = date("Y-m-01", strtotime($DateTime));
    $end = date("Y-m-t", strtotime($DateTime));
    return array(
        'start' => date('Y-m-d', strtotime($start)),
        'end' => date('Y-m-d', strtotime($end)),
        'title' => $title,
        'start_nix' => strtotime($start),
        'end_nix' => strtotime($end)
    );
}

function getQuarter($DateTime)
{
    $y = $DateTime->format('Y');
    $m = $DateTime->format('m');
    switch ($m) {
        case $m >= 1 && $m <= 3:
            $start = '01/01/' . $y;
            $end = (new DateTime('03/1/' . $y))->modify('Last day of this month')->format('m/d/Y');
            $title = 'Q1 ' . $y;
            break;
        case $m >= 4 && $m <= 6:
            $start = '04/01/' . $y;
            $end = (new DateTime('06/1/' . $y))->modify('Last day of this month')->format('m/d/Y');
            $title = 'Q2 ' . $y;
            break;
        case $m >= 7 && $m <= 9:
            $start = '07/01/' . $y;
            $end = (new DateTime('09/1/' . $y))->modify('Last day of this month')->format('m/d/Y');
            $title = 'Q3 ' . $y;
            break;
        case $m >= 10 && $m <= 12:
            $start = '10/01/' . $y;
            $end = (new DateTime('12/1/' . $y))->modify('Last day of this month')->format('m/d/Y');
            $title = 'Q4 ' . $y;
            break;
    }
    return array(
        'start' => date('Y-m-d', strtotime($start)),
        'end' => date('Y-m-d', strtotime($end)),
        'title' => $title,
        'start_nix' => strtotime($start),
        'end_nix' => strtotime($end)
    );
}

function calculateFYForDate($req_date)
{
    $month = date('m', strtotime($req_date));
    if ($month >= 4) {
        $y = date('Y', strtotime($req_date));
        $pt = $y + 1;
        $start = $y . "-04-01";
        $end = $pt . "-03-31";
    } else {
        $y = date('Y', strtotime($req_date)) - 1;
        $pt = date('Y', strtotime($req_date));
        $start = $y . "-04-01";
        $end = $pt . "-03-31";
    }
    return array(
        'start' => date('Y-m-d', strtotime($start)),
        'end' => date('Y-m-d', strtotime($end)),
        'start_nix' => strtotime($start),
        'end_nix' => strtotime($end)
    );
}

function business_report_state_dealer($StateID, $is_count_rtn = 0, $selected_date = '', $source_type = '')
{
    $ci = &get_instance();
    if ($StateID != 0) {
        $where_con = 'st.StateID="' . $StateID . '"';
    } else {
        $where_con = '1=1';
    }
    if ($source_type != '') {
        if ($source_type == 0) {
            $where_con .= ' and usr.SRID = 0';
        } else {
            $where_con .= ' and usr.SRID > 0';
        }
    }
    // print_r(getQuarter(new DateTime()));
    $request_date = date('Y-m-d', strtotime($selected_date));
    $where_con .= ' and StepCompleted>=2';
    $where_con .= ' and DATE_FORMAT(created,"%Y-%m-%d") <= "' . $request_date . '"';
    // $where_con .= ' and um.SubscriptionPlanID != 0';

    $join = array(
        array('table' => TBL_USER_MEMBERSHIP . ' um', 'on' => 'usr.id=um.UserID', 'type' => 'LEFT'),
        array('table' => TBL_SUBSCRIPTION_PLAN . ' sp', 'on' => 'sp.PlanID=um.SubscriptionPlanID', 'type' => 'LEFT'),
        array('table' => TBL_TRANSACTION_LOG . ' tra', 'on' => 'tra.TransLogID=um.TransLogID', 'type' => 'LEFT'),
        array('table' => TBL_STATE . ' st', 'on' => 'usr.StateID=st.StateID', 'type' => 'LEFT')
    );
    if ($is_count_rtn == 1) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', 'id', $join, 'usr.id');
        // pre($ci->db->last_query());
        return "<a target='_blank' href='" . base_url('sr-business-report/view-details/') . $StateID . "/T/Total/" . base64_encode($request_date) . "/" . $source_type . "'>" . $data . "</a>";
    } else if ($is_count_rtn == 2) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', 'id', $join, 'usr.id');
        return $data;
    } else {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', false, $join, 'usr.id');
        return $data;
    }
}

function business_report_dealer_count($StateID, $type, $is_count_rtn = 0, $selected_date = '', $source_type = '')
{
    $ci = &get_instance();
    if ($StateID != 0) {
        $where_con = 'st.StateID="' . $StateID . '"';
    } else {
        $where_con = '1=1';
    }
    // $where_con .= ' and um.SubscriptionPlanID != 0';
    $where_con .= ' and StepCompleted>=2';
    if ($source_type != '') {
        if ($source_type == 0) {
            $where_con .= ' and usr.SRID = 0';
        } else {
            $where_con .= ' and usr.SRID > 0';
        }
    }
    // print_r(getQuarter(new DateTime()));
    $request_date = date('Y-m-d', strtotime($selected_date));
    $current_req_month = date('Y-m', strtotime($selected_date));
    if ($type == 'FTD') {
        $where_con .= ' and DATE_FORMAT(created,"%Y-%m-%d") = "' . $request_date . '"';
    } else if ($type == 'MTD') {
        $Month = getMonthDates($request_date);
        $where_con .= ' and (DATE_FORMAT(created,"%Y-%m-%d") BETWEEN "' . $Month['start'] . '" AND "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    } else if ($type == 'QTD') {
        $Quarter = getQuarter(new DateTime($request_date));
        // $where_con .= ' and (DATE_FORMAT(created,"%Y-%m-%d") >= "'.$Quarter['start'].'" and DATE_FORMAT(created,"%Y-%m-%d") <= "'.$Quarter['end'].'")';
        $where_con .= ' and (DATE_FORMAT(created,"%Y-%m-%d") BETWEEN "' . $Quarter['start'] . '" AND "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    } else if ($type == 'YTD') {
        $YearDate = calculateFYForDate($request_date);
        // $where_con .= ' and (DATE_FORMAT(created,"%Y-%m-%d") >= "'.$YearDate['start'].'" and DATE_FORMAT(created,"%Y-%m-%d") <= "'.$YearDate['end'].'")';
        $where_con .= ' and (DATE_FORMAT(created,"%Y-%m-%d") BETWEEN "' . $YearDate['start'] . '" AND "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    }

    $join = array(
        array('table' => TBL_USER_MEMBERSHIP . ' um', 'on' => 'usr.id=um.UserID', 'type' => 'LEFT'),
        array('table' => TBL_SUBSCRIPTION_PLAN . ' sp', 'on' => 'sp.PlanID=um.SubscriptionPlanID', 'type' => 'LEFT'),
        array('table' => TBL_TRANSACTION_LOG . ' tra', 'on' => 'tra.TransLogID=um.TransLogID', 'type' => 'LEFT'),
        array('table' => TBL_STATE . ' st', 'on' => 'usr.StateID=st.StateID', 'type' => 'LEFT')
    );
    if ($is_count_rtn == 1) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', 'id', $join, 'usr.id');
        return "<a target='_blank' href='" . base_url('sr-business-report/view-details/') . $StateID . "/D" . "/" . $type . "/" . base64_encode($request_date) . "/" . $source_type . "'>" . $data . "</a>";
    } else if ($is_count_rtn == 2) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', 'id', $join, 'usr.id');
        return $data;
    } else {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', false, $join, 'usr.id');
        return $data;
    }
}

function business_report_subscription_amt($StateID, $type, $is_count_rtn = 0, $selected_date = '', $source_type = '')
{
    $ci = &get_instance();
    if ($StateID != 0) {
        $where_con = 'st.StateID="' . $StateID . '"';
    } else {
        $where_con = '1=1';
    }
    // $where_con .= ' and um.SubscriptionPlanID != 0';
    $where_con .= ' and StepCompleted>=2';
    if ($source_type != '') {
        if ($source_type == 0) {
            $where_con .= ' and usr.SRID = 0';
        } else {
            $where_con .= ' and usr.SRID > 0';
        }
    }
    // print_r(getQuarter(new DateTime()));
    $request_date = date('Y-m-d', strtotime($selected_date));
    $current_req_month = date('Y-m', strtotime($selected_date));
    if ($type == 'FTD') {
        $where_con .= ' and Reference IN ("Registration","MembershipRenewals")';
        $where_con .= ' and PaymentStatus="Paid"';
        $where_con .= ' and DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") = "' . $request_date . '"';
    } else if ($type == 'MTD') {
        $where_con .= ' and Reference IN ("Registration","MembershipRenewals")';
        $where_con .= ' and PaymentStatus="Paid"';
        $Month = getMonthDates($request_date);
        $where_con .= ' and (DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") BETWEEN "' . $Month['start'] . '" AND "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    } else if ($type == 'QTD') {
        $where_con .= ' and Reference IN ("Registration","MembershipRenewals")';
        $where_con .= ' and PaymentStatus="Paid"';
        $Quarter = getQuarter(new DateTime($request_date));
        // $where_con .= ' and (DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") >= "'.$Quarter['start'].'" and DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") <= "'.$Quarter['end'].'")';
        $where_con .= ' and (DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") BETWEEN "' . $Quarter['start'] . '" and "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    } else if ($type == 'YTD') {
        $where_con .= ' and Reference IN ("Registration","MembershipRenewals")';
        $where_con .= ' and PaymentStatus="Paid"';
        $YearDate = calculateFYForDate($request_date);
        // $where_con .= ' and (DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") >= "'.$YearDate['start'].'" and DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") <= "'.$YearDate['end'].'")';
        $where_con .= ' and (DATE_FORMAT(PayphipaymentDateTime,"%Y-%m-%d") BETWEEN "' . $YearDate['start'] . '" and "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    }
    $join = array(
        array('table' => TBL_USERS . ' usr', 'on' => 'usr.id=tra.UserID', 'type' => 'LEFT'),
        array('table' => TBL_USER_MEMBERSHIP . ' um', 'on' => 'tra.UserID=um.UserID', 'type' => 'LEFT'),
        array('table' => TBL_SUBSCRIPTION_PLAN . ' sp', 'on' => 'sp.PlanID=tra.PlanID', 'type' => 'LEFT'),
        array('table' => TBL_STATE . ' st', 'on' => 'usr.StateID=st.StateID', 'type' => 'LEFT')
    );
    if ($is_count_rtn == 1) {
        // $data = $ci->Common->get_info(1,TBL_TRANSACTION_LOG.' tra',1,$where_con,'DISTINCT(tra.TransLogID),IF((SUM(Amount) - SUM(RSDAmount)) !="",(SUM(Amount) - SUM(RSDAmount)),"0.00") as Total',$join);
        $data = $ci->Common->get_info_with_sub_query(1, TBL_TRANSACTION_LOG . ' tra', 1, $where_con, 'DISTINCT(tra.TransLogID),IF((Amount - RSDAmount) !="",(Amount - RSDAmount),0) as Amount', 'IF(SUM(x.Amount) !="",SUM(x.Amount),0) AS TotalAmount', $join);

        // $Total = 0;
        // foreach ($data as $key => $value) {
        //     $Total += $value->Amount;
        // }
        // if($type == 'YTD'){
        //     print_r($ci->db->last_query());die;
        // }
        return "<a target='_blank' href='" . base_url('sr-business-report/view-details/') . $StateID . "/S" . "/" . $type . "/" . base64_encode($request_date) . "/" . $source_type . "'>" . $data->TotalAmount . "</a>";
    } else if ($is_count_rtn == 2) {
        // $data = $ci->Common->get_info(1,TBL_TRANSACTION_LOG.' tra',1,$where_con,'DISTINCT(tra.TransLogID),IF((SUM(Amount) - SUM(RSDAmount)) !="",(SUM(Amount) - SUM(RSDAmount)),"0.00") as Total',$join);
        $data = $ci->Common->get_info_with_sub_query(1, TBL_TRANSACTION_LOG . ' tra', 1, $where_con, 'DISTINCT(tra.TransLogID),IF((Amount - RSDAmount) !="",(Amount - RSDAmount),0) as Amount', 'IF(SUM(x.Amount) !="",SUM(x.Amount),0) AS TotalAmount', $join);
        return $data->TotalAmount;
    } else {
        $data = $ci->Common->get_all_info(1, TBL_TRANSACTION_LOG . ' tra', 1, $where_con, 'DISTINCT(tra.TransLogID) as TransLogID,first_name,last_name,Code,email,StateName,created,id,StartDate,EndDate,Amount,RSDAmount,PayphipaymentDateTime,TransactionID,TransType,Reference,IF(PlanName != "", `PlanName`, "Free") AS PlanName', false, $join);
        return $data;
    }
}

function business_report_expired_dealer_count($StateID, $type, $is_count_rtn = 0, $selected_date = '', $source_type = '')
{
    $ci = &get_instance();
    if ($StateID != 0) {
        $where_con = 'st.StateID="' . $StateID . '"';
    } else {
        $where_con = '1=1';
    }
    // $where_con .= ' and um.SubscriptionPlanID != 0';
    $where_con .= ' and StepCompleted>=2';
    if ($source_type != '') {
        if ($source_type == 0) {
            $where_con .= ' and usr.SRID = 0';
        } else {
            $where_con .= ' and usr.SRID > 0';
        }
    }
    // print_r(getQuarter(new DateTime()));
    $today_date = date('Y-m-d');
    $request_date = date('Y-m-d', strtotime($selected_date));
    $current_req_month = date('Y-m', strtotime($selected_date));
    $Quarter = getQuarter(new DateTime($request_date));
    $YearDate = calculateFYForDate($request_date);

    $today_nix = strtotime($today_date);
    $request_nix = strtotime($request_date);

    if ($type == 'FTD') {
        $where_con .= ' and DATE_FORMAT(EndDate,"%Y-%m-%d") = "' . $request_date . '"';
    } else if ($type == 'MTD') {
        $Month = getMonthDates($request_date);
        $where_con .= ' and (DATE_FORMAT(EndDate,"%Y-%m-%d") BETWEEN "' . $Month['start'] . '" AND "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
        // die;
    } else if ($type == 'QTD') {
        // $where_con .= ' and (DATE_FORMAT(EndDate,"%Y-%m-%d") >= "'.$Quarter['start'].'" and DATE_FORMAT(EndDate,"%Y-%m-%d") < "'.$request_date.'")';
        $where_con .= ' and (DATE_FORMAT(EndDate,"%Y-%m-%d") BETWEEN "' . $Quarter['start'] . '" and "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
    } else if ($type == 'YTD') {
        // $where_con .= ' and (DATE_FORMAT(EndDate,"%Y-%m-%d") >= "'.$YearDate['start'].'" and DATE_FORMAT(EndDate,"%Y-%m-%d") < "'.$request_date.'")';
        $where_con .= ' and (DATE_FORMAT(EndDate,"%Y-%m-%d") BETWEEN "' . $YearDate['start'] . '" and "' . $request_date . '")';
        // echo $where_con;
        // echo '<br />';
        // die;
    }

    $join = array(
        array('table' => TBL_USER_MEMBERSHIP . ' um', 'on' => 'usr.id=um.UserID', 'type' => 'LEFT'),
        array('table' => TBL_SUBSCRIPTION_PLAN . ' sp', 'on' => 'sp.PlanID=um.SubscriptionPlanID', 'type' => 'LEFT'),
        array('table' => TBL_TRANSACTION_LOG . ' tra', 'on' => 'tra.TransLogID=um.TransLogID', 'type' => 'LEFT'),
        array('table' => TBL_STATE . ' st', 'on' => 'usr.StateID=st.StateID', 'type' => 'LEFT')
    );

    if ($is_count_rtn == 1) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*', 'id', $join, 'usr.id');
        return "<a target='_blank' href='" . base_url('sr-business-report/view-details/') . $StateID . "/E" . "/" . $type . "/" . base64_encode($request_date) . "/" . $source_type . "'>" . $data . "</a>";
    } else if ($is_count_rtn == 2) {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*', 'id', $join, 'usr.id');
        return $data;
    } else {
        $data = $ci->Common->get_all_info(1, TBL_USERS . ' usr', 1, $where_con, '*,IF(PlanName != "",PlanName,"Free") as PlanName', false, $join, 'usr.id');
        // pre($data);
        return $data;
    }
}

function pre($data)
{
    echo "<pre>";
    print_r($data);
    die;
}

function referal_code_link($id)
{
    $ci = &get_instance();
    $data = $ci->Common->get_info($id, TBL_SALES_REPRESENTATIVE, 'SRID', '', 'SalesCode');
    $link = BASE_URL . 'register?code=' . base64_encode($data->SalesCode);
    return $link;
}

if (!function_exists('_insert_order_items')) {
    function _insert_order_items($order_id)
    {
        $ci = &get_instance();

        // Make sure Common model is loaded
        if (!isset($ci->Common)) {
            $ci->load->model('Common');
        }

        $item_ids = $ci->input->post('item_id');
        $qtys = $ci->input->post('item_qty');

        if (!is_array($item_ids) || !is_array($qtys)) {
            return;
        }

        foreach ($item_ids as $index => $item_id) {
            $qty = $qtys[$index];

            $item = $ci->Common->get_info($item_id, 'tbl_item', 'item_id');
            $price = $item ? $item->selling_price : 0;
            $amount = $qty * $price;

            $ci->Common->add_info('tbl_order_dtl', array(
                'order_hdr_id' => $order_id,
                'item_id'      => $item_id,
                'qty'          => $qty,
                'amount'       => $amount,
                'created_on'   => date("Y-m-d H:i:s"),
                'modified_on'  => date("Y-m-d H:i:s"),
            ));
        }
    }
}

if (!function_exists('_insert_purchase_items')) {
    function _insert_purchase_items($order_id)
    {
        $ci = &get_instance();

        // Make sure Common model is loaded
        if (!isset($ci->Common)) {
            $ci->load->model('Common');
        }

        $item_ids = $ci->input->post('item_id');
        $qtys = $ci->input->post('item_qty');

        if (!is_array($item_ids) || !is_array($qtys)) {
            return;
        }

        foreach ($item_ids as $index => $item_id) {
            $qty = $qtys[$index];

            $item = $ci->Common->get_info($item_id, 'tbl_item', 'item_id');
            $price = $item ? $item->selling_price : 0;
            $amount = $qty * $price;
            $purchase_price_per_kg = $item ? ($item->selling_price / $item->factor) : 0;
            $qty_kg = $item ? ($qty * $item->factor) : 0;

            $ci->Common->add_info('tbl_purchase_dtl', array(
                'purchase_hdr_id' => $order_id,
                'item_id'      => $item_id,
                'qty'          => $qty,
                'pkt_price'          => $price,
                'qty_kg'          => $qty_kg,
                'purchase_price_per_kg' => $purchase_price_per_kg,
                'total_amount' => $amount,
                'created_on'   => date("Y-m-d H:i:s"),
                'modified_on'  => date("Y-m-d H:i:s"),
            ));
        }
    }
}

function order_item($order_id, $items)
{
    $ci = &get_instance();
    // Make sure Common model is loaded
    if (!isset($ci->Common)) {
        $ci->load->model('Common');
    }
   
    foreach ($items as $item) {
        $item_id = $item['item_id'];
        $qty = $item['qty'];
        
        $item_details = $ci->Common->get_info($item_id, 'tbl_item', 'item_id');
        $qty_kg = $item_details ? ($qty * $item_details->factor) : 0;
        // Get available stock in packet
        $stock = $ci->db->select("(total_purchase_qty_pkt - total_sells_qty_pkt) AS available_pkt", false)
            ->from("tbl_item_stock")
            ->where("item_id", $item_id)
            ->get()
            ->row();

        if ($stock && $stock->available_pkt < $qty && $item_details->reorder == 0) {
            throw new Exception("Item ID $item_id has only {$stock->available_pkt} packets left.");
        }

        // Insert to tbl_order_dtl as usual
        $ci->db->insert('tbl_order_dtl', [
            'order_hdr_id' => $order_id,
            'item_id' => $item_id,
            'qty' => $qty,
            'amount' => $item['amount'],
            'created_on' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        ]);

        // Update stock (increment sells)
        $ci->db->query("
    UPDATE tbl_item_stock s
    JOIN tbl_item i ON s.item_id = i.item_id
    SET s.total_sells_qty_pkt = s.total_sells_qty_pkt + ?,
        s.total_sells_qty_kg = s.total_sells_qty_kg + (? * i.factor)
    WHERE s.item_id = ?
", [$qty, $qty, $item_id]);
    }
}

function purchase_item($purchase_id, $items)
{
    $ci = &get_instance();
     // Make sure Common model is loaded
        if (!isset($ci->Common)) {
            $ci->load->model('Common');
        }
    foreach ($items as $item) {
       
        $item_id = $item['item_id'];
        $qty_kg = $item['qty_kg'];
        $item_mst = $ci->Common->get_info($item_id, 'tbl_item', 'item_id');
        // $qty_kg = $item_mst ? ($qty * $item_mst->factor) : 0;
        $qty = ($qty_kg / $item_mst->factor);
        //  print_r([
        //     'purchase_hdr_id' => $purchase_id,
        //     'item_id' => $item_id,
        //     'qty' => $qty,
        //     'pkt_price' => $item_mst->selling_price,
        //     'qty_kg' => $qty_kg,
        //     'total_amount' => $item['total_amount'],
        //     'purchase_price_per_kg' => $item['purchase_price_per_kg'],
        //     'created_on' => date('Y-m-d H:i:s'),
        //     'modified_on' => date('Y-m-d H:i:s')
        // ]);die;
        $ci->db->insert('tbl_purchase_dtl', [
            'purchase_hdr_id' => $purchase_id,
            'item_id' => $item_id,
            'qty' => $qty,
            'qty_kg' => $qty_kg,
            'pkt_price' => 0,
            'total_amount' => $item['total_amount'],
            'purchase_price_per_kg' => $item['item_price'],
            'created_on' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        ]);

        // Update stock (increment purchase)
        $ci->db->query("
            UPDATE tbl_item_stock s
            JOIN tbl_item i ON s.item_id = i.item_id
            SET total_purchase_qty_pkt = total_purchase_qty_pkt + ?, 
                 s.total_purchase_qty_kg = s.total_purchase_qty_kg + (? * i.factor)
            WHERE s.item_id = ?
        ", [$qty, $qty, $item_id]);
    }
}


// Add this function to your controller or create a helper
function convert_number_to_words($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred
                : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else {
            $str[] = null;
        }
    }

    $rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($rupees ? $rupees . 'Rupees' : '') . $paise;
}

function order_paid_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_ORDER_HDR, 'order_hdr_id', FALSE, 'is_paid');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->is_paid == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="orders" data-method="paid">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}

function order_delivered_row($id)
{

    $ci = &get_instance();
    $action = "";
    $IsActive = $ci->Common->get_info($id, TBL_ORDER_HDR, 'order_hdr_id', FALSE, 'id_delivered');
    //    echo $ci->db->last_query();
    //    die;
    if ($IsActive) {
        if ($IsActive->id_delivered == 1) {
            $st = "checked";
        } else {
            $st = "";
        }
        $action = <<<EOF
            <div class="tooltip-top">
                <label class="switch banner_feature" data-id="{$id}" data-control="orders" data-method="delivered">
                    <input type="checkbox" {$st}>
                    <span class="slider round"></span>
                </label>
            </div>
EOF;
    }
    return $action;
}
