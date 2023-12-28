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
use src\models\Hour;
use src\models\Room;
use src\models\Alcohol;

class HoursController extends Controller {

    public function __construct(){

        parent::__construct();
        

    
        $this->admin         = $this->admin_id; 
        $this->hourmdl       = (new Hour);
        $this->walletClass   = (new walletClass);
        $this->pag           =  new Pagination(new Hour(),''); 
        $this->getArray      = (new commonArrays)->getArrays();
        $this->userArr       = $this->systemArrays['userStatusArr'];

        $this->ImgArr       = [0=>"Active",1=>"Hide"];
        $this->wallets      = $this->systemArrays['wallets'];

        $this->mainTitle    = 'Hours';

        $this->tabs         = [''];


    }

 public function getInputs()
    {
        $input = [];
       
        $input['status']     = $this->cleanMe(Router::post('status')); 
        $input['name']   = $this->cleanMe(Router::post('name'));
        $input['from_time']   = $this->cleanMe(Router::post('from_time'));
        $input['to_time']   = $this->cleanMe(Router::post('to_time'));
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;



        return $input;
    }

   public function actionIndex()
   {

       $this->subTitle     = 'Hours List';
       $this->checkPageAccess(72);
       $filter  = $this->getInputs();

       
       $data  = $this->hourmdl->getHourList($filter);


       $onclick   = "onclick=pageHistory('".$filter['status']."','".$filter['name']."','".$filter['from_time']."','".$filter['to_time']."','***')";


       $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
       $filter['data']       = $data;


       return $this->render('Hours/Hourslist',$filter); 

   }
   public function actionCreateHours()
   {
       $this->checkPageAccess(73);
       $this->subTitle     = 'Create Hours';
       return $this->render('Hours/create_hours'); 

   }
   public function actionAddnewhours()
   {

        $id              = $this->cleanMe(Router::post('id'));
        $name            = $this->cleanMe(Router::post('name'));
        $from_time       = $this->cleanMe(Router::post('from_time'));
        $to_time         = $this->cleanMe(Router::post('to_time'));
        

        $this->emptyCheck($name,'Name');
        $name_check = $this->hourmdl->checknameexist($name);
        $this->alredyExistname($name_check,'Name Already Exists');
          
        $this->emptyCheck($from_time,'From Time');

        $this->emptyCheck($to_time,'To Time');
        
        $time_frame_check =$this->hourmdl->checktimeframeexist($from_time,$to_time);
        $this->alredyExisttimeframe($time_frame_check,'Time Frame Already Exists');

        if($from_time == $to_time){
        $this->sendMessage("error","Start Time and End Time Should be Diffrent");
         die();

        }
        $params=array(
            'name'      => $name,
            'from_time' => $from_time,
            'to_time'   => $to_time,
           
            );


            if(empty($id)){
            $is_updated =$this->hourmdl->createHour($params); 
              if ($is_updated === true) {
                 $room_id =$this->hourmdl->lastInsertId();
                 $activity='Creation of Hours id-'.$room_id;
                 $this->hourmdl->adminActivityLog($activity);
                 $msg = "Hours Added Successfully";
                 $this->sendMessage("success",$msg);
               }else{
                 $msg=Root::t('subadmin','edit_err_text');
                 return $this->sendMessage("error",$msg);
               }
            }
        
        

        return false;  

    }
    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Root::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
    }
    public function actionBlockHours(){
            
        $this->checkPageAccess(7);
        $value = $this->cleanMe(Router::post('uid')); 

        $status = $this->cleanMe(Router::post('status')); 



        if($status == 0){
            $action = 'Activated Hours';
               
        }
              
            
        else{
              
           $action = 'Blocked Hours';
              
        }

        $details = $this->hourmdl->getTime($value);
        if($status == '0'){

        $time_frame_check =$this->hourmdl->checktimeframeexist($details['from_time'],$details['to_time']);
        $time_check =$this->alredyExisttimeframe($time_frame_check,'Time Frame Already Exists'); 
        }

       if(empty($time_frame_check)){
       $update =$this->hourmdl->updateHoursStatus($status,$value);

        if($update){
            $getdetailsofhours = $this->hourmdl->getdetailsofhours($value);
            $activity= $action.' Name -'.$getdetailsofhours['name'].' id -'.$value;
            $this->hourmdl->adminActivityLog($activity);

            $this->sendMessage('success', $action);
       }
        }

        else{
             $this->sendMessage('success', 'error');
       }
        

    }


   public function actionEdithours()
  {
    $this->checkPageAccess(74);
    $id = $this->cleanMe(Router::get('id'));
    $details = $this->hourmdl->getdetailsofhours($id);
    $data['details'] =$details;
    return $this->render('Hours/edit_hours',$data);
   
  }
public function actionUpdateHours()
  {


    $id              = $this->cleanMe(Router::post('id'));
    $name            = $this->cleanMe(Router::post('name'));
    $from_time       = $this->cleanMe(Router::post('from_time'));
    $to_time         = $this->cleanMe(Router::post('to_time'));
        

    $this->emptyCheck($name,'Name');
    $test_name_updation = $this->hourmdl->checknameavailability($name,$id);
    $this->alredyExistnameforupdation($test_name_updation,'Name Already Exists');
          
    $this->emptyCheck($from_time,'From Time');

    $this->emptyCheck($to_time,'To Time');
    if($from_time == $to_time){
        $this->sendMessage("error","Start Time and End Time Should be Diffrent");
         die();

        }

    $update_time_frame = $this->hourmdl->checktimeframeupdation($from_time,$to_time,$id);
    $this->alredyExisttimeframeforupdation($update_time_frame,'Time Frame Already Exists');
         
    $params=array(
            'id'        => $id,
            'name'      => $name,
            'from_time' => $from_time,
            'to_time'   => $to_time,
           
            );


         if(!empty($id)){
            $is_updated =$this->hourmdl->Updatehours($params); 
            if ($is_updated === true) {
                $activity='Updated Hours - '.$name .' id -'.$id;
                $this->hourmdl->adminActivityLog($activity);
                $msg = "Hours Updated Successfully";
                $this->sendMessage("success",$msg);
                }else{
                    $msg=Root::t('subadmin','edit_err_text');
                    return $this->sendMessage("error",$msg);
                }
            }
            
            

            

    }
 
public function alredyExistname($var,$key){
        if(!empty($var)){
         
         $this->sendMessage("error","Name Already Exist");
         die();
        }
    }
 public function alredyExisttimeframe($var,$key){
        if(!empty($var)){
         
         $this->sendMessage("error","Time frame Already Exist");
        
         die();

        }
    } 

 public function alredyExistnameforupdation($var,$key){
        if(!empty($var)){
         
         $this->sendMessage("error","Name Already Exist");
         die();
        }
    } 
    public function alredyExisttimeframeforupdation($var,$key)
    {
        if(!empty($var)){
         
         $this->sendMessage("error","Timeframe Already Exist");
         die();

        }
    } 
    
    
  

}

