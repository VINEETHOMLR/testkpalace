<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Memo;
use inc\Root;
use inc\commonArrays;

class MemoController extends Controller {

    public function __construct(){

        parent::__construct();

        $this->mdl       = (new Memo);
        $this->pag       =  new Pagination(new Memo(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		    $arr                    = commonArrays::getArrays();

        $this->statusArry       = $arr['announcementArr']['memoStatusArr'];
        
    
        $this->imagetype         = $arr['announcementArr']['fileType'];
		
    }

    public function actionIndex() {

         $this->checkPageAccess(22);

         $filter['datefrom']  = $this->cleanMe(Router::post('datefrom')); 
         $filter['dateto']    = $this->cleanMe(Router::post('dateto'));
         $filter['status']    = $this->cleanMe(Router::post('status'));
         $filter['slug_name'] = $this->cleanMe(Router::post('slug_name')); 
         $page                = $this->cleanMe(Router::post('page')); 
         $filter['page']      = (!empty($page)) ? $page : '1'; 
        
         $data                = $this->mdl->getMemo($filter);

         $filter['LanguageArray'] = $this->mdl->getLanguageArray();
         $filter['data']       = $data;
         
         $onclick              = "onclick=pageHistory('".$filter['datefrom']."','".$filter['dateto']."','".$filter['status']."','".$filter['slug_name']."','***')";
         $filter['pagination'] = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        return $this->render('memo/memo',$filter);
    }
        
    public function actionUpdate() {
		
		$this->checkPageAccess(41);

        $data['LanguageArray']   = $this->mdl->getLanguageArray();
        $data['include_userr']    = '';
        $data['exclude_userr']    = '';
        $data['include_country'] = '';
        $data['exclude_country'] = '';
        $data['memo']            = [];
        $data['log']             = '';
        $data['divCount']        = 1;

        $this->subTitle  = 'Create Memo';

        if(isset($_GET['id'])){

            $memo = $this->mdl->callsql("SELECT * FROM `memo` WHERE log_id='$_GET[id]' ","rows");

            if(empty($memo))
               Router::redirect(['Memo','Update']);   

            if( !empty($memo[0]['include_user']))
                $data['include_userr'] = $this->mdl->getUSerData($memo[0]['include_user']);
            
            if( !empty($memo[0]['exclude_user']))
                $data['exclude_userr'] = $this->mdl->getUSerData($memo[0]['exclude_user']);

            if( !empty($memo[0]['include_country']))
                $data['include_country'] = $this->mdl->getCountryData($memo[0]['include_country']);
            
            if( !empty($memo[0]['exclude_country']))
                $data['exclude_country'] = $this->mdl->getCountryData($memo[0]['exclude_country']);
           
            $this->subTitle  = 'Edit Memo';

            $data['memo']    = $memo;
            $data['log']     = $_GET['id'];
            $data['divCount']= count($memo);
            $data['include_downline'] = $memo[0]['include_downline'];
            $data['excludeusr_include_downline'] = $memo[0]['excludeusr_include_downline'];
            return $this->render('memo/memo_file',$data);

        }else
            return $this->render('memo/memo_file',$data);
    }

    public function actionAdd() {

        $this->checkPageAccess(40);

        $totalCount       = $this->cleanMe(Router::post('totalCount'));
        $status           = $this->cleanMe(Router::post('status'));
        $edit             = $this->cleanMe(Router::post('editID')); 
        $slug             = $this->cleanMe(Router::post('slug')); 
        $position         = $this->cleanMe(Router::post('position')); 
        $removedID        = $this->cleanMe(Router::post('removedID'));
        $removedID        = empty($removedID) ? [] : explode(",", $removedID);

        $include_userr    = $this->cleanMe(Router::post('include_userr'));
        $exclude_userr    = $this->cleanMe(Router::post('exclude_userr'));
        $include_country  = $this->cleanMe(Router::post('include_country'));
        $exclude_country  = $this->cleanMe(Router::post('exclude_country'));
        $include_donwline = $this->cleanMe(Router::post('include_downline'));
        $excludeusr_include_downline         = $this->cleanMe(Router::post('excludeusr_include_downline'));
        $fromdate         = $this->cleanMe(Router::post('fromdate'));
        $todate           = $this->cleanMe(Router::post('todate'));

        $ip               = [];

        $maxsize          = 8388608; 

        $file_acceptable  = array('image/jpeg', 'image/png','image/jpg');

        if(empty($slug))
            return $this->sendMessage('error',"Please Enter Memo Name To Proceed");

        if(empty($position))
            return $this->sendMessage('error',"Please Enter Position To Proceed");

        if(empty($fromdate))
            return $this->sendMessage('error',"Please Select Open Time For Memo");

        if(empty($todate))
            return $this->sendMessage('error',"Please Select Close Time For Memo");

        $fromdate = strtotime($fromdate);
        $todate   = strtotime($todate);

        if($fromdate >= $todate)
            return $this->sendMessage('error','Close time must be greater than open time');

       

        for($i=1;$i <= $totalCount;$i++) {

            if(in_array($i, $removedID))
               continue;
           
            $language  = $this->cleanMe(Router::post('language'.$i));

            $file_upload     = 0 ;

            if(!empty($edit)){

               $check_entry  = $this->mdl->callsql("SELECT id FROM `memo` WHERE lang_code='$language' AND log_id='$edit'","value");

               $file_upload  = empty($check_entry) ? 0 : 1;
            }

            if(empty($_FILES['filename'.$i]['name']) && empty($file_upload))
               return $this->sendMessage('error',"Please Upload File To Proceed");

            if(empty($language))
               return $this->sendMessage('error',"Please Select Language To Proceed");
			
			if($i > 1){
			
				if(in_array($language, array_column($ip['data'], 'language')))
					return $this->sendMessage('error',"Cannot select same language multiple times");
			
			}

            $Where    = empty($edit) ? '' : "AND log_id !='$edit'";
            
            $check    = $this->mdl->callsql("SELECT * FROM `memo` WHERE lang_code='$language' AND ((from_date BETWEEN '$fromdate' AND '$todate') OR (to_date BETWEEN '$fromdate' AND '$todate'))  $Where","row");

            // if(!empty($check))
            //     return $this->sendMessage('error',"Memo Already Exists For This Time Period");

            if(!empty($_FILES['filename'.$i]['name'])){
         
               if((!in_array($_FILES['filename'.$i]['type'], $file_acceptable)) && (!empty($_FILES["filename".$i]["type"])))
                    return $this->sendMessage('error','Invalid File Type. jpeg, jpg, png types are accepted');
             //echo $_FILES['filename'.$i]['error'];
       
               if($_FILES['filename'.$i]['size']==0)
                    return $this->sendMessage('error','Invalid File. Try another file');

               if($_FILES['filename'.$i]['size']>$maxsize)
                    return $this->sendMessage('error',Root::t('Memo','er02'));
            }  

            $ip['data'][$i]['language']  = $language;
        }

        $ip['slug']     = $slug;
        $ip['fromdate'] = $fromdate;
        $ip['todate']   = $todate;
        $ip['status']   = $status;
        $ip['position']  = $position;
        $ip['edit']     = $edit;
        $ip['include_donwline']     = $include_donwline;
        $ip['excludeusr_include_downline']     = $excludeusr_include_downline;
        
        //proceed to upload files

        for($i=1;$i <= $totalCount;$i++) {

            if(in_array($i, $removedID))
               continue;

            $newFile_org        = "";

            $lang               = $ip['data'][$i]['language'];

            if(!empty($_FILES['filename'.$i]['name'])){
               $filename    = $_FILES['filename'.$i]['name']; 
               $temp_name   = $_FILES['filename'.$i]['tmp_name'];
               $path_parts  = pathinfo($filename);
               $extension   = $path_parts['extension'];
               $newFile_org = $path_parts['filename'].time().$lang.'.'.$extension;
               $target_file = FILEUPLOADPATH.'/memo/'.$newFile_org;
               $FileType    = pathinfo($target_file,PATHINFO_EXTENSION);

               $file_type   = 1;

               move_uploaded_file($temp_name, $target_file);
            }

            $ip['data'][$i]['file']      = $newFile_org;
        }

        $ip['include_userr']    = empty($include_userr) ? '' : json_encode(explode(",", $include_userr));
        $ip['exclude_userr']    = empty($exclude_userr) ? '' : json_encode(explode(",", $exclude_userr));
        $ip['include_country'] = empty($include_country) ? '' : json_encode(explode(",", $include_country));
        $ip['exclude_country'] = empty($exclude_country) ? '' : json_encode(explode(",", $exclude_country));

        //print_r($ip); exit;
        if(!empty($edit)){
           $success = $this->mdl->updateMemo($ip);
           $msg     = 'Memo Details Updated Successfully';
        }else{

           $success = $this->mdl->addMemo($ip);
           $msg     = 'Memo Added Successfully';
        }
        
        if($success){

            $this->sendMessage('success',$msg);
            return false;
        }else
           return $this->sendMessage("error","Something Went Wrong..Please try again.."); 
    }

    public function actionUpdateMemoStatus() { 
             
          $this->checkPageAccess(41);
          $id      = $this->cleanMe(Router::post('id'));
          $status  = $this->cleanMe(Router::post('status'));

          $this->mdl->UpdateMemoStatus($id,$status);

          $this->sendMessage('success','Status Updated Successfully');

          return false;
    }

    public function actionDelete(){
        
        $this->checkPageAccess(42);

        $ID   = $this->cleanMe(Router::post('getId'));

        $delete = $this->mdl->deleteMemo($ID);

        if($delete){
            return $this->sendMessage('success',"Memo Deleted");
        }else
           return $this->sendMessage("error","Something Went Wrong.."); 
    }
    public function actionGetCountry(){

        $term = $this->cleanMe(Router::get('term')); 
        if( ! empty($term)){
            $country = $this->mdl->getCountry($term);
           echo $country = json_encode($country); 

        }   
    }
    public function actionData(){

        $term = $this->cleanMe(Router::req('term')); 
        if( ! empty($term)){
            $userList      = $this->mdl->getUserByIdorName($term);
            echo $userList = json_encode($userList);    
        }
    }

    public function actionGetUsers(){

        $term = $this->cleanMe(Router::req('term')); 

        if( ! empty($term)){

            $userlist =$this->mdl->getUserByNameId($term);
                             

            echo  $userList = json_encode($userlist);    
        }
    }

    
}

