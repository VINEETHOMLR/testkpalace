<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Language;
use inc\Root;
use inc\commonArrays;
use src\lib\SimpleXLSX;
use src\lib\SimpleXLSXGen;

/**
 * To handle the users data models
 * @author 
 */

class LanguageController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Language);
        $this->pag =  new Pagination(new Language(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		$arr                    = commonArrays::getArrays();

        $this->statusArry       = $arr['announcementArr']['annoStatusArr'];
        
		
        $this->imagetype = $arr['announcementArr']['fileType'];
        
    
    }
    public function actionIndex() {

      $this->checkPageAccess(45);

         
         $lang_key   = $this->cleanMe(Router::post('lang_key'));
         $fepage 	 = $this->cleanMe(Router::post('fepage')); 
         //$language = $this->cleanMe(Router::post('language')); 
         $page     = $this->cleanMe(Router::post('page')); 
         $en     = $this->cleanMe(Router::post('en'));

         $page = (!empty($page)) ? $page : '1'; 
        
         $LanguageArray=$this->mdl->getLanguageArray();
         
         $filter=[
                  "lang_key"   	=> $lang_key,
                  "en"   	    => $en,
                  "fepage" 	 	=> $fepage,
                  "page"     	=> $page];

         $data=$this->mdl->getLanguageContent($filter);
         
         $onclick = "onclick=pageHistory('".$lang_key."','".$en."','".$fepage."','***')";
         $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('language/index',['LanguageArray'=>$LanguageArray,'lang_key'=>$lang_key,'en'=>$en,'fepage'=>$fepage,'data' => $data, 'pagination'=> $pagination]);
    }
        
    public function actionCreate() {
		
		$this->checkPageAccess(45);
		
        $LanguageArray=$this->mdl->getLanguageArray();

        $this->subTitle  = 'Create Announcement';

        if(isset($_GET['id'])){

            $language = $this->mdl->callsql("SELECT * FROM `language_content` WHERE id='$_GET[id]' ","row");

            if(empty($language))
               Router::redirect(['Language','create']);   

            $this->subTitle  = 'Edit Language';

            return $this->render('language/create',['LanguageArray'=>$LanguageArray,'language'=>$language,'id'=>$_GET['id']]);

        }else
            return $this->render('language/create',['LanguageArray'=>$LanguageArray,'language'=>[],'id'=>'']);
    }

    public function actionAdd() {
		

        $lang_key    = $this->cleanMe(Router::post('lang_key'));
        $page        = $this->cleanMe(Router::post('page'));
        $edit        = $this->cleanMe(Router::post('editID')); 
        
        $ip            = [];
   
        if(empty($lang_key))
            return $this->sendMessage('error',"Please Enter Language Key To  Proceed");

		$LanguageArray  =   $this->mdl->getLanguageArray();
		$lang_data = [];
        foreach ($LanguageArray as $key => $value) {
			
			$lang_data[$value['lang_code']] = $_POST[$value['lang_code']];
           
        }

        $ip['lang_key']     	= $lang_key;
        $ip['page'] 			= $page;
        $ip['lang_data']		= $lang_data;
        $ip['edit']     		= $edit;
       
        if(!empty($edit)){
           $success = $this->mdl->updateLanguage($ip);
           $msg     = 'Language Updated Successfully';
        }else{

           $success = $this->mdl->addLanguage($ip);
           $msg     = 'Language Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
   
    } 

    public function actionsingleUpdate(){

        $updatelang      = $this->cleanMe(Router::post('updatelang'));
        $rowid           = $this->cleanMe(Router::post('rowid'));
        
        if(empty($updatelang))
            return $this->sendMessage("error","Please Add Content To Proceed");
		
		if(empty($rowid))
            return $this->sendMessage("error","Please Add Content To Proceed");

		$details = explode("__",$rowid);
		
		$id  = $details[0];
		$row = $details[1];

        $update = $this->mdl->query("UPDATE `language_content` SET `".$row."`='$updatelang' WHERE id='$id'");

        if($this->mdl->execute()){
			
           return $this->sendMessage('success',"Updated Successfully");
		   
        }else
           return $this->sendMessage("error","Updation Failed..Try again..");
    }


    public function actiongetEdit(){

        $ID   = $this->cleanMe(Router::post('AnnId'));
        $data = $this->mdl->callsql("SELECT * FROM `announcement` WHERE id='$ID' ","row");

        return  $this->renderJSON($data);
    }

    public function actionDelete(){
        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteLanguage($ID);

        if($delete){
            return $this->sendMessage('success',"Language Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }
	
	public function actionBulkUpload(){
             
        if(empty($_FILES['importexcel'])) 
            return $this->sendMessage("error",'Please select CSV file to upload');
            
        if(!empty($_FILES['importexcel'])){     

            $filename   = $_FILES['importexcel']['name'];
            $temp_name  = $_FILES['importexcel']['tmp_name'];
            $path_parts = pathinfo($filename);
            $extension  = $path_parts['extension'];
            $image_array = array('CSV','csv');

            if(!in_array($extension, $image_array)){
                return $this->sendMessage("error",'Please Select Valid CSV File');
            }

            $LanguageArray = $this->mdl->getLanguageArray();

            $csvFile       = fopen($temp_name, 'r');

            $heading       = true;

            while(($line = fgetcsv($csvFile)) !== FALSE){

                if($heading){
                   $heading = false;
                   continue;
                }
               
                $language_key      = $line[0];
                $page              = $line[1];

                if(empty($language_key))
                    continue;

                $lang_data         = [];

                $line_start        = 2;

                foreach ($LanguageArray as $key => $value) {
                    
                    $lang_data[$value['lang_code']] = empty($line[$line_start]) ? $line[2] :  $line[$line_start];
                    
                    $line_start ++;
                }
            
                $id     = $this->mdl->callsql("SELECT id FROM `language_content` WHERE lang_key='$language_key' ","value");
                
                $id     = (!empty($id)) ? $id : 0 ;

                $ip['lang_key']         = $language_key;
                $ip['page']             = $page;
                $ip['lang_data']        = $lang_data;
                $ip['edit']             = $id;
                
                if(!empty($id)){
                   $success = $this->mdl->updateLanguage($ip);
                }else{
                   $success = $this->mdl->addLanguage($ip);
                }
            }

            fclose($csvFile);

            return $this->sendMessage('success',"Import Successfully"); 
        }else
           return $this->sendMessage('error',"Import Failed"); 
  
    }

  public function actionCreateExcel()
    {       
        $LanguageArray=$this->mdl->getLanguageArray();

        $csv = "Language Key,Page, "; // Column headers

        foreach($LanguageArray as $k=>$v){
            $csv .= $v['lang_code'].",";
        }
        
        $csv .="\n";

        $csv_handler = fopen(FILEUPLOADPATH.'langsample.csv','w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);
        fclose($csv_handler);

        // Download the CSV file
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=langsample.csv");
        readfile(FILEUPLOADPATH.'langsample.csv');
        exit();
    }

    public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename = 'lang_pack'; 

        $lang_key = $this->cleanMe(Router::post('lang_key')); 
        $fepage   = $this->cleanMe(Router::post('fepage'));
        $en     = $this->cleanMe(Router::post('en'));
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        // $filter = $this->getInputs();
        $filter['lang_key'] = $lang_key;
        $filter['fepage'] = $fepage;
        $filter['en'] = $en;
        $filter['export'] = "export";

        $data = $this->mdl->getLanguageContent($filter);
        
        $LanguageArray= $this->mdl->getLanguageArray();
        $langhead = [];
       foreach ($LanguageArray as $key => $languages) {
        $langhead[] = $languages['lang_code'];
       }
       $langs = '';
       if(!empty($langhead))
       {
         $langs = implode(',',$langhead);
       }

        $csv = "Key, Page , $langs  \n";//Column headers
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";
        $LanguageArray= (new Language)->getLanguageArray();
    
        foreach ($data['data'] as $val) {
 
            $html.= $val['lang_key'].','.$val['page'].',';
      
        foreach ($LanguageArray as $keys => $languages) {
        $html.= str_replace(","," ",$val[$languages['lang_code']]).',';
        }
        $html.= "\n"; //Append data to csv
        }
        // foreach ($data['data'] as $his) {  

        //     $html.= $his['lang_key'].','.$his['page'].','.$his['email'].','.$his['time']."\n"; //Append data to csv

        // }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export file -".$filename;
        $log_data = array(
            "user" => $user,
            "datefrom" => $datefrom,
            "dateto" => $dateto,
            "export" => $filename." list"
            );

        $logdata = json_encode($log_data,JSON_UNESCAPED_UNICODE);
        $this->mdl->adminActivityLog($act,$logdata);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }
	
	
	

}

