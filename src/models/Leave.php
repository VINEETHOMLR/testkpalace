<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Leave extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "leave_type";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
    }
     public function getList($data)
    {
   
        $where = ' WHERE id!=0 AND status!=2';

        if(!empty($data['leave_name'])){
            $where .= " AND leave_name LIKE '%$data[leave_name]%' ";
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

               
                //$result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
        
                $result['data'][$key]['leave_name']   = $value['leave_name']; 
                $result['data'][$key]['allowed_count'] = $value['allowed_count'];

                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Leave/UpdateLeaveType/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a><button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';

                if(empty($value['status'])){
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }else{
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }

                $result['data'][$key]['status'] = $status;
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = !empty($data['page'])?$data['page']:'1';
        $result['perPage'] = $this->perPage;
        return $result;
    }

    public function updateLeaveType($ip){

        $time   = time();
        $leave_name = htmlspecialchars_decode($ip['leave_name']);
        $leave_name = str_replace('&amp;', '&', $leave_name);
        $allowed_count = $ip['allowed_count'];
        $this->query("UPDATE $this->tableName SET leave_name='$leave_name',allowed_count = '$allowed_count',updated_at='$time',updated_by='$this->adminID' WHERE `id`='$ip[edit]'");
        $this->execute();
          
        $this->adminActivityLog("Updated Leave Type -" .$leave_name. " id- ".$ip['edit']);

        return true;
    }
    public function addLeaveType($ip){

        $time  = time(); 
        $status = 0;
       
        $this->query("INSERT INTO $this->tableName SET leave_name='$ip[leave_name]',allowed_count = '$ip[allowed_count]',status='$status',created_at='$time',updated_at='$time',created_by='$this->adminID' ");
            
        $this->execute();
        $last_id = $this->lastInsertId();

        $this->adminActivityLog("Leave Type added -" .$ip['leave_name']. " id- ".$last_id);

        return true;
    }


     public function getLeaveTypeById($id){

        $time   = time();
   
        $details=$this->callsql("SELECT * FROM $this->tableName WHERE `id`='$id'","row");
    
        return $details;
    }

    public function getAllLeaveType(){
   
        $details = $this->callsql("SELECT * FROM $this->tableName WHERE status='0'","rows");
    
        return $details;
    }

    public function checkExist($key,$edit){

        $time   = time();

        $details=$this->callsql("SELECT COUNT(id) FROM $this->tableName WHERE `leave_name` LIKE '%$key%' AND id !='$edit' ","value");
    
        return $details;
    }

    public function deleteLeavetype($ID){
      
      $this->query("UPDATE $this->tableName SET status= 2 WHERE id='$ID'");
      
      $this->execute();
      $leave_name = $this->getLeaveTypeById($ID);
      $this->adminActivityLog("Leave Type Deleted -" .$leave_name['leave_name']. " id- ".$ID);
      return true;
   }

   public function UpdateAlcoholCategoryStatus($id,$status){

      $this->callsql("UPDATE $this->tableName SET status='$status' WHERE id='$id'");
      $leave_name = $this->getLeaveTypeById($id);
      $this->adminActivityLog("Leave Type Status updated - " .$leave_name['leave_name']. " id- ".$id);
      return true;
    }

    public function getDetails($id)
    {

        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }
}