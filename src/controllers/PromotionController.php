<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Promotion;
use inc\Root;
use inc\commonArrays;
/**
 * To handle the users data models
 * @author 
 */

class PromotionController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Promotion);
        $this->pag =  new Pagination(new Promotion(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		    $arr                    = commonArrays::getArrays();
        $this->statusArry       = array(0=>'Hidden', 1 =>'Published');
            
    
    }
    public function actionIndex() {

      $this->checkPageAccess(21);

         $datefrom = $this->cleanMe(Router::post('datefrom')); 
         $dateto   = $this->cleanMe(Router::post('dateto'));
         $status   = $this->cleanMe(Router::post('status'));
       
         $page     = $this->cleanMe(Router::post('page')); 

         $page = (!empty($page)) ? $page : '1'; 
        
         $date_from = empty($datefrom) ? '' : date("Y-m-d", strtotime($datefrom));
         $date_to = empty($dateto) ? '' : date("Y-m-d", strtotime($dateto));

         $filter=["datefrom" => $date_from,
                  "dateto"   => $date_to,
                  "status"   => $status,
                  "page"     => $page];

         $data=$this->mdl->getPromotion($filter);
         
         $onclick = "onclick=pageHistory('".$datefrom."','".$dateto."','".$status."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        
        return $this->render('promotion/index',['datefrom'=>$datefrom,'dateto'=>$dateto,'status'=>$status,'data' => $data, 'pagination'=> $pagination]);
    }
        
public function actionCreate() {
          $this->checkPageAccess(32);
        $this->subTitle  = 'Create Promotion';

        if(isset($_GET['promotion_id'])){

           $promotion = $this->mdl->callsql("SELECT * FROM `promotions` WHERE id='$_GET[promotion_id]' ","row");
           
           $this->subTitle  = 'Edit Promotion';
        }


        return $this->render('promotion/create',['promotion'=>$promotion]);
    }
    
   public function actionAdd() {
         $this->checkPageAccess(32);
       
        $title        = cleanMe($_POST['title']);
        $message      = cleanMe($_POST['message']);
        $editID       = cleanMe($_POST['editID']);
        $status       = cleanMe($_POST['status']); 
        $date         = cleanMe($_POST['date']);
        $maxsize      = 8388608; 


        if(empty($title)){
           return $this->sendMessage("error","Enter Title to Proceed");
        }
        if(empty($date)){
           return $this->sendMessage("error","Enter date to Proceed");
        }

    
        if(empty($_FILES) && empty($editID)){
           return $this->sendMessage("error","Upload file to Proceed");
        } 


        

      
        if(!empty($editID)){

           $prevData     = $this->mdl->callsql("SELECT image FROM `promotions` WHERE id='$editID' ","row");
           $promotion = $prevData['image'];
         

           if(empty($promotion) && empty($_FILES))
               return $this->sendMessage("error","Upload file to Proceed");
        }

      $files =  $_FILES['filename'];
      if(isset($files)){

              $acceptable = array('image/jpeg','image/jpg','image/png');

              if((!in_array($files['type'], $acceptable)) && (!empty($files["type"])))
                  return $this->sendMessage("error","Invalid File Only PNG /JPEG type accepted"); 

              if($files['size']==0)
                  return $this->sendMessage("error","Invalid File Only PNG /JPEG type accepted");

              if($files['size']>$maxsize)
                  return $this->sendMessage("error","File size exceeds maximum limit 8 MB");  

              $filename    = $files['name']; 
              $temp_name   = $files['tmp_name'];
              $path_parts  = pathinfo($filename);
              $extension   = $path_parts['extension'];
              $newFile_org = 'promotion'.time().'.'.$extension;
              $target_file = FILEUPLOADPATH.'promotions/'.$newFile_org;

             

              
              if(!move_uploaded_file ($temp_name, $target_file)){
                   
                  return $this->sendMessage("error",'File Upload Failed');
              }

              if(!empty($editID) && !empty($promotion) && (!empty($_FILES))){

                  $Deletefile = FILEUPLOADPATH."promotions/".$promotion; 
           
                  if (file_exists($Deletefile))
                      unlink($Deletefile); 
              }

             
            
          }

          if(!empty($promotion) && !empty($editID) && empty($_FILES))
          {
            $newFile_org = $promotion;
          }
          


        if(!empty($editID)){
             //$this->checkPageAccess(35);
           $success = $this->mdl->update_promotion($_POST,$newFile_org);
           $msg     = 'Details Updated Successfully';
        }else{

           $success = $this->mdl->addPromotion($_POST,$newFile_org);
           $msg     = 'Promotion Added';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    } 


   

    public function actionDelete(){
          $this->checkPageAccess(34);
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deletePromotion($ID);

        if($delete){
            return $this->sendMessage('success',"Promotion Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }

   
}

