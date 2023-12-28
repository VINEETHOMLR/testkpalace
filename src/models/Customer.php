<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Customer extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "customer";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal = (new CommonModal);
    }
    
    
    public function adminActivityLog($activity){

        $time=time();

        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , createtime= '$time' , createip='$this->IP' ");
        $this->execute();

        return true;
    }


    public function getCustomerAlcohol($data){

        $where = ' WHERE a.status = 0 ';

        if(!empty($data['user_id'])){
            $where .= " AND a.user_id = '$data[user_id]' ";
        }
        if(!empty($data['alcohol'])){
            $where .= " AND b.name LIKE '%$data[alcohol]%' ";
        }
        if(!empty($data['volume'])){
            $where .= " AND a.volume_percent = '$data[volume]' ";
        }
        if(!empty($data['expiryfrom']) && !empty($data['expiryto'])){
           $where .= " AND a.expiry_date BETWEEN '$data[expiryfrom]' AND '$data[expiryto]' ";
        }
        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(a.id) FROM customer_inventory a INNER JOIN inventory b ON a.inventory_id = b.id  $where ORDER BY a.id ","value");
        if(!empty($data['export'])){
          $result['data'] = $this->callsql("SELECT a.*,b.name FROM customer_inventory a INNER JOIN inventory b ON a.inventory_id = b.id  $where ORDER BY a.id DESC ","rows");
        }else{
        $result['data'] = $this->callsql("SELECT a.*,b.name FROM customer_inventory a INNER JOIN inventory b ON a.inventory_id = b.id  $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        }
        foreach ($result['data'] as $key => $value) {
            
          $result['data'][$key]['name']      = $this->callsql("SELECT username FROM customer WHERE id='$value[user_id]'","value");
          $result['data'][$key]['uniqueid']  = $this->callsql("SELECT uniqueid FROM customer WHERE id='$value[user_id]'","value");
          $result['data'][$key]['item']      = $value['name'];
          $result['data'][$key]['exp_date']  = date("d-m-Y",strtotime($value['expiry_date']));
          $result['data'][$key]['create_time']  = date("d-m-Y",$value['createtime']);
          $result['data'][$key]['action'] = '<a href="'.BASEURL.'Customer/UpdateCustomerAlcohol/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';
                                                      
        }

        if($count==0){
            $result['data'] = array();
        }

        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }
    public function getEmailById($key){

        return $this->callsql("SELECT `id`,CASE
                             WHEN `username` like '$key%' THEN `username`
                             WHEN `uniqueid`like '$key%' THEN `uniqueid`
                             ELSE ''
                             END AS text  FROM `customer` WHERE username like '$key%' OR `uniqueid` like '$key%'",'rows'); 
    }
    public function getUsernameId($key){

        return $this->callsql("SELECT `id`,CASE
                             WHEN `username` like '$key%' THEN `username`
                             ELSE ''
                             END AS text  FROM `customer` WHERE username like '$key%' OR `uniqueid` like '$key%'",'rows'); 
    }
    function getemail($user_id){

       return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");
   }

   function getUsername($user_id){

       return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");
   }

   public function getCustomerList($data){
        
        $where = ' WHERE a.id!=0 ';
        // $where1 = '  ';

        if($data['status']!=""){
            $where.= " AND a.status = '$data[status]' ";
        }else{
            $where.= " AND a.status ='0' ";
        }
        // if($data['customername']!=""){
        //      $where .= " AND us.status = '$data[status]' ";
        //  } 
        if($data['username']!=""){
          $where .= " AND a.username = '$data[username]' ";
        }

        if($data['uniqueid']!=""){
          $where .= " AND a.uniqueid LIKE '%".$data['uniqueid']."%' ";
        }
         
        if($data['surname']!=""){
          $where .= " AND b.surname = '$data[surname]' ";
        }
        if($data['given_name']!=""){
            $where .= " AND b.given_name = '$data[given_name]' ";
        }
        if($data['nickname']!=""){
            $where .= " AND b.nickname = '$data[nickname]'";
        }
        if($data['mobile']!=""){
            $where .= " AND b.mobile = '$data[mobile]'";
        }
        if($data['dob']!=""){
            $where .= " AND b.dob = '$data[dob]'";
        }
        if($data['assistant_name']!=""){
            $where .= " AND b.assistant_name LIKE '%$data[assistant_name]%'";
        }
        if($data['account_manager']!=""){
          $where .= " AND b.account_manager LIKE '%$data[account_manager]%' ";
        }
        if($data['referral']!=""){
          $where .= " AND b.referral LIKE '%$data[referral]%' ";
        }

        // if($data['userID']!=""){
        //     $where .= " AND us.username LIKE '%$data[userID]%'";
        // }
        if(!empty($data['user_id'])){
            $where .= " AND b.user_id = '$data[user_id]' ";
        }

        // if($data['username']!=""){
        //     $where .= " AND us.email LIKE '%$data[username]%'";
        // }    
        // if(!empty($data['dob'])){

            
        //     $date_to   = strtotime($data['dob']." 23:59:59");

        //     $where    .= " AND b.dob BETWEEN '$date[dob]'  ";
        // }  

        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND a.createtime BETWEEN '$date_from' AND '$date_to' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(a.id) FROM $this->tableName as a INNER JOIN customer_extra as b ON a.id=b.user_id  $where ","value");
             if(!empty($data['export'])){
          $result['data'] = $this->callsql("SELECT a.*, b.user_id,b.surname,b.given_name,b.nickname,b.mobile_code,b.mobile,b.gender,b.dob,b.language,b.allergies,b.assistant_name,b.assistant_mobile,b.assistant_email,b.account_manager,b.referral,b.promo_popup,b.remarks FROM $this->tableName as a INNER JOIN customer_extra as b ON a.id=b.user_id  $where ORDER BY a.id DESC ","rows");
        }else{
        $result['data'] = $this->callsql("SELECT a.*, b.user_id,b.surname,b.given_name,b.nickname,b.mobile_code,b.mobile,b.gender,b.dob,b.language,b.allergies,b.assistant_name,b.assistant_mobile,b.assistant_email,b.account_manager,b.referral,b.promo_popup,b.remarks FROM $this->tableName as a INNER JOIN customer_extra as b ON a.id=b.user_id  $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        }

        // $result['data'] = $this->callsql("SELECT a.*, b.user_id,b.surname,b.given_name,b.nickname,b.mobile_code,b.mobile,b.gender,b.dob,b.language,b.allergies,b.assistant_name,b.assistant_mobile,b.assistant_email,b.promo_popup,b.remarks FROM $this->tableName as a INNER JOIN customer_extra as b ON a.id=b.user_id  $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");

        // $result['data'] = $this->callsql("SELECT * FROM $this->tableName as us  $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");
        
    


        foreach ($result['data'] as $key => $value) {
            
            $result['data'][$key]['language']       = $this->callsql("SELECT lang_name FROM `language` WHERE id='$value[language]' ","value");
            $result['data'][$key]['country']        = $this->callsql("SELECT name FROM `country` WHERE id='$value[countrycode]' ","value");
            $result['data'][$key]['phonecode']      = $this->callsql("SELECT phonecode FROM `country` WHERE id='$value[mobile_code]' ","value");

            $result['data'][$key]['allergies']      = str_replace(',', '/', $value['allergies']);
          $acctStatus = '';
          if($value['status']=='0'){
              $acctStatus = "Active";
          }
          else{
              $accStatus = "Blocked";
          }

          //$result['data'][$key]['fname']      = ucfirst($cus_extra['given_name']);

          $result['data'][$key]['time']       = date("d-m-Y H:i:s",$value['createtime']);
          $result['data'][$key]['accStatus']      = ($value['status']==0) ? 'Active' : 'Blocked';


        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }
    public function updateCustomerStatus($status,$value)
    {
        $this->query("UPDATE $this->tableName SET status='$status' WHERE id='$value'");
        $this->execute();
        return true;
    }
    public function getCustomerInfo($user_id) {

    return $this->callSql("SELECT * FROM $this->tableName WHERE id = '$user_id' ","row");

  }
  
  public function getcustomerdetails($id){

          $customer['info']                  = $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');  

          if(empty($customer['info']))
            return [] ;

          
          $uId                           = $customer['info']['id'];
          $customer['extra']                 = $this->callsql("SELECT * FROM customer_extra WHERE user_id='$uId'",'row'); 
          $customer['wallet']                = $this->callsql("SELECT * FROM customer_wallet WHERE user_id='$uId'",'row');       
          //$customer['lang']                = $this->callsql("SELECT * FROM customer_wallet WHERE user_id='$uId'",'row');       

          return $customer;
    }

    

    public function updateCustomer($data){

       $time=time();
       $addactivity1=$addactivity2=$addactivity3=$addactivity4=$addactivity5=$addactivity6=$addactivity7=$addactivity8=$addactivity9=$addactivity10=$addactivity11=$addactivity12=$addactivity13=$addactivity14=$addactivity15=$addactivity16=$addactivity17='';
       
       $usersdetails = $this->callsql("SELECT * FROM customer u JOIN customer_extra ue ON u.`id`=ue.`user_id` WHERE u.`id`='$data[edit]'","row");
       if(!empty($usersdetails)){

          $this->query("UPDATE `customer` SET countrycode ='$data[countrycode]',email ='$data[email]' WHERE id ='$data[edit]' ");
          $this->execute();
          $last_id = $this->lastInsertId();

          $mobile_country_code = $this->callsql("SELECT phonecode FROM country WHERE id='$data[mobile_country_code]'",'value');

          $this->query("UPDATE `customer_extra` SET surname='$data[surname]',given_name='$data[given_name]',nickname='$data[nickname]',mobile_code='$data[mobile_code]',mobile='$data[mobile]',gender='$data[gender]',dob='$data[dob]',language='$data[language]',allergies='$data[allergies]',assistant_name='$data[assistant_name]',assistant_mobile='$data[assistant_mobile]',assistant_email='$data[assistant_email]',account_manager='$data[account_manager]',remarks='$data[remarks]',mobile_country_code='$mobile_country_code' WHERE user_id ='$data[edit]'");
          $this->execute();
          
          $usersdetails2 = $this->callsql("SELECT * FROM customer u JOIN customer_extra ue ON u.`id`=ue.`user_id` WHERE u. id='$data[edit]'","row");

          if($usersdetails['given_name']!=$usersdetails2['given_name'])
          {
            $addactivity1 ="Username updated from ".$usersdetails['given_name']." to ".$usersdetails2['given_name'].".";
          }
         
          if($usersdetails['mobile']!=$usersdetails2['mobile'])
          {
            $addactivity2 ="Mobile number updated from ".$usersdetails['mobile']." to ".$usersdetails2['mobile'].".";
          }
          if($usersdetails['gender']!=$usersdetails2['gender'])
          {
            $addactivity3 ="Gender updated from ".$usersdetails['gender']." to ".$usersdetails2['gender'].".";
          }

          if($usersdetails['email']!=$usersdetails2['email'])
          {
            $addactivity4 ="Customer Email updated from ".$usersdetails['email']." to ".$usersdetails2['email'].".";
          }
         
          if($usersdetails['countrycode']!=$usersdetails2['countrycode'])
          {
            $addactivity5 ="Country Code updated from ".$usersdetails['countrycode']." to ".$usersdetails2['countrycode'].".";
          }
          if($usersdetails['surname']!=$usersdetails2['surname'])
          {
            $addactivity6 ="Surname updated from ".$usersdetails['surname']." to ".$usersdetails2['surname'].".";
          }
          if($usersdetails['nickname']!=$usersdetails2['nickname'])
          {
            $addactivity7 ="Nickname updated from ".$usersdetails['nickname']." to ".$usersdetails2['nickname'].".";
          }
         
          if($usersdetails['dob']!=$usersdetails2['dob'])
          {
            $addactivity8 ="Date Of Birth updated from ".$usersdetails['dob']." to ".$usersdetails2['dob'].".";
          }
          if($usersdetails['language']!=$usersdetails2['language'])
          {
            $addactivity9 ="Language updated from ".$usersdetails['language']." to ".$usersdetails2['language'].".";
          }

          if($usersdetails['allergies']!=$usersdetails2['allergies'])
          {
            $addactivity10 ="Allergies updated from ".$usersdetails['allergies']." to ".$usersdetails2['allergies'].".";
          }
          if($usersdetails['assistant_name']!=$usersdetails2['assistant_name'])
          {
            $addactivity11="Assistant Name updated from ".$usersdetails['assistant_name']." to ".$usersdetails2['assistant_name'].".";
          }
          if($usersdetails['assistant_email']!=$usersdetails2['assistant_email'])
          {
            $addactivity12 ="Assistant Email updated from ".$usersdetails['given_name']." to ".$usersdetails2['given_name'].".";
          }
         
          if($usersdetails['assistant_mobile']!=$usersdetails2['assistant_mobile'])
          {
            $addactivity13="Assistant Mobile updated from ".$usersdetails['assistant_mobile']." to ".$usersdetails2['assistant_mobile'].".";
          }
         
          if($usersdetails['remarks']!=$usersdetails2['remarks'])
          {
            $addactivity14="Remarks updated from ".$usersdetails['remarks']." to ".$usersdetails2['remarks'].".";
          }
          if($usersdetails['mobile_code']!=$usersdetails2['mobile_code'])
          {
            $addactivity15="Mobile code updated from ".$usersdetails['mobile_code']." to ".$usersdetails2['mobile_code'].".";
          }
          if($usersdetails['email']!=$usersdetails2['email'])
          {
            $addactivity16="Email updated from ".$usersdetails['email']." to ".$usersdetails2['email'].".";
          }
           if($usersdetails['account_manager']!=$usersdetails2['account_manager'])
          {
            $addactivity17="Account Manager updated from ".$usersdetails['account_manager']." to ".$usersdetails2['account_manager'].".";
          }

          $addactivity=$addactivity1."".$addactivity2."".$addactivity3."".$addactivity4."".$addactivity5."".$addactivity6."".$addactivity7."".$addactivity8."".$addactivity9."".$addactivity10."".$addactivity11."".$addactivity12."".$addactivity13."".$addactivity14."".$addactivity15."".$addactivity16."".$addactivity17;
          $activity = "Edited Details of Customer ".$usersdetails2['given_name'];
          $this->adminActivityLog($activity);

          return true;
        }else
          return false;
    }
    public function UpdatePass($new,$uid)
    {
      $this->query("UPDATE `customer` SET  `password`='$new' WHERE id='$uid'");
      if($this->execute())
      {
          return true;

      }
      return false;
    }

    public function getCustomerGalleryList($data){

        $where = ' WHERE us.id!=0 ';

        if($data['status']!=""){
            $where .= " AND us.status = '$data[status]' ";
        }else{
            $where.= " AND us.status ='0' ";
        }

        if(!empty($data['user_id'])){
            $where .= " AND us.user_id = '$data[user_id]' ";
        }    
        if(!empty($data['room_id'])){
            $where .= " AND us.room_id = '$data[room_id]' ";
        }  

        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND us.createtime BETWEEN '$date_from' AND '$date_to' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(us.id) FROM customer_gallery as us  $where ","value");

        $result['data'] = $this->callsql("SELECT * FROM customer_gallery as us $where ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach ($result['data'] as $key => $value) {

          $cus_extra   = $this->callsql("SELECT * FROM customer_extra WHERE user_id='$value[user_id]'","row");
          $room_id     = $this->callsql("SELECT id,room_no,type,description FROM room WHERE id='$value[room_id]'","row");
          $gmail   = $this->callsql("SELECT email,uniqueid,username FROM customer WHERE id='$value[user_id]'","row");
          if($value['status']=='0')
              $acctStatus="Active";
          else
              $accStatus="Hide";

          $result['data'][$key]['fname']      = !empty($gmail['username']) ? $gmail['username'] : '';
          $result['data'][$key]['room_no']    = !empty($room_id['description']) ? $room_id['description'] : '';
          $result['data'][$key]['email']      = !empty($gmail['email'])?$gmail['email']:'';
          $result['data'][$key]['uniqueid']   = !empty($gmail['uniqueid']) ? $gmail['uniqueid'] : '';
          $result['data'][$key]['time']       = !empty($value['createtime']) ? date("d-m-Y H:i:s",$value['createtime']) : '';                                              
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }

    public function getImages($id){

        $this->query("SELECT * FROM `customer_gallery` WHERE id=:id");
        $this->bind(':id',$id);

       return $this->single(); 
    }

    public function addgallery($data,$gallery){

        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $admin_id=$this->adminID;
       
        $activity  = "Gallery Image added";
        $this->adminActivityLog($activity);

        $this->query("INSERT INTO customer_gallery SET  user_id='$data[user_id]',room_id='$data[room_id]',remarks='".htmlspecialchars_decode($data['remarks'])."',image='$gallery',createtime='$time',status='$data[status]',createid='$admin_id',createip='$ip'");
           if($this->execute()){
              $link_id  = $this->lastInsertId(); 
           }
           return true;
     }


      public function update_gallery($data,$gallery)
    {   


        $activity="Gallery Image Updated";
        $time=time(); 
        $ip=$_SERVER['REMOTE_ADDR']; 
        $this->adminActivityLog($activity);
       $this->query("UPDATE customer_gallery SET user_id='$data[user_id]',room_id='$data[room_id]',remarks='".htmlspecialchars_decode($data['remarks'])."',status='$data[status]',image='$gallery'  WHERE `id`='$data[editID]'");
        $this->execute();

        
       return true;
    }
    public function addCustomerAlcohol($ip) {

      $time       = time();
      $user_id    = $ip['user_id'];
      $ip_add     = $this->IP;
      for($i=0;$i<count($ip['name']);$i++){
        
        $name     = $ip['name'][$i];
        $volume   = $ip['volume'][$i];
        $expiry   = date("Y-m-d", strtotime($ip['expiry'][$i]));
        $quantity = $ip['balance'][$i];//same value balance
        $balance  = $ip['balance'][$i];

        $this->callSql("INSERT INTO `customer_inventory`(`user_id`, `inventory_id`, `volume_percent`, `expiry_date`, `quantity`, `balance`, `createtime`, `createip`) VALUES ('$user_id','$name','$volume','$expiry','$quantity','$balance','$time','$ip_add')");
        }

        $id = $this->lastInsertId();
        return $id;

     }
     public function getCustomerAlcoholById($id) {

      return $this->callSql("SELECT * FROM customer_inventory WHERE id = '$id' ","row");

    }
     public function addCreateCustomer($ip) {

            $time=time();
            $ippr=$this->IP;
            

            $nextId  = $this->calLSql("SELECT id FROM customer ORDER BY id DESC limit 1","value");
            if(empty($nextId) || $nextId == '0'){
              $nextId = '1';
            }else{
              $nextId = bcadd($nextId,1);
            }
            $uniqueId = "KP-".$this->addPrefix($nextId);
            $username       = $ip['username'];
            $referal          = $ip['referral'];
            $email          = $ip['email'];
            $countrycode    = $ip['countrycode'];
            $surname        = $ip['surname'] ;
            $given_name     = $ip['given_name'];
            $nickname       = $ip['nickname'];
            $mobilecode     = $ip['mobilecode'];
            $mobile_country_code     = $ip['mobile_country_code'];
            $mobile         = $ip['mobile'];
            $gender         = $ip['gender'];
            $date1          = $ip['dob'];
            $language       = $ip['language'];
            $allergies      = $ip['allergies'];
            $assname        = $ip['assistant_name'];
            $assmobile      = $ip['assistant_mobile'];
            $assemail       = $ip['assistant_email'];
            $accman         = $ip['account_manager'];
            $remarks        = $ip['remarks'];

            $this->query("INSERT INTO customer SET uniqueid='$uniqueId',username ='$ip[username]', email ='$ip[email]',countrycode ='$ip[countrycode]', createtime='$time',createip='$ippr' ");
            $this->execute();
            $last_id = $this->lastInsertId(); 

            $mobile_country_code = $this->callsql("SELECT phonecode FROM country WHERE id='$mobile_country_code'",'value');

          $this->query("INSERT INTO customer_extra SET  user_id='$last_id', surname ='$ip[surname]',given_name ='$ip[given_name]', nickname ='$ip[nickname]',mobile_code ='$ip[mobilecode]',mobile ='$ip[mobile]',gender ='$ip[gender]',dob ='$ip[dob]',language ='$ip[language]',allergies ='$ip[allergies]',assistant_name ='$ip[assistant_name]',assistant_mobile ='$ip[assistant_mobile]',assistant_email ='$ip[assistant_email]',account_manager ='$ip[account_manager]',referral ='$ip[referral]',remarks ='$ip[remarks]',mobile_country_code='$mobile_country_code'");
          $this->execute();
          $this->query("INSERT INTO customer_wallet SET user_id='$last_id' ");
          $this->execute();
          return $last_id;
        
        


     }
       public function generateUniqueId(){

        $alpRand  = mt_rand(10,25);
        $rand     = mt_rand(100000000,999999999);
        $uniqueId = $alpRand.$rand;
        
        $this->query("SELECT COUNT(*) FROM `customer` WHERE uniqueid=:unique ");
        $this->bind(":unique",$uniqueId);
        $count    = $this->getValue();
        
        if(!empty($count)){
            $uniqueId = $this->generateUniqueId();
        }
        return $uniqueId;
    }
    //  public function getReferral($referal) {

    //     $this->query("SELECT COUNT(*) FROM customer WHERE  uniqueid=:referal ");
    //     $this->bind(":referal",$referal);
    //     $referData  = $this->single();
        

    //     if (empty($referData)){
    //         $referlID   = '';
    //         return false;
    //       }
    //     $referlID   = $referData['id'];

        
    //     return $referlID;
    // }
     public function getCountryCode(){
   
        $details = $this->callsql("SELECT id,nicename,iso,phonecode FROM country ORDER BY name ASC","rows");
     //print_r($details); exit;

        return $details;
    }
    public function getLanguage(){
        $language    = $this->callsql("SELECT id,lang_name FROM `language` WHERE `status`=1","rows");
          // print_r($language); exit;
        return $language;
    }

    public function getAllergies(){
        $list    = $this->callsql("SELECT id,name FROM `allergies` WHERE `status`=0","rows");
          // print_r($language); exit;
        return $list;
    }

   
    public function UpdateAlcoholCustomer($ip)
    {
        $name     = $ip['name'];
        $volume   = $ip['volume'];
        $expiry   = date("Y-m-d", strtotime($ip['expiry']));
        //$quantity = $ip['quantity'];
        $balance  = $ip['balance'];
        $id       = $ip['edit_id'];
   
        $this->query("UPDATE `customer_inventory` SET `inventory_id`=$name,`volume_percent`='$volume',`expiry_date`='$expiry',`balance`='$balance' WHERE id='$id'");
        $this->execute();
        return true;
    }
      public function deleteCustomerAlcohol($ID){
      
        $this->query("UPDATE customer_inventory SET status=1 WHERE id='$ID'");
      
        $this->execute();

        return true;
   }

      public function deletegallery($ID){

      $time=time();
      $this->query("UPDATE `customer_gallery` SET status='2' WHERE id='$ID'");
      if($this->execute()){
        
         $this->adminActivityLog("Gallery Deleted");
         return true;
      }else
         return false;
   }
   
  public function getInventoryList(){
    $inventory            = $this->callsql("SELECT * FROM `inventory` WHERE status =0 ORDER BY name ASC","rows");
    return $inventory;
  }
    
  private function addPrefix($integer){
    $integerStr = (string)$integer;
    if (strlen($integerStr) < 6) {
        $zerosToAdd = 6 - strlen($integerStr);
        $prefixedInteger = str_repeat("0", $zerosToAdd) . $integerStr;
        return $prefixedInteger;
    } else {
        return $integerStr;
    }
  }

    public function getCustomerProfileRequestsbkp($data){
        $where = ' WHERE a.id!=0 ';

        /*if($data['status']=='0' || $data['status']=='1' || $data['status']=='2'){
            $where.= " AND a.status = '$data[status]' ";
        }*/

        if(!empty($data['status']) ||  in_array($data['status'],['0','1'])){
            $where .= " AND a.status = '$data[status]' ";
        }

        if($data['uniqueid']!=""){
            $unique_id = $this->callsql("SELECT email FROM `customer` WHERE uniqueid='".$data['uniqueid']."' ","value");
            $where .= " AND a.request LIKE '%".$unique_id."%' ";
        }

        if($data['requested_by']!=""){
            $req_by_data = $this->callsql("SELECT user_id FROM `user` WHERE email LIKE '%".$data['requested_by']."%' ","value");
            $where .= " AND a.requested_by = '".$req_by_data."' ";

        }

        if($data['email']!=""){
            $customer_email = $this->callsql("SELECT email FROM `customer` WHERE email='".$data['email']."' ","value");
            if(!empty($customer_email)){
                $where .= " AND a.request LIKE '%".$customer_email."%' ";
            }
            else{
                $where .= " AND a.request LIKE '%---%' ";
            }

        }

        if($data['mobile_no']!=""){
            $customer_uid = $this->callsql("SELECT user_id FROM `customer_extra` WHERE mobile='".$data['mobile_no']."' ","value");
            $customer_mobile = $this->callsql("SELECT email FROM `customer` WHERE id='".$customer_uid."' ","value");

            $where .= " AND a.request LIKE '%".$customer_mobile."%' ";

        }
         
        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND a.createtime BETWEEN '$date_from' AND '$date_to' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(a.id) FROM cutomer_profile_request as a  $where ","value");
        //echo "SELECT a.* FROM cutomer_profile_request as a $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage"; die;
        $result['data'] = $this->callsql("SELECT a.* FROM cutomer_profile_request as a $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        
        $statusArr = ['0'=>'requested','1'=>'approved','3'=>'rejected'];

        foreach ($result['data'] as $key => $value) {
            $udata = json_decode($value['request'],TRUE);
            $result['data'][$key]['email']      = $udata['email'];
            $result['data'][$key]['mobile_no']  = $udata['mobile_no'];
            $result['data'][$key]['user_id']    = $this->callsql("SELECT id FROM `customer` WHERE email='".$udata['email']."' ","value");
            $result['data'][$key]['customer_uid']    = $this->callsql("SELECT uniqueid FROM `customer` WHERE email='".$udata['email']."' ","value");
            $result['data'][$key]['requested_by']    = $this->callsql("SELECT email FROM `user` WHERE user_id='".$value['requested_by']."' ","value");
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;

    }

    public function getCustomerProfileRequests($data){
        $where = ' WHERE a.id!=0 ';

        /*if($data['status']=='0' || $data['status']=='1' || $data['status']=='2'){
            $where.= " AND a.status = '$data[status]' ";
        }*/

        if(!empty($data['status']) ||  in_array($data['status'],['0','1','2'])){
            $where .= " AND a.status = '$data[status]' ";
        }

        if(!empty($data['customer_id'])){
            $where .= " AND a.customer_id = '$data[customer_id]' ";
        }

        if(!empty($data['type'])){
            $where .= " AND a.type = '$data[type]' ";
        }

        // if($data['uniqueid']!=""){
        //     $unique_id = $this->callsql("SELECT email FROM `customer` WHERE uniqueid='".$data['uniqueid']."' ","value");
        //     $where .= " AND a.request LIKE '%".$unique_id."%' ";
        // }

        // if($data['requested_by']!=""){
        //     $req_by_data = $this->callsql("SELECT user_id FROM `user` WHERE email LIKE '%".$data['requested_by']."%' ","value");
        //     $where .= " AND a.requested_by = '".$req_by_data."' ";

        // }

        // if($data['email']!=""){
        //     $customer_email = $this->callsql("SELECT email FROM `customer` WHERE email='".$data['email']."' ","value");
        //     if(!empty($customer_email)){
        //         $where .= " AND a.request LIKE '%".$customer_email."%' ";
        //     }
        //     else{
        //         $where .= " AND a.request LIKE '%---%' ";
        //     }

        // }

        // if($data['mobile_no']!=""){
        //     $customer_uid = $this->callsql("SELECT user_id FROM `customer_extra` WHERE mobile='".$data['mobile_no']."' ","value");
        //     $customer_mobile = $this->callsql("SELECT email FROM `customer` WHERE id='".$customer_uid."' ","value");

        //     $where .= " AND a.request LIKE '%".$customer_mobile."%' ";

        // }
         
        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND a.request_time BETWEEN '$date_from' AND '$date_to' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count = $this->callsql("SELECT COUNT(a.id) FROM cutomer_profile_request as a  $where ","value");
        //echo "SELECT a.* FROM cutomer_profile_request as a $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage"; die;
        $result['data'] = $this->callsql("SELECT a.* FROM cutomer_profile_request as a $where ORDER BY a.id DESC LIMIT $pagecount,$this->perPage","rows");
        
        $statusArr = ['0'=>'Requested','1'=>'Approved','3'=>'Rejected'];
        $typeArray = ['1'=>'Email Update','2'=>'Mobile Number Update','3'=>'Gender Update','4'=>'Surname Update','5'=>'DOB Update','6'=>'Username Update'];

        foreach ($result['data'] as $key => $value) {

            $customer_name = $this->callsql("SELECT username FROM customer WHERE id='$value[customer_id]'",'value');

            $updated_by    =  !empty($value['updated_by']) ? $this->callsql("SELECT username FROM user WHERE user_id='$value[updated_by]'",'value'):'-';

            $result['data'][$key]['customer_uid'] = $customer_name;
            $result['data'][$key]['updated_by'] = $updated_by;
            $result['data'][$key]['updated_time'] = !empty($value['update_time']) ? date('d-m-Y H:i:s',$value['update_time']) : '-';
            $result['data'][$key]['type'] =  $typeArray[$value['type']] ;
            $result['data'][$key]['requested_by']    = $this->callsql("SELECT concat(first_name,' ',last_name)  as name FROM `user` WHERE user_id='".$value['requested_by']."' ","value");

           
        }

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;

    }

    public function getCustomerApprovalDetails($id){
        $customer_req_dtls          = $this->callsql("SELECT * FROM cutomer_profile_request WHERE id='$id'",'row');
        $cust_dtls_decode           = json_decode($customer_req_dtls['request'], TRUE);

        $customer['req_data']        = $customer_req_dtls;  
        $customer['info']           = $this->callsql("SELECT * FROM $this->tableName WHERE email='".$cust_dtls_decode['email']."'",'row');  
          if(empty($customer['info'])){
                return [] ;
          }
          $uId                      = $customer['info']['id'];
          $customer['extra']        = $this->callsql("SELECT * FROM customer_extra WHERE user_id='$uId'",'row'); 

          return $customer;
    }


    public function getCustomerUpdateRequestDetails($id){
        
        $typeArray = ['1'=>'Email Update','2'=>'Mobile Number Update','3'=>'Gender Update','4'=>'Surname Update','5'=>'DOB Update','6'=>'Username Update'];
        $old_labels = [

                       '1'=>['old'=>'Old Email','new'=>'New Email'],
                       '2'=>['old'=>'Old Phone','new'=>'New Phone'],
                       '3'=>['old'=>'Old Gender','new'=>'New Gender'],
                       '4'=>['old'=>'Old Surname','new'=>'New Surname'],
                       '5'=>['old'=>'Old DOB','new'=>'New DOB'],
                       '6'=>['old'=>'Old Username','new'=>'New Username']

                     ];

        $requestDetails  = $this->callsql("SELECT * FROM cutomer_profile_request WHERE id='$id'",'row');
        $customer_id     =  $requestDetails['customer_id'];
        $customer_extra = $this->callsql("SELECT given_name,mobile_country_code,mobile FROM customer_extra WHERE user_id='$customer_id'",'row');
        $customer_email = $this->callsql("SELECT email FROM customer WHERE id='$customer_id'",'value');
        $type = $typeArray[$requestDetails['type']];
        $old_label = $old_labels[$requestDetails['type']]['old'];
        $new_label = $old_labels[$requestDetails['type']]['new'];

        if($requestDetails['type'] == '1'){ //email updation
            
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value =  $json_decoded['old_email'];
            $new_value = $json_decoded['email'];

        }

        if($requestDetails['type'] == '2'){ //mobile updation
            
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value =  $json_decoded['old_mobile_country_code'].''.$json_decoded['old_mobile']; 
            $new_value = $json_decoded['mobile_country_code'].''.$json_decoded['mobile'];

        }

        if($requestDetails['type'] == '3'){ //gender Updation
            
            $genderArray = ['0'=>'Male','1'=>'Female'];
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value    =  $genderArray[$json_decoded['old_gender']]; 
            $new_value    = $genderArray[$json_decoded['gender']];

        }

        if($requestDetails['type'] == '4'){ //Surname Updation
            
            
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value    =  $json_decoded['old_surname']; 
            $new_value    =  $json_decoded['surname'];

        }

        if($requestDetails['type'] == '5'){ //DOB Updation
            
            
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value    =  $json_decoded['old_dob']; 
            $new_value    =  $json_decoded['dob'];

        }
        if($requestDetails['type'] == '6'){ //username updation
            
            
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $old_value    =  $json_decoded['old_username']; 
            $new_value    =  $json_decoded['username'];

        }

        $unique_id = $this->callsql("SELECT uniqueid FROM customer WHERE id='$customer_id'",'value');

        $details = [];
        $details['id'] = $id;
        $details['uniqueid'] = $unique_id;
        $details['given_name'] = $customer_extra['given_name'];
        $details['type'] = $type;
        $details['old_label'] = $old_label;
        $details['new_label'] = $new_label;
        $details['old_value'] = $old_value;
        $details['new_value'] = $new_value;
        $details['remarks']   = !empty($requestDetails['remarks']) ? $requestDetails['remarks'] : '';
        $details['status']    = $requestDetails['status'];
        return $details;





    }

    public function getRequestDetails($id)
    {

        return $this->callsql("SELECT id,request,type,customer_id,status FROM cutomer_profile_request WHERE id='$id'",'row');  

    }

    public function checkMobileUsed($params)
    {
        $mobile = $params['mobile'];
        return $result = $this->callsql("SELECT id FROM customer_extra WHERE concat(mobile_country_code,'',mobile)='$mobile'",'value');



    }
    public function checkEmailUsed($params)
    {
        $email = $params['email'];
        return $result = $this->callsql("SELECT id FROM customer WHERE email='$email'",'value');



    }

    public function checkUsernameUsed($params)
    {
        $username = $params['username'];
        return $result = $this->callsql("SELECT id FROM customer WHERE username='$username'",'value');



    }

    

    public function approveRequest($params)
    {
        $id = $params['id'];
        $remarks = $params['remarks'];
        $typeArray = ['1'=>'Email Update','2'=>'Mobile Number Update','3'=>'Gender Update','4'=>'Surname Update','5'=>'DOB Update','6'=>'Username Update'];
        $old_labels = ['1'=>['old'=>'Old Email','new'=>'New Email'],'2'=>['old'=>'Old Phone','new'=>'New Phone'],'3'=>['old'=>'Old Gender','new'=>'New Gender'],'4'=>['old'=>'Old Surname','new'=>'New Surname'],'5'=>['old'=>'Old DOB','new'=>'New DOB'],'6'=>['old'=>'Old Username','new'=>'New Username']];
        $requestDetails  = $this->callsql("SELECT * FROM cutomer_profile_request WHERE id='$id'",'row');
        $customer_id     =  $requestDetails['customer_id'];
        $customer_extra = $this->callsql("SELECT given_name,mobile_country_code,mobile,surname,gender FROM customer_extra WHERE user_id='$customer_id'",'row');
        $type = $typeArray[$requestDetails['type']];
        $old_label = $old_labels[$requestDetails['type']]['old'];
        $new_label = $old_labels[$requestDetails['type']]['new'];
        $customer = $this->callsql('SELECT * FROM customer WHERE id="$id"','row');

        if($requestDetails['type'] == '1'){ //email updation

            $old_value =  $customer['email']; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $json_decoded['email'];

        }

        if($requestDetails['type'] == '2'){ //mobile updation

            $old_value =  $customer_extra['mobile_country_code'].''.$customer_extra['mobile']; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $json_decoded['mobile_country_code'].''.$json_decoded['mobile'];

        }


        if($requestDetails['type'] == '3'){ //gender updation
            
            $genderArray = ['0'=>'Male','1'=>'Female'];
            $old_value   =  $genderArray[$customer_extra['gender']]; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $genderArray[$json_decoded['gender']]; 

        }
        if($requestDetails['type'] == '4'){ //surname updation

            $old_value =  $customer_extra['surname']; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $json_decoded['surname'];

        }

        if($requestDetails['type'] == '5'){ //dob updation

            $old_value =  $customer_extra['dob']; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $json_decoded['dob'];

        }

        if($requestDetails['type'] == '6'){ //username updation

            $old_value =  $customer['username']; 
            $json_decoded =  json_decode($requestDetails['request'],true); 
            $new_value = $json_decoded['username'];

        }

        $tableArray = ['1'=>'customer','2'=>'customer_extra','3'=>'customer_extra','4'=>'customer_extra','5'=>'customer_extra','6'=>'customer'];
        $whereArray = ['1'=>'id','2'=>'user_id','3'=>'user_id','4'=>'user_id','5'=>'user_id','6'=>'id'];

        foreach($json_decoded as $key=>$value)
        {
            $table = $tableArray[$requestDetails['type']];
            $where = $whereArray[$requestDetails['type']];
            
             if (explode('_',$key)[0]!='old') {

                $sql = "UPDATE $table SET $key='$value' WHERE $where='$customer_id'";  
                $this->query($sql);
                $this->execute();  
                
              }
            


        }

        $sql = "UPDATE cutomer_profile_request SET status='1',remarks='$remarks',updated_by='$this->adminID',user_type='1' WHERE id='$id'";
        $this->query($sql);
        $this->execute();


        $activity = "Approved Customer Profile Update Request.Request id=".$id.'.Changed '.$old_value.' to '.$new_value;

        return $this->adminActivityLog($activity);





    }


    public function rejectRequest($params){

        $id = $params['id'];
        $remarks = $params['remarks'];
        $sql = "UPDATE cutomer_profile_request SET status='2',remarks='$remarks',updated_by='$this->adminID',user_type='1' WHERE id='$id'";
        $this->query($sql);
        $this->execute();


        $activity = "Rejected Customer Profile Update Request.Request id=".$id;

        return $this->adminActivityLog($activity);

    }

    

    public function approveCustomerRequest($data){
        $time = time();
        $this->query("UPDATE cutomer_profile_request SET status='1', update_time = $time, remarks = '".$data['remarks']."' WHERE id='".$data['cid']."' ");
        $this->execute();

        $customer_req_dtls          = $this->callsql("SELECT * FROM cutomer_profile_request WHERE id='".$data['cid']."' ",'row');
        $cust_dtls_decode           = json_decode($customer_req_dtls['request'], TRUE);

        $this->query("UPDATE customer SET status='0'  WHERE email='".$cust_dtls_decode['email']."' ");
        $this->execute();

        return true;
    }

    public function rejectCustomerRequest($data){
        $time = time();
        $this->query("UPDATE cutomer_profile_request SET status='2', update_time = $time, remarks = '".$data['remarks']."' WHERE id='".$data['cid']."' ");
        $this->execute();

        $customer_req_dtls          = $this->callsql("SELECT * FROM cutomer_profile_request WHERE id='".$data['cid']."' ",'row');
        $cust_dtls_decode           = json_decode($customer_req_dtls['request'], TRUE);

        $this->query("UPDATE customer SET status='1'  WHERE email='".$cust_dtls_decode['email']."' ");
        $this->execute();

        return true;
    }

    public function getCustomersList()
    {

        $sql = "SELECT given_name as name,user_id  FROM customer_extra";

        return $this->callsql($sql,'rows');

    }
    public function getCustomersusernameList()
    {

        $sql = "SELECT id,username FROM customer";

        return $this->callsql($sql,'rows');

    }


    public function getUpdateAlcoholList($data){
        $result         = [];  
        $where          = ' WHERE id!=0 AND status = 1';
        $where2         = '';
        $order_ids_arr  = [];

        $where_customer_id      = ' WHERE status="1" ';

        if(!empty($data['customer_id'])){
            $where_customer_id .= " AND customer_id = '".$data['customer_id']."' ";
            $order_history  = $this->callsql("SELECT id, customer_id, room_id FROM `order_history`  $where_customer_id  ","rows");
            $order_ids      = array_unique(array_column($order_history, 'id'));
            
            if(!empty($order_ids)){
                array_push($order_ids_arr, $order_ids);
            }
        }
        

        if(!empty($data['alcohol'])){
            $where2     .= " WHERE inventory_id = '".$data['alcohol']."' ";

            $purchase_order_dtls    = $this->callsql("SELECT id, order_id, inventory_id, expiry_date FROM `customer_purchases` $where2  ","rows");
            $purchase_order_ids     = array_unique(array_column($purchase_order_dtls, 'order_id'));

            if(!empty($purchase_order_ids)){
                array_push($order_ids_arr, $purchase_order_ids);
            }
        }

        if(!empty($data['volume'])){
            $where      .= " AND volume= '".$data['volume']."' ";
        }
        if($data['search']=='Search'){
            if(!empty($order_ids_arr)){
                $where .= " AND order_id IN('".implode(',',$order_ids_arr[0])."')";
            }
            else if(empty($order_ids_arr)){
                return $result['data'] = array();
            }
        }

        $pagecount      = ($data['page'] - 1) * $this->perPage;
        $count          = $this->callsql("SELECT COUNT(id) FROM customer_purchase_details $where ","value");

        $result['data'] = $this->callsql("SELECT `id`, `order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status` FROM customer_purchase_details $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage","rows");

        foreach($result['data'] as $key=>$value){

            $cust_id        = $this->callsql("SELECT customer_id FROM order_history WHERE id='".$value['order_id']."'","value");
            $purchas_dtls   = $this->callsql("SELECT item_name, expiry_date, balance FROM customer_purchases WHERE id='".$value['purchase_id']."'","row");

            $result['data'][$key]['name']           = $this->callsql("SELECT username FROM customer WHERE id='$cust_id'","value");
            $result['data'][$key]['customer_id']    = $this->callsql("SELECT uniqueid FROM customer WHERE id='$cust_id'","value");
            $result['data'][$key]['item']           = $purchas_dtls['item_name'];
            $result['data'][$key]['volume']         = $value['volume'];
            $result['data'][$key]['balance']        = $purchas_dtls['balance'];
            $result['data'][$key]['exp_date']       = date("d-m-Y",$purchas_dtls['expiry_date']);
            $result['data'][$key]['create_time']    = date("d-m-Y",$value['updated_time']);
            $result['data'][$key]['action']         = '<a href="'.BASEURL.'Customer/UpdateAlcoholEdit?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>';
        }

        //echo "<pre>"; print_r($result);; die;
        if($count==0){
            $result['data'] = array();
        }

        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }


    public function getPurchaseAlcoholById($id) {
      
      $purcha_dtls      = $this->callSql("SELECT id,order_id,purchase_id, volume FROM customer_purchase_details WHERE id = '$id' ","row");
      $item_id          = $this->callSql("SELECT inventory_id, balance, expiry_date FROM customer_purchases WHERE id = '".$purcha_dtls['purchase_id']."' ","row");

      $data                 = $this->callSql("SELECT * FROM inventory WHERE id = '".$item_id['inventory_id']."' ","row");

      $data = json_decode($item_id['expiry_date'], true);

            $idToFind = $id;
            $expiryDate = '';

            foreach ($data as $item) {
                if ($item['id'] == $idToFind) {
                    $expiryDate = $item['expiry_date'];
                    break;
                }
            }

      $data['inv_id']       = $item_id['inventory_id'];
      $data['volume']       = $purcha_dtls['volume'];
      $data['balance']      = $item_id['balance'];
      $data['expiry_date']  = $expiryDate;
      $data['edit_id']      = $purcha_dtls['id'];

      // print_r($data);die();

      return $data;

    }

    public function checkPurchaseDetailsById($purchase_details_id)
    {
        $purchase_dtls   = $this->callsql("SELECT `id`, `order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status` FROM customer_purchase_details WHERE id = '$purchase_details_id' AND status =1 ORDER BY id DESC LIMIT 1 ",'row'); 
        if(empty($purchase_dtls))
        {
            $purchase_dtls = [];
        }
        return $purchase_dtls;
    }

    public function UpdateAlcohol($params){
        $time = time();

        $name     = $params['name'];
        $volume   = $params['volume'];
        $expiry   = !empty($params['expiry']) ? date("Y-m-d", strtotime($params['expiry'])) : '';
        $balance  = $params['balance'];
        $id       = $params['edit_id'];

        $purcha_dtls  = $this->callSql("SELECT order_id,purchase_id FROM customer_purchase_details WHERE id = '$id' ","row");
        $initial_balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$purcha_dtls['purchase_id']."' ","value");

        if($balance == $initial_balance){

            $purchase_dtls   = $this->callsql("SELECT `id`, `order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status` FROM customer_purchase_details WHERE id = '$id' ",'row');  


            if($volume >= $purchase_dtls['volume']){
                return false;
            }
            $this->query("UPDATE `customer_purchase_details` SET `status`='0' WHERE id = '$id'");
            $this->execute();

            $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$purchase_dtls['order_id']."','".$purchase_dtls['purchase_id']."', '".$volume."', '".$signature."', '".$this->adminID."','".$time."', '1','".$id."')" );
            $this->execute();

            if(!empty($params['expiry'])){
                $this->query("UPDATE customer_purchases SET expiry_date = '".strtotime($params['expiry'])."' WHERE id='".$purchase_dtls['purchase_id']."' " );
                $this->execute();
            }

            if($volume == '0') {

                $this->updateBottlebalance(['purchase_id'=>$purchase_dtls['purchase_id']]);

            }
            
            $activityParams             = [];
            $activityParams['activity'] = "Alcohol volume updated to ".$volume.'.id-'.$id;
            $activityParams['admin_id'] = $data['admin_id'];
        }
        else if($balance != $initial_balance){
            $tot            = $this->callsql("SELECT id, purchase_id FROM customer_purchase_details WHERE id = '".$id."' AND volume='".$volume."' AND status=1",'rows');

            $purchase_ids   = array_unique(array_column($tot, 'purchase_id'));

            if(empty($tot)){
                return false;
            }
            if($balance <= count($tot)){

                $tot_data   = $this->callsql("SELECT id, order_id, purchase_id, signature FROM customer_purchase_details WHERE purchase_id  IN('".implode(',',$purchase_ids)."') AND volume='".$volume."' AND status=1 ORDER BY id ASC LIMIT $balance",'rows');
                
                if(!empty($tot_data)){

                    foreach($tot_data as $key=>$val){
                        $this->query("UPDATE customer_purchase_details SET status='0', signature = '".$signature."' WHERE id='".$val['id']."'");
                        $this->execute();

                        $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$val['order_id']."','".$val['purchase_id']."', '0', '".$signature."', '".$this->userID."','".$time."', '1', '".$val['id']."')" );
                        $this->execute();

                        //deduct bottle count in customer_purchase
                        $this->updateBottlebalance(['purchase_id'=>$val['purchase_id']]);

                    }
                }
            }
            $activityParams = [];
            $activity = "Alcohol count updated to ".$balance.'.id-'.$id;
            $activityParams['admin_id'] = $data['admin_id'];

        }

        $return_data = $this->adminActivityLog($activity);

        return $return_data;
    }

    public function isJson($json) {
        
        $result = json_decode($json);

        
        if ($result === FALSE || empty($result) || !is_array($result)) {
            return false;
        }
        return true;
    }



    public function UpdateAlcoholPercentage($params){
        $time = time();

        $volume   = $params['volume'];
        // $expiry   = !empty($params['expiry']) ? date("Y-m-d", strtotime($params['expiry'])) : '';
        $id       = $params['edit_id'];
        $purchase_dtls  = $this->callSql("SELECT order_id,purchase_id,volume FROM customer_purchase_details WHERE id = '$id' ","row");
        $expire_date      = $this->callSql("SELECT expiry_date FROM customer_purchases WHERE id = '".$purchase_dtls['purchase_id']."' AND expiry_date IS NOT NULL ","value"); 

        $expire_date = !empty($expire_date) ? $expire_date : '';
         
        // if($expire_date == 'null') {
         //     echo "hai";

         // }exit;

        //$expire_date = $expire_date!='null'  ? date('d-m-Y',$expire_date) : '';exit;

        if(!$this->isJson($expire_date)) {

            $purchase_details_ids = $this->callSql("SELECT id FROM customer_purchase_details WHERE purchase_id = '$purchase_dtls[purchase_id]' AND status='1' ORDER BY id ASC ","rows");
            $purchase_details_ids  = array_column($purchase_details_ids,'id');
            $expire_array = [];


            foreach($purchase_details_ids as $key=>$value){

                $expire_array[] =['id'=>$value,'expiry_date'=> $expire_date];

            }

            $expire_array = json_encode($expire_array);

            $this->query("UPDATE customer_purchases SET expiry_date='$expire_array' WHERE  id = '".$purchase_dtls['purchase_id']."'");
            $this->execute();

        }

        if(!empty($params['expiry_date'])) {

            $this->updateExpiry(['new_id'=>$id,'old_id'=>$id,'new_date'=>$params['expiry_date'],'purchase_id'=>$purchase_dtls['purchase_id']]);
            $expiry_id = $id;

        }

        if($volume > $purchase_dtls['volume']){
            return false;
        }
        if($params['volume']!=$purchase_dtls['volume'])
        {
            $this->query("UPDATE `customer_purchase_details` SET `status`='0' WHERE id = '$id'");
            $this->execute();

            $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$purchase_dtls['order_id']."','".$purchase_dtls['purchase_id']."', '".$volume."', '".$this->adminID."','".$time."', '1','".$id."')" );
            $this->execute();

            $new_id = $this->lastInsertId();

            if(!empty($params['expiry_date'])){
                   // $this->query("UPDATE customer_purchases SET expiry_date = '".strtotime($params['expiry'])."' WHERE id='".$purchase_dtls['purchase_id']."' " );
                    //$this->execute();
                $this->updateExpiry(['new_id'=>$new_id,'old_id'=>$id,'purchase_id'=>$purchase_dtls['purchase_id']]);
            }

            if($volume == '0') {

                    $this->updateBottlebalance(['purchase_id'=>$purchase_dtls['purchase_id']]);

            }
                
            $activity = "Alcohol volume updated to ".$volume.'.id-'.$new_id;

            $return_data = $this->adminActivityLog($activity);
            
            $expiry_id = !empty($new_id) ? $new_id : $id;

        }

        $activity = "Alcohol expiry date added".$params['expiry_date'].'.id-'.$expiry_id;

        $return_data = $this->adminActivityLog($activity);

        return $return_data;
    }

    public function updateExpiry($params)
    {

        $old_id      = $params['old_id'];
        $new_id      = $params['new_id'];
        $purchase_id = $params['purchase_id'];
        $new_date = !empty($params['new_date']) ? $params['new_date'] :'';
        $updated_at = time();
        $updated_id = $this->adminID;

        $expiry_date = $this->callsql("SELECT expiry_date FROM customer_purchases WHERE id='$purchase_id'",'value');
        // print_r($expiry_date);die();

        $expiry_date = json_decode($expiry_date,true);
        foreach($expiry_date as $key=>$value){
            if($value['id'] == $old_id) {
                $expiry_date[$key]['id'] = $new_id;
                if(!empty($new_date)) {
                    $expiry_date[$key]['expiry_date'] = $new_date;

                }

            }

        }
        $expiry_date = json_encode($expiry_date);
        $sql = "UPDATE customer_purchases SET expiry_date='$expiry_date',updated_at='$updated_at',updated_id='$updated_id' WHERE id='$purchase_id'";
        $this->query($sql);
        $this->execute();



    }
    public function deleteUpdateAlcohol($ID){
        $this->query("UPDATE customer_inventory SET status=1 WHERE id='$ID'");
        $this->execute();

        return true;
    }

    public function updateBottlebalance($params){

        $purchase_id = $params['purchase_id'];
        $sql = "UPDATE customer_purchases SET balance=balance-1 WHERE id='$purchase_id'";
        $this->query($sql);
        return $this->execute();

    }
}
