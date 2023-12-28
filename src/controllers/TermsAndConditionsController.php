<?php

namespace src\controllers;
use inc\Root;
use inc\Controller;
use inc\commonArrays;

use src\lib\Router;
use src\lib\Pagination;
use src\models\TermsConditions;

class TermsAndConditionsController extends Controller {

    public function __construct(){
        parent::__construct();
        $this->admin         = $this->admin_id;

        $this->mdl           = (new TermsConditions);
        $this->mainTitle    = 'Terms and Conditions';
        $this->pag       =  new Pagination(new TermsConditions(),'');
    }

   
    public function actionIndex(){
        $this->subTitle     = 'Terms and Conditions List';
        $this->checkPageAccess(94);
        $status   = $this->cleanMe(Router::post('status'));
        $page     = $this->cleanMe(Router::post('page')); 
        $page = (!empty($page)) ? $page : '1'; 

        $filter=["status"   => $status,
                  "page"     => $page];

       $data  = $this->mdl->getList($filter);
       
       $filter['data']       = $data;

       return $this->render('TermsConditions/index',$filter); 

   }
   public function actionCreate()
   {
       $this->checkPageAccess(92);

       $this->subTitle  = 'Create Position';

       $lang_list       = $this->mdl->getAllLangiages();

       return $this->render('TermsConditions/add_new',['lang_list'=>$lang_list]); 

   }

   

    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Root::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
    }

    public function actionChangeStatus(){

        $id     = $this->cleanMe(Router::post('id')); 
        $status = $this->cleanMe(Router::post('status'));
        $params = [];
        $params['status'] = $status;
        $params['id']     = $id;
        $update = $this->mdl->updateStatus($params);
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

    public function actionUploadPdfFile(){
        $lang_id     = $this->cleanMe(Router::post('lang_id')); 

        $params = [];

        if(empty($lang_id)) {
            return $this->sendMessage("error",'Please select Language');
        }
        if(!empty($lang_id)){
            $checkExists = $this->mdl->checkExists($lang_id);
            if(!empty($checkExists)){
                return $this->sendMessage("error",'Terms and conditions exists for the selected language !');
            }
        }

        if(empty($_FILES['file'])) {
            return $this->sendMessage("error",'Please select file to upload');
        }

        if(!empty($_FILES['file'])){     
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('pdf');

            if(!in_array($extension, $image_array)){
                $data['msg'] = 'Please Select Valid File Format';
                $data['showlistpopup'] = false;
                return $this->sendMessage("error",'Please Select Valid File Format');
            }
            $newFile_org = 'TermsConditions'.$this->admin.'_'.time().'.'.$extension;
            $target_file = BASEPATH."web/upload/termsandconditions/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }
            if(!move_uploaded_file ($temp_name, $target_file)){

               $data['msg'] = 'Something Went Wrong...';
               $data['showlistpopup'] = false;
               return $this->sendMessage("error",'Something Went Wrong...',"Invalid format");
            }else{
                $params['file_name']    = $newFile_org;
                $params['lang_id']      = $lang_id;

                $update =$this->mdl->insertData($params);
                return $this->sendMessage('success',"Pdf File uploaded Successfully");

            }
        }
    }

    public function actionUpdateStatus(){
        $id         = $this->cleanMe(Router::post('getId'));
        $status     = $this->cleanMe(Router::post('status'));

        $checkExists = $this->mdl->checkExistsActive($id);
        if(!empty($checkExists)){
            return $this->sendMessage("error",'Cannot activate multiple Terms and conditions for the same language !');
        }

        $delete = $this->mdl->updateTermsconditions($id, $status);

        if($delete){
            return $this->sendMessage('success',"Status Updated");
        }else
           return $this->sendMessage("error","Something Went Wrong..");
    }
}

