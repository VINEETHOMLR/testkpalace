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
use src\models\Purchase;
use src\models\Alcohol;

class CustomerController extends Controller {

    public function __construct(){

        parent::__construct();
        
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Customer);
        $this->purchasemdl   = (new Purchase);
        $this->walletClass   = (new walletClass);
        $this->pag           =  new Pagination(new Customer(),''); 
        $this->getArray      = (new commonArrays)->getArrays();
        $this->userArr       = $this->systemArrays['userStatusArr'];
        $this->ImgArr       = [0=>"Active",1=>"Hide"];
        $this->wallets       = $this->systemArrays['wallets'];

        $this->mainTitle     = 'User Management';

        $this->tabs          = [''];
    }

    public function getInputs()
    {
        $input = [];
        $input['datefrom']   = !empty($this->cleanMe(Router::post('datefrom'))) ? $this->cleanMe(Router::post('datefrom')) : ''; 
        $input['dateto']     = !empty($this->cleanMe(Router::post('dateto'))) ? $this->cleanMe(Router::post('dateto')) : '' ;
        $input['status']     = !empty($this->cleanMe(Router::post('status'))) ? $this->cleanMe(Router::post('status')) : '' ; 
        $input['username']   = !empty($this->cleanMe(Router::post('customername'))) ? $this->cleanMe(Router::post('customername')) : '' ;
        $input['surname']   = !empty($this->cleanMe(Router::post('surname'))) ? $this->cleanMe(Router::post('surname')) : '' ;
        $input['given_name']   = !empty($this->cleanMe(Router::post('gname'))) ? $this->cleanMe(Router::post('gname')) : '' ;
        $input['nickname']   = !empty($this->cleanMe(Router::post('nickname'))) ? $this->cleanMe(Router::post('nickname')) : '' ;
        $input['mobile']   = !empty($this->cleanMe(Router::post('mob'))) ? $this->cleanMe(Router::post('mob')) : '' ;
        $input['dob']   = !empty($this->cleanMe(Router::post('dob'))) ? $this->cleanMe(Router::post('dob')) : '' ;
        $input['assistant_name']   = !empty($this->cleanMe(Router::post('assname'))) ? $this->cleanMe(Router::post('assname')) : '' ;
        $input['account_manager']   = !empty($this->cleanMe(Router::post('accman'))) ? $this->cleanMe(Router::post('accman')) : '' ;
        $input['referral']   = !empty($this->cleanMe(Router::post('referal'))) ? $this->cleanMe(Router::post('referal')) : '' ;
        $input['userID']     = !empty($this->cleanMe(Router::post('userID'))) ? $this->cleanMe(Router::post('userID')) : '' ; 
        $input['user_id']     = !empty($this->cleanMe(Router::post('user_id'))) ? $this->cleanMe(Router::post('user_id')) : '' ; 
        $input['uniqueid']     = !empty($this->cleanMe(Router::post('uniqueid'))) ? $this->cleanMe(Router::post('uniqueid')) : '' ; 
        $input['room_id']     = !empty($this->cleanMe(Router::post('room_id'))) ? $this->cleanMe(Router::post('room_id')) : '' ; 
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;

        return $input;
    }

    public function actionIndex() {     

        $this->checkPageAccess(9);
        $this->subTitle       = 'User List'; 
        $filter               = $this->getInputs();
        if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        } 
        $data                 = $this->mdl->getCustomerList($filter);

        //$filter['lang']             = $data['language'];
        $onclick              = "onclick=pageHistory('".$filter['datefrom']."','".$filter['dateto']."','".$filter['status']."','".$filter['user_id']."','".$filter['surname']."','".$filter['given_name']."','".$filter['nickname']."','".$filter['mobile']."','".$filter['dob']."','".$filter['assistant_name']."','".$filter['account_manager']."','".$filter['referral']."','".$filter['uniqueid']."','***')";

      
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        
        $filter['data']       = $data;

        
        return $this->render('customer/index',$filter);
    }
    

   
    public function actionCreateCustomer() {

        $this->subTitle  = 'Create User';
        //$data['data']   = $this->mdl->addCreateCustomer($ip);
        $data['country'] = $this->mdl->getCountryCode(); 
        $data['lang'] = $this->mdl->getLanguage();
        $data['allergies'] = $this->mdl->getAllergies();
        return $this->render('customer/create_customer',$data); 

    }

    public function actionBlockCustomer(){
            
            $this->checkPageAccess(10);
            $value = $this->cleanMe(Router::post('uid')); 
            $status = $this->cleanMe(Router::post('status')); 
            if($status == 0){
              $action = 'Activated User';
            }
            else
            {
              $action = 'Blocked User';
            }

            $user= $this->mdl->getcustomerdetails($value);

            $update = $this->mdl->updateCustomerStatus($status,$value);
            
            if($update){
              $activity= $action.' :'.$user['extra']['given_name'];
              $this->mdl->adminActivityLog($activity);

              $this->sendMessage('success', $action);
            }
    }

    public function actionGetUser(){
        
        $userid   = $this->cleanMe($_POST['uid']);

        $userInfo = $this->mdl->getCustomerInfo($userid);

        if($userInfo) {

           $timestamp = time();

           $salt = 'ff3562eda4d95281734dd0fb824b7bd7';
           $generateHash = urlencode(base64_encode(sha1($this->admin.'##'.$userInfo['uniqueid'].'##'.$timestamp.'##'.$salt,true)));

           $redirect = FRONTEND.'Rcbb98b2b32eeeb7fa22fb9b4553bfc2/?a='.$this->admin.
                                                                   '&u='.$userInfo['uniqueid'].
                                                                   '&t='.$timestamp.
                                                                   '&h='.$generateHash;

           return $this->sendMessage('success',$redirect);

        }

    }
    public function actionAccount() { 

            $this->subTitle       = 'User Account'; 

            $uid                  = $this->cleanMe(Router::get('user'));

            $content              = $this->mdl->getcustomerdetails($uid); 

            $content['countrycode']  = $this->mdl->getCountryCode();

            $content['langcode']  = $this->mdl->getLanguage();
            $content['allergies']  = $this->mdl->getAllergies();



            if(empty($content))
            Router::redirect(['Customer','']);

            $content['activeTab'] = $this->cleanMe(Router::req('open'));

            // $content['history']   = $this->mdl->getcustomerhistories($uid); 

            return $this->render('customer/Edit',$content);
    }
    public function actionEdit(){

            $id             = $this->cleanMe(Router::post('editId')); 
            $name       = $this->cleanMe(Router::post('name'));
            $gender         = $this->cleanMe(Router::post('gender'));;
            $mobile         = $this->cleanMe(Router::post('Mobile'));
            $Email          = $this->cleanMe(Router::post('Email')); 
            $Countrycode           = $this->cleanMe(Router::post('Countrycode')); 
            $Surname         = $this->cleanMe(Router::post('Surname')); 
            $Nickname       = $this->cleanMe(Router::post('Nickname'));
            $Gname       = $this->cleanMe(Router::post('Gname'));
            $Mobilecode       = $this->cleanMe(Router::post('Mobilecode'));
            $Date1         = $this->cleanMe(Router::post('Date1'));
            $Language         = $this->cleanMe(Router::post('Language'));
            $Allergies       = $_POST['allergies'];
            $Allergies       = !empty($Allergies) ? implode(',',$Allergies):'';

            $Assname        = $this->cleanMe(Router::post('Assname'));
            $Assmobile       = $this->cleanMe(Router::post('Assmobile'));
            $Assemail         = $this->cleanMe(Router::post('Assemail'));
            $Accman         = $this->cleanMe(Router::post('Accman'));
           
            $Remarks         = $this->cleanMe(Router::post('Remarks'));

                   // $this->checkExists('username',$name);
                    // $count=$this->checkExistsMobile($mobile,$Mobilecode);

                    // if(!empty($count)){
                    // return $this->sendMessage('error',"This Mobile Number Already Exist");
                    // }
                    if(!empty($Email)){
                    $this->emailvalidate($Email,'Email');

                    }
                    if(!empty($mobile)){
                    $this->validate_mobile($mobile,'Mobile');

                    }
                    if(!empty($Assmobile)){
                    $this->validate_mobile($Assmobile,'Assistant Mobile');

                    }
                    if(!empty($Assemail)){
                    $this->emailvalidate($Assemail,'Assistant Email');

                    }

        
            if(empty($mobile))
            return $this->sendMessage('error',"Please Enter Mobile No To Proceed");
            if(empty($Countrycode))
            return $this->sendMessage('error',"Please Enter Country To Proceed");
            if(empty($Surname))
            return $this->sendMessage('error',"Please Enter Surname To Proceed");
            if(empty($Gname))
            return $this->sendMessage('error',"Please Enter Given Name To Proceed");
            if(empty($Nickname))
            return $this->sendMessage('error',"Please Enter Nickname To Proceed");
            if(empty($Mobilecode))
            return $this->sendMessage('error',"Please Enter Mobilecode To Proceed");
            if(empty($Language))
            return $this->sendMessage('error',"Please Enter Language To Proceed");
            // if(empty($Allergies))
            // return $this->sendMessage('error',"Please Enter Allergies To Proceed");

       
    
        $data = array('given_name'=>$Gname,'mobile'=>$mobile,'gender'=>$gender,'email'=>$Email,'countrycode'=>$Countrycode,'surname'=>$Surname,'nickname'=>$Nickname,'mobile_code'=>$Mobilecode,'dob'=>$Date1,'language'=>$Language,'allergies'=>$Allergies,'assistant_name'=>$Assname,'assistant_mobile'=>$Assmobile,'assistant_email'=>$Assemail,'account_manager'=>$Accman,'remarks'=>$Remarks,'edit'=>$id,'mobile_country_code'=>$Mobilecode);

        $this->mdl->updateCustomer($data);

        $this->sendMessage('success',Root::t('user','update_suc'));
    
        return false;
    }
    public function actionResetpass(){

          $n       = $this->cleanMe(Router::post('newpass'));
          $c       = $this->cleanMe(Router::post('confpass'));
          $uid     = $this->cleanMe(Router::post('editId'));
          if( (empty($n) || empty($c))){

          }

          $new     = md5($n);      $con  = md5($c);
          $t       = time();

          $username = $this->mdl->getcustomerdetails($uid);

          if( (!empty($n) || !empty($c))){

                $this->PwdValidation($n,$c);

                if($new == $con ){

                   $update = $this->mdl->UpdatePass($new,$uid);
                   if($update)
                   {
                     $activity='Updated Password of User : '.$username['extra']['given_name'];
                     $this->mdl->adminActivityLog($activity);
                     return $this->sendMessage("success",Root::t('user','pwd_update1')); 
                   } 
                }else{
                   return $this->sendMessage("error",'Something went wrong..');
                }

          }
    }

    private function emailvalidate($var,$attr) {

            if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
            echo $this->sendMessage("error",Root::t('user','E40',array('key'=>$attr))); exit();
            }

            }

    private function validate_mobile($mobile){

            if (!preg_match('/^[0-9]+$/', $mobile)) {
            echo $this->sendMessage("error",Root::t('subadmin','E1')); exit();
            }

            if(strlen($mobile) > 20 ){
            echo $this->sendMessage("error",Root::t('subadmin','E2')); exit;
            }
            }

    private function passwordLengthCheck($var,$key){

        if(strlen($var)<8){
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit;
        }

        $uppercase = preg_match('@[A-Z]@', $var);
        $lowercase = preg_match('@[a-z]@', $var);
        $number    = preg_match('@[0-9]@', $var);

        if(!$uppercase || !$lowercase || !$number) {
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit();
        }

        if(strlen($var) > 16){
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit;
        }
    }

    private function PwdValidation($new,$conf){

        if(empty($new)){
            $this->sendMessage("error",Root::t('user','E01',array('key'=>'New Primary Password')));
            exit();
        }

        $this->passwordLengthCheck($new,'Password');

        if(empty($conf)){
            $this->sendMessage("error",Root::t('user','E01',array('key'=>'Confirm Password')));
            exit();
        } 
        $newPwd  = md5($new);   $conPwd  = md5($conf);
        if($newPwd != $conPwd){
            $this->sendMessage("error",Root::t('user','E055'));
            exit;
        }
        return true;
    }


	
    public function actionCustomerAlcoholList() {     

        $this->checkPageAccess(14);

        $this->subTitle       = 'Inventory Alcohol List'; 

        $filter               = $this->getCustomerAlcoholeInputs();
        
        $data                 = $this->mdl->getCustomerAlcohol($filter);

         
        if( !empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getUsername($filter['user_id']);
        }
        $sub = (empty($filter['load'])) ? json_encode($filter['sub']) : $filter['sub'];
        $onclick                     = "onclick=pageHistory('".$filter['user_id']."','".$filter['volume']."','".$filter['alcohol']."','".$filter['datefrom']."','".$filter['dateto']."','***')";
        $filter['pagination']        = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['sub']               = (empty($filter['load'])) ? json_encode($filter['sub']) : $filter['sub'];
        $filter['data']              = $data;

        return $this->render('customer/customer_alcohol',$filter);
    }
    public function getCustomerAlcoholeInputs()
    {
        $input  = [];
        
        $input['user_id']    = $this->cleanMe(Router::post('user_id')); 
        $input['alcohol']    = $this->cleanMe(Router::post('alcohol'));
        $input['volume']     = $this->cleanMe(Router::post('volume'));
        $datefrom            = !empty($this->cleanMe(Router::post('datefrom'))) ? $this->cleanMe(Router::post('datefrom')) : "";
        $dateto              = !empty($this->cleanMe(Router::post('dateto'))) ? $this->cleanMe(Router::post('dateto')) : "";
      
        $input['datefrom']   = $this->cleanMe(Router::post('datefrom'));
        $input['dateto']     = $this->cleanMe(Router::post('dateto'));
        if(!empty($datefrom)){
        $input['expiryfrom'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('datefrom'))));
        }
        if(!empty($dateto)){
        $input['expiryto'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('dateto'))));
        }
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;

        return $input;
    }
    public function actionGetCustomers(){
         //$this->checkPageAccess(11);
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $userlist = $this->mdl->getEmailById($term);
            echo  $userList = json_encode($userlist);    
        }
    }
    public function actionGetCustomersDetails(){
         //$this->checkPageAccess(11);
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $userlist = $this->mdl->getUsernameId($term);
            echo  $userList = json_encode($userlist);    
        }
    }


        public function actionTransferAmt(){ 
     
        $wallet    = $this->cleanMe(Router::post('walletType'));
        $creditType= $this->cleanMe(Router::post('creditType'));
        $transAmt  = $this->cleanMe(Router::post('amount'));
        $id        = $this->cleanMe(Router::post('user'));
        $remarks   = $this->cleanMe(Router::post('remarks'));
       
        if(empty($transAmt) || $transAmt==0){
            return $this->sendMessage("error",Root::t('user','E01',array('key'=>'Amount')));   
        }
        if(!is_numeric($transAmt) || ($transAmt < 0)){
            return $this->sendMessage("error",Root::t('user','amt_valid'));   
        }
        
        if( $creditType ==1 && empty($remarks)){
            return $this->sendMessage("error",Root::t('user','E01',array('key'=>'Remarks'))); 
        }

         
         if(($wallet == 1) && ($creditType == 0)){

            $transType = '31';
          }
          else if(($wallet == 1) && ($creditType == 1)){

            $transType = '32';
          }
           
           else if(($wallet == 2) && ($creditType == 0)){

            $transType = '33';
          }
           else if(($wallet == 2) && ($creditType == 1)){

            $transType = '34';
          }
          else if(($wallet == 3) && ($creditType == 0)){

            $transType = '35';
          }
           else if(($wallet == 3) && ($creditType == 1)){

            $transType = '36';
          }



       
        $creditWalletInfo = $this->wallets[$wallet];

        if(empty($creditWalletInfo))
           return $this->sendMessage("error","Transferring amount failed");  

        if( ($creditType == 0 && $creditWalletInfo['is_credit_enabled'] == 0) || ($creditType == 1 && $creditWalletInfo['is_debit_enabled'] == 0))
          return $this->sendMessage("error",'Transferring amount failed');  

        if(!empty($creditType)){

           $checkWallet = $creditWalletInfo['table_column_name'];
          
            $valid = $this->walletClass->checkBalance($checkWallet, $id, $transAmt);
          
           
           if(empty($valid)){
              return $this->sendMessage("error",Root::t('user','balance_err'));   
              exit;
           }

        }

        $username   =  $this->mdl->getemail($id);

        
       $transfer   =  $this->walletClass->updateWallet($id, $creditType, $transType, $transAmt,$wallet,$transId = 0, $doneBy =$this->admin,$force=0,$remarks);
        
        
        if($transfer){
        
            $type     = empty($creditType) ? 'Credited' : 'Debited' ;
            $activity = $type." amount $transAmt to ".$creditWalletInfo['label']." (User : $username)";
    
            $this->mdl->adminActivityLog($activity);
        
            $this->sendMessage('success',"Amount $type Successfully");
           return false;
        }else
           return $this->sendMessage('error',"Something went wrong..");
    }


     public function actionRefreshWallet(){

        $uid         = $this->cleanMe(Router::post('id'));

        $wallet      = $this->mdl->callsql("SELECT * FROM customer_wallet WHERE user_id='$uid'",'row');  

        $response    = '';

        foreach ($this->wallets as $key => $value) {

            if($value['is_hidden'] == 1)
                continue;

            $response.= '<div class="row col-12">
                            <div class="col-5" style="float:left;"><label>'.$value['label'].'</label></div>
                            <div class="" style="float:left;">-</div>
                            <div class="col-5" style="float:left;"><label>'.number_format($wallet[$value['table_column_name']],$value['decimal_limit']).'</label></div>
                        </div>';
        }

        return $this->renderJSON($response);
    }
    public function actionGallery() {     

        $this->checkPageAccess(13);
        $this->subTitle       = 'Customer Gallery'; 
        $filter               = $this->getInputs();
        if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        } 
        $data                 = $this->mdl->getCustomerGalleryList($filter);
        $roomList             = $this->purchasemdl->getRoomList();

        $onclick              = "onclick=pageHistory('***')";
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['data']       = $data;
        return $this->render('customer/gallery_list',['data'=>$filter,'roomList'=>$roomList]);
    }
    public function actionGetimage(){

        $id        = $this->cleanMe(Router::post('gallery'));

        $image_info   = $this->mdl->getImages($id); 

        $response['imagename'] = $image_info['image'];
      
        return $this->renderJson($response);
    }
    public function actionCreate() {

        $this->checkPageAccess(41);

        $this->subTitle  = 'Add Gallery';

        if(isset($_GET['id'])){

           $gallery = $this->mdl->getImages($_GET['id']);

          


           //$gallery['s_username'] = $this->mdl->getemail(  $gallery['user_id']);

           
           $this->subTitle  = 'Edit Gallery';
        }

        $customerList = $this->mdl->getCustomersusernameList();
        $roomList = $this->purchasemdl->getRoomList();



        return $this->render('customer/gallery_create',['gallery'=>$gallery,'customerList'=>$customerList,'roomList'=>$roomList]);
    }

    public function actionAdd() {
         $this->checkPageAccess(41);
       
        $user_id      = cleanMe($_POST['user_id']);
        $remarks      = cleanMe($_POST['remarks']);
        $room_id      = cleanMe($_POST['room_id']);
        $editID       = cleanMe($_POST['editID']);
        $status       = cleanMe($_POST['status']); 
        $maxsize      = 8388608; 



        if(empty($user_id) && empty($room_id)){
           return $this->sendMessage("error","Select User or room to Proceed");
        }
        

        
        if(empty($remarks)){
           return $this->sendMessage("error","Enter Remarks to Proceed");
        }
        if(empty($_FILES) && empty($editID)){
           return $this->sendMessage("error","Upload file to Proceed");
        } 
      
        if(!empty($editID)){

           $prevData     = $this->mdl->getImages($editID);
           $gallery = $prevData['image'];
         

           if(empty($gallery) && empty($_FILES))
               return $this->sendMessage("error","Upload image to Proceed");
        }

      $files =  $_FILES['filename'];
      // print_r($files);exit;
      if(isset($files)){

              $acceptable = array('image/jpeg','image/jpg','image/png','image/svg+xml');

              if((!in_array($files['type'], $acceptable)) && (!empty($files["type"])))
                  return $this->sendMessage("error","Invalid File Only PDF /JPEG/SVG type accepted"); 

              if($files['size']==0)
                  return $this->sendMessage("error","Invalid File Only PDF /JPEG/SVG type accepted");

              if($files['size']>$maxsize)
                  return $this->sendMessage("error","File size exceeds maximum limit 8 MB");  

              $filename    = $files['name']; 
              $temp_name   = $files['tmp_name'];
              $path_parts  = pathinfo($filename);
              $extension   = $path_parts['extension'];
              $newFile_org = 'gallery'.time().'.'.$extension;
              $target_file = FILEUPLOADPATH.'gallery/'.$newFile_org;

              if(!move_uploaded_file ($temp_name, $target_file)){
                   
                  return $this->sendMessage("error",'File Upload Failed');
              }

              if(!empty($editID) && !empty($gallery) && (!empty($_FILES))){

                  $Deletefile = FILEUPLOADPATH."gallery/".$gallery; 
           
                  if (file_exists($Deletefile))
                      unlink($Deletefile); 
              }

             
            
          }

          if(!empty($gallery) && !empty($editID) && empty($_FILES))
          {
            $newFile_org = $gallery;
          }
          


        if(!empty($editID)){
           $success = $this->mdl->update_gallery($_POST,$newFile_org);
           $msg     = 'Details Updated Successfully';
        }else{

           $success = $this->mdl->addgallery($_POST,$newFile_org);
           $msg     = 'New Image Added';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }
     public function actionAddCustomerAlcohol() {
        
        $this->checkPageAccess(25);
        $filter               = $this->getCustomerAlcoholeInputs();
         if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        }
        $filter['inventory'] = (new Customer)->getInventoryList();


        return $this->render('customer/create_customer_alcohol',$filter);
    }



    public function actionAddInv() {
        
        $this->checkPageAccess(23);
        $ip['user_id']        = $_POST['user_id'];
        $ip['name']           = $_POST['name'];
        $ip['volume']         = $_POST['volume'];
        //$ip['quantity']       = $_POST['quantity'];
        $ip['expiry']         = $_POST['expiry'];
        $ip['balance']        = $_POST['balance'];
        if(empty($ip['user_id']))
                return $this->sendMessage('error',"Please Select User To Proceed");
        for($i=0;$i<count($ip['name']);$i++){
            if(empty($ip['name'][$i]))
                return $this->sendMessage('error',"Please Enter Name To Proceed");
            if(empty($ip['volume'][$i]))
                return $this->sendMessage('error',"Please Enter Volume To Proceed");
            /*if(empty($ip['quantity'][$i]))
                return $this->sendMessage('error',"Please Enter Quantity To Proceed");*/
            if(empty($ip['expiry'][$i]))
                return $this->sendMessage('error',"Please Enter Expiry Date To Proceed");
            if(empty($ip['balance'][$i]))
                return $this->sendMessage('error',"Please Enter Balance To Proceed");
        }
        $id = $this->mdl->addCustomerAlcohol($ip);
        if(!empty($id)){
             $this->sendMessage('success',"Added Successfufly");
        }
        else{
            return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
        
    }
            
    public function actionUpdateCustomerAlcohol(){
     $this->checkPageAccess(24);
      $alcohol = [];
      $id = $_GET['id'];
      $alcohol['inventory'] = (new Customer)->getInventoryList();
      $alcohol['data'] = $this->mdl->getCustomerAlcoholById($id);

    return $this->render('customer/edit_customer_alcohol',$alcohol);
    }
    public function actionAlcoholUpdateCustomer() {
        
        $this->checkPageAccess(24);
        // $ip['user_id']        = $_POST['user_id'];
        $ip['name']           = $_POST['name'];
        $ip['volume']         = $_POST['volume'];
       // $ip['quantity']       = $_POST['quantity'];
        $ip['expiry']         = $_POST['expiry'];
        $ip['balance']        = $_POST['balance'];
        $ip['edit_id']        = $_POST['edit_id'];
        
            if(empty($ip['name']))
                return $this->sendMessage('error',"Please Enter Name To Proceed");
            if(empty($ip['volume']))
                return $this->sendMessage('error',"Please Enter Volume To Proceed");
            /*if(empty($ip['quantity']))
                return $this->sendMessage('error',"Please Enter Quantity To Proceed");*/
            if(empty($ip['expiry']))
                return $this->sendMessage('error',"Please Enter Expiry Date To Proceed");
            if(empty($ip['balance']))
                return $this->sendMessage('error',"Please Enter Balance To Proceed");
        
        $id = $this->mdl->UpdateAlcoholCustomer($ip);
        if(!empty($id)){
             $this->sendMessage('success',"Updated Successfufly");
        }
        else{
            return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
        
    }
     public function actionCustDelete(){
       $this->checkPageAccess(25);
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteCustomerAlcohol($ID);

        if($delete){
            return $this->sendMessage('success',"Customer Alcohol Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }


       public function actionDelete(){
          $this->checkPageAccess(43);

        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deletegallery($ID);

        if($delete){
            return $this->sendMessage('success',"Gallery Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }

       public function checkExists($key,$checkData){  
    
            $this->mdl->query("SELECT * FROM customer WHERE $key='$checkData'");

            $res=$this->mdl->single(); 
            if(!empty($res)>0){
            $msg = Root::t('user','E17',array('key'=>$key));
            $this->sendMessage("error",$msg);
            die();
            }
    }
    public function checkExistsMobile($mobile,$mobilecode){  
    
            $check = $this->mdl->callsql("SELECT COUNT(id) FROM customer_extra WHERE mobile='$mobile' AND mobile_code='$mobilecode' ","value");

            return $check;
    }
    // public function checkExistsReferal($referal) {

    //     $refer = $this->mdl->callsql("SELECT COUNT(id) FROM customer WHERE  username = '$referal' ","value");
        
    //     return $refer;
    // }
    

    public function actionAddCustomer() {

      //  $this->checkPageAccess(31);

        
        $user_id   = $this->cleanMe(Router::post('user_id'));
        $uniqueid   = $this->cleanMe(Router::post('uniqueid'));
        $name          = $this->cleanMe(Router::post('name'));
        $referal          = $this->cleanMe(Router::post('referal'));
        $email          = $this->cleanMe(Router::post('email')); 
        $countrycode           = $this->cleanMe(Router::post('countrycode')); 
        $surname         = $this->cleanMe(Router::post('surname')); 
        $gname          = $this->cleanMe(Router::post('gname'));
        $nickname       = $this->cleanMe(Router::post('nickname'));
        $mobilecode       = $this->cleanMe(Router::post('mobilecode'));
        $mobile        = $this->cleanMe(Router::post('mobile'));
        $gender       = $this->cleanMe(Router::post('gender'));
        $date1         = $this->cleanMe(Router::post('date1'));
        $language         = $this->cleanMe(Router::post('language'));
        $allergies       = $this->cleanMe(Router::post('allergies'));
        $assname        = $this->cleanMe(Router::post('assname'));
        $assmobile       = $this->cleanMe(Router::post('assmobile'));
        $assemail         = $this->cleanMe(Router::post('assemail'));
        $accman          = $this->cleanMe(Router::post('accman'));
        $remarks         = $this->cleanMe(Router::post('remarks'));


        
        // $this->usernameCheck($username,'username');
        $this->checkExists('username',$name);

        $count=$this->checkExistsMobile($mobile,$mobilecode);

        if(!empty($count)){
        return $this->sendMessage('error',"This Mobile Number Already Exist");
        }
        // $counts=$this->checkExistsReferal($referal);

        // if($counts==false){
          
        // return $this->sendMessage('error',"This is not a valid referral id");
        // }
        

        if(!empty($email)){
        $this->emailvalidate($email,'Email');

        }
        if(!empty($mobile)){
        $this->validate_mobile($mobile);

        }
        if(!empty($assmobile)){
        $this->validate_mobile($assmobile,'Assistant Mobile');

        }
        if(!empty($assemail)){
        $this->emailvalidate($assemail,'Assistant Email');

        }

        $ip            = [];

        if(empty($name))
            return $this->sendMessage('error',"Please Enter Username To Proceed");

        // if(empty($email))
        //     return $this->sendMessage('error',"Please Enter Email To Proceed");

        if(empty($referal))
            return $this->sendMessage('error',"Please Enter Referral Name To Proceed");

        if(empty($countrycode))
            return $this->sendMessage('error',"Please Enter Country To Proceed");

        if(empty($surname))
            return $this->sendMessage('error',"Please Enter Surname To Proceed");
        if(empty($gname))
            return $this->sendMessage('error',"Please Enter Given Name To Proceed");
        if(empty($nickname))
            return $this->sendMessage('error',"Please Enter Nickname To Proceed");
        if(empty($mobilecode))
            return $this->sendMessage('error',"Please Enter Mobilecode To Proceed");
        
        if(empty($mobile))
            return $this->sendMessage('error',"Please Enter Mobile To Proceed");
       
        if(empty($language))
            return $this->sendMessage('error',"Please Enter Language To Proceed");

        if(empty($accman))
            return $this->sendMessage('error',"Please Enter Account Manager To Proceed");
        
        // if(empty($allergies))
        //     return $this->sendMessage('error',"Please Enter Allergies To Proceed");

            
            // $ip['user_id']        = $_POST['user_id'];
            // $ip['uniqueid']        = $_POST['uniqueid']; 
            
            $ip['username']       = $name;
            $ip['referral']       = $referal;
            $ip['email']          = $email;
            $ip['countrycode']    = $countrycode;
            $ip['surname']        = $surname;
            $ip['given_name']     = $gname;
            $ip['nickname']       = $nickname;
            $ip['mobilecode']     = $mobilecode;
            $ip['mobile_country_code']     = $mobilecode;
            $ip['mobile']         = $mobile;
            $ip['gender']         = $gender;
            $ip['dob']          = $date1;
            $ip['language']       = $language;
            $ip['allergies']      = $allergies;
            $ip['assistant_name']        = $assname;
            $ip['assistant_mobile']      = $assmobile;
            $ip['assistant_email']       = $assemail;
            $ip['account_manager']       = $accman;
            $ip['remarks']        = $remarks;
          
            $success = $this->mdl->addCreateCustomer($ip);
            $msg     = 'User Added Successfully';
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else{
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
    }
    public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename = 'Customer List'; 

        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $user  = $this->cleanMe(Router::post('user_id'));
        $surname = $this->cleanMe(Router::post('surname')); 
        $gname   = $this->cleanMe(Router::post('gname'));
        $nickname  = $this->cleanMe(Router::post('nickname'));
        $mob = $this->cleanMe(Router::post('mob')); 
        $dob   = $this->cleanMe(Router::post('dob'));
        $assname  = $this->cleanMe(Router::post('assname'));
        $accman  = $this->cleanMe(Router::post('accman'));
        $referal  = $this->cleanMe(Router::post('referal'));
        $status  = $this->cleanMe(Router::post('status'));

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter = $this->getInputs();
        $filter['export'] = "export";

        $data = $this->mdl->getCustomerList($filter);

        $csv = "User ID, User name,  Surname ,Given Name, Nickname, Mobile ,Email Id ,Date Of Birth ,Nationality ,Language ,Allergies ,Assistant Name, Assistant Phone ,Assistant Email,Account Manager ,Referral , Remarks , Status ,Join Time   \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";
        foreach ($data['data'] as $his) {  

            $html.= $his['uniqueid'].','.$his['username'].','.$his['surname'].','.$his['given_name'].','.$his['nickname'].','.$his['mobile'].','.$his['email'].','.$his['dob'].','.$his['country'].','.$his['language'].','.$his['allergies'].','.$his['assistant_name'].','.$his['assistant_mobile'].','.$his['assistant_email'].','.$his['account_manager'].','.$his['referral'].',"'.$his['remarks'].'",'.$his['accStatus'].','.$his['time']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename;
        $log_data = array(
            "user" => $user,
            "datefrom" => $datefrom,
            "dateto" => $dateto,
            "export" => $filename." history"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        $this->mdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }
    
    public function actionExportCustomerAlcohol() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename = 'Customer Alcohol List'; 
        $input=[];
        $input['user_id']   = $this->cleanMe(Router::post('user_id')); 
        $input['alcohol']   = $this->cleanMe(Router::post('alcohol'));
        $input['volume']    = $this->cleanMe(Router::post('volume'));
        $datefrom  = !empty($this->cleanMe(Router::post('datefrom'))) ? $this->cleanMe(Router::post('datefrom')) : "";
        $dateto    = !empty($this->cleanMe(Router::post('dateto'))) ? $this->cleanMe(Router::post('dateto')) : "";
      
        $input['datefrom']   = $this->cleanMe(Router::post('datefrom'));
        $input['dateto']     = $this->cleanMe(Router::post('dateto'));
        if(!empty($datefrom)){
        $input['expiryfrom'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('datefrom'))));
        }
        if(!empty($dateto)){
        $input['expiryto'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('dateto'))));
        }

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter = $input;
        $filter['export'] = "export";

        $data = $this->mdl->getCustomerAlcohol($filter);

        $csv = "User Name, User Id, Alcohol , Volume, Expiry Date,Balance  \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";
        foreach ($data['data'] as $his) {  

            $html.= $his['name'].','.$his['uniqueid'].','.html_entity_decode(html_entity_decode($his['item'])).','.$his['volume_percent'].','.$his['exp_date'].','.$his['balance']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename;
        $log_data = array(
            "user" => $user,
            "datefrom" => $datefrom,
            "dateto" => $dateto,
            "export" => $filename." history"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        $this->mdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }

    public function actionCustomerProfileRequestbkp(){

        $this->checkPageAccess(51);
        $this->subTitle       = 'Customer Profile Requests'; 
        $filter               = $this->getProfileRequestInputs();
        
        $data                 = $this->mdl->getCustomerProfileRequests($filter);

        //echo "<pre>";print_r($data);echo "</pre>";
        $onclick              = "onclick=pageHistory('".$filter['status']."','".$filter['uniqueid']."','".$filter['requested_by']."','".$filter['email']."','".$filter['mobile_no']."','***')";

      
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        $filter['data']       = $data;
        
        return $this->render('customer/profile_requests',$filter);
    }

    public function actionCustomerProfileRequest(){

        $this->checkPageAccess(51);
        $this->subTitle       = 'Customer Profile Requests'; 
        $filter               = $this->getProfileRequestInputs();
        
        $data                 = $this->mdl->getCustomerProfileRequests($filter);

        //echo "<pre>";print_r($data);echo "</pre>";

        if(!empty($filter['customer_id'])) {

             $filter['s_username']    = $this->mdl->getemail($filter['customer_id']);    

        }
        $onclick              = "onclick=pageHistory('".$filter['status']."','".$filter['customer_id']."','".$filter['type']."','***')";

      
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        $filter['data']       = $data;
        
        return $this->render('customer/profile_requests',$filter);
    }

    public function getProfileRequestInputs(){
        $input = [];
        $input['status']        = !empty($_POST['status']) ? $this->cleanMe(Router::post('status')) : '';
        $input['customer_id']      = !empty($_POST['user_id']) ? $this->cleanMe(Router::post('user_id')) : '';
        $input['user_id']      = !empty($_POST['user_id']) ? $this->cleanMe(Router::post('user_id')) : '';
        $input['type']      = !empty($_POST['type']) ? $this->cleanMe(Router::post('type')) : '';
        //$input['requested_by']  = !empty($_POST['requested_by']) ? $this->cleanMe(Router::post('requested_by')) : '';
        //$input['email']         = !empty($_POST['email']) ? $this->cleanMe(Router::post('email')) : '';
        //$input['mobile_no']     = !empty($_POST['mobile_no']) ? $this->cleanMe(Router::post('mobile_no')) : '';
        //$input['createtime']     = !empty($_POST['createtime']) ? $this->cleanMe(Router::post('createtime')) : '';
        
        $input['page']          = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']          = empty($input['page']) ? 0 : 1 ;

        return $input;

    }

    public function actiongetCustomerDetails(){
        $id        = !empty($_POST['id']) ? $this->cleanMe(Router::post('id')) : '';
        $data       = $this->mdl->getCustomerUpdateRequestDetails($id);
        
        $response   = '';
        // $responsebkp.= ' 
        //             <div class="modal-body" id="verificationModalContent">

        //             <div class="row">
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="full_name">Customer ID :</label>
        //                 <span id="full_name">'.$data['info']['uniqueid'].'</span>
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="type">First Name:</label>
        //                 <span id="type">'.$data['extra']['given_name'].'</span>
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="industry">Second Name:</label>
        //                 <span id="industry">'.$data['extra']['surname'].'</span>
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="company_name">Email:</label>
        //                 <span id="company_name">'.$data['info']['email'].'</span>
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="registeration_number">Mobile Number:</label>
        //                 <span id="registeration_number">'.$data['extra']['mobile'].'</span>
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
                        
        //             </div>
        //             <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <label for="registeration_number">Remarks:</label>
        //             </div>
        //              <div class="col-6 col-sm-6 col-md-6 col-lg-6 form-group">
        //                 <textarea class="form-control" rows="3" cols="29" id="remarks" >'.$data['req_data']['remarks'].'</textarea>
        //             </div>

        //         </div>

        //         <div class="modal-footer">
        //             <button type="button" class="btn btn-success" onclick="approveRequest('.$data['req_data']['id'].')">Approve</button> 
        //             <button type="button" class="btn btn-danger" onclick="rejectRequest('.$data['req_data']['id'].')">Reject</button>
        //         </div>
        //         ';
        if($data['status']=='0'){
        $remark_data = '<tr class="border-0">
                    <td><label for="registeration_number">Remarks</label></td>
                    <td><textarea class="form-control" rows="3" cols="29" id="remarks">' . $data['remarks'] . '</textarea></td>
                    </tr>';
        }else{
            $remark_data = '<tr class="border-0">
                            <td><label for="registeration_number">Remarks</label></td>
                            <td><span id="remarks">' . $data['remarks'] . '</span></td>
                        </tr>';
        }
       $response .= '
                    <div class="" id="verificationModalContent">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label for="customer_id">ID</label></td>
                                    <td><span id="customer_id">' . $data['uniqueid'] . '</span></td>
                                </tr>
                                <tr>
                                    <td><label for="full_name">Name</label></td>
                                    <td><span id="full_name">' . $data['given_name'] . '</span></td>
                                </tr>
                                <tr>
                                    <td><label for="company_name">Update Type</label></td>
                                    <td><span id="company_name">' . $data['type'] . '</span></td>
                                </tr>
                                <tr>
                                    <td><label for="registeration_number">' . $data['old_label'] . '</label></td>
                                    <td><span id="registeration_number">' . $data['old_value'] . '</span></td>
                                </tr>
                                <tr>
                                    <td><label for="registeration_number">' . $data['new_label'] . '</label></td>
                                    <td><span id="registeration_number">' . $data['new_value'] . '</span></td>
                                </tr>
                                '.$remark_data.'
                            </tbody>
                        </table>
                    </div>';

                if($data['status']=='0'){
                $response .='<div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="approveRequest('.$data['id'].')">Approve</button> 
                    <button type="button" class="btn btn-danger" onclick="rejectRequest('.$data['id'].')">Reject</button>
                </div>
                ';
                }

        echo $response;


    }



    public function actionApproveCustomerProfilebkp(){
        $data['cid']        = !empty($_POST['cid']) ? $this->cleanMe(Router::post('cid')) : '';
        $data['remarks']    = !empty($_POST['remarks']) ? $this->cleanMe(Router::post('remarks')) : '';
        $update       = $this->mdl->approveCustomerRequest($data);
        $action     = 'Approved Customer Profile Request.';
        if($update){
            $activity= $action.' Request ID : '.$data['cid'];
            $this->mdl->adminActivityLog($activity);
            $this->sendMessage('success', $action);
        }


    }

    public function actionRejectCustomerProfilebkp(){
        $data['cid']        = !empty($_POST['cid']) ? $this->cleanMe(Router::post('cid')) : '';
        $data['remarks']    = !empty($_POST['remarks']) ? $this->cleanMe(Router::post('remarks')) : '';

        $update       = $this->mdl->rejectCustomerRequest($data);
        $action     = 'Rejected Customer Profile Request.';

        if($update){
            $activity= $action.' Request ID : '.$data['cid'];
            $this->mdl->adminActivityLog($activity);

            $this->sendMessage('success', $action);
        }


    }

    public function actionApproveCustomerProfile(){

        $data['cid']        = !empty($_POST['cid']) ? $this->cleanMe(Router::post('cid')) : '';
        $data['remarks']    = !empty($_POST['remarks']) ? $this->cleanMe(Router::post('remarks')) : '';

        if(empty($data['cid'])) {

            $this->sendMessage('error',"Please select a request to proceed");
            die();

        }

        if(empty($data['remarks'])) {

            $this->sendMessage('error',"Please enter remark to proceed");
            die();

        }
        

        $details = $this->mdl->getRequestDetails($data['cid']);

        if($details['status']!='0') {

            $this->sendMessage('error',"Already Changed the request status");
            die();

        }
        $json_decode = json_decode($details['request'],true);
        if($details['type'] == '1') { //email updation

            $params = [];
            $params['email'] = $json_decode['email'];
            if($this->mdl->checkEmailUsed($params)){

                $this->sendMessage('error',"Already used");
                die();

            }

        }
        if($details['type'] == '2') { //mobile updation

            $params = [];
            $params['mobile'] = $json_decode['mobile_country_code'].$json_decode['mobile'];
            if($this->mdl->checkMobileUsed($params)){

                $this->sendMessage('error',"Already used");
                die();

            }

        }

        if($details['type'] == '6') { //username updation

            $params = [];
            $params['username'] = $json_decode['username'];
            if($this->mdl->checkUsernameUsed($params)){

                $this->sendMessage('error',"Already used");
                die();

            }

        }

        $params = [];
        $params['id'] = $data['cid'];
        $params['remarks'] = $data['remarks'];
        if($this->mdl->approveRequest($params)){

            $this->sendMessage('success',"Successfully Approved the request");
            die();

        }else{

            $this->sendMessage('error',"Failed to approve the request");
            die();

        }

        


    }


    public function actionRejectCustomerProfile(){
        $data['cid']        = !empty($_POST['cid']) ? $this->cleanMe(Router::post('cid')) : '';
        $data['remarks']    = !empty($_POST['remarks']) ? $this->cleanMe(Router::post('remarks')) : '';

        if(empty($data['cid'])) {

            $this->sendMessage('error',"Please select a request to proceed");
            die();

        }

        if(empty($data['remarks'])) {

            $this->sendMessage('error',"Please enter remark to proceed");
            die();

        }

        $details = $this->mdl->getRequestDetails($data['cid']);

        if($details['status']!='0') {

            $this->sendMessage('error',"Already Changed the request status");
            die();

        }

        $params = [];
        $params['id'] = $data['cid'];
        $params['remarks'] = $data['remarks'];
        if($this->mdl->rejectRequest($params)){

            $this->sendMessage('success',"Successfully Rejected the request");
            die();

        }else{

            $this->sendMessage('error',"Failed to reject the request");
            die();

        }
        

    }


    public function getUpdateAlcoholeInputs(){
        $input  = [];
        
        $input['customer_id']    = $this->cleanMe(Router::post('customer_id')); 
        $input['alcohol']    = $this->cleanMe(Router::post('alcohol'));
        $input['volume']     = $this->cleanMe(Router::post('volume'));
        if(!empty($datefrom)){
            $input['expiryfrom'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('datefrom'))));
        }
        if(!empty($dateto)){
            $input['expiryto'] = date("Y-m-d",strtotime($this->cleanMe(Router::post('dateto'))));
        }
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;

        return $input;
    }


    public function actionUpdateAlcoholList() {     

        $this->checkPageAccess(14);

        $this->subTitle     = 'Customer Alcohol List'; 

        $filter             = $this->getUpdateAlcoholeInputs();
        $filter['search']   = $this->cleanMe(Router::post('Search')); 
        
        $data               = $this->mdl->getUpdateAlcoholList($filter);

        if(!empty($filter['customer_id'])){
            $filter['s_username']    = $this->mdl->getUsername($filter['customer_id']);
        }

        $sub = (empty($filter['load'])) ? json_encode($filter['sub']) : $filter['sub'];
        $onclick                     = "onclick=pageHistory('".$filter['user_id']."','".$filter['volume']."','".$filter['alcohol']."','".$filter['datefrom']."','".$filter['dateto']."','***')";
        $filter['pagination']        = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['sub']               = (empty($filter['load'])) ? json_encode($filter['sub']) : $filter['sub'];
        $filter['data']              = $data;

        return $this->render('customer/update_alcohol',$filter);
    }

    public function actionUpdateAlcoholEdit(){
        $this->checkPageAccess(24);
        $alcohol    = [];
        $id         = $_GET['id'];

        $alcohol['inventory']   = (new Customer)->getInventoryList();
        $alcohol['data']        = $this->mdl->getPurchaseAlcoholById($id);

        return $this->render('customer/edit_update_alcohol',$alcohol);
    }


    public function actionUpdateAlcohol() {
        $this->checkPageAccess(24);

        $params['volume']   = $this->cleanMe(Router::post('volume')); 
        $params['edit_id']  = $this->cleanMe(Router::post('edit_id'));
        $params['expiry_date'] = $this->cleanMe(Router::post('expiry'));

        if($params['volume']==''){
            return $this->sendMessage('error',"Please Enter Volume To Proceed");
        }
        
        if(empty($params['edit_id']))
        {
            return $this->sendMessage('error',"Please Enter id To Proceed");
        }
        if(empty($params['expiry_date']))
        {
            return $this->sendMessage('error',"Please Enter Expiry To Proceed");
        }

        $check_volume = $this->mdl->checkPurchaseDetailsById($params['edit_id']);

        // if ($params['volume'] == $check_volume['volume'])
        // {
        //     return $this->sendMessage('error',"Volume Already In ".$params['volume']);
        // }
        if ($params['volume'] > $check_volume['volume'])
        {
            return $this->sendMessage('error',"Please Enter Valid Volume To Proceed");
        }

        $id = $this->mdl->UpdateAlcoholPercentage($params);
        if(!empty($id)){
             $this->sendMessage('success',"Updated Successfufly");
        }
        else{
            return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
        
    }
     public function actionUpdateAlcoholDelete(){
       $this->checkPageAccess(25);
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteUpdateAlcohol($ID);

        if($delete){
            return $this->sendMessage('success',"Customer Alcohol Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }






}

