<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class InventoryEdit extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "inventory_edit_request";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];
        $this->statusArray = ['0'=>'Approved','1'=>'Rejected','2'=>'Pending'];

        
    }
    public function getList($data)
    {

        $where = ' WHERE id!=0';

       
        
        if(!empty($data['status']) ||  in_array($data['status'],['0','1','2'])){
            $where .= " AND status = '$data[status]' ";
        }
        if(!empty($data['user_id'])){
            $where .= " AND requested_by = '$data[user_id]' ";
        }
        if(!empty($data['staff_id'])){
            
            $where .= " AND requested_by IN(SELECT user_id FROM user WHERE staff_id LIKE '%".$data['staff_id']."%' ) ";
        }



        if(!empty($data['datefrom']) && !empty($data['dateto'])){

            $datefrom = strtotime($data['datefrom'].' 00:00:00');
            $dateto   = strtotime($data['dateto'].' 23:59:59');
            $where .= " AND requested_time BETWEEN '$datefrom' AND '$dateto' ";
        }


        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];

        

        $approve_permission     = in_array(60,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $reject_permission      = in_array(61,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $view_remark_permission = in_array(6,$this->admin_services) || $this->admin_role == '1' ? true : false;

        
        foreach ($result['data'] as $key => $value) {

               
                //$result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
            $inventory_id     = $value['inventory_id'];
            $inventoryDetails = $this->callsql("SELECT brand, name, type, brand, category_id, volume, alcohol_percent, price, quantity, vintage, country FROM inventory WHERE id='$inventory_id'",'row'); 
            $user_id = $value['requested_by'];

            $userDetails = $this->callsql("SELECT username,first_name,last_name,staff_id FROM user WHERE user_id='$user_id'",'row'); 



            $action = '';

            $status = $this->statusArray[$value['status']];

            
            $result['data'][$key]['brand']          = !empty($inventoryDetails['brand']) ? $inventoryDetails['brand'] : '-';
            $result['data'][$key]['name']           = !empty($inventoryDetails['name']) ?  $inventoryDetails['name'] : '-';
            $result['data'][$key]['type']           = !empty($inventoryDetails['type']) ?  $inventoryDetails['type'] : '-';
            $result['data'][$key]['requested_by']   = !empty($userDetails['username']) ?  $userDetails['username'] : '-';

            $result['data'][$key]['price']          = !empty($inventoryDetails['price']) ?  $inventoryDetails['price'] : '-';
            $result['data'][$key]['stock_quantity'] = !empty($inventoryDetails['quantity']) ?  $inventoryDetails['quantity'] : '-';
            $result['data'][$key]['vintage']        = !empty($inventoryDetails['vintage']) ?  $inventoryDetails['vintage'] : '-';
            $result['data'][$key]['country']        = !empty($inventoryDetails['country']) ?  $inventoryDetails['country'] : '-';
            $result['data'][$key]['volume']         = !empty($inventoryDetails['volume']) ?  $inventoryDetails['volume'] : '-';
            $result['data'][$key]['alcohol']        = !empty($inventoryDetails['alcohol_percent']) ?  $inventoryDetails['alcohol_percent'] : '-';
            $result['data'][$key]['category']       = $this->callsql("SELECT name FROM category WHERE id='".$inventoryDetails['category_id']."' ",'value');

            $result['data'][$key]['user_id']  = $user_id;
            $result['data'][$key]['staff_id']  = !empty($userDetails['staff_id']) ? $userDetails['staff_id'] : '-';

            $result['data'][$key]['requested_time']  = !empty($value['requested_time']) ?  date('d-m-Y H:i:s',$value['requested_time']) : '-';

            $result['data'][$key]['update_time']  = !empty($value['update_time']) ?  date('d-m-Y H:i:s',$value['update_time']) : '-';
            $updated_name = '';
            if($value['added_by']==2)
                {
                    $updated_name = $this->callsql("SELECT username FROM user WHERE user_id = '$value[updated_by]' ","value");
                }else{
                    $updated_name = $this->callsql("SELECT username FROM admin WHERE id = '$value[updated_by]' ","value");
                }
            $result['data'][$key]['updated_name']  = !empty($updated_name) ? $updated_name : '-';

            if($view_remark_permission && $value['status']!='2') {

                $action .= '<button class="btn btn-primary" onclick="showRemarkModal('.$value['id'].')">View Remark</button>';

            }

            //$result['data']['status']  = !empty($value['status']) ?  $this->statusArray[$value['status']] : '-';
            $result['data'][$key]['status']  =  '-';

            if($approve_permission && $value['status']=='2') {

                $action .= '<button class="btn btn-primary" onclick="showUpdateModal('.$value['id'].',0)">Approve</button>';

            }
            if($reject_permission && $value['status']=='2') {

                $action .= '<button class="btn btn-danger" onclick="showUpdateModal('.$value['id'].',1)">Reject</button>';

            }

            


                
            $result['data'][$key]['action'] = !empty($action) ? $action : '-';
            $status = $this->statusArray[$value['status']];


            $result['data'][$key]['status'] = $status;
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


        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }


    public function processRequest($params)
    {
        
        $actionArray = ['0'=>'Accepted','1'=>'Rejected'];
        $time = time();
        $credit_type = 0;
        
        if($params['action'] == '0') { //for approve
            $initial_stock = $this->callsql("SELECT quantity  FROM inventory WHERE id='".$params['inventory_id']."' ",'value');

            $edit_quantity    = $params['edit_quantity'];
            
            $sql = "UPDATE inventory SET quantity = $edit_quantity WHERE id='$params[inventory_id]'";
            $this->query($sql);
            $this->execute();

            if($params['edit_quantity'] < $initial_stock){
                $credit_type = '1';
            }
            else if($params['edit_quantity'] > $initial_stock){
                $credit_type = '0';

            }
            $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`) VALUES ('".$params['inventory_id']."','".$params['edit_quantity']."','$time',5,'$credit_type','$this->adminID','$time')");
            $this->execute();

        }
        

        $sql = "UPDATE inventory_edit_request SET status='$params[action]',remarks ='$params[remark]',updated_by='$this->adminID',update_time='$time' WHERE id='$params[id]'";
        $this->query($sql);
        $this->execute();

        $activity = "Inventory Edit Request ".$actionArray[$params['action']].'.Request Id-'.$params['id'];
        return $this->adminActivityLog($activity);

    }

    


    
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        return $this->execute();
        
    }
     
     

    

    

    
}
