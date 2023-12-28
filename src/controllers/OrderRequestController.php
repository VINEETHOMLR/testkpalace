<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Orderhistory;
use src\models\Foc;
use inc\Root;
use inc\commonArrays;
/**
 * To handle the users data models
 * @author 
 */

class OrderRequestController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Orderhistory);
        $this->focmdl = (new Foc);
        $this->pag =  new Pagination(new Orderhistory(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		$arr                 = commonArrays::getArrays();
        $this->statusArry    = ['0'=>'Approved','1'=>'Rejected','2'=>'Pending'];

        
            
    
    }
    public function actionIndex() {


        $this->checkPageAccess(64);
        $status        = $this->cleanMe(Router::post('status'));
        $customer_id   = $this->cleanMe(Router::post('customer_id'));
        $createid      = $this->cleanMe(Router::post('createid'));
        $from_date     = $this->cleanMe(Router::post('datefrom'));
        $to_date       = $this->cleanMe(Router::post('dateto'));
        $payment_mode  = $this->cleanMe(Router::post('payment_mode'));
        $foc  = $this->cleanMe(Router::post('foc'));
        $page          = $this->cleanMe(Router::post('page')); 
        $page          = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ "status"        => $status,
                  "customer_id"   => $customer_id,
                  "createid"      => $createid,
                  "from_date"     => $from_date,
                  "to_date"       => $to_date,
                  "payment_mode"  => $payment_mode,
                  "foc"           => $foc,
                  "page"          => $page];



        $data=$this->mdl->getList($filter);

        $customer_name = '';
        $admin_name    = '';
        if(!empty($customer_id)) {

        	$customer_name = $this->mdl->getcustomername($customer_id);

        }

        if(!empty($createid)) {

        	$admin_name = $this->mdl->getAdminname($createid);

        }


        $focList = $this->focmdl->getAllFocList();
         
        $onclick = "onclick=pageHistory('".$status."','".$customer_id."','".$createid."','".$from_date."','".$to_date."','".$payment_mode."','".$foc."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('Purchase/order_request',['status'=>$status,'customer_id'=>$customer_id,'createid'=>$createid,'from_date'=>$from_date,'to_date'=>$to_date,'customer_name'=>$customer_name,'admin_name'=>$admin_name,'data' => $data,'payment_mode'=>$payment_mode,'foc'=>$foc,'pagination'=> $pagination,'focList'=>$focList]);
        
    }


    public function actiongetFocReport() {


        $this->checkPageAccess(87);
        $status        = !empty(Router::post('status')) ? $this->cleanMe(Router::post('status')) : '';
        $customer_id   = !empty(Router::post('customer_id')) ? $this->cleanMe(Router::post('customer_id')) : '';
        $createid      = !empty(Router::post('createid')) ? $this->cleanMe(Router::post('createid')) : '';
        $from_date     = !empty(Router::post('datefrom')) ? $this->cleanMe(Router::post('datefrom')) : '';
        $to_date       = !empty(Router::post('dateto')) ? $this->cleanMe(Router::post('dateto')) : '';
        $payment_mode  = !empty(Router::post('payment_mode')) ? $this->cleanMe(Router::post('payment_mode')) : '';
        $foc           = !empty(Router::post('foc')) ? $this->cleanMe(Router::post('foc')) : '';
        $item_name     = !empty(Router::post('item_name')) ? $this->cleanMe(Router::post('item_name')) : '';
        $page          = $this->cleanMe(Router::post('page')); 
        $page          = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ "status"        => $status,
                  "customer_id"   => $customer_id,
                  "createid"      => $createid,
                  "from_date"     => $from_date,
                  "to_date"       => $to_date,
                  "payment_mode"  => $payment_mode,
                  "foc"           => $foc,
                  "item_name"     => $item_name,
                  "page"          => $page];

        // print_r($filter);die();

        $data=$this->mdl->getFocReport($filter);
        $customer_name = '';
        $admin_name    = '';
        if(!empty($customer_id)) {

            $customer_name = $this->mdl->getcustomername($customer_id);

        }

        if(!empty($createid)) {

            $admin_name = $this->mdl->getAdminname($createid);

        }


        $focList = $this->focmdl->getAllFocList();
         
        $onclick = "onclick=pageHistory('".$status."','".$customer_id."','".$createid."','".$from_date."','".$to_date."','".$payment_mode."','".$foc."','".$item_name."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('Purchase/foc_report',['status'=>$status,'customer_id'=>$customer_id,'createid'=>$createid,'from_date'=>$from_date,'to_date'=>$to_date,'customer_name'=>$customer_name,'admin_name'=>$admin_name,'data' => $data,'payment_mode'=>$payment_mode,'foc'=>$foc,'item_name'=>$item_name,'pagination'=> $pagination,'focList'=>$focList]);
        
    }

    public function actionUpdateOrderRequest(){

    	$id            = !empty($_POST['id']) ? cleanMe($_POST['id']) : '';
    	$action        = !empty($_POST['action']) ? cleanMe($_POST['action']) : '';
    	$payment_mode  = !empty($_POST['update_payment_mode']) ? cleanMe($_POST['update_payment_mode']) : '';
    	$foc           = !empty($_POST['update_foc']) ? cleanMe($_POST['update_foc']) : '';
    	$foc_remark_id = !empty($_POST['update_foc_remark_id']) ? cleanMe($_POST['update_foc_remark_id']) : '';
        $purchase_id   = !empty($_POST['purchase_id']) ? cleanMe($_POST['purchase_id']) : '';
        //$expire_at     = !empty($_POST['expire_at']) ? cleanMe($_POST['expire_at']) : '';

    	if(empty($id)) {

    		return $this->sendMessage('error',"Please Select Request To Proceed");
    		die();

    	}

    	if($action == '0' ) {

    		if(in_array($foc,[0]) && empty($payment_mode)) {

    		    return $this->sendMessage('error',"Please Select Payment Mode To Proceed");
    		    die();

    	    }

	    	if(in_array($foc,[1]) && empty($foc_remark_id)) {

	    		return $this->sendMessage('error',"Please Select Foc Remark To Proceed");
	    		die();

	    	}
	        $payment_mode = $foc == '1' ? '' : $payment_mode;

    	}


        $details = $this->mdl->getDetails($id);
        if($details['order_status']!='0') {

                return $this->sendMessage('error',"Sorry!This request already processed");
                die();

        }
    	

        $order_status_array = ['0'=>'1','1'=>'2'];

    
        
        if(empty($action)) {
            
            $action = '0';
        	$params = [];
	        $params['action']         = $action;
	        $params['payment_mode']   = $payment_mode;
	        $params['foc']            = $foc;
	        $params['foc_remark_id']  = $foc == '1' ? $foc_remark_id : '';
	        $params['order_status']   = $order_status_array[$action];
	        $params['foc_updated_by'] = $this->adminID;
            $params['purchase_id']    = $purchase_id;
            //$params['expire_at']      = $expire_at;

            $expiryArray = [];
            foreach($_POST['purchase_id'] as $key=>$value){

                if(in_array('',$_POST['expire_at_'.$value])) {

                    return $this->sendMessage('error',"Please enter expiry date to proceed");
                    die();

                }
                
                $expiryArray[] = ['purchase_id'=>$value,'expiry_date'=>$_POST['expire_at_'.$value]];

            }

            $params['expiry_array'] = $expiryArray;

            

          

        }else{

        	$params = [];
	        $params['action']         = $action;
	        $params['payment_mode']   = '';
	        $params['foc']            = '';
	        $params['foc_remark_id']  = '';
	        $params['order_status']   = $order_status_array[$action];
	        $params['foc_updated_by'] = '';
            $params['purchase_id']    = $purchase_id;
            $params['expire_at']      = $expire_at;


        }
        
        $params['id']            = $id;

      

        $response = $this->mdl->updatePurchase($params);
        $actionArray = ['0'=>'Approve','1'=>'Reject'];

        if($response) {

        	$msg = "Successfully ".$actionArray[$action]." the order";
        	return $this->sendMessage('success',$msg);
        	die();




        }else{

        	$msg = "Failed to ".$actionArray[$action]." the order";
        	return $this->sendMessage('error',$msg);
        	die();
        }

        
        return $this->sendMessage('error',"Something went wrong");
        die();

    }

    public function actiongetApprovalDetails(){
        $id         = $this->cleanMe(Router::get('id'));
        $details    = $this->mdl->getApprovalDetails($id);
        $focList    = $this->focmdl->getAllFocList();
        return $this->renderAjax('Purchase/order_request_approval_modal',['details'=>$details, 'focList'=>$focList]);
    }


    public function actionGetDetails()
    {

        $id            = $this->cleanMe(Router::get('id'));
        $details = $this->mdl->getOrderDetails($id);
        return $this->renderAjax('Purchase/order_request_view_modal',['details'=>$details]);

    }

    public function actiongetOrderDetails(){
        $id        = $this->cleanMe(Router::post('orderid'));

        $response   = $this->mdl->getCustomerOrderDetails($id);
        return $this->renderJson($response);
    }

    public function actionExport()
    {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Order Request List'; 


        $status        = $this->cleanMe(Router::post('status'));
        $customer_id   = $this->cleanMe(Router::post('customer_id'));
        $createid      = $this->cleanMe(Router::post('createid'));
        $from_date     = $this->cleanMe(Router::post('datefrom'));
        $to_date       = $this->cleanMe(Router::post('dateto'));
        $payment_mode  = $this->cleanMe(Router::post('payment_mode'));
        $foc  = $this->cleanMe(Router::post('foc'));
        $filter=[ "status"        => $status,
                  "customer_id"   => $customer_id,
                  "createid"      => $createid,
                  "from_date"     => $from_date,
                  "to_date"       => $to_date,
                  "payment_mode"  => $payment_mode,
                  "foc"           => $foc,
                  "page"          => '1',
                  "export"        => true
               ];

        $data=$this->mdl->getList($filter);

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;
        
        $csv = "User Id,Username,Order Id,Room, Description , Sales Date,Status \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            $html.= $his['uniqueid'].','.$his['customer_name'].','.$his['id'].','.$his['room_no'].',"'.$his['description'].'",'.$his['createtime'].','.$his['status']."\n"; //Append data to csv

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


    public function actionFocExport()
    {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');
        $filename         = 'Foc List'; 


        $status        = $this->cleanMe(Router::post('status'));
        $customer_id   = $this->cleanMe(Router::post('customer_id'));
        $createid      = $this->cleanMe(Router::post('createid'));
        $from_date     = $this->cleanMe(Router::post('datefrom'));
        $to_date       = $this->cleanMe(Router::post('dateto'));
        $payment_mode  = $this->cleanMe(Router::post('payment_mode'));
        $foc           = $this->cleanMe(Router::post('foc'));
        $item_name     = $this->cleanMe(Router::post('item_name'));
        $filter=[ "status"        => $status,
                  "customer_id"   => $customer_id,
                  "createid"      => $createid,
                  "from_date"     => $from_date,
                  "to_date"       => $to_date,
                  "payment_mode"  => $payment_mode,
                  "foc"           => $foc,
                  "item_name"     => $item_name,
                  "page"          => '1',
                  "export"        => true
               ];

        $data=$this->mdl->getFocReport($filter);

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;
        
        $csv = "Order Id,Item Name,Quantity , Description , Staff ID, Staff Name ,Customer Id,Customer name,Room, Sales Date,Sales Approved ON \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            $html.= $his['id'].','.$his['item_name'].','.$his['quantity'].',"'.$his['description'].'",'.$his['staff_id'].','.$his['staff_name'].','.$his['uniqueid'].','.$his['customer_name'].','.$his['room_no'].','.$his['createtime'].','.$his['updated_at']."\n"; //Append data to csv

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

    public function actiongetOrderExpiryDetails(){
        $id             = $this->cleanMe(Router::get('id'));
        $details        = $this->mdl->getCustomerOrderDetails($id);
        return $this->renderAjax('Purchase/order_expiry_update_modal',['details'=>$details]);
    }

    
   
}