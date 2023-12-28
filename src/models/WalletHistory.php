<?php

/**
 * @author 
 * @desc <Core>Auto Generated model
 */

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use inc\commonArrays;


class WalletHistory extends Database {

    /**
     * Constructor of the model
     */
    public function __construct($db = 'db') {

        parent::__construct(Root::db());

        global $transactionArray, $creditArray;
        
        $this->tableName = "customer_wallet";
        $this->adminID     = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->systemArrays = commonArrays::getArrays();
        $this->creditType = $this->systemArrays['creditArray'];
        $this->transactionArray = $this->systemArrays['transactionArray'];

        $this->perPage = 10;
    }

 

    public function getSerchData($filter){


        $tableName  = $filter['tableName'];

        $curPage  = !empty($filter['page']) ? $filter['page'] : 1;

       
        $search = "WHERE id!=0";

        if(!empty($filter['user_id'])){
             $search .= " AND  user_id = '$filter[user_id]' ";
        }

       
       
        if($filter['creditType']!==""){
            $search .= " AND credit_type = '$filter[creditType]' ";
        }
        if($filter['txn_type']!=""){

            $search .= " AND  transaction_type = '$filter[txn_type]' ";
        }

       
        
        if(!empty($filter['datefrom']) && !empty($filter['dateto'])){
             $date_from = date("Y-m-d", strtotime($filter['datefrom']));
             $date_to   = date("Y-m-d", strtotime($filter['dateto']));
             $search .= " AND  transactiondate BETWEEN '$date_from' AND '$date_to' ";
        }

        

        if(empty($filter['user_id']) && empty($filter['datefrom']) && empty($filter['dateto']) && empty($filter['txn_type']) && ($filter['creditType']=="") )
        {  
           
              $search = "WHERE transactiondate = CURDATE()";
           
        }


        
        $pagecount = ($curPage - 1) * $this->perPage;

        $retun['where'] = $search;
        $retun['pagecount'] = $pagecount;
        $retun['page'] = $curPage;
        return $retun;
    }

    public function getHistory($filter){ 
      
        $SearchData = $this->getSerchData($filter);
        $search     = $SearchData['where'];
        $pagecount  = $SearchData['pagecount'];
       
        $tableName  = $filter['tableName'];


      $count = $this->callsql("SELECT count(id) FROM `$tableName` $search",'value');
      $data['total'] = $this->callsql("SELECT sum(value) FROM `$tableName` $search",'value');

        if(empty($filter['export'])){


            $search .= " ORDER BY id DESC LIMIT $pagecount,$this->perPage ";

            $data['data']    = $this->callsql("SELECT * FROM `$tableName` $search ","rows");


            $data['count']   = $count;
            $data['perPage'] = $this->perPage;
            $data['curPage'] = $SearchData['page'];

        }else{
            $data['data']=$this->callsql("SELECT * FROM `$tableName` $search ORDER BY `$tableName`.id DESC",'rows');
        }


        
        foreach ($data['data'] as $key => $value) {

            $data['data'][$key]['uniqueid'] = $this->callsql("SELECT uniqueid FROM customer WHERE id='$value[user_id]'","value");
            $data['data'][$key]['username'] = $this->callsql("SELECT username FROM customer WHERE id='$value[user_id]'","value");;  
            $data['data'][$key]['txn_date'] = date('d-m-Y',strtotime($value['transactiondate']));  
            $data['data'][$key]['txn_type'] = $this->transactionArray[$value['transaction_type']];
            $data['data'][$key]['credit_type'] = $this->creditType[$value['credit_type']];   
        }

        if($count==0){
            $data['data'] = array();
        }



      
        return $data;
    }



    function getusername($user_id){

       return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");
   }

    

    public function adminActivityLog($activity,$data){
        $time=time();
        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' ,action ='$activity',data ='$data', createtime= '$time',createip='$this->IP'");
        $this->execute();

        return true;
    }

   
    
}
 
