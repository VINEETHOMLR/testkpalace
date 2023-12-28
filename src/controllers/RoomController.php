<?php

namespace src\controllers;
use inc\Root;
use inc\Controller;
use inc\commonArrays;

use src\lib\Router;
use src\lib\Pagination;
use src\lib\walletClass;
use src\models\SettingsModal;
use src\models\Customer;
use src\models\User;
use src\models\Room;
use src\models\Alcohol;

class RoomController extends Controller {

    public function __construct(){

        parent::__construct();
        

    
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Customer);
        $this->usermdl       = (new User);
        $this->roommdl       = (new room);
        $this->walletClass   = (new walletClass);
        $this->pag           =  new Pagination(new room(),''); 
        $this->getArray      = (new commonArrays)->getArrays();
        $this->userArr       = $this->systemArrays['userStatusArr'];

        $this->ImgArr       = [0=>"Active",1=>"Hide"];
        $this->wallets      = $this->systemArrays['wallets'];

        $this->mainTitle    = 'Room';

        $this->tabs         = [''];


    }

 public function getInputs()
    {
        $input = [];
       
        $input['status']     = $this->cleanMe(Router::post('status')); 
        $input['maximum_allowed']   = $this->cleanMe(Router::post('maximum_allowed'));
        $input['price']   = $this->cleanMe(Router::post('price'));
        $input['type']   = $this->cleanMe(Router::post('type'));
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;



        return $input;
    }

   public function actionIndex()
   {

       $this->subTitle     = ' Room List';
       $this->checkPageAccess(75);
       $filter  = $this->getInputs();

       if( ! empty($filter['user_id'])){

            $filter['s_username']    = $this->usermdl->getUsername($filter['user_id']);


        }
       $data  = $this->roommdl->getRoomList($filter);


       $onclick   = "onclick=pageHistory('".$filter['status']."','".$filter['maximum_allowed']."','".$filter['price']."','".$filter['type']."','***')";
       $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
       $filter['data']       = $data;


       return $this->render('Room/roomlist',$filter); 

   }
   public function actionCreateroom()
   {
       $this->checkPageAccess(63);

       $this->subTitle     = 'Create Room';
       $service_group_id = $this->roommdl->getserivegroupid($this->admin_id);
       return $this->render('Room/create_room'); 

   }

   public function actionAddnewroom()
   {



        $id              = $this->cleanMe(Router::post('id'));
        $room_no         = $this->cleanMe(Router::post('room_no'));
        $table_no         = $this->cleanMe(Router::post('table_no'));
        $type            = $this->cleanMe(Router::post('type'));
        $level           = $this->cleanMe(Router::post('level'));
        $description     = $this->cleanMe(Router::post('description'));
        $maximum_allowed = $this->cleanMe(Router::post('maximum_allowed'));
        $price           = $this->cleanMe(Router::post('price'));

        if($type== '1')
         {

          $this->emptyCheck($level,'Level');
          if(!empty($level)){
            $this->validate_number($level);
           
           }
          $this->emptyCheck($room_no,'Room Number');
          $this->emptyCheck($description,'Description');
          $this->emptyCheck($maximum_allowed,'Maximum Allowed');
          if(!empty($maximum_allowed)){
            $this->validate_number_for_max_allowed($maximum_allowed);
           
          }
          if(!empty($price)){
            $this->validate_price($price);
           
           }

           $this->emptyCheck($price,'Price');

           $this->validatePricetag($price);
           $this->validatenumtag($maximum_allowed);
           $test_room_availability = $this->roommdl->checkroomavailability($room_no,$level,$type);

           $this->alredyExist($test_room_availability,'Room Already Exists');

       
       
           $params=array(
            'room_no'        => $room_no,
            'type'           => $type,
            'level'          => $level,
            'description'    => $description,
            'maximum_allowed'=> $maximum_allowed,
            'price'          => $price,
           
            );


            if(empty($id)){
            $is_updated =$this->roommdl->createRoom($params); 
              if ($is_updated === true) {
                 $room_id =$this->roommdl->lastInsertId();
                 $activity='Creation of new Room id-'.$room_id;
                 $this->roommdl->adminActivityLog($activity);
                 $msg = "Room Added Successfully";
                 $this->sendMessage("success",$msg);
               }else{
                 $msg=Root::t('subadmin','edit_err_text');
                 return $this->sendMessage("error",$msg);
               }
            }
        
        

        return false;  

    }
    if($type== '2')
    {
       $this->emptyCheck($table_no,'Table Number');
       $this->emptyCheck($description,'Description'); 
       $testtableavailabily = $this->roommdl->checktableavailability($table_no,$type);
       $this->alredyExisttable($testtableavailabily,'Table Already Exists');

        $params=array(
            'table_no'        => $table_no, 
            'type'           => $type,
            'description'    => $description,
        );


        if(empty($id)){
            $is_updated =$this->roommdl->createTable($params); 
            if ($is_updated === true) {

                $table_id =$this->roommdl->lastInsertId();

                $activity='Creation of new Table id - '.$table_id;
                $this->roommdl->adminActivityLog($activity);
                $msg = "Table Added Successfully";
                $this->sendMessage("success",$msg);
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
         }
        
      }
    }
    function validatePricetag($price) {

      if (preg_match('/^0{2,}/', $price)) {
         echo $this->sendMessage("error","Enter Valid Price"); exit();
       } 
       if(substr($price, 0, 1) === '0'){
         echo $this->sendMessage("error","Enter Valid Price"); exit();
       }
    }

    function validatenumtag($maximum_allowed) {
        if ($maximum_allowed == 0 || preg_match('/00+/', $maximum_allowed)) {
            echo $this->sendMessage("error","Enter Valid number for maximum person allowed"); exit();
        } 
    }

    
    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Root::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
    }

   
      private function validate_number($number){
       
       if (!preg_match('/^[0-9]+$/', $number)) {
         echo $this->sendMessage("error","Enter Valid number for Level"); exit();
        }

        
       }
       private function validate_number_for_max_allowed($number){
       
       if (!preg_match('/^[0-9]+$/', $number)) {
         echo $this->sendMessage("error","Enter Valid number for Maximum Person allowed"); exit();
        }

        
       }
     private function validate_price($price){
       
       if (!preg_match('/^[0-9]+$/', $price)) {
         echo $this->sendMessage("error","Enter Valid Price"); exit();
        }


        
     }
    public function alredyExist($var,$key){
        if(!empty($var)){
         
         $this->sendMessage("error","Room Not Available");
         die();
        }
    }

    public function alredyExisttable($var,$key){
        if(!empty($var)){
         
         $this->sendMessage("error","Table Not Available");
         die();
        }
    }
    public function actionBlockroom(){
            
            $this->checkPageAccess(7);
            $value = $this->cleanMe(Router::post('uid')); 

            $type =$this->roommdl->gettype($value);

           

            $status = $this->cleanMe(Router::post('status')); 



            if($status == 0){

               if($type== '1'){

                $action = 'Activated Room ';
               }
               else{
                $action = 'Activated Table ';
               }
              
            }
            else
            {
              if($type== '1'){
              $action = 'Blocked Room ';
              }
              else{
              $action = 'Blocked Table';
              }
            }

            

            $update =$this->roommdl->updateroomStatus($status,$value);
            
            if($update){
              $activity= $action.' id -'.$value;
              $this->roommdl->adminActivityLog($activity);

              $this->sendMessage('success', $action);
            }
    }
  

  public function actionUpdateroom()
  {
    $this->checkPageAccess(76);

    $id = $this->cleanMe(Router::get('id'));
    
     
     $details = $this->roommdl->getdetailsofroom($id);

     $data['details'] =$details;

      return $this->render('Room/edit_room',$data);
   
  }

  public function actionUpdateroomdetails()
  {


    $id  = $this->cleanMe(Router::post('id'));
       
       
       $type_id  = $this->roommdl->gettype($id);


         if($type_id == '1')  {

        $room_no         = $this->cleanMe(Router::post('room_no'));
        $level           = $this->cleanMe(Router::post('level'));
        $description     = $this->cleanMe(Router::post('description'));
        $maximum_allowed = $this->cleanMe(Router::post('maximum_allowed'));
        $price           = $this->cleanMe(Router::post('price'));

           $this->emptyCheck($level,'Level');
          if(!empty($level)){
            $this->validate_number($level);
           
           }
          $this->emptyCheck($room_no,'Room Number');
          $this->emptyCheck($description,'Description');
          $this->emptyCheck($maximum_allowed,'Maximum Allowed');
          if(!empty($maximum_allowed)){
            $this->validate_number_for_max_allowed($maximum_allowed);
           
          }
          if(!empty($price)){
            $this->validate_price($price);
           
           }

           $this->emptyCheck($price,'Price');

           $this->validatePricetag($price);
           $this->validatenumtag($maximum_allowed);
           $check_room_availability = $this->roommdl->checkroomexist($room_no,$level,$type_id,$id);

           $this->alredyExist($check_room_availability,'Room Already Exists');
            $params=array(
                'id'             => $id,
                'room_no'        => $room_no,
                'type'           => '1',
                'level'          => $level,
                'description'    => $description,
                'maximum_allowed'=> $maximum_allowed,
                'price'          => $price,
               
            );


            if(!empty($id)){
                $is_updated =$this->roommdl->Updateroom($params); 
                if ($is_updated === true) {
                    $activity='Update Room id -'.$id;
                    $this->roommdl->adminActivityLog($activity);
                    $msg = "Room Updated Successfully";
                    $this->sendMessage("success",$msg);
                }else{
                    $msg=Root::t('subadmin','edit_err_text');
                    return $this->sendMessage("error",$msg);
                }
            }
            
            

            

        }

        if($type_id == '2')
        {
            $table_no         = $this->cleanMe(Router::post('table_no'));
            
            $description     = $this->cleanMe(Router::post('description'));

            $this->emptyCheck($table_no,'Table Number');
            $this->emptyCheck($description,'Description'); 
            $testtableavailabily = $this->roommdl->checktableExist($table_no,$type_id,$id);
            $this->alredyExisttable($testtableavailabily,'Table Already Exists');

             $params=array(
            'id'             => $id,
            'table_no'        => $table_no, 
            'type_id'           => $type_id,
            'description'    => $description,
        );


        if(empty(!$id)){
            $is_updated =$this->roommdl->UpdateTable($params); 
            if ($is_updated === true) {

                $table_id =$this->roommdl->lastInsertId();

                $activity='Updation Table id - '.$id;
                $this->roommdl->adminActivityLog($activity);
                $msg = "Table Updated Successfully";
                $this->sendMessage("success",$msg);
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
         }
        
        } return false; 
  }
}

