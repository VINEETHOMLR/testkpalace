<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\InventoryEdit;
use src\models\InventoryTransactions;
use inc\Root;
use inc\commonArrays;
use src\models\User;
/**
 * To handle the users data models
 * @author 
 */

class InventoryController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl          = (new InventoryEdit);
        $this->transmdl     = (new InventoryTransactions);

        $this->usermdl      = (new User);
        $this->pag          =  new Pagination(new InventoryEdit(),''); 
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
		
		$arr                = commonArrays::getArrays();
        $this->transType = ['1'=>'Purchase','2'=>'Sales','3'=>'Purchase Return','4'=>'Sales Return','5'=>'Adjustment'];
        $this->creditType = ['0'=>'Credit','1'=>'Debit'];

        
            
    
    }

    public function actionIndex() {


        $this->checkPageAccess(59);
        $status   = $this->cleanMe(Router::post('status'));
        $user_id  = $this->cleanMe(Router::post('user_id'));
        $staff_id = $this->cleanMe(Router::post('staff_id'));
        $page     = $this->cleanMe(Router::post('page')); 
        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        $page     = (!empty($page)) ? $page : '1'; 
        
        

        $filter=["status"     => $status,
                  "user_id"   => $user_id,
                  "staff_id"  => $staff_id,
                  "datefrom"  => $datefrom,
                  "dateto"    => $dateto,
                  "page"      => $page];

        $username = '';          

        if( ! empty($user_id)){
             $username    = $this->usermdl->getUsername($user_id);

        }          

        $data = $this->mdl->getList($filter);
         
        $onclick    = "onclick=pageHistory('".$status."','".$user_id."','".$staff_id."','".$datefrom."','".$dateto."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('inventoryedit/index',['status'=>$status,'username'=>$username,'user_id'=>$user_id,'datefrom'=>$datefrom,'dateto'=>$dateto,'staff_id'=>$staff_id,'data' => $data, 'pagination'=> $pagination]);

    }

    public function actionupdateRequest()
    {


        $id       = !empty($_POST['id']) ? cleanMe($_POST['id']) : '';
        $remark   = !empty($_POST['remark']) ? cleanMe($_POST['remark']) : '';
        $action   = !empty($_POST['action']) ? cleanMe($_POST['action']) : '0';

        if(empty($id)){
           return $this->sendMessage("error","Please select a request to Proceed");
        }

        if(empty($remark)){
           return $this->sendMessage("error","Enter Remark to Proceed");
        }

        $details = $this->mdl->getDetails($id);

      
        if($details['status']!='2') { //check requst on pendignn stage

            return $this->sendMessage("error","Selected request already processed");	

        }

        //check add or deduct

        

        
        $params['edit_quantity'] = $details['edit_quantity'];
        $params['id']            = $id;
        $params['inventory_id']  = $details['inventory_id'];
        $params['action']        = $action;
        $params['remark']        = $remark;
        if($this->mdl->processRequest($params)){

        	return $this->sendMessage("success","Successfully updated the request");	
            die();

        }else{

        	return $this->sendMessage("error","Faield to update the request");	
            die();

        }

        

    }


    public function actionShowRemark()
    {

        $id       = !empty($_POST['id']) ? cleanMe($_POST['id']) : '';
        $details = $this->mdl->getDetails($id);
        echo json_encode($details);

    }

    public function actionTransactionsList(){
        $this->checkPageAccess(59);
        $status         = $this->cleanMe(Router::post('status'));
        $item_id        = $this->cleanMe(Router::post('item_id'));
        $page           = $this->cleanMe(Router::post('page')); 
        $datefrom       = $this->cleanMe(Router::post('datefrom')); 
        $dateto         = $this->cleanMe(Router::post('dateto'));
        $creditType     = $this->cleanMe(Router::post('creditType'));
        $transType      = $this->cleanMe(Router::post('transType'));
        $foc            = $this->cleanMe(Router::post('foc'));
        $page           = (!empty($page)) ? $page : '1'; 

        $filter=["status"       => $status,
                  "item_id"     => $item_id,
                  "datefrom"    => $datefrom,
                  "dateto"      => $dateto,
                  "creditType"  => $creditType,
                  "transType"   => $transType,
                  "foc"         => $foc,
                  "page"        => $page];

        $item_name = '';

        if( ! empty($item_id)){
            $item_name    = $this->transmdl->getname($item_id);
        }          

        $data=$this->transmdl->getList($filter);
         
        $onclick = "onclick=pageHistory('".$item_id."','".$datefrom."','".$dateto."','".$transType."','".$foc."','".$creditType."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');

        return $this->render('inventory/transactions_list',['status'=>$status,'item_name'=>$item_name,'item_id'=>$item_id,'datefrom'=>$datefrom,'dateto'=>$dateto,'data' => $data,'creditType'=>$creditType,'transType'=>$transType,'foc'=>$foc, 'pagination'=> $pagination]);
    }

    public function actionGetItems(){
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $itemlist = $this->transmdl->getItemname($term);
            echo  $itemList = json_encode($itemlist);    
        }
    }

    public function actionExport()
    {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Inventory Edit Request List'; 

        $status   = $this->cleanMe(Router::post('status'));
        $user_id  = $this->cleanMe(Router::post('user_id'));
        $staff_id = $this->cleanMe(Router::post('staff_id'));
        $page     = $this->cleanMe(Router::post('page')); 
        $datefrom = $this->cleanMe(Router::post('datefrom')); 
        $dateto   = $this->cleanMe(Router::post('dateto'));
        
        

        $filter=["status"     => $status,
                  "user_id"   => $user_id,
                  "staff_id"   => $staff_id,
                  "datefrom"  => $datefrom,
                  "dateto"    => $dateto,
                  "page"      => '1',
                  "export"    =>true
                ];

        $data = $this->mdl->getList($filter);  
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;
        
        $csv = "Staff ID,Requested By,Brand,Name,Category,Vintage,Country,Volume,Alcohol,Price,Type, Quantity , Requested Time,Updated Time,Updated By,Status \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            $html.= $his['staff_id'].','.$his['requested_by'].','.$his['brand'].','.$his['name'].','.$his['category'].','.$his['vintage'].','.$his['country'].','.$his['volume'].','.$his['alcohol'].','.$his['price'].','.$his['type'].',"'.$his['edit_quantity'].'",'.$his['requested_time'].','.$his['update_time'].','.$his['updated_name'].','.$his['status']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Order Request List .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download); 



    }

    public function actionExportInvTransactions(){
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Inventory Transaction List'; 

        $status         = $this->cleanMe(Router::post('status'));
        $item_id        = $this->cleanMe(Router::post('item_id'));
        $datefrom       = $this->cleanMe(Router::post('datefrom')); 
        $dateto         = $this->cleanMe(Router::post('dateto'));
        $creditType     = $this->cleanMe(Router::post('creditType'));
        $transType      = $this->cleanMe(Router::post('transType'));
        $foc            = $this->cleanMe(Router::post('foc'));
        

        $filter = ["status"       => $status,
            "item_id"     => $item_id,
            "datefrom"    => $datefrom,
            "dateto"      => $dateto,
            "creditType"  => $creditType,
            "transType"   => $transType,
            "foc"         => $foc,
            "page"        => '1',
            "export"      => true
        ];

        $data = $this->transmdl->getList($filter);


        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;
        
        $csv = "Transaction ID, Item Name, Category, Brand, Vintage, Country, Volume, Alcohol %, Price, Quantity, FOC, Credit Type, Transaction Type , Transaction Date, Updated By \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $val) {
            $html.= $val['id'].','.$val['name'].','.$val['category'].','.$val['brand'].','.$val['vintage'].','.$val['country'].','.$val['volume'].','.$val['alcohol'].','.$val['price'].','.$val['quantity'].','.$val['foc'].','.$val['credit_type'].',"'.$val['trans_type'].'",'.$val['date'].','.$val['updated_by']."\n"; //Append data to csv
        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Inventory transaction List .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }
   
}

