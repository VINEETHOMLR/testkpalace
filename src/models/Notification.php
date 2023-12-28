<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class Notification extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());
        $this->tableName = "notification";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }


    public function getNotificationListPopUp($data)
    {

        $where = ' WHERE read_at = 0 AND status = 0';

        $count  = $this->callsql("SELECT count(DISTINCT id) FROM $this->tableName $where",'value');
        
        if(!empty($data['export'])){
           
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        
        }else{
            
            $pagecount = ($data['page'] - 1) * $this->perPage;
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC");
        
        }

        $result = ['data' => $this->resultset()];

        foreach ($result['data'] as $key => $value) {

            $this->typeArray = [1 => 'Customer Profile Request', 2 => "Inventory Edit Request" , 3 => "Order Request", 4 => "Leave Request"];
            $this->url = [ 1 => 'Customer/CustomerProfileRequest/', 2 => "Inventory/Index" , 3 => "OrderRequest/Index", 4 => "LeaveRequest/Index/" ];
            
            $username = $this->callsql("SELECT username FROM user WHERE user_id = '$value[user_id]' ","value");
            $result['data'][$key]['username']   = !empty($username) ? $username : ''; 
            $result['data'][$key]['type']       = $this->typeArray[$value['type']];
            $result['data'][$key]['url']        = BASEURL.$this->url[$value['type']];
            $result['data'][$key]['data']       = $value['data'];
            $result['data'][$key]['count']      = $count;

            // $groupedData[$value['type']][] = $result['data'][$key];

        }

        if($count==0){
            $result = array();
        }
        return $result;


    }

    public function UpdateReadStatus($id)
    {
        $time = time();
        $this->query("UPDATE $this->tableName SET read_at = '$time' , status = 1 WHERE id='$id'");

        $this->execute();
        $this->adminActivityLog("Notification readed Notification Id - ".$id);
        return true;

    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
  
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();

        return true;
    }


    public function createNotification($params){

    
        $type               = $params['type'];
        $data               = $params['data'];
        $time               = time();
        $status             = $params['status'];
        $user_id            = !empty($params['user_id']) ? $params['user_id'] : '';
        $read_at            = 0;


        $data   = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        
        $sql    = "INSERT INTO $this->tableName SET type='$type',data='$data',`time`='$time',read_at='$read_at',status='$status',user_id='$user_id'";

        $this->query($sql);
        $this->execute();

        

    }





}