<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\lib\RRedis;

class TermsConditions extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName = "terms_and_conditions";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }
    public function getList($data)
    {
        $where = ' WHERE id!=0';

        $count  = $this->callsql("SELECT count(id) FROM terms_and_conditions $where",'value');
        $this->query("SELECT * FROM terms_and_conditions $where  ORDER BY id DESC");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

                $lang_name  = $this->callsql("SELECT lang_name FROM language WHERE id = '".$value['language']."'",'value');

                $result['data'][$key]['lang'] = !empty($value['language']) ? $lang_name : '-';

                $result['data'][$key]['created_dt'] = empty($value['date']) ? '-' : date("d-m-Y", strtotime($value['date']));

                if(!empty($value['status'])){
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }else{
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }

                $result['data'][$key]['action'] = $status;
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

   
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }

    public function getAllLangiages(){
        return $this->callsql("SELECT id, lang_name FROM language WHERE status='1' ",'rows');

    }

    public function insertData($data){

        $time       =time(); 
        $ip         =$_SERVER['REMOTE_ADDR']; 
        $admin_id   =$this->adminID;
       
        $activity   = $data['file_name']." terms and conditions added";
        $this->adminActivityLog($activity);


        $this->query("INSERT INTO $this->tableName SET  file_name='".$data['file_name']."', language='".$data['lang_id']."' , added_by='".$admin_id ."', created_at = '".$time ."', updated_at = '".$time ."' , status = '1' ");
        $this->execute();

        return true;
    }


  



    public function updateTermsconditions($id, $status){

        $time=time();

        $this->query("UPDATE `terms_and_conditions` SET status='".$status."',updated_at='$time' WHERE id='".$id."' ");
        if($this->execute()){
            $this->adminActivityLog("Terms and conditions status changed - id" .$id);
            return true;
        }else
            return false;
   }

    public function checkExists($lang_id){
        return $this->callsql("SELECT id FROM terms_and_conditions WHERE language='".$lang_id."' AND status=1 ",'rows');
 
    }

    public function checkExistsActive($id){
        $data = $this->callsql("SELECT language, status FROM terms_and_conditions WHERE id='".$id."' ",'row');
        if($data['status'] == 0){
            return $this->callsql("SELECT id FROM terms_and_conditions WHERE language='".$data['language']."' AND status='1' ",'rows');
        }
        else{
            return '';
        }

    }


 
  
}
