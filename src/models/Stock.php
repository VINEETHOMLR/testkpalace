<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class Stock extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "stock";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

    }

    public function getList($data){
    	$where = ' WHERE a.id!=0';

        if(!empty($data['supplier_id'])){
            $where .= " AND a.supplier_id = '".$data['supplier_id']."' ";
        }

        if(!empty($data['contact_person'])){
            $where .= " AND b.contact_person LIKE '%".$data['contact_person']."%' ";
        }

        if(!empty($data['contact_no'])){
            $where .= " AND b.contact_no LIKE '%".$data['contact_no']."%' ";
        }

        if(!empty($data['order_no'])){
            $where .= " AND a.invoice_number LIKE '%".$data['order_no']."%' ";
        }

        if(!empty($data['invoice_date'])){
            $where .= " AND a.invoice_date LIKE '%".strtotime($data['invoice_date'])."%' ";
        }

        if(!empty($data['delivery_date'])){
            $where .= " AND a.delivery_date LIKE '%".strtotime($data['delivery_date'])."%' ";
        }


        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(a.id) FROM $this->tableName a INNER JOIN supplier b ON a.supplier_id = b.id $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT a.*, b.* FROM $this->tableName a INNER JOIN supplier b ON a.supplier_id = b.id $where  ORDER BY a.id DESC ");
        }else{
            $this->query("SELECT a.*, b.* FROM $this->tableName a INNER JOIN supplier b ON a.supplier_id = b.id $where  ORDER BY a.id DESC LIMIT $pagecount,$this->perPage");
        }
        

        $result = ['data' => $this->resultset()];
        
        foreach ($result['data'] as $key => $value) {

            $supplier_dtls = $this->callsql("SELECT supplier_name, contact_person, contact_no FROM supplier WHERE id='".$value['supplier_id']."'",'row'); 

            $action = '';  
            $result['data'][$key]['supplier_name']  = !empty($supplier_dtls) ? $supplier_dtls['supplier_name'] : '-';
            //$result['data'][$key]['contact_no']     = !empty($supplier_dtls) ? $supplier_dtls['contact_no'] : '-';

            $txt = '';
                $excel_txt =  '';
                $is_valid_json = $this->isJson($value['contact_person']);
                
                if($is_valid_json) {

                    $contact_person = json_decode($value['contact_person'],true);
                    $contact_number = json_decode($value['contact_no'],true);

                }else{

                    $contact_person = array($value['contact_person']);
                    $contact_number = array($value['contact_no']);
                    
                }
                for($i=0; $i<count($contact_person); $i++){
                    if($contact_person[$i]!='')
                    {
                        $txt .= '<span class="contact_list">'.($i+1).' . '.$contact_person[$i]." - ".$contact_number[$i]." </span> <br>";
                        $excel_txt .= ($i+1).' . '.$contact_person[$i]." - ".$contact_number[$i]." || ";
                    }
                }
                $result['data'][$key]['contact_details']        = !empty($supplier_dtls) ? $txt : '-';
                $result['data'][$key]['contact_details_exl']    = !empty($supplier_dtls) ? $excel_txt : '-';

            $result['data'][$key]['quantity']               = $this->callsql("SELECT count(id) FROM inventory_transactions WHERE stock_id='".$value['id']."'",'value');

            $result['data'][$key]['credit_type']            = !empty($value['credit_type']) ?  $this->creditType[$value['credit_type']] : '-';
            $result['data'][$key]['trans_type']             = !empty($value['trans_type']) ?  $this->transType[$value['trans_type']] : '-';

            $result['data'][$key]['date']                   = !empty($value['date']) ?  date('d-m-Y H:i:s',$value['date']) : '-';

            $result['data'][$key]['action']                 = '<button class="btn btn-primary" onclick="showViewModal('.$value['id'].')">View</button>';

    
        }
       
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;

    }
    public function isJson($json) {
        
        $result = json_decode($json);

        
        if ($result === FALSE || empty($result)) {
            return false;
        }
        return true;
    }
    public function getSupplierName(){
    	$supplierList = $this->callsql("SELECT id,supplier_name FROM supplier WHERE status=0 ORDER BY supplier_name ASC","rows");
        return $supplierList;
    }

    public function getSupplierProducts($supplier_id){
    	$productList    = $this->callsql("SELECT id,name, price FROM inventory WHERE supplier_id=$supplier_id ORDER BY id ASC","rows");
        $contact_dtls   = $this->callsql("SELECT contact_no, contact_person FROM supplier WHERE id=$supplier_id ORDER BY id ASC","row");

        $dc = json_decode($contact_dtls['contact_person']);
        $contact_person = implode ( "<br>", $dc );

        $dcc = json_decode($contact_dtls['contact_no']);
        $contact_no = implode ( "<br>", $dcc );
        $txt = '';
        for($i=0; $i<count($dc); $i++){
            $txt .= '<span>'.($i+1).' . '.$dc[$i]."-".$dcc[$i]." </span> ";
        }

        $productList['contact_dtls'] = $txt;

        return $productList;
    }

    public function getSupplierList($key){
       return $this->callsql("SELECT `id`,CASE
                             WHEN `supplier_name` like '$key%' THEN `supplier_name`
                             WHEN `id`like '$key%' THEN `id`
                             ELSE ''
                             END AS text  FROM `supplier` WHERE supplier_name like '$key%' OR `id` like '$key%'",'rows'); 

    }

    public function getProductList($key){
       return $this->callsql("SELECT `id`,CASE
                             WHEN `supplier_name` like '$key%' THEN `supplier_name`
                             WHEN `id`like '$key%' THEN `id`
                             ELSE ''
                             END AS text  FROM `supplier` WHERE supplier_name like '$key%' OR `id` like '$key%'",'rows'); 

    }

    public function addStock($data){

    	$time       	= time();
        $supplier_id    = $data['supplier_id'];
        $invoice_date	= strtotime($data['invoice_date']);
        $description 	= $data['description'];
        $invoice_number = $data['invoice_number'];
        $delivery_date 	= strtotime($data['delivery_date']);
        $ip_address 	= $this->IP;


        $this->callSql("INSERT INTO `stock`( `supplier_id`, `description`, `invoice_number`, `invoice_date`, `delivery_date`, `createtime`, `created_by`, `status`) VALUES ('$supplier_id','$description','$invoice_number','$invoice_date','$delivery_date','$time','$this->adminID','0')");
        $stock_id = $this->lastInsertId();

        for($i=0;$i<count($data['product_list']);$i++){
        
            $inv_id        	= $data['product_list'][$i];
            $unit          	= $data['unit'][$i];
            $unit_price    	= $data['unit_price'][$i];
            $item_dtls 		= $this->callsql("SELECT * FROM inventory WHERE id='$inv_id'","row");

        	if($data['unit_price'][$i] == $item_dtls['price']){
        		$new_qty = $item_dtls['quantity'] + $data['unit'][$i];
        		$this->query("UPDATE inventory SET `quantity`='".$new_qty."' WHERE `id`='".$inv_id."'");
        		$this->execute();
                $inv_id = $inv_id;
        	}
        	else{
            	$this->callSql("INSERT INTO `inventory`(`category_id`, `supplier_id`, `image`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `status`, `createtime`, `createtip`, `createid`) VALUES ('".$item_dtls['category_id']."','".$item_dtls['supplier_id']."','".$item_dtls['image']."','".$item_dtls['brand']."','".$item_dtls['name']."','".$item_dtls['type']."','".$item_dtls['vintage']."','".$item_dtls['country']."','".$item_dtls['volume']."','".$item_dtls['alcohol_percent']."','".$data['unit_price'][$i]."','".$data['unit'][$i]."',0,'$time','$ip_address','$this->adminID')");
                    $inv_id = $this->lastInsertId();
        	}

            $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`, `stock_id`, `ref_id`) VALUES ('$inv_id','".$data['unit'][$i]."','$time',1,0,'$this->adminID','$time', '".$stock_id."', '$inv_id')");
            	$this->execute();
   
        }
        

        return true;

    }

    public function addStockImport($data)
    {
        
        $time           = time();
        $supplier_id    = !empty($data['stockDetails']['supplier_id']) ? $data['stockDetails']['supplier_id'] : '';
        $invoice_date   = !empty($data['stockDetails']['invoice_date']) ? strtotime($data['stockDetails']['invoice_date']) : '';
        $description    = !empty($data['stockDetails']['description']) ? $data['stockDetails']['description'] : '';
        $invoice_number = !empty($data['stockDetails']['invoice_number']) ? $data['stockDetails']['invoice_number']:'';
        $delivery_date  = !empty($data['stockDetails']['delivery_date']) ? strtotime($data['stockDetails']['delivery_date']) : '';
        $ip_address     = $this->IP;
        $stock_id = '';

        if(!empty($data['productList'])) {

     
            //$this->callSql("INSERT INTO `stock`( `supplier_id`, `description`, `invoice_number`, `invoice_date`, `delivery_date`, `createtime`, `created_by`, `status`) VALUES ('$supplier_id','$description','$invoice_number','$invoice_date','$delivery_date','$time','$this->adminID','0')");

            $this->query("INSERT INTO `stock`( `supplier_id`, `description`, `invoice_number`, `invoice_date`, `delivery_date`, `createtime`, `created_by`, `status`) VALUES ('$supplier_id','$description','$invoice_number','$invoice_date','$delivery_date','$time','$this->adminID','0')");

            $this->execute();
            $stock_id = $this->lastInsertId();

            foreach($data['productList'] as $key=>$value){

                $inv_id         = $value['product_id'];
                $unit           = $value['unit'];
                $unit_price     = $value['price'];
                $item_dtls      = $this->callsql("SELECT * FROM inventory WHERE id='$inv_id'","row");
                if($unit_price == $item_dtls['price']){

                    $new_qty = $item_dtls['quantity'] + $unit;
                    $this->query("UPDATE inventory SET `quantity`='".$new_qty."' WHERE `id`='".$inv_id."'");
                    $this->execute();
                    

                }else{

                    $this->callSql("INSERT INTO `inventory`(`category_id`, `supplier_id`, `image`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `status`, `createtime`, `createtip`, `createid`) VALUES ('".$item_dtls['category_id']."','".$item_dtls['supplier_id']."','".$item_dtls['image']."','".$item_dtls['brand']."','".$item_dtls['name']."','".$item_dtls['type']."','".$item_dtls['vintage']."','".$item_dtls['country']."','".$item_dtls['volume']."','".$item_dtls['alcohol_percent']."','".$unit_price."','".$unit."',0,'$time','$ip_address','$this->adminID')");
                    $inv_id = $this->lastInsertId();
                }

                $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`, `stock_id`, `ref_id`) VALUES ('$inv_id','".$unit."','$time',1,0,'$this->adminID','$time', '".$stock_id."', '$inv_id')");
                $this->execute();

            }



        }

        //remove temp data

        if(!empty($data['bulk_id'])) {

            $this->DeleteTempStock($data['bulk_id']);

        }
        if($stock_id) {

            $activity = "Imported new stock.Stock id-".$stock_id;
            return $this->adminActivityLog($activity);

        }
        

        return true;


     



    }

    public function DeleteTempStock($id)
    {

        $this->query("DELETE FROM `temp_stock_bulk` WHERE bulk_id='$id'");
      
        return $this->execute();

        
    }

    public function getStockDetails($id){

        $details = $this->callsql("SELECT id, supplier_id, description, invoice_number, invoice_date, delivery_date, createtime, created_by, status FROM $this->tableName WHERE id='$id'",'row');

        $supplier_name  = $this->callsql("SELECT supplier_name FROM supplier WHERE id='".$details['supplier_id']."'",'value');
        
        $stock_item_details  = $this->callsql("SELECT * FROM inventory_transactions WHERE stock_id='".$details['id']."'",'rows'); 


        $details['supplier_name']   = !empty($supplier_name)? $supplier_name : '' ;
        $details['invoice_no']      = $details['invoice_number'];
        $details['invoice_date']    = !empty($details['invoice_date']) ? date('d-m-Y',$details['invoice_date']) : '-'; ;;
        $details['delivery_date']   = !empty($details['delivery_date']) ? date('d-m-Y',$details['delivery_date']) : '-'; ;;
        $details['item']            = $stock_item_details;
        foreach($stock_item_details as $key=>$val){
            $inv_dtls = $this->callsql("SELECT name, price, brand, category_id, vintage, country, volume, alcohol_percent, quantity FROM inventory WHERE id='".$val['inventory_id']."'",'row');
            $details['item'][$key]['item_name'] = $inv_dtls['name'];
            $details['item'][$key]['price']     = $inv_dtls['price'];
            $details['item'][$key]['brand']     = $inv_dtls['brand'];
            $details['item'][$key]['category']  = $this->callsql("SELECT name FROM category WHERE id='".$inv_dtls['category_id']."' ",'value');
            $details['item'][$key]['vintage']   = $inv_dtls['vintage'];
            $details['item'][$key]['country']   = $inv_dtls['country'];
            $details['item'][$key]['volume']    = $inv_dtls['volume'];
            $details['item'][$key]['alcohol']   = $inv_dtls['alcohol_percent'];
            $details['item'][$key]['quantity']  = $inv_dtls['quantity'];
        }
          

        return $details; 

    }

    
    public function checkBulk_id($bulk_id)
    {
        $last_bulk_id = $this->callsql("SELECT bulk_id FROM temp_inventory_bulk WHERE bulk_id = '$bulk_id'","value");
        return $last_bulk_id;

    }


    public function addTempStock($data)
    {
        

        $name            = $data['name'];
        $price           = $data['price'];
        $quantity        = $data['quantity'];
        $bulk_id         = $data['bulk_id'];
        $upload_filename = $data['upload_filename'];
        $status = '0';
        $createtime = time();
        $createtip = $this->IP;
        $createid = $this->adminID;
        $sql = "INSERT INTO temp_stock_bulk SET name='$name',price='$price',quantity='$quantity',bulk_id='$bulk_id',upload_filename='$upload_filename',status='$status',createtime='$createtime',createtip='$createtip',createid='$createid'";
        $this->query($sql);
        $this->execute();

    }

    public function getTempDetails($random_bulk_id)
    {
        $details = $this->callsql("SELECT * FROM temp_stock_bulk WHERE bulk_id='$random_bulk_id'","rows");
        return $details;

    }

    public function getProductByName($name)
    {
        return $this->callsql("SELECT id,name,supplier_id FROM inventory WHERE name='$name'",'row');

    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        return $this->execute();
        
    }
     



}