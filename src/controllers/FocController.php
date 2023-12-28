<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Foc;
use inc\Root;
use inc\commonArrays;
/**
 * To handle the users data models
 * @author 
 */

class FocController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Foc);
        $this->pag =  new Pagination(new Foc(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		    $arr                 = commonArrays::getArrays();
        $this->statusArry    = array(0=>'Active', 1 =>'Inactive');

        
            
    
    }
    public function actionIndex() {


        $this->checkPageAccess(55);
        $status   = $this->cleanMe(Router::post('status'));
        $page     = $this->cleanMe(Router::post('page')); 
        $page = (!empty($page)) ? $page : '1'; 
        
        

        $filter=["status"   => $status,
                  "page"     => $page];

        $data=$this->mdl->getFocList($filter);
         
        $onclick = "onclick=pageHistory('".$status."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('foc/index',['status'=>$status,'data' => $data, 'pagination'=> $pagination]);
    }
        
    public function actionCreate() {
        
        $this->checkPageAccess(56);
        $this->subTitle  = 'Create Promotion';

        if(isset($_GET['promotion_id'])){

           $promotion = $this->mdl->callsql("SELECT * FROM `promotions` WHERE id='$_GET[promotion_id]' ","row");
           
           $this->subTitle  = 'Edit Promotion';
        }


        return $this->render('promotion/create',['promotion'=>$promotion]);
    }

    public function actionGetFocEdit()
    {

        $id      = $this->cleanMe($_POST['id']);
        $details = $this->mdl->getDetails($id);

        echo json_encode($details);
        

    }

    public function actionDelete()
    {
        
        $this->checkPageAccess(58);
        $id      = $this->cleanMe($_POST['id']);
        if(empty($id)){
           return $this->sendMessage("error","Please select a foc remark to Proceed");
        }
        $delete = $this->mdl->deleteRemark($id);
        if($delete){
            return $this->sendMessage('success',"Successfully Deleted FOC");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 



    }



    
    public function actionAdd() {

        $this->checkPageAccess(56);
       
        $remark   = cleanMe($_POST['remark']);
        $id       = !empty($_POST['id']) ? cleanMe($_POST['id']) : '';
        
        if(empty($remark)){
           return $this->sendMessage("error","Enter Foc Remark to Proceed");
        }

        $params = [];
        $params['remark'] = $remark;
        $params['id'] = $id;
        if(!empty($id)){
             //$this->checkPageAccess(35);
           $success = $this->mdl->updateRemark($params);
           $msg     = 'Remark Updated Successfully';
        }else{

           $success = $this->mdl->addRemark($params);
           $msg     = 'Remark Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    } 

    public function actionChangeStatus()
    {

        $id     = $this->cleanMe(Router::post('id')); 
        $status = $this->cleanMe(Router::post('status'));
        $params = [];
        $params['status'] = $status;
        $params['id']     = $id;
        $update =$this->mdl->updateStatus($params);
        if($update) {

            $msg = "FOC Remark Updated Successfully";
            $this->sendMessage("success",$msg);
            die();

        }else{

            $msg = "FOC Remark updation Failed";
            $this->sendMessage("error",$msg);
            die();

        }

        $msg = "Something went wrong";
        $this->sendMessage("error",$msg);
        die();



    }


   

   
}

