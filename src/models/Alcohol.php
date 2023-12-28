<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Alcohol extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "inventory";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
    }
     public function getAlcoholList($data)
    {
        // print_r($data);die();
   
        $where = ' WHERE status=0 ';

        if(!empty($data['name'])){
            $where .= " AND name LIKE '%$data[name]%' ";
        }
        if(!empty($data['brand'])){
            $where .= " AND brand LIKE '%$data[brand]%' ";
        }
        if($data['foc_id']!=''){
            $where .= " AND foc LIKE '%$data[foc_id]%' ";
        }
        if(!empty($data['type'])){
            $where .= " AND type LIKE '%$data[type]%' ";
        }
         if(!empty($data['vintage'])){
            $where .= " AND vintage LIKE '%$data[vintage]%' ";
        }
        if(!empty($data['country'])){
            $where .= " AND country LIKE '%$data[country]%' ";
        }
        if(!empty($data['volume'])){
            $where .= " AND volume LIKE '%$data[volume]%' ";
        }
         if(!empty($data['price'])){
            $where .= " AND price LIKE '%$data[price]%' ";
        }
        if(!empty($data['category_id'])){
            $where .= " AND category_id = '$data[category_id]' ";
        }
        if(!empty($data['supplier_id'])){
            $where .= " AND supplier_id = '$data[supplier_id]' ";
        }

        if(!empty($data['id'])){
            $where .= " AND id ='$data[id]' ";
        }
        if(!empty($data['inventory_id'])){
            $where .= " AND id ='$data[inventory_id]' ";
        }
        $count  = $this->callsql("SELECT count(DISTINCT id) FROM inventory $where",'value');
        if(!empty($data['export'])){
        $this->query("SELECT * FROM inventory $where  ORDER BY id DESC ");
        }else{
        $pagecount = ($data['page'] - 1) * $this->perPage;
        $this->query("SELECT * FROM inventory $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

               
                $result['data'][$key]['item_date'] = !empty($value['item_date']) ? date("d-m-Y",$value['item_date']) : '-';
                $result['data'][$key]['foc']        = (empty($value['foc'])) ? 'No FOC' : 'FOC';
                $result['data'][$key]['category']   =$this->callsql("SELECT name FROM category WHERE id=$value[category_id]",'value');
                $result['data'][$key]['supplier']   =$this->callsql("SELECT supplier_name FROM supplier WHERE id=$value[supplier_id]",'value');
                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Alcohol/UpdateAlcohol/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';

                if(empty($value['status'])){
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }else{
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }

                $result['data'][$key]['status'] = $status;
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = !empty($data['page'])?$data['page']:'1';
        $result['perPage'] = $this->perPage;
        return $result;
    }
    public function getCategoryList($data)
    {
   
        $where = ' WHERE status=0 ';

        if($data['status']!=''){
            $where .= " AND status = '$data[status]' ";
        }

        if(!empty($data['name'])){
            $where .= " AND name LIKE '%$data[name]%' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(DISTINCT id) FROM category $where",'value');
        $this->query("SELECT * FROM category $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

               
                //$result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
                $result['data'][$key]['name']   = $value['name'];
                $result['data'][$key]['status']   = $value['status'];
                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Alcohol/Update/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';

                if(empty($value['status'])){
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }else{
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }

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

    public function getInventorydetails($id)
    {
        $result = $this->callsql("SELECT * FROM `inventory` WHERE id='$id' ","rows");
        
        foreach($result as $key => $value)
        {
            $result[$key]['item_date'] = !empty($value['item_date']) ? date("d-m-Y",$value['item_date']) : '-';
        }

        return $result;
    }
    public function getSupplierList($data)
    {
   
        $where = ' WHERE status=0 ';

        if($data['status']!=''){
            $where .= " AND status = '$data[status]' ";
        }

        if(!empty($data['keyword'])){
            $where .= " AND supplier_name LIKE '%".$data['keyword']."%' || contact_person LIKE '%".$data['keyword']."%' || contact_no LIKE '%".$data['keyword']."%' || email LIKE '%".$data['keyword']."%' ";
        }

        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(DISTINCT id) FROM supplier $where",'value');
        $this->query("SELECT * FROM supplier $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        $result = ['data' => $this->resultset()];
        foreach ($result['data'] as $key => $value) {

               
                //$result['data'][$key]['datetime'] = date("d-m-Y H:i:s",$value['createtime']);
                $result['data'][$key]['name']   = $value['supplier_name'];
                $result['data'][$key]['status']   = $value['status'];
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
                $result['data'][$key]['contact_details'] = $txt;
                $result['data'][$key]['contact_details_exl'] = $excel_txt;

                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Alcohol/updateSupplier/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';

                if(empty($value['status'])){
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }else{
                     $status = '<label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].');"></span></label>';
                }

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

    public function isJson($json) {
        
        $result = json_decode($json);

        
        if ($result === FALSE || empty($result)) {
            return false;
        }
        return true;
    }

    public function UpdateAlcoholCategoryStatus($id,$status){

      $this->callsql("UPDATE `category` SET status='$status' WHERE id='$id'");
      $category_name = $this->getCategoryById($id);
      $this->adminActivityLog("Category Status updated - " .$category_name['name']." id - ".$id);
      return true;
    }

    public function UpdateSupplierCategoryStatus($id,$status){

      $this->callsql("UPDATE `supplier` SET status='$status' WHERE id='$id'");
      $supplier_name = $this->getSupplierCategoryById($id);
      $this->adminActivityLog("Inventory Supplier Status updated - " .$supplier_name['supplier_name']." id - ".$id);
      return true;
    }

    public function deleteCategory($ID){
      
      $this->query("UPDATE `category` SET status= 1 WHERE id='$ID'");
      $this->execute();
      $category_name = $this->getCategoryById($ID);
      $this->adminActivityLog("Inventory Category Deleted - " .$category_name['name']. " id -".$ID);

      return true;
   }
   public function deleteSupplierCategory($ID){
      
      $this->query("UPDATE `supplier` SET status= 1 WHERE id='$ID'");
      $this->execute();
      $supplier_name = $this->getSupplierCategoryById($ID);
      $this->adminActivityLog("Inventory Supplier Deleted - " .$supplier_name['supplier_name']. " id -".$ID);

      return true;
   }
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }
     public function updateCategory($ip){

        $time   = time();
        $name   = htmlspecialchars_decode($ip['name']);
        $name   = str_replace('&amp;', '&', $name);

        $name_ch = htmlspecialchars_decode($ip['name_ch']);
        $name_ch = str_replace('&amp;', '&', $name_ch);

        $this->query("UPDATE category SET name='$name', name_ch='$name_ch'  WHERE `id`='$ip[edit]'");
        $this->execute();
          
        $this->adminActivityLog("Updated Category - ". $name . " id -".$ip['edit']);

        return true;
    }
    public function addCategory($ip){

        $time  = time(); 
        $status = 0;
       
        $this->query("INSERT INTO category SET name='".$ip['name']."', name_ch='".$ip['name_ch']."',status='$status',createtime='$time',createip='$this->IP',createid='$this->adminID'");
            
        $this->execute();
    
        $this->adminActivityLog("Category added - " . $ip['name']);

        return true;
    }
    public function updateSupplier($ip){

        $time   = time();
        $name = str_replace('&amp;', '&', $ip['name']);
        
         $this->query("UPDATE supplier SET supplier_name='".$name."',contact_person='".json_encode($ip['contact_person'])."',contact_no='".json_encode($ip['contact_no'])."',email='".$ip['email']."'  WHERE `id`='$ip[edit]'");
          
         $this->execute();
          
        $this->adminActivityLog("Updated Inventory Supplier - " . $name . " id -".$ip['edit'] );

        return true;
    }
    public function addSupplier($ip){

        $time  = time(); 
        $status = 0;
        $this->query("INSERT INTO supplier SET supplier_name='".$ip['name']."',contact_person='".json_encode($ip['contact_person'])."',contact_no='".json_encode($ip['contact_no'])."',email='".$ip['email']."',status='$status'");
            
        $this->execute();
    
        $this->adminActivityLog("Inventory Supplier added - ".$ip['name']);

        return true;
    }

    public function getCategoryById($id){

        $time   = time();
   
        $details=$this->callsql("SELECT * FROM category WHERE `id`='$id'","row");
    
        return $details;
    }
    public function getSupplierCategoryById($id){

        $time   = time();
   
        $details=$this->callsql("SELECT * FROM supplier WHERE `id`='$id'","row");
    
        return $details;
    }
    public function getInventoryById($id){
   
        $details=$this->callsql("SELECT * FROM inventory WHERE `id`='$id'","row");
    
        return $details;
    }

    public function getCategoryByName($name){

        $time   = time();
        $details=$this->callsql("SELECT * FROM category WHERE `name` LIKE '".$name."' AND status = 0 ","row");
        
        if(empty($details))
        {
            $name = htmlentities($name);
            {
            $details=$this->callsql("SELECT * FROM category WHERE `name` LIKE '".$name."' AND status = 0 ","row");
            }
        }
        return $details;
    }
    public function getSupplierCategoryByName($name){

        $time   = time();
   
        $details=$this->callsql("SELECT * FROM supplier WHERE `supplier_name` LIKE '$name' AND status = 0 ","row");
        
        if(empty($details))
        {
            $name = htmlentities($name);
            {
            $details=$this->callsql("SELECT * FROM supplier WHERE `supplier_name` LIKE '$name' AND status = 0 ","row");
            }
        }

        return $details;
    }

     public function getCategoryAll(){

        $time   = time();
   
        $details = $this->callsql("SELECT * FROM category WHERE status = 0","rows");

        foreach($details as $key=>$value)
        {
            $result[$key]['id'] = $value['id'];
            
            $value['name'] = str_replace('&amp;', '&', $value['name']);

            $result[$key]['name'] = strtoupper(htmlspecialchars_decode($value['name']));
        }
        return $result;
    }
    public function getSupplierCategoryAll(){

        $time   = time();
   
        $details=$this->callsql("SELECT * FROM supplier WHERE status = 0","rows");

        foreach($details as $key=>$value)
        {
            
            $value['supplier_name'] = str_replace('&amp;', '&', $value['supplier_name']);

            $result[$key]['id'] = $value['id'];
            $result[$key]['supplier_name'] = strtoupper(htmlspecialchars_decode($value['supplier_name']));
        }
        return $result;
    }
     public function checkExist($key,$edit){

        $time   = time();

        $details=$this->callsql("SELECT COUNT(id) FROM category WHERE `name` LIKE '%$key%' AND id !='$edit' AND status=0","value");
    
        return $details;
    }

    public function checkExistSupplier($ip){

        $time   = time();
        $where = '';
        if($ip['edit'])
        {
            $where = 'AND id != '.$ip['edit'].'';
        }
        $details=$this->callsql("SELECT COUNT(id) FROM supplier WHERE `supplier_name` LIKE '%$ip[name]%' AND status=0 $where","value");
    
        return $details;
    }

    public function getCategoryNames(){
   
        $details = $this->callsql("SELECT id,name FROM category WHERE status=0 ORDER BY name ASC","rows");
    
        return $details;
    }

    public function getSupplierNames(){
   
        $details = $this->callsql("SELECT id,supplier_name FROM supplier WHERE status=0 ORDER BY supplier_name ASC","rows");
        return $details;
    }
   public function UpdateAlcoholInv($ip){

        $time         = time();   
        // print_r($ip);die();
        for($i=1;$i<=$ip['totalCount'];$i++){
            $image_already = $this->callsql("SELECT image FROM `inventory` WHERE id='".$ip['edit']."' ","value");

            $filename      = empty($ip['file'][$i]) ? $image_already : $ip['file'][$i];

            //strtotime($data['invoice_date']);
            $this->query("UPDATE inventory SET `category_id`='".$ip['category'][$i]."',`foc`='".$ip['foc'][$i]."',`supplier_id`='".$ip['supplier'][$i]."', `item_date`='".strtotime($ip['item_date'][$i])."',`image`='".$filename."',`brand`='".$ip['brand'][$i]."', `name`='".$ip['name'][$i]."',`type`='".$ip['type']."',`vintage`='".$ip['vintage'][$i]."',`country`='".$ip['country'][$i]."',`volume`='".$ip['volume'][$i]."',`alcohol_percent`='".$ip['percent'][$i]."',`price`='".$ip['price'][$i]."', `quantity`='".$ip['quantity'][$i]."', `createtime` ='$time' WHERE `id`='".$ip['edit']."'");
            $this->execute();
            $this->adminActivityLog("Updated Inventory - " .$ip['name'][$i] . " id- ".$ip['edit']);

            //Inventory Transactions
            $this->query("UPDATE inventory_transactions SET `quantity`='".$ip['quantity'][$i]."', `updatetime` ='$time' WHERE `inventory_id`='".$ip['edit']."'");
            $this->execute();

        }
        

        return true;
    }

    public function addAlcoholInv($ip){
        $time     = time(); 
  
        for($i=1;$i<=$ip['totalCount'];$i++){

            $this->query("INSERT INTO `inventory`( `category_id`,`supplier_id`, `foc`,`item_date`, `image`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `createtime`, `createtip`, `createid`) VALUES ('".$ip['category'][$i]."','".$ip['supplier'][$i]."','".$ip['foc'][$i]."','".strtotime($ip['item_date'][$i])."','".$ip['file'][$i]."','".$ip['brand'][$i]."','".$ip['name'][$i]."','".$ip['type'][$i]."','".$ip['vintage'][$i]."','".$ip['country'][$i]."','".$ip['volume'][$i]."','".$ip['percent'][$i]."','".$ip['price'][$i]."','".$ip['quantity'][$i]."','$time','$this->IP','$this->adminID')");
            
            $this->execute();
            $last_id = $this->lastInsertId();
            $this->adminActivityLog("Inventory Added".$last_id."-".$ip['name'][$i]);


            $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`, `ref_id`) VALUES ('$last_id','".$ip['quantity'][$i]."','$time',1,0,'$this->adminID','$time', '$last_id')");
            $this->execute();


        }

        

        return true;
    }

    public function addExportAlcoholInv($ip){

        $time     = time(); 
  
        $this->query("INSERT INTO `inventory`( `category_id`,`supplier_id`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `createtime`, `createtip`, `createid`) VALUES ('".$ip['category']."','".$ip['supplier']."','".$ip['brand']."','".$ip['name']."','".$ip['type']."','".$ip['vintage']."','".$ip['country']."','".$ip['volume']."','".$ip['percent']."','".$ip['price']."','".$ip['quantity']."','$time','$this->IP','$this->adminID')");
        
        $this->execute();
        $last_id = $this->lastInsertId();
        $this->adminActivityLog("Inventory Added with Export ".$last_id."-".$ip['name']);

        $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`, `ref_id`) VALUES ('$last_id','".$ip['quantity']."','$time',1,0,'$this->adminID','$time', '$last_id')");
        $this->execute();
        

        return true;
    }



    public function UpdatewithImportAlcoholInv($ip){

        $time         = time();

            $this->query("UPDATE inventory SET `quantity`= quantity+'".$ip['quantity']."', `createtime` ='$time' WHERE `id`='".$ip['id']."'");
            $this->execute();
            $this->adminActivityLog("Updated Inventory Quantity with Import Quantity -".$ip['quantity']." Name -".$ip['name']);

            //Inventory Transactions
            $this->query("INSERT INTO `inventory_transactions`(`inventory_id`, `quantity`, `date`, `trans_type`, `credit_type`, `updated_by`, `updatetime`) VALUES ('".$ip['id']."','".$ip['quantity']."','$time',1,0,'$this->adminID','$time')");

            $this->execute();
        

        return true;
    }

     public function addTempAlcoholInv($ip){

        $time     = time(); 
  
            $this->query("INSERT INTO `temp_inventory_bulk`( `category_id`,`supplier_id`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `createtime`, `createtip`, `createid`,`bulk_id`,`upload_filename`) VALUES ('".$ip['category']."','".$ip['supplier']."','".$ip['brand']."','".$ip['name']."','".$ip['type']."','".$ip['vintage']."','".$ip['country']."','".$ip['volume']."','".$ip['percent']."','".$ip['price']."','".$ip['quantity']."','$time','$this->IP','$this->adminID','".$ip['bulk_id']."','".$ip['upload_filename']."')");

           //echo "INSERT INTO `inventory`( `category_id`,`supplier_id`, `brand`, `name`, `type`, `vintage`, `country`, `volume`, `alcohol_percent`, `price`, `quantity`, `createtime`, `createtip`, `createid`) VALUES ('".$ip['category']."','".$ip['supplier']."','".$ip['brand']."','".$ip['name']."','".$ip['type']."','".$ip['vintage']."','".$ip['country']."','".$ip['volume']."','".$ip['percent']."','".$ip['price']."','".$ip['quantity']."','$time','$this->IP','$this->adminID')";die();
            
            $this->execute();
            $this->adminActivityLog("Inventory Added with Export in temp table");
        

        return true;
    }


    public function getlastBulk_id()
    {
        $last_bulk_id = $this->callsql("SELECT bulk_id FROM temp_inventory_bulk ORDER BY id DESC LIMIT 1","value");
        return $last_bulk_id;

    }

    public function checkBulk_id($bulk_id)
    {
        $last_bulk_id = $this->callsql("SELECT bulk_id FROM temp_inventory_bulk WHERE bulk_id = '$bulk_id'","value");
        return $last_bulk_id;

    }

    public function getTempDetails($random_bulk_id)
    {
        $details = $this->callsql("SELECT * FROM temp_inventory_bulk WHERE bulk_id='$random_bulk_id'","rows");
        return $details;

    }

    public function DeleteTempAlcoholInv($random_bulk_id)
    {

        $this->query("DELETE FROM `temp_inventory_bulk` WHERE bulk_id='$random_bulk_id'");
      
        $this->execute();

        return true;
    }
    public function DeleteTempAlcoholInvByid($id)
    {

        $this->query("DELETE FROM `temp_inventory_bulk` WHERE id='$id'");
      
        $this->execute();

        return true;
    }

    public function deleteAlcohol($ID){
      
        $this->query("UPDATE `inventory` SET status=1 WHERE id='$ID'");
        $this->execute();
        $inventoryname = $this->getInventoryById($ID);
        $this->adminActivityLog("Inventory Deleted - ".$inventoryname['name'].' id - '.$ID);

        return true;
    }

   public function checkExistInventrory($params)
   {
    $category_id = $params['category_id'];
    $supplier_id = $params['supplier_id'];
    $name = $params['name'];
    $price = $params['price'];
    $country = $params['country'];

    $check_exist = $this->callsql("SELECT id FROM inventory WHERE category_id = '$category_id' AND supplier_id = '$supplier_id' AND name = '$name' AND price = '$price' AND country ='$country' AND status = 0 ","value");

    return $check_exist;
   }
   public function getgraphdata()
   {


        $details = $this->callsql("SELECT category_id, SUM(quantity) AS Value From inventory GROUP BY category_id","rows");
        if(!empty($details)){
       
        foreach ($details as $key => $value) {


            $result['data'][$key]['quantity']  = $value['Value'];
            $result['data'][$key]['name']      = htmlspecialchars_decode(htmlspecialchars_decode($this->callsql("SELECT name FROM `category` WHERE id='$value[category_id]' ","value")));


        }
        }
        else{
            $result['data']['quantity'] =0;
            $result['data']['name'] ='';

        }
        return $result;

   }

   public function getcategorycount()
   {

        $category_count = $this->callsql("SELECT count(id) From category ","value");
        return  $category_count;

   }
   public function generateColorArray($count) {
        $colors = array();
    
        for ($i = 0; $i < $count; $i++) {
            $color = $this->generateRandomColor();
            $colors[] = $color;
        }
    
       return $colors;
   }
   public function generateRandomColor() {
        $color = '#';
    
        for ($i = 0; $i < 6; $i++) {
        $color .= dechex(rand(0, 15));
        }
    
    return $color;
  }

  public function getoutofstocksoondata()
  {
     
      $details = $this->callsql("SELECT name, quantity  From inventory WHERE quantity<10  ORDER BY quantity","rows");
      if(!empty($details)){
          foreach ($details as $key => $value) {

              $result['data'][$key]['quantity']  = $value['quantity'];
              $result['data'][$key]['name']      = $value['name'];

           }
       
         
      }
      else{

           $result['data']['quantity']  = '';
           $result['data']['name']      = ''; 

      }
      return $result;
  }


    
}
