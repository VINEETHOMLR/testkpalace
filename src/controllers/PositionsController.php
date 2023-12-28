<?php

namespace src\controllers;
use inc\Root;
use inc\Controller;
use inc\commonArrays;

use src\lib\Router;
use src\lib\Pagination;
use src\models\Positions;

class PositionsController extends Controller {

    public function __construct(){

        parent::__construct();
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Positions);
        $this->mainTitle    = 'Positions';
        $this->pag       =  new Pagination(new Positions(),'');


    }

   
    public function actionIndex()
    {

        $this->subTitle     = ' Positions List';
        $this->checkPageAccess(94);
        $status   = in_array(Router::post('status'),[0,1]) ? $this->cleanMe(Router::post('status')) : '';
        $page     = $this->cleanMe(Router::post('page')); 
        $page = (!empty($page)) ? $page : '1'; 
        
        

        $filter=["status"   => $status,
                  "page"     => $page];


       // if( ! empty($filter['user_id'])){

       //      $filter['s_username']    = $this->usermdl->getUsername($filter['user_id']);


       //  }
       $data  = $this->mdl->getPositionsList($filter);
       $onclick   = "onclick=pageHistory('".$filter['status']."','***')";
       $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
       $filter['data']       = $data;


       return $this->render('Positions/index',$filter); 

   }
   public function actionCreate()
   {
       $this->checkPageAccess(92);

       $this->subTitle  = 'Create Position';

       $dept_list       = $this->mdl->getAllDepartments();

       return $this->render('Positions/create',['dept_list'=>$dept_list]); 

   }

   public function actionEdit()
   {
       $this->checkPageAccess(93);
       
       $id = $this->cleanMe(Router::req('id')); 
       $id = base64_decode($id);
       $this->subTitle     = 'Edit Position';

       $dept_list   = $this->mdl->getAllDepartments();
       $details     = $this->mdl->getDetails($id);
       return $this->render('Positions/edit',['details'=>$details, 'dept_list'=>$dept_list]); 

   }

   public function actionAdd()
   {

      $id           = $this->cleanMe(Router::post('id'));
      $dept         = $this->cleanMe(Router::post('dept'));
      $name         = $this->cleanMe(Router::post('name'));
      $this->emptyCheck($dept,'Department');
      $this->emptyCheck($name,'Name');
      $params = [];
      $params['dept'] = $dept;
      $params['name'] = $name;
      $params['id']   = $id;
      $alreadyUsed = $this->mdl->checkAlreadyExists($params);
      if($alreadyUsed) {
          $msg = "Name already used with selected Department";
          return $this->sendMessage("error",$msg);
          die();

      }


      if(empty($id)) {

          if($this->mdl->createPosition($params)){

              $msg = "Successfully added position";
              return $this->sendMessage("success",$msg);
              die();  

          }else{

              $msg = "Failed to add position";
              return $this->sendMessage("error",$msg);
              die();   
          }

            

      }else{

          if($this->mdl->updatePosition($params)){

              $msg = "Successfully updated position";
              return $this->sendMessage("success",$msg);
              die();  

          }else{

              $msg = "Failed to update position";
              return $this->sendMessage("error",$msg);
              die();   
          }  

      }

      $msg = "Something went wrong";
      $this->sendMessage("error",$msg);
      die();



   }


    
    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Root::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
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

            $msg = "Position Updated Successfully";
            $this->sendMessage("success",$msg);
            die();

        }else{

            $msg = "Position updation Failed";
            $this->sendMessage("error",$msg);
            die();

        }

        $msg = "Something went wrong";
        $this->sendMessage("error",$msg);
        die();



    }


   
   


 
}

