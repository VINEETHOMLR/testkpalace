<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class Admin extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "admin";
        $this->admin     = $_SESSION[SITENAME.'_admin'];
        $this->perPage   = 10;
    }

    public function getLogin($id){

        return $this->callsql("SELECT login_time,login_ip FROM admin_login_log WHERE admin_id='$id' ORDER BY id DESC LIMIT 1","row");
    }

    public function getAdmin() {   
       
       return $this->callsql( "SELECT id,username,name,service_group_id,email,current_login_time,last_login_time,last_login_ip FROM $this->tableName WHERE id='$this->admin'","row");
    }

    public function getadminList($data){

        $where = " WHERE id!='$this->admin' AND status !=3 "; 

        if(empty($_SESSION[SITENAME.'_admin_role'])){
            $where .=" AND createby='$this->admin'";
        }

        if($data['status'] !=""){
           
            $where .= " AND status = '$data[status]' ";

        }

        if(!empty($data['username'])){
           
            $where .= " AND username LIKE '%$data[username]%' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){ 

            $where .= " AND current_login_time  BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        } 

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName $where ","value");

        $result['data'] = $this->callsql("SELECT id,username,name,email,status,createtime,last_login_time,last_login_ip FROM $this->tableName $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");
        foreach ($result['data'] as $key => $value) {

            $loginData=$this->getLogin($value['id']);
            $login = !empty($loginData['logintime']) ? (date("d-m-Y H:i:s",$loginData['logintime']))."<br>".$loginData['login_ip']:'';
            $login = (empty($loginData['logintime'])) ? "-" : $login;
            $createTime = (empty($value['create_time'])) ? "-" : date("d-m-Y H:i:s",$value['createtime']);
            $lastSeen = (empty($value['last_login_time'])) ? "-" : date("d-m-Y H:i:s",$value['last_login_time']);

            $checked = !empty($value['status']) ? '' : 'checked';
            
            $result['data'][$key]['login']  = $login;
            $result['data'][$key]['create'] = $createTime;
            $result['data'][$key]['lastSeen'] = $lastSeen."<br>".$value['last_login_ip'];
			
			$action    =  '<ul class="table-controls">';
			
            $action    .=  '<li><a href="'.BASEURL.'Admin/Profile/?admin='.base64_encode($value['id']).'" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>';
		
			
            $action    .=  '<li><a onclick="DeleteAdmin('.$value['id'].')" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>';
		
            $action    .=  '</ul>';
			
            $result['data'][$key]['action'] = $action;
            
            $result['data'][$key]['Status'] = '<label class="switch s-primary mb-0">
                                                   <input type="checkbox" '.$checked.'><span class="slider round" onclick="switchStatus('.$value["id"].','.$value['status'].');"></span>
                                              </label>';
        }
        
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
       
        return $result;
    }

    public function insertAdmin($params){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR'];    
        $query=$this->query("INSERT INTO $this->tableName SET username='$params[username]',name='$params[name]',password='$params[password]',email='$params[email]',service_group_id='$params[services]',role='0',createtime='$time',createby='$this->admin',createip='$ip',status='0'");

        $this->execute($query);
        if($this->rowCount()>0){

            $adminuseranme = $this->callsql("SELECT username FROM admin WHERE id='$this->admin' ",'value');
            $act=$adminuseranme." created new admin ".$params['username'];
            $this->adminActivityLog($act);
            return true;
        }else
            return false;
    }
    public function memberInfoEdit($post){

         $time=time(); $ip=$_SERVER['REMOTE_ADDR']; 

         if(($_SESSION[SITENAME.'_admin']==$post['admin_id'])){

            $this->query("UPDATE admin SET username='$post[username]',service_group_id='$post[services]', name='$post[name]',email='$post[email]',updatetime='$time',updateip='$ip' WHERE id='$post[admin_id]'");
            $this->execute();

         }else{
            $this->query("UPDATE admin SET username='$post[username]',service_group_id='$post[services]', name='$post[name]',email='$post[email]',updatetime='$time',updateip='$ip' WHERE id='$post[admin_id]'");
            $this->execute();
         }

        $activity=$post['username']." Details Edited"; 
        $this->adminActivityLog($activity);
          
        return true;
    }

    public function getService($ser_id){ 

        $this->query("SELECT * FROM service_group WHERE id='$ser_id' ");
        return $this->resultset();
    }

    public function checkServiceName($name,$sId){
      if(empty($sId)){
        $data = $this->callsql("SELECT group_name FROM `service_group` WHERE  group_name='$name'","value") ;
      }else{
        $data = $this->callsql("SELECT group_name FROM `service_group` WHERE  group_name='$name' AND id!='$sId'","value") ;
      }
      return $data;
    }

    public function getServiceArray(){

        $this->query("SELECT * FROM admin_services");
        $this->execute(); 
        $res = array();
        foreach($this->resultset() as $row){  
          
            $res['rows'][] = array( 
                               'group_name'=>$row['group_name'],
                               'id' => $row['id']);
        }
        return $res;
    }


    public function serviceAdd($data){
        
           $newServiceVal = implode(',', $data['services']);
           $time   = time();
           $admin  = $this->admin;
           $ip     =$_SERVER['REMOTE_ADDR'];
           $stmt   = "INSERT INTO service_group SET group_name ='$data[servicegrpname]' , service_id='$newServiceVal',createtime='$time',createip='$ip',createid='$admin' ";
           $this->query($stmt);
           $this->execute();

           $ServiceId = $this->lastInsertId();

           $adminSer = $this->callsql("SELECT service_group_id FROM `admin` WHERE role=1 ","value");
           
           if($_SESSION[SITENAME.'_admin_role']!=1){
          
                 $adminSer = $this->callsql("SELECT service_group_id FROM `admin` WHERE role!=1 AND id='$this->admin'  ","value");
           }


           $ad_services = json_decode($adminSer,true);
           if(empty($ad_services)){ $ad_services=[];}
           array_push($ad_services, $ServiceId);
           if($_SESSION[SITENAME.'_admin_role']==1){
          
            $this->callsql("UPDATE `admin` SET service_group_id ='".json_encode($ad_services)."' WHERE role=1 ");    
           }else{
            $this->callsql("UPDATE `admin` SET service_group_id ='".json_encode($ad_services)."' WHERE role!=1 AND id='$this->admin' ");    
           }
           
           
           $adminuseranme = $this->callsql("SELECT username FROM admin WHERE id='$this->admin' ",'value');
           $act=$adminuseranme." Service added ";
           $this->adminActivityLog($act);
           
          return true;
    }

    public function serviceUpdate($data){
          
           $newServiceVal = implode(',', $data['services']);

           $stmt   = "UPDATE service_group SET group_name ='$data[servicegrpname]' , service_id='$newServiceVal' WHERE id='$data[servegrpid]' ";
           $this->query($stmt);
           $this->execute();
           
           $adminuseranme = $this->callsql("SELECT username FROM admin WHERE id='$this->admin' ",'value');
           $act=$adminuseranme." Service Edited ";
           $this->adminActivityLog($act);

          return true;
    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->admin;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }

    public function UpdateSlotStatus($id,$status){

      $this->query("UPDATE `admin` SET `status` = '$status' WHERE `id`='$id'");
      $this->execute();

      return true;
    }

    public function deleteAdmin($id){

        $this->query("UPDATE `admin` SET `status` = 3 WHERE `id`='$id'");
        $this->execute();

        return true;
    }

    public function getActivities()
    {
        $data = $this->callsql("SELECT action,createtime FROM admin_activity_log WHERE admin_id=$this->admin",'rows');

        return $data;

    }

    public function getadminActivity($data){

        $where = " WHERE admin_id!='$this->admin' "; 

        if(!empty($data['username'])){

            $where .= " AND admin_id = '$data[username]' ";
        }

        if(!empty($data['datefrom']) && !empty($data['dateto'])){ 

            $where .= " AND createtime  BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        } 

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_activity_log` $where ","value");

        $result['data']=$this->callsql("SELECT * FROM `admin_activity_log` $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {
           $username = $this->callsql("SELECT username FROM `admin` WHERE `id` = '$value[admin_id]'",'value'); 
           $result['data'][$key]['subAdmin'] = ucwords($username);
           $result['data'][$key]['time'] = date("d-m-Y H:i:s",$value['createtime']);
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        
        return $result;
    }

    public function getSubadmin(){

       return $this->callsql("SELECT username,id FROM `admin` WHERE `role` != '1' ",'rows'); 
    }

    public function getActivity($filter){

        $pagecount = ($filter['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_activity_log` WHERE admin_id='$filter[admin]' ","value");

        $this->query("SELECT * FROM admin_activity_log WHERE admin_id='$filter[admin]' ORDER BY id DESC LIMIT $pagecount,$this->perPage");

        $data = ['data' => $this->resultset()]; 

        if($count==0){
            $data['data'] = array();
        }
        $data['count']   = $count;
        $data['curPage'] = $filter['page'];
        $data['perPage'] = $this->perPage;
        
        return $data;
    } 

    public function getLoginLog($filter){

        $pagecount = ($filter['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(id) FROM `admin_login_log` WHERE admin_id='$filter[admin]' ","value");

        $this->query("SELECT * FROM admin_login_log WHERE admin_id='$filter[admin]' ORDER BY id DESC LIMIT $pagecount,$this->perPage");

        $data = ['data' => $this->resultset()]; 

        if($count==0){
            $data['data'] = array();
        }
        $data['count']   = $count;
        $data['curPage'] = $filter['page'];
        $data['perPage'] = $this->perPage;
        
        return $data;
    }

    public function getSiteData($key){

        return $this->callsql("SELECT data FROM site_data WHERE keyvalue= '$key'",'value');
    }

    public function UpdateSiteStatus($status){

        $this->query("UPDATE `site_data` SET `data`='$status' WHERE keyvalue='maintanace_status'");
        $this->execute();
        
        if($this->execute()){
            $act  = empty($status) ? "Maintanace Disabled" : "Maintanace Enabled";
            $this->adminActivityLog($act);
            return true;
        }else
            return false; 
    }

    public function getCoinID($coinCode){

       $this->query("SELECT id FROM `coin` WHERE coin_code=:code");
       $this->bind(":code",$coinCode);

       return $this->getValue();
    }
    public function getAllServiceArray(){

        $data = $this->callsql("SELECT * FROM services WHERE status =0 ORDER BY id ASC",'rows');
      
        foreach($data as $key=>$value){
         
         $service_name[$value['id']]=$value['service_name'];
        }
        
        return $service_name;
    }
     public function GetServiceMaster($id){
    
         $a=[];
         $i=0;
         $data3 = $this->callsql("SELECT service_id FROM service_group WHERE id='$id' ",'row');      
         $list=explode(",",$data3['service_id']);
         $a=[];
         foreach($list as $key => $value1){
           $id = $this->callsql("SELECT master_id FROM services WHERE id=$value1",'value');
           $a[$i]=$id;
           $i++;
         }
        
         $a=array_unique($a);
         $data = $this->callsql("SELECT * FROM service_master WHERE status=0 ORDER BY id ASC",'rows');
         foreach($data as $key1=>$value){
    
           if(in_array($value['id'],$a)){
    
             $data1[$key1]['master_name']=$value['master_name'] ;
             $data1[$key1]['id']=$value['id'] ;
             $data1[$key1]['service']=$this->callsql("SELECT * FROM services WHERE master_id='$value[id]'",'rows');
           }
         }
        return $data1;
    }
     public function GetServiceMasterAll(){

        $data = $this->callsql("SELECT * FROM service_master WHERE status=0 ORDER BY id ASC",'rows');
         foreach($data as $key=>$value){

             $data[$key]['master_name']=$value['master_name'] ;
             $data[$key]['service']=$this->callsql("SELECT * FROM services WHERE master_id='$value[id]' AND status =0",'rows');
         }
         //echo "<pre>";
        //print_r($data);exit;
        return $data;
    }
     public function GetServiceMasterByAdminUser($admin_services){
       //  print_r($admin_service_group);exit;
        // $sid = implode(",",$admin_service_group);
        
         $a=[];
         $i=0;
         //$all = $this->callsql("SELECT service_id FROM service_group WHERE id IN(".$sid.") ",'rows');
         $data3 = $this->callsql("SELECT service_id FROM service_group WHERE id='$id' ",'row');  
          
         $list=$admin_services;
         $a=[];
         foreach($list as $key => $value1){
           $id = $this->callsql("SELECT master_id FROM services WHERE id=$value1",'value');
           $a[$i]=$id;
           $i++;
         }
        
         $a=array_unique($a);
         $data = $this->callsql("SELECT * FROM service_master WHERE status=0 ORDER BY id ASC",'rows');
         foreach($data as $key1=>$value){
    
           if(in_array($value['id'],$a)){
    
             $data1[$key1]['master_name']=$value['master_name'] ;
             $data1[$key1]['id']=$value['id'] ;
             $data1[$key1]['service']=$this->callsql("SELECT * FROM services WHERE master_id='$value[id]'",'rows');
           }
         }
        
        return $data1;

         
    }


}
