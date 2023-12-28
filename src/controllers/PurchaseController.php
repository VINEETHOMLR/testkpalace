<?php

namespace src\controllers;
use inc\Root;
use inc\Controller;
use inc\commonArrays;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Purchase;

class PurchaseController extends Controller {

    public function __construct(){

        parent::__construct();
        
        $this->admin         = $this->admin_id;
        $this->mdl           = (new Purchase);
        $this->pag           =  new Pagination(new Purchase(),'');
       
    }
    
    public function getInputs()
    {
        $input = [];
        $input['datefrom']      = $this->cleanMe(Router::post('datefrom')); 
        $input['dateto']        = $this->cleanMe(Router::post('dateto'));
        $input['username']      = $this->cleanMe(Router::post('customername'));
        $input['user_id']       = $this->cleanMe(Router::post('user_id')); 
        $input['expiry_date']   = $this->cleanMe(Router::post('expiry_date')); 
        $input['room_id']       = $this->cleanMe(Router::post('room_id')); 
        $input['order_id']      = $this->cleanMe(Router::post('order_id')); 
        $input['inventory_id']  = $this->cleanMe(Router::post('inventory_id')); 
        $input['volume']        = $this->cleanMe(Router::post('volume'));

        $input['page']          = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']          = empty($input['page']) ? 0 : 1 ;

        return $input;
    }
     public function actionList() {
        
        $this->checkPageAccess(47);

        $filter               = $this->getInputs();
        if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        }
        if(!empty($filter['room_id'])){
            $filter['room_id']    = $filter['room_id'];
        } 
        $data= $this->mdl->getCustomerPurchase($filter);
        $room_list = $this->mdl->getRoomList();

