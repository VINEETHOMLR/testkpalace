<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class InventoryTransactions extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "inventory_transactions";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

        $this->transType = ['1'=>'Purchase','2'=>'Sales','3'=>'Purchase Return','4'=>'Sales Return','5'=>'Adjustment'];
        $this->creditType = ['0'=>'Credit','1'=>'Debit'];

    }
    public function getList($data){
        $where = ' WHERE id!=0';

        if(!empty($data['status']) ||  in_array($data['status'],['0','1','2'])){
            $where .= " AND status = '".$data['status']."' ";
        }

        if(!empty($data['item_id'])){
            $where .= " AND inventory_id = '".$data['item_id']."' ";
        }

        if($data['foc']!=''){
            $where .= " AND inventory_id IN(SELECT id FROM inventory WHERE foc IN('".$data['foc']."')) ";
        }

        if(!empty($data['transType'])){

            $where .= " AND trans_type = '".$data['transType']."' ";
        }

        if(in_array($data['creditType'],['0','1'])){

            $where .= " AND credit_type = '".$data['creditType']."' ";
        }





        if(!empty($data['datefrom']) && !empty($data['dateto'])){
            
            $datefrom = date('Y-m-d',strtotime($data['datefrom']));
            $dateto   = date('Y-m-d',strtotime($data['dateto']));
            $datefrom = strtotime($datefrom.' 00:00:00');
            $dateto   = strtotime($dateto.' 23:59:59');
            $where .= " AND date BETWEEN '$datefrom' AND '$dateto' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];
        
        foreach ($result['data'] as $key => $value) {

            $inventory_id     = $value['inventory_id'];
            $inventoryDetails = $this->callsql("SELECT id, brand, name, type, category_id, price, vintage, country, volume, alcohol_percent,foc FROM inventory WHERE id='$inventory_id'",'row'); 
            $user_id = $value['updated_by'];
            if($value['added_by']==1)
            {
            $updated_by = $this->callsql("SELECT username FROM admin WHERE id='$user_id'",'row'); 
            }else{
            $updated_by = $this->callsql("SELECT username FROM user WHERE user_id='$user_id'",'row'); 
            }
            $action = '';  
            $result['data'][$key]['foc']            = (empty($inventoryDetails['foc'])) ? 'No FOC' : 'FOC';
            $result['data'][$key]['brand']          = !empty($inventoryDetails['brand']) ? $inventoryDetails['brand'] : '-';
            $result['data'][$key]['name']           = !empty($inventoryDetails['name']) ?  $inventoryDetails['name'] : '-';
            $result['data'][$key]['price']          = !empty($inventoryDetails['price']) ?  $inventoryDetails['price'] : '-';
            $result['data'][$key]['vintage']        = !empty($inventoryDetails['vintage']) ?  $inventoryDetails['vintage'] : '-';
            $result['data'][$key]['country']        = !empty($inventoryDetails['country']) ?  $inventoryDetails['country'] : '-';
            $result['data'][$key]['volume']         = !empty($inventoryDetails['volume']) ?  $inventoryDetails['volume'] : '-';
            $result['data'][$key]['alcohol']        = !empty($inventoryDetails['alcohol_percent']) ?  $inventoryDetails['alcohol_percent'] : '-';
            $category_name = '';
            if(!empty($inventoryDetails['category_id']))
            {
                $category_name = $this->callsql("SELECT name FROM category WHERE id='".$inventoryDetails['category_id']."' ",'value');
            }

            $result['data'][$key]['category']       = !empty($category_name) ? $category_name : '-';

            $result['data'][$key]['updated_by']     = !empty($updated_by['username']) ?  $updated_by['username'] : '-';
            $result['data'][$key]['credit_type']    = $value['credit_type']!='' ?  $this->creditType[$value['credit_type']] : '-';
            $result['data'][$key]['trans_type']     = !empty($value['trans_type']) ?  $this->transType[$value['trans_type']] : '-';

            $result['data'][$key]['user_id']        = $data['item_id'];

            $result['data'][$key]['date']           = !empty($value['date']) ?  date('d-m-Y H:i:s',$value['date']) : '-';
    
        }
       
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

    public function getDetails($id){
        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }

    public function getItemname($key){
       return $this->callsql("SELECT `id`,CASE
                             WHEN `name` like '$key%' THEN `name`
                             WHEN `id`like '$key%' THEN `id`
                             ELSE ''
                             END AS text  FROM `inventory` WHERE name like '$key%' OR `id` like '$key%'",'rows'); 

    }

    function getname($item_id){
       return $this->callsql("SELECT name FROM inventory WHERE id=$item_id","value");
    }

    function getCustomerSales() {
        $date_array = [
            date('d-m-Y'),
            date('d-m-Y', strtotime("-1 days")),
            date('d-m-Y', strtotime("-2 days")),
            date('d-m-Y', strtotime("-3 days")),
            date('d-m-Y', strtotime("-4 days")),
            date('d-m-Y', strtotime("-5 days")),
            date('d-m-Y', strtotime("-6 days"))
        ];

        $data = ['datasets' => []];

        foreach ($date_array as $k => $v) {
            $start = strtotime($v . " 00:00:00");
            $end = strtotime($v . " 23:59:59");

            $inv_trans = $this->callsql("SELECT inventory_id FROM inventory_transactions WHERE `updatetime` >='$start' AND `updatetime` <='$end'", "rows");

            if (!empty($inv_trans)) {
                $inv_ids = array_column($inv_trans, 'inventory_id');
                $inv_data = $this->callsql("SELECT id, category_id FROM `inventory` WHERE id IN(" . implode(',', $inv_ids) . ")", "rows");
                $cat_ids = array_column($inv_data, 'category_id');

                $category_data = $this->callsql("SELECT id, name FROM `category` WHERE id IN(" . implode(',', $cat_ids) . ")", "rows");

                foreach ($category_data as $value) {
                    $dataset = [
                        'label' => $value['name'],
                        'backgroundColor' => 'pink',
                        'borderColor' => 'red',
                        'borderWidth' => 1,
                        'data' => array_fill(0, count($date_array), 0),
                    ];

                    $inv_data = $this->callsql("SELECT id FROM `inventory` WHERE category_id='" . $value['id'] . "'", "rows");
                    $inv_ids = array_column($inv_data, 'id');
                    $cat_qty = $this->callsql("SELECT sum(quantity) as qty FROM `inventory_transactions` WHERE inventory_id IN(" . implode(',', $inv_ids) . ") AND `updatetime`>='$start' AND `updatetime` <='$end'", "rows");

                    if (!empty($cat_qty)) {
                        $dataset['data'][$k] = (int)$cat_qty[0]['qty'];
                    }

                    $data['datasets'][] = $dataset;
                }
            } else {
                // Handle the case where inventory transactions are empty
                $data['datasets'][] = [
                    'label' => '',
                    'backgroundColor' => 'pink',
                    'borderColor' => 'red',
                    'borderWidth' => '1',
                    'data' => array_fill(0, count($date_array), 0),
                ];
            }
        }

        return $data;
    }




    public function getInvOverview(){
        
        $total_stock = array();
        $ttl = 0;
        $categories = $this->callsql("SELECT id, name FROM category", "rows");
        $purchase_quantity = 0;

        foreach ($categories as $key=>$value) {
           
            $category_name = html_entity_decode($value['name']);
            $purchase_ttl = 0;
            
            $total_stock[$key]['category_name']     = $category_name;
            $total_stock[$key]['purchase_quantity'] = 0;

            $inv_dtls = $this->callsql("SELECT id FROM inventory WHERE status = 0  AND category_id = '".$value['id']."'", "rows");
            
            $inv_ids = array_column($inv_dtls, 'id');
            
            $inv_ids_string = implode(', ', $inv_ids);
            
            if(!empty($inv_ids_string)){
                $purchase_ttl = $this->callsql("SELECT SUM(quantity) as quantity FROM `inventory_transactions` WHERE trans_type IN (1,5) AND credit_type=0 AND inventory_id IN($inv_ids_string)", "value");

                // if ((int)$purchase_ttl > 0) {
                    $total_stock[$key]['purchase_quantity']= (Int)$purchase_ttl;
                    $ttl += $purchase_ttl;
                // }
            }
        }
        
        $response['total_stock'] = $total_stock;
        $response['total_purchase'] = $ttl;
        return $response;
    }

    public function getInstock(){
        
        $total_stock = array();
        $ttl            = 0;
        $base_stock     = 0;
        $inv_quantity = 0;
        $sale_cnt = 0;
        $categories_list    =  $this->callsql("SELECT id, name FROM category ","rows");
        
        $purchase_quantity = 0;

        foreach($categories_list as $key=>$value){
            
            $category_name = html_entity_decode($value['name']);
            $purchase_ttl   = 0;
            $total_stock[$key]['category_name']     = $category_name;
            $total_stock[$key]['stock_quantity']    = 0;
            $total_stock[$key]['purchase_quantity'] = 0;
            
            $inv_dtls          = $this->callsql("SELECT id FROM inventory WHERE status = 0 AND category_id = '".$value['id']."'","rows");
            
            $inv_ids = array_column($inv_dtls, 'id');
            
            $inv_ids_string = implode(', ', $inv_ids);

            if(!empty($inv_ids_string)){

                $purchase_ttl = $this->callsql("SELECT SUM(quantity) as quantity FROM `inventory_transactions` WHERE trans_type IN (1,5) AND credit_type=0 AND inventory_id IN($inv_ids_string)", "value");
                    
                $inv_stock = $this->callsql("SELECT sum(quantity) as inv_quantity FROM inventory WHERE status = 0 AND id IN($inv_ids_string) AND category_id = '".$value['id']."' ","value");

                // if ((int)$purchase_ttl > 0) {
                    $total_stock[$key]['purchase_quantity'] = (Int)$purchase_ttl;
                    $purchase_quantity += (Int)$purchase_ttl;
                // }
                // if ((int)$inv_stock > 0) {
                //     $total_stock[$key]['stock_quantity']    = (Int)$inv_stock;
                //     $ttl    += (Int)$inv_stock;
                // }
                $total_stock[$key]['stock_quantity']    = (Int)$inv_stock;
                $ttl    += (Int)$inv_stock;
            }
        }

        $response['total_stock'] = $total_stock;
        $response['total_avail_stock'] = $ttl;
        $response['purchase_total'] = $purchase_quantity;

        return $response;
    
    }

    public function getCustomerCeller(){
        $total_stock = array();
        $ttl            = 0;
        $sales_ttl      = 0;
        $categories_list    =  $this->callsql("SELECT id, name FROM category ","rows");
        $purchase_quantity = 0;
        $sell_quantity = 0;
        foreach($categories_list as $key=>$value){
            
            $category_name = html_entity_decode($value['name']);
                    
            $total_stock[$key]['category_name']     = $category_name;
            $total_stock[$key]['sales_quantity']    = 0;
            $total_stock[$key]['purchase_quantity'] = 0;

            $inv_dtls = $this->callsql("SELECT id FROM inventory WHERE status = 0  AND category_id = '".$value['id']."'", "rows");
            
            $inv_ids = array_column($inv_dtls, 'id');
            
            $inv_ids_string = implode(', ', $inv_ids);

            if(!empty($inv_ids_string)){
                $sell_ttl = $this->callsql("SELECT SUM(quantity) as quantity FROM `inventory_transactions` WHERE trans_type IN (2,5) AND credit_type=1 AND inventory_id IN($inv_ids_string)", "value");
                $purchase_ttl = $this->callsql("SELECT SUM(quantity) as quantity FROM `inventory_transactions` WHERE trans_type IN (1,5) AND credit_type=0 AND inventory_id IN($inv_ids_string)", "value");
                
                // if ((int)$purchase_ttl > 0) {
                    $ttl += (Int)$purchase_ttl;
                    $total_stock[$key]['purchase_quantity']= (Int)$purchase_ttl;
                // }
                    $sales_ttl += (Int)$sell_ttl;
                    $total_stock[$key]['sales_quantity']= (Int)$sell_ttl;
            }            
        }

        $response['total_stock'] = $total_stock;
        $response['sales_total'] = $sales_ttl;
        $response['purchase_total'] = $ttl;

        return $response;
    }

    

}