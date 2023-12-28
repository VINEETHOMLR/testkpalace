<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Foc extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "foc_remarks";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

        
    }
     public function getFocList($data)
    {

        $where = ' WHERE id!=0';

       
        
        if(!empty($data['status']) ||  in_array($data['status'],['0','1'])){
            $where .= " AND status = '$data[status]' ";
        }

        

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];

        $statusArray = ['0'=>'Active','1'=>'Cancelled/Deleted'];

        $edit_permission   = in_array(57,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $delete_permission = in_array(58,$this->admin_services) || $this->admin_role == '1' ? true : false;


        foreach ($result['data'] as $key => $value) {

               
                //$result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);

            $action = '';
            $checked = $value['status'] == '0' ? 'checked' : ''; 

            $action .= $edit_permission ?  '<button class="btn btn-info" onclick="showEditModal('.$value['id'].')">Edit</button>' : '';

            //$action .= $delete_permission ?  '<button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>' : '';

            $result['data'][$key]['status']        = $edit_permission ? '<label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            
                                            <input type="checkbox" '.$checked.'>
                                          <span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].')"></span>
                                                </label>' : $statusArray[$value['status']];


                
            $result['data'][$key]['action'] = $action;
      
            // if(empty($value['status'])){
            //     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
            // }else{
            //     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
            // }
            
            //$status = $statusArray[$value['status']];


            //$result['data'][$key]['status'] = $status;
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

    public function getDetails($id)
    {


        return $this->callsql("SELECT id,remark FROM $this->tableName WHERE id='$id'",'row');

    }

    public function  updateRemark($params)
    {

        $id = $params['id'];
        $remark = $params['remark'];
        $sql = "UPDATE $this->tableName SET remark='$remark' WHERE id='$id'";
        $this->query($sql);
        $this->execute();
        $this->adminActivityLog("Foc Remark Upadted.Remark ID -".$id);
        return true;

    }

    public function  addRemark($params)
    {

        $remark = $params['remark'];
        $created_by = $this->adminID;
        $sql = "INSERT INTO  $this->tableName SET remark='$remark',status='0',created_by='$created_by'";
        $this->query($sql);
        $this->execute();
        $id = $this->lastInsertId();
        $this->adminActivityLog("Foc Remark Added.Remark ID -".$id);
        return true;

    }


    public function deleteRemark($id){
      
        $this->query("UPDATE $this->tableName SET status= 1 WHERE id='$id'");
        $this->execute();
        $this->adminActivityLog("Foc Remark Deleted.Remark ID -".$id);
        return true;
        
    }

    public function updateStatus($params)
    {

        $id     = $params['id'];
        $status = $params['status'];
        $this->query("UPDATE $this->tableName SET status='$status' WHERE id='$id'");
        $this->execute();
        $statusArray = ['0'=>'Active','1'=>'Inactive'];

        $activity = 'Foc Reamrk status changed to '.$statusArray[$status].'.Id-'.$id;

        return $this->adminActivityLog($activity);
        

    }

    public function getAllFocList()
    {
        return $this->callsql("SELECT id,remark FROM foc_remarks WHERE status='0' ORDER BY id DESC",'rows');
    }
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        return $this->execute();
       
    }
     
     

    

    

    
}
