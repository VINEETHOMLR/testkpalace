<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;
use src\lib\RRedis;

class Hour extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName    = "hour_type";
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

    public function getTime($id)
    {

        return $this->callsql("SELECT id,from_time,to_time,status FROM $this->tableName WHERE id='$id'",'row');  

    }
    public function createHour($data){


         

        $query     = "INSERT INTO hour_type (`name`,`from_time`,`to_time`,`status`,`created_at`) VALUES
                        (:name,:from_time,:to_time,:status,:created_at)";
                
        $this->query($query);
       
        $this->bind(':name',$data['name']);
        $this->bind(':from_time',$data['from_time']);
        $this->bind(':to_time',$data['to_time']);
        $this->bind(':status',0);
        $this->bind(':created_at',time());
        $this->execute();

        $userId = $this->lastInsertId();
        
        $redis_Key = "HourList";
        $this->rds->del($redis_Key);

        $this->adminActivityLog("Created Hour -".$data['name']." id- ".$userId);

        return true;
    }

    public function getHourList($data){

       $where = ' WHERE a.id!=0 ';
       if($data['name']!=""){
            $where.= " AND a.name = '$data[name]'";
        }
        if($data['from_time']!=""){
            $where.= " AND a.from_time = '$data[from_time]' ";
        }
        
        if($data['status']!=""){
            $where.= " AND a.status = '$data[status]' ";
        }
        if($data['to_time']!=""){
          $where .= " AND a.to_time = '$data[to_time]'";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;



        $count = $this->callsql("SELECT COUNT(id) FROM $this->tableName as a $where ","value");

       

       if(!empty($data['export'])){

         $result['data'] = $this->callsql("SELECT * FROM $this->tableName ","rows");
        }else{
        $result['data'] = $this->callsql("SELECT * FROM $this->tableName as a  $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        }
       
        
    


         foreach ($result['data'] as $key => $value) {
               
                 
          if($value['status']=='0')
              $acctStatus="Active";
          else
              $accStatus="Blocked";

    

        }
        if($count==0){
            $result['data'] = array();
        }

        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;



        return $result;
    }



     public function updateHoursStatus($status,$value)
    {
        $this->query("UPDATE $this->tableName SET status='$status' WHERE id='$value'");
        $this->execute();
        
        $redis_Key = "HourList";
        $this->rds->del($redis_Key);

        // $getdetailsofhours = $this->getdetailsofhours($value);
        // $this->adminActivityLog("Updated Hour Status -".$getdetailsofhours['name']);

        return true;
    }
    public function getdetailsofhours($id)
    {
       $hours_details = $this->callsql("SELECT * FROM $this->tableName WHERE `id` =$id ","row");

       return $hours_details;
       
    }
    public function Updatehours($params)
    {
    

        $this->query("UPDATE hour_type SET name='".$params['name']."',from_time='".$params['from_time']."',to_time='".$params['to_time']."' WHERE id='".$params['id']."'");
        $this->execute();
        
        $redis_Key = "HourList";
        $this->rds->del($redis_Key);

        return true;
    
    }
    public function checknameexist($name){

        $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `name` ='$name'","value");


         return $result; 

    }
    public function checktimeframeexist($from_time,$to_time){

        $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `from_time` ='$from_time' AND `to_time` ='$to_time' AND `status`= 0 ","value");


         return $result; 
    }

    public function checktimeframeexistforblock($from_time,$to_time){

        $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `from_time` ='$from_time' AND `to_time` ='$to_time' AND `status`= 0 ","value");


         return $result; 
    }
    public function checknameavailability($name,$id)
    {
          $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `name` ='$name' AND `id`!= $id","value");


         return $result; 
    }
    public function checktimeframeupdation($from_time,$to_time,$id)
    {
       $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `from_time` ='$from_time' AND `to_time` ='$to_time' AND `status`= 0 AND `id`!=$id","value");


         return $result;  
    }

    public function addRedis($key,$time,$data){

        if($this->rds->exists($key)) {

            $this->rds->del($key);    
        }

        $this->rds->set($key,$data,$time);



    }
    
}