<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\lib\RRedis;
use src\models\CommonModal;

class Room extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName    = "room";
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

    public function createRoom($data){


         

        $query     = "INSERT INTO room (`room_no`,`type`,`level`,`description`,`max_allowed`,`price`,`status`) VALUES
                        (:room_no,:type,:level,:description,:max_allowed,:price,:status)";
                
        $this->query($query);
       
        $this->bind(':room_no',$data['room_no']);
        $this->bind(':type',$data['type']);
         $this->bind(':level',$data['level']);
        $this->bind(':description',$data['description']);
        $this->bind(':max_allowed',$data['maximum_allowed']);
        $this->bind(':price',$data['price']);

        $this->bind(':status',0);

        $this->execute();

        $userId = $this->lastInsertId();
        $redis_Key = "RoomList";
        $this->rds->del($redis_Key);
        return true;
    }

     public function  createTable($data){


         

        $query     = "INSERT INTO room (`room_no`,`type`,`description`,`status`) VALUES
                        (:table_no,:type,:description,:status)";
                
        $this->query($query);
       
        $this->bind(':table_no',$data['table_no']);
        
        $this->bind(':description',$data['description']);
        $this->bind(':type',$data['type']);

        $this->bind(':status',0);

        $this->execute();

        $userId = $this->lastInsertId();
        
        $redis_Key = "TableList";
        $this->rds->del($redis_Key);

        return true;
    }

   

    
    public function getRoomList($data){

       $where = ' WHERE a.id!=0 ';
       if($data['maximum_allowed']!=""){
            $where.= " AND a.max_allowed = '$data[maximum_allowed]' AND a.max_allowed != '0'";
        }
        if($data['type']!=""){
            $where.= " AND a.type = '$data[type]' ";
        }
        // if($data['price']!=""){
        //   $where .= " AND a.price = '$data[price]'";
        // }
        if($data['status']!=""){
            $where.= " AND a.status = '$data[status]' ";
        }
        if($data['price']!=""){
          $where .= " AND a.price = '$data[price]'AND a.price != '0'";
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
    //End User Management

public function getserivegroupid($admin_id)
{
 

   $result = $this->callsql("SELECT service_group_id FROM `admin` WHERE `id` =$admin_id ","row");

   $service_gp_id =json_decode($result['service_group_id'],true);
  
}
public function checkroomavailability($room_no,$level,$type)
{
     
     
    $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `room_no` ='$room_no' AND `level`=$level AND `type`='1'","value");


             return $result;     
  }
  public function checkroomexist($room_no,$level,$type,$id)
  {
     $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `room_no` ='$room_no' AND `level`=$level AND `type`='$type'AND id!='$id'","value");


             return $result;
  }

  public function checktableavailability($table_no)
  {
    $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `room_no` ='$table_no' AND `type`='2'","value");


             return $result;   
  }
public function checktableExist($table_no,$type,$id)
{
    $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `room_no` ='$table_no' AND `type`='$type' AND `id`!='$id'","value");


             return $result; 
}


    public function getdetailsofroom($id)
    {


       
       $room_details = $this->callsql("SELECT * FROM $this->tableName WHERE `id` =$id ","row");

       return $room_details;
       
    }

    public function getDetails($id)
    {


       
       $room_details = $this->callsql("SELECT * FROM $this->tableName WHERE `id` =$id ","row");

       return $room_details;
       
    }

    public function Updateroom($params)
    {


            $this->query("UPDATE room SET room_no='".$params['room_no']."',type='".$params['type']."',level='".$params['level']."',max_allowed='".$params['maximum_allowed']."',price='".$params['price']."' WHERE id='".$params['id']."'");
            $this->execute();
            
            $redis_Key = "RoomList";
            $this->rds->del($redis_Key);

            return true;
    
    }

    public function UpdateTable($params)
    {


            $this->query("UPDATE room SET room_no='".$params['table_no']."',type='".$params['type_id']."' WHERE id='".$params['id']."'");
            $this->execute();
            
            $redis_Key = "TableList";
            $this->rds->del($redis_Key);

            return true;
    
    }

    public function gettype($id){

        $result = $this->callsql("SELECT type FROM $this->tableName WHERE  `id`='$id' ","value");


             return $result; 

    }

    public function roomavailabilityforupdation($room_no,$level,$id)
    {
        $result = $this->callsql("SELECT count(id) FROM $this->tableName WHERE `room_no` ='$room_no' AND `level`=$level AND `id`!=$id ","value");


             return $result;     
    }


    public function getDatByType($type='')
    {

        $where = " WHERE id!='0' AND status='0'";
        if(!empty($type)) {

            $where .= " AND type='$type'";   

        }
        return $this->callsql("SELECT id,room_no,type FROM $this->tableName $where ORDER BY id DESC",'rows');  

      }
    public function updateroomStatus($status,$value)
    {
      
        $this->query("UPDATE $this->tableName SET status='$status' WHERE id='$value'");
        $this->execute();
        
        $redis_Key = "TableList";
        $this->rds->del($redis_Key);

        $redis_Key = "RoomList";
        $this->rds->del($redis_Key);
        
        return true;

    }

    public function getRooms()
    {
        return $this->callsql("SELECT id,room_no FROM $this->tableName WHERE type='1' AND status='0'",'rows');  
    }


    public function addRedis($key,$time,$data){

        if($this->rds->exists($key)) {

            $this->rds->del($key);    
        }

        $this->rds->set($key,$data,$time);



    }
}