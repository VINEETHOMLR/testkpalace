<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\LeaveRequest;
use src\models\Leave;
use inc\Root;
use inc\commonArrays;
use src\models\User;
use src\models\Stock;
use src\models\Departments;
use src\models\Positions;

/**
 * To handle the users data models
 * @author
 */

class LeaveRequestController extends Controller
{
    /**
     *
     * @return Mixed

     */
    public function __construct()
    {
        parent::__construct();

        $this->mdl              = (new LeaveRequest());
        $this->leavemdl         = (new Leave());
        $this->usermdl          = (new User());
        $this->stockmdl         = (new Stock());
        $this->departmentsmdl   = (new Departments());
        $this->positionmdl      = (new Positions());
        $this->pag          =  new Pagination(new LeaveRequest(), '');
        $this->adminID      = $_SESSION[SITENAME . '_admin'];

        $arr                = commonArrays::getArrays();
        $this->userArr       = $this->systemArrays['userStatusArr'];






    }

    public function actionIndex()
    {


        $this->checkPageAccess(82);
        $username       = $this->cleanMe(Router::post('username'));
        $leave_id       = $this->cleanMe(Router::post('leave_id'));
        $role_id        = $this->cleanMe(Router::post('role_id'));
        $department     = $this->cleanMe(Router::post('department'));
        $status         = $this->cleanMe(Router::post('status'));
        $leave_date     = $this->cleanMe(Router::post('leave_date'));
        $leave_to_date  = $this->cleanMe(Router::post('leave_to_date'));
        $leave_taken    = $this->cleanMe(Router::post('leave_taken'));
        $page           = $this->cleanMe(Router::post('page'));
        $page           = (!empty($page)) ? $page : '1';



        $filter = ["username"   => $username,
                "leave_id"    => $leave_id,
                "status"      => $status,
                "leave_date"  => $leave_date,
                "leave_to_date" => $leave_to_date,
                "role_id"      => $role_id,
                "department"    => $department,
                "leave_taken"   => $leave_taken,
                "page"          => $page];

        $data = $this->mdl->getList($filter);

        $data['departments'] = $this->departmentsmdl->getActiveDepartments();

        $data_leave_type = $this->leavemdl->getAllLeaveType();
        $user_id = '';
        $user_name = '';
        if(!empty($filter['username'])) {
            $user_id    = $filter['username'];
            $filter['username']    = $this->usermdl->getUsername($filter['username']);
            $user_name = $filter['username'];

        }

        $onclick    = "onclick=pageHistory('" . $username . "','" . $leave_id . "','" . $status . "','" . $leave_date . "','" . $leave_taken . "','" . $role_id . "','" . $department . "','" . $leave_to_date . "','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'], $data['count'], $data['perPage'], 1, $onclick, 'pagestring');


        return $this->render('leave_request/index', ['filter' => $filter,'user_id' => $user_id,'username' => $user_name , 'data' => $data,'data_leave_type' => $data_leave_type, 'pagination' => $pagination]);

    }

    public function actionCreate()
    {

        $this->checkPageAccess(88);
        $data_leave_type = $this->leavemdl->getAllLeaveType();
        $this->render('leave_request/create', ['types' => $data_leave_type]);


    }

    public function actionGetUsers()
    {
        //$this->checkPageAccess(11);
        $term = $this->cleanMe(Router::req('term'));

        if(!empty($term)) {

            $userlist = $this->usermdl->getEmailById($term);
            echo  $userList = json_encode($userlist);
        }
    }

    public function actionEdit()
    {

        $id   = $this->cleanMe(Router::post('id'));

        $filter = ["id"     => $id];

        $data = $this->mdl->getDetails($filter);

        return $this->sendMessage("success", $data, 'success');
        die();


    }

