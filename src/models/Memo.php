<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Memo extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());
        $this->tableName = "memo";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;

        $this->cmdl            = new CommonModal();
    }
   
    
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }

    

    
   public function getLanguageArray(){

      return  $this->callsql("SELECT * FROM `language` WHERE `status`=1","rows");
   }

   

   

   public function getCountry($key){

        return $this->callsql("SELECT `numeric_code` as id,name AS text  FROM `countries` WHERE name like '$key%' ",'rows');
    }
    
    public function getUSerData($users){

        $data   = json_decode($users,true);

        $return = '';

        foreach ($data as $value) {

           $email = $this->callsql("SELECT email FROM `customer` WHERE id='$value'","value");
           
           $return .= '<option value="'.$value.'" selected>'.$email.'</option>';
        }

        return $return;
    }

    public function getCountryData($country){

        $data   = json_decode($country,true);

        $return = '';

        foreach ($data as $value) {

           $country = $this->callsql("SELECT name FROM `countries` WHERE numeric_code='$value'","value");
           
           $return .= '<option value="'.$value.'" selected>'.$country.'</option>';
        }

        return $return;
    }


   public function getMemo($data){

       $where = ' WHERE id!=0 ';

        if($data['status']!="")
            $where .= " AND status = '$data[status]' ";
        else
            $where .= " AND status != '2' ";

        if($data['slug_name']!="")
            $where .= " AND slug LIKE '%$data[slug_name]%' ";

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $datefrom = strtotime($data['datefrom']." 00:00:00");
            $dateto   = strtotime($data['dateto']." 23:59:59");
            $where   .= " AND from_date > '$datefrom' AND to_date < '$dateto' ";
        }

        $pagecount  = ($data['page'] - 1) * $this->perPage;

        $count      = $this->callsql("SELECT count(DISTINCT log_id) FROM memo $where",'value');

        $userStatus = array(0=>"Published", 1=>"Hidden", 2=>"Cancelled");

        $this->query("SELECT * FROM memo $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result     = ['data' => $this->resultset()];

        foreach ($result['data'] as $key => $value) {

            $result['data'][$key]['action'] = '<a href="'.BASEURL.'Memo/Update/?id='.$value['log_id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['log_id'].')">Delete</button>';

            if($value['status']==1){
                $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['log_id'].','.$value['status'].');"></span></label>';
            }else{
                $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['log_id'].','.$value['status'].');"></span></label>';
            }

            $result['data'][$key]['status'] = $status;
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
   }

    public function addMemo($ip){

        $time     = time(); 

        $prev_log = $this->callsql("SELECT MAX(log_id) FROM `memo` ","value");
        $next_log = $prev_log+1;
        $log      = empty($prev_log) ? 1 : $next_log;

      
        foreach ($ip['data'] as $key => $value) {
            $this->query("INSERT INTO `memo` SET log_id='$log',slug='$ip[slug]',filename='$value[file]', lang_code='$value[language]',from_date='$ip[fromdate]', to_date='$ip[todate]',createtime='$time',status=$ip[status],createid='$this->adminID',include_user='$ip[include_userr]',exclude_user='$ip[exclude_userr]',include_country='$ip[include_country]',exclude_country='$ip[exclude_country]',position='$ip[position]', include_downline='$ip[include_donwline]'");
            
            $this->execute();
        }

        $this->adminActivityLog($ip['slug']." memo added");

        return true;
    }

    public function updateMemo($ip){

        $time         = time();

        $db_entries   = $this->callsql("SELECT lang_code FROM `memo` WHERE log_id='$ip[edit]'","rows");
        $db_entries   = array_column($db_entries, 'lang_code');

        $updated_lang = array_column($ip['data'], 'language');

        foreach ($db_entries as $lg) {
          
            if(!in_array($lg, $updated_lang)){

                $this->query("DELETE FROM `memo` WHERE lang_code='$lg' AND log_id='$ip[edit]'");
                $this->execute();
            }
        }

       

        foreach ($ip['data'] as $key => $value) {

            $check_entry  = $this->callsql("SELECT id FROM `memo` WHERE lang_code='$value[language]' AND log_id='$ip[edit]'","value");

            if(empty($check_entry)){

                  $this->query("INSERT INTO `memo` SET log_id='$ip[edit]',slug='$ip[slug]',filename='$value[file]', lang_code='$value[language]',from_date='$ip[fromdate]', to_date='$ip[todate]',createtime='$time',status=$ip[status],createid='$this->adminID',include_user='$ip[include_userr]',exclude_user='$ip[exclude_userr]',include_country='$ip[include_country]',exclude_country='$ip[exclude_country]',position='$ip[position]', include_downline='$ip[include_donwline]'");
            
                  $this->execute();
            }else{

                  $memoData      = $this->callsql("SELECT filename FROM `memo` WHERE lang_code='$value[language]' AND log_id='$ip[edit]' ","row");

                  $filename      = empty($value['file']) ? $memoData['filename'] : $value['file'];
                 
                  $this->query("UPDATE memo SET slug='$ip[slug]',filename='$filename',from_date='$ip[fromdate]', to_date='$ip[todate]',status=$ip[status],include_user='$ip[include_userr]',exclude_user='$ip[exclude_userr]',include_country='$ip[include_country]',exclude_country='$ip[exclude_country]',position='$ip[position]', include_downline='$ip[include_donwline]' WHERE lang_code='$value[language]' AND `log_id`='$ip[edit]'");
                  $this->execute();
            }
        }
       
        $this->adminActivityLog("Updated Memo");

        return true;
    }

    public function UpdateMemoStatus($id,$status){

      $this->callsql("UPDATE `memo` SET status='$status' WHERE log_id='$id'");
      $this->adminActivityLog("Memo Status updated");
      return true;
    }

    public function deleteMemo($ID){
      
      $this->query("DELETE FROM `memo` WHERE log_id='$ID'");
      
      $this->execute();

      return true;
   }


   

    public function getUserByIdorName($key)
    {
     // echo "SELECT `id`, `email` AS text  FROM `customer` WHERE email like CONCAT(:key, '%') ";

        $this->query("SELECT `id`, `email` AS text  FROM `customer` WHERE email like CONCAT(:key, '%') ");
        $this->bind(':key',$key);
        return $this->resultset();
    }

    public function getUserByNameId($key){

        return $this->callsql("SELECT `id`,CASE
                            WHEN `email` like '$key%' THEN `email`
                            WHEN `uniqueid`like '$key%' THEN `uniqueid`
                            ELSE ''
                            END AS text  FROM `customer` WHERE email like '$key%' OR `uniqueid` like '$key%'",'rows'); 
    }
    


}
