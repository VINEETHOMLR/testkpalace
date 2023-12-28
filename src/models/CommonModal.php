<?php
namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class CommonModal extends Database {

    public function __construct() {

        parent::__construct(Root::db());
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->tableName        = "user_wallet";
    }

    public function getUsername($id){

        return $this->callsql("SELECT username FROM user where id = '$id'",'value'); 
    }

    public function getLeaderUsers($userId){
        
        $leader_user = $this->callsql("SELECT id FROM user WHERE leader_id='$userId'","rows"); 
        $leader_user = array_column($leader_user, 'id');
        $leader_user = empty($leader_user) ? 0 : implode(",", $leader_user);
        
        return $leader_user;
    }

    public function getSubLeaderUsers($userId){
        
        $leader_user = $this->callsql("SELECT id FROM user WHERE subleader_id='$userId'","rows"); 
        $leader_user = array_column($leader_user, 'id');
        $leader_user = empty($leader_user) ? 0 : implode(",", $leader_user);
        
        return $leader_user;
    }


    public function getChild($userId){

        $downlineArr    = array(); 
        $downlineArr[0] = "";
     
        $aUser = $this->callsql("SELECT id,user_status FROM user WHERE sponsor='$userId'","rows"); 
    
        foreach ($aUser as $key => $value) { 

            if($value['user_status'] == 1){

               $downlineArr[0].=$value['id'].",";
            }

            $downlineArr[0].=$this->getChild($value['id']);
        }

        return $downlineArr[0];
    }

    public function getLeaderDownline($userId){

        $downlineArr     = array(); 
        $downlineArr[0]  = "";
     
        $aUser           = $this->callsql("SELECT id FROM user WHERE leader_id='$userId' AND id!='$userId'","rows"); 
        if(!empty($aUser)){
            foreach ($aUser as $key => $value) { 

                $downlineArr[0]  .= $value['id'].",";
                $downlineArr[0]  .= $this->getLeaderDownline($value['id']);
            }
        }else{
            return "";
        }

        return $downlineArr[0];
    }



    public function getSubLeaderDownline($userId){

        $downlineArr     = array(); 
        $downlineArr[0]  = "";
     
        $aUser           = $this->callsql("SELECT id FROM user WHERE subleader_id='$userId' AND id!='$userId'","rows"); 
        if(!empty($aUser)){
            foreach ($aUser as $key => $value) { 

                $downlineArr[0]  .= $value['id'].",";        
                $downlineArr[0]  .= $this->getSubLeaderDownline($value['id']);
            }
        }else{
            return "";
        }

        return $downlineArr[0];
    }

    public function getLeaderSearch($check){
       
        $leaders = $this->callsql("SELECT id FROM user WHERE leader_status=1",'rows'); 
        $leaders = array_column($leaders,'id');
        $List    = '';

        foreach($leaders as $rem){
                
            $List.= !empty($check) ? $this->getLeaderDownline($rem).$rem : $rem;

            if(count($leaders)>1){
                $List .= ",";
            }
        }

        if(count($leaders)==0){
            $List .= 0;
        }

        $List = rtrim($List, ',');
        
        return $List;
    }

    public function getSubLeaderSearch($check){
       
        $leaders = $this->callsql("SELECT id FROM user WHERE subleader_status=1",'rows'); 
        $leaders = array_column($leaders,'id');
        $List    = '';

        foreach($leaders as $rem){
                
            $List.= !empty($check) ? $this->getSubLeaderDownline($rem).$rem : $rem;

            if(count($leaders)>1){
                $List .= ",";
            }
        }

        if(count($leaders)==0){
            $List .= 0;
        }

        $List = rtrim($List, ',');
        
        return $List;
    }


    public function adminActivityLognew($activity,$data,$user_id){

        $query = "INSERT INTO `admin_activity_log` (`user_id`,`admin_id`,`action`,`data`,`createtime`,`createip`) VALUES (:user_id,:admin_id,:action,:data,:ctime,:createip)";

        $this->query($query);
        $this->bind(':user_id', $user_id);
        $this->bind(':admin_id', $this->adminID);
        $this->bind(':action', $activity);
        $this->bind(':data', $data);
        $this->bind(':ctime', time());
        $this->bind(':createip', $this->IP);
       
        $this->execute();

        return true;
    }
}