    public function actionApplyLeave()
    {

        $user_id        = !empty(Router::post('user_id')) ? $this->cleanMe(Router::post('user_id')) : '';
        $leave_id       = !empty(Router::post('leave_id')) ? $this->cleanMe(Router::post('leave_id')) : '';
        $leave_type     = !empty(Router::post('leave_type')) ? $this->cleanMe(Router::post('leave_type')) : '';
        $date_from      = !empty(Router::post('date_from')) ? $this->cleanMe(Router::post('date_from')) : '';
        $date_to        = !empty(Router::post('date_to')) ? $this->cleanMe(Router::post('date_to')) : '';
        $reason         = !empty(Router::post('reason')) ? $this->cleanMe(Router::post('reason')) : '';
        $id             = !empty(Router::post('id')) ? $this->cleanMe(Router::post('id')) : '';
        $status         = !empty(Router::post('status')) ? $this->cleanMe(Router::post('status')) : '0';
        $newFile_org = '';
        if(!empty($id)) {

            $details = $this->mdl->getRequestDetails($id);

            if($details['status'] != '0') {

                return $this->sendMessage('error', "Status already changed.You cannot edit this request");
                die();

            }


            $newFile_org = $details['upload_file'];

        }

        if(empty($user_id)) {

            return $this->sendMessage('error', "Please select a user to proceed");
            die();

        }
        if(empty($leave_id)) {

            return $this->sendMessage('error', "Please select a leave to proceed");
            die();

        }

        $leaveDetails = $this->leavemdl->getDetails($leave_id);


        if(empty($leaveDetails)) {


            return $this->sendMessage('error', "Please select a valid leave to proceed");
            die();

        }
        if(empty($leaveDetails) && $leaveDetails['status'] != '0') {


            return $this->sendMessage('error', "Please select a valid leave to proceed");
            die();

        }
        if(empty($leave_type)) {

            return $this->sendMessage('error', "Please select a type to proceed");
            die();

        }
        if(empty($date_from)) {

            return $this->sendMessage('error', "Please select a from date to proceed");
            die();

        }

        // if($leave_type == '2') {

        //     $date_to = $date_from;

        // }
        if(empty($date_to)) {

            return $this->sendMessage('error', "Please select a to date to proceed");
            die();

        }
        if($leave_type == '2' && ($date_to != $date_from)) { //half day

            return $this->sendMessage('error', "From Date & To Date should be same");
            die();

        }



        if(!empty($date_from) && !empty($date_to)) {

            $date_from_converted = str_replace('/', '-', $date_from);
            $date_from_converted = date('Y-m-d', strtotime($date_from_converted));

            $date_to_converted = date('Y-m-d', strtotime($date_to));
            $date_to_converted = date('Y-m-d', strtotime($date_to_converted));

            $date_from_converted = strtotime($date_from_converted);
            $date_to_converted   = strtotime($date_to_converted);

            if($date_to_converted < $date_from_converted) {


                return $this->sendMessage('error', "From Date should not be greater thanTo Date");
                die();

            }

        }

        if(empty($reason)) {

            return $this->sendMessage('error', "Please enter reason to proceed");
            die();

        }

        $files =  !empty($_FILES['file']) ? $_FILES['file'] : '';
        $maxsize      = 8388608;
        // print_r($files);exit;
        if(!empty($files)) {

            $acceptable = array('image/jpeg','image/jpg','image/png');

            if((!in_array($files['type'], $acceptable)) && (!empty($files["type"]))) {
                return $this->sendMessage("error", "Invalid File Only JPEG/SVG/PNG type accepted");
            }

            if($files['size'] == 0) {
                return $this->sendMessage("error", "Invalid File Only JPEG/SVG/PNG type accepted");
            }

            if($files['size'] > $maxsize) {
                return $this->sendMessage("error", "File size exceeds maximum limit 8 MB");
            }

            $filename    = $files['name'];
            $temp_name   = $files['tmp_name'];
            $path_parts  = pathinfo($filename);
            $extension   = $path_parts['extension'];
            $newFile_org = 'leave_request_' . $user_id . time() . '.' . $extension;
            $target_file = FILEUPLOADPATH . 'leave/' . $newFile_org;

            if(!move_uploaded_file($temp_name, $target_file)) {

                return $this->sendMessage("error", 'File Upload Failed');
                die();
            }




        }



        $date_from = str_replace('/', '-', $date_from);
        $date_from = date('Y-m-d', strtotime($date_from));

        $date_to = date('Y-m-d', strtotime($date_to));
        $date_to = date('Y-m-d', strtotime($date_to));

        $date_from_converted = strtotime($date_from);
        $date_to_converted   = strtotime($date_to);

        $params = [];
        $params['user_id']    = $user_id;
        $params['leave_id']   = $leave_id;
        $params['leave_type'] = $leave_type;
        $params['date_from']  = $date_from_converted;
        $params['date_to']    = $date_to_converted;

        $canApply = $this->mdl->checkCanApply($params);
        if(!$canApply) {

            return $this->sendMessage('error', "Sorry!You have no sufficient balance to apply this leave");
            die();

        }



        $params = [];
        $params['user_id']     = $user_id;
        $params['leave_id']    = $leave_id;
        $params['leave_type']  = $leave_type;
        $params['date_from']   = $date_from_converted;
        $params['date_to']     = $date_to_converted;
        $params['reason']      = $reason;
        $params['status']      = $status;
        $params['upload_file'] = $newFile_org;
        if(empty($id)) {

            if($this->mdl->applyLeave($params)) {

                return $this->sendMessage('success', "Successfully applied");
                die();


            } else {

                return $this->sendMessage('error', "Failed to apply leave");
                die();

            }

        } else {

            $params['id']     = $id;

            if($this->mdl->updateLeaveRequest($params)) {

                return $this->sendMessage('success', "Successfully updated");
                die();


            } else {

                return $this->sendMessage('error', "Failed to update request");
                die();

            }


        }







    }
    public function actionUpdateLeaveStatus()
    {



        $id = $this->cleanMe(Router::post('id'));
        $status = $this->cleanMe(Router::post('status'));
        $remark   = $this->cleanMe(Router::post('remark'));

        if(empty($id)) {
            return $this->sendMessage('error', "Please Choose Valid Entry");
        }
        if(empty($status)) {
            return $this->sendMessage('error', "Please Choose Status");
        }

        $check_is_processed = $this->mdl->isLeaveRequestproceed($id);
        if($check_is_processed) {
            return $this->sendMessage('error', "Leave Request Already Processed");
        }


        //check balance
        $requestDetails = $this->mdl->getRequestDetails($id);

        $params = [];
        $params['user_id']    = $requestDetails['user_id'];
        $params['leave_id']   = $requestDetails['leave_id'];
        $params['leave_type'] = $requestDetails['leave_type'];
        $params['date_from']  = $requestDetails['date_from'];
        $params['date_to']    = $requestDetails['date_to'];

        $canApply = $this->mdl->checkCanApply($params);
        if(!$canApply) {

            return $this->sendMessage('error', "Sorry no sufficient leave balance");
            die();

        }

        $username = $this->mdl->getLeaveRequestById($id);

        $params = [];
        $params['id'] = $id;
        $params['status'] = $status;
        $params['remark'] = $remark;
        $params['username'] = $username;

        $success = $this->mdl->UpdateLeaveStatus($params);

        $this->statusArray     = ['0' => 'Requested','1' => 'Approved','2' => 'Rejected','3' => 'Cancelled'];

        $msg     = 'Leave Request ' . $this->statusArray[$status] . ' Successfully';

        if($success) {

            $this->sendMessage('success', $msg);
            return false;
        } else {
            return $this->sendMessage("error", "Something Went Wrong..Please try again..");
        }




    }


