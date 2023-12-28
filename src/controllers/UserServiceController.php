<?php

namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Permission;
use inc\commonArrays;
use inc\Root;

class UserServiceController extends Controller {
    

    public function __construct(){
        
        parent::__construct();
        $this->mdl        = (new Permission); 
        $this->pag        = new Pagination(new Permission(),''); 
        $this->getArray      = (new commonArrays)->getArrays();       
        $this->admin      = $this->admin_id;
        $this->roleArr       = $this->getArray['roleArr'];
    }
    
    public function actionIndex() {
        
        

         $this->checkPageAccess(50);

        $get_service_list  = $this->mdl->getList(); 
        
        return $this->render('user/create_service',['service_group'=>$get_service_list]);
    
    }

    public function actionEdit() {  

    
        $this->checkPageAccess(50);

        $permissionList = $this->roleArr;

        $service_master  = $this->mdl->GetServiceMasterAll(); 
        $permission_id   = $this->cleanMe(Router::get('id')); 
        $permission_id = base64_decode($permission_id);

        $permission_detatils = $this->mdl->getPermissionById($permission_id); 
        $id = $permission_detatils['permission_id'];
        $permissions_ids     = $permission_detatils['permissions'];
        $permission_name     = $permissionList[$permission_detatils['role']];
        return $this->render('user/create_service',['service_master'=>$service_master,'permission_id'=>$id,'permissions_ids'=>$permissions_ids,'permission_name'=>$permission_name]);
    
    }
    public function actionUpdateUserService() {

        $this->checkPageAccess(50);

        $permission_id = $this->cleanMe(Router::post('permission_id'));

        if(empty($_POST['user_service_array']))
        {
          return $this->sendMessage ('error',Root::t('servicegroup','serv_err'));
        }

        foreach ($_POST['user_service_array'] as $serviceVal) {
         
           if($serviceVal < 1)
              $this->sendMessage ('error',Root::t('servicegroup','servicevalid_error')); 
              $services[] = cleanMe($serviceVal);
        }

        $newServiceVal = implode(',', $services);

        if($permission_id !=''){

           $data = [
             'permission_id'     => $permission_id,
             'permissions'       => $services
           ];

           $update = $this->mdl->serviceUpdate($data);
           $msg    = Root::t('servicegroup','edit_succ');

        }else{
          

            return $this->sendMessage('error','Something Went Wrong');

          // $data = [
          //   'servicegrpname' => $servicename,
          //   'services'       => $services
          // ];

          // $add = $this->mdl->serviceAdd($data);
          // $msg = Root::t('servicegroup','add_succ');
        }

        return $this->sendMessage('success',$msg );
    }

}