        $onclick              = "onclick=pageHistory('***')";
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['rooms']      = $room_list;
        $filter['data']       = $data;
        return $this->render('Purchase/customer_purchase',$filter);
    }
    public function actiongetOrderDetails(){

        $id        = $this->cleanMe(Router::get('id'));

        $filter               = $this->getInputs();
        
        $filter['id']    = $id;

        $response['order_id']   = $id;
        
        $response['order_items']    = $this->mdl->getOrderItems($id);
        $response['username']    = $this->mdl->getCustomerbyOrderId($id);

        $response['data']   = $this->mdl->getOrderDetails($filter);
        //return $this->renderJson($response);
        return $this->render('Purchase/customer_purchase_details',$response);

    }


    public function actiongetCustomerOrderDetails(){

        //$id        = $this->cleanMe(Router::post('orderid'));

        $id             = $this->cleanMe(Router::get('id'));
        $filter         = $this->getInputs();
        $filter['id']   = $id;
        $details        = $this->mdl->getOrderDetails($filter);
       // echo "<pre>"; print_r($details);exit;
        //return $this->renderJson($response);

        return $this->renderAjax('Purchase/order_details_modal',['details'=>$details]);



    }

     public function actionCreate() {
        
        $this->checkPageAccess(46);
       
        $data['inventorylist'] = $this->mdl->getInventoryList();
        $data['roomList'] = $this->mdl->getRoomList();

        return $this->render('Purchase/create_customer_purchase',$data);
    }


    public function actiongetItemPriceQty()
    {
        $ip['name']           = $_POST['name'];
        $ip['unit']           = $_POST['unit'];
        $ip['unit_price']     = $_POST['unit_price'];
        $inventory_details = $this->mdl->getItemPriceQty($ip['name']);
        
        if($ip['unit'] > $inventory_details[0]['quantity']){
            return $this->sendMessage('error',$inventory_details[0]['name']." product is out of stock.");
 
         }
    }
    public function actionAddPurchase() {
        
        $this->checkPageAccess(46);
        $ip['user_id']        = $_POST['user_id'];
        $ip['room_id']        = $_POST['room_id'];
        $ip['description']    = $_POST['description'];
        $ip['name']           = $_POST['name'];
        $ip['unit']           = $_POST['unit'];
        $ip['unit_price']     = $_POST['unit_price'];
        $ip['expire_at']    = $_POST['expire_at'];
        

        if(empty($ip['user_id']))
               return $this->sendMessage('error',"Please Enter Username To Proceed");

        if(empty($ip['room_id']))
               return $this->sendMessage('error',"Please Enter Room To Proceed");

        $row_cout = 1;
        
        $nameCounts = array_count_values($ip['name']);

        for($i=0;$i<count($ip['name']);$i++){
            if(empty($ip['name'][$i]))
                return $this->sendMessage('error',"Please Enter Item To Proceed");

            if(empty($ip['unit'][$i]))
                return $this->sendMessage('error',"Please Enter Unit To Proceed");
            if(empty($ip['unit_price'][$i]))
              
                return $this->sendMessage('error',"Please Enter Unit price To Proceed");
        
         $this->isNumeric($ip['unit'][$i],'Unit');

         $inventory_details = $this->mdl->getItemPriceQty($ip['name'][$i]);
         // Check if the same product name is received more than once
        if ($nameCounts[$ip['name'][$i]] > 1) {
            return $this->sendMessage('error', 'Row ' . $row_cout . ': Duplicate product name - ' . $inventory_details[0]['name']);
        }
        // if($ip['unit'][$i] > $inventory_details[0]['quantity']){
            
        //     return $this->sendMessage('error','Row '.$row_cout.' : '.$inventory_details[0]['name']." product is out of stock.");
 
        // }
        $row_cout++;
        $this->isNumeric($ip['unit_price'][$i],'Unit Price');
        
        }
        
        $success = $this->mdl->addCustomer_purchase($ip);

        if($success){
             $this->sendMessage('success',"Added Successfufly");
        }
        else{
            return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
        
    }

    public function actionOrderDetails(){

        $filter               = $this->getInputs();
        if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        }
        if(!empty($filter['room_id'])){
            $filter['room_id']    = $filter['room_id'];
        } 
        $filter['order_id']   = $_GET['id'];

        $data= $this->mdl->getCustomerPurchaseDetails($filter);
        $room_list = $this->mdl->getRoomList();

        $onclick              = "onclick=pageHistory('***')";
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['rooms']      = $room_list;
        $filter['data']       = $data;
        return $this->render('Purchase/customer_purchase_details',$filter);

    }

    public function actionCustomerAlcohol(){

        $this->checkPageAccess(47);


        $filter               = $this->getInputs();

        if(! empty($_GET['user_id'])){
            $filter['user_id']    = $_GET['user_id'];
        }

        if(! empty($_GET['order_id'])){
            $filter['order_id']    = $_GET['order_id'];
        }

        if( ! empty($filter['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($filter['user_id']);
        }

        if(!empty($filter['order_id'])){
            $filter['order_id']    = $filter['order_id'];
        }

         
        $data= $this->mdl->getCustomerAlcohol($filter);
        $room_list = $this->mdl->getRoomList();

        $onclick              = "onclick=pageHistory('***')";
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['rooms']      = $room_list;
        $filter['data']       = $data;
        return $this->render('Purchase/customer_alcohol',$filter);

    }

    public function actionCustomerAlcoholDetails(){

        $this->checkPageAccess(47);

        if(! empty($_GET['user_id'])){
            $filter['s_username']    = $this->mdl->getemail($_GET['user_id']);
        }
        
        $data= $this->mdl->getCustomerAlcohol($filter);

        $onclick              = "onclick=pageHistory('***')";
        $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        $filter['rooms']      = $room_list;
        $filter['data']       = $data;
        return $this->render('Purchase/customer_alcohol',$filter);

    }
            
   
     public function isNumeric($var,$key){
        $numVal = is_numeric($var);
        if($numVal != 1){
            echo $this->sendMessage("error","Please Enter Valid $key To Proceed"); exit;
        }

        if($var < 0){
           echo $this->sendMessage("error","Please Enter Valid $key To Proceed"); exit;
        }
    }

    public function actionUpdateOrderExpirybkp(){
        $id            = cleanMe($_POST['id']);
        $action        = 0;
        
        $purchase_id       = $_POST['purchase_id'];
        $expire_at     = $_POST['expire_at'];

        $order_status_array = ['0'=>'1','1'=>'2'];
        
        if($action == '0') {
            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = $this->admin;
            $params['purchase_id']    = $purchase_id;
            $params['expire_at']      = $expire_at;
        }else{

            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = '';
            $params['purchase_id']    = $purchase_id;
            $params['expire_at']      = $expire_at;
        }
        
        $params['id']            = $id;
        $response = $this->mdl->updateExpiryDate($params);
        $actionArray = ['0'=>'Approve','1'=>'Reject'];
        if($response) {
            $msg = "Successfully updated the order";
            return $this->sendMessage('success',$msg);
            die();
        }else{
            $msg = "Failed to update the order";
            return $this->sendMessage('error',$msg);
            die();
        }
        return $this->sendMessage('error',"Something went wrong");
        die();
    }

    public function actionUpdateOrderExpiry(){
        $id            = cleanMe($_POST['id']);
        $action        = 0;
        
        $purchase_id       = $_POST['purchase_id'];
        //$expire_at     = $_POST['expire_at'];

        $order_status_array = ['0'=>'1','1'=>'2'];


        $purchase_ids = $this->mdl->getPurchaseIds($id);
        $expiry_array = [];

        foreach($purchase_ids as $key=>$value){
            
            $purchase_details_ids  = $this->mdl->getPurchaseDetailsIds($value['id']);
            $sub_expiry_array = [];
            foreach($purchase_details_ids as $k=>$v){

                $expiry_date = $_POST['expire_at_'.$value['id'].'_'.$v['id']];
                $sub_expiry_array[]= ['id'=>$v['id'],'expiry_date'=>$expiry_date];

            }
            $expiry_array[$value['id']] = $sub_expiry_array;

       


        }

        


        
        if($action == '0') {
            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = $this->admin;
            $params['purchase_id']    = $purchase_id;
            //$params['expire_at']      = $expire_at;
            $params['expiry_date'] = $expiry_array;
        }else{

            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = '';
            $params['purchase_id']    = $purchase_id;
            //$params['expire_at']      = $expire_at;
            $params['expiry_date'] = $expiry_array;
        }
        
        $params['id']            = $id;
        //$response = $this->mdl->updateExpiryDate($params);
        $response = $this->mdl->updateExpiryData($params);
        $actionArray = ['0'=>'Approve','1'=>'Reject'];
        if($response) {
            $msg = "Successfully updated the order";
            return $this->sendMessage('success',$msg);
            die();
        }else{
            $msg = "Failed to update the order";
            return $this->sendMessage('error',$msg);
            die();
        }
        return $this->sendMessage('error',"Something went wrong");
        die();
    }

    public function actionExport(){
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        
        $filename         = 'Sales Report'; 

        $user_id         = $this->cleanMe(Router::post('user_id'));
        $datefrom        = $this->cleanMe(Router::post('datefrom'));
        $dateto       = $this->cleanMe(Router::post('dateto')); 
        $room_id         = $this->cleanMe(Router::post('room_id'));
        
        $filter = ["user_id"       => $user_id,
            "datefrom"     => $datefrom,
            "dateto"    => $dateto,
            "room_id"      => $room_id,
            "page"      => '1',
            "export"    =>true
        ];


        $data = $this->mdl->getCustomerPurchaseDetails($filter);


        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;
        
        $csv = "User ID, Username, Order Id, Room, Description, Sales Date \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $val) {
            $html.= $val['uniqueid'].','.$val['customer_name'].','.$val['order_id'].','.$val['room_no'].',"'.$val['description'].'",'.$val['time']."\n"; //Append data to csv
        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Customer Sales List .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }

    public function actionGetItemPrice(){
        $item_id         = $this->cleanMe(Router::post('item_id'));
        echo  $this->mdl->getItemPrice($item_id);


    }


    public function actionUpdateOrderDetails(){
        $id            = cleanMe($_POST['id']);
        $action        = 0;
        
        $purchase_id       = $_POST['purchase_id'];
        $expire_at     = $_POST['expire_at'];

        $order_status_array = ['0'=>'1','1'=>'2'];
        
        if($action == '0') {
            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = $this->admin;
            $params['purchase_id']    = $purchase_id;
            $params['expire_at']      = $expire_at;
        }else{

            $params = [];
            $params['action']         = $action;
            $params['foc_updated_by'] = '';
            $params['purchase_id']    = $purchase_id;
            $params['expire_at']      = $expire_at;
        }
        
        $params['id']            = $id;
        $response = $this->mdl->updateOrderDetails($params);
        $actionArray = ['0'=>'Approve','1'=>'Reject'];
        if($response) {
            $msg = "Successfully updated the order";
            return $this->sendMessage('success',$msg);
            die();
        }else{
            $msg = "Failed to update the order";
            return $this->sendMessage('error',$msg);
            die();
        }
        return $this->sendMessage('error',"Something went wrong");
        die();
    }



    public function actiongetUsedHistory(){
        $id             = $this->cleanMe(Router::get('id'));
        $details        = $this->mdl->getUsedHistory($id);
        //echo "<pre>"; print_r($details);
        return $this->renderAjax('Purchase/used_history_modal',['details'=>$details]);
    }

    public function actionUpdateOrderBalExp(){
        $id             = $_POST['id'];
        //$expire_at      = $_POST['expire_at'];
        $details_ids    = $_POST['details_id'];
        $volume         = $_POST['volume'];
        $balances       = $_POST['balance'];
        $old_balances   = $_POST['old_balance'];
        $purchase_id    = $_POST['purchase_id'];

        $params = [];

        $params['id']           = $id;
        //$params['expire_at']    = $expire_at;
        $params['details_ids']  = $details_ids;
        $params['balances']     = $balances;
        $params['old_balances'] = $old_balances;
        $params['purchase_ids'] = $purchase_id;
        $params['volume'] = $volume;
        $purchase_ids = $purchase_id;
        // $expiry_array = [];

        foreach($balances as $key=>$value){

            $params['purchase_id'] = $key;
            $params['volume'] = $value;
            
            $response = $this->mdl->updateOrderOrderBalExp($params);

        }

        // $params['expiry_date'] = $expiry_array;
        if($response==1) {
            $msg = "Successfully updated the order";
            return $this->sendMessage('success',$msg);
            die();
        }else{
            $msg = "Failed to update the order";
            return $this->sendMessage('error',$msg);
            die();
        }
        return $this->sendMessage('error',"Something went wrong");
        die();

    }

    public function actiongetRoomTable(){
        $type = $this->cleanMe(Router::post('type'));
        $html = '';
        $list = $this->mdl->getRoomTableList($type);

        if($type==1){
            $html = '<option value="">Select Room</option>';

        }
        if($type==2){
            $html = '<option value="">Select Table</option>';

        }

        foreach($list as $lis){

            $html .= '<option value='.$lis['id'].'>'.$lis['description'].'</option>'; 
        }
        echo ($html);
    }

}

