<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Leave;
use inc\Root;
use inc\commonArrays;
use src\models\User;
/**
 * To handle the users data models
 * @author 
 */

class LeaveController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl          = (new Leave);
        $this->usermdl      = (new User);
        $this->pag          =  new Pagination(new Leave(),''); 
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
        
        $arr                = commonArrays::getArrays();
        
            
    
    }

    public function actionIndex() {


        $this->checkPageAccess(78);
        $name   = $this->cleanMe(Router::post('name'));
        $page   = $this->cleanMe(Router::post('page'));
        $page     = (!empty($page)) ? $page : '1'; 
        
        

        $filter=["leave_name"     => $name,
                  "page"      => $page];        

        $data = $this->mdl->getList($filter);
         
        $onclick    = "onclick=pageHistory('".$name."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('leave/index',['name'=>$name,'data' => $data, 'pagination'=> $pagination]);

    }


    public function actionAddLeaveType() {
              
        $leave_name     = $this->cleanMe(Router::post('name'));
        $allowed_count  = $this->cleanMe(Router::post('allowed_count'));
        $edit           = $this->cleanMe(Router::post('editID')); 
        $ip             = [];
        
        $ip         = ['leave_name'=>$leave_name,'allowed_count'=>$allowed_count,'edit'=>$edit];
        if(empty($leave_name)) {
            return $this->sendMessage('error',"Please Enter Name To Proceed");
        }
        if(empty($allowed_count)) {
            return $this->sendMessage('error',"Please Enter Maximum Leave (In Days) To Proceed");
        }
        if(!empty($allowed_count) && !is_numeric($allowed_count)) {
            return $this->sendMessage('error',"Please Enter Valid Maximum Leave (In Days) To Proceed");
        }
        $count = $this->mdl->checkExist($leave_name,$edit);
        if(!empty($count)){
            return $this->sendMessage('error',"This Leave Type Already Exist");
        }
        if(!empty($edit)){
           $success = $this->mdl->updateLeaveType($ip);
           $msg     = 'Leave Details Updated Successfully';
        }else{

           $success = $this->mdl->addLeaveType($ip);
           $msg     = 'Leave Type Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }


    public function actionUpdateLeaveType() {
        
        
        $data['name']             = '';
        $data['id']               = '';

        if(isset($_GET['id'])){
        $id = $this->cleanMe(Router::get('id'));
        $this->checkPageAccess(80);
            $details = $this->mdl->getLeaveTypeById($id);
            $data['leave_name']    = $details['leave_name'];
            $data['allowed_count']    = $details['allowed_count'];
            $data['id']      = $id;
           
            return $this->render('leave/edit_leave_type',$data);

        }else{
            $this->checkPageAccess(79);
            return $this->render('leave/create_leave_type',$data);
        }
    }


    public function actionDeleteLeaveType(){
         $this->checkPageAccess(81);

        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteLeavetype($ID);

        if($delete){
            return $this->sendMessage('success',"Leave Type Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }


     public function actionChangeLeaveTypeStatus() { 
         $this->checkPageAccess(78);
          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateAlcoholCategoryStatus($id,$status);

          $this->sendMessage('success','Status Updated Successfully');
           
          return false;
    }
        
    





   

   
}

