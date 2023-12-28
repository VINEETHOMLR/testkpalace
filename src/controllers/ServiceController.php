<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Admin;
use inc\Root;

class ServiceController extends Controller {

    public function __construct(){
        
        parent::__construct();
        //$this->ServiceArr = (new commonArrays)->getArrays['getServiceArray'];
		
        //$this->ServiceArr = $this->systemArrays['getServiceArray'];
		   
        $this->mdl        = (new Admin); 
        $this->pag        = new Pagination(new Admin(),''); 
        $this->ServiceArr = $this->mdl->getAllServiceArray();
        
        $this->admin      = $this->admin_id;
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
    }
    
    public function actionIndex() {  

     // $this->checkPageAccess(3);
      $adminDetails = $this->mdl->getAdmin();
      $adminDetails['service_group_id'];
      $admin_service_group =json_decode($adminDetails['service_group_id'],true);
      $servicesalready = '';
      if(isset($_REQUEST['id'])){
        $servid           = base64_decode($this->cleanMe($_REQUEST['id']));
        $ser              = $this->mdl->getService($servid); 
        //print_r($ser);
        $servicegrpinfoid = $ser['0']['id'];
        $servicenameedit  = $ser['0']['group_name'];
        $servicesedit     = $ser['0']['service_id'];
        if($this->admin_role!=1){
        $service_master      = $this->mdl->GetServiceMaster($servid);
        } 
        else{

          $service_master      = $this->mdl->GetServiceMasterAll();
        }
      }else{
        $servicenameedit  = $servicesedit= $servicegrpinfoid= $service_master='';

        
         
          $service_master      = $this->mdl->GetServiceMasterAll();

          if($this->admin_role!=1) {
         
          $service_master      = $this->mdl->GetServiceMasterByAdminUser($this->admin_services);
          $servicesalready   =implode(",",$this->admin_services);
         }
      }
	  
	  $services      = $this->actionGetServiceArray();

	  $servicesArray = $this->admin_role==1?$this->ServiceArr:$services['serviceArray'];
	  $servicesKey   = (!empty($services['serviceKey'])) ? $services['serviceKey'] : [] ;	

    $data               = $this->actionGetServiceList($admin_service_group);
       
    return $this->render('admin/create_service',['servicesArray' => $servicesArray,'servicesKey' => $servicesKey,'servicenameedit'=>$servicenameedit,'servicegrpinfoid'=>$servicegrpinfoid,'servicesedit'=>$servicesedit,'data'=>$data,'service_master'=>$service_master,'servicesalready'=>$servicesalready]);
    }
    public function actionGetServiceArray(){

        $servicearray = $this->ServiceArr;
		
		if($this->admin_role==1)
		{
			$where = " WHERE 1 ";
		}
		else
		{
			
			$where = " WHERE createid='$this->admin'  ";
			
			$privleged_group = $this->mdl->callsql("SELECT service_group_id FROM admin WHERE id ='$this->admin' ","value");
	
			if(!empty($privleged_group)){
				$privleged_group  = json_decode($privleged_group,true);
				$adminservice  	= implode(",",$privleged_group);
				$where .= " OR id IN ('$adminservice') ";
			}
			
			
			
		}
	
 
        $this->mdl->query("SELECT * FROM service_group $where ");
        $data      = ['data' => $this->mdl->resultset()];
		    $services  = [];
	
		foreach ($data['data'] as $key => $value) {
            $services = array_merge($services , explode(",", $value['service_id']));
		}

		$servicearraynew = [];
   
        foreach ($services as  $value)
		{
            $servicearraynew[$value] = $servicearray[$value];
        }
		
		$response["serviceArray"]  = $servicearraynew;
		$response["serviceKey"]     = $services; 		
		
        return $response;
    }
    public function actionGetServiceList($group_id){

        $page      = $this->cleanMe(Router::post('page')); 
        $page      = (!empty($page)) ? $page : '1'; 
        $perPage   = 50;
        $pagecount = ($page - 1) * $perPage;
        if($this->admin_role==1){
              $count     = $this->mdl->callsql("SELECT count(*) FROM service_group","value");

              $this->mdl->query("SELECT * FROM service_group LIMIT $pagecount,$perPage");

        } else{
              $count     = $this->mdl->callsql("SELECT count(*) FROM service_group WHERE id IN(".implode(',',$group_id).")","value");

              $this->mdl->query("SELECT * FROM service_group WHERE id IN(".implode(',',$group_id).") LIMIT $pagecount,$perPage");

        }
             $data      = ['data' => $this->mdl->resultset()];

        foreach ($data['data'] as $key => $value) {
            $services = explode(",", $value['service_id']);
            $selected = '<div class="row col-md-12">';
            foreach ($services as $val) {
                 $selected .= '<div class="col-md-6 col-xl-3 col-sm-12 col-12"><div class="dot"></div>'.$this->ServiceArr[$val].'</div>';
            }
            $selected.= "</div>";
            $data['data'][$key]['service'] = $selected;
            $data['data'][$key]['action'] = '<a href="'.BASEURL.'Service/Index/?id='.(base64_encode($value['id'])).'"><button class="btn btn-info"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>&nbsp;'.Root::t('app','edit_text').'</button></a>';
        }

        $onclick    = "onclick=pageHistory('***')";
        $pagination = $this->pag->getPaginationString($page,$count,$perPage,1, $onclick,'pagestring');

        $data['pagination'] = $pagination;

        return $data;
    }
    
    public function actionAddService() {
         
        $servicename = $this->cleanMe($_POST['servicename']);
        
        if(empty($servicename))
           return $this->sendMessage ('error',Root::t('servicegroup','servicenameerror'));

        $servegrpid = $this->cleanMe($_POST['servegrpid']);

        $check = $this->mdl->checkServiceName($servicename,$servegrpid);

        if(!empty($check))
          return $this->sendMessage ('error',Root::t('servicegroup','service_err')); 

        if(empty($_POST['servicearr']))
          return $this->sendMessage ('error',Root::t('servicegroup','serv_err'));
    
        $services      = array();
        $servicesArray = $this->ServiceArr;
        $countMax      = count($servicesArray);

        foreach ($_POST['servicearr'] as $serviceVal) {
         
           if($serviceVal < 1)
              $this->sendMessage ('error',Root::t('servicegroup','servicevalid_error')); 
              $services[] = cleanMe($serviceVal);
        }

        $newServiceVal = implode(',', $services);

        if($servegrpid !=''){

           $data = [
             'servegrpid'     => $servegrpid,
             'servicegrpname' => $servicename,
             'services'       => $services
           ];

           $update = $this->mdl->serviceUpdate($data);
           $msg    = Root::t('servicegroup','edit_succ');

        }else{
          $data = [
            'servicegrpname' => $servicename,
            'services'       => $services
          ];

          $add = $this->mdl->serviceAdd($data);
          $msg = Root::t('servicegroup','add_succ');
        }

        return $this->sendMessage('success',$msg );
    }

}
