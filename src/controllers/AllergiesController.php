<?php

namespace src\controllers;
use inc\Root;
use inc\Controller;
use inc\commonArrays;

use src\lib\Router;
use src\lib\Pagination;
use src\models\Allergies;

class AllergiesController extends Controller {

    public function __construct(){

        parent::__construct();
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Allergies);
        $this->mainTitle    = 'Allergies';
        $this->pag       =  new Pagination(new Allergies(),'');


    }

   
    public function actionIndex()
    {

        $this->subTitle     = ' Allergies List';
        $this->checkPageAccess(83);


        $status   = in_array(Router::post('status'),[0,1]) ? $this->cleanMe(Router::post('status')) : '';
        $page     = $this->cleanMe(Router::post('page')); 
        $page = (!empty($page)) ? $page : '1'; 
        
        

        $filter=["status"   => $status,
                  "page"     => $page];



       // if( ! empty($filter['user_id'])){

       //      $filter['s_username']    = $this->usermdl->getUsername($filter['user_id']);


       //  }
       $data  = $this->mdl->getAllergiesList($filter);
       $onclick   = "onclick=pageHistory('".$filter['status']."','***')";
       $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
       $filter['data']       = $data;


       return $this->render('Allergies/index',$filter); 

   }
   public function actionCreate()
   {
       $this->checkPageAccess(85);

       $this->subTitle     = 'Create Allergy';
       return $this->render('Allergies/create'); 

   }

   public function actionEdit()
   {
       $this->checkPageAccess(84);
       
       $id = $this->cleanMe(Router::req('id')); 
       $id = base64_decode($id);
       $this->subTitle     = 'Edit Allergy';

       $details = $this->mdl->getDetails($id);
       return $this->render('Allergies/edit',['details'=>$details]); 

   }

   public function actionAdd()
   {

      $id              = $this->cleanMe(Router::post('id'));
      $name         = $this->cleanMe(Router::post('name'));
      $this->emptyCheck($name,'Name');
      $params = [];
      $params['name'] = $name;
      $params['id']   = $id;
      $alreadyUsed = $this->mdl->checkAlreadyExists($params);
      if($alreadyUsed) {
          $msg = "Name already used";
          return $this->sendMessage("error",$msg);
          die();

      }


      if(empty($id)) {

          if($this->mdl->createAllergy($params)){

              $msg = "Successfully added allergy";
              return $this->sendMessage("success",$msg);
              die();  

          }else{

              $msg = "Failed to add allergy";
              return $this->sendMessage("error",$msg);
              die();   
          }

            

      }else{

          if($this->mdl->updateAllergy($params)){

              $msg = "Successfully updated allergy";
              return $this->sendMessage("success",$msg);
              die();  

          }else{

              $msg = "Failed to update allergy";
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

            $msg = "Allergy Updated Successfully";
            $this->sendMessage("success",$msg);
            die();

        }else{

            $msg = "Allergy updation Failed";
            $this->sendMessage("error",$msg);
            die();

        }

        $msg = "Something went wrong";
        $this->sendMessage("error",$msg);
        die();



    }


   
   


 
}

