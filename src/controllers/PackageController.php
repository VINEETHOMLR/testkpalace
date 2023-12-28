<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Package;
use inc\Root;
use inc\commonArrays;
/**
 * To handle the users data models
 * @author 
 */

class PackageController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Package);
        $this->pag =  new Pagination(new Package(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		     $arr  = commonArrays::getArrays();

        $this->statusArry = array(0=>'Hidden', 1 =>'Published', 2 =>'Sold');
            
    
    }
    public function actionIndex() {

      $this->checkPageAccess(22);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
       
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         
         $date_from = empty($datefrom) ? '' : strtotime($datefrom." 00:00:00");
         $date_to = empty($dateto) ? '' : strtotime($dateto." 23:59:59");

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "page"     => $page];

         $data=$this->mdl->getPackage($filter);
         
         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        
        return $this->render('Package/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'data' => $data, 'pagination'=> $pagination]);
    }
        
public function actionCreate() {

        $this->checkPageAccess(37);

        $this->subTitle  = 'Create Package';

        if(isset($_GET['Package_id'])){

           $Package = $this->mdl->callsql("SELECT * FROM `packages` WHERE id='$_GET[Package_id]' ","row");
           
           $this->subTitle  = 'Edit Package';
        }


        return $this->render('Package/create',['Package'=>$Package]);
    }
    
   public function actionAdd() {
        
        $this->checkPageAccess(37);
       
        $amount       = cleanMe(Router::post('amount'));
        $percentage   = cleanMe(Router::post('percentage'));
        $editID       = cleanMe(Router::post('editID'));
        $status       = cleanMe(Router::post('status')); 
        $descriptions = cleanMe(Router::post('descriptions')); 

        if(empty($amount)){
           return $this->sendMessage("error","Enter Amount to Proceed");
        }
        if(!is_numeric($amount))
        {
            return $this->sendMessage("error","Enter Valid Amount to Proceed");
        }
         if(empty($percentage)){
           return $this->sendMessage("error","Enter Percentage to Proceed");
        }

        $this->isNumeric($amount,'Amount');
        
        $filter = ['amount'=>$amount,'percentage'=>$percentage,'editID'=>$editID,'status'=>$status,'descriptions'=>$descriptions];
        if(!empty($editID)){
            $this->checkPageAccess(38);
           $success = $this->mdl->update_Package($filter);
           $msg     = 'Details Updated Successfully';
        }else{

           $success = $this->mdl->addPackage($filter);
           $msg     = 'Package Added';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    } 


    public function isNumeric($var,$key){
        $numVal = is_numeric($var);
        if($numVal != 1){
            echo $this->sendMessage("error","Please Enter Valid $key To Proceed"); exit;
        }

        if($var < 0){
           echo $this->sendMessage("error","Please Enter Valid $key To Proceed"); exit;
        }
    }
   
}

