<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use inc\commonArrays;
use src\lib\Router;
use src\models\CommonModal;

class Permission extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());
        $this->tableName = "permission";
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
        $this->IP           = $_SERVER['REMOTE_ADDR'];
        $this->perPage      = 10;
        $this->CommonModal  = (new CommonModal);
        $this->getArray      = (new commonArrays)->getArrays();
        $this->roleArr       = $this->getArray['roleArr'];

    }


    public function getList()
    {

        $permissionList = $this->roleArr;
        
        $sql  = "SELECT * FROM $this->tableName WHERE status='1'";

        $list = $this->callsql($sql,'rows');

        $result =[];
        
        foreach($list as $key=>$value)
        {

            $permission_id  = $value['permission_id'];
            $permissions    = $value['permissions'];

            $result[$key]['role_id']    = $value['role'];
            $result[$key]['group_name'] = $permissionList[$value['role']];
            
            $services = explode(",", $permissions);
                 
            $selected = '<div class="row col-md-12">';
            
                 foreach ($services as $val) {
                     
                     $service_name    = $this->callsql("SELECT service_name FROM user_services WHERE id ='$val' ",'value');

                     $selected .= '<div class="col-md-6 col-xl-3 col-sm-12 col-12"><div class="dot"></div>'.$service_name.'</div>';
                }
            
            $selected.= "</div>";

            $result[$key]['permission_type'] = $selected;


            $result[$key]['action'] = '<a href="'.BASEURL.'UserService/Edit/?id='.(base64_encode($value['permission_id'])).'"><button class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>&nbsp;'.Root::t('app','edit_text').'</button></a>';

        }

        return $result;


    }


    public function GetServiceMasterAll(){

        $data = $this->callsql("SELECT * FROM user_service_master WHERE status=0 ORDER BY id ASC",'rows');
            
            foreach($data as $key=>$value){

                 $data[$key]['master_name']=$value['master_name'] ;
                 $data[$key]['service']=$this->callsql("SELECT * FROM user_services WHERE master_id='$value[id]' AND status =0",'rows');
            }

        return $data;
    }

    public function getPermissionById($permission_id){ 

        $result = $this->callsql("SELECT * FROM $this->tableName WHERE permission_id='$permission_id' ",'row');
        return $result;
    }

    public function serviceUpdate($data){
          
        $newServiceVal = implode(',', $data['permissions']);

        $stmt   = "UPDATE permission SET permissions='$newServiceVal' WHERE permission_id='$data[permission_id]' ";
        $this->query($stmt);
        $this->execute();
           
        $adminuseranme = $this->callsql("SELECT username FROM admin WHERE id='$this->admin' ",'value');
        $act=$adminuseranme." User Service Edited ";
        $this->adminActivityLog($act);

        return true;
    }

   
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }


 
  
}
