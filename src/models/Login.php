<?php

namespace src\models;

use src\lib\Database;
use inc\Root;

class Login extends Database {

    public $conn;

    public function __construct() {
        parent::__construct(Root::db());
        $this->conn      = $this->getConnection();
        $this->tableName = 'admin';
    }

    public function login($user,$pass){
          
        $pass  = md5($pass); 
        
        $this->query("SELECT * FROM $this->tableName WHERE username=:user && password=:pass AND status='0'");
        $this->bind(':user',$user);
        $this->bind(':pass',$pass);
        $login = $this->single();

        if(!empty($login)){
      
           $_SESSION[SITENAME.'_admin']           = $login['id'];
           $_SESSION[SITENAME.'_admin_user']      = $login['username'];
           $_SESSION[SITENAME.'_admin_role']      = $login['role'];
           $_SESSION[SITENAME.'_admin_status']    = $login['status'];
           $_SESSION['ALCOHOL_BO']                = "Admin";

           $serv_group = $login['service_group_id'];
           $serv_arr   = json_decode($serv_group); 
           $privilage  = array();

            if(!empty($serv_arr)){
              foreach ($serv_arr as $value) { 
                 $inc_services = $this->callsql("SELECT service_id FROM service_group WHERE id = '$value'","value"); 
                 if(! empty($inc_services)){
                   $new_privilege  = explode(',', $inc_services);
                   $privilage      = array_merge($privilage,$new_privilege);
                 }
              }
            } 
           
           $_SESSION[SITENAME.'_admin_privilages'] = array_unique($privilage);

           $r = session_id(); $time = time(); $ip = $_SERVER['REMOTE_ADDR'] ; 

           $this->callsql("UPDATE $this->tableName SET current_login_time ='$time',createip='$ip' WHERE id='$login[id]'");

           $prevEntry = $this->callsql("SELECT * FROM admin_login_log WHERE admin_id='$login[id]' ORDER BY id DESC LIMIT 1","row");

           if($prevEntry['login_status'] == 0){

              $lastSeen = $this->callsql("SELECT last_login_time   FROM $this->tableName WHERE id='$login[id]'","value");

              $this->callsql("UPDATE admin_login_log SET login_status='1',logout_time='$lastSeen' WHERE id='$prevEntry[id]'");
           }

           $stmt   = "INSERT INTO admin_login_log SET admin_id ='$login[id]' , session_id ='$r' ,login_time= '$time' , login_ip='$ip', login_status='0' ,last_active_time='$time' ";

           $this->query($stmt);
           $this->execute();

            return true;
        }else{
            return false;
        }
    }

    public function logout(){

        $id = $_SESSION[SITENAME.'_admin']; $time = time(); $ip = $_SERVER['REMOTE_ADDR'] ; 

        $this->callsql("UPDATE $this->tableName SET last_login_time ='$time',last_login_ip='$ip' WHERE id='$id'");

        $stmt   = "UPDATE admin_login_log SET logout_type ='0' ,logout_time= '$time' , logout_ip='$ip', login_status='1' ,last_active_time='$time' WHERE admin_id='$id' order by id DESC limit 1 ";

        $this->query($stmt);
        $this->execute();

        return true;
    }



}
