<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\lib\RRedis;
use src\models\CommonModal;

class Positions extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName    = "positions";
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
        $this->IP           = $_SERVER['REMOTE_ADDR'];
        $this->perPage      = 10;
        $this->CommonModal  = (new CommonModal);
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

    }
    
    
    public function adminActivityLog($activity){

        $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , createtime= '$time' , createip='$this->IP'");
        $this->execute();

        return true;
    }

    public function createPosition($data){


        $name = $data['name'];
        $dept = $data['dept'];
        $time = time();
        $created_by = $this->adminID;
        $sql = "INSERT INTO $this->tableName SET name='".$name."', dept_id='".$dept."', created_by='".$created_by."', created_at='".$time."' ";
        $this->query($sql);
        $this->execute();
        $id = $this->lastInsertId();
        
        $activity = "New position created.Id-".$id;
        return $this->adminActivityLog($activity);

    }

    public function updatePosition($data)
    {

        $id = $data['id'];
        $name = $data['name'];
        $dept = $data['dept'];
        $time = time();
        $updated_by = $this->adminID;

        $sql = "UPDATE $this->tableName SET name='".$name."', dept_id='".$dept."',updated_at='".$time."',updated_by='".$updated_by."' WHERE id='".$id."' ";
        $this->query($sql);
        $this->execute();
        $id = $this->lastInsertId();
        
        $activity = "Updated position.Id-".$id;
        return $this->adminActivityLog($activity);

    }

    public function getDetails($id)
    {

        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }

    public function getActivePositions()
    {

        return $this->callsql("SELECT id,name FROM $this->tableName WHERE status='0' ",'rows');

    }
    public function getPositionsList($data){

       $where = ' WHERE a.id!=0 ';
       
        // if(in_array($data['status'],[0,1])){
        //     $where.= " AND a.status = '$data[status]' ";
        // }
        if($data['status'] == '0' || $data['status'] == '1') {

           $where.= " AND a.status = '$data[status]' "; 

        }
        

        $pagecount = ($data['page'] - 1) * $this->perPage;



        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName as a $where ","value");

       

       if(!empty($data['export'])){

         $result['data'] = $this->callsql("SELECT * FROM $this->tableName ","rows");
        }else{
        $result['data'] = $this->callsql("SELECT * FROM $this->tableName as a  $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        }
       
        
    

        $edit_permission   = in_array(93,$this->admin_services) || $this->admin_role == '1' ? true : false;
        
        foreach ($result['data'] as $key => $value) {

            $statusArray = ['0'=>'Active','1'=>'Inactive'];
               
            $checked = $value['status'] == '0' ? 'checked' : '';  
            $department_name    = $this->callsql("SELECT name FROM departments WHERE id='".$value['dept_id']."' ",'value');
            $result['data'][$key]['department']     = $department_name;   
            $result['data'][$key]['status']         = $edit_permission ? '<label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            
                                            <input type="checkbox" '.$checked.'>
                                          <span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].')"></span>
                                                </label>' : $statusArray[$value['status']];

            $result['data'][$key]['action']         = $edit_permission ? '<a href="'.BASEURL.'Positions/Edit?id='.base64_encode($value['id']).'"><button class="btn btn-primary">Edit</button></a>':'-';                             

    

        }
        if($count==0){
            $result['data'] = array();
        }

        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;



        return $result;
    }
    //End User Management


  


   

   
    public function updateStatus($params)
    {

        $status = $params['status'];
        $id     = $params['id'];

        $sql = "UPDATE $this->tableName SET status='$status' WHERE id='$id'";
        $this->query($sql);
        $this->execute();

        $activity = "Admin changed the position status to ".$status.'.Id-'.$id;
        return $this->adminActivityLog($activity);


    }

    public function getAllDepartments(){
        return $this->callsql("SELECT id, name FROM departments WHERE status='0' ",'rows');

    }

    public function checkAlreadyExists($params)
    {

        $id   = !empty($params['id']) ? $params['id'] : '';
        $name = !empty($params['name']) ? $params['name'] : '';
        $dept = !empty($params['dept']) ? $params['dept'] : '';
        $where = " WHERE name='".$name."' AND dept_id='".$dept."' ";
        if(!empty($id)) {
            $where .= " AND id!='$id'";

        }

        $sql = "SELECT id FROM $this->tableName $where";
        $result = $this->callsql($sql,'rows');
        if(!empty($result)) {

            return true;

        }

        return false;

        

    }

  

    public function addRedis($key,$time,$data){

        if($this->rds->exists($key)) {

            $this->rds->del($key);    
        }

        $this->rds->set($key,$data,$time);



    }
}