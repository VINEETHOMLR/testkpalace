<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Orderhistory extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "order_history";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];
        $this->statusArray = ['0'=>'Approved','1'=>'Rejected','2'=>'Pending'];
        $this->paymentmodeArray = ['1'=>'Cash','2'=>'Card'];
        $this->focStatusArray = ['0'=>'No','1'=>'Yes'];

        
    }
    
    public function getList($data)
    {


    	$where = ' WHERE id!=0 AND status=2';
    	/*if(!empty($data['status']) ||  in_array($data['status'],['0','1','2'])){
            $where .= " AND status = '$data[status]' ";
        }*/

        if(!empty($data['payment_mode']) ||  in_array($data['payment_mode'],['1','2'])){
            $where .= " AND payment_mode = '$data[payment_mode]' ";
        }
        if(!empty($data['foc']) ||  in_array($data['foc'],['0','1'])){
            $where .= " AND foc = '$data[foc]' ";
        }
        if(!empty($data['customer_id'])) {

        	$where .= " AND customer_id = '$data[customer_id]' ";

        }

        if(!empty($data['createid'])) {

        	$where .= " AND createid = '$data[createid]' ";

        }

        if(!empty($data['from_date']) && !empty($data['to_date'])) {

        	$from_date = strtotime($data['from_date'].' 00:00:00');
        	$to_date   = strtotime($data['to_date'].' 23:59:59');
        	$where .= " AND createtime BETWEEN '$from_date' AND '$to_date'";

        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        
        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT id,customer_id,status,room_id,description,total_amount,createtime,createid,order_status,payment_mode,foc  FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];
        $approve_permission = in_array(65,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $reject_permission  = in_array(66,$this->admin_services) || $this->admin_role == '1' ? true : false;


        foreach ($result['data'] as $key => $value) {

        	$customer_dtls = $this->callsql("SELECT uniqueid,username FROM customer WHERE id='".$value['customer_id']."' ",'row');
        	$created_by = $this->callsql("SELECT username as adminname FROM user WHERE user_id='".$value['createid']."' ",'value');
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$value['room_id']."' ","value");


        	$action = '';
        	if($approve_permission && $value['status']=='2') {

                $action .= '<button class="btn btn-primary" onclick="showApproveModal('.$value['id'].',0)">Approve</button>';

            }
            if($reject_permission && $value['status']=='2') {

                $action .= '<button class="btn btn-danger" onclick="rejectRequest('.$value['id'].',1)">Reject</button>';

            }

            //if($value['status'] == '1' || $value['status'] == '0') {

             	$view_modal = '<button class="btn btn-primary" onclick="showVieweModal('.$value['id'].')">View</button>';

             //}


        	$result['data'][$key]['customer_name']     = $customer_dtls['username'];
            $result['data'][$key]['uniqueid']          = $customer_dtls['uniqueid'];
            $result['data'][$key]['room_no']           = $room_no;
        	$result['data'][$key]['created_by']        = $created_by;
        	$result['data'][$key]['createtime']        = !empty($value['createtime']) ? date('d-m-Y H:i:s',$value['createtime']) : '-';
        	$result['data'][$key]['status']            = $this->statusArray[$value['status']];
        	$result['data'][$key]['foc']               = $this->focStatusArray[$value['foc']];
        	$result['data'][$key]['payment_mode']      = !empty($value['payment_mode']) ? $this->paymentmodeArray[$value['payment_mode']] : '-';
        	$result['data'][$key]['view_modal']        = $view_modal;
            $result['data'][$key]['action']            = $action;
        	$result['data'][$key]['total_amount']      = !empty($value['total_amount']) ? number_format($value['total_amount'],2) : '-';


        }

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;




    }


    public function getFocReport($data)
    {


        $where = ' WHERE id!=0 AND order_status=1';

        if(!empty($data['payment_mode']) ||  in_array($data['payment_mode'],['1','2'])){
            $where .= " AND payment_mode = '$data[payment_mode]' ";
        }
        if(!empty($data['foc']) ||  in_array($data['foc'],['0','1'])){
            $where .= " AND foc = '$data[foc]' ";
        }
        if(!empty($data['customer_id'])) {

            $where .= " AND customer_id = '$data[customer_id]' ";

        }
        if(!empty($data['item_name'])) {

            $where .= " AND id IN(SELECT customer_purchases.order_id FROM customer_purchases WHERE inventory_id IN(SELECT inventory.id FROM inventory WHERE item_name LIKE '%".$data['item_name']."%'))";

        }

        if(!empty($data['createid'])) {

            $where .= " AND createid = '$data[createid]' ";

        }

        if(!empty($data['from_date']) && !empty($data['to_date'])) {

            $from_date = strtotime($data['from_date'].' 00:00:00');
            $to_date   = strtotime($data['to_date'].' 23:59:59');
            $where .= " AND createtime BETWEEN '$from_date' AND '$to_date'";

        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        
        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT id,customer_id,status,room_id,description,total_amount,createtime,createid,order_status,payment_mode,foc,updated_at  FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];

        foreach ($result['data'] as $key => $value) {

            $customer_dtls = $this->callsql("SELECT uniqueid,username FROM customer WHERE id='".$value['customer_id']."' ",'row');
            $created_by = $this->callsql("SELECT username as adminname FROM user WHERE user_id='".$value['createid']."' ",'value');
            $created_by_id = $this->callsql("SELECT staff_id FROM user WHERE user_id='".$value['createid']."' ",'value');
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$value['room_id']."' ","value");

            $customer_purchase_dtls = $this->callsql("SELECT unit,inventory_id FROM `customer_purchases` WHERE order_id = '".$value['id']."' ","row");
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$value['room_id']."' ","value");
            
            $item_name = $this->callsql("SELECT name FROM `inventory` WHERE id = '".$customer_purchase_dtls['inventory_id']."' ","value");
            // if($value['updated_by']==2)
            // {
            //         $updated_name = $this->callsql("SELECT username FROM user WHERE user_id = '$value[foc_updated_by]' ","value");
            // }else{
            //         $updated_name = $this->callsql("SELECT username FROM admin WHERE id = '$value[foc_updated_by]' ","value");
            // }
            
            $result['data'][$key]['updated_name']  = !empty($updated_name) ? $updated_name : '-';



            $result['data'][$key]['inventory_id']      = $customer_purchase_dtls['inventory_id'];
            $result['data'][$key]['customer_name']     = $customer_dtls['username'];
            $result['data'][$key]['uniqueid']          = $customer_dtls['uniqueid'];
            $result['data'][$key]['staff_name']        = $created_by;
            $result['data'][$key]['staff_id']          = $created_by_id;
            $result['data'][$key]['quantity']          = $customer_purchase_dtls['unit'];
            $result['data'][$key]['item_name']         = $item_name;
            $result['data'][$key]['item_name']         = $item_name;
            $result['data'][$key]['room_no']           = $room_no;
            $result['data'][$key]['total_amount']      = !empty($value['total_amount']) ? number_format($value['total_amount'],2) : '-';
            $result['data'][$key]['createtime']        = !empty($value['createtime']) ? date('d-m-Y H:i:s',$value['createtime']) : '-';
            $result['data'][$key]['updated_at']        = !empty($value['updated_at']) ? date('d-m-Y H:i:s',$value['updated_at']) : '-';
            $result['data'][$key]['status']            = $this->statusArray[$value['status']];
            $result['data'][$key]['foc']               = $this->focStatusArray[$value['foc']];


        }

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;




    }

    

    public function getDetails($id)
    {


        return $this->callsql("SELECT *  FROM $this->tableName WHERE id='$id'",'row');

    }

    public function getcustomername($id)
    {

    	
    	return $customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id='$id'",'value');

    }

    public function getAdminname($id)
    {

    	return $this->callsql("SELECT username as adminname FROM user WHERE user_id='$id'",'value');

    }

    public function  updatePurchase($params)
    {

        $id                 = $params['id'];
        $status             = $params['action'];
        $payment_mode       = $params['payment_mode'];
        $foc                = $params['foc'];
        $foc_remark_id      = $params['foc_remark_id'];
        $foc_updated_by     = $params['foc_updated_by'];
        $order_status       = $params['order_status'];
        //$expire_at          = $params['expire_at'];
        $purchase_id        = $params['purchase_id'];
        $updated_by         = $this->adminID;
        $updated_at         = time();
        $sql = "UPDATE $this->tableName SET status='$status',payment_mode='$payment_mode',foc='$foc',foc_remark_id='$foc_remark_id',foc_updated_by='$foc_updated_by',order_status='$order_status',updated_by='$updated_by',updated_at='$updated_at',user_type='1' WHERE id='$id'";

        $this->query($sql);
        $this->execute();

        $params['purchase_id'] = $purchase_id;
        //$params['expire_at'] = $expire_at;
        
        if($status==0)
        {
            //$this->updateExpiryDate($params);

            $customer_purchases = $this->callsql("SELECT id,order_id,inventory_id,unit FROM customer_purchases WHERE order_id='$id'",'rows');

            foreach($customer_purchases as $key=>$value)
            {
                
                $param = [];
                $param['count']       = $value['unit'];
                $param['purchase_id'] = $value['id'];
                $param['order_id']    = $value['order_id'];
                $param['updated_id']  = $this->adminID;
                $param['volume']      = '100';
                $this->addcustomer_purchase_details($param); //add entries into customer_purchase_details table

                

            }

            //add expiry

            $this->addExpiryDate($params['expiry_array']);


          

            $inv_ids = $this->callsql("SELECT inventory_id,unit  FROM customer_purchases WHERE order_id='".$params['id']."' ",'rows');
            foreach($inv_ids as $key => $val){

                $initial_stock = $this->callsql("SELECT quantity  FROM inventory WHERE id='".$val['inventory_id']."' ",'value');

                $new_qty = $initial_stock - $val['unit'];

                $this->query("UPDATE inventory SET `quantity`='".$new_qty."' WHERE `id`='".$val['inventory_id']."'");
                $this->execute();

                $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`) VALUES ('".$val['inventory_id']."','".$val['unit']."','$updated_at',2,1,'$this->adminID','$updated_at')");
                $this->execute();
            }
        }
        
        if($status==1)
        {

            $sql_cs_purchase =  "UPDATE customer_purchases SET status='1' WHERE order_id='$id'";
            
            $this->query($sql_cs_purchase);
            $this->execute();
        }
        


        $actionArray = ['0'=>'Approved','1'=>'Reject'];
        $activity = "Admin ".$actionArray[$params['action']]." the order.Id - ".$id;
        return $this->adminActivityLog($activity);
        

    }

    public function addcustomer_purchase_details($data)
    {
        $count       = $data['count'];
        $purchase_id = $data['purchase_id'];
        $order_id    = $data['order_id'];
        $updated_id  = $data['updated_id'];
        $volume      = $data['volume'];
        $sqlQuery = "INSERT INTO customer_purchase_details (order_id, purchase_id,volume,updated_id,updated_time,status) VALUES "; 
        $volume = '100';
        $status = '1';
        $updated_time = time();
        $signature = '';

        for($i=0;$i<$count;$i++)
        {

            $sqlQuery .="(".$order_id.", '".$purchase_id."', '".$volume."', '".$updated_id."', '".$updated_time."', '".$status."'),";


        }

        $sqlQuery = rtrim($sqlQuery, ',');

        
        $this->query($sqlQuery);
        $this->execute();


    }

    public function getOrderDetails($id)
    {

        $details = $this->callsql("SELECT id,customer_id,total_amount,status,createid,createtime,order_status,payment_mode,foc,foc_remark_id FROM $this->tableName WHERE id='$id'",'row');

        $customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id='$details[customer_id]'",'value');
        $created_by = $this->callsql("SELECT username as adminname FROM user WHERE user_id='$details[createid]'",'value');
        $foc_remark = '-';
        if($details['foc'] == '1' && $details['foc_remark_id']) {

            $foc_remark = $this->callsql("SELECT remark FROM foc_remarks WHERE id='$details[foc_remark_id]'",'value');

        }
        $details['customer_name'] = $customer_name;
        $details['created_by']    = $created_by;
        $details['createtime']    = !empty($details['createtime']) ? date('d-m-Y H:i:s',$details['createtime']) : '-';
        $details['status']        = $this->statusArray[$details['status']];
        $details['foc']           = $this->focStatusArray[$details['foc']];
            $details['payment_mode']  = !empty($details['payment_mode']) ? $this->paymentmodeArray[$details['payment_mode']] : '-';

        $details['foc_remark']        = $foc_remark;  



        

        return $details;  



    }

    public function isJson($json) {
        
        $result = json_decode($json);

        
        if ($result === FALSE || empty($result) || !is_array($result)) {
            return false;
        }
        return true;
    }

    public function getApprovalDetails($id){

        $details = $this->callsql("SELECT id, customer_id, total_amount,status,createid,createtime,order_status,payment_mode,foc,foc_remark_id FROM $this->tableName WHERE id='$id'",'row');



        $customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id='$details[customer_id]'",'value');
        $created_by = $this->callsql("SELECT username as adminname FROM user WHERE user_id='$details[createid]'",'value');
        $foc_remark = '-';
        if($details['foc'] == '1' && $details['foc_remark_id']) {
            $foc_remark = $this->callsql("SELECT remark FROM foc_remarks WHERE id='$details[foc_remark_id]'",'value');
        }
        $details   = $this->callsql("SELECT id, inventory_id, item_name, unit, unit_price FROM customer_purchases WHERE order_id='$id'",'rows');

   
        return $details;

    }

    public function getCustomerOrderDetails($id){
        $foc_remark = '-';
        $where = ' WHERE id!=0 AND order_id="'.$id.'" AND status = 0';

        $orderdet  = $this->callsql("SELECT * FROM `customer_purchases` $where ORDER BY id DESC","rows");
        foreach ($orderdet as $key => $value) {
            $ordr  = $this->callsql("SELECT createtime, total_amount, customer_id, foc, foc_remark_id, payment_mode FROM `order_history` WHERE id ='".$id." '","row");
            $orderdet[$key]['time'] = date("d-m-Y h:i A",$ordr['createtime']);
            $orderdet[$key]['purchase_id'] = $value['id'];
            $orderdet[$key]['total_amount'] = number_format($ordr['total_amount'],2);
            $orderdet[$key]['unit_total_amount'] = $value['unit'] * $value['unit_price'];


            if($ordr['foc'] == '1' && $ordr['foc_remark_id']) {
                $foc_remark = $this->callsql("SELECT remark FROM foc_remarks WHERE id='$ordr[foc_remark_id]'",'value');
            }
            $orderdet[$key]['foc']           = $this->focStatusArray[$ordr['foc']];
            $orderdet[$key]['payment_mode']  = !empty($ordr['payment_mode']) ? $this->paymentmodeArray[$ordr['payment_mode']] : '-';
            $orderdet[$key]['foc_remark']        = $foc_remark;



      

            $is_validjson = $this->isJson($value['expiry_date']);
            if($is_validjson) {

                $orderdet[$key]['expiry_date'] = json_decode($value['expiry_date'],true); 

            }else{

                
                $expiry_date   = $this->callsql("SELECT id FROM `customer_purchase_details` WHERE purchase_id ='".$value['id']."' AND status=1","rows");
                foreach($expiry_date as $k=>$v){
                    $expiry_date[$k]['expiry_date'] = "";

                }

                $orderdet[$key]['expiry_date'] = $expiry_date;
              

            }
            
        }

        

        return $orderdet;
    }

    
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        return $this->execute();
        
    }

    public function addExpiryDate($data){

        $updated_by         = $this->adminID;
        $updated_at         = time();

        
       
        foreach($data as $key=>$value){
            
             
            $purchase_id = $value['purchase_id'];
            $customer_purchase_details_ids = $this->callsql("SELECT id FROM customer_purchase_details WHERE purchase_id='$purchase_id' AND status='1'",'rows');
            
            $expireArray = [];

            $customer_purchase_details_ids = array_column($customer_purchase_details_ids,'id');

            foreach($value['expiry_date'] as $k1=>$v1){
                
                $expireArray[] = ['id'=>$customer_purchase_details_ids[$k1],'expiry_date'=>$v1];


            }

            $expireArray = json_encode($expireArray);
            $sql = "UPDATE customer_purchases SET expiry_date='$expireArray' WHERE id='$purchase_id' ";
            $this->query($sql);
            $this->execute();


        }

        

        // exit;

        // for($i=0; $i<sizeof($data['expire_at']); $i++){
        //     $sql = "UPDATE customer_purchases SET expiry_date='".strtotime($data['expire_at'][$i])."', updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='".$data['purchase_id'][$i]."' ";
        //     $this->query($sql);
        //     $this->execute();
        // }
        // return true;
    }
     
     

    

    

    
}
