<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class Purchase extends Database {

    public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "customer_purchases";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        
    }
    
    

    public function getCustomerPurchase($data){

       $where = ' WHERE id!=0 AND status = 0 AND order_status=1';


        if(!empty($data['user_id'])){
            $where .= " AND customer_id = '$data[user_id]' ";
        }    

        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND createtime BETWEEN '$date_from' AND '$date_to' ";
        }

        if(!empty($data['room_id'])){
            $where .= " AND room_id = '".$data['room_id']."' ";
        }  

        $pagecount = ($data['page'] - 1) * $this->perPage;
        $count = $this->callsql("SELECT COUNT(id) FROM order_history $where ","value");
        $result['data'] = $this->callsql("SELECT id,customer_id,room_id,description,createtime,user_type,updated_by FROM `order_history` $where ORDER BY id DESC LIMIT $pagecount,$this->perPage ","rows");

        foreach ($result['data'] as $key => $val) {
            // $purchases  = $this->callsql("SELECT id,order_id FROM `customer_purchases` $where GROUP BY order_id","rows");
            $customer   = $this->callsql("SELECT username,uniqueid FROM `customer` WHERE id= '$val[customer_id]' ","row");
            $room_no  = $this->callsql("SELECT type, room_no FROM `room` WHERE id = '".$val['room_id']."' ","row");

             if($val['user_type']==2)
            {
                $updated_name = $this->callsql("SELECT username FROM user WHERE user_id = '$val[updated_by]' ","value");
            }else{
                $updated_name = $this->callsql("SELECT username FROM admin WHERE id = '$val[updated_by]' ","value");
            }
            $roomTable_type = '';
            if(!empty($room_no['type'])){
                $roomTable_type = ($room_no['type']==1) ? 'Room- ' : 'Table- ';
            }

            $result['data'][$key]['uniqueid']       = $customer['uniqueid'];
            $result['data'][$key]['customer_name']  = $customer['username'];
            $result['data'][$key]['order_id']       = $val['id'];
            $result['data'][$key]['room_no']        = !empty($room_no['room_no']) ? $roomTable_type.$room_no['room_no'] : '';
            $result['data'][$key]['time']           = date("d-m-Y H:i:s",$val['createtime']);
            $result['data'][$key]['updated_name']   = !empty($updated_name)?$updated_name :'';

        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }
    public function getOrderDetails($data){
        $order_total = 0;
        //$where = ' WHERE id!=0 AND order_id="'.$data["id"].'" AND status = 0 AND id IN (SELECT purchase_id FROM customer_purchase_details where customer_purchases.id = customer_purchase_details.purchase_id AND customer_purchase_details.status = 1 )';

        $where = ' WHERE id!=0 AND order_id="'.$data["id"].'" AND status = 0';

        if(!empty($data['inventory_id'])){
            $where .= " AND inventory_id = '".$data['inventory_id']."' ";
        }
        $orderdet  = $this->callsql("SELECT `id`, `order_id`, `inventory_id`, `item_name`, `unit`, `unit_price`, `createtime`, `createip`, `createid`, `status`, `expiry_date`, `updated_id`, `updated_at`, `balance` FROM `customer_purchases` $where ORDER BY id DESC","rows");



        foreach ($orderdet as $key => $value) {
            $ordr  = $this->callsql("SELECT createtime, total_amount, customer_id FROM `order_history` WHERE id ='".$data['id']." '","row");

            $orderdet[$key]['time']                 = date("d-m-Y h:i A",$ordr['createtime']);
            $orderdet[$key]['total_amount']         = number_format($ordr['total_amount'],2);
            $orderdet[$key]['unit_total_amount']    = $value['unit'] * $value['unit_price'];
            $order_total                            = $order_total + ($value['unit'] * $value['unit_price']);

            
            $orderdet[$key]['balance']  = $this->callsql("SELECT id as details_id, volume FROM `customer_purchase_details` WHERE purchase_id ='".$value['id']."' AND status=1 AND volume !=0 ","rows");
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
        //echo "<pre>"; print_r($orderdet); die;
        $order_total                = $order_total;

        $result['orderList']         = $orderdet;
        $result['order_total']      = $order_total;

  
        return $result;

    }

    public function getPurchaseIds($order_id)
    {

        $sql = "SELECT id FROM customer_purchases WHERE order_id='$order_id'";
        return $this->callsql($sql,'rows');


    }

    public function getPurchaseDetailsIds($purchase_id){
        $sql = "SELECT id FROM customer_purchase_details WHERE purchase_id='$purchase_id' AND status='1'";
        return $this->callsql($sql,'rows');

    }

    public function isJson($json) {
        
        $result = json_decode($json);


        
        if ($result === FALSE || empty($result) || !is_array($result)) {
            return false;
        }
        return true;
    }

    public function getOrderItems($id){
        $inventory_list            = $this->callsql("SELECT inventory_id,item_name FROM `customer_purchases` WHERE order_id=$id AND status = 0 ORDER BY inventory_id ASC","rows");
        return $inventory_list;
    }
    public function getInventoryList(){
    
        $inventory            = $this->callsql("SELECT id,name FROM `inventory` WHERE status =0 ORDER BY name ASC","rows");

        return $inventory;
    }

    function getemail($user_id){

       return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");
   }

   function getUsername($user_id){

       return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");
   }

   public function getItemPriceQty($id){
        return $this->callsql("SELECT id, name, quantity, price  FROM inventory WHERE status=0 AND id='".$id."' ORDER BY id DESC ","rows");
    }
  
    public function addCustomer_purchase($ip) {

        $time       = time();
        $user_id    = $ip['user_id'];
        $room_id    = $ip['room_id'];
        $description = $ip['description'];
        $ip_address = $this->IP;

        $total_amount = 0;
        $m=0;

        for($i=0;$i<count($ip['unit']);$i++){
        
        $unit          = $ip['unit'][$i];
        $unit_price    =$ip['unit_price'][$i];

        $total_amount = $total_amount+( $unit * $unit_price);
  
        }

        $this->callSql("INSERT INTO `order_history`(`customer_id`,`room_id`,`total_amount`,`description`,`status`,`createtime`, `createip`,`createid`,`order_status`,`updated_by`) VALUES ('$user_id','$room_id','$total_amount','$description','0','$time','$ip_address','$this->adminID ','1','$this->adminID')");

        $order_id = $this->lastInsertId();

        for($i=0;$i<count($ip['name']);$i++){
        
            $inv_id        = $ip['name'][$i];
            $unit          = $ip['unit'][$i];
            $unit_price    = $ip['unit_price'][$i];
            $item_name  = $this->callsql("SELECT name FROM inventory WHERE id='$inv_id'","value");
           
            $this->callSql("INSERT INTO `customer_purchases`(`order_id`, `inventory_id`,`item_name`, `unit`, `unit_price`,`createtime`, `createip`, `createid`, `balance`) VALUES ('$order_id','$inv_id','$item_name','$unit','$unit_price','$time','$ip_address','$this->adminID', '$unit' )");
            $purchase_id = $this->lastInsertId();

            for($j=1;$j<=$ip['unit'][$i];$j++){
                $this->callSql("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`) VALUES ('$order_id','$purchase_id','100','$this->adminID','$time','1')");
            }

            $inventory_qty = $this->callsql("SELECT quantity FROM `inventory` WHERE id ='$inv_id'","value");

            $new_qty = $inventory_qty - $unit;

            $this->query("UPDATE inventory SET `quantity`='".$new_qty."' WHERE `id`='".$inv_id."'");
            $this->execute();

            $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`, `ref_id`) VALUES ('$inv_id', '".$unit."', '$time', 2, 1, '$this->adminID', '$time', '$purchase_id')");
            $this->execute();  
        }

        $purchase_ids = $this->getPurchaseIds($order_id);
            
        foreach($purchase_ids as $key=>$value){
                $purchase_details_ids   = $this->getPurchaseDetailsIds($value['id']);
                $sub_expiry_array       = [];
                foreach($purchase_details_ids as $k=>$v){

                    $expiry_date = $ip['expire_at'][$m];
                $sub_expiry_array[]= ['id'=>$v['id'],'expiry_date'=>$expiry_date];

            }
            
            $expiry_array[$value['id']] = $sub_expiry_array;
            $m++;
        }
            $exp_data['expiry_date']    = $expiry_array;


        $this->updateExpiryData($exp_data);

        

        return true;

     }


         public function adminActivityLog($activity){

        $time=time();
        $this->query("INSERT INTO admin_activity_log SET admin_id ='$this->adminID' , action ='$activity' , createtime= '$time' , createip='$this->IP' ");
        $this->execute();

        return true;
    }

    public function getRoomList(){
        $roomList            = $this->callsql("SELECT * FROM `room` WHERE status =0 AND type=1 ORDER BY room_no ASC","rows");
        return $roomList;
    }

    public function getCustomerPurchaseDetails($data){
//echo "<pre>";print_r($data); echo "</pre>"; die;
        $where = ' WHERE id!=0 AND status = 0';

        if(!empty($data['order_id'])){
            $where .= " AND id = '".$data['order_id']."' ";
        }

        if(!empty($data['user_id'])){
            $where .= " AND customer_id = '".$data['user_id']."' ";
        }    

        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $date_from = strtotime($data['datefrom']." 00:00:00");
            $date_to   = strtotime($data['dateto']." 23:59:59");

            $where    .= " AND createtime BETWEEN '$date_from' AND '$date_to' ";
        }

        if(!empty($data['room_id'])){
            $where .= " AND room_id = '".$data['room_id']."' ";
        }  

        $pagecount = ($data['page'] - 1) * $this->perPage;
        $count = $this->callsql("SELECT COUNT(id) FROM order_history $where ","value");
        $result['data'] = $this->callsql("SELECT id,customer_id,room_id,description,createtime FROM `order_history` $where ORDER BY id DESC LIMIT $pagecount,$this->perPage ","rows");

        foreach ($result['data'] as $key => $val) {
            // $purchases  = $this->callsql("SELECT id,order_id FROM `customer_purchases` $where GROUP BY order_id","rows");
            $customer   = $this->callsql("SELECT username,uniqueid FROM `customer` WHERE id= '$val[customer_id]' ","row");
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$val['room_id']."' ","row");

            $result['data'][$key]['uniqueid']       = $customer['uniqueid'];
            $result['data'][$key]['customer_name']  = $customer['username'];
            $result['data'][$key]['order_id']       = $val['id'];
            $result['data'][$key]['room_no']        = !empty($room_no['room_no']) ? $room_no['room_no'] : '-'  ;
            $result['data'][$key]['time']           = date("d-m-Y H:i:s",$val['createtime']);
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;

    }

    public function getCustomerbyOrderId($id){
        $user_id = $this->callsql("SELECT customer_id FROM order_history WHERE id=$id","value");
        return $this->callsql("SELECT username FROM customer WHERE id=$user_id","value");

    }

    public function getCustomerAlcohol($data){
        $where = ' WHERE id!=0 AND status = 1 AND volume !=0 ';

        if(!empty($data['user_id'])){
            $order_id = $this->callsql("SELECT id FROM `order_history` WHERE customer_id = '".$data['user_id']."' ","rows");
            $order_ids = array_column($order_id, 'id');
            if(!empty($order_ids)){
                $where .= " AND order_id IN(".implode(',',$order_ids).")";
            }
            if(empty($order_ids)){
                return false;
            }

        } 
        if(!empty($data['order_id'])){
            $where .= " AND order_id = '".$data['order_id']."' ";

        } 

        if(!empty($data['volume'])){
            $where .= " AND volume = '".$data['volume']."' ";
        }

         

        $pagecount = ($data['page'] - 1) * $this->perPage;
        $count = $this->callsql("SELECT COUNT(id) FROM customer_purchase_details $where ","value");
        $result['data'] = $this->callsql("SELECT * FROM `customer_purchase_details` $where ORDER BY order_id DESC LIMIT $pagecount,$this->perPage ","rows");

        foreach ($result['data'] as $key => $val) {
            $order_history  = $this->callsql("SELECT id, customer_id, room_id FROM `order_history` WHERE id= '".$val['order_id']."' ","row");
            $purchases  = $this->callsql("SELECT id,order_id, item_name,expiry_date FROM `customer_purchases` WHERE id= '".$val['purchase_id']."' GROUP BY order_id","row");
            $customer   = $this->callsql("SELECT username,uniqueid FROM `customer` WHERE id= '".$order_history['customer_id']."' ","row");
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$order_history['room_id']."' ","row");
            $expiryDate = '';
            if(!empty($purchases['expiry_date'])){
                $is_validjson = $this->isJson($purchases['expiry_date']);
                if($is_validjson)
                {
                    $expiry = json_decode($purchases['expiry_date'], true);

                    $idToFind = $val['id'];
                    $expiryDate = '';

                    foreach ($expiry as $item) {
                        if ($item['id'] == $idToFind) {
                            $expiryDate = $item['expiry_date'];
                            break;
                        }
                    }
                }else{
                    $expiryDate = !empty($purchases['expiry_date']) ? date('d-m-Y',$purchases['expiry_date']) : '';
                    if($expiryDate==false){
                        $expiryDate = '';
                    }

                }
            }

            $result['data'][$key]['uniqueid']       = $customer['uniqueid'];
            $result['data'][$key]['order_id']       = $val['order_id'];
            $result['data'][$key]['customer_name']  = $customer['username'];
            $result['data'][$key]['item_name']      = $purchases['item_name'] ?? '';
            $result['data'][$key]['expiry_date']    = $expiryDate;
            $result['data'][$key]['room_no']        = $room_no['room_no'];
            $result['data'][$key]['time']           = date("d-m-Y H:i:s",$val['updated_time']);
            $result['data'][$key]['action']         = '<a href="'.BASEURL.'Customer/UpdateAlcoholEdit?id='.$val['id'].'"><button class="btn btn-info">Edit</button></a>';
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;

        return $result;
    }

    public function updateExpiryDate($data){

        $updated_by         = $this->adminID;
        $updated_at         = time();

        for($i=0; $i<sizeof($data['expire_at']); $i++){
            $sql = "UPDATE customer_purchases SET expiry_date='".strtotime($data['expire_at'][$i])."', updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='".$data['purchase_id'][$i]."' ";
            $this->query($sql);
            $this->execute();
        }
        return true;
    }

    public function updateExpiryData($data)
    {

        $updated_by         = $this->adminID;
        $updated_at         = time();

        foreach ($data['expiry_date'] as $key => $value) {
            $purchase_id = $key;
            $expiry_date = json_encode($value);
            $sql = "UPDATE customer_purchases SET expiry_date='$expiry_date',updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='$purchase_id'";
            $this->query($sql);
            $this->execute();
        }
        return true;

      

    }

    public function getItemPrice($item_id){
            return $item_price = $this->callsql("SELECT price FROM `inventory` WHERE id = '".$item_id."' ","value");


    }

    public function updateOrderDetails($data){

        $updated_by         = $this->adminID;
        $updated_at         = time();

        /*for($i=0; $i<sizeof($data['expire_at']); $i++){
            $sql = "UPDATE customer_purchases SET expiry_date='".strtotime($data['expire_at'][$i])."', updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='".$data['purchase_id'][$i]."' ";
            $this->query($sql);
            $this->execute();
        }
        return true;*/



        for($i=0; $i<sizeof($data['expire_at']); $i++){
            $purchase_dtls = $this->callSql("SELECT order_id, purchase_id FROM customer_purchase_details WHERE id = '".$data['purchase_id'][$i]."' ","row");
            $initial_balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$data['purchase_id'][$i]."' ","value");

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
                $activity = "Alcohol volume updated to ".$volume.'.id-'.$id;
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

                            $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `signature`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$val['order_id']."','".$val['purchase_id']."', '0', '".$signature."', '".$this->adminID."','".$time."', '1','".$val['id']."')" );
                            $this->execute();

                            //deduct bottle count in customer_purchase
                            $this->updateBottlebalance(['purchase_id'=>$val['purchase_id']]);
                        }
                    }
                }
                $activity = "Alcohol count updated to ".$balance.'.id-'.$id;
            }
        }

        $return_data = $this->adminActivityLog($activity);

    }

    public function getUsedHistory($id){

        $result['data'] = $this->callsql("SELECT * FROM `customer_purchase_details` WHERE order_id = '".$id."' ORDER BY id DESC","rows");

        $total['volume_100']     = $this->callsql("SELECT count(id) FROM `customer_purchase_details` WHERE order_id = '".$id."' AND volume=100 AND status=1 ORDER BY id DESC","value");
        $total['volume_75']     = $this->callsql("SELECT count(id) FROM `customer_purchase_details` WHERE order_id = '".$id."' AND volume=75 AND status=1 ORDER BY id DESC","value");
        $total['volume_50']     = $this->callsql("SELECT count(id) FROM `customer_purchase_details` WHERE order_id = '".$id."' AND volume=50 AND status=1 ORDER BY id DESC","value");
        $total['volume_25']     = $this->callsql("SELECT count(id) FROM `customer_purchase_details` WHERE order_id = '".$id."' AND volume=25 AND status=1 ORDER BY id DESC","value");
        $total['volume_0']     = $this->callsql("SELECT count(id) FROM `customer_purchase_details` WHERE order_id = '".$id."' AND volume=0 AND status=1 ORDER BY id DESC","value");

        foreach ($result['data'] as $key => $val) {

            $order_history  = $this->callsql("SELECT id, customer_id, room_id FROM `order_history` WHERE id= '".$val['order_id']."' ","row");
            $purchases  = $this->callsql("SELECT id,order_id, item_name FROM `customer_purchases` WHERE id= '".$val['purchase_id']."' GROUP BY order_id","row");
            $customer   = $this->callsql("SELECT username,uniqueid FROM `customer` WHERE id= '".$order_history['customer_id']."' ","row");
            $room_no  = $this->callsql("SELECT room_no FROM `room` WHERE id = '".$order_history['room_id']."' ","row");
            
            if($val['user_type']==2)
            {
                $updated_name = $this->callsql("SELECT username FROM user WHERE user_id = '$val[updated_id]' ","value");
            }else{
                $updated_name = $this->callsql("SELECT username FROM admin WHERE id = '$val[updated_id]' ","value");
            }

            $image = !empty($val['signature']) ? FRONTEND.'web/upload/sign/'.$val['signature']:'';

            $result['data'][$key]['uniqueid']       = $customer['uniqueid'];
            $result['data'][$key]['order_id']       = $val['order_id'];
            $result['data'][$key]['customer_name']  = $customer['username'];
            $result['data'][$key]['item_name']      = $purchases['item_name'];
            $result['data'][$key]['room_no']        = $room_no['room_no'];
            $result['data'][$key]['time']           = date("d-m-Y H:i:s",$val['updated_time']);
            $result['data'][$key]['signature']      = $image;
            $result['data'][$key]['updated_name']   = $updated_name;



        }
        $result = array_merge($total,$result);
        return $result;
    }


    public function updateOrderOrderBalExpBkp($data){
        $time = time();
        $updated_at = time();
        $updated_by = $this->adminID;
        foreach($data['expire_at'] as $key=>$val){
            $sql = "UPDATE customer_purchases SET expiry_date='".strtotime($val)."', updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='".$data['purchase_ids'][$key]."' "; 
            $this->query($sql);
            $this->execute();
        }
        for($i=0; $i<count($data['details_ids']); $i++){
            foreach($data['details_ids'][$i] as $key=>$val){

                $dtls_data  = $this->callsql("SELECT id, order_id, purchase_id, signature, volume FROM customer_purchase_details WHERE id='".$val."' AND status=1 ORDER BY id ASC",'row');
            
                if($data['balances'][$i][$key] == 0){
                    $vol    = 0;
                }
                else{
                    $vol    = $dtls_data['volume'];
                }
                $old_data   = $this->callsql("SELECT count(id) FROM customer_purchase_details WHERE id='".$val."' AND purchase_id = '".$dtls_data['purchase_id']."' AND volume = '".$dtls_data['volume']."' AND status = 1 ORDER BY id ASC",'value');

                if($data['balances'][$i][$key] != $old_data){
                    $this->query("UPDATE customer_purchase_details SET status='0' WHERE id='".$val."'");
                    $this->execute();
                    
                    $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$dtls_data['order_id']."','".$dtls_data['purchase_id']."', '".$vol."','".$this->adminID."','".$time."', '1','".$val."')" );
                    $this->execute();

                    $this->updateBottlebalance(['purchase_id'=>$dtls_data['purchase_id']]);
                    
                    $balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$dtls_data['purchase_id']."' ","value");

                    $activity = "Alcohol count updated to ".$balance.'.id-'.$val;
                    $return_data = $this->adminActivityLog($activity);
                }
            }
        }
        

        return true;

    }

    public function updateOrderOrderBalExpnewbkp($data){
        $time = time();
        $updated_at = time();
        $updated_by = $this->adminID;
        // foreach($data['expire_at'] as $key=>$val){
        //     $sql = "UPDATE customer_purchases SET expiry_date='".strtotime($val)."', updated_id='".$updated_by."', updated_at='".$updated_at."' WHERE id='".$data['purchase_ids'][$key]."' "; 
        //     $this->query($sql);
        //     $this->execute();
        // }

        // foreach($data['expiry_date'] as $key=>$value){
           
        //    $expiry = json_encode($value);
        //    $sql = "UPDATE customer_purchases SET expiry_date='$expiry' WHERE id='$key'";
        //    $this->query($sql);
        //    $this->execute();
         

        // }
     

        for($i=0; $i<count($data['details_ids']); $i++){
            foreach($data['details_ids'][$i] as $key=>$val){

                $dtls_data  = $this->callsql("SELECT id, order_id, purchase_id, signature, volume FROM customer_purchase_details WHERE id='".$val."' AND status=1 ORDER BY id ASC",'row');
            
                if($data['balances'][$i][$key] == 0){
                    $vol    = 0;
                }
                else{
                    $vol    = $dtls_data['volume'];
                }
                $old_data   = $this->callsql("SELECT count(id) FROM customer_purchase_details WHERE id='".$val."' AND purchase_id = '".$dtls_data['purchase_id']."' AND volume = '".$dtls_data['volume']."' AND status = 1 ORDER BY id ASC",'value');

                if($data['balances'][$i][$key] != $old_data){
                    $this->query("UPDATE customer_purchase_details SET status='0' WHERE id='".$val."'");
                    $this->execute();
                    
                    $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$dtls_data['order_id']."','".$dtls_data['purchase_id']."', '".$vol."','".$this->adminID."','".$time."', '1','".$val."')" );
                    $this->execute();
                    $new_id = $this->lastInsertId();

                    //$this->updateExpiry(['new_id'=>$new_id,'old_id'=>$val,'purchase_id'=>$dtls_data['purchase_id']]);

                    $this->updateBottlebalance(['purchase_id'=>$dtls_data['purchase_id']]);
                    
                    $balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$dtls_data['purchase_id']."' ","value");

                    $activity = "Alcohol count updated to ".$balance.'.id-'.$val;
                    $return_data = $this->adminActivityLog($activity);
                }
            }
        }
        

        return true;

    }

    public function updateOrderOrderBalExp($data){
        
        $time = time();
        $updated_at = time();
        $updated_by = $this->adminID;
        $return_data = false;
        $customer_purchases  = $this->callSql("SELECT order_id,unit,id FROM customer_purchases WHERE id = '".$data['purchase_id']."' ","row");

        $customer_purchases_details = $this->callsql("SELECT * FROM customer_purchase_details WHERE purchase_id = '".$customer_purchases['id']."' AND status = 1 ORDER BY id ASC",'rows');

        $balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$customer_purchases['id']."' ","value");
        foreach($data['volume'] as $volume => $new_count)
        {

            $current_count = $this->callsql("SELECT count(id) FROM customer_purchase_details WHERE purchase_id = '".$data['purchase_id']."' AND volume='".$volume."' AND status = 1 ORDER BY id ASC",'value');

            if($new_count<$current_count)
            {  
                $difference = $current_count-$new_count;
                
                $ids = $this->callsql("SELECT id FROM customer_purchase_details WHERE volume='".$volume."' AND status=1 AND purchase_id='".$data['purchase_id']."' ORDER BY id ASC LIMIT $difference",'rows');

                $this->query("UPDATE customer_purchase_details SET status='0' WHERE volume='".$volume."' AND status=1 AND purchase_id='".$data['purchase_id']."' ORDER BY id ASC LIMIT $difference ");
                $this->execute();
                
               // $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`,`previous_id`) VALUES ('".$customer_purchases['order_id']."','".$data['purchase_id']."', '0','".$this->adminID."','".$time."', '1','".."')" );
                
                $self = $this;  // Assign $this to another variable

                $insertValues = array_map(function($updatedRow) use ($customer_purchases, $data, $self, $time) {
                    $updatedId = $updatedRow['id'];
                    return "('" . $customer_purchases['order_id'] . "','" . $data['purchase_id'] . "', '0','" . $self->adminID . "','" . $time . "', '1','" . $updatedId . "')";
                }, $ids);

                // Join the values and insert them in a single query
                $valuesString = implode(',', $insertValues);
                $this->query("INSERT INTO `customer_purchase_details`(`order_id`, `purchase_id`, `volume`, `updated_id`, `updated_time`, `status`, `previous_id`) VALUES $valuesString");
                $this->execute();


                $sql = "UPDATE customer_purchases SET balance = balance-$difference WHERE id='".$data['purchase_id']."'";
                $this->query($sql);
                $this->execute();
                $balance      = $this->callSql("SELECT balance FROM customer_purchases WHERE id = '".$data['purchase_id']."' ","value");

                $activity = "Alcohol count updated to ".$balance.".id-".$data['purchase_id'];
                $return_data = $this->adminActivityLog($activity);

            }


        }
        return true;

    }

    public function updateExpiry($params)
    {

        $old_id      = $params['old_id'];
        $new_id      = $params['new_id'];
        $purchase_id = $params['purchase_id'];

        $updated_at = time();
        $updated_id = $this->adminID;

        $expiry_date = $this->callsql("SELECT expiry_date FROM customer_purchases WHERE id='$purchase_id'",'value');
        $expiry_date = json_decode($expiry_date,true);
        foreach($expiry_date as $key=>$value){
            if($value['id'] == $old_id) {
                $expiry_date[$key]['id'] = $new_id;

            }

        }
        $expiry_date = json_encode($expiry_date);
        $sql = "UPDATE customer_purchases SET expiry_date='$expiry_date',updated_at='$updated_at',updated_id='$updated_id' WHERE id='$purchase_id'";
        $this->query($sql);
        $this->execute();



    }



    public function updateBottlebalance($params){

        $purchase_id = $params['purchase_id'];
        $sql = "UPDATE customer_purchases SET balance=balance-1 WHERE id='$purchase_id'";
        $this->query($sql);
        return $this->execute();

    }

    public function getRoomTableList($type){
        $tableList       = $this->callsql("SELECT * FROM `room` WHERE status =0 AND type='".$type."' ORDER BY room_no ASC","rows");
        return $tableList;   
    }



}
