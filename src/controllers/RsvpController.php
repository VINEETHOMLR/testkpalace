<?php
namespace src\controllers;

use inc\Controller;
use src\lib\Router;
use src\lib\Pagination;
use src\models\Rsvp;
use src\models\RsvpHistory;
use src\models\Room;
use src\models\Hour;
use src\models\Customer;
use src\models\User;
use inc\Root;
use inc\commonArrays;
/**
 * To handle the users data models
 * @author 
 */

class RsvpController extends Controller {

    /**
     * 
     * @return Mixed

     */
    public function __construct(){
        parent::__construct();

        $this->mdl = (new Rsvp);
        $this->rsvphistorymdl = (new RsvpHistory);
        $this->roommdl = (new Room);
        $this->hourmdl = (new Hour);
        $this->customermdl = (new Customer);
        $this->usermdl = (new User);


        $this->pag =  new Pagination(new Rsvp(),''); 
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
		
		$arr                 = commonArrays::getArrays();
        $this->statusArry    = ['0'=>'Booked','1'=>'Cancelled'];

        
            
    
    }
    public function actionIndex() {


        $this->checkPageAccess(68);
        $status        = $this->cleanMe(Router::post('status'));
        $user_id   = $this->cleanMe(Router::post('user_id'));
        $from_booked_date      = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date      = $this->cleanMe(Router::post('to_booked_date'));
        $room_id     = $this->cleanMe(Router::post('room_id'));
        $hour_id       = $this->cleanMe(Router::post('hour_id'));
        $rsvp_type  = $this->cleanMe(Router::post('rsvp_type'));
        $page          = $this->cleanMe(Router::post('page')); 
        $page          = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ "status"             => $status,
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "room_id"            => $room_id,
                  "hour_id"            => $hour_id,
                  "rsvp_type"          => $rsvp_type,
                  "page"               => $page];



        $data=$this->mdl->getList($filter);
        $customer_name = '';
        $admin_name    = '';
        if(!empty($user_id)) {

        	$customer_name = $this->mdl->getcustomername($user_id);

        }


        $hourList = $this->mdl->getHours();
        $roomList = $this->roommdl->getDatByType();
        $onclick = "onclick=pageHistory('".$status."','".$user_id."','".$from_booked_date."','".$to_booked_date."','".$room_id."','".$hour_id."','".$rsvp_type."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        
        
