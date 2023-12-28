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
use src\models\Alcohol;
use src\models\Room;
use src\models\LeaveRequest;
use src\models\Departments;
use src\models\Positions;

class UserController extends Controller {

    public function __construct(){

        parent::__construct();
        

    
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Customer);
        $this->usermdl       = (new User);
        $this->walletClass   = (new walletClass);
        $this->roommdl       = (new Room);
        $this->leaveRequestmdl = (new LeaveRequest);
        $this->departmentsmdl  = (new Departments);
        $this->positionmdl     = (new Positions);
        $this->pag           =  new Pagination(new User(),''); 
        $this->getArray      = (new commonArrays)->getArrays();
        
        $this->userArr       = $this->systemArrays['userStatusArr'];
        $this->ImgArr        = [0=>"Active",1=>"Hide"];
        $this->wallets       = $this->systemArrays['wallets'];
        
        $this->roleArr       = $this->getArray['roleArr'];

        $this->mainTitle    = 'User Management';

        $this->tabs         = [''];


    }

    public function getInputs()
    {
        $input = [];
       
        $input['status']     = $this->cleanMe(Router::post('status')); 
        $input['username']   = $this->cleanMe(Router::post('username'));
        $input['first_name'] = $this->cleanMe(Router::post('first_name'));
        $input['nick_name']  = $this->cleanMe(Router::post('nick_name'));
        $input['staff_id']   = $this->cleanMe(Router::post('staff_id'));
        $input['position']   = $this->cleanMe(Router::post('position'));
        $input['department'] = $this->cleanMe(Router::post('department'));
        $input['mobile']     = $this->cleanMe(Router::post('mob'));
        
        $input['passport_number']   = $this->cleanMe(Router::post('passport_number'));
        $input['dob']   = $this->cleanMe(Router::post('dob'));
     
        $input['user_id']     = $this->cleanMe(Router::post('user_id')); 
        $input['uniqueid']     = $this->cleanMe(Router::post('uniqueid')); 
        $input['role']     = $this->cleanMe(Router::post('role')); 
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;



        return $input;
    }

    public function actionIndex() {   


        $this->checkPageAccess(9);
        $this->subTitle       = 'User List'; 
        $filter               = $this->getInputs();



        if( ! empty($filter['user_id'])){
             $filter['s_username']    = $this->usermdl->getUsername($filter['user_id']);

        }

        
        $data                 = $this->usermdl->getUserList($filter);
        $data['positions']    = $this->positionmdl->getActivePositions();
        $data['departments']  = $this->departmentsmdl->getActiveDepartments();
        //$filter['lang']             = $data['language'];
        $onclick              = "onclick=pageHistory('".$filter['status']."','".$filter['user_id']."','".$filter['username']."','".$filter['first_name']."','".$filter['mobile']."','".$filter['dob']."','".$filter['nick_name']."','".$filter['staff_id']."','".$filter['position']."','".$filter['department']."','".$filter['passport_number']."','".$filter['uniqueid']."','".$filter['role']."','***')";

        
      
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        
        $filter['data']       = $data;



        return $this->render('user/index',$filter);
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

            $user= $this->usermdl->getcustomerdetails($value);

            $update =$this->usermdl->updateCustomerStatus($status,$value);
            
            if($update){
              $activity= $action.' :'.$user['extra']['given_name'];
              $this->mdl->adminActivityLog($activity);

              $this->sendMessage('success', $action);
            }
    }

     public function actionGetUsers(){
         //$this->checkPageAccess(11);
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $userlist = $this->usermdl->searchUsers($term);
            echo  $userList = json_encode($userlist);    
        }
    }


    public function actionCreateUser() {
        $this->subTitle     = 'Create User';
        $user_data = '';
        $user_id       = $this->cleanMe(Router::get('user_id'));
        $user_id = base64_decode($user_id);
        if(!empty($user_id)){
            $user_data            = $this->usermdl->getUserData($user_id);
        }
        $service            = $this->usermdl->getServicesList();
        $subService         = $this->usermdl->getSubServicesList();
        
        $positions          = $this->positionmdl->getActivePositions();
        $departments        = $this->departmentsmdl->getActiveDepartments();

        $roomArr = $this->roommdl->getRooms();

        $roleArr = $this->roleArr;
        $data['ServicesAr'] = $service; 
        $data['subService'] = $subService; 
        $data['roleArr']    = $roleArr; 
        $data['country']    = $this->mdl->getCountryCode(); 
        $data['lang']       = $this->mdl->getLanguage();
        $data['user_data']  = $user_data;
        $data['roomArr']    = $roomArr;
        
        $data['positions']    = $positions;
        $data['departments']  = $departments;
        return $this->render('user/create_user',$data); 

    }

    public function actionValidateUser(){
        $role_id        = $this->cleanMe(Router::post('role_id'));
        $user_id        = $this->cleanMe(Router::post('user_id'));
        $first_name     = $this->cleanMe(Router::post('first_name'));
        $last_name      = $this->cleanMe(Router::post('last_name'));
        $nick_name      = $this->cleanMe(Router::post('nick_name'));
        $username       = $this->cleanMe(Router::post('username'));
        $password       = $this->cleanMe(Router::post('password'));
        $conPwd         = $this->cleanMe(Router::post('ConfirmPassword'));
        $transpin       = $this->cleanMe(Router::post('transpin'));
        $mobileno       = $this->cleanMe(Router::post('mobileno'));
        $mobileno2      = $this->cleanMe(Router::post('mobileno2'));
        $passport_number  = $this->cleanMe(Router::post('passport_number'));
        $gender         = $this->cleanMe(Router::post('gender'));
        $dob            = $this->cleanMe(Router::post('date1'));
        $nationality    = $this->cleanMe(Router::post('countrycode'));
        $email          = $this->cleanMe(Router::post('email'));
        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));

        $this->emptyCheck($role_id,'User Role');

        $this->emptyCheck($first_name,'Firstname');
        //$this->checkExists($user_id,'first_name',$first_name);

        $this->emptyCheck($last_name,'Lastname');
        //$this->checkExists($user_id, 'last_name',$last_name);

        $this->emptyCheck($nick_name,'Nickname');
        //$this->checkExists($user_id, 'nick_name',$nick_name);

        $this->usernameCheck($username,'Username');
        $this->checkExists($user_id, 'username',$username,'Username');
        $this->emptyCheck($passport_number,'NRIC/Passport No./FIN no');
        $this->checkExists($user_id, 'passport_number',$passport_number,'NRIC/Passport No./FIN no');
        if(empty($user_id)){
            $this->emptyCheck($password,'Password');
            $this->emptyCheck($conPwd,'Confirm Password');
            //validate password

            $this->PwdValidation($password,$conPwd,true);
            //$this->CheckPwd($password,$conPwd);
        }

        $this->emptyCheck($position,'Position');
        $this->emptyCheck($department,'Department');
        $this->emptyCheck($email,'Email');
        if(!empty($email)){
            $this->emailvalidate($email,'Email');
            $this->checkExists($user_id, 'email',$email,'Email'); 
        }

        $this->emptyCheck($mobileno,'Mobile Number');
        if(!empty($mobileno)){
            $this->validate_mobile($mobileno);
            $this->checkExists($user_id, 'mobileno',$mobileno,'Mobile Number'); 
            $this->checkExists($user_id, 'mobileno2',$mobileno,'Mobile Number'); 

        }

        // $this->emptyCheck($mobileno2,'Second Mobile Number');
        if(!empty($mobileno2)){
            $this->validate_mobile($mobileno2);
            $this->checkExists($user_id, 'mobileno2',$mobileno2,'Mobile Number'); 
            $this->checkExists($user_id, 'mobileno',$mobileno2,'Mobile Number'); 
        }
        if($mobileno2==$mobileno)
        {
            $this->sendMessage("error","Primary and secondary mobileno is same");
            die();
        }
        $this->emptyCheck($gender,'Gender');

        $this->emptyCheck($dob,'DOB');

        $this->emptyCheck($nationality,'Nationality');
        
        if(empty($transpin)){
            $this->emptyCheck($transpin,'Transaction Pin');
        }

        if(!empty($transpin)){
            $this->validate_transpin($transpin);
        }


        $msg = '';
        $response['message']    = $msg;
        return $this->sendMessage("success",$response);
        die();
    }


    public function actionAddUser(){
        //$this->checkPageAccess(4);
        $role_id        = $this->cleanMe(Router::post('role_id'));
        $user_id       = $this->cleanMe(Router::post('user_id'));
        $first_name       = $this->cleanMe(Router::post('first_name'));
        $last_name      = $this->cleanMe(Router::post('last_name'));
        $nick_name      = $this->cleanMe(Router::post('nick_name'));
        $username       = $this->cleanMe(Router::post('username'));
        $password       = $this->cleanMe(Router::post('password'));
        $conPwd         = $this->cleanMe(Router::post('ConfirmPassword'));
        $transpin       = $this->cleanMe(Router::post('transpin'));
        $mobileno       = $this->cleanMe(Router::post('mobileno'));
        $mobileno2      = $this->cleanMe(Router::post('mobileno2'));
        $passport_number= $this->cleanMe(Router::post('passport_number'));
        $gender         = $this->cleanMe(Router::post('gender'));
        $dob            = $this->cleanMe(Router::post('date1'));
        $nationality    = $this->cleanMe(Router::post('countrycode'));
        $email          = $this->cleanMe(Router::post('email'));
        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));
        $permission     = $_POST['permission'];

        foreach ($permission as $serviceVal) {
            $services[] =$serviceVal;
        }

        $permissionList = implode(', ', $services);

        $params=array(
            'user_id'       => $user_id,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nick_name'     => $nick_name,
            'role_id'       => $role_id,
            'username'      => $username,
            'password'      => ($password),
            'transpin'      => $transpin,
            'mobileno'      => $mobileno,
            'mobileno2'     => !empty($mobileno2)?$mobileno2:'',
            'gender'        => $gender,
            'passport_number'=> $passport_number,
            'dob'           => strtotime($dob),
            'nationality'   => $nationality,
            'email'         => $email,
            'position'      => $position,
            'department'    => $department,
            'services'      => $permissionList
        );

        if(empty($user_id)){
            $is_updated =$this->usermdl->createUser($params); 
            if ($is_updated === true) {
                $activity='Creation of new user :'.$username;
                $this->usermdl->adminActivityLog($activity);
                $msg = "User Created Successfully";
                $this->sendMessage("success",$msg);
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
        }
        else{
            $is_updated =$this->usermdl->updateUser($params); 
            if ($is_updated === true) {

                $msg = "User Updated Successfully";
                $this->sendMessage("success",$msg);

                     
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
        }
        

        return false;  
    }

    public function actionAddRoomTablet()
    {

        $role_id        = $this->cleanMe(Router::post('role_id'));
        $room_id        = $this->cleanMe(Router::post('room_id'));
        $password       = $this->cleanMe(Router::post('password'));
        $conPwd         = $this->cleanMe(Router::post('confirm_password'));
        $user_id         = $this->cleanMe(Router::post('user_id'));
        

        $this->emptyCheck($role_id,'User Role');
        $this->emptyCheck($room_id,'Room ');
        if(empty($user_id)) {

            $this->emptyCheck($password,'Password');
            $this->emptyCheck($conPwd,'Confirm Password');

            if(!empty($password) && !empty($conPwd)) {

                $this->passwordLengthCheck($password,'Password');
                if($password!=$conPwd) {

                    $msg = "Password and Confirm password should be same";
                    $this->sendMessage("error",$msg);
                    die();

                }

            }

        }
        

        $params = [];
        $params['room_id'] = $room_id;
        $params['id']      = $user_id;
        $alreadyAssigned = $this->usermdl->checkRoomAlreadyAssigned($params);
        if($alreadyAssigned) {

            $this->sendMessage("error",'The selected room is already assigned'); 
            die();

        }

        $roomdetails = $this->roommdl->getDetails($room_id);
        $username    = $roomdetails['room_no'];
        $permissions = $this->usermdl->getPermissionByRole($role_id);

        
        if(empty($user_id)) {

            $params = [];
            $params['username']    = $username;
            $params['room_id']     = $room_id;
            $params['permission']  = $permissions;
            $params['password']    = $password;
            $params['role_id']     = $role_id;
            $params['status']      = '0';
            if($this->usermdl->createRoomTablet($params)){

                $msg = "User Created Successfully";
                $this->sendMessage("success",$msg);
                die();

            }else{

                $msg = "Sorry failed to create user";
                $this->sendMessage("error",$msg);
                die();

            }

        }else{

            $params = [];
            $params['username']    = $username;
            $params['room_id']     = $room_id;
            $params['permission']  = $permissions;
            $params['role_id']     = $role_id;
            $params['status']      = '0';
            $params['user_id']     = $user_id;
            if($this->usermdl->UpdateRoomTablet($params)){

                $msg = "User Updated Successfully";
                $this->sendMessage("success",$msg);
                die();

            }else{

                $msg = "Sorry failed to update user";
                $this->sendMessage("error",$msg);
                die();

            }   

        }
        

        $msg = "Something went wrong";
        $this->sendMessage("error",$msg);




    }

    public function actiongetRolePermission(){
        $role_id       = $this->cleanMe(Router::post('role_id'));
        $permissions = $this->usermdl->getRolePermission($role_id);
        echo $permissionList = json_encode($permissions); 


    }

    public function emptyCheck($var,$key){
        if(empty($var)){
         $msg = Root::t('user','E01',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
        }
    }

    private function usernameCheck($var,$key){
       
        if(empty($var)){
          echo $this->sendMessage("error",Root::t('user','E01',array('key'=>$key))); exit;
        }
        if (count(explode(' ', $var)) > 1) {
          echo $this->sendMessage("error",Root::t('user','E31',array('key'=>$key))); exit;
        }
        if(strlen($var)<3){
           echo $this->sendMessage("error",Root::t('user','E33',array('key'=>$key))); exit;
        }

        if (preg_match('/^\d+$/',$var)) {
           echo $this->sendMessage("error",Root::t('user','E39',array('key'=>$key))); exit();
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

        if(strlen($mobile) != 8){
           echo $this->sendMessage("error",'Invalid Mobile Number'); exit;
        }
    }

    private function validate_transpin($transpin){
       
       if (!preg_match('/^[0-9]+$/', $transpin)) {
         echo $this->sendMessage("error",Root::t('subadmin','E092')); exit();
        }

        if(strlen($transpin) != 6){
           echo $this->sendMessage("error",'Transaction Pin must be 6 digits'); exit;
        }
    }

    public function CheckPwd($pwd,$conPwd){

        if($pwd != $conPwd){
          $msg=Root::t('subadmin','pwd_missmatch');
          $this->sendMessage("error",$msg);
          die();  
        }
    }

    public function checkExists($user_id, $key,$checkData,$text=''){
        $qry = '';
        if(!empty($user_id)){
            $qry = 'AND user_id!='.$user_id;
        }  
    
       $this->mdl->query("SELECT * FROM user WHERE $key='$checkData' $qry ");
       $res=$this->mdl->single(); 
       if(!empty($res)>0){
        
        if(!empty($text))
        {
            $key = $text;
        }

         $msg = Root::t('user','E17',array('key'=>$key));
         $this->sendMessage("error",$msg);
         die();
       }
    }

  public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename   = 'User List'; 
        $user_id    = $this->cleanMe(Router::post('user_id')); 
        $status     = $this->cleanMe(Router::post('status')); 
        $username   = $this->cleanMe(Router::post('username'));
        $first_name = $this->cleanMe(Router::post('first_name'));
        $nick_name  = $this->cleanMe(Router::post('nick_name'));
        $staff_id   = $this->cleanMe(Router::post('staff_id'));
        $position   = $this->cleanMe(Router::post('position'));
        $department = $this->cleanMe(Router::post('department'));
        $passport_number = $this->cleanMe(Router::post('passport_number'));
        $mobile     = $this->cleanMe(Router::post('mob'));
        $dob        = $this->cleanMe(Router::post('dob'));
        $gender     = $this->cleanMe(Router::post('gender'));
        $email      = $this->cleanMe(Router::post('email'));
        $role       = $this->cleanMe(Router::post('role'));
     
     
        //$user_id  = $this->cleanMe(Router::post('user_id')); 
   
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter =  $this->getInputs();
        

        $filter['export'] = "export";

        $data = $this->usermdl->getUserList($filter);

        
        $csv = "User name,Staff ID,Role,Position,Department,First Name ,Last Name,Nick Name, Mobile 1 , Mobile 2,NRIC/Passport No./FIN no,Date Of Birth ,Email,Gender,Nationality,status   \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        $genderArr = ['1'=>'Male','2'=>'Female'];
        $roleArry = $this->roleArr;
               
          

        foreach ($data['data'] as $his) { 

          

            $gender = !empty($his['gender']) ? $genderArr[$his['gender']] : '';
            $role   = $roleArry[$his['role_id']];

        
            $html.= $his['username'].','.$his['staff_id'].','.$role.','.$his['position'].','.$his['department'].','.$his['first_name'].','.$his['last_name'].','.$his['nick_name'].','.$his['mobileno'].','.$his['mobileno2'].','.$his['passport_number'].','.date('d-m-Y',$his['dob']).','.$his['email'].','.$gender.','.$his['nationality'].','.$his['account_status']."\n"; //Append data to csv

        }

        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename;
        $log_data = array(
            
            "export" => $filename." history"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        $this->usermdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }
    public function actionGetUser(){
        
        $userid   = $this->cleanMe($_POST['uid']);

        $userInfo = $this->usermdl->getUserInfo($userid);

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

    public function actionUpdateUser() {
        $this->subTitle     = 'Update User';
        $user_data = '';
        $user_id       = $this->cleanMe(Router::get('user_id'));
        $user_id = base64_decode($user_id);

        $active_tab       = $this->cleanMe(Router::get('active'));

        if(!empty($user_id)){
            $user_data            = $this->usermdl->getUserData($user_id);
        }
        $service            = $this->usermdl->getServicesLists($user_id);
        $subService         = $this->usermdl->getSubServicesList();
        $have_all_permision         = $this->usermdl->checkhaveallpermission($user_id); //check user have all permsions

        $positions          = $this->positionmdl->getActivePositions();
        $departments        = $this->departmentsmdl->getActiveDepartments();

        
        
        $roleArr = $this->roleArr;
        $roomArr = $this->roommdl->getRooms();
        $data['ServicesAr'] = $service; 
        $data['subService'] = $subService; 
        $data['roleArr']    = $roleArr; 

        $data['country']    = $this->mdl->getCountryCode(); 

        $data['lang']       = $this->mdl->getLanguage();

        $data['user_data']  = $user_data;
        $data['have_all_permision']  = $have_all_permision;
        $data['roomArr']  = $roomArr;
        
        $data['positions']   = $positions;
        $data['departments'] = $departments;

        //leave details
        $leaveTypes = $this->leaveRequestmdl->getLeaveDetails(['user_id'=>$user_id]);
        $data['leaveTypes']  = $leaveTypes;
        $data['user_id']      = $user_id;
        $data['activeTab']      = !empty($active_tab) ? $active_tab : '';


        return $this->render('user/edituser',$data); 

    }

    public function actionUpdateLeaveBalance()
    {
        
        $leaveBalance = $_POST['leave_balance'];
        $staff_id = $_POST['staff_id'];
        if(empty($staff_id)) {
            
             echo $this->sendMessage("error",'Please select a user to proceed');
             die();
        }

        if(in_array('',$leaveBalance)) {

            echo $this->sendMessage("error",'Please enter leave balance to proceed');
            die();

        }

        foreach($leaveBalance as $key=>$value){

           
            
            $details = $this->leaveRequestmdl->getLeaveTypeDetails($key);
            if($value<0) {

                $msg = "Negative values not allowed in ".$details['leave_name'];
                echo $this->sendMessage("error",$msg);
                die();

            }
            $allowed_count = !empty($details['allowed_count']) ? $details['allowed_count'] : '0';
            if($value > $allowed_count) {
                
                $msg = $details['leave_name']." maximum allowed count is ".$allowed_count.". Please recheck to proceed";
                echo $this->sendMessage("error",$msg);
                die();

            } 


        }
        
        $params = [];
        $params['leaveBalance'] = $leaveBalance;
        $params['staff_id']     = $staff_id;
        if($this->leaveRequestmdl->updateLeave($params)){

            $msg = "Successfully updated the leave balance";
            echo $this->sendMessage("success",$msg);
            die();


        }else{

            $msg = "Failed to update the leave balance";
            echo $this->sendMessage("error",$msg);
            die();


        }

        $msg = "Something went wrong";
        echo $this->sendMessage("success",$msg);
        die();



    }


     public function actionValidateUpdateUser(){
        $role_id        = $this->cleanMe(Router::post('role_id'));
        $user_id        = $this->cleanMe(Router::post('user_id'));

        $first_name       = $this->cleanMe(Router::post('first_name'));
        $last_name      = $this->cleanMe(Router::post('last_name'));
        $username       = $this->cleanMe(Router::post('username'));
        $nick_name      = $this->cleanMe(Router::post('nick_name'));
        $password       = $this->cleanMe(Router::post('password'));
        $conPwd         = $this->cleanMe(Router::post('ConfirmPassword'));
        $transpin       = $this->cleanMe(Router::post('transpin'));
        $mobileno       = $this->cleanMe(Router::post('mobileno'));
        $gender         = $this->cleanMe(Router::post('gender'));
        $dob            = $this->cleanMe(Router::post('date1'));
        $mobileno2      = $this->cleanMe(Router::post('mobileno2'));
        $passport_number  = $this->cleanMe(Router::post('passport_number'));
        $nationality    = $this->cleanMe(Router::post('countrycode'));
        $email    = $this->cleanMe(Router::post('email'));

        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));

        $this->emptyCheck($role_id,'User Role');

        $this->emptyCheck($first_name,'Firstname');
        
        $this->emptyCheck($last_name,'Lastname');
        $this->emptyCheck($nick_name,'Nickname');
       
        $this->usernameCheck($username,'Username');
        $this->checkExists($user_id, 'username',$username,'Username');

        $this->emptyCheck($position,'Position');
        $this->emptyCheck($department,'Department');

        $this->emptyCheck($passport_number,'NRIC/Passport No./FIN no');
        $this->checkExists($user_id, 'passport_number',$passport_number,'NRIC/Passport No./FIN no');


        $this->emptyCheck($email,'Email');
        if(!empty($email)){
            $this->emailvalidate($email,'Email');
            $this->checkExists($user_id, 'email',$email,'Email'); 
            
        }

        $this->emptyCheck($mobileno,'Mobile Number');
        if(!empty($mobileno)){
            $this->validate_mobile($mobileno);
            $this->checkExists($user_id, 'mobileno',$mobileno,'Mobile Number'); 
            $this->checkExists($user_id, 'mobileno2',$mobileno,'Mobile Number'); 
        }
        // $this->emptyCheck($mobileno2,'Mobile Number');
        if(!empty($mobileno2)){
            $this->validate_mobile($mobileno2);
            $this->checkExists($user_id, 'mobileno2',$mobileno2); 
            $this->checkExists($user_id, 'mobileno',$mobileno2); 
            
        }
        
        if($mobileno2==$mobileno)
        {
            $this->sendMessage("error","Primary and secondary mobileno is same");
            die();
        }

        $this->emptyCheck($gender,'Gender');

        $this->emptyCheck($dob,'DOB');

        $this->emptyCheck($nationality,'Nationality');
        
        

        $msg = '';
        $response['message']    = $msg;
        return $this->sendMessage("success",$response);
        die();
    }

 public function actionResetpass(){


          $n       = $this->cleanMe(Router::post('newpass'));
          $c       = $this->cleanMe(Router::post('confpass'));
          $uid     = $this->cleanMe(Router::post('editId'));
          if( (empty($n) || empty($c))){

          }

          $new     = md5($n);      $con  = md5($c);
          $t       = time();

          $username = $this->usermdl->getuserdetails($uid);

        

          if( (!empty($n) || !empty($c))){

                $this->PwdValidation($n,$c);

                if($new == $con ){

                   $update = $this->usermdl->UpdatePass($new,$uid);
                   if($update)
                   {
                     $activity='Updated Password of User : '.$username['info']['username'];
                     $this->usermdl->adminActivityLog($activity);
                     return $this->sendMessage("success",Root::t('user','pwd_update1')); 
                   } 
                }else{
                   return $this->sendMessage("error",'Something went wrong..');
                }

          }
    }


    public function actionResetpin(){

    
          $n       = $this->cleanMe(Router::post('newpin'));
          $c       = $this->cleanMe(Router::post('confpin'));
          $uid     = $this->cleanMe(Router::post('editId'));

          if( (empty($n) || empty($c))){

           }

          $new     = md5($n);      $con  = md5($c);
          $t       = time();
          $username1 = $this->usermdl->getuserdetails($uid);

          $username = $this->usermdl->getUsername($uid);
        
          if( (!empty($new) || !empty($con))){

            $validate = $this->PinValidation($n,$c);

            if($n == $c){
              
                $update = $this->usermdl->UpdatePin($new,$uid);
                   if($update)
                   {
                     $activity='Updated Transaction Pin of user :'.$username;
                     $this->usermdl->adminActivityLog($activity);
                     return $this->sendMessage("success",Root::t('user','pin_update_sucess_msg')); 
                   } 
                }
            else{
                   return $this->sendMessage("error",'Something went wrong..');
                }

            } 
    }
       private function PinValidation($newpin,$confpin){

        if(empty($newpin)){
            $this->sendMessage("error",Root::t('user','E01',array('key'=>'New Pin')));
            exit();
        }


        if(!empty($newpin)){
            $this->validate_transpin($newpin);
        }
        

        if(empty($confpin)){
            $this->sendMessage("error",Root::t('user','E01',array('key'=>'Confirm Pin')));
            exit();
        } 
        $newPin  = md5($newpin);   $conPin  = md5($confpin);

      
        if($newPin != $conPin){
            $this->sendMessage("error",Root::t('user','E47'));
            exit;
        }




        return true;
    }


    private function passwordLengthCheck($var,$key){

        if(strlen($var)<8){
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit;
        }



        $uppercase = preg_match('@[A-Z]@', $var);
        $lowercase = preg_match('@[a-z]@', $var);
        $number    = preg_match('@[0-9]@', $var);
        //$special   = preg_match("/\W/", $var);

        if(!$uppercase || !$lowercase || !$number ) {
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit();
        }

       
        if(strlen($var) > 16){
           echo $this->sendMessage("error",Root::t('user','E46',array('key'=>$key))); exit;
        }
    }

    private function PwdValidation($new,$conf,$usercreation=false){

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

            if(!$usercreation) {

                $this->sendMessage("error",Root::t('user','E055'));

            }
            if($usercreation) {

                $this->sendMessage("error","Password & Confirm password should be same");

            }
            
            exit;
        }
        return true;
    }

    public function actionAddUserUpdation()
    {

        $role_id        = $this->cleanMe(Router::post('role_id'));
        $user_id        = $this->cleanMe(Router::post('user_id'));
        $first_name     = $this->cleanMe(Router::post('first_name'));
        $last_name      = $this->cleanMe(Router::post('last_name'));
        $username       = $this->cleanMe(Router::post('username'));
       $nick_name       = $this->cleanMe(Router::post('nick_name'));
        $mobileno       = $this->cleanMe(Router::post('mobileno'));
        $mobileno2      = $this->cleanMe(Router::post('mobileno2'));
         $passport_number = $this->cleanMe(Router::post('passport_number'));
        $gender         = $this->cleanMe(Router::post('gender'));
        $dob            = $this->cleanMe(Router::post('date1'));
        $nationality    = $this->cleanMe(Router::post('countrycode'));
        $email          = $this->cleanMe(Router::post('email'));
        $position       = $this->cleanMe(Router::post('position'));
        $department     = $this->cleanMe(Router::post('department'));
        $permission     = $_POST['permission'];

        $services = [];

        if(!empty($permission)) { 

            foreach ($permission as $serviceVal) {
                $services[] =$serviceVal;
            }

        }

        

        $permissionList = implode(', ', $services);

        $params=array(
            'user_id'       => $user_id,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'role_id'       => $role_id,
            'username'      => $username,
            'nick_name'     => $nick_name,
            'passport_number'=> $passport_number,
            'mobileno'       => $mobileno,
            'mobileno2'      => $mobileno2,
            'gender'         => $gender,
            'dob'            => strtotime($dob),
            'nationality'    => $nationality,
            'position'       => $position,
            'department'     => $department,
            'email'          => $email,
            'services'       => $permissionList
        );

        if(empty($user_id)){
            $is_updated =$this->usermdl->createUser($params); 
            if ($is_updated === true) {
                $msg = "User Created Successfully";
                $this->sendMessage("success",$msg);
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
        }
        else{
            $is_updated =$this->usermdl->UserUpdation($params); 
            if ($is_updated === true) {
                $activity='Updated user :'.$username;
                $this->usermdl->adminActivityLog($activity);
                $msg = "User Updated Successfully";
                $this->sendMessage("success",$msg);
            }else{
                $msg=Root::t('subadmin','edit_err_text');
                return $this->sendMessage("error",$msg);
            }
        }
        

        return false;

    }

    public function actiongetDepartmentPositions(){
        $dept_id = $this->cleanMe(Router::get('dept_id'));

        $positionList = $this->usermdl->getDepartmentPosition($dept_id);
        $html = '<option value="">Select Position</option>';

        foreach($positionList as $pos){

            $html .= '<option value='.$pos['id'].'>'.$pos['name'].'</option>'; 
        }
        echo ($html);

    }
}

