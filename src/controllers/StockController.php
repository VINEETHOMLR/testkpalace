<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Stock;
use inc\Root;
use inc\commonArrays;
use src\models\User;
/**
 * To handle the users data models
 * @author 
 */

class StockController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->stockmdl          = (new Stock);

        $this->usermdl      = (new User);
        $this->pag          =  new Pagination(new Stock(),''); 
        $this->adminID      = $_SESSION[SITENAME.'_admin'];
        $this->mainTitle     = 'Inventory Management';


		
		$arr                = commonArrays::getArrays();
    }

    

    public function actionIndex(){
        $this->checkPageAccess(59);
        $this->subTitle     = 'Stock'; 
        $supplier_id        = !empty(Router::post('supplier_id')) ? $this->cleanMe(Router::post('supplier_id')) : '';
        $contact_person     = !empty(Router::post('contact_person')) ? $this->cleanMe(Router::post('contact_person')) : '';
        $contact_no         = !empty(Router::post('contact_no')) ? $this->cleanMe(Router::post('contact_no')) : '';
        $order_no           = !empty(Router::post('order_no')) ? $this->cleanMe(Router::post('order_no')) : '';
        $invoice_date       = !empty(Router::post('invoice_date')) ? date('Y-m-d',strtotime($this->cleanMe(Router::post('invoice_date')))) : '';
        $delivery_date      = !empty(Router::post('delivery_date')) ? date('Y-m-d',strtotime($this->cleanMe(Router::post('delivery_date')))) : '';
        $page               = !empty(Router::post('page')) ? $this->cleanMe(Router::post('page')) : '';
        
        $page               = (!empty($page)) ? $page : '1'; 
        
        $filter             = ["supplier_id"    => $supplier_id,
                               "contact_person" => $contact_person,
                               "contact_no"     => $contact_no,
                               "order_no"       => $order_no,
                               "invoice_date"   => $invoice_date,
                               "delivery_date"  => $delivery_date,
                               "page"           => $page];
        $username = '';          

        if( ! empty($user_id)){
             $username    = $this->usermdl->getname($user_id);
        }

        $data = $this->stockmdl->getList($filter);

        if(!empty($invoice_date)) {

            $filter['invoice_date'] = date('d-m-Y',strtotime($invoice_date));
            
        }

        if(!empty($delivery_date)) {

            $filter['delivery_date'] = date('d-m-Y',strtotime($delivery_date));

        }
         
        $data['supplierList']   = $this->stockmdl->getSupplierName();

        $onclick = "onclick=pageHistory('".$filter['supplier_id']."','".$filter['order_no']."','".$filter['invoice_date']."','".$filter['delivery_date']."','***')";

        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');


        
        return $this->render('stock/index',['filter' => $filter, 'data' => $data, 'pagination'=> $pagination]);
        

    }

    public function actionUpdateStock(){
        $this->checkPageAccess(30);

        $data[] = '';
        

        $data['supplierList']   = $this->stockmdl->getSupplierName();

        $this->subTitle  = 'Update Stock';

        if(isset($_GET['id'])){
            $id = $this->cleanMe(Router::get('id'));
            $alcohol = $this->mdl->callsql("SELECT * FROM `inventory` WHERE id='$id' ","rows");
            $this->subTitle  = 'Edit Inventory';

            $data['alcohol']    = $alcohol;
            $data['log']     = $id;
            $data['divCount']= count($alcohol);
            return $this->render('stock/updateStock',$data);
        }else
            return $this->render('stock/updateStock',$data);



    }
    public function actiongetSupplierProducts(){
        $supplier_id = $this->cleanMe(Router::get('supplierid'));

        $productList = $this->stockmdl->getSupplierProducts($supplier_id);
        echo  $productList = json_encode($productList);    


    }

    public function actionGetSupplierList(){
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $supplierList = $this->stockmdl->getSupplierList($term);
            echo  $supplierList = json_encode($supplierList);    
        }
    }

    public function actionGetProductList(){
        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

           $supplierList = $this->stockmdl->getProductList($term);
            echo  $supplierList = json_encode($supplierList);    
        }
    }

    public function actionAddStock()
    {
       


        $supplier_id      = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : '';
        $invoice_date     = !empty($_POST['invoice_date']) ? date('Y-m-d',strtotime($_POST['invoice_date'])) : '';
        $description      = !empty($_POST['description']) ? $_POST['description'] : '';
        $invoice_number   = !empty($_POST['invoice_number']) ? $_POST['invoice_number'] : '';
        $delivery_date    = !empty($_POST['delivery_date']) ? date('Y-m-d',strtotime($_POST['delivery_date'])) : '';
        $data = '';
        if(empty($supplier_id)){


            $data = ['type'=>'validation','msg'=>'Please Select Supplier To Proceed'];
            return $this->sendMessage('error',$data);

        }
        if(empty($invoice_date)){


            $data = ['type'=>'validation','msg'=>'Please Enter Invoice Date To Proceed'];
            return $this->sendMessage('error',$data);

        }
        if(empty($description)){


            $data = ['type'=>'validation','msg'=>'Please Enter Description To Proceed'];
            return $this->sendMessage('error',$data);

        }
        if(empty($invoice_number)){


            $data = ['type'=>'validation','msg'=>'Please Enter Invoice Number To Proceed'];
            return $this->sendMessage('error',$data);

        }
        if(empty($delivery_date)){


            $data = ['type'=>'validation','msg'=>'Please Enter Delivery Date To Proceed'];
            return $this->sendMessage('error',$data);

        }

        //$files =  $_FILES['filename'];
        if(empty($_FILES['filename'])) {

            $data = ['type'=>'validation','msg'=>'Please upload excel file  To Proceed'];
            return $this->sendMessage('error',$data);

        }

        if(!empty($_FILES['filename'])){     
            
            $filename   = $_FILES['filename']['name'];
            $temp_name  = $_FILES['filename']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('CSV','csv');

            if(!in_array($extension, $image_array)){
                
                $data = [];
                $data['msg'] = 'Please Select Valid Format';
                $data['type'] = 'validation';
                return $this->sendMessage("error",$data);
                die();
            }

           
            $newFile_org = 'CreateStockBulk_'.$this->adminID.'_'.time().'.'.$extension;
            $target_file = BASEPATH."web/upload/stock/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!move_uploaded_file ($temp_name, $target_file)){
               
               $data = [];
               $data['msg']  = 'Something Went Wrong...';
               $data['type'] = 'validation';
               return $this->sendMessage("error",$data,"error");
               die();
            }

        }

        $csvFile = file($target_file);
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        if(empty($data)){
               
            $data['msg'] = 'Selected excel file is empty';
            $data['type'] = 'validation';  
            return $this->sendMessage("error",$data,"error");
            die();

        } 
        $error_array =[];
        $error_status = '0';

        $random_bulk_id = $this->generaterandomid();

        foreach($data as $k=>$v)
        {

            if($k!='0') 
            {

                $rowindex    = $k+1;
                $slno        = !empty($v[0]) ? $v[0] : '';
                $name        = !empty($v[1]) ? $v[1] : '';
                $quantity    = !empty($v[2]) ? $v[2] : '';
                $price       = !empty($v[3]) ? $v[3] : '';
                  
                $ip['name']            = $name;
                $ip['price']           = $price;
                $ip['quantity']        = $quantity;
                $ip['bulk_id']         = $random_bulk_id;
                $ip['upload_filename'] = $newFile_org;
                $this->stockmdl->addTempStock($ip);
     
               
            }

        }

        

        $details_temp = $this->stockmdl->getTempDetails($random_bulk_id);
        $html = [];
        $html['header'] = '';
        $html['valid'] = '';
        $html['invalid'] = '';
        $html['footer'] = '';
        $html['header'] = '
                        <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                        <thead> 
                            <tr>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Product</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Qunatity</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Price</th>
                                
                            </tr>
                        </thead>';
        foreach($details_temp as $k=>$v){

            $name                 = !empty($v['name']) ? $v['name'] : '';
            $quantity             = !empty($v['quantity']) ? $v['quantity'] : '';
            $price                = !empty($v['price']) ? $v['price'] : '';
            
            $isValid = true;
            $inventoryDetails = $this->stockmdl->getProductByName($name);
            if(empty($name) || empty($quantity) || empty($price)) {

                $isValid = false;

            }

            if(empty($inventoryDetails)) {

                $isValid = false;

            }
            if(!empty($inventoryDetails) && $inventoryDetails['supplier_id']!=$supplier_id) {

                $isValid = false;

            }

        

            if(!ctype_digit($quantity)) {
                $isValid = false;
            }

            $pattern = '/^(0|[1-9]\d*)(\.\d{2})?$/';
            if (preg_match($pattern, $quantity) == '0') {
               $isValid = false;
            }
            if (preg_match($pattern, $price) == '0') {
               $isValid = false;
            }

            
            if($isValid)
            {
                    
                $html['valid'].= '<tr role="row" class="odd">
                    <td>'.$name.'</td>
                    <td>'.$quantity.'</td>
                    <td>'.$price.'</td>
                </tr>';
                
            }else{

                $html['valid'].= '<tr role="row" class="table-danger">
                    <td>'.$name.'</td>
                    <td>'.$quantity.'</td>
                    <td>'.$price.'</td>
                </tr>';

            }




        }                


        $html['footer'] = '</tbody></table>';
        $data['msg'] = '';
        $data['type']            = 'showpopup';  
        $data['html']['header']  = $html['header'];
        $data['html']['valid']   = $html['valid'];
        $data['html']['invalid'] = $html['invalid'];
        $data['html']['footer']  = $html['footer'];
        $data['bulk_id']         = $random_bulk_id;
        $data['supplier_id']     = $supplier_id;
        $data['invoice_date']    = $invoice_date;
        $data['description']     = $description;
        $data['invoice_number']  = $invoice_number;
        $data['delivery_date']   = $delivery_date;
        return $this->sendMessage("success",$data,'success');
        die(); 





    }

    public function actionupdateStockFromTemp()
    {

        $random_bulk_id    = !empty($this->cleanMe(Router::post('bulk_id'))) ? $this->cleanMe(Router::post('bulk_id')) : ''; 

        $supplier_id    = !empty($this->cleanMe(Router::post('supplier_id'))) ? $this->cleanMe(Router::post('supplier_id')) : '';
        $invoice_date    = !empty($this->cleanMe(Router::post('invoice_date'))) ? $this->cleanMe(Router::post('invoice_date')) : '';
        $description    = !empty($this->cleanMe(Router::post('description'))) ? $this->cleanMe(Router::post('description')) : '';
        $invoice_number    = !empty($this->cleanMe(Router::post('invoice_number'))) ? $this->cleanMe(Router::post('invoice_number')) : '';
        $delivery_date    = !empty($this->cleanMe(Router::post('delivery_date'))) ? $this->cleanMe(Router::post('delivery_date')) : '';
        $details_temp = $this->stockmdl->getTempDetails($random_bulk_id);

        if(empty($details_temp)) {

            return $this->sendMessage("error","Something Went Wrong Please try again..",'error');
            die(); 

        }

      
        
        $productList = [];
        foreach($details_temp as $k=>$v){

            $name                 = !empty($v['name']) ? $v['name'] : '';
            $quantity             = !empty($v['quantity']) ? $v['quantity'] : '';
            $price                = !empty($v['price']) ? $v['price'] : '';
            
            $isValid = true;
            $inventoryDetails = $this->stockmdl->getProductByName($name);

            if(empty($name) || empty($quantity) || empty($price)) {

                $isValid = false;

            }

            if(empty($inventoryDetails)) {

                $isValid = false;

            }

            if(!empty($inventoryDetails) && $inventoryDetails['supplier_id']!=$supplier_id) {

                $isValid = false;

            }
            if(!ctype_digit($quantity)) {
                $isValid = false;
            }

            $pattern = '/^(0|[1-9]\d*)(\.\d{2})?$/';
            if (preg_match($pattern, $quantity) == '0') {
               $isValid = false;
            }
            if (preg_match($pattern, $price) == '0') {
               $isValid = false;
            }

            if($isValid) {

                $productList[] = ['product_id'=>$inventoryDetails['id'],'unit'=>$quantity,'price'=>$price];


            }

            


        }


        $stockDetails = [];
        $stockDetails['supplier_id']    = $supplier_id; 
        $stockDetails['invoice_date']   = $invoice_date; 
        $stockDetails['description']    = $description; 
        $stockDetails['invoice_number'] = $invoice_number; 
        $stockDetails['delivery_date']  = $delivery_date;
        

        $params = [];
        $params['stockDetails'] = $stockDetails; 
        $params['productList']  = $productList; 
        $params['bulk_id']      = $random_bulk_id; 



        if($this->stockmdl->addStockImport($params)){



            return $this->sendMessage("success","Successfully Imported Valid Stocks",'success');
            die(); 


        }else{
            return $this->sendMessage("error","Something Went Wrong Please try again..",'error');
            die(); 
        }

        return $this->sendMessage("error","Something Went Wrong Please try again..",'error');
        die(); 



    }

    public function generaterandomid()
    {        

        do {

            $bulk_id = rand(1,10000);

            $isBulkidExist = $this->stockmdl->checkBulk_id($bulk_id);

            $isBulkidExist = !empty($isBulkidExist) ? $isBulkidExist : '';

        } while ($isBulkidExist);

        return $bulk_id;
    }

    public function actionAddStockManually(){
       
        $data['supplier_id']      = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : '';
        $data['invoice_date']     = !empty($_POST['invoice_date']) ? date('Y-m-d',strtotime($_POST['invoice_date'])) : '';
        $data['description']      = !empty($_POST['description']) ? $_POST['description'] : '';
        $data['invoice_number']   = !empty($_POST['invoice_number']) ? $_POST['invoice_number'] : '';
        $data['delivery_date']    = !empty($_POST['delivery_date']) ? date('Y-m-d',strtotime($_POST['delivery_date'])) : '';
        $data['product_list']     = !empty($_POST['product_list']) ? $_POST['product_list'] : '';
        $data['unit']             = !empty($_POST['unit']) ? $_POST['unit'] : '';
        $data['unit_price']       = !empty($_POST['unit_price']) ? $_POST['unit_price'] : '';

        if(empty($data['supplier_id']))
               return $this->sendMessage('error',"Please Select Supplier To Proceed");

        if(empty($data['invoice_date']))
               return $this->sendMessage('error',"Please Enter Invoice Date To Proceed");

        if(empty($data['description']))
               return $this->sendMessage('error',"Please Enter Description To Proceed");

        if(empty($data['invoice_number']))
               return $this->sendMessage('error',"Please Enter Invoice Number To Proceed");

        if(empty($data['delivery_date']))
               return $this->sendMessage('error',"Please Enter Deliveery Date To Proceed");

        for($i=0;$i<count($data['product_list']);$i++){
           
            if(empty($data['product_list'][$i]))
                return $this->sendMessage('error',"Please Select Product To Proceed");

            if(empty($data['unit'][$i]))
                return $this->sendMessage('error',"Please Enter Unit To Proceed");
            if(empty($data['unit_price'][$i]))
              
                return $this->sendMessage('error',"Please Enter Unit price To Proceed");
        
          $this->isNumeric($data['unit'][$i],'Unit');
        
         $this->isNumeric($data['unit_price'][$i],'Unit Price');
        
        }
        
         $success = $this->stockmdl->addStock($data);

        if($success){
             $this->sendMessage('success',"Added Successfufly");
        }
        else{
            return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
        }
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

    public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename = 'Stock List'; 
        $supplier_id        = !empty(Router::post('supplier_id')) ? $this->cleanMe(Router::post('supplier_id')) : '';
        $order_no           = !empty(Router::post('order_no')) ? $this->cleanMe(Router::post('order_no')) : '';
        $invoice_date       = !empty(Router::post('invoice_date')) ? date('Y-m-d',strtotime($this->cleanMe(Router::post('invoice_date')))) : '';
        $delivery_date      = !empty(Router::post('delivery_date')) ? date('Y-m-d',strtotime($this->cleanMe(Router::post('delivery_date')))) : '';
        $page               = !empty(Router::post('page')) ? $this->cleanMe(Router::post('page')) : '';
        
        $page               = (!empty($page)) ? $page : '1'; 
        
        $filter             = ["supplier_id"     => $supplier_id,
                               "order_no"        => $order_no,
                               "invoice_date"    => $invoice_date,
                               "delivery_date"   => $delivery_date,
                               "page"            => $page];
        
        //$user_id  = $this->cleanMe(Router::post('user_id')); 
   
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;       

        $filter['export'] = "export";

        $data = $this->stockmdl->getList($filter);

        
        $csv = "Supplier Name, Contact Details, Invoice Date, Description, Order No, Delivery Date   \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";


        foreach ($data['data'] as $val) {
            $html.= $val['supplier_name'].','.$val['contact_details_exl'].','.date('d-m-Y',$val['invoice_date']).','.$val['description'].','.$val['invoice_number'].','.date('d-m-Y',$val['delivery_date'])."\n"; //Append data to csv

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

    public function actiongetStockDetails(){

        $id            = $this->cleanMe(Router::get('id'));
        $details = $this->stockmdl->getStockDetails($id);
        $this->renderAjax('stock/stock_details_view_modal',['details'=>$details]);

    }


}
