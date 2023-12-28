<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;
use inc\commonArrays;

class LeaveRequest extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "leave_request";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
        $this->getArray      = (new commonArrays)->getArrays();


        $this->roleArr       = $this->getArray['roleArr'];
    }
    
    public function getList($data)
    {
   
        $where = ' WHERE id!=0 ';

        if(!empty($data['username'])){
            $where .= " AND user_id ='$data[username]' ";
        }
        if(!empty($data['leave_id'])){
            $where .= " AND leave_id ='$data[leave_id]' ";
        }
        if(!empty($data['role_id'])){
            $where .= " AND $this->tableName.user_id IN ( SELECT user.user_id FROM user WHERE role_id = '$data[role_id]') ";
        }
        if(!empty($data['department'])){
            $where .= " AND $this->tableName.user_id IN ( SELECT user.user_id FROM user WHERE department = '$data[department]') ";
        }
        if(!empty($data['status']) ||  in_array($data['status'],['0','1','2','3'])){
            $where .= " AND status ='$data[status]' ";
        }
        if(!empty($data['leave_date'])){
            $date = strtotime($data['leave_date']);

            $where .= " AND date_from ='$date' ";
        }
        if(!empty($data['leave_to_date'])){
            $date2 = strtotime($data['leave_to_date']);

            $where .= " AND date_to ='$date2' ";
        }
        if(!empty($data['leave_taken'])){
            $where .= " AND leave_type ='$data[leave_taken]' ";
        }

        
        $count  = $this->callsql("SELECT count(DISTINCT id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
        $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
        $pagecount = ($data['page'] - 1) * $this->perPage;
        $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }

        
        
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

            $this->leave_typeArray = ['1'=>'Full Day','2'=>'Half Day'];
            $this->statusArray     = ['0'=>'Requested','1'=>'Approved','2'=>'Rejected','3'=>'Cancelled'];
            $this->permissionList = $this->roleArr;



            $user_details = $this->callsql("SELECT username,first_name,last_name,role_id,staff_id,department FROM user WHERE user_id = '$value[user_id]' ","row");
            $result['data'][$key]['username']   = !empty($user_details['username']) ? $user_details['username'] : ''; 
            $result['data'][$key]['first_name'] = !empty($user_details['first_name']) ? $user_details['first_name'] : ''; 
            $result['data'][$key]['last_name']  = !empty($user_details['last_name']) ? $user_details['last_name'] : ''; 
            $result['data'][$key]['role']  = !empty($user_details['role_id']) ? $this->permissionList[$user_details['role_id']]:'-';
            
            $result['data'][$key]['department']  = !empty($user_details['department']) ?  $this->callsql("SELECT name FROM departments WHERE id ='$user_details[department]' ","value"):'-';

            $result['data'][$key]['staff_id']  = !empty($user_details['staff_id']) ? $user_details['staff_id']:'-';
            $leave_name = $this->callsql("SELECT leave_name FROM leave_type WHERE id ='$value[leave_id]' ","value");
            $result['data'][$key]['leave_name']   = !empty($leave_name) ? $leave_name : ''; 
            $result['data'][$key]['from_date']   = !empty($value['date_from']) ? date("d-m-Y",$value['date_from']):'-';
            $result['data'][$key]['to_date']   = !empty($value['date_to']) ? date("d-m-Y",$value['date_to']):'-';
            $result['data'][$key]['leave_type']   = !empty($value['leave_type']) ? $this->leave_typeArray[$value['leave_type']]:'-';
                
            if($value['leave_type'] == '2') {

                $datediff = '.5';

            }else{

                $datediff = $value['date_to'] - $value['date_from'];
                $datediff = round($datediff / (60 * 60 * 24))+1;

            }
                

            $result['data'][$key]['total_days'] = !empty($datediff) ? $datediff :'1' ;
                
            if($value['user_type']==2)
            {
                $updated_name = $this->callsql("SELECT username FROM user WHERE user_id = '$value[updated_by]' ","value");
            }else{
                $updated_name = $this->callsql("SELECT username FROM admin WHERE id = '$value[updated_by]' ","value");
            }
            $result['data'][$key]['updated_name'] = $updated_name;
            $result['data'][$key]['reason']       = !empty($value['reason']) ? $value['reason'] : '-';
                //$result['data'][$key]['upload_file']  = !empty($value['upload_file']) ? $value['upload_file'] : '-';
            $result['data'][$key]['upload_file']  = !empty($value['upload_file']) ? '<a href='.FRONTEND.'web/upload/leave/'.$value['upload_file'].' target="_blank"><button class="btn btn-info">View</button> </a>':'-';
                
            $status_color = $value['status']==1 ? 'btn btn-success' : 'btn btn-danger';

            if($value['status']==0)
            {
                $button_status = '<button type="button" class="btn btn-info" onclick="showApproveModal('.$value['id'].')">'.$this->statusArray[$value['status']].'
                    </button>';
            }else{
                $button_status = '<div class="'.$status_color.'">'.$this->statusArray[$value['status']].'</div>';
            }
            if(!empty($data['export']))
            {
                $button_status = $this->statusArray[$value['status']];
            }

            $result['data'][$key]['status']       = $button_status;
                $result['data'][$key]['remark']       = !empty($value['remark']) ? $value['remark'] : '-';
                
            $result['data'][$key]['action']       = $value['status'] == '0' ? '<button type="button" class="btn btn-info" onclick="showEditModal('.$value['id'].')">Edit</button>':'<button type="button" class="btn btn-info" onclick="showViewModal('.$value['id'].')">View</button>';

        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = !empty($data['page'])?$data['page']:'1';
        $result['perPage'] = $this->perPage;
        return $result;
    }


    public function UpdateLeaveStatus($params)
    {   
        
        $this->statusArray     = ['0'=>'Requested','1'=>'Approved','2'=>'Rejected','3'=>'Cancelled'];
        
        $username = $params['username'];
        $status   = $params['status'];
        $remark   = $params['remark'];
        $id       = $params['id'];
        $updatetime = time();
        $updated_by = $this->adminID;
        $this->query("UPDATE $this->tableName SET status='$status',remark='$remark',updated_by='$updated_by',updatetime='$updatetime',user_type='1' WHERE id='$id'");
        $this->execute();

        if($status == '1') {

            $this->updateLeaveBalance(['ids'=>$id]); 

        }
        $this->adminActivityLog("Leave Request ".$this->statusArray[$status]." for Username - ".$username);
        return true;

    }

    public function updateLeaveBalance($params)
    {

        $ids = !empty($params['ids']) ? $params['ids']:'';
        if(!empty($ids)) {

            $leave_calculation_type = $this->callsql("SELECT data FROM site_data WHERE keyvalue='leave_calculation_type'",'value');
            $year = '';
            if($leave_calculation_type == '1') {
                
                $year = date('Y');
            }



            $sql = "SELECT user_id,leave_id,leave_type,date_to,date_from FROM $this->tableName WHERE id IN($ids) ";
            $leaveRequest = $this->callsql($sql,'rows');

            foreach($leaveRequest as $key=>$value) {

                $staff_id   = $value['user_id'];
                $leave_id   = $value['leave_id'];
                $leave_type = $value['leave_type'];
                $no_of_days = 0;

                $year_count_array = [];
                if(($value['date_from'] == $value['date_to']) && $leave_type=='1') { //one full day

                    $no_of_days = 1;
                    $year = date('Y',$value['date_from']);
                    $year_count_array = [$year=>'1'];


                }



                if(($value['date_from'] != $value['date_to']) && $leave_type=='1') { //more than one full day
   
                    $datediff = $value['date_to'] - $value['date_from'];
                    $datediff = round($datediff / (60 * 60 * 24));
                    $no_of_days = $datediff+1;


                    $year_count_array = $this->getDatesFromRange(date('Y-m-d',$value['date_from']), date('Y-m-d',$value['date_to']));




                    

                }
                if(($value['date_from'] == $value['date_to']) && $leave_type=='2') { //one half day

                    $no_of_days = .5;
                    $year = date('Y',$value['date_from']);
                    $year_count_array = [$year=>.5];

                }
                
                if(!empty($year_count_array)) {

                    foreach ($year_count_array as $k => $v) {

                        $time = time();
                        
                        $sql = "UPDATE leave_report SET balance=balance-$v,updated_at='$time' WHERE leave_id='$leave_id' AND staff_id='$staff_id' AND year='$k'";
                        $this->query($sql);
                        $this->execute();
                    }

                }
                

               



            }

           
           



        }

    }

    function getDatesFromRange($Date1, $Date2, $format = 'Y-m-d') { 
      
        $array = array(); 
        $Variable1 = strtotime($Date1); 
        $Variable2 = strtotime($Date2); 
          
        for ($currentDate = $Variable1; $currentDate <= $Variable2;  
                                        $currentDate += (86400)) { 
                                              
            $Store = date('Y-m-d', $currentDate); 
            $array[] = $Store; 
        }

        $years = [];
        foreach($array as $key=>$value){

            $years[] = [date('Y',strtotime($value))];

        }

        $counts = array();

        
        foreach ($years as $item) {
            $year = $item[0];
            if (isset($counts[$year])) {
                $counts[$year]++;
            } else {
                $counts[$year] = 1;
            }
        }

        return $counts;

       



        
    } 

    public function updateLeaveBalancebkp($params)
    {

        $ids = !empty($params['ids']) ? $params['ids']:'';
        if(!empty($ids)) {

            $leave_calculation_type = $this->callsql("SELECT data FROM site_data WHERE keyvalue='leave_calculation_type'",'value');
            $year = '';
            if($leave_calculation_type == '1') {
                
                $year = date('Y');
            }



            $sql = "SELECT user_id,leave_id,leave_type,date_to,date_from FROM $this->tableName WHERE id IN($ids) ";
            $leaveRequest = $this->callsql($sql,'rows');

           
            foreach($leaveRequest as $key=>$value)
            {

                $staff_id   = $value['user_id'];
                $leave_id   = $value['leave_id'];
                $leave_type = $value['leave_type'];
                if($value['leave_type'] == '2') { //half day

                    $days = '.5';

                }
                if($value['leave_type'] == '1') { //full day

                    if($value['date_from'] == $value['date_to']) {

                        $days = '1';

                    }else{

                        $datediff = $value['date_to'] - $value['date_from'];
                        $datediff = round($datediff / (60 * 60 * 24));
                        $days     = $datediff;
                    }

                    

                }
                
                $time = time();
                $checkExist = $this->callsql("SELECT id,balance FROM leave_report WHERE staff_id='$staff_id' AND leave_id='$leave_id' AND year='$year'",'row');
                if(!empty($checkExist)) { //have entry already
                   
                    $sql = "UPDATE leave_report SET balance=balance-$days,updated_at='$time' WHERE id='$checkExist[id]'";
                   

                }
                if(empty($checkExist)) {
                    
                    $total = $this->callsql("SELECT allowed_count FROM leave_type WHERE id='$leave_id'",'value');
                    $balance = $total-$days;
                    $sql = "INSERT INTO leave_report SET staff_id='$staff_id',year='$year',leave_id='$leave_id',total='$total',balance='$balance',created_at='$time',updated_at='$time'";

                }

                $this->query($sql);
                $this->execute();

            }



        }

    }


    public function getLeaveRequestById($id)
    {   
        $user_id = $this->callsql("SELECT user_id FROM $this->tableName WHERE id = '$id' ", "value");

        $username = $this->callsql("SELECT username FROM user WHERE user_id = '$user_id' ","value");

        return $username;

    }
    public function getDetails($filter)
    {
        $details = $this->callsql("SELECT * FROM $this->tableName WHERE id = '$filter[id]' ", "row");

        $details['username'] = $this->callsql("SELECT username FROM user WHERE user_id = '$details[user_id]' ","value");
        $details['leave_date'] = date('d-m-Y',$details['date_from']);
        $details['leave_to_date'] = date('d-m-Y',$details['date_to']);
        $details['upload_file'] = '<a href='.FRONTEND.'web/upload/leave/'.$details['upload_file'].' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download Uploaded File </a>';

        return $details;

    }
    public function isLeaveRequestproceed($id)
    {
        $is_processed =  $this->callsql("SELECT COUNT(id) FROM $this->tableName WHERE id = '$id' AND status!='0' ", "value");
        return $is_processed;
    }
    public function updateDetails($params)
    {
        $time = time();
        $username   = $params['username'];
        $id         = $params['id'];
        $leave_id   = $params['leave_id'];
        $leave_date = strtotime($params['leave_date']);
        $leave_date_to = strtotime($params['leave_date_to']);
        $leave_type = $params['leave_type'];
        $reason     = $params['reason'];
        $upload_file= $params['upload_file'];
        $remark     = $params['remark'];
        $upload_addon = '';
        if(!empty($upload_file))
        {
            $upload_addon = " upload_file='$upload_file',";
        }
        $this->query("UPDATE $this->tableName SET leave_id='$leave_id', date_from='$leave_date',date_to='$leave_date_to',leave_type='$leave_type',reason='$reason',remark='$remark',$upload_addon updatetime = '$time' WHERE id='$id'");

        $this->execute();
        $this->adminActivityLog("Details Updated for Username -".$username. ' id- '.$id);
        return true;
    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }
    public function gettodaysleave(){
       
        $time = time();
        $dateoftoday = date('Y-m-d',$time);
        $result = [];
       
        $details = $this->callsql("SELECT user_id,leave_type,DATE(FROM_UNIXTIME(date_from)) as dat  From leave_request WHERE `status`='1'","rows");

        
        if(!empty($details)){
            foreach ($details as $key => $value) {

                
          if($value['dat'] == $dateoftoday){
               $result['data'][$key]['user_id']    = $this->callsql("SELECT username FROM `user` WHERE user_id='$value[user_id]' ","value");
               $result['data'][$key]['staff_id']    = $this->callsql("SELECT staff_id FROM `user` WHERE user_id='$value[user_id]' ","value");
               $result['data'][$key]['leave_type'] = $this->callsql("SELECT leave_name FROM `leave_type` WHERE id='$value[leave_type]' ","value");
              }
            }
       
        
        }
        else{

           $result['data']['user_id']  = '';
           $result['data']['leave_type']      = ''; 

        }
     
       return $result;
    }

    public function getLeaveBalance($params)
    {
        
        $staffIds = $params['staff_ids'];
        $year = $params['year'];
        $leave_calculation_type = $this->callsql("SELECT data FROM site_data WHERE keyvalue='leave_calculation_type'",'value');
        // $year = '';
        // if($leave_calculation_type == '1') {
                
        //     $year = date('Y');
        // }

        $leaveTypes = $this->callsql("SELECT id,leave_name,allowed_count FROM leave_type WHERE status='0'",'rows');

        $staff_ids = explode(',',$staffIds);

        
        $result = [];
        foreach($staff_ids as $key=>$value)
        {

            $result[$key]['staff_name'] = 'Test Name';
            $result[$key]['staff_id'] = $value;
            $leaveBalanceArray = [];
            foreach($leaveTypes as $k=>$v){
                
                $leave_id = $v['id'];
                $total    = $v['allowed_count'];
                $balance  = $v['allowed_count'];
                $checkExist = $this->callsql("SELECT leave_id,total,balance FROM leave_report WHERE leave_id='$leave_id' AND year='$year' AND staff_id='$value'",'row');
                if(!empty($checkExist)) {

                    $total  = $checkExist['total'];
                    $balance = $checkExist['balance'];

                }

                $leaveBalanceArray[] = ['id'=>$v['id'],'leave_name'=>$v['leave_name'],'allowed_count'=>$total,'balance'=>$balance];


            }
            $result[$key]['leave_report'] = $leaveBalanceArray;



        }

       return $result;



    }

    public function getStaffList($data){



        //$dateofbirth =strtotime($data['dob']);
        $where = ' WHERE a.user_id!=0 AND role_id!=6';
        // $where1 = '  ';

        if($data['status']!=""){
            $where.= " AND a.status = '$data[status]' ";
        }

        if($data['role']!=""){
            $where.= " AND a.role_id = '$data[role]' ";
        }
        // if($data['customername']!=""){
        //      $where .= " AND us.status = '$data[status]' ";
        //  } 
        if($data['username']!=""){
          $where .= " AND a.user_id = '$data[username]' ";
        }

        if($data['staff_id']!=""){
          $where .= " AND a.staff_id LIKE '%".$data['staff_id']."%' ";
        }
        if($data['position']!=""){
          $where .= " AND a.position = '$data[position]' ";
        }
        if($data['department']!=""){
          $where .= " AND a.department = '$data[department]' ";
        }
         
        if($data['first_name']!=""){
          $where .= " AND a.first_name = '$data[first_name]' ";
        }
        
        if($data['nick_name']!=""){
          $where .= " AND a.nick_name = '$data[nick_name]' ";
        }
         


        if($data['mobile']!=""){
            $where .= " AND a.mobileno LIKE '%".$data['mobile']."%'  OR a.mobileno2 LIKE '%".$data['mobile']."%'";

          
        }

       
        
    
        // if($data['userID']!=""){
        //     $where .= " AND us.username LIKE '%$data[userID]%'";
        // }
        if(!empty($data['user_id'])){
            $where .= " AND a.user_id = '$data[user_id]' ";
        }

        if(!empty($data['email'])){
            $where .= " AND a.email = '$data[email]' ";
        }

        // if($data['username']!=""){
        //     $where .= " AND us.email LIKE '%$data[username]%'";
        // }    
        // if(!empty($data['dob'])){

            
        //     $date_to   = strtotime($data['dob']." 23:59:59");

        //     $where    .= " AND b.dob BETWEEN '$date[dob]'  ";
        // }  


        $pagecount = ($data['page'] - 1) * $this->perPage;



        $count = $this->callsql("SELECT COUNT(DISTINCT user_id) FROM user as a $where ","value");

       

        if(!empty($data['export'])){

            $result['data'] = $this->callsql("SELECT * FROM user as a  $where ORDER BY a.user_id DESC","rows");
        }else{

            $result['data'] = $this->callsql("SELECT * FROM user as a  $where ORDER BY a.user_id DESC LIMIT $pagecount,$this->perPage","rows");
        }



        
       
       
        $list = [];

        $year = $data['year'];

        foreach ($result['data'] as $key => $value) {
               
            $list['data'][$key]['user_id']   = $value['user_id'];
            $list['data'][$key]['username']  = !empty($value['username']) ? $value['username'] : '-';
            $list['data'][$key]['staff_id']  = !empty($value['staff_id']) ? $value['staff_id'] : '-';
                       
            $list['data'][$key]['position']  = !empty($value['position']) ?  $this->callsql("SELECT name FROM positions WHERE id ='$value[position]' ","value"):'-';

            $list['data'][$key]['department']  = !empty($value['department']) ?  $this->callsql("SELECT name FROM departments WHERE id ='$value[department]' ","value"):'-';

            $list['data'][$key]['role']      = !empty($value['role_id']) ? $this->roleArr[$value['role_id']] : '-';
            $list['data'][$key]['email']     = !empty($value['email']) ? $value['email'] : '-';
            $list['data'][$key]['first_name'] = !empty($value['first_name']) ? $value['first_name']: '-';
            $list['data'][$key]['last_name'] = !empty($value['last_name']) ? $value['last_name']: '-';
            $list['data'][$key]['leaveList'] = $this->getLeaveBalance(['staff_ids'=>$value['user_id'],'year'=>$year]);
 
            

        }



   

        if($count==0){
            $list['data'] = array();
        }

        $list['count']   = $count;
        $list['curPage'] = $data['page'];
        $list['perPage'] = $this->perPage;
        return $list;
    }


    public function getLeaveDetails($params)
    {

        $user_id = !empty($params['user_id']) ? $params['user_id'] : '';

        $leaveTypes = $this->callsql("SELECT id,leave_name,allowed_count FROM leave_type WHERE status='0'",'rows');
        $year = date('Y');
        foreach($leaveTypes as $key=>$value)
        {

            $id = $value['id'];
            $leaveDetails = $this->callsql("SELECT id,staff_id,balance FROM leave_report WHERE leave_id='$id' AND staff_id='$user_id' AND year='$year'",'row');
            $balance = !empty($leaveDetails) ? $leaveDetails['balance'] : $value['allowed_count'];
            $leaveTypes[$key]['leave_balance'] = $balance;


        }

        return $leaveTypes;


    }

    public function getLeaveTypeDetails($id)
    {

        return $this->callsql("SELECT * FROM leave_type WHERE id='$id'",'row');

    }

    public function updateLeave($params)
    {

        $leaveBalance = $params['leaveBalance'];
        $staff_id     = $params['staff_id'];
        $year  = date('Y');
        foreach($leaveBalance as $key=>$value){

            $sql = "SELECT id,balance FROM leave_report WHERE leave_id='$key' AND staff_id='$staff_id' AND year='$year'";
            $currentDetails = $this->callsql($sql,'row');
            $created_at = time();
            if(empty($currentDetails)) {
                
                $details = $this->getLeaveTypeDetails($key);
                
                $sql = "INSERT INTO leave_report SET staff_id='$staff_id',year='$year',leave_id='$key',total='$details[allowed_count]',balance='$value',created_at='$created_at',updated_at='$created_at'";
                $this->query($sql);
                $this->execute();

                $activity = "Admin inserted leave details .leave count =$value,staff id=$staff_id,leave id=$key,year=$year";
                $this->adminActivityLog($activity);

            }else{
                if($currentDetails['balance']!=$value) {

                    $sql = "UPDATE leave_report SET balance='$value',updated_at='$created_at' WHERE id='$currentDetails[id]'";
                    $this->query($sql);
                    $this->execute();
                    $activity = "Admin changed the leave count to $value,staff id=$staff_id,leave id=$key,year=$year";
                    $this->adminActivityLog($activity);

                }
                
            }

            

            
            


        }

        return true;


    }

    public function checkCanApply($params)
    {


        $user_id    = $params['user_id'];
        $leave_id   = $params['leave_id'];
        $leave_type = $params['leave_type'];
        $date_from  = $params['date_from'];
        $date_to    = $params['date_to'];



        $no_of_days = 0;

        $year_count_array = [];
        if(($params['date_from'] == $params['date_to']) && $leave_type=='1') { //one full day

            $no_of_days = 1;
            $year = date('Y',$params['date_from']);
            $year_count_array = [$year=>'1'];


        }



        if(($params['date_from'] != $params['date_to']) && $leave_type=='1') { //more than one full day

            $datediff = $params['date_to'] - $params['date_from'];
            $datediff = round($datediff / (60 * 60 * 24));
            $no_of_days = $datediff+1;


            $year_count_array = $this->getDatesFromRange(date('Y-m-d',$params['date_from']), date('Y-m-d',$params['date_to']));




            

        }
        if(($params['date_from'] == $params['date_to']) && $leave_type=='2') { //one half day

            $no_of_days = .5;
            $year = date('Y',$params['date_from']);
            $year_count_array = [$year=>.5];

        }
        
        $canApply = true;


        foreach($year_count_array as $key=>$value){

            $currentBalanceDetails = $this->callsql("SELECT leave_id,balance FROM leave_report WHERE staff_id='$user_id' AND year='$key' AND leave_id='$leave_id'",'row');

            
            if(!empty($currentBalanceDetails)) {

                if($currentBalanceDetails['balance'] < $value) {

                    $canApply = false;

                }

            }else{

                $allowed_count = $this->callsql("SELECT allowed_count FROM leave_type WHERE id='$leave_id'",'value');
                if($value>$allowed_count) {

                    $canApply = false;

                }
                $created_at = time(); 
                $sql = "INSERT INTO leave_report SET staff_id='$user_id',year='$key',leave_id='$leave_id',total='$allowed_count',balance='$allowed_count',created_at='$created_at',updated_at='$created_at'";
                $this->query($sql);
                $this->execute();
                $lastInsertId = $this->lastInsertId();
                $activity = 'Inserted new leave balance id='.$lastInsertId;
                $this->adminActivityLog($activity);

                
            }

        }

        return $canApply;

        




    }

    public function applyLeave($params)
    {

        
        $user_id     = $params['user_id'];
        $leave_id    = $params['leave_id'];
        $leave_type  = $params['leave_type'];
        $date_from   = $params['date_from'];
        $date_to     = $params['date_to'];
        $reason      = $params['reason'];
        $upload_file = $params['upload_file'];
        $status      = $params['status'];
        $createtime  = time();
        $updatetime  = time();
        $updated_by  = $this->adminID;

        $sql = "INSERT INTO $this->tableName SET user_id='$user_id',leave_id='$leave_id',leave_type='$leave_type',date_from='$date_from',date_to='$date_to',reason='$reason',upload_file='$upload_file',status='$status',createtime='$createtime',updatetime='$updatetime',updated_by='$updated_by'";

        $this->query($sql);
        $this->execute();
        $id = $this->lastInsertId();
        $leave_type_array = ['1'=>'Full Day','2'=>'Half Day'];
        $leave_name = $this->callsql("SELECT leave_name FROM leave_type WHERE id='$leave_id'",'value');
           



        $activity = "Applied ".$leave_name.'.'.$leave_type_array[$leave_type].'.Applied id-'.$id;

        return $this->adminActivityLog($activity);



    }

    public function updateLeaveRequest($params)
    {

        $user_id     = $params['user_id'];
        $leave_id    = $params['leave_id'];
        $leave_type  = $params['leave_type'];
        $date_from   = $params['date_from'];
        $date_to     = $params['date_to'];
        $reason      = $params['reason'];
        $upload_file = $params['upload_file'];
        $status      = $params['status'];
        $createtime  = time();
        $updatetime  = time();
        $updated_by  = $this->adminID;
        $id      = $params['id'];

        $sql = "UPDATE $this->tableName SET user_id='$user_id',leave_id='$leave_id',leave_type='$leave_type',date_from='$date_from',date_to='$date_to',reason='$reason',upload_file='$upload_file',status='$status',updatetime='$updatetime',updated_by='$updated_by' WHERE id='$id' ";

        $this->query($sql);
        $this->execute();
        $leave_type_array = ['1'=>'Full Day','2'=>'Half Day'];
        $leave_name = $this->callsql("SELECT leave_name FROM leave_type WHERE id='$leave_id'",'value');
        $activity = "Updated leave request ".$leave_name.'.'.$leave_type_array[$leave_type].'.Applied id-'.$id;

        return $this->adminActivityLog($activity);

    }


    public function getRequestDetails($id)
    {

        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }


    public function addTempLeave($data)
    {

        $email           = $data['email'];
        $year            = $data['year'];
        $leave_id        = $data['leave_id'];
        $leave_type      = $data['leave_type'];
        $date_from       = $data['date_from'];
        $date_to         = $data['date_to'];
        $status          = $data['status'];
        $reason          = $data['reason'];
        $bulk_id         = $data['bulk_id'];
        $upload_filename = $data['upload_filename'];
        $createtime = time();
        $createtip  = $this->IP;
        $createid   = $this->adminID;
        $sql = "INSERT INTO temp_leave_bulk SET email='$email',year='$year',leave_id='$leave_id',leave_type='$leave_type',date_from='$date_from',date_to='$date_to',bulk_id='$bulk_id',upload_filename='$upload_filename',status='$status',reason='$reason',createtime='$createtime',createtip='$createtip',createid='$createid'";
        $this->query($sql);
        $this->execute();

    }

    public function getTempDetails($id)
    {

        $list = $this->callsql("SELECT * FROM temp_leave_bulk WHERE bulk_id='$id'",'rows');
        $this->leave_typeArray = ['full day'=>'1','half day'=>'2'];
        $this->statusArray     = ['requested'=>'0','approved'=>'1','rejected'=>'2','cancelled'=>'3'];


        foreach($list as $key=>$value){


            $is_valid = true;
            $email = $value['email'];
            $year  = $value['year'];
            $leave_id  = $value['leave_id'];
            $leave_type  = $value['leave_type'];
            $date_from  = $value['date_from'];
            $date_to  = $value['date_to'];
            $reason  = $value['reason'];
            $status  = $value['status'];

            $leave_type_id = '';

            $list[$key]['user_id']       = '';
            $list[$key]['leave_name']    = '';
            $list[$key]['leave_type_id'] = '';
            $list[$key]['status_id']     = ''; 

            if(empty($email) || empty($year) || empty($leave_id) || empty($leave_type) || empty($date_from) || empty($date_to) || empty($reason) || empty($reason)) {

                $is_valid = false;

            }

            if(!empty($email)){

                $userExist = $this->callsql("SELECT user_id FROM user WHERE email='$email'",'value'); 
                $list[$key]['user_id'] = $userExist;
                if(!$userExist) {

                    $is_valid = false;

                }

            }

           

            if(!empty($leave_id)) {

                $leave_id   = strtolower($leave_id);

                
                $checkExist = $this->callsql("SELECT id FROM leave_type WHERE LOWER(leave_name)='$leave_id'",'value');
                $list[$key]['leave_name'] = $checkExist;
                if(!$checkExist) {

                    $is_valid = false;

                }

            }

          

            if(!empty($leave_type)) {

                $leave_type   = strtolower($leave_type);
                $leave_type_id = $this->leave_typeArray[$leave_type];
                $list[$key]['leave_type_id'] = $leave_type_id;
                if(!$leave_type_id) {

                    $is_valid = false;

                }

            }

            $date_from_converted = !empty($date_from) ? strtotime(date('Y-m-d',strtotime($date_from))) : '';
            $date_to_converted   = !empty($date_to) ? strtotime(date('Y-m-d',strtotime($date_to))) : '';

            $list[$key]['date_from_converted'] = $date_from_converted;
            $list[$key]['date_to_converted']   = $date_to_converted;

            if(empty($date_from) || empty($date_to)) {

                $is_valid = false;
                
            }

            if($date_from_converted > $date_to_converted) {
                
                $is_valid = false;
            }

            if(!empty($leave_type_id )) {

                if($leave_type_id == '2') { //half day

                    if($date_from_converted != $date_to_converted){

                    }

                }

            }

            
            if(!empty($status)) {


                $status   = strtolower($status);
                $status_id = $this->statusArray[$status];
                $list[$key]['status_id'] = $status_id;
                if(!in_array($status_id,[0,1,2,3])) {

                    $is_valid = false;

                }

            }



            $list[$key]['is_valid'] = $is_valid;

            


        }


    return $list;

    }

    public function processImport($params)
    {

        $bulk_id = $params['bulk_id'];
        $action = $params['action'];
        $list = $this->getTempDetails($bulk_id);
        if($action == '1') {
            
            if(!empty($list)) {

               
                $createtime = time();
                $remark = '' ;
                $updated_by = $this->adminID;
                $sql = "INSERT INTO leave_request (user_id, leave_id, leave_type,date_from,date_to,reason,status,createtime,updatetime,updated_by,remark) VALUES ";
                $insert = '';
                $file_name = '';
                foreach($list as $key=>$value){

                    if($value['is_valid']){
                        
                        $file_name = $value['upload_filename'];
                        $insert .= "('" . $value['user_id'] . "', '" . $value['leave_name'] . "', '" . $value['leave_type_id'] . "', '" . $value['date_from_converted'] . "', '" . $value['date_to_converted'] . "', '" . $value['reason'] . "', '" . $value['status_id'] . "', '" . $createtime . "', '" . $createtime . "', '" . $updated_by . "', '" . $remark . "'),";

                    }
                    
                }

                if(!empty($insert)) {

                    $insert = rtrim($insert, ',');
                    $sql   .= $insert;
                    $this->query($sql);
                    $this->execute();
                    
                }

                
                if($file_name) {
                    $this->DeleteTempLeave($bulk_id);
                    $activity = "Imported new leave request.Import File -".$file_name;
                    return $this->adminActivityLog($activity);

                }else{
                   return $this->DeleteTempLeave($bulk_id);    
                }

                   

            }
            

        }
        if($action == '2') {

            return $this->DeleteTempLeave($bulk_id);

        }


        


    }

    public function DeleteTempLeave($id)
    {

        $this->query("DELETE FROM `temp_leave_bulk` WHERE bulk_id='$id'");
        return $this->execute();

        
    }




    public function insertBulk($params)
    {


        $username    = !empty($params['username']) ? $params['username'] :'';
        $bulk_id     = !empty($params['bulk_id']) ? $params['bulk_id'] :'';
        $upload_file = !empty($params['upload_file']) ? $params['upload_file'] :'';
        $year        = !empty($params['year']) ? $params['year'] :'';
        $request     = !empty($params['request']) ? json_encode($params['request']) :'';

        $sql = "INSERT INTO temp_staff_leave_balance_bulk SET username='$username',year='$year',request='$request',bulk_id='$bulk_id',upload_file='$upload_file'";
        $this->query($sql);
        $this->execute();

    }

    public function getTempData($bulk_id)
    {

        $sql  = "SELECT * FROM temp_staff_leave_balance_bulk WHERE bulk_id='$bulk_id'";
        $list = $this->callsql($sql,'rows');
        foreach($list as $key=>$value){

            $username    = !empty($value['username']) ? $value['username'] : '';
            $year        = !empty($value['year']) ? $value['year'] : '';
            $request     = !empty($value['request']) ? json_decode($value['request'],true) : [];
            $is_valid    = true;

            if(empty($username) || empty($year) || empty($request) ) {

                $is_valid = false;

            }

            $user_id = $this->callsql("SELECT user_id FROM user WHERE username='$username'",'value');
            $list[$key]['user_id'] = $user_id;
            if(empty($user_id)) {

                $is_valid = false;

            }

            if($year>date('Y')) {

                $is_valid = false;

            }



            foreach($request as $requestkey=>$requestvalue){

                $leave_name     = strtolower($requestvalue['leave_title']);
                $leaveDetails   = $this->callsql("SELECT id,allowed_count FROM leave_type WHERE LOWER(leave_name)='$leave_name'",'row');
                if(!empty($leaveDetails)) {
                    

                    $request[$requestkey]['id']             = $leaveDetails['id'];
                    $request[$requestkey]['allowed_count']  = $leaveDetails['allowed_count'];
                    $request[$requestkey]['is_valid']       = true;
                    if($requestvalue['balance'] > $leaveDetails['allowed_count']) { //check greater than allowed count

                        $request[$requestkey]['is_valid'] = false;
                        $is_valid = false;

                    }
                    if(!is_numeric($requestvalue['balance'])) {

                        $request[$requestkey]['is_valid'] = false;
                        $is_valid = false;

                    }

                }else{

                    $request[$requestkey]['id']       = '';
                    $request[$requestkey]['allowed_count']       = '';
                    $request[$requestkey]['is_valid'] = false;
                    $is_valid = false;

                }

                




            }


           
            $list[$key]['is_valid'] = $is_valid;
            $list[$key]['request']  = $request;


        }

        return $list;

    }


    public function processLeaveBalanceImport($params)
    {

        $action  = $params['action'];
        $bulk_id = $params['bulk_id'];

        if($action == '1') { //proceed

            $list = $this->getTempData($bulk_id);

            foreach($list as $key=>$value){

                $is_valid    = $value['is_valid'];
                $year        = $value['year'];
                $user_id     = $value['user_id'];
                $upload_file = $value['upload_file'];
                if($is_valid) {

                    foreach($value['request'] as $requestkey=>$requestvalue)
                    {
                        
                        $leave_id      = $requestvalue['id'];
                        $balance       = $requestvalue['balance'];
                        $allowed_count = $requestvalue['allowed_count'];
                        $currentBalanceDetailsId = $this->callsql("SELECT id FROM leave_report WHERE staff_id='$user_id' AND leave_id='$leave_id' AND year='$year'",'value');
                        $time = time();
                        if(!empty($currentBalanceDetailsId)) {
                        
                            $sql = "UPDATE leave_report SET balance='$balance',updated_at='$time' WHERE id='$currentBalanceDetailsId'";
                            $this->query($sql);
                            $this->execute();

                            $activity = "Admin updated the leave balance through import.id-".$currentBalanceDetailsId.",Staff id -".$user_id.',leave id-'.$leave_id.',balance-'.$balance.'.Import File-'.$upload_file;
                            


                        }else{

                            $sql = "INSERT INTO leave_report SET staff_id='$user_id',year='$year',leave_id='$leave_id',total='$allowed_count',balance='$balance',created_at='$time',updated_at='$time'";
                            $this->query($sql);
                            $this->execute();
                            $id = $this->lastInsertId();
                            $activity = "Admin added leave balance.Id-".$id.'Staff id-'.$user_id.',Balance-'.$balance;


                        }

                        $this->adminActivityLog($activity);


                    }

                }



            }

            $this->query("DELETE FROM `temp_staff_leave_balance_bulk` WHERE bulk_id='$bulk_id'");
            return $this->execute();

        }
        if($action == '2') //cancel
        {

            $this->query("DELETE FROM `temp_staff_leave_balance_bulk` WHERE bulk_id='$bulk_id'");
            return $this->execute();

        }

        return false;
        


    }

}
