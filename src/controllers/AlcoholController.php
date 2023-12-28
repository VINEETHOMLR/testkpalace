<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Alcohol;
use inc\Root;
use inc\commonArrays;
use src\lib\SimpleXLSX;

class AlcoholController extends Controller {

        public function __construct(){

        parent::__construct();


        $this->mdl       = (new Alcohol);
        $this->pag       =  new Pagination(new Alcohol(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		$this->mainTitle     = 'Inventory Management';
		//$arr                    = commonArrays::getArrays();
		
    }
    public function getInputs()
    {
        $input = [];
        
        $input['name']     = !empty($this->cleanMe(Router::post('name'))) ? $this->cleanMe(Router::post('name')) : ''; 
        $input['brand']     = !empty($this->cleanMe(Router::post('brand'))) ? $this->cleanMe(Router::post('brand')) : ''; 
        $input['category_id']     = !empty($this->cleanMe(Router::post('category'))) ? $this->cleanMe(Router::post('category')) : '';
        $input['supplier_id']     = !empty($this->cleanMe(Router::post('supplier'))) ? $this->cleanMe(Router::post('supplier')) : '';
        $input['vintage']     = !empty($this->cleanMe(Router::post('vintage'))) ? $this->cleanMe(Router::post('vintage')) : '';
        $input['country']     = !empty($this->cleanMe(Router::post('country'))) ? $this->cleanMe(Router::post('country')) : ''; 
        $input['volume']     = !empty($this->cleanMe(Router::post('volume'))) ? $this->cleanMe(Router::post('volume')) : '';
        $input['price']     = !empty($this->cleanMe(Router::post('price'))) ? $this->cleanMe(Router::post('price')) : '';
        $input['foc_id']           = $this->cleanMe(Router::post('foc_id'));
        $input['inventory_id']     = $this->cleanMe(Router::get('inventory_id'));
        
        $input['id']     = !empty($this->cleanMe(Router::get('id'))) ? $this->cleanMe(Router::get('id')) : '';

        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;

        return $input;
    }
     public function getCategoryInputs()
    {
        $input = [];
        $input['name']     = !empty($this->cleanMe(Router::post('name'))) ? $this->cleanMe(Router::post('name')) : '';
        $input['status']     = !empty($this->cleanMe(Router::post('status'))) ? $this->cleanMe(Router::post('status')) : '';
        $input['page']       = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']       = empty($input['page']) ? 0 : 1 ;

        return $input;
    }
    public function getSupplierInputs()
    {
        $input = [];
        $input['name']              = !empty($this->cleanMe(Router::post('name'))) ? $this->cleanMe(Router::post('name')) : '';
        $input['status']            = !empty($this->cleanMe(Router::post('status'))) ? $this->cleanMe(Router::post('status')) : '';
        $input['contact_person']    = !empty($this->cleanMe(Router::post('contact_person'))) ? $this->cleanMe(Router::post('contact_person')) : '';
        $input['contact_no']        = !empty($this->cleanMe(Router::post('contact_no'))) ? $this->cleanMe(Router::post('contact_no')) : '';
        $input['email']             = !empty($this->cleanMe(Router::post('email'))) ? $this->cleanMe(Router::post('email')) : '';
        $input['keyword']             = !empty($this->cleanMe(Router::post('keyword'))) ? $this->cleanMe(Router::post('keyword')) : '';
        $input['page']              = empty($_POST['page']) ? 1 : $this->cleanMe(Router::post('page')) ; 
        $input['load']              = empty($input['page']) ? 0 : 1 ;

        return $input;
    }

    public function actionList() {

         $this->checkPageAccess(16);
         $this->subTitle       = 'Inventory List'; 
         $filter               = $this->getInputs(); 
         $data                 = $this->mdl->getAlcoholList($filter);
         $filter['data']       = $data;
         $filter['categoryArray']   = $this->mdl->getCategoryNames();
         $filter['supplierArray']   = $this->mdl->getSupplierNames();

         $onclick              = "onclick=pageHistory('".$filter['name']."','".$filter['brand']."','".$filter['vintage']."','".$filter['country']."','".$filter['volume']."','".$filter['price']."','".$filter['category_id']."','".$filter['supplier_id']."','".$filter['foc_id']."','***')";
         $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('alcohol/list',$filter);
    }
    public function actionUpdateAlcohol() {
        
        $this->checkPageAccess(30);

        $data['categoryArray']   = $this->mdl->getCategoryNames();
        $data['supplierArray']   = $this->mdl->getSupplierNames();
        
        $data['alcohol']         = [];
        $data['log']             = '';
        $data['divCount']        = 1;

        $this->subTitle  = 'Create Inventory';



        if(isset($_GET['id'])){
            
            $id = $this->cleanMe(Router::get('id'));

            $alcohol = $this->mdl->getInventorydetails($id);
           
            $this->subTitle  = 'Edit Inventory';

            $data['alcohol']    = $alcohol;
            $data['log']     = $id;
            $data['divCount']= count($alcohol);
           
            return $this->render('alcohol/create_alcohol',$data);

        }else
            return $this->render('alcohol/create_alcohol',$data);
    }

    public function actionListCategory() {

         $this->checkPageAccess(15);
         $this->subTitle       = 'Inventory Category List'; 
         $filter               = $this->getCategoryInputs(); 
         
         $data                 = $this->mdl->getCategoryList($filter);

         $filter['data']       = $data;
         
         $onclick              = "onclick=pageHistory('".$filter['name']."','".$filter['status']."','***')";
         $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('alcohol/category_list',$filter);
    }

    public function actionSupplierList() {

         // $this->checkPageAccess(15);

         $this->subTitle       = 'Inventory Supplier List'; 
         $filter               = $this->getSupplierInputs(); 
         
         $data                 = $this->mdl->getSupplierList($filter);

         $filter['data']       = $data;
         
         $onclick              = "onclick=pageHistory('".$filter['name']."','".$filter['status']."','***')";
         $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('alcohol/supplier_list',$filter);
    }

    public function actionAddSupplier() {
        
         $this->checkPageAccess(51);
      
        $name               = $this->cleanMe(Router::post('name'));
        $contact_person     = $_POST['contact_person'];
        $contact_no         = $_POST['contact_no'];
        $email              = $this->cleanMe(Router::post('email'));
        
        $edit       = $this->cleanMe(Router::post('editID')); 
        $ip         = [];
        
        $ip         = ['name'=>$name,'contact_person'=>$contact_person,'contact_no'=>$contact_no,'email'=>$email,'edit'=>$edit];

        if(empty($name)) {
            return $this->sendMessage('error',"Please Enter Name To Proceed");
        }

        if(!empty($email)) {
            $this->emailvalidate($email,'Email');
        }

        /*if(empty($contact_person)) {
            return $this->sendMessage('error',"Please Enter Contact Person To Proceed");
        }

        if(empty($contact_no)) {
            return $this->sendMessage('error',"Please Enter Contact Number To Proceed");
        }

        if(!empty($contact_no)){
            $this->validate_mobile($contact_no);
        }*/


        for($i=0;$i<count($contact_person);$i++){
            if(empty($contact_person[$i]))
                return $this->sendMessage('error',"Please Enter Contact Person To Proceed");

            if(empty($contact_no[$i]))
                return $this->sendMessage('error',"Please Enter Contact Number To Proceed");
            if(!empty($contact_no[$i])){
                $this->validate_mobile($contact_no[$i]);
            }
        }

        $count = $this->mdl->checkExistSupplier($ip);
        if(!empty($count)){
            return $this->sendMessage('error',"This Supplier Name Already Exist");
        }
        if(!empty($edit)){
           $success = $this->mdl->updateSupplier($ip);
           $msg     = 'Supplier Details Updated Successfully';
        }else{

           $success = $this->mdl->addSupplier($ip);
           $msg     = 'Supplier Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }
    public function actionUpdateSupplier() {
        
        
        $data['supplier_name']    = '';
        $data['contact_person']    = '';
        $data['contact_no']    = '';
        $data['email']    = '';
        $data['id']               = '';
      
        $this->subTitle           = 'Create Inventory Supplier';
       
        if(isset($_GET['id'])){
            
            $id = $this->cleanMe(Router::get('id'));
        
            $this->checkPageAccess(52);
            
            $details = $this->mdl->getSupplierCategoryById($id);
   
            $this->subTitle  = 'Edit Inventory Supplier';

            $is_valid_json = $this->isJson($details['contact_person']);
            if($is_valid_json) {

                $details['contact_person'] = $details['contact_person'];
                $details['contact_no']     = $details['contact_no'];

            }else{

                $details['contact_person'] = json_encode(array($details['contact_person']));
                $details['contact_no'] = json_encode(array($details['contact_no']));

                    
            }
            $data['supplier_name']      = $details['supplier_name'];
            $data['contact_person']     = $details['contact_person'];
            $data['contact_no']         = $details['contact_no'];
            $data['email']              = $details['email'];
            $data['id']                 = $this->cleanMe(Router::get('id'));

            return $this->render('alcohol/supplier_create_category',$data);

        }else
            $this->checkPageAccess(51);
            return $this->render('alcohol/supplier_create_category',$data);
    }

    public function isJson($json) {
        
        $result = json_decode($json);

        
        if ($result === FALSE || empty($result)) {
            return false;
        }
        return true;
    }

    public function actionSupplierDelete(){
        
        $this->checkPageAccess(53);

        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteSupplierCategory($ID);

        if($delete){
            return $this->sendMessage('success',"Supplier Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }


    public function actionUpdateSupplierCategoryStatus() { 
         $this->checkPageAccess(53);
          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateSupplierCategoryStatus($id,$status);

          $this->sendMessage('success','Status Updated Successfully');
           
          return false;
    }
    
        
    public function actionUpdate() {
        
        
        $data['name']           = '';
        $data['name_ch']        = '';
        $data['id']             = '';
      
        $this->subTitle         = 'Create Category';
       
        if(isset($_GET['id'])){
        $id = $this->cleanMe(Router::get('id'));
        $this->checkPageAccess(27);
            $details = $this->mdl->getCategoryById($id);
   
            $this->subTitle  = 'Edit Category';

            $data['name']    = $details['name'];
            $data['name_ch']    = $details['name_ch'];
            $data['id']      = $id;
           
            return $this->render('alcohol/create_category',$data);

        }else
            $this->checkPageAccess(26);
            return $this->render('alcohol/create_category',$data);
    }
    public function actionAddAlcohol() {

        $this->checkPageAccess(29);
        $totalCount    = $this->cleanMe(Router::post('totalCount'));  
        $ip            = [];
        $maxsize       = 8388608; 
        $newFile_org        = [];
        $file_acceptable  = array('image/jpeg', 'image/png','image/jpg','image/svg+xml');
        $file_upload     = 0 ;
 
        $edit             = $this->cleanMe(Router::post('editID')); 


        for($i=1;$i<=$totalCount;$i++) {

            $foc[$i]            = $this->cleanMe(Router::post('foc'.$i));
            $item_date[$i]      = $this->cleanMe(Router::post('item_date'.$i));
            $name[$i]           = $this->cleanMe(Router::post('name'.$i));
            $supplier[$i]       = $this->cleanMe(Router::post('supplier'.$i)); 
            $cat[$i]            = $this->cleanMe(Router::post('cat'.$i)); 
            $brand[$i]          = $this->cleanMe(Router::post('brand'.$i)); 
            // $type[$i]          = $this->cleanMe(Router::post('type'.$i));
            $vintage[$i]        = $this->cleanMe(Router::post('vintage'.$i));
            $country[$i]        = $this->cleanMe(Router::post('country'.$i));
            $volume[$i]         = $this->cleanMe(Router::post('volume'.$i));
            $percent[$i]        = $this->cleanMe(Router::post('percent'.$i));
            $price[$i]          = $this->cleanMe(Router::post('price'.$i));
            $quantity[$i]       = $this->cleanMe(Router::post('quantity'.$i));
            $file_name[$i]      = $_FILES['filename'.$i]['name'];
        
            if(empty($item_date[$i]))
                return $this->sendMessage('error',"Please Enter Item Date To Proceed");

            if(empty($name[$i]))
                return $this->sendMessage('error',"Please Enter Name To Proceed");

            /*if(empty($supplier[$i]))
                return $this->sendMessage('error',"Please Select Supplier To Proceed");*/

            if(empty($cat[$i]))
                return $this->sendMessage('error',"Please Select Category To Proceed");

            if(empty($brand[$i]))
                return $this->sendMessage('error',"Please Enter Brand To Proceed");

           
            if(empty($vintage[$i]))
                return $this->sendMessage('error',"Please Enter Vintage To Proceed");
            if(empty($volume[$i]))
                return $this->sendMessage('error',"Please Enter Volume To Proceed");
            /*if(empty($percent[$i]))
                return $this->sendMessage('error',"Please Enter percent To Proceed");*/
            
            /*if(empty($price[$i]))
                return $this->sendMessage('error',"Please Enter Price To Proceed");*/
            
            /*if(!empty($price[$i])){
                if(!is_numeric($price[$i]))
                {
                   return $this->sendMessage('error',"Please Enter Valid Price To Proceed"); 
                }
            }*/
            if(empty($quantity[$i]))
                return $this->sendMessage('error',"Please Enter Quantity To Proceed");

            if(!empty($quantity[$i])){
                if(!is_numeric($quantity[$i]))
                {
                   return $this->sendMessage('error',"Please Enter Valid Quantity To Proceed"); 
                }
            }

            if(empty($edit)){
                if(empty($file_name[$i]) && empty($file_upload)){
                   return $this->sendMessage('error',"Please Upload File To Proceed");
               }
            }

            if(!empty($file_name[$i])){ 
                if((!in_array($_FILES['filename'.$i]['type'], $file_acceptable)) && (!empty($_FILES["filename".$i]["type"]))){
                    return $this->sendMessage('error','Invalid File Type. jpeg, jpg, png ,svg types are accepted');
                }
                if($_FILES['filename'.$i]['size']==0){
                    return $this->sendMessage('error','Invalid File. Try another file');
                }

               $filename[$i]    = $file_name[$i]; 
               $temp_name[$i]   = $_FILES['filename'.$i]['tmp_name'];

               $size    = getimagesize($temp_name[$i]);
               $sizeArr = explode('"',$size[3]);
               $width   = trim($sizeArr[1]);
               $height  = trim($sizeArr[3]);
               $path_parts[$i]  = pathinfo($filename[$i]);
               $extension[$i]   = $path_parts[$i]['extension'];
               $extension_check = strtolower($extension[$i]); 
               
               if($extension_check!='png'){

                    return $this->sendMessage("error","Only PNG image allowed"); 

               }
               if($width != "180" || $height != "450"){   
                    
                    return $this->sendMessage("error","Image Dimension is 180px x 450px");
               }
               // if($extension[$i]!='svg') {

               //    if($width != "180" || $height != "450"){
               //    return $this->sendMessage("error","Image Dimension is 180px x 450px"); 
               //    }
               // } else {

               //  preg_match("#viewbox=[\"']\d* \d* (\d*) (\d*)#i", file_get_contents($temp_name[$i]), $d);
               //  $width_in   = $d[1];
               //  $height_in  = $d[2];

               //    if($width_in != "180" || $height_in != "450"){   
               //     return $this->sendMessage("error","Image Dimension is 180px x 450px");
               //    } 
               // }

              
               $newFile_org[$i] = $path_parts[$i]['filename'].time().'.'.$extension[$i];
               $target_file[$i] = FILEUPLOADPATH.'inventory/'.$newFile_org[$i];
               $FileType        = pathinfo($target_file[$i],PATHINFO_EXTENSION);

               $file_type      = 1;

               move_uploaded_file($temp_name[$i], $target_file[$i]);
            }
        }
         
            if(!empty($edit)){

               $check_entry  = $this->mdl->callsql("SELECT id FROM `inventory` WHERE  id='$edit'","value");

               $file_upload  = empty($check_entry) ? 0 : 1;
            }


        $ip['foc']          = $foc;
        $ip['item_date']    = $item_date;
        $ip['name']         = $name;
        $ip['supplier']     = $supplier;
        $ip['category']     = $cat;
        $ip['brand']        = $brand;
        $ip['type']         = '';
        $ip['vintage']      = $vintage;
        $ip['country']      = $country;
        $ip['volume']       = $volume;
        $ip['percent']      = $percent;
        $ip['price']        = $price;
        $ip['quantity']     = $quantity;
        $ip['edit']         = $edit;
        $ip['totalCount']   = $totalCount;
        
        //proceed to upload files

        $ip['file']      = $newFile_org;

       
        if(!empty($edit)){
           $success = $this->mdl->UpdateAlcoholInv($ip);
           $msg     = 'Inventory Details Updated Successfully';
        }else{

           $success = $this->mdl->addAlcoholInv($ip);
           $msg     = 'Inventory Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }
    public function actionAdd() {
        
         $this->checkPageAccess(26);
      
        $name       = $this->cleanMe(Router::post('name'));
        $name_ch    = $this->cleanMe(Router::post('name_ch'));
        $edit       = $this->cleanMe(Router::post('editID')); 
        $ip         = [];
        
        $ip         = ['name'=>$name,'name_ch'=>$name_ch,'edit'=>$edit];
        if(empty($name)) {
            return $this->sendMessage('error',"Please Enter Name To Proceed");
        }
        if(empty($name_ch)) {
            return $this->sendMessage('error',"Please Enter Chinese Name To Proceed");
        }
        $count = $this->mdl->checkExist($name,$edit);
        if(!empty($count)){
            return $this->sendMessage('error',"This Category Name Already Exist");
        }
        if(!empty($edit)){
           $success = $this->mdl->updateCategory($ip);
           $msg     = 'Category Details Updated Successfully';
        }else{

           $success = $this->mdl->addCategory($ip);
           $msg     = 'Category Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }

   

    public function actionUpdateAlcoholCategoryStatus() { 
         $this->checkPageAccess(28);
          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateAlcoholCategoryStatus($id,$status);

          $this->sendMessage('success','Status Updated Successfully');
           
          return false;
    }

    public function actionDelete(){
         $this->checkPageAccess(28);

        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteCategory($ID);

        if($delete){
            return $this->sendMessage('success',"Category Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }
     public function actionDeleteAlcohol(){
         $this->checkPageAccess(31);
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteAlcohol($ID);

        if($delete){
            return $this->sendMessage('success',"Alcohol Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }
    public function actionExportAlcohol() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename = 'Inventory List'; 
        $input=[];
        $input['name']      = $this->cleanMe(Router::post('name')); 
        $input['brand']     = $this->cleanMe(Router::post('brand'));
        // $input['type']      = $this->cleanMe(Router::post('type'));
        $input['vintage']   = $this->cleanMe(Router::post('vintage')); 
        $input['country']   = $this->cleanMe(Router::post('country'));
        $input['volume']    = $this->cleanMe(Router::post('volume'));
        $input['price']     = $this->cleanMe(Router::post('price'));
        $input['category_id']     = $this->cleanMe(Router::post('category'));
        $input['supplier_id']     = $this->cleanMe(Router::post('supplier'));
        $input['foc_id']     = $this->cleanMe(Router::post('foc_id'));

        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter = $input;
        $filter['export'] = "export";

        $data = $this->mdl->getAlcoholList($filter);
        $csv = "FOC,Item Date, Product Name ,Category, Supplier, Brand, Vintage,Country,Volume,Alcohol % ,Unit Price, Selling Price,Balance Quantity  \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";
        foreach ($data['data'] as $his) {  

            $html.= html_entity_decode(html_entity_decode(html_entity_decode($his['foc'])).','.html_entity_decode($his['item_date'])).','.html_entity_decode(html_entity_decode($his['name'])).','.html_entity_decode(html_entity_decode($his['category'])).','.html_entity_decode(html_entity_decode($his['supplier'])).','.$his['brand'].','.$his['vintage'].','.$his['country'].','.$his['volume'].','.$his['alcohol_percent'].','.$his['unit_price'].','.$his['price'].','.$his['quantity']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename;
        $log_data = array(
            "export" => $filename." List"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        //$this->mdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }




    public function actionImportInventoryList()
    {


        if(empty($_FILES['file'])) {
            
            $data['msg'] = 'Please select file to upload';

            return $this->sendMessage("error",$data);

        }


        if(!empty($_FILES['file'])){     
            $filename   = $_FILES['file']['name'];
            $temp_name  = $_FILES['file']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('CSV','csv');

            if(!in_array($extension, $image_array)){

                $data['msg'] = 'Please Select Valid Format';
                $data['showlistpopup'] = false;
                return $this->sendMessage("error",$data);
            }

           
            $newFile_org = 'CreateInventoryBulk_'.$this->adminID.'_'.time().'.'.$extension;
            $target_file = BASEPATH."web/upload/inventory/".$newFile_org; 
            $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
            $path = pathinfo($target_file);

            if (!file_exists($path['dirname'])) {
                mkdir($path['dirname'], 0777, true);
            }

            if(!move_uploaded_file ($temp_name, $target_file)){

               $data['msg'] = 'Something Went Wrong...';
               $data['showlistpopup'] = false;
               return $this->sendMessage("error",$data,"error");
            }

          }

            $csvFile = file($target_file);
            $data = [];
            foreach ($csvFile as $line) {
                $data[] = str_getcsv($line);
            }
          if(empty($data)){
               
              $data['msg'] = 'Selected excel file is empty';
              $data['showlistpopup'] = false;  
              return $this->sendMessage("error",$data,"error");

          } 
          $error_array =[];
          $error_status = '0';


         $supplierarray = $this->mdl->getSupplierCategoryAll();
         $categoryarray = $this->mdl->getCategoryAll();

        $random_bulk_id = $this->generaterandomid();

        foreach($data as $k=>$v)
        {

            if($k!='0') 
            {

                  $rowindex             = $k+1;
                  $slno                 = !empty($v[0]) ? $v[0] : '';
                  $name                 = !empty($v[1]) ? $v[1] : '';
                  $supplier_id          = !empty($v[2]) ? $v[2] : '';
                  $cateogry_id          = !empty($v[3]) ? $v[3] : '';
                  $brand                = !empty($v[4]) ? $v[4] : '';
                  $vintage              = !empty($v[5]) ? $v[5] : '';
                  $country              = !empty($v[6]) ? $v[6] : '';
                  $volume               = !empty($v[7]) ? $v[7] : '';
                  $al_percentage        = !empty($v[8]) ? $v[8] : '';
                  $price                = !empty($v[9]) ? $v[9] : '';
                  $quantity             = !empty($v[10]) ? $v[10] : '';



                $ip['name']          = $name;
                $ip['supplier']      = $supplier_id;
                $ip['category']      = $cateogry_id;
                $ip['brand']         = $brand;
                $ip['type']          = '';
                $ip['vintage']       = $vintage;
                $ip['country']       = $country;
                $ip['volume']        = $volume;
                $ip['percent']       = $al_percentage;
                $ip['price']         = $price;
                $ip['quantity']      = $quantity;
                $ip['bulk_id']       = $random_bulk_id;
                $ip['upload_filename'] = $newFile_org;
                $this->mdl->addTempAlcoholInv($ip);

                  // $errors = []; 
                  // if(empty($slno)) {
                  //     $errors[] = 'Slno cannot be empty';
                  //     $error_status = '1';
                  // }

                  // if(empty($name)) {
                  //     $errors[] = 'Name cannot be empty';
                  //     $error_status = '1';
                  // }

                  // if(empty($supplier_id)) {
                      
                  //     $errors[] = "Supplier cannot be empty ";
                  //     $error_status = '1';
                      
                  // }


                  // if(!empty($supplier_id)) {
                    
                    
                  //   $supplier_key = $this->mdl->getSupplierCategoryByName($supplier_id);

                  //   if(empty($supplier_key)) {

                  //       $errors[] = "Please add a valid Supplier ".$supplier_id;
                  //       $error_status = '1';
                                  
                  //   }
                            
                  // }

                  // if(empty($cateogry_id)) {
                      
                  //     $errors[]= "Category cannot be empty ";
                  //     $error_status = '1';
                      
                  // }

                  // if(!empty($cateogry_id)) {

                  //   $category_details = $this->mdl->getCategoryByName($cateogry_id);

                  //   if(empty($category_details['name'])) {

                  //       $errors[] = "Please add a valid category ".$cateogry_id;
                  //       $error_status = '1';
                                  
                  //   }
                            
                  // }
                  // if(empty($brand)) {
                      
                  //     $errors[]= "Brand cannot be empty ";
                  //     $error_status = '1';
                      
                  // }
                  // if(empty($vintage)) {
                      
                  //     $errors[]= "vintage cannot be empty ";
                  //     $error_status = '1';
                      
                  // }
                  // if(empty($al_percentage)) {
                      
                  //     $errors[]= "Alcohol Percentage cannot be empty ";
                  //     $error_status = '1';
                      
                  // }
                  // if(empty($price)) {
                      
                  //     $errors[]= "Price cannot be empty ";
                  //     $error_status = '1';
                      
                  // }
                  // if(!empty($price)){
                  //   if(!is_numeric($price))
                  //   {
                  //          $errors[]= "Please Enter Valid Price To Proceed";
                  //          $error_status = '1';
                  //   }
                  // }
                  // if(empty($quantity)) {
                      
                  //     $errors[]= "Quantity cannot be empty ";
                  //     $error_status = '1';
                      
                  // }
                  // if(!empty($quantity)){
                  //   if(!is_numeric($quantity))
                  //   {
                  //          $errors[]= "Please Enter Valid Quantity To Proceed";
                  //          $error_status = '1';
                  //   }
                  // }


                  // $error_array[]  = array('row'=>$rowindex,'errorList'=>$errors);
               
            }

        } 

       // if($error_status == '1') {

       //    $this->mdl->DeleteTempAlcoholInv($random_bulk_id);
           
       //     $html = $this->createErrorHtml($error_array);
       //     $data['msg'] = '';
       //     $data['showlistpopup'] = true;  
       //     $data['html'] = $html;  
       //     return $this->sendMessage("error",$data,"error");
       //     die();
       // }

       //find valid or invalid details

       //check if exists already
       $details_temp = $this->mdl->getTempDetails($random_bulk_id);
          $html = [];
          $html['header'] = '';
          $html['valid'] = '';
          $html['invalid'] = '';
          $html['footer'] = '';
          $html['header'] = '
                        <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                        <thead> 
                            <tr>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Category</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Supplier</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Brand</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Name</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Vintage</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Country</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Volume</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Alcohol Percent</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Price</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Quantity</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                            </tr>
                        </thead>';

          foreach($details_temp as $k=>$v){

                  $name                 = !empty($v['name']) ? $v['name'] : '';
                  $supplier_id          = !empty($v['supplier_id']) ? $v['supplier_id'] : '';
                  $cateogry_id          = !empty($v['category_id']) ? $v['category_id'] : '';
                  $brand                = !empty($v['brand']) ? $v['brand'] : '';
                  $vintage              = !empty($v['vintage']) ? $v['vintage'] : '';
                  $country              = !empty($v['country']) ? $v['country'] : '';
                  $volume               = !empty($v['volume']) ? $v['volume'] : '';
                  $al_percentage        = !empty($v['alcohol_percent']) ? $v['alcohol_percent'] : '';
                  $price                = !empty($v['price']) ? $v['price'] : '';
                  $quantity             = !empty($v['quantity']) ? $v['quantity'] : '';

                 $category_details = $this->mdl->getCategoryByName($cateogry_id);
                 $current_category_id = !empty($category_details) ? $category_details['id']: '';

                 $supplier_details = $this->mdl->getSupplierCategoryByName($supplier_id);
                 $current_supplier_id = !empty($supplier_details) ? $supplier_details['id']: '';
                 $action = '';
                if(!empty($current_category_id) && !empty($current_supplier_id))
                {
                    $params = [];
                    $params['category_id'] = $current_category_id;
                    $params['supplier_id'] = $current_supplier_id;
                    $params['name'] = $name;
                    $params['price'] = $price;
                    $params['country'] = $country;
                    $exists = $this->mdl->checkExistInventrory($params);
                    $action = '<button class="btn btn-info" onclick="return filterWithId('.$exists.');">View</button>';
                }else{
                    $exists = 0;
                }
                  

                if($exists!=0)
                {
                    $html['valid'].= '<tr role="row" class="odd">
                        <td>'.htmlspecialchars_decode($category_details['name']).'</td>
                        <td>'.htmlspecialchars_decode($supplier_details['supplier_name']).'</td>
                        <td>'.$brand.'</td>
                        <td>'.$name.'</td>
                        <td>'.$vintage.'</td>
                        <td>'.$country.'</td>
                        <td>'.$volume.'</td>
                        <td>'.$al_percentage.'</td>
                        <td>'.$price.'</td>
                        <td>'.$quantity.'</td>
                        <td>'.$action.'</td>
                    </tr>';
                
                }else{

                    $html['invalid'] .= '<tr role="row" class="table-danger">
                        <td>'.htmlspecialchars_decode($cateogry_id).'</td>
                        <td>'.htmlspecialchars_decode($supplier_id).'</td>
                        <td>'.$brand.'</td>
                        <td>'.$name.'</td>
                        <td>'.$vintage.'</td>
                        <td>'.$country.'</td>
                        <td>'.$volume.'</td>
                        <td>'.$al_percentage.'</td>
                        <td>'.$price.'</td>
                        <td>'.$quantity.'</td>
                    </tr>';

                }




          }

          $html['footer'] = '</tbody></table>';

           $data['msg'] = '';
           $data['showlistpopup'] = true;  
           $data['html']['header'] = $html['header'];
           $data['html']['valid'] = $html['valid'];
           $data['html']['invalid'] = $html['invalid'];
           $data['html']['footer'] = $html['footer'];
           $data['bulk_id'] = $random_bulk_id;

           return $this->sendMessage("success",$data,'success');
            die(); 
    }
    
   function createErrorHtml($errorList){
      
      $table = '<table class="table">';
      $table .= '<tr><th>No</th><th>Row</th><th>Error</th></tr>';
      foreach($errorList as $k=>$v){
          if(!empty($v['errorList']))
          {
              $table.='<tr>';
              $table.='<td>';
              $table.= $k+1;
              $table.='</td>';
              $table.='<td>';
              $table.= $v['row'];
              $table.='</td>';
              $table.='<td>';
              $table.= implode(',',$v['errorList']);
              $table.='</td>';
              $table.='</tr>';
          }
          
      }

       $table .= '</table>';

       return $table;
    }


    public function actionupdateInventroryFromTemp()
    {

        $random_bulk_id    = !empty($this->cleanMe(Router::post('bulk_id'))) ? $this->cleanMe(Router::post('bulk_id')) : ''; 
        $details_temp = $this->mdl->getTempDetails($random_bulk_id);

          foreach($details_temp as $k=>$v){
              
              // if($k!='0') {
                
                  // $rowindex             = $k+1;
                  // $slno                 = !empty($v[0]) ? $v[0] : '';
                  $name                 = !empty($v['name']) ? $v['name'] : '';
                  $supplier_id          = !empty($v['supplier_id']) ? $v['supplier_id'] : '';
                  $cateogry_id          = !empty($v['category_id']) ? $v['category_id'] : '';
                  $brand                = !empty($v['brand']) ? $v['brand'] : '';
                  $vintage              = !empty($v['vintage']) ? $v['vintage'] : '';
                  $country              = !empty($v['country']) ? $v['country'] : '';
                  $volume               = !empty($v['volume']) ? $v['volume'] : '';
                  $al_percentage        = !empty($v['alcohol_percent']) ? $v['alcohol_percent'] : '';
                  $price                = !empty($v['price']) ? $v['price'] : '';
                  $quantity             = !empty($v['quantity']) ? $v['quantity'] : '';

                $category_details = $this->mdl->getCategoryByName($cateogry_id);
                $current_category_id = !empty($category_details) ? $category_details['id']: '';

                $supplier_details = $this->mdl->getSupplierCategoryByName($supplier_id);
                $current_supplier_id = !empty($supplier_details) ? $supplier_details['id']: '';
                if(!empty($current_category_id) && !empty($current_supplier_id))
                {
                    $params = [];
                    $params['category_id'] = $current_category_id;
                    $params['supplier_id'] = $current_supplier_id;
                    $params['name'] = $name;
                    $params['price'] = $price;
                    $params['country'] = $country;
                    $exists_id = $this->mdl->checkExistInventrory($params);
                }else
                {
                    $exists_id = 0;
                }
                if($exists_id!=0)
                {
                    $ip['name']          = $name;
                    $ip['supplier']      = $current_supplier_id;
                    $ip['category']      = $current_category_id;
                    $ip['brand']         = $brand;
                    $ip['type']          = '';
                    $ip['vintage']       = $vintage;
                    $ip['country']       = $country;
                    $ip['volume']        = $volume;
                    $ip['percent']       = $al_percentage;
                    $ip['price']         = $price;
                    $ip['quantity']      = $quantity;
                    $ip['id']            = $exists_id;
                  
                  $response = $this->mdl->UpdatewithImportAlcoholInv($ip);
                  
                  if($response)
                  {

                    $this->mdl->DeleteTempAlcoholInvByid($v['id']);

                  }else{
                    
                    return $this->sendMessage("error","Something Went Wrong Please try again..",'error');
                    die(); 

                  }
               }else{

                    $this->mdl->DeleteTempAlcoholInvByid($v['id']);

               }
            }

            return $this->sendMessage("success","Successfully Imported Valid Inventory",'success');
            die(); 
    }
    
    public function actionDeleteInventroryFromTemp(){

        $random_bulk_id   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->DeleteTempAlcoholInv($random_bulk_id);
    
        if($delete){
            return $this->sendMessage('success',"Inventory Not Imported");
        }else{
           return $this->sendMessage("error","Something Went Wrong.."); 
        }

    }

    public function generaterandomid()
    {        

        do {

            $bulk_id = rand(1,10000);

            $isBulkidExist = $this->mdl->checkBulk_id($bulk_id);

            $isBulkidExist = !empty($isBulkidExist) ? $isBulkidExist : '';

        } while ($isBulkidExist);

        return $bulk_id;
    }

    private function emailvalidate($var,$attr) {
        if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
         echo $this->sendMessage("error",'Enter Valid Email'); exit();
        }

    }

    private function validate_mobile($mobile){
       if (!preg_match('/^[0-9]+$/', $mobile)) {
         echo $this->sendMessage("error",Root::t('subadmin','E1')); exit();
        }

        if(strlen($mobile) != 8){
           echo $this->sendMessage("error",'Invalid Mobile Number'); exit;
        }
    }


}

