<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class User extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());

        $this->tableName    = "user";
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
        $this->IP           = $_SERVER['REMOTE_ADDR'];
        $this->perPage      = 10;
        $this->CommonModal  = (new CommonModal);

    }
    
    
    public function adminActivityLog($activity){

        $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , createtime= '$time' , createip='$this->IP' ");
        $this->execute();

        return true;
    }

    public function createUser($data){
        $time      = time();


        $transpin  = empty($data['transpin']) ? '' : md5($data['transpin']);

        $query     = "INSERT INTO user (`username`,`password`,`role_id`,`transpin`,`first_name`,`last_name`,`nick_name`,`mobileno`,`mobileno2`,`passport_number`,`gender`,`dob`,`nationality`,`email`,`status`,`createid`,`created_at`,`permission`,`position`,`department`) VALUES
                        (:username,:password,:role_id,:transpin,:first_name,:last_name,:nick_name,:mobileno,:mobileno2,:passport_number,:gender,:dob,:nationality,:email,:status,:createid,:created_at,:permission,:position,:department)";

        $this->query($query);
        $this->bind(':username',$data['username']);
        //$this->bind(':uniqueid',$uniqueId);
        $this->bind(':password',md5($data['password']));
        $this->bind(':role_id',$data['role_id']);
        $this->bind(':transpin',$transpin);
        $this->bind(':first_name',$data['first_name']);

        $this->bind(':last_name',$data['last_name']);
        $this->bind(':nick_name',$data['nick_name']);
        $this->bind(':mobileno2',$data['mobileno2']);
        $this->bind(':passport_number',$data['passport_number']);
        $this->bind(':mobileno',$data['mobileno']);
        $this->bind(':gender',$data['gender']);
        $this->bind(':dob',$data['dob']);
        $this->bind(':nationality',$data['nationality']);
        $this->bind(':email',$data['email']);
        $this->bind(':status',0);
        $this->bind(':createid', 1);
        $this->bind(':created_at',$time);
        $this->bind(':permission',$data['services']);
        $this->bind(':position',$data['position']);
        $this->bind(':department',$data['department']);
        $this->execute();

        $userId = $this->lastInsertId();
        
        // $nextId = bcadd($userId,1);
        
        $uniqueId = "KPSF-".$this->addPrefix($userId);

        $this->query("UPDATE user SET staff_id='".$uniqueId."' WHERE user_id='".$userId."'");
        $this->execute();
        

        return true;
    }

    public function createRoomTablet($params)
    {

        $username = $params['username'];
        $password = md5($params['password']);
        $room_id  = $params['room_id'];
        $role_id  = $params['role_id'];
        $permission  = $params['permission'];
        $status  = $params['status'];
        $createid  = $this->adminID;
        $created_at  = time();

        $sql = "INSERT INTO $this->tableName SET username='$username',password='$password',room_id='$room_id',role_id='$role_id',status='$status',createid='$createid',created_at='$created_at',permission='$permission'";
        $this->query($sql);
        $this->execute();

        $userId = $this->lastInsertId();
        
        // $nextId = bcadd($userId,1);
        
        $uniqueId = "KPSF-".$this->addPrefix($userId);

        $this->query("UPDATE user SET staff_id='".$uniqueId."' WHERE user_id='".$userId."'");
        $this->execute();

        $activity = "Created a new room tablet.Id-$userId";
        return $this->adminActivityLog($activity);




    }

    public function UpdateRoomTablet($params)
    {

        $username = $params['username'];
        $room_id  = $params['room_id'];
        $role_id  = $params['role_id'];
        $status   = $params['status'];
        $user_id  = $params['user_id'];
        $permission  = $params['permission'];
        //$createid  = $this->adminID;
        //$created_at  = time();

        $sql = "UPDATE $this->tableName SET username='$username',room_id='$room_id',role_id='$role_id',status='$status',permission='$permission' WHERE user_id='$user_id'";
        $this->query($sql);
        $this->execute();

       
        $activity = "Updated a room tablet.id-".$user_id;
        return $this->adminActivityLog($activity);

    }

    public function updateUser($params){
        $time=time(); 


        $this->query("UPDATE user SET username='".$params['username']."',role_id='".$params['role_id']."',first_name='".$params['first_name']."',last_name='".$params['last_name']."',nick_name='".$params['nick_name']."',mobileno='".$params['mobileno']."',mobileno2='".$params['mobileno2']."',gender='".$params['gender']."',passport_number='".$params['passport_number']."',dob='".$params['dob']."',nationality='".$params['nationality']."',email='".$params['email']."',permission='".$params['services']."',position='".$params['position']."',department='".$params['department']."' WHERE user_id='".$params['user_id']."'");
            $this->execute();
            return true;
    }
    public function UserUpdation($params){
        $time=time(); 

        $this->query("UPDATE user SET username='".$params['username']."',role_id='".$params['role_id']."',first_name='".$params['first_name']."',last_name='".$params['last_name']."',mobileno='".$params['mobileno']."',nick_name='".$params['nick_name']."',mobileno2='".$params['mobileno2']."',passport_number='".$params['passport_number']."',gender='".$params['gender']."',dob='".$params['dob']."',nationality='".$params['nationality']."',email='".$params['email']."',permission='".$params['services']."',position='".$params['position']."',department='".$params['department']."' WHERE user_id='".$params['user_id']."'");
            $this->execute();
            return true;
    }
    public function getServicesList(){
        return $this->callsql("SELECT * FROM user_service_master","rows");
    }

    public function getServicesLists($user_id){
        
        $userDetails = $this->callsql("SELECT * FROM user WHERE user_id='$user_id'",'row');


        $selected_services = !empty($userDetails['permission']) ? $userDetails['permission'] : '';
        $list = $this->callsql("SELECT * FROM user_service_master","rows");

        
        $selected_services = explode(',',$selected_services);

        

        foreach($list as $key=>$value)
        {

            $subPermission_ids = $this->callsql("SELECT GROUP_CONCAT(id) as ids FROM user_services WHERE master_id='$value[id]'",'value');

            $subPermission_ids = explode(',',$subPermission_ids);
            $checked = true;
            foreach($subPermission_ids as $key1=>$value1){

                if(!in_array($value1,$selected_services)) {
                    $checked = false;
                }

            }

            $list[$key]['all_checked'] = $checked;



        }


        return $list;
    }

   public function getSubServicesLists($user_id)
   {
        $userDetails = $this->callsql("SELECT * FROM user WHERE user_id='$user_id'",'row');



        $selected_services = !empty($userDetails['permission']) ? $userDetails['permission'] : '';
        $list = $this->callsql("SELECT * FROM user_service_master","rows");

      
        $selected_services = explode(',',$selected_services);

     

        foreach($list as $key=>$value)
        {

            $subPermission_ids = $this->callsql("SELECT GROUP_CONCAT(id) as ids FROM user_services WHERE master_id='$value[id]'",'value');

            $subPermission_ids = explode(',',$subPermission_ids);
            $checked = true;
            foreach($subPermission_ids as $key1=>$value1){

                if(!in_array($value1,$selected_services)) {
                    $checked = false;
                }

            }

            $list[$key]['all_sub_checked'] = $checked;



        }


        return $list;

   }

    public function checkhaveallpermission($user_id)
    {

        $subservice = $this->callsql("SELECT * FROM user_services WHERE status='0'","rows"); 


        $userDetails = $this->callsql("SELECT * FROM user WHERE user_id='$user_id'",'row');
        $selected_services = !empty($userDetails['permission']) ? explode(',',$userDetails['permission']) : [];
        $checked = true;
        foreach($subservice as $key=>$value)
        {

            if(!in_array($value['id'],$selected_services)) {
                    $checked = false;
            } 

        }

        return $checked;


   }


    public function getSubServicesList(){
        return $this->callsql("SELECT * FROM user_services","rows");
    }
    

     public function getAllServiceArray(){

        $data = $this->callsql("SELECT * FROM user_services WHERE status =0 ORDER BY id ASC",'rows');
      
        foreach($data as $key=>$value){
         
         $service_name[$value['id']]=$value['service_name'];
        }
        
        return $service_name;
    }
     public function GetServiceMaster($id){
    
         $a=[];
         $i=0;
         $data3 = $this->callsql("SELECT role FROM permission WHERE permission_id='$id' ",'row');      
         $list=explode(",",$data3['service_id']);
         $a=[];
         foreach($list as $key => $value1){
           $id = $this->callsql("SELECT master_id FROM user_services WHERE id=$value1",'value');
           $a[$i]=$id;
           $i++;
         }
        
         $a=array_unique($a);
         // $user_permission = ['1'=>'Floor Staff','2'=>'Inventory Admin','3'=>'Management Admin','4'=>'HR Admin','5'=>'Manager','6'=>'Manager','7'=>'Room Tablet'];
        
         $data = $this->callsql("SELECT * FROM user_service_master WHERE status=0 ORDER BY id ASC",'rows');

         foreach($data as $key1=>$value){
    
           if(in_array($value['id'],$a)){
        
             $data1[$key1]['master_name']=$value['master_name'] ;
             $data1[$key1]['id']=$value['id'] ;
             $data1[$key1]['service']=$this->callsql("SELECT * FROM user_services WHERE master_id='$value[id]'",'rows');
           }
         }
        return $data1;
    }
     public function GetServiceMasterAll(){

        $data = $this->callsql("SELECT * FROM user_service_master WHERE status=0 ORDER BY id ASC",'rows');
         foreach($data as $key=>$value){

             $data[$key]['master_name']=$value['master_name'] ;
             $data[$key]['service']=$this->callsql("SELECT * FROM user_services WHERE master_id='$value[id]' AND status =0",'rows');
         }
         //echo "<pre>";
        //print_r($data);exit;
        return $data;
    }
     public function GetServiceMasterByAdminUser($admin_services){
        
         $a=[];
         $i=0;
         $data3 = $this->callsql("SELECT role FROM permission WHERE permission_id='$id' ",'row');  
          
         $list=$admin_services;
         $a=[];
         foreach($list as $key => $value1){
           $id = $this->callsql("SELECT master_id FROM user_services WHERE id=$value1",'value');
           $a[$i]=$id;
           $i++;
         }
        
         $a=array_unique($a);
         $data = $this->callsql("SELECT * FROM user_service_master WHERE status=0 ORDER BY id ASC",'rows');
         foreach($data as $key1=>$value){
    
           if(in_array($value['id'],$a)){
    
             $data1[$key1]['master_name']=$value['master_name'] ;
             $data1[$key1]['id']=$value['id'] ;
             $data1[$key1]['service']=$this->callsql("SELECT * FROM user_services WHERE master_id='$value[id]'",'rows');
           }
         }
        
        return $data1;

         
    }

    public function getRolePermission($role_id){
         return $this->callsql("SELECT permissions FROM permission WHERE role='$role_id' ",'row');
    }

    function getUsername($user_id){

       return $this->callsql("SELECT username FROM user WHERE user_id=$user_id","value");
    }

    function getname($user_id){

       return $this->callsql("SELECT CONCAT(first_name,' ',last_name) as name FROM user WHERE user_id=$user_id","value");
    }


   public function getUserList($data){



        $dateofbirth =strtotime($data['dob']);
        $where = ' WHERE a.user_id!=0 ';
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
          $where .= " AND a.username = '$data[username]' ";
        }

        if($data['staff_id']!=""){
          $where .= " AND a.staff_id LIKE '%".$data['staff_id']."%' ";
        }
        if($data['position']!=""){
          $where .= " AND a.position = '$data[position]'  ";
        }
        if($data['department']!=""){
          $where .= " AND a.department = '$data[department]'  ";
        }
         
        if($data['first_name']!=""){
          $where .= " AND a.first_name = '$data[first_name]' ";
        }
        
        if($data['nick_name']!=""){
          $where .= " AND a.nick_name = '$data[nick_name]' ";
        }
         if($data['passport_number']!=""){
          $where .= " AND a.passport_number = '$data[passport_number]' ";
        }


        if($data['mobile']!=""){
            $where .= " AND a.mobileno LIKE '%".$data['mobile']."%'  OR a.mobileno2 LIKE '%".$data['mobile']."%'";

          
        }

        if($data['dob']!=""){
            $where .= " AND a.dob = '$dateofbirth'";
        }
        
    
        // if($data['userID']!=""){
        //     $where .= " AND us.username LIKE '%$data[userID]%'";
        // }
        if(!empty($data['user_id'])){
            $where .= " AND a.user_id = '$data[user_id]' ";
        }

        // if($data['username']!=""){
        //     $where .= " AND us.email LIKE '%$data[username]%'";
        // }    
        // if(!empty($data['dob'])){

            
        //     $date_to   = strtotime($data['dob']." 23:59:59");

        //     $where    .= " AND b.dob BETWEEN '$date[dob]'  ";
        // }  


        $pagecount = ($data['page'] - 1) * $this->perPage;



        $count = $this->callsql("SELECT COUNT(DISTINCT user_id) FROM $this->tableName as a $where ","value");

       

       if(!empty($data['export'])){

         $result['data'] = $this->callsql("SELECT * FROM $this->tableName as a  $where ORDER BY a.user_id DESC","rows");
        }else{
        $result['data'] = $this->callsql("SELECT * FROM $this->tableName as a  $where ORDER BY a.user_id DESC LIMIT $pagecount,$this->perPage","rows");
        }
       
       


         foreach ($result['data'] as $key => $value) {
            
            $result['data'][$key]['position']      = $this->callsql("SELECT name FROM `positions` WHERE id='$value[position]' ","value");
            $result['data'][$key]['department']      = $this->callsql("SELECT name FROM `departments` WHERE id='$value[department]' ","value");
            $result['data'][$key]['nationality']      = $this->callsql("SELECT name FROM `country` WHERE id='$value[nationality]' ","value");
            
            $result['data'][$key]['account_status']      = (empty($value['status'])) ? 'Active' : 'Blocked';

        }
        if($count==0){
            $result['data'] = array();
        }

        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;



        return $result;
    }

    public function getuserdetails($id){

          $customer['info']                  = $this->callsql("SELECT * FROM $this->tableName WHERE user_id='$id'",'row');  

          if(empty($customer['info']))
            return [] ;

          
          $uId                           = $customer['info']['user_id'];
          //$customer['extra']                 = $this->callsql("SELECT * FROM customer_extra WHERE user_id='$uId'",'row'); 
          //$customer['wallet']                = $this->callsql("SELECT * FROM customer_wallet WHERE user_id='$uId'",'row');       
          //$customer['lang']                = $this->callsql("SELECT * FROM customer_wallet WHERE user_id='$uId'",'row');       

          return $customer;
    }
    public function getcustomerdetails($id){

          $customer['info']                  = $this->callsql("SELECT * FROM $this->tableName WHERE user_id='$id'",'row');  

          if(empty($customer['info']))
            return [] ;

          
          $uId                           = $customer['info']['id'];
               

          return $customer;
    }


      public function UpdatePass($new,$uid)
    {
      $this->query("UPDATE `user` SET  `password`='$new' WHERE user_id='$uid'");
      if($this->execute())
      {
          return true;

      }
      return false;
    }
      public function UpdatePin($newpin,$id)
    { 
    
      
      $this->query("UPDATE `user` SET  `transpin`='$newpin' WHERE user_id='$id'");
      if($this->execute())
      {
          return true;

      }
      return false;
    }
    public function updateCustomerStatus($status,$value)
    {
        $this->query("UPDATE $this->tableName SET status='$status' WHERE user_id='$value'");
        $this->execute();
        return true;
    }
   public function getUserInfo($user_id) {

    return $this->callSql("SELECT * FROM $this->tableName WHERE user_id = '$user_id' ","row");
     }

    public function getUserData($id){
         return $this->callsql("SELECT * FROM user WHERE user_id='$id' ",'row');

    }



  

  public function getUsers()
  {
    return $this->callSql("SELECT user_id FROM $this->tableName","row");
  }

   public function getEmailById($key){


        return $this->callsql("SELECT `user_id` as id,CASE
                             WHEN `username` like '$key%' THEN `username`
                            
                             ELSE ''
                             END AS text  FROM `user` WHERE username like '$key%' ",'rows'); 
    }


    public function searchUsers($key){


        return $this->callsql("SELECT `user_id` as id,CASE
                             WHEN `username` like '$key%' THEN `username`
                            
                             ELSE ''
                             END AS text  FROM `user` WHERE username like '$key%' ",'rows'); 
    }

    public function searchCustomers($key){


        return $this->callsql("SELECT `id` as id,CASE
                             WHEN `username` like '$key%' THEN `username`
                            
                             ELSE ''
                             END AS text  FROM `customer` WHERE username like '$key%' ",'rows'); 
    }

    public function getMobileNumbers($user_ids)
    {

        $user_ids = !empty($user_ids) ? implode(',',$user_ids):'';
        if(!empty($user_ids)) {

            $sql = "SELECT CONCAT(mobile_country_code,'',mobile) as mobile FROM customer_extra WHERE user_id IN($user_ids)";
            $result = $this->callsql($sql,'rows');

            return $result;

        }

        return [];

    }

    public function checkRoomAlreadyAssigned($params)
    {

        $room_id = $params['room_id'];
        $id = !empty($params['id']) ? $params['id'] : '';

        $where = " WHERE status='0' ";
        $where .= " AND room_id='$room_id'";
        if(!empty($id)) {

            $where .= " AND user_id!='$id'";

        }

        $sql = "SELECT user_id FROM user $where";
        return $this->callsql($sql,'row');

    }

    public function getPermissionByRole($role_id)
    {

        return $this->callsql("SELECT permissions FROM permission WHERE role='6'",'value');

    }

    private function addPrefix($integer){
        $integerStr = (string)$integer;
        if (strlen($integerStr) < 6) {
            $zerosToAdd = 6 - strlen($integerStr);
            $prefixedInteger = str_repeat("0", $zerosToAdd) . $integerStr;
            return $prefixedInteger;
        } else {
            return $integerStr;
        }
    }


    

    //End User Management

    public function getDepartmentPosition($dept_id){
        return $this->callsql("SELECT id, name FROM positions WHERE dept_id='".$dept_id."'",'rows');
    }
}