    public function actionUpdateDetails()
    {


        $id = $this->cleanMe(Router::post('id'));

        $leave_id     = $this->cleanMe(Router::post('leave_id'));
        $leave_date   = $this->cleanMe(Router::post('leave_date'));
        $leave_date_to = $this->cleanMe(Router::post('leave_date_to'));
        $leave_type   = $this->cleanMe(Router::post('leave_type'));
        $reason   = $this->cleanMe(Router::post('reason'));
        $remark   = $this->cleanMe(Router::post('remark'));

        if(empty($id)) {
            return $this->sendMessage('error', "Please Choose Valid Entry");
        }
        if(empty($leave_id)) {
            return $this->sendMessage('error', "Please Enter Leave Type ");
        }
        if(empty($leave_date)) {
            return $this->sendMessage('error', "Please Enter Leave From Date");
        }
        if(empty($leave_date_to)) {
            return $this->sendMessage('error', "Please Enter Leave To Date");
        }
        if(empty($leave_type)) {
            return $this->sendMessage('error', "Please Enter Leave Type(Half/Full Day)");
        }
        if(empty($reason)) {
            return $this->sendMessage('error', "Please Enter Reason");
        }
        if(empty($remark)) {
            return $this->sendMessage('error', "Please Enter Remark");
        }
        $newFile_org = '';
        if(!empty($_FILES['file'])) {
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            //$file_array = array('pdf','doc','docx','DOCX','PDF','DOC');
            $file_array = array('png','jpeg','jpg');

            if(!in_array($extension, $file_array)) {

                return $this->sendMessage('error', "Please Upload Valid File");
            }

            $newFile_org = 'LeaveRequest_' . time() . '.' . $extension;
            //$target_file = BASEPATH."web/upload/doc_leaverequest/".$newFile_org;
            $target_file = FILEUPLOADPATH . "web/upload/leave/" . $newFile_org;
            $FileType    = pathinfo($target_file, PATHINFO_EXTENSION);
            $path        = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }


            if(!move_uploaded_file($temp_name, $target_file)) {
                return $this->sendMessage('error', "Something Went Wrong Please try again.");
            }




        }

