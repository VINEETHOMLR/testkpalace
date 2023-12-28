<?php

namespace src\controllers;

use inc\Controller;
use inc\Root;
use src\lib\Router;
use src\lib\Helper as H;
use src\models\Login;
use src\models\Admin;
use src\models\Alcohol;
use src\models\InventoryTransactions;
use src\models\LeaveRequest;
use src\models\Notification;
use inc\commonArrays;


class IndexController extends Controller {

    public function __construct(){
         
        parent::__construct();
        $this->login        = (new Login);
        $this->mdl          = (new Admin);
        $this->alcoholmdl   = (new Alcohol);
        $this->transmdl     = (new InventoryTransactions);
        $this->leavemdl     = (new LeaveRequest);
        $this->notimdl     = (new Notification);

    }

    public function actionIndex() {

        $data['site_status']       = $this->mdl->getSiteData('maintanace_status');

        $graph_data = $this->alcoholmdl->getgraphdata();

        $category_count = $this->alcoholmdl->getcategorycount();
        $generateColorArray = $this->alcoholmdl->generateColorArray($category_count);

        $outofstock = $this->alcoholmdl->getoutofstocksoondata();
        $leave_data = $this->leavemdl->gettodaysleave();

        $data['leave_data'] = $leave_data;
        $data['outofstock'] = $outofstock;
        $data['color_array'] = $generateColorArray;

        $data['quantity'] = array_column($graph_data['data'], 'quantity');
        $data['name'] =array_column($graph_data['data'], 'name');

        $data['customerSales'] = $this->transmdl->getCustomerSales();
        
        $data['inv_overview']       = $this->transmdl->getInvOverview();
        $data['inv_instock']        = $this->transmdl->getInstock();
        $data['inv_cust_cellar']    = $this->transmdl->getCustomerCeller();
       
        
        
        return $this->render('index',$data);
    }
   
    public function actionUpdateSite(){

        $status = $this->cleanMe($_POST['status']);
        
        $update = $this->mdl->UpdateSiteStatus($status);

        if($update)
            return $this->sendMessage('success',"Updated Successfully");
        else
            return $this->sendMessage('error',"Updation Failed...");  
    }

    public function actionLogout() { 
        
        $login = $this->login->logout();
        if($login!=""){
            session_destroy();  
            $this->sendMessage('success',Root::t('login', 'logout_suc'));
            return false;
        }else

        return $this->sendMessage('error',"Something went wrong...");  
    }

    public function actionLanguage() {

        $this->raise->siteLang($_POST['language']);
        return $_SESSION['SITE_LANG']; 
    }

    public function actionISBanned(){

        $adminid   = $this->admin_id ;
        $admininfo = $this->mdl->callsql("SELECT status FROM admin where id=$adminid","row");
        if(!empty($admininfo['status'])){
            $login = $this->login->logout();

            if($login!=""){
                session_destroy(); 
                return $this->sendMessage('error',"You Were Banned");  
            }
        }
		return $this->sendMessage('success',""); 
		
    }


    public function actionGetAdminNotifications(){

        $params = [];
        $params['page'] = 1;
        
        $this->typeArray = [1 => 'Customer Profile Request', 2 => "Inventory Edit Request" , 3 => "Order Request", 4 => "Leave Request"];
        $this->url = [ 1 => 'Customer/CustomerProfileRequest/', 2 => "Inventory/Index" , 3 => "OrderRequest/Index", 4 => "LeaveRequest/Index/" ];

        $list = $this->notimdl->getNotificationListPopUp($params);
          
        $html ='';
        $count = '0';
        if(!empty($list['data']))
        {
             foreach($list['data'] as $k=>$v){

                if($v['count']) {
                    
                    $count = $v['count'];
                    $html .='<div class="dropdown-item" id="notifyallocatejobs" >
                                        <div class="media2">
                                            <div class="media-body">
                                                <div class="notification-para"><span class="user-name">
                                                <a href="#" onclick="notificationread(\'' . $v['url'] . '\', \'' . $v['id'] . '\');">' . $v['data'] . ' <font color="red"></font></a></span></div>
                                            </div>
                                        </div>
                                    </div>';
                }
                  
                  
              }
        }
            // foreach ($list as $type => $items) {
            //     if(!empty($type))
            //     {
            //         $count = $count + count($items);
            //         $html .='<div class="dropdown-item" id="notifyallocatejobs" >
            //                             <div class="media2">
            //                                 <div class="media-body">
            //                                     <div class="notification-para"><span class="user-name"><a href="'.BASEURL."{$this->url[$type]}".'">'."{$this->typeArray[$type]} - <font color=red>" . count($items).'</font></a></span></div>
                                                
            //                                 </div>
            //                             </div>
            //                         </div>';
            //     }
            // }
          $data = ['count'=>$count,'html'=>$html];
          echo json_encode($data,true);
    }
    public function actionNotificationread()
    {
        $id   = $this->cleanMe(Router::post('notifi_id'));

        $update = $this->notimdl->UpdateReadStatus($id);

        if($update){
            return $this->sendMessage('success',"Updated Successfully");
        }
        else{
            return $this->sendMessage('error',"Updation Failed...");  
        }


    }

}
