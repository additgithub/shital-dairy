<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model extends CI_Controller {

    public $table_name = TBL_M_MODEL;
    public $controllers = 'model';
    public $view_name = 'model';
    public $title = 'Model';
    public $PrimaryKey = 'ModelID';

    function __construct() {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('auth/login/');
        } else if ($this->tank_auth->get_user_role_id() != '1' && $this->tank_auth->get_user_id() != 1544 && $this->tank_auth->get_user_id() != 1041 && $this->tank_auth->get_user_id() != 2049 && $this->uri->segment('2') != 'export-records') {
            redirect('/');
        }
    }

    function index() {
        $data['page_title'] = "Manage " . $this->title;
        $data['main_content'] = $this->view_name . '/list';
        $this->load->view('main_content', $data);
    }

    function add() {
        $data["MakeName"] = $this->Common->get_list(TBL_M_MAKE, 'MakeID ', 'MakeName');
        //$data["MakeID"] = array();
        $data['page_title'] = "Add New " . $this->title;
        // print_r($data); die;
        $this->load->view($this->view_name . '/form', $data);
    }

    function edit($id) {

        $data_found = 0;
        if ($id > 0) {
            $data_obj = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey);
            if ($data_obj->MakeID != 0)
                $data["MakeName"] = $this->Common->get_list(TBL_M_MAKE, 'MakeID ', 'MakeName');
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

    function submit_form() {
        if ($this->input->post()) {

            $response = array("status" => "error", "heading" => "Unknown Error", "message" => "There was an unknown error that occurred. You will need to refresh the page to continue working.");
            $error_element = error_elements();
            $this->form_validation
                    ->set_rules('ModelName', 'Model Name', 'required')
                    ->set_rules('MakeName', 'Make Name', 'required')
                    ->set_error_delimiters($error_element[0], $error_element[1]);

            if ($this->form_validation->run()) {

                $id = ($this->input->post($this->PrimaryKey) && $this->input->post($this->PrimaryKey) > 0) ? $this->input->post($this->PrimaryKey) : 0;

                $post_data = array(
                    "ModelName" => $this->input->post('ModelName'),
                    "MakeID" => $this->input->post('MakeName'),
                    "isActive" => 1,
                );

                if ($id > 0):
                    $post_data['ModifiedBy'] = $this->tank_auth->get_user_id();
                    $post_data['ModifiedOn'] = date("Y-m-d H:i:s");
                    if ($this->Common->update_info($id, $this->table_name, $post_data, $this->PrimaryKey)):
                        $response = array("status" => "ok", "heading" => "Updated successfully...", "message" => "Details updated successfully.");
                    else:
                        $response = array("status" => "error", "heading" => "Not Updated...", "message" => "Details not updated successfully.");
                    endif;
                else:
                    $post_data['CreatedOn'] = date("Y-m-d H:i:s");
                    $post_data['CreatedBy'] = $this->tank_auth->get_user_id();
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

    function activated($id) {
        if ($id > 0) {
            $IsFeatured = $this->Common->get_info($id, $this->table_name, $this->PrimaryKey, FALSE, 'isActive');
            if ($IsFeatured->isActive == 0) {
                $activated = 1;
                $status = "ok";
                $heading = "Success";
                $message = "Model Activated";
            } else {
                $activated = 0;
                $status = "ok";
                $heading = "Success";
                $message = "Model Deactivated";
            }
            $data = array(
                "isActive" => $activated,
            );

            if ($this->Common->update_info($id, $this->table_name, $data, $this->PrimaryKey)) {
                $response = array("status" => $status, "heading" => $heading, "message" => $message);
                echo json_encode($response);
                die;
            }
        }
    }

    function export_records()
    {
        $this->load->library('PHPExcel');
        $this->datatables->join(TBL_M_MAKE . ' mk', 'md.MakeID=mk.MakeID', 'LEFT');
        $this->datatables->join(TBL_VEHICLE_TYPE . ' vt', 'mk.VehicleTypeID=vt.VehicalTypeID', 'LEFT');

        $join = array(
            array('table' => TBL_M_MAKE . ' mk', 'on' => 'md.MakeID=mk.MakeID', 'type' => 'LEFT'),
            array('table' => TBL_VEHICLE_TYPE . ' vt', 'on' => 'mk.VehicleTypeID=vt.VehicalTypeID', 'type' => 'LEFT'),
        );
       
        $vehicle_types = $this->Common->get_all_info(1, TBL_M_MODEL . ' md', 1, '', 'mk.MakeName,md.ModelName,vt.VehicalType');
        
        
        if (!empty($vehicle_types)) {
            // create new PHPExcel object
            $objPHPExcel = new PHPExcel;
            // set default font
            $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
            // set default font size
            $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
            // create the writer
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

            /**
             * Define currency and number format.
             */
            // currency format, € with < 0 being in red color
            //                $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
            // number format, with thousands separator and two decimal points.
            //                $numberFormat = '#,#0.##;[Red]-#,#0.##';
            // writer already created the first sheet for us, let's get it
            $objSheet = $objPHPExcel->getActiveSheet();
            // rename the sheet
            $objSheet->setTitle('Model List');

            // let's bold and size the header font and write the header
            // as you can see, we can specify a range of cells, like here: cells from A1 to A4
            $objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(11);

            // write header
            $objSheet->getCell('A1')->setValue('SR. No');
            $objSheet->getCell('B1')->setValue('Vehical Type');
            $objSheet->getCell('C1')->setValue('Make Name');
            $objSheet->getCell('D1')->setValue('Model Name');
           
            $i = 2;
            foreach ($vehicle_types as $single) {
                $objSheet->setCellValueExplicit('A' . $i, ($i-1), PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('B' . $i, $single->VehicalType, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('C' . $i, $single->MakeName, PHPExcel_Cell_DataType::TYPE_STRING);
                $objSheet->setCellValueExplicit('D' . $i, $single->ModelName, PHPExcel_Cell_DataType::TYPE_STRING);
                $i++;
            }

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            //                $objSheet->getColumnDimension('A3')->setAutoSize(true);
            // write the file
            $fileName = 'Model_list_' . date('d-m-Y') . '.xlsx';
            //        $objWriter->save('test.xlsx');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {
            redirect('');
        }
    }
    function manage() {

        $this->datatables->select('md.' . $this->PrimaryKey . ', mk.MakeName,md.ModelName,vt.VehicalType, md.isActive');
        $this->datatables->join(TBL_M_MAKE . ' mk', 'md.MakeID=mk.MakeID', 'LEFT');
        $this->datatables->join(TBL_VEHICLE_TYPE . ' vt', 'mk.VehicleTypeID=vt.VehicalTypeID', 'LEFT');
        $this->datatables->from($this->table_name . ' md')
                ->edit_column('md.isActive', '$1', 'model_active_row(md.' . $this->PrimaryKey . ')')
                ->add_column('action', $this->action_row('$1'), 'md.' . $this->PrimaryKey);
        $this->datatables->unset_column('md.' . $this->PrimaryKey);
//        $this->datatables->unset_column('md.');
        echo $this->datatables->generate();
    }

    function MakeNameList() {
        $MakeID = $this->input->post('MakeID');
        //print_r($MakeID);
        $GetCat = $this->Common->get_list(TBL_M_MAKE, "MakeID", "MakeName");
        // echo $this->db->last_query();die;
        if (!empty($GetCat)) {
            $response = array("status" => "ok", "data" => $GetCat);
        } else {
            $response = array("status" => "error");
        }
        echo json_encode($response);
        die;
    }

    function show_image($image) {
        $url = UPLOAD_DIR . BANNER_DIR . $image;
        $defaultimage = DEFAULT_NO_EMAGE;
        $image = '<img src="' . $url . '" width="80px" height="60px" onerror="this.onerror=null;this.src=\'' . $defaultimage . '\';">';

        return $image;
    }

    function action_row($id) {
        $action = <<<EOF
            <div class="tooltip-top">
                <a data-original-title="Edit {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal btn-mini open_my_form_form" data-id="{$id}" data-control="{$this->controllers}"><i class="fa fa-pencil"></i></a>
                <a data-original-title="Remove {$this->title}" data-placement="top" data-toggle="tooltip" href="javascript:;" class="btn btn-xs btn-default btn-equal delete_btn btn-mini" data-id="{$id}" data-method="{$this->controllers}"><i class="fa fa-trash-o"></i></a>
            </div>
EOF;
        return $action;
    }

}
