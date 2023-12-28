<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\lib\RRedis;

class Promotion extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());

        $this->rds          = new RRedis();
        $this->tableName = "promotions";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }
    public function getPromotion($data)
    {
        $where = ' WHERE id!=0 AND status!=2';


        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            $where .= " AND date BETWEEN '$data[datefrom]' AND '$data[dateto]' ";
        }

         if($data['status']!=""){
            $where .= " AND status = $data[status] ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM promotions $where",'value');
        $this->query("SELECT * FROM promotions $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

                $result['data'][$key]['date'] = empty($value['date']) ? '-' : date("d-m-Y", strtotime($value['date']));
                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Promotion/Create/?promotion_id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';


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

   public function addPromotion($data,$promotion){

        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $admin_id=$this->adminID;
       
        $activity  = $data['title']." promotion added";
        $this->adminActivityLog($activity);

        $data['type'] = 1;

        $datenew  = empty($data['date']) ? '' : date("Y-m-d", strtotime($data['date']));
        $this->query("INSERT INTO $this->tableName SET  title='$data[title]',type='$data[type]',description='".htmlspecialchars_decode($data['message'])."',image='$promotion', date='$datenew',createtime='$time',status='$data[status]',createid='$admin_id'");
        if($this->execute()){
            $link_id  = $this->lastInsertId(); 
            $title = $data['title'];
        }
           
        $redis_Key = "PromotionList";
        $this->rds->del($redis_Key);

        return true;
     }


      public function update_promotion($data,$promotion)
    {   


        $activity  = $data['title']." promotion updated";

        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $admin_id=$this->adminID;
        $this->adminActivityLog($activity);

         $data['type'] = 1;

        $datenew  = empty($data['date']) ? '' : date("Y-m-d", strtotime($data['date']));
       $this->query("UPDATE $this->tableName SET title='$data[title]',type='$data[type]',description='".htmlspecialchars_decode($data['message'])."',status='$data[status]',date='$datenew',updateid  ='$admin_id',image='$promotion',updatetime ='$time',updateip='$this->IP'  WHERE `id`='$data[editID]'");
        $this->execute();

        $redis_Key = "PromotionList";
        $this->rds->del($redis_Key);

       return true;
    }



   public function deletePromotion($ID){

      $time=time();
      $this->query("UPDATE `promotions` SET status='2',updatetime='$time',updateid='$this->adminID',updateip='$this->IP' WHERE id='$ID'");
      if($this->execute()){
         
         $redis_Key = "PromotionList";
         $this->rds->del($redis_Key);

         $this->adminActivityLog("Promotion Deleted - id" .$ID);
         return true;
      }else
         return false;
   }


 
  
}