        return $this->render('rsvp/index',['status'=>$status,'user_id'=>$user_id,'from_booked_date'=>$from_booked_date,'to_booked_date'=>$to_booked_date,'room_id'=>$room_id,'hour_id'=>$hour_id,'rsvp_type'=>$rsvp_type,'data' => $data,'customer_name'=>$customer_name,'pagination'=> $pagination,'hourList'=>$hourList,'roomList'=>$roomList]);
        
    }

    public function actionChangeHistory()
    {

        $user_id          = $this->cleanMe(Router::post('user_id'));
        $from_booked_date = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date   = $this->cleanMe(Router::post('to_booked_date'));

        $page          = $this->cleanMe(Router::post('page')); 
        $page          = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ 
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "page"               => $page];



        $data=$this->rsvphistorymdl->getChangeHistory($filter);
        $customer_name = '';
        $admin_name    = '';
        if(!empty($user_id)) {

            $customer_name = $this->mdl->getcustomername($user_id);

        }

        $onclick = "onclick=pageHistory('".$user_id."','".$from_booked_date."','".$to_booked_date."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        return $this->render('rsvp/changehistory',['user_id'=>$user_id,'from_booked_date'=>$from_booked_date,'to_booked_date'=>$to_booked_date,'data' => $data,'customer_name'=>$customer_name,'pagination'=> $pagination]);


    }


    public function actionMergeHistory()
    {

        $user_id          = $this->cleanMe(Router::post('user_id'));
        $from_booked_date = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date   = $this->cleanMe(Router::post('to_booked_date'));

        $page          = $this->cleanMe(Router::post('page')); 
        $page          = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ 
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "page"               => $page];



        $data=$this->rsvphistorymdl->getmergeHistory($filter);
        $customer_name = '';
        $admin_name    = '';
        if(!empty($user_id)) {

            $customer_name = $this->mdl->getcustomername($user_id);

        }

        $onclick = "onclick=pageHistory('".$user_id."','".$from_booked_date."','".$to_booked_date."','***')";
        $pagination = $this->pag->getPaginationString($data['curPage'],$data['count'],$data['perPage'],1, $onclick,'pagestring');
        return $this->render('rsvp/mergehistory',['user_id'=>$user_id,'from_booked_date'=>$from_booked_date,'to_booked_date'=>$to_booked_date,'data' => $data,'customer_name'=>$customer_name,'pagination'=> $pagination]);


    }

    public function actionCreate()
    {

    	$this->checkPageAccess(69);
    	$hourList     = $this->mdl->getHours();
        $customerList = $this->usermdl->searchCustomers('');
        return $this->render('rsvp/create',['hourList'=>$hourList,'customerList'=>$customerList]);

    }

    public function actionGetMobileNumber()
    {

        $user_ids           = !empty($_POST['user_ids']) ? $_POST['user_ids'] :[];

        $mobileNumbers = $this->usermdl->getMobileNumbers($user_ids);

        if(!empty($mobileNumbers)) {

            $mobileNumbers = array_column($mobileNumbers, 'mobile');
            $mobileNumbers = implode(',',$mobileNumbers);

        }

        echo json_encode($mobileNumbers);





    }


    public function actionEdit()
    {

        $this->checkPageAccess(70);
        $id = $this->cleanMe(Router::req('id'));
        $id = base64_decode($id);
        $hourList = $this->mdl->getHours();
        $details  = $this->mdl->getDetails($id);
        $room_details  = $this->roommdl->getdetailsofroom($details['room_id']);
        $is_room_table = $room_details['type'];
        $room_list     = $this->roommdl->getDatByType($is_room_table);
        $details['booked_date']   = !empty($details['booked_date']) ? date('d-m-Y',$details['booked_date']) : '';
        //$timeData  = $this->hourmdl->getTime($details['hour_id']);
        if(!empty($details['hour_id'])) {

            $timeData['from_time'] = date('h:i A',($details['checkin_time']));
            $timeData['to_time'] = date('h:i A',($details['checkout_time']));

        }

        if(empty($details['hour_id'])) {

            $timeData['from_time'] = date('h:i A',($details['checkin_time']));
            $timeData['to_time'] = date('h:i:A',($details['checkout_time']));

        }
        
        $customer_name = '';
        if(!empty($details['user_id'])) {

            $customer_name = $this->mdl->getcustomername($details['user_id']);

        }

        $customerList = $this->usermdl->searchCustomers('');

        $mobileNumbers = $this->usermdl->getMobileNumbers(explode(',',$details['user_id']));

        if(!empty($mobileNumbers)) {

            $mobileNumbers = array_column($mobileNumbers, 'mobile');
            $mobileNumbers = implode(',',$mobileNumbers);

        }

        $details['mobileNumbers'] = $mobileNumbers;

        



              
        return $this->render('rsvp/edit',['hourList'=>$hourList,'details'=>$details,'is_room_table'=>$is_room_table,'room_list'=>$room_list,'timeData'=>$timeData,'customer_name'=>$customer_name,'customerList'=>$customerList]);

    }

    public function actionAddBooking()
    {

        $id           = $this->cleanMe(Router::post('id'));
    	$type         = $this->cleanMe(Router::post('type'));
    	$room_id      = $this->cleanMe(Router::post('room_id'));
    	$booked_date  = $this->cleanMe(Router::post('booked_date'));
    	$hour_id      = $this->cleanMe(Router::post('hour_id'));
    	$user_id      = $this->cleanMe(Router::post('user_id'));
    	$mummy_name   = $_POST['mummy_name'];
        $remarks      = $this->cleanMe(Router::post('remarks'));
        $status       = $this->cleanMe(Router::post('status'));
        $mummy_name   = !empty($mummy_name) ? json_decode($mummy_name,true) : [];
        $rsvp_type    = $this->cleanMe(Router::post('rsvp_type'));
        $status       = $this->cleanMe(Router::post('status'));


        $checkin_time     = !empty(Router::post('checkin_time')) ? strtotime($this->cleanMe(Router::post('checkin_time'))):'';
        $checkout_time       = !empty(Router::post('checkout_time')) ? strtotime($this->cleanMe(Router::post('checkout_time'))):'';

        //new updations
        // $is_custom_time       = !empty(Router::post('is_custom_time')) ? $this->cleanMe(Router::post('is_custom_time')) :'0';
        // $custom_from_time     = !empty(Router::post('custom_from_time')) ? strtotime($this->cleanMe(Router::post('custom_from_time'))):'';
        // $custom_to_time       = !empty(Router::post('custom_to_time')) ? strtotime($this->cleanMe(Router::post('custom_to_time'))):'';

        $receptionist_no       = !empty(Router::post('receptionist_no')) ? $this->cleanMe(Router::post('receptionist_no')) : '';
        $pr_manager       = !empty(Router::post('pr_manager')) ? $this->cleanMe(Router::post('pr_manager')) : '';
        $brought_in_by       = !empty(Router::post('brought_in_by')) ? $this->cleanMe(Router::post('brought_in_by')) : '';
        $male_count       = !empty(Router::post('male_count')) ? $this->cleanMe(Router::post('male_count')) : 0;
        $female_count       = !empty(Router::post('female_count')) ? $this->cleanMe(Router::post('female_count')) : 0;


       


       




        if(!empty($id)) {

            $details = $this->mdl->getDetails($id);

            $current_date = date("Y-m-d");
            $current_date = strtotime($current_date);

            // if($details['booked_date'] < $current_date) {

            //     return $this->sendMessage("error","Sorry You cannot edit this RSVP.");
            //     die();

            // }

            $booked_checkin_date = date('Y-m-d',$details['booked_date']).' '.date('H:i:s',$details['checkin_time']);
            $booked_checkout_date = date('Y-m-d',$details['booked_date']).' '.date('H:i:s',$details['checkout_time']);
            $booked_checkin_date      =  strtotime($booked_checkin_date);

            if(time()>strtotime($booked_checkout_date)) {

                return $this->sendMessage("error","Sorry You cannot edit this RSVP.");
                die();

            }
            
   
            // if($details['booked_date'] < time()) {
            //     return $this->sendMessage("error","Sorry You cannot edit this RSVP.");
            //     die();
            // }

            // $hourDetails = $this->hourmdl->getTime($details['hour_id']);
            // //check already started

            // $booked_date_check = date('Y-m-d',$details['booked_date']);
            // $booked_date_check = strtotime($booked_date_check)+$details['checkin_time'];
            // //$booked_date_check = strtotime($booked_date_check);
            // if($booked_date_check < time()) {
            //     return $this->sendMessage("error","Sorry You cannot edit this RSVP.");
            //     die();
            // }
            


        }
        
        if(empty($type)) {

            return $this->sendMessage("error","Please select a room/table to Proceed");
            die();

        }
        if(empty($room_id)) {

            return $this->sendMessage("error","Please select a room/table number to Proceed");
            die();

        }

        $room_details = $this->mdl->getRoomDetails($room_id);
        if($room_details['status']!='0') {

            return $this->sendMessage("error","Please select an active room/table number to Proceed");
            die();

        }

        if(empty($booked_date)) {

            return $this->sendMessage("error","Please select a date to Proceed");
            die();

        }
        if(!empty($booked_date)) {

        	$newbooked_date = date('Y-m-d',strtotime($booked_date));
        	$currentDate    = date('Y-m-d');
        	$currentDate    = strtotime($currentDate);
   
		    if(strtotime($newbooked_date) < $currentDate) {
		        return $this->sendMessage("error","The selected date already passed");
                die();
		    }
		        	

        }
        // if(empty($hour_id) && empty($is_custom_time)) {

        //     return $this->sendMessage("error","Please select hour type/custom Time to Proceed");
        //     die();

        // }
        if(empty($hour_id)) {

            return $this->sendMessage("error","Please select hour type to Proceed");
            die();

        }

        if(!empty($hour_id)) {

            $hourDetails = $this->hourmdl->getTime($hour_id);
            if(!empty($hourDetails)){

                if($hourDetails['status']!='0') {

                    return $this->sendMessage("error","The selected hour is already disabled by admin.Please select another to proceed");
                    die();

                }
                //check the time already passed
                $current_time   = time();
                $newbooked_date = date('Y-m-d',strtotime($booked_date));
                $selected_time  = $newbooked_date.' '.date("H:i",$checkout_time);
                $selected_time  = strtotime($selected_time);
                if($current_time > $selected_time) {

                    return $this->sendMessage("error","The selected time slot already passed.Please select another to proceed");
                    die();


                }




            }

            //check already booked 
            $params = [];
            $params['id']               = $id;
            $params['hour_id']          = $hour_id;
            $params['booked_date']      = $booked_date;
            $params['room_id']          = $room_id;
            $params['checkin_time']     = $checkin_time;
            $params['checkout_time']    = $checkout_time;
            $check = $this->mdl->checkcanBook($params);

            if(!$check) {

                return $this->sendMessage("error","Selcted time slot is not available");
                die();

            }

        }

        // if(!empty($is_custom_time)) {

        //     if(empty($custom_from_time)) {

        //         return $this->sendMessage("error","Please enter check in time to proceed");
        //         die();

        //     }

        //     if(empty($custom_to_time)) {

        //         return $this->sendMessage("error","Please enter check out time to proceed");
        //         die();

        //     }


        //     if($custom_from_time == $custom_to_time) {

        //         return $this->sendMessage("error","Checkin & checkout time should be different");
        //         die();

        //     }

        //     //check time already passed

        //     $newbooked_date = date('Y-m-d',strtotime($booked_date));
        //     $selected_time  = $newbooked_date.' '.date("H:i:s",$custom_from_time);
        //     $selected_time  = strtotime($selected_time);
        //     $current_time   = time();
        //     if($current_time > $selected_time) {
        //     //if($selected_time < $current_time) {

        //         return $this->sendMessage("error","The selected time slot already passed.Please select another to proceed");
        //         die();


        //     }



        //     //check already booked 
        //     $params = [];
        //     $params['id']               = $id;
        //     $params['hour_id']          = $hour_id;
        //     $params['booked_date']      = $booked_date;
        //     $params['room_id']          = $room_id;
        //     $params['checkin_time']     = $custom_from_time;
        //     $params['checkout_time']    = $custom_to_time;


        //     $check = $this->mdl->checkcanBook($params);
        //     if(!$check) {

        //         return $this->sendMessage("error","Selcted time slot is not available");
        //         die();

        //     }



        // }

        

        

        

        if(empty($user_id)) {

            return $this->sendMessage("error","Please select customer name to Proceed");
            die();

        }

        $userDetails = $this->customermdl->getCustomerInfo($user_id);
        if(empty($userDetails)) {

            return $this->sendMessage("error","The selected customer is not a valid customer");
            die();

        }
        if($userDetails['status']!='0') {

            return $this->sendMessage("error","The selected customer is not an active customer");
            die();

        }

        
        if(empty($mummy_name)) {

            return $this->sendMessage("error","Please enter mummy name to Proceed");
            die();

        }

        if(in_array('', $mummy_name)){

            return $this->sendMessage("error","Please fill all the mummy names to proceed");
            die();

        }

        if(empty($rsvp_type)) {

            return $this->sendMessage("error","Please seelct rsvp type to Proceed");
            die();

        }

        if(empty($receptionist_no)) {

            return $this->sendMessage("error","Please enter receptionist no to Proceed");
            die();

        }
        if(empty($pr_manager)) {

            return $this->sendMessage("error","Please enter PR Manager to Proceed");
            die();

        }
        if(empty($brought_in_by)) {

            return $this->sendMessage("error","Please enter Brought In By to Proceed");
            die();

        }
        if(empty($male_count) && empty($female_count)) {

            return $this->sendMessage("error","Please enter number of male/female to Proceed");
            die();

        }

        $total_pax = $male_count+$female_count;
        if( $type=='1' && $total_pax > $room_details['max_allowed']) {

            return $this->sendMessage("error","In this room can accomodate ".$room_details['max_allowed'].' only');
            die();


        }

        // if(empty($remarks)) {

        //     return $this->sendMessage("error","Please enter remarks to Proceed");
        //     die();

        // }

        // if(!empty($hour_id)) {

        //     $checkin_time  = strtotime($hourDetails['from_time']);
        //     $checkout_time = strtotime($hourDetails['to_time']);

        // }
        if(!empty($hour_id)) {

            $checkin_time  = $checkin_time;
            $checkout_time = $checkout_time;

        }

        if(empty($hour_id)) {

            $checkin_time  = $custom_from_time;
            $checkout_time = $custom_to_time;

        }



        $booked_date = date('Y-m-d',strtotime($booked_date));
        $booked_date = strtotime($booked_date);
            

        $params = [];
        $params['room_id']               = $room_id;
        $params['booked_date']           = $booked_date;
        $params['rsvp_type']             = $rsvp_type;
        $params['hour_id']               = $hour_id;
        $params['user_id']               = $user_id;
        $params['mummy_name']            = json_encode($mummy_name);
        $params['remarks']               = $remarks;
        $params['status']                = $status;
        //$params['is_custom_time']        = $is_custom_time;
        //$params['custom_from_time']      = $custom_from_time;
        //$params['custom_to_time']        = $custom_to_time;
        $params['receptionist_no']       = $receptionist_no;
        $params['pr_manager']            = $pr_manager;
        $params['brought_in_by']         = $brought_in_by;
        $params['male_count']            = $male_count;
        $params['female_count']          = $female_count;

        $params['checkin_time']          = $checkin_time;
        $params['checkout_time']         = $checkout_time;



        if(empty($id)) {

            if($this->mdl->addRsvp($params)) {

                return $this->sendMessage("success","Successfully created the RSVP");
                die();

            }else{

                return $this->sendMessage("error","Sorry!Failed to create RSVP");
                die();          

            }

        }else{


            $details = $this->mdl->getDetails($id);

            //check have merge
            $have_merge = $this->mdl->checkisMerged($id);
            
            $old_booked_date = $details['booked_date'];
            $new_booked_date = $booked_date;
            $remove_merge = false;
            if($old_booked_date != $new_booked_date) {

                $remove_merge = true;

            }

            $old_hour_id = $details['hour_id'];
            $new_hour_id = $hour_id;

            if($old_hour_id != $new_hour_id) {

                $remove_merge = true;

            }

            $room_type = $this->roommdl->gettype($room_id);
            if($room_type!=1) {

                $remove_merge = true;

            }

            $old_checkin_time  = $details['checkin_time'];
            $old_checkout_time = $details['checkout_time'];

            if($checkin_time != $old_checkin_time) {

                $remove_merge = true;

            }
            if($checkout_time != $old_checkout_time) {
                
                $remove_merge = true;

            }

            $params['status'] = $details['status'];
            
            $params['id'] = $id;
            if($this->mdl->updateRsvp($params)) {

                if($have_merge && $remove_merge) {
                    
                    $this->mdl->removeMerge(['id'=>$id]);
                }

                return $this->sendMessage("success","Successfully updated the RSVP");
                die();

            }else{

                return $this->sendMessage("error","Sorry!Failed to update RSVP");
                die();          

            }

        }
        

        return $this->sendMessage("error","Something went wrong");
        die();


        


    }

    public function actionChangeStatus()
    {
        

        $id  = $this->cleanMe(Router::post('id'));
        $status  = $this->cleanMe(Router::post('status'));

        if(empty($id)) {

            return $this->sendMessage("error","Please select an RSVP to proceed");
            die();

        }

        $details = $this->mdl->getDetails($id);
        if($details['status'] == $status) {

            return $this->sendMessage("error","Status already changed by another admin");
            die();

        }
        //check enabling possible
        if($status == '0'){

            $params = [];
            $params['id']          = $details['id'];
            $params['hour_id']     = $details['hour_id'];
            $params['booked_date'] = date('d-m-Y',$details['booked_date']);
            $params['room_id']     = $details['room_id'];
            $params['checkin_time'] = $details['checkin_time'];
            $params['checkout_time'] = $details['checkout_time'];
            $check = $this->mdl->checkcanBook($params);
            if(!$check) {

                return $this->sendMessage("error","Selected time slot is not available now");
                die();

            }


            //check the time already  passed

            $booked_date = date('Y-m-d',$details['booked_date']).' '.date('H:i:s',$details['checkin_time']);;
            $booked_date = strtotime($booked_date);
            if(time()>=$booked_date){ //check booked time already passed

                return $this->sendMessage("error","Selected time already started");
                die();

            }

        }

        if($status == '1') {

            //check the time already  passed
            $booked_date = date('Y-m-d',$details['booked_date']).' '.date('H:i:s',$details['checkin_time']);;
            $booked_date = strtotime($booked_date);
            if(time()>=$booked_date){ //check booked time already passed

                return $this->sendMessage("error","Selected time already started");
                die();

            }

        }



        $params = [];
        $params['id']     = $id;
        $params['status'] = $status;
        if($this->mdl->changeStatus($params)){


            if($status == '1') {//disable 

                //check have merge


                $have_merge = $this->mdl->checkisMerged($id);   
                if($have_merge) {

                    $this->mdl->removeMerge(['id'=>$id]);

                }

            }

            $msg = "Successfully changed the status to ".$this->statusArry[$status];
            return $this->sendMessage("success",$msg);
            die();

        }else{

            $msg = "Sorry! Failed to change the status";
            return $this->sendMessage("error",$msg);
            die();


        }

        $msg = "Something went wrong";
        return $this->sendMessage("error",$msg);
        die();







    }


    public function actionGetRooms()
    {

    	$type  = $this->cleanMe(Router::post('type'));
    	$list  = $this->roommdl->getDatByType($type);

    	$options = '<option value="">Room/Table Number</option>';
        foreach($list as $key=>$value){

        	$options .= '<option value='.$value['id'].'>'.$value['room_no'].'</option>';

        }
        echo json_encode($options);


    }

    public function actionGetTime()
    {

    	$id        = $this->cleanMe(Router::post('id'));
    	$timeData  = $this->hourmdl->getTime($id);
    	$timeData['from_time'] = date('h:i A',strtotime($timeData['from_time']));
    	$timeData['to_time'] = date('h:i A',strtotime($timeData['to_time']));
    	echo json_encode($timeData);

    
    }

    



    public function actionMergeRoom()
    {
        
        $rsvp_id = $this->cleanMe(Router::get('id'));
        $rsvp_id = base64_decode($rsvp_id);

        $action = $this->cleanMe(Router::get('action'));
        $action = base64_decode($action);
        $action = empty($action) ? '1':$action;

        $merge_room_id = $this->cleanMe(Router::get('merge_room_id'));
        $merge_room_id = base64_decode($merge_room_id);

        
        
        if(!empty($rsvp_id)) {

            $rsvp_details = $this->mdl->getDetails($rsvp_id);
            $room_type = $this->roommdl->gettype($rsvp_details['room_id']);
            if($room_type!='1') {

                $this->redirect($_SERVER['HTTP_REFERER']);
                die();

            }


        }
        

        //get rsvp list
        $room_list           = $this->mdl->getChangeRoomRsvpList();


       
        $available_room_list = [];
        if($rsvp_id) {

            $available_room_list = $this->mdl->getAvailableRoomList($rsvp_id);
           
        }



        

        return $this->render('rsvp/merge_room',['rsvp_id'=>$rsvp_id,'room_list'=>$room_list,'available_room_list'=>$available_room_list,'merge_room_id'=>$merge_room_id,'action'=>$action]);
        

    }

    public function actionMergeChangeRoom()
    {
        
        $rsvp_id             = '';
        $room_list           = [];
        $available_room_list = [];
        $merge_room_id       = '';
        $action              = '';


        return $this->render('rsvp/merge_room',['rsvp_id'=>$rsvp_id,'room_list'=>$room_list,'available_room_list'=>$available_room_list,'merge_room_id'=>$merge_room_id,'action'=>$action]);

    }

    public function actionRooms()
    {

        $action  = $this->cleanMe(Router::post('action'));
        $room_list = [];
        if($action == '1') { //merge

            $room_list           = $this->mdl->getChangeRoomRsvpList();

        }

        if($action == '2') { //change room

            $room_list           = $this->mdl->getChangeRoomList();

        }

        $options = '<option value="">Select Room</option>';
        foreach($room_list as $key=>$value){

            $options .= '<option value='.$value['rsvp_id'].'>'.$value['room_no'].'</option>';

        }
        echo json_encode($options);



    }

    public function actionGetMergeAvailableRooms(){

        $rsvp_id  = $this->cleanMe(Router::post('rsvp_id'));
        $action   = $this->cleanMe(Router::post('action'));
        if($action == '1') {
            $available_room_list = !empty($rsvp_id) ? $this->mdl->getAvailableRoomList($rsvp_id) :[];
            $options = '<option value="">Merge With</option>';
            

            if(!empty($available_room_list)) {

                foreach($available_room_list as $key=>$value){

                    $options .= '<option value='.$value['rsvp_id'].'>'.$value['room_no'].'</option>';

                }

            }

        }else if($action == '2'){

            $available_room_list = !empty($rsvp_id) ? $this->mdl->getChangeRoomAvailableRoomList($rsvp_id):[];
            $options = '<option value="">Change With</option>';
            if(!empty($available_room_list)) {

                foreach($available_room_list as $key=>$value){

                    $options .= '<option value='.$value['rsvp_id'].'_'.$value['room_id'].'>'.$value['room_no'].'</option>';

                }

            }
            

        }else{

            $available_room_list = [];

        }
        
        
        echo json_encode($options);

    }

    public function actionProceedMerge()
    {

        $rsvp_id  = $this->cleanMe(Router::post('rsvp_id'));
        $merge_id = $this->cleanMe(Router::post('merge_id'));
        $action = $this->cleanMe(Router::post('action'));
        if(empty($rsvp_id)) {

            $msg = "Please select a room to proceed";
            return $this->sendMessage("error",$msg);
            die();

        }

        if(empty($merge_id)) {

            $msg = "Please select merge with to proceed";
            return $this->sendMessage("error",$msg);
            die();

        }

        if($action == '1') { // merge

            if($rsvp_id == $merge_id) {

                $msg = "Both the rooms should not be same";
                return $this->sendMessage("error",$msg);
                die();

            }

            //check the rsvp already expired
            $rsvp_details = $this->mdl->getDetails($rsvp_id);
            if($rsvp_details['status'] == '1') {

                $msg = "The selected RSVP is already cancelled";
                return $this->sendMessage("error",$msg);
                die();

            }

            $rsvp_booked_date = date('Y-m-d',$rsvp_details['booked_date']);
            //$hourDetails = $this->hourmdl->getTime($rsvp_details['hour_id']);
            $rsvp_booked_date = $rsvp_booked_date.' '.date('H:i:s',$rsvp_details['checkout_time']);
            $rsvp_booked_date = strtotime($rsvp_booked_date);
            if(time()>=$rsvp_booked_date){ //check booked time already passed

                return $this->sendMessage("error","The selected RSVP booked time already passed");
                die();

            }


            //check the mergewith  already expired
            $merge_with_details = $this->mdl->getDetails($merge_id);
            if($merge_with_details['status'] == '1') {

                $msg = "The selected merge with already cancelled";
                return $this->sendMessage("error",$msg);
                die();

            }

            $merge_with_booked_date = date('Y-m-d',$merge_with_details['booked_date']);
            //$hourDetails = $this->hourmdl->getTime($merge_with_details['hour_id']);
            //$merge_with_booked_date = $merge_with_booked_date.' '.$hourDetails['from_time'];
            $merge_with_booked_date = $merge_with_booked_date.' '.date('H:i:s',$merge_with_details['checkout_time']);
            $merge_with_booked_date = strtotime($merge_with_booked_date);
            if(time()>=$merge_with_booked_date){ //check booked time already passed

                return $this->sendMessage("error","The selected merge with booked time already passed");
                die();

            }
            
            $params = [];
            $params['rsvp_id']  = $rsvp_id;
            $params['merge_id'] = $merge_id;


            $check = $this->mdl->checkisMerged($rsvp_id);

         
            
            if($check) {

                return $this->sendMessage("error","The selected merge with already merged with other RSVP");
                die();


            }
            
            $params = [];
            $params['id']       = $rsvp_id;
            $params['merge_id'] = $merge_id;
            $response = $this->mdl->mergeRoom($params);
            if($response){

                $msg    = "Successfully merged room";
                $status = 'success';

            }else{

                $msg    = "Sorry!Failed to merge room";
                $status = 'error';

            }

        }

        if($action == '2') { //change room 

            $rsvp_details = $this->mdl->getDetails($rsvp_id);
            $room_details = $this->roommdl->getDetails($rsvp_details['room_id']);

            if($rsvp_details['status']!='0') {

                $msg    = "Sorry! Room ".$room_details['room_no']." RSVP already cancelled";
                $status = 'error';
                return $this->sendMessage($status,$msg);
                die();


            }

            $is_merged = $this->mdl->checkisMerged($rsvp_id);
            
            //check already merged
            if($is_merged) {

                $msg    = "Sorry! Room ".$room_details['room_no']." already merged with some other room";
                $status = 'error';
                return $this->sendMessage($status,$msg);
                die();


            }

            $merge_id_exploded = explode('_',$merge_id);

            $mereg_rsvp_id = $merge_id_exploded['0'];
            $room_id       = $merge_id_exploded['1'];
            $is_merged = '';
            $room_details = $this->roommdl->getDetails($room_id);
            if($mereg_rsvp_id ) {
                

                $rsvp_details = $this->mdl->getDetails($mereg_rsvp_id);
                if($room_details['status'] == '1'){

                    $msg    = "Sorry! Room ".$room_details['room_no']." is not active now";
                    $status = 'error';
                    return $this->sendMessage($status,$msg);
                    die();

                }
                if($rsvp_details['status']!='0') {

                    $msg    = "Sorry! Room ".$room_details['room_no']." RSVP already cancelled";
                    $status = 'error';
                    return $this->sendMessage($status,$msg);
                    die();


                }

                $is_merged = $this->mdl->checkisMerged($mereg_rsvp_id);
                if($is_merged) {

                    $msg    = "Sorry! Room ".$room_details['room_no']." already merged with some other room";
                    $status = 'error';
                    return $this->sendMessage($status,$msg);
                    die();


                }

            }

            if($room_details['status'] == '1'){

                $msg    = "Sorry! Room ".$room_details['room_no']." is not active now";
                $status = 'error';
                return $this->sendMessage($status,$msg);
                die();

            }

            
            

            if(!empty($mereg_rsvp_id)){ //swap rooms



                $rsvp_details1 = $this->mdl->getDetails($rsvp_id);
                $rsvp_details2 = $this->mdl->getDetails($mereg_rsvp_id);

                $swap1_params = [];
                $swap1_params['id']       = $rsvp_id;
                $swap1_params['room_id']  = $rsvp_details2['room_id'];
                $swap1_params['user_id']  = $rsvp_details1['user_id'];
                $swap1_params['hour_id']  = $rsvp_details1['hour_id'];
                $swap1_params['checkin_time']     = $rsvp_details1['checkin_time'];
                $swap1_params['checkout_time']     = $rsvp_details1['checkout_time'];
                $swap1_params['activity'] = 'Admin swaped the room in RSVP .id-'.$rsvp_id.'Old Room id-'.$rsvp_details1['room_id'].'.New Room id-'.$rsvp_details2['room_id'].'.';

                $response1 = $this->mdl->changeRoom($swap1_params);

                //add history
                $historyParams = [];
                $historyParams['old_room_id']   = $rsvp_details1['room_id'];
                $historyParams['new_room_id']   = $rsvp_details2['room_id'];
                $historyParams['rsvp_id']       = $rsvp_id;
                $historyParams['merge_rsvp_id'] = $rsvp_details2['id'];
                $historyParams['type']          = '1';
                $historyParams['status']        = '0';
                



                $swap2_params = [];
                $swap2_params['id']       = $mereg_rsvp_id;
                $swap2_params['room_id']  = $rsvp_details1['room_id'];
                $swap2_params['user_id']  = $rsvp_details2['user_id'];
                $swap2_params['hour_id']  = $rsvp_details2['hour_id'];
                $swap2_params['checkin_time']     = $rsvp_details2['checkin_time'];
                $swap2_params['checkout_time']     = $rsvp_details2['checkout_time'];
                
                $swap2_params['activity'] = 'Admin swaped the room in RSVP .id-'.$mereg_rsvp_id.'Old Room id-'.$rsvp_details2['room_id'].'.New Room id-'.$rsvp_details1['room_id'].'.';

                $response2 = $this->mdl->changeRoom($swap2_params);

                //add history
                $historyParams2 = [];
                $historyParams2['old_room_id']   = $rsvp_details2['room_id'];
                $historyParams2['new_room_id']   = $rsvp_details1['room_id'];
                $historyParams2['rsvp_id']       = $mereg_rsvp_id;
                $historyParams2['merge_rsvp_id'] = $rsvp_details1['id'];
                $historyParams2['type']          = '1';
                $historyParams2['status']        = '0';
                

                if($response1 && $response2) {
                    
                    $this->rsvphistorymdl->addHistory($historyParams);
                    $this->rsvphistorymdl->addHistory($historyParams2);
                    $msg    = "Successfully changed room";
                    $status = 'success';
                    return $this->sendMessage($status,$msg);
                    die();

                }

                $msg    = "Failed to change room";
                $status = 'error';
                return $this->sendMessage($status,$msg);
                die();


            }

            if(empty($mereg_rsvp_id)){ //only change room 

                $rsvp_details1 = $this->mdl->getDetails($rsvp_id);
                
                $swap1_params = [];
                $swap1_params['id']       = $rsvp_id;
                $swap1_params['room_id']  = $room_details['id'];
                $swap1_params['user_id']  = $rsvp_details1['user_id'];
                $swap1_params['hour_id']  = $rsvp_details1['hour_id'];
                
                $swap1_params['activity'] = 'Admin changed the room in RSVP .id-'.$rsvp_id.'Old Room id-'.$rsvp_details1['room_id'].'.New Room id-'.$room_details['id'].'.';

                $response = $this->mdl->changeRoom($swap1_params);
                if($response) {
                    
                    //add history
                    $params = [];
                    $params['old_room_id']   = $rsvp_details1['room_id'];
                    $params['new_room_id']   = $room_details['id'];
                    $params['rsvp_id']       = $rsvp_details1['id'];
                    $params['merge_rsvp_id'] = '';
                    $params['type']          = '1';
                    $params['status']        = '0';
                    $this->rsvphistorymdl->addHistory($params);

                    $msg    = "Successfully changed room";
                    $status = 'success';
                    return $this->sendMessage($status,$msg);
                    die();

                }

                $msg    = "Failed to change room";
                $status = 'error';
                return $this->sendMessage($status,$msg);
                die();


            }


        }

        return $this->sendMessage($status,$msg);
        die();


    }


    public function actionCancelMerge() 
    {

        $id = $this->cleanMe(Router::post('id'));
        if(empty($id)) {

            $msg = "Please select an RSVP to proceed";
            return $this->sendMessage("error",$msg);
            die();

        }

        //check the rsvp already expired
        $details = $this->mdl->getDetails($id);
        if($details['status'] == '1') {

            $msg = "The selected RSVP is already cancelled";
            return $this->sendMessage("error",$msg);
            die();

        }

        $rsvp_booked_date = date('Y-m-d',$details['booked_date']);
        //$hourDetails = $this->hourmdl->getTime($details['hour_id']);
        $rsvp_booked_date = $rsvp_booked_date.' '.date('H:i:s',$details['checkout_time']);
        $rsvp_booked_date = strtotime($rsvp_booked_date);
        if(time()>=$rsvp_booked_date){ //check booked time already passed

            return $this->sendMessage("error","The selected RSVP booked time already passed");
            die();

        }

        if(empty($details['merge_room_id'])) {

            return $this->sendMessage("error","The selected RSVP have no merge");
            die();

        }
        
        $params = [];
        $params['id'] = $id; 
        $response = $this->mdl->cancelMerge($params);
        if($response) {

            return $this->sendMessage("success","Successfully cancelled the merge");
            die();

        }else{

            return $this->sendMessage("error","Failed to cancel the merge");
            die();
        }

        return $this->sendMessage("error","Something went wrong");
        die();




    }

    public function actionCheckismerged()
    {

        $id                      = $this->cleanMe(Router::post('id'));
        $check_date_changed      = !empty(Router::post('check_date_changed')) ? $this->cleanMe(Router::post('check_date_changed')) : false;
        $merged = $this->mdl->checkisMerged($id);
        $date_changed = false;
        if($check_date_changed) {

            $booked_date   = $this->cleanMe(Router::post('booked_date'));
            $hour_id       = $this->cleanMe(Router::post('hour_id'));
            $checkin_time  = $this->cleanMe(Router::post('checkin_time'));
            $checkout_time = $this->cleanMe(Router::post('checkout_time'));
            $room_id       = $this->cleanMe(Router::post('room_id'));


            $booked_date   = date('Y-m-d',strtotime($booked_date));
            $booked_date   = strtotime($booked_date);
            $checkin_time  = date('H:i:s',strtotime($checkin_time));
            $checkin_time  = strtotime($checkin_time);
            $checkout_time = date('H:i:s',strtotime($checkout_time));
            $checkout_time = strtotime($checkout_time);

            $details = $this->mdl->getDetails($id);
            if($booked_date!=$details['booked_date']) {

                $date_changed = true;

            }
            if($checkin_time!=$details['checkin_time']) {

                $date_changed = true;

            }

            if($checkout_time!=$details['checkout_time']) {

                $date_changed = true;

            }

            if($hour_id!=$details['hour_id']) {

                $date_changed = true;

            }

            if($room_id!=$details['room_id']) {

                $date_changed = true;

            }






        }

        if($date_changed && $merged) {

            $merged = true;

        }else{

            $merged = false;

        }

        
        echo json_encode($merged);




    }

    public function actionloadDetails(){

        $id      = $this->cleanMe(Router::get('id'));
        $details = $this->mdl->getRsvpDetails($id,true);
        $this->renderAjax('rsvp/loaddetails',['details'=>$details]);




    }

    public function actionloadMergeDetails()
    {
        $id      = $this->cleanMe(Router::get('id'));
        $details=$this->rsvphistorymdl->getDetails($id);
        $rsvpdetails = $this->mdl->getRsvpDetails($details['rsvp_id'],false);
        $details2 = $this->mdl->getRsvpDetails($details['merge_rsvp_id'],false);


        
        $this->renderAjax('rsvp/merge_history_modal',['details'=>$rsvpdetails,'details2'=>$details2]);

    }

    public function actionloadChangeDetails()
    {
        $id      = $this->cleanMe(Router::get('id'));
        $details =$this->rsvphistorymdl->getDetails($id);

       
        $rsvpdetails = $this->mdl->getRsvpDetails($details['rsvp_id'],false);

        $old_room_details = $this->mdl->getRoomDetails($details['old_room_id']);
        $new_room_details = $this->mdl->getRoomDetails($details['new_room_id']);
        $rsvpdetails['old_room'] = $old_room_details['type']=='1'?'R-'.$old_room_details['room_no']:'T-'.$old_room_details['room_no']; 
        $rsvpdetails['new_room'] = $old_room_details['type']=='1'?'R-'.$new_room_details['room_no']:'T-'.$new_room_details['room_no']; 


        
        $this->renderAjax('rsvp/change_history_modal',['details'=>$rsvpdetails]);

    }

    // public function actionloadChangeDetails()
    // {

    //     $id = $this->cleanMe(Router::get('id'));

    // }




    public function actionExport() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename         = 'Rsvp List'; 
        $status           = $this->cleanMe(Router::post('status'));
        $user_id          = $this->cleanMe(Router::post('user_id'));
        $from_booked_date = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date   = $this->cleanMe(Router::post('to_booked_date'));
        $room_id          = $this->cleanMe(Router::post('room_id'));
        $hour_id          = $this->cleanMe(Router::post('hour_id'));
        $rsvp_type        = $this->cleanMe(Router::post('rsvp_type'));
     
     
        
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        $filter=[ "status"             => $status,
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "room_id"            => $room_id,
                  "hour_id"            => $hour_id,
                  "rsvp_type"          => $rsvp_type,
                  "page"               => '1',
                  "export"             => true];
        $data = $this->mdl->getList($filter);


        


        
        $csv = "Customer Name,Room no/Table no ,Booking Date,Hour, Check in Time, Check out Time , Rsvp Type,Receptionist No,PR Manager,Brought In By,Male Count,Female Count,Remark,Mummy Name,Status,Created At \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            //$html.= $his['customer_name'].','.$his['room_table_no'].','.$his['booked_date'].','.$his['hour_name'].','.$his['time'].','.$his['rsvp_type'].','.$his['remarks'].',"'.$his['mummy_name'].'",'.$his['status'].','.$his['created_at']."\n"; //Append data to csv

            $html.= '"  '.$his['customer_name'].'",'.$his['room_table_no'].','.$his['booked_date'].','.$his['hour_name'].','.$his['from_time'].','.$his['to_time'].','.$his['rsvp_type'].','.$his['receptionist_no'].','.$his['pr_manager'].','.$his['brought_in_by'].','.$his['male_count'].','.$his['female_count'].','.$his['remarks'].',"'.$his['mummy_name'].'",'.$his['status'].','.$his['created_at']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Rsvp .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }

    public function actionGetCardDetails()
    {
        $rsvp_ids = $this->cleanMe(Router::post('rsvp_id'));
        $action  = $this->cleanMe(Router::post('action'));

        $rsvp_ids = explode('_',$rsvp_ids);
        $rsvp_id  = !empty($rsvp_ids[0]) ? $rsvp_ids[0] : '';
        
        $titleArray = ['2'=>'Merge with Details','1'=>'Change With Room Details'];
        
        $title  = !empty($action) ? $titleArray[$action]:'Booking Details';
        
        
        $html  = '<h5 class="card-title">'.$title.'</h5>';
        if($rsvp_id) {

            $details = $this->mdl->getRsvpDetails($rsvp_id);
            $roomDetails = $this->mdl->getRoomDetails($details['room_id']);
            $html .= '<table class="table">
            <tbody>
              <tr>
                <th scope="row">Customer Name</th>
                <td>'.$details['customer_name'].'</td>
              </tr>
              <tr>
                <th scope="row">Contact No</th>
                <td>'.$details['contact_no'].'</td>
              </tr>
              <tr>
                <th scope="row">Room No/Table No</th>
                <td>'.$details['room_no'].'</td>
              </tr>
              <tr>
                <th scope="row">Booked Date</th>
                <td>'.$details['booked_date'].'</td>
              </tr>
              <tr>
                <th scope="row">Hour Type</th>
                <td>'.$details['hour_name'].'</td>
              </tr>
              <tr>
                <th scope="row">Time</th>
                <td>'.$details['hour_time'].'</td>
              </tr>
              <tr>
                <th scope="row">Rsvp Type </th>
                <td>'.$details['rsvp_type'].'</td>
              </tr>
              <tr>
                <th scope="row">Mummy Name </th>
                <td>'.$details['mummy_name'].'</td>
              </tr>
              
              <tr>
                <th scope="row">Receptionist No </th>
                <td>'.$details['receptionist_no'].'</td>
              </tr>
              
              <tr>
                <th scope="row">PR Manager  </th>
                <td>'.$details['pr_manager'].'</td>
              </tr>
              
              <tr>
                <th scope="row">Brought In By </th>
                <td>'.$details['brought_in_by'].'</td>
              </tr>
              <tr>
                <th scope="row">Male Count </th>
                <td>'.$details['male_count'].'</td>
              </tr>
              <tr>
                <th scope="row">Female Count </th>
                <td>'.$details['female_count'].'</td>
              </tr>

              
              <tr>
                <th scope="row">Maximum Allowed</th>
                <td>'.$roomDetails['max_allowed'].'</td>
              </tr>
              
              
            </tbody>
          </table>';

        }

        if(empty($rsvp_id)) {

            $roomDetails = $this->mdl->getRoomDetails($rsvp_ids[1]);

            $html .= '<table class="table">
            <tbody>
              <tr>
                <th scope="row">Customer Name</th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Contact No</th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Room No/Table No</th>
                <td>'.$roomDetails['room_no'].'</td>
              </tr>
              <tr>
                <th scope="row">Booked Date</th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Hour Type</th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Time</th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Rsvp Type </th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Mummy Name </th>
                <td>-</td>
              </tr>
              
              <tr>
                <th scope="row">Receptionist No </th>
                <td>-</td>
              </tr>
              
              <tr>
                <th scope="row">PR Manager  </th>
                <td>-</td>
              </tr>
              
              <tr>
                <th scope="row">Brought In By </th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Male Count </th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Female Count </th>
                <td>-</td>
              </tr>
              <tr>
                <th scope="row">Maximum Allowed</th>
                <td>'.$roomDetails['max_allowed'].'</td>
              </tr>
              
              
            </tbody>
          </table>';

        }

        echo json_encode($html);
        

        
                                                    
                                                  


    }

    public function actionExportChangeHistory() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename         = 'Rsvp Change History'; 
        $user_id          = $this->cleanMe(Router::post('user_id'));
        $from_booked_date = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date   = $this->cleanMe(Router::post('to_booked_date'));

        $page             = $this->cleanMe(Router::post('page')); 
        $page             = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ 
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "page"               => $page];



        $data=$this->rsvphistorymdl->getChangeHistory($filter);
          
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        


        


        
        $csv = "Customer Name,Old Room No/Table No ,New Room No/Table No,Hour,Booked Date, Check in Time, Check out Time ,Updated Time,Updated By \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            //$html.= $his['customer_name'].','.$his['room_table_no'].','.$his['booked_date'].','.$his['hour_name'].','.$his['time'].','.$his['rsvp_type'].','.$his['remarks'].',"'.$his['mummy_name'].'",'.$his['status'].','.$his['created_at']."\n"; //Append data to csv

            $html.= '"  '.$his['customer_name'].'",'.$his['old_room'].','.$his['new_room'].','.$his['hour_name'].','.$his['booked_date'].','.$his['checkin_time'].','.$his['checkout_time'].','.$his['updated_at'].','.$his['updated_by']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Rsvp Change History .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }

    public function actionExportMergeHistory() {
        
        ini_set('max_execution_time', 30000);
        ini_set('memory_limit', '-1');

        $filename         = 'Rsvp Merge History'; 
        $user_id          = $this->cleanMe(Router::post('user_id'));
        $from_booked_date = $this->cleanMe(Router::post('from_booked_date'));
        $to_booked_date   = $this->cleanMe(Router::post('to_booked_date'));

        $page             = $this->cleanMe(Router::post('page')); 
        $page             = (!empty($page)) ? $page : '1'; 
        
        

        $filter=[ 
                  "user_id"            => $user_id,
                  "from_booked_date"   => $from_booked_date,
                  "to_booked_date"     => $to_booked_date,
                  "page"               => $page];



        $data=$this->rsvphistorymdl->getMergeHistory($filter);
          
        $time_nw = time();
        $export_excel_folder = BASEPATH .'web/upload'.DIRECTORY_SEPARATOR;

        


        


        
        $csv = "Customer Name,Room No/Table No ,Merged with  Room No/Table No,Hour,Booked Date, Check in Time, Check out Time ,Updated Time,Updated By  \n";
        $filename_nw = $filename.'_'.$time_nw.'.csv';
        $csv_handler = fopen($export_excel_folder.$filename_nw,'w');

        fprintf($csv_handler, chr(0xEF).chr(0xBB).chr(0xBF));
        fwrite($csv_handler,$csv);

        $html = "";

        foreach ($data['data'] as $his) { 



            //$html.= $his['customer_name'].','.$his['room_table_no'].','.$his['booked_date'].','.$his['hour_name'].','.$his['time'].','.$his['rsvp_type'].','.$his['remarks'].',"'.$his['mummy_name'].'",'.$his['status'].','.$his['created_at']."\n"; //Append data to csv

            $html.= '"  '.$his['customer_name'].'",'.$his['old_room'].','.$his['new_room'].','.$his['hour_name'].','.$his['booked_date'].','.$his['checkin_time'].','.$his['checkout_time'].','.$his['updated_at'].','.$his['updated_by']."\n"; //Append data to csv

        }
        if(!empty($html)){
            fwrite($csv_handler,$html);
        }

        fclose($csv_handler);

        $act="Admin export Rsvp Merge History .file -".$filename;
        $this->mdl->adminActivityLog($act);

        $download = '<a href="'.BASEURL.'web/upload/'.$filename_nw.'" download><button type="button" class="btn btn-primary" id="downloadcsv"  name="'.BASEURL.'web/upload/'.$filename_nw.'" style="float:right;">Download</button></a>';

        return $this->sendMessage('success',$download);
    }



        
    

    

    





   

   
}