        $username = $this->mdl->getLeaveRequestById($id);

        $params = [];
        $params['id']         = $id;
        $params['leave_id']   = $leave_id;
        $params['leave_date'] = $leave_date;
        $params['leave_date_to'] = $leave_date_to;
        $params['leave_type'] = $leave_type;
        $params['reason']     = $reason;
        $params['upload_file'] = $newFile_org;
        $params['remark']     = $remark;
        $params['username']   = $username;

        $success = $this->mdl->updateDetails($params);

        $msg     = 'Details Updated Successfully';

        if($success) {

            $this->sendMessage('success', $msg);
            return false;
        } else {
            return $this->sendMessage("error", "Something Went Wrong..Please try again..");
        }

    }


    public function actionExport()
    {

        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Leave Request';

        $username       = $this->cleanMe(Router::post('username'));
        $leave_id       = $this->cleanMe(Router::post('leave_id'));
        $role_id        = $this->cleanMe(Router::post('role_id'));
        $department     = $this->cleanMe(Router::post('department'));
        $status         = $this->cleanMe(Router::post('status'));
        $leave_date     = $this->cleanMe(Router::post('leave_date'));
        $leave_to_date  = $this->cleanMe(Router::post('leave_to_date'));
        $leave_taken    = $this->cleanMe(Router::post('leave_taken'));


        $filter = ["username"    => $username,
           "leave_id"          => $leave_id,
           "status"            => $status,
           "leave_date"        => $leave_date,
           "leave_to_date"     => $leave_to_date,
           "role_id"           => $role_id,
           "department"        => $department,
           "leave_taken"       => $leave_taken,
           "page"        => '1',
           "export"    => true];
        $data = $this->mdl->getList($filter);
        $time_nw = time();
        $export_excel_folder = BASEPATH . 'web/upload' . DIRECTORY_SEPARATOR;

        $csv = "Staff ID,Username,FullName,Role,Department, From Date , To Date,Total No of Days,Reason of Leave,Approved BY,Remark,Status \n";
        $filename_nw = $filename . '_' . $time_nw . '.csv';
        $csv_handler = fopen($export_excel_folder . $filename_nw, 'w');

        fprintf($csv_handler, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fwrite($csv_handler, $csv);

        $html = "";

        foreach ($data['data'] as $his) {



            $html .= $his['staff_id'] . ',' . $his['username'] . ',' . $his['first_name'] . ' ' . $his['last_name'] . ',' . $his['role'] . ',' . $his['department'] . ',"' . $his['from_date'] . '",' . $his['to_date'] . ',' . $his['total_days'] . '-' . $his['last_name'] . ',' . $his['reason'] . '-' . $his['leave_name'] . ',' . $his['updated_name'] . ',' . $his['remark'] . ',' . $his['status'] . ',' . "\n"; //Append data to csv

        }
        if(!empty($html)) {
            fwrite($csv_handler, $html);
        }

        fclose($csv_handler);

        $act = "Admin export Order Request List .file -" . $filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="' . BASEURL . 'web/upload/' . $filename_nw . '" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="' . BASEURL . 'web/upload/' . $filename_nw . '" style="float:right;">Download</button></a>';

        return $this->sendMessage('success', $download);



    }

    public function actionStaffList()
    {

        $this->checkPageAccess(86);
        $username       = $this->cleanMe(Router::post('user_id'));
        $role           = $this->cleanMe(Router::post('role'));
        $staff_id       = $this->cleanMe(Router::post('staff_id'));
        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));
        $first_name     = $this->cleanMe(Router::post('first_name'));
        $nick_name      = $this->cleanMe(Router::post('nick_name'));
        $mobile         = $this->cleanMe(Router::post('mobile'));
        $status         = $this->cleanMe(Router::post('status'));
        $email          = $this->cleanMe(Router::post('email'));
        $year           = !empty(Router::post('year')) ? $this->cleanMe(Router::post('year')) : date('Y');
        $page           = $this->cleanMe(Router::post('page'));
        $page           = (!empty($page)) ? $page : '1';



        $filter = ["username"     => $username,
                "role"          => $role,
                "staff_id"      => $staff_id,
                "position"      => $position,
                "department"    => $department,
                "first_name"    => $first_name,
                "nick_name"     => $nick_name,
                "mobile"        => $mobile,
                "status"        => $status,
                "email"         => $email,
                "year"          => $year,
                "page"          => $page];


        $data = $this->mdl->getStaffList($filter);
        $data['departments'] = $this->departmentsmdl->getActiveDepartments();
        $data['positions']   = $this->positionmdl->getActivePositions();

        $data_leave_type = $this->leavemdl->getAllLeaveType();
        $user_id = '';
        $user_name = '';
        if(!empty($filter['username'])) {
            $user_id    = $filter['username'];
            $filter['username']    = $this->usermdl->getUsername($filter['username']);
            $user_name = $filter['username'];

        }

        $onclick    = "onclick=pageHistory('" . $user_id . "','" . $role . "','" . $staff_id . "','" . $position . "','" . $department . "','" . $first_name . "','" . $nick_name . "','" . $status . "','" . $email . "','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'], $data['count'], $data['perPage'], 1, $onclick, 'pagestring');

        //generate sample leave import file


        $this->actionCreateSampleFile();

        return $this->render('leave_request/staff_list', ['filter' => $filter,'user_id' => $user_id,'username' => $user_name , 'data' => $data,'data_leave_type' => $data_leave_type, 'pagination' => $pagination]);

    }
    public function actionImportLeave()
    {

        if(empty($_FILES['filename'])) {

            $data = ['type' => 'validation','msg' => 'Please upload excel file  To Proceed'];
            return $this->sendMessage('error', $data);

        }
        if(!empty($_FILES['filename'])) {

            $filename   = $_FILES['filename']['name'];
            $temp_name  = $_FILES['filename']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('CSV','csv');

            if(!in_array($extension, $image_array)) {

                $data = [];
                $data['msg'] = 'Please Select Valid Format';
                $data['type'] = 'validation';
                return $this->sendMessage("error", $data);
                die();
            }


            $newFile_org = 'CreateLeaveRequest_' . $this->adminID . '_' . time() . '.' . $extension;
            $target_file = BASEPATH . "web/upload/stock/" . $newFile_org;
            $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!move_uploaded_file($temp_name, $target_file)) {

                $data = [];
                $data['msg']  = 'Something Went Wrong...';
                $data['type'] = 'validation';
                return $this->sendMessage("error", $data, "error");
                die();
            }

        }

        $csvFile = file($target_file);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        if(empty($data)) {

            $data['msg'] = 'Selected excel file is empty';
            $data['type'] = 'validation';
            return $this->sendMessage("error", $data, "error");
            die();

        }
        $error_array = [];
        $error_status = '0';

        $random_bulk_id = $this->generaterandomid();



        foreach($data as $k => $v) {

            if($k != '0') {

                $rowindex         = $k + 1;
                $slno             = !empty($v[0]) ? $v[0] : '';
                $email            = !empty($v[1]) ? $v[1] : '';
                $year             = !empty($v[2]) ? $v[2] : '';
                $leave_id         = !empty($v[3]) ? $v[3] : '';
                $leave_type       = !empty($v[4]) ? $v[4] : '';
                $date_from        = !empty($v[5]) ? $v[5] : '';
                $date_to          = !empty($v[6]) ? $v[6] : '';
                $status           = !empty($v[7]) ? $v[7] : '';
                $reason           = !empty($v[8]) ? $v[8] : '';

                $ip['email']           = $email;
                $ip['year']            = $year;
                $ip['leave_id']        = $leave_id;
                $ip['leave_type']      = $leave_type;
                $ip['date_from']       = date('Y-m-d', strtotime($date_from));
                $ip['date_to']         = date('Y-m-d', strtotime($date_to));
                $ip['status']          = $status;
                $ip['reason']          = $reason;
                $ip['bulk_id']         = $random_bulk_id;
                $ip['upload_filename'] = $newFile_org;


                $this->mdl->addTempLeave($ip);


            }

        }

        $details_temp = $this->mdl->getTempDetails($random_bulk_id);


        $html = [];
        $html['header'] = '';
        $html['valid'] = '';
        $html['invalid'] = '';
        $html['footer'] = '';
        $html['header'] = '
                        <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                        <thead> 
                            <tr>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Email</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Year</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Leave Name</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Leave Type</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">From Date</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">To Date</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Reason</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                
                            </tr>
                        </thead>';

        foreach($details_temp as $k => $v) {



            $class = $v['is_valid'] ? 'odd' : 'table-danger';

            $html['valid'] .= '<tr role="row" class="' . $class . '">
                    <td>' . $v['email'] . '</td>
                    <td>' . $v['year'] . '</td>
                    <td>' . $v['leave_id'] . '</td>
                    <td>' . $v['leave_type'] . '</td>
                    <td>' . date('d-m-Y', strtotime($v['date_from'])) . '</td>
                    <td>' . date('d-m-Y', strtotime($v['date_to'])) . '</td>
                    <td>' . $v['reason'] . '</td>
                    <td>' . $v['status'] . '</td>
                </tr>';





        }

        $html['footer'] = '</tbody></table>';
        $data['msg'] = '';
        $data['type']            = 'showpopup';
        $data['html']['header']  = $html['header'];
        $data['html']['valid']   = $html['valid'];
        $data['html']['invalid'] = $html['invalid'];
        $data['html']['footer']  = $html['footer'];
        $data['bulk_id']         = $random_bulk_id;
        return $this->sendMessage("success", $data, 'success');
        die();


    }

    public function actionProcessImport()
    {

        $bulk_id = $this->cleanMe(Router::post('bulk_id'));
        $type    = $this->cleanMe(Router::post('type'));


        $response = $this->mdl->processImport(['action' => $type,'bulk_id' => $bulk_id]);

        if($response) {

            $successArray = ['1' => 'Successfully Imported the valid datas','2' => 'Successfully Rejected'];
            return $this->sendMessage("success", $successArray[$type], 'success');
            die();

        } else {

            $successArray = ['1' => 'Failed to import','2' => 'Failed to reject'];
            return $this->sendMessage("error", $successArray[$type], 'error');
            die();

        }

        return $this->sendMessage("error", 'Something went wrong', 'error');
        die();



    }

    public function generaterandomid()
    {

        do {

            $bulk_id = rand(1, 10000);

            $isBulkidExist = $this->stockmdl->checkBulk_id($bulk_id);

            $isBulkidExist = !empty($isBulkidExist) ? $isBulkidExist : '';

        } while ($isBulkidExist);

        return $bulk_id;
    }

    public function actionExportStaffList()
    {

        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Staff List';

        $username       = $this->cleanMe(Router::post('user_id'));
        $role           = $this->cleanMe(Router::post('role'));
        $staff_id       = $this->cleanMe(Router::post('staff_id'));
        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));
        $first_name     = $this->cleanMe(Router::post('first_name'));
        $nick_name      = $this->cleanMe(Router::post('nick_name'));
        $mobile           = $this->cleanMe(Router::post('mobile'));
        $status           = $this->cleanMe(Router::post('status'));
        $email           = $this->cleanMe(Router::post('email'));
        $year           = !empty(Router::post('year')) ? $this->cleanMe(Router::post('year')) : date('Y');


        $filter = ["username"     => $username,
                "role"          => $role,
                "staff_id"      => $staff_id,
                "position"      => $position,
                "department"    => $department,
                "first_name"    => $first_name,
                "nick_name"     => $nick_name,
                "mobile"        => $mobile,
                "status"        => $status,
                "email"         => $email,
                "year"          => $year,
                "page"          => '1',
                "export"    => true];
        $data = $this->mdl->getStaffList($filter);


        $time_nw = time();
        $export_excel_folder = BASEPATH . 'web/upload' . DIRECTORY_SEPARATOR;

        $data_leave_type = $this->leavemdl->getAllLeaveType();

        $custom_header = array_column($data_leave_type, 'leave_name');
        $custom_header = implode(',', $custom_header);

        $csv = "Username,Staff Id,Role,Position,Department,First Name , Email \n";

        if(!empty($custom_header)) {

            $csv = "Username,Staff Id,Role,Position,Department,First Name,Last Name , Email," . $custom_header . " \n";

        }

        $filename_nw = $filename . '_' . $time_nw . '.csv';
        $csv_handler = fopen($export_excel_folder . $filename_nw, 'w');

        fprintf($csv_handler, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fwrite($csv_handler, $csv);

        $html = "";

        foreach ($data['data'] as $his) {


            $leaves = $his['leaveList'][0]['leave_report'];

            $leaves = array_column($leaves, 'balance');
            $leaves = implode(',', $leaves);
            if(!empty($custom_header)) {

                $html .= $his['username'] . ',' . $his['staff_id'] . ',' . $his['role'] . ',' . $his['position'] . ',' . $his['department'] . ',"' . $his['first_name'] . '","' . $his['last_name'] . '",' . $his['email'] . ',' . $leaves . "\n"; //Append data to csv

            } else {

                $html .= $his['username'] . ',' . $his['staff_id'] . ',' . $his['role'] . ',' . $his['position'] . ',' . $his['department'] . ',"' . $his['first_name'] . '","' . $his['last_name'] . '",' . $his['email'] . ',' . "\n"; //Append data to csv
            }





        }
        if(!empty($html)) {
            fwrite($csv_handler, $html);
        }

        fclose($csv_handler);

        $act = "Admin export staff list leave balance .file -" . $filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="' . BASEURL . 'web/upload/' . $filename_nw . '" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="' . BASEURL . 'web/upload/' . $filename_nw . '" style="float:right;">Download</button></a>';

        return $this->sendMessage('success', $download);

    }

    public function actionImportStaffLeaveBalance()
    {

        if(empty($_FILES['filename'])) {

            $data = ['type' => 'validation','msg' => 'Please upload file To Proceed'];
            return $this->sendMessage('error', $data);

        }
        if(!empty($_FILES['filename'])) {

            $filename   = $_FILES['filename']['name'];
            $temp_name  = $_FILES['filename']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('CSV','csv');

            if(!in_array($extension, $image_array)) {

                $data = [];
                $data['msg'] = 'Please Select Valid Format';
                $data['type'] = 'validation';
                return $this->sendMessage("error", $data);
                die();
            }


            $newFile_org = 'StaffLeave_Balance' . $this->adminID . '_' . time() . '.' . $extension;
            $target_file = BASEPATH . "web/upload/leave/" . $newFile_org;
            $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!move_uploaded_file($temp_name, $target_file)) {
                $data = [];
                $data['msg']  = 'Something Went Wrong...';
                $data['type'] = 'validation';
                return $this->sendMessage("error", $data, "error");
                die();
            }
        }

        $csvFile = file($target_file);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        if(empty($data)) {
            $data['msg'] = 'Selected excel file is empty';
            $data['type'] = 'validation';
            return $this->sendMessage("error", $data, "error");
            die();
        }
        $random_bulk_id = $this->generaterandomid();

        $excel_headers = [];
        foreach($data as $k => $v) {
            if($k == 0) { //csv headers
                $excel_headers = $v;
            }

        }

        unset($excel_headers[0]);
        unset($excel_headers[1]);
        unset($excel_headers[2]);



        foreach($data as $k => $v) {


            $arrayList = [];
            if($k != 0) { //csv headers

                $username  = !empty($v[1]) ? $v[1] : '';
                $year      = !empty($v[2]) ? $v[2] : '';
                $requestArray = [];
                foreach($excel_headers as $headerkey => $headervalue) {

                    $requestArray[] = ['leave_title' => $headervalue,'balance' => !empty($v[$headerkey]) ? $v[$headerkey] : '0'];

                }

                $arrayList = ['bulk_id' => $random_bulk_id,'upload_file' => $newFile_org,'username' => $username,'year' => $year,'request' => $requestArray];
                $this->mdl->insertBulk($arrayList);



            }


        }

        $list = $this->mdl->getTempData($random_bulk_id);
        $headerHtml = '';
        foreach($excel_headers as $key => $value) {

            $headerHtml .= '<th class="sorting_disabled" rowspan="1" colspan="1">' . $value . '</th>';
        }
        $html = [];
        $html['header']  = '';
        $html['valid']   = '';
        $html['invalid'] = '';
        $html['footer']  = '';
        $html['header'] = '
                        <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                        <thead> 
                            <tr>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Year</th>'
                                . $headerHtml .

                            '</tr>
                        </thead>';
        foreach($list as $key => $value) {

            $class = $value['is_valid'] ? 'odd' : 'table-danger';
            $requestHtml = '';
            foreach ($value['request'] as $requestkey => $requestvalue) {

                $requestHtml .= '<td>' . $requestvalue['balance'] . '</td>';

            }
            $html['valid'] .= '<tr role="row" class="' . $class . '">
                    <td>' . $value['username'] . '</td>
                    <td>' . $value['year'] . '</td>'
                    . $requestHtml .
                    '</tr>';

        }

        $html['footer'] = '</tbody></table>';
        $data['msg'] = '';
        $data['type']            = 'showpopup';
        $data['html']['header']  = $html['header'];
        $data['html']['valid']   = $html['valid'];
        $data['html']['invalid'] = $html['invalid'];
        $data['html']['footer']  = $html['footer'];
        $data['bulk_id']         = $random_bulk_id;
        return $this->sendMessage("success", $data, 'success');
        die();


    }

    public function actionProcessStaffLeaveImport()
    {

        $bulk_id  = $this->cleanMe(Router::post('bulk_id'));
        $type     = $this->cleanMe(Router::post('type'));
        $response = $this->mdl->processLeaveBalanceImport(['action' => $type,'bulk_id' => $bulk_id]);
        if($response) {
            $successArray = ['1' => 'Successfully Imported the valid data','2' => 'Successfully Rejected'];
            return $this->sendMessage("success", $successArray[$type], 'success');
            die();
        } else {
            $successArray = ['1' => 'Failed to import','2' => 'Failed to reject'];
            return $this->sendMessage("error", $successArray[$type], 'error');
            die();
        }
        return $this->sendMessage("error", 'Something went wrong', 'error');
        die();

    }

    public function actionCreateSampleFile()
    {

        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');




        //delete the current file
        $filename = 'sample_staff_leave.csv';

        $current_file = BASEPATH . 'web/assets/Sample_excels/' . $filename;
        if (file_exists($current_file)) {
            unlink($current_file);
        }



        $time_nw = time();
        $export_excel_folder = BASEPATH . 'web/assets/Sample_excels' . DIRECTORY_SEPARATOR;

        $data_leave_type = $this->leavemdl->getAllLeaveType();

        $custom_header = array_column($data_leave_type, 'leave_name');
        $custom_header = implode(',', $custom_header);


        if(!empty($custom_header)) {

            $csv = "Slno,Username,Year," . $custom_header . " \n";

        }

        $filename_nw = $filename;
        $csv_handler = fopen($export_excel_folder . $filename_nw, 'w');

        fprintf($csv_handler, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fwrite($csv_handler, $csv);



        $leaveCount = count($data_leave_type);
        $leaves = [];
        for($i = 1;$i <= $leaveCount;$i++) {
            array_push($leaves, $i);

        }

        $leaves = implode(',', $leaves);
        $year = date('Y');
        $html = "1,Testusername," . $year . ',' . $leaves . "\n";



        if(!empty($html)) {
            fwrite($csv_handler, $html);
        }

        fclose($csv_handler);



    }

    public function actiongetStaffData()
    {
        $user_id = Router::post('user_id');
        $data = $this->usermdl->getuserdetails($user_id);
        $response['staffid'] = $data['info']['staff_id'];
        $department_id = $data['info']['department'];
        $position_id = $data['info']['position'];
        $response['department'] = $this->usermdl->callsql("SELECT name FROM departments WHERE id='$department_id'", 'value');
        $response['position'] = $this->usermdl->callsql("SELECT name FROM positions WHERE id='$position_id'", 'value');
        die(json_encode($response));
    }
    public function actiongetLeaveBalance()
    {
        $user_id = Router::post('user_id');
        $leave_id = Router::post('leave_id');
        $key = date("Y");

        $response = $this->mdl->callsql("SELECT leave_id,balance,total FROM leave_report WHERE staff_id='$user_id' AND year='$key' AND leave_id='$leave_id'", 'row');
        die(json_encode($response));
    }




}