<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\lib\RRedis;

class Package extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName = "packages";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }
    public function getPackage($data)
    {
        $where = ' WHERE id!=0 ';
 

        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND createtime BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

         if($data['status']!=""){
            $where .= " AND status = $data[status] ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM packages $where",'value');
        $this->query("SELECT * FROM packages $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

               
                $result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Package/Create/?Package_id='.$value['id'].'"><button class="btn btn-info">Edit</button></a> ';


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

   public function addPackage($data){

        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $admin_id=$this->adminID;
    
        $this->query("INSERT INTO $this->tableName SET  amount='$data[amount]',descriptions='$data[descriptions]',percentage='$data[percentage]', createip='$ip',createtime='$time',status='$data[status]',createid='$admin_id'");
           if($this->execute()){
              $link_id  = $this->lastInsertId(); 
              $activity  = $data['amount']." Package added id - " .$link_id;
              $this->adminActivityLog($activity);
              $redis_Key = "PackageList";
              $this->rds->del($redis_Key);
             
           }
           return true;
     }


    public function update_Package($data)
    {   


        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $admin_id=$this->adminID;

        $this->query("UPDATE $this->tableName SET amount='$data[amount]',percentage='$data[percentage]',descriptions='$data[descriptions]',status='$data[status]',updateid  ='$admin_id',updatetime ='$time',updateip='$this->IP'  WHERE `id`='$data[editID]'");
        $this->execute();
        $activity  = $data['amount']." Package Updated id - ".$data['editID'] ;
        $this->adminActivityLog($activity);

        $redis_Key = "PackageList";
        $this->rds->del($redis_Key);
        
       return true;
    }


    public function addRedis($key,$time,$data){

        if($this->rds->exists($key)) {

            $this->rds->del($key);    
        }

        $this->rds->set($key,$data,$time);



    }



 

 
  
}
