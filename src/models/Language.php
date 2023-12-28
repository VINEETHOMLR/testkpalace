<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;

class Language extends Database {

    /**
     * Constructor of the model
     */
   public function __construct($db = 'db') {

        parent::__construct(Root::db());
        $this->tableName = "language_content";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
    }
    public function getLanguageContent($data)
    {
        $where = ' WHERE id!=0 ';

        if($data['lang_key']!=""){
        
			$where .= " AND lang_key LIKE '%$data[lang_key]%' ";
        
		}

        if($data['en']!=""){
        
			$where .= " AND en LIKE '%$data[en]%' ";
        
		}

        if($data['fepage']!=""){
			
            $where .= " AND page LIKE '%$data[fepage]%' ";
        }


        $pagecount = ($data['page'] - 1) * $this->perPage;

        $count  = $this->callsql("SELECT count(id) FROM language_content $where",'value');
        //$userStatus = array(0=>"Published", 1=>"Hidden", 2=>"Cancelled");
		
		if(empty($data['export']))
		{
		
			$this->query("SELECT * FROM language_content $where ORDER BY id DESC LIMIT $pagecount,$this->perPage");
			$result = ['data' => $this->resultset()];
		
		}
		else
		{
            $result['data']=$this->callsql("SELECT * FROM language_content $where ORDER BY id DESC",'rows');
        }
		
        foreach ($result['data'] as $key => $value) 
		{
                
                $result['data'][$key]['action'] = '<a href="'.BASEURL.'Language/Create/?id='.$value['id'].'"><button class="btn btn-info">Edit</button></a>
                                                   <button class="btn btn-info" onclick="deleteThis('.$value['id'].')">Delete</button>';
        }
        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;
    }

    public function updateStatus($ip){   

        $time         = time();

        $db_entries   = $this->callsql("SELECT lang_code FROM `announcement` WHERE log_id='$ip[edit]'","rows");
        $db_entries   = array_column($db_entries, 'lang_code');

        $updated_lang = array_column($ip['data'], 'language');

        foreach ($db_entries as $lg) {
          
            if(!in_array($lg, $updated_lang)){

                $this->query("DELETE FROM `announcement` WHERE lang_code='$lg' AND log_id='$ip[edit]'");
                $this->execute();
            }
        }

        foreach ($ip['data'] as $key => $value) {

            $check_entry  = $this->callsql("SELECT id FROM `announcement` WHERE lang_code='$value[language]' AND log_id='$ip[edit]'","value");

            if(empty($check_entry)){

                  $annData      = $this->callsql("SELECT thumb_nail FROM `announcement` WHERE log_id='$ip[edit]' ","row");

                  $img_thumb    = empty($ip['thumb_nail']) ? $annData['thumb_nail'] : $ip['thumb_nail'];

                  $this->query("INSERT INTO $this->tableName SET  title='$value[title]',message='$value[description]', category_type='$value[category]',filename='$value[file]',thumb_nail='$img_thumb',video_embed='$value[video_embed]',filename_video='$value[video]',type=1,file_type='$value[file_type]', lang_code='$value[language]',createtime='$time',status=$ip[status],slug='$ip[slug]',log_id='$ip[edit]',createid='$this->adminID',include_user='$ip[include_user]',exclude_user='$ip[exclude_user]',include_country='$ip[include_country]',exclude_country='$ip[exclude_country]',include_downline='$ip[include_downline]'");
                  $this->execute();
   
                  
            }else{

                  $annData      = $this->callsql("SELECT filename_video,filename,thumb_nail,video_embed FROM `announcement` WHERE lang_code='$value[language]' AND log_id='$ip[edit]' ","row");

                  $filename     = empty($value['file']) ? $annData['filename'] : $value['file'];
                  $img_thumb    = empty($ip['thumb_nail']) ? $annData['thumb_nail'] : $ip['thumb_nail'];
                  $video_embed  = empty($value['video_embed']) ? $annData['video_embed'] : $value['video_embed'];
                  $file_video   = empty($value['video']) ? $annData['filename_video'] : $value['video'];

                  if($value['file_type'] ==1){
                     $file_video = $video_embed = '';
                  }else{
                      $filename   = '';
                      $vd_check   = explode('_', $value['videoType']);
                      if($vd_check[0]=='video')
                        $video_embed  = '';
                      else
                        $file_video   = '';
                  }

                  $this->query("UPDATE $this->tableName SET title='$value[title]',message='$value[description]',status='$ip[status]',type=1,file_type='$value[file_type]', category_type='$value[category]',filename='$filename',thumb_nail='$img_thumb',video_embed='$video_embed',filename_video='$file_video',updatetime='$time',updateid='$admin_id',updateip='$ip',slug='$ip[slug]',include_user='$ip[include_user]',exclude_user='$ip[exclude_user]',include_country='$ip[include_country]',exclude_country='$ip[exclude_country]',include_downline='$ip[include_downline]' WHERE lang_code='$value[language]' AND `log_id`='$ip[edit]'");
                  $this->execute();
                  
				  $this->adminActivityLog("Updated Announcement");
            }
        }
       
       

        return true;
    }

    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        $this->execute();
        return true;
    }

   public function getLanguageArray(){

      return  $this->callsql("SELECT * FROM `language` WHERE `status`=1","rows");
   }



 
   public function deleteLanguage($ID){

      $this->query("DELETE FROM `language_content`  WHERE id='$ID'");
      if($this->execute()){
        
         $this->adminActivityLog("Language Key Deleted");
         return true;
      }else
         return false;
   }

    public function addLanguage($ip){

        
		$query = [];
        $LanguageArray  =   $this->getLanguageArray();
		
		
		foreach ($LanguageArray as $key => $value) {
			
			if(!empty($ip['lang_data'][$value['lang_code']]))
			{
				$query[] = ' `'.$value['lang_code'].'`= "'.$ip['lang_data'][$value['lang_code']].'" ' ;
			}
           
        }
		
		$insert_query  = '';
        if (!empty($query)) {
            $insert_query = implode(',', $query);
        }
        if($insert_query!="")
		{
			$insert_query = ",".$insert_query;
		}
      
      
            $this->query("INSERT INTO `language_content` SET lang_key='$ip[lang_key]',page='$ip[page]' $insert_query ");
            
            $this->execute();
       
        $this->adminActivityLog($ip['lang_key']." language content  added");

        return true;
    }

    public function updateLanguage($ip){

        $query = [];
        $LanguageArray  =   $this->getLanguageArray();
		
		
		foreach ($LanguageArray as $key => $value) {
			
			if(!empty($ip['lang_data'][$value['lang_code']]))
			{
				$query[] = ' `'.$value['lang_code'].'`= "'.$ip['lang_data'][$value['lang_code']].'" ' ;
			}
           
        }
		
		$insert_query  = '';
        if (!empty($query)) {
            $insert_query = implode(',', $query);
        }
        if($insert_query!="")
		{
			$insert_query = ",".$insert_query;
		}
      
      
            $this->query("UPDATE `language_content` SET lang_key='$ip[lang_key]',page='$ip[page]' $insert_query WHERE id = $ip[edit] ");
            
            $this->execute();
       
        $this->adminActivityLog($ip['lang_key']." language content  updated");

        return true;

        return true;
    }

}
