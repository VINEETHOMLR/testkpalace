<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class Rsvp extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "rsvp";
        $this->adminID   = $_SESSION[SITENAME.'_admin'];
        $this->IP        = $_SERVER['REMOTE_ADDR'];
        $this->perPage   = 10;
        $this->CommonModal           = (new CommonModal);
        $this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
        $this->admin_role     = $_SESSION[SITENAME.'_admin_role'];
        $this->statusArray = ['0'=>'Booked','1'=>'Cancelled'];
        $this->rsvptypeArray = ['1'=>'Walk in','2'=>'Online'];
        $this->roomTypeArray = ['1'=>'Room','2'=>'Table'];
        $this->typeArray = ['1'=>'R','2'=>'T'];
        
        
    }


    public function getHours()
    {
        return $this->callsql("SELECT id,name,from_time,to_time FROM hour_type WHERE status='0' ORDER BY id DESC",'rows');
    }

    public function getRoomDetails($id)
    {
        return $room_details = $this->callsql("SELECT id,room_no,status,max_allowed,type FROM room WHERE  id='$id'",'row');
    }
    public function addRsvp($params)
    {

        
        $room_id       = $params['room_id'];
        $booked_date   = $params['booked_date'];
        $rsvp_type     = $params['rsvp_type'];
        $hour_id       = $params['hour_id'];
        $user_id       = $params['user_id'];
        $mummy_name    = $params['mummy_name'];
        $remarks       = $params['remarks'];
        $status        = $params['status'];
        //$is_custom_time = $params['is_custom_time'];
        //$custom_from_time = $params['custom_from_time'];
        //$custom_to_time = $params['custom_to_time'];
        $updated_by    = $this->adminID;
        $created_at    = time();

        $checkin_time = $params['checkin_time'];
        $checkout_time = $params['checkout_time'];

        $receptionist_no       = $params['receptionist_no'];
        $pr_manager            = $params['pr_manager'];
        $brought_in_by         = $params['brought_in_by'];
        $male_count            = $params['male_count'];
        $female_count          = $params['female_count'];


        $sql = "INSERT INTO $this->tableName SET room_id='$room_id',booked_date='$booked_date',rsvp_type='$rsvp_type',hour_id='$hour_id',user_id='$user_id',mummy_name='$mummy_name',remarks='$remarks',status='$status',updated_by='$updated_by',created_at='$created_at',updated_at='$created_at',checkin_time='$checkin_time',checkout_time='$checkout_time',receptionist_no='$receptionist_no',pr_manager='$pr_manager',brought_in_by='$brought_in_by',male_count='$male_count',female_count='$female_count'";
        $this->query($sql);
        $this->execute();
        $id = $this->lastInsertId();

        $activity = "Rsvp Booked.Id-".$id;
        return $this->adminActivityLog($activity);  

    }

    public function updateRsvp($params)
    {

        $id            = $params['id'];
        $room_id       = $params['room_id'];
        $booked_date   = $params['booked_date'];
        $rsvp_type     = $params['rsvp_type'];
        $hour_id       = $params['hour_id'];
        $user_id       = $params['user_id'];
        $mummy_name    = $params['mummy_name'];
        $remarks       = $params['remarks'];
        $status        = $params['status'];
        //$merge_room_id = $params['merge_room_id'];
        $updated_by    = $this->adminID;
        $created_at    = time();

        
        $checkin_time = $params['checkin_time'];
        $checkout_time = $params['checkout_time'];
        $receptionist_no       = $params['receptionist_no'];
        $pr_manager            = $params['pr_manager'];
        $brought_in_by         = $params['brought_in_by'];
        $male_count            = $params['male_count'];
        $female_count          = $params['female_count'];

        $sql = "UPDATE $this->tableName SET room_id='$room_id',booked_date='$booked_date',rsvp_type='$rsvp_type',hour_id='$hour_id',user_id='$user_id',mummy_name='$mummy_name',remarks='$remarks',status='$status',updated_by='$updated_by',updated_at='$created_at',checkin_time='$checkin_time',checkout_time='$checkout_time',receptionist_no='$receptionist_no',pr_manager='$pr_manager',brought_in_by='$brought_in_by',male_count='$male_count',female_count='$female_count' WHERE id='$id'";
        $this->query($sql);
        $this->execute();
        $activity = "Rsvp Updated.Id-".$id;
        return $this->adminActivityLog($activity);

    }

    public function checkcanBook($params)
    {

        $id          = !empty($params['id']) ? $params['id'] : '';
        $hour_id     = !empty($params['hour_id']) ? $params['hour_id'] :'';
        $room_id     = !empty($params['room_id']) ?  $params['room_id'] : '';
        //$booked_date = $params['booked_date'];
        $booked_time = date('Y-m-d',strtotime($params['booked_date'])); 
        $booked_time_new = date('Y-m-d',strtotime($params['booked_date'])); 
        $booked_time = strtotime($booked_time);//converted into timestamp
        $checkin_time     = !empty($params['checkin_time']) ?  $booked_time_new.' '.date('H:i:s',$params['checkin_time']) : '';
        $checkout_time     = !empty($params['checkout_time']) ?  $booked_time_new.' '.date('H:i:s',$params['checkout_time']) : '';

        $where = '';
        if(!empty($id)) {

            $where .= " AND id!='$id'";

        }

        $return = true;

        // if(!empty($hour_id)) {

        //     $hour_details = $this->callsql("SELECT id,to_time,from_time FROM hour_type WHERE id='$hour_id'",'row'); 
        //     $checkin_time = $booked_time+strtotime($hour_details['from_time']);
        //     $checkout_time = $booked_time+strtotime($hour_details['to_time']);

        // }

        // if(empty($hour_id) && empty($id)) {

           
        //     $checkin_time = $booked_time+$checkin_time;
        //     $checkout_time = $booked_time+$checkout_time;

        // }

        $checkin_time  = strtotime($checkin_time);
        $checkout_time = strtotime($checkout_time);

   




        $sql = "SELECT id,room_id,hour_id,booked_date,checkin_time,checkout_time FROM $this->tableName WHERE room_id='$room_id' AND booked_date='$booked_time' $where";
        $details = $this->callsql($sql,'rows');



        


        foreach($details as $key=>$value){

               $hour = !empty($value['hour_id']) ? $value['hour_id'] : '';
            //if(!empty($hour)){



                //$hourDetails = $this->callsql("SELECT id,to_time,from_time FROM hour_type WHERE id='$hour'",'row'); 

                $db_checkin_time = date('Y-m-d',$value['booked_date']).' '.date('H:i:s',$value['checkin_time']);

                $db_checkin_time = strtotime($db_checkin_time);


                $db_checkout_time = date('Y-m-d',$value['booked_date']).' '.date('H:i:s',$value['checkout_time']);

                $db_checkout_time = strtotime($db_checkout_time);

                if($checkin_time >= ($db_checkin_time)   && $checkin_time <= ($db_checkout_time)) {


                     $return = false;
                }

                if($checkout_time >= ($db_checkin_time)   && $checkout_time <= ($db_checkout_time)) {


                     $return = false;
                }


               


              

                // if($checkin_time > ($booked_time+strtotime($hourDetails['from_time']))   && $checkout_time < ($booked_time+strtotime($hourDetails['to_time']))) {


                //      $return = false;
                // }


                // if($checkin_time == ($booked_time+strtotime($hourDetails['from_time']))   && $checkout_time == ($booked_time+strtotime($hourDetails['to_time']))) {

 
                //      $return = false;
                // }
                
           // }

            // if(empty($value['hour_id'])) {


            //    if($checkin_time >= ($booked_time+$value['checkin_time'])   && $checkin_time <= ($booked_time+$value['checkout_time'])) {


            //          $return = false;
            //     }

            //     if($checkout_time >= ($booked_time+$value['checkin_time'])   && $checkout_time <= ($booked_time+$value['checkout_time'])) {


            //          $return = false;
            //     }

            // }

        }

     

        return $return;

    



    }

    public function checkcanBookbkp($params)
    {

        $id          = !empty($params['id']) ? $params['id'] : '';
        $hour_id     = $params['hour_id'];
        $room_id     = $params['room_id'];
        $booked_date = $params['booked_date'];

        $where = '';
        if(!empty($id)) {

            $where .= " AND id!='$id'";

        }

        $booked_time = date('Y-m-d',strtotime($params['booked_date'])); 
        $booked_time = strtotime($booked_time);//converted into timestamp
        $details = $this->callsql("SELECT id,hour_id,room_id,booked_date FROM $this->tableName WHERE hour_id='$hour_id' AND booked_date='$booked_time' AND room_id='$room_id' AND status='0' $where ORDER BY id DESC LIMIT 1",'row');
        if(!empty($details)) {

            $hourDetails = $this->callsql("SELECT id,to_time,from_time FROM hour_type WHERE id='$hour_id'",'row'); 
            $to_time = $hourDetails['from_time'];
            $already_booked_date  = date('Y-m-d',$details['booked_date']);
            $already_booked_date  = $already_booked_date.' '.$to_time;
            $already_booked_date  = strtotime($already_booked_date);
            if(time()>$already_booked_date) {//if time already passed

                return true;;

            }

            return false;



        }

        return true;


    }
    
    public function getList($data)
    {


    	$where = ' WHERE id!=0';
    	if(!empty($data['status']) ||  in_array($data['status'],['0','1'])){
            $where .= " AND status = '$data[status]' ";
        }
        if(!empty($data['user_id'])) {

            //$where .= " AND user_id = '$data[user_id]' ";
            $where .= " AND FIND_IN_SET (".$data['user_id'].",user_id) ";

            

        }

        if(!empty($data['from_booked_date']) && !empty($data['to_booked_date'])) {

            $from_booked_date = strtotime($data['from_booked_date'].' 00:00:00');
            $to_booked_date   = strtotime($data['to_booked_date'].' 23:59:59');
            $where .= " AND booked_date BETWEEN '$from_booked_date' AND '$to_booked_date'";

        }

        if(!empty($data['room_id'])) {

            //$where .= " AND room_id = '$data[room_id]' ";

            $rsvp_ids = $this->callsql("SELECT id FROM $this->tableName WHERE room_id='$data[room_id]'",'rows');


            if(!empty($rsvp_ids)) {

                $rsvp_ids = array_column($rsvp_ids,'id');
                //$rsvp_ids = implode(',',$rsvp_ids);

                //$rsvp_ids = $this->callsql("SELECT id FROM $this->tableName WHERE id NOT IN($rsvp_ids) AND room_id ='$data[room_id]'",'rows');

                
            }
            //check merged 

            $merge_ids = $this->callsql("SELECT merge_room_id FROM $this->tableName WHERE room_id='$data[room_id]'",'rows');

            if(!empty($merge_ids)) {

                $merge_ids = array_column($merge_ids,'merge_room_id');

            }

            $ids = array_merge($rsvp_ids,$merge_ids);

            if(!empty($ids)) {
                $ids = implode(',',$ids);
                $where .= " AND id IN($ids) "; 
            }



            
            //$merge_ids = 

        }

        if(!empty($data['hour_id'])) {

            $where .= " AND hour_id = '$data[hour_id]' ";

        }
        if(!empty($data['rsvp_type'])) {

            $where .= " AND rsvp_type = '$data[rsvp_type]' ";

        }
        
        $pagecount = ($data['page'] - 1) * $this->perPage;

        
        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT id,room_id,booked_date,rsvp_type,hour_id,created_at,user_id,mummy_name,remarks,status,created_at,merge_room_id,checkin_time,checkout_time,receptionist_no,pr_manager,brought_in_by,male_count,female_count  FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }
        
        $result = ['data' => $this->resultset()];
        $edit_permission   = in_array(70,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $merge_permission  = in_array(77,$this->admin_services) || $this->admin_role == '1' ? true : false;
        $view_permission  = in_array(68,$this->admin_services) || $this->admin_role == '1' ? true : false;


        foreach ($result['data'] as $key => $value) {


            //check merged
            $merge_details = $this->callsql("SELECT room_id FROM $this->tableName WHERE merge_room_id='$value[id]'",'value');
            if(!empty($merge_details)) {

                $value['room_id'] = $merge_details;


            }
            //check have merge

            $have_merge = $this->callsql("SELECT merge_room_id FROM $this->tableName WHERE id ='$value[id]'",'value');
            $merged_customer_names = [];
            if($have_merge) {

                $merged_customer_ids = $this->callsql("SELECT user_id FROM $this->tableName WHERE id ='$have_merge'",'value');
                $merged_customer_names = $this->callsql("SELECT username as customername FROM customer WHERE id IN($merged_customer_ids)",'rows');

            }



        	$customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id IN($value[user_id])",'rows');

           

            $roomDetails = $this->callsql("SELECT room_no,type FROM room WHERE id='$value[room_id]'",'row'); 
            $cancel_edit_possible = true; 
            $booked_date = date('Y-m-d',$value['booked_date']).' '.date('H:i:s',$value['checkout_time']);;
            $hourDetails = [];  

            if(!empty($value['hour_id'])) {

                $hourDetails = $this->callsql("SELECT name,to_time,from_time FROM hour_type WHERE id='$value[hour_id]'",'row');
                
                //$booked_date = strtotime($booked_date.' '.$hourDetails['from_time']);
                //$cancel_edit_possible = time()>$booked_date ? false : true;

            }

            $booked_date = strtotime($booked_date);//+$value['checkin_time'];
            $cancel_edit_possible = time()>$booked_date ? false : true;

            

        	$action = '';
        	if($edit_permission && $cancel_edit_possible) {

                $action .= '<a href="'.BASEURL.'Rsvp/Edit?id='.base64_encode($value['id']).'"><button class="btn btn-primary">Edit</button></a>';

            }   

            if($merge_permission && $cancel_edit_possible) {

                
                $show_merge_button = true; 
                //check this is already merged
                $already_merged = $this->callsql("SELECT id FROM $this->tableName WHERE merge_room_id='$value[id]' AND status='0'",'value');
                $show_merge_button = $already_merged ? false :true;
                
                if($roomDetails['type'] == '1'  && empty($value['merge_room_id']) && $show_merge_button && $value['status']=='0') {

                    $action .= '<a href="'.BASEURL.'Rsvp/MergeRoom?id='.base64_encode($value['id']).'"><button class="btn btn-primary">Merge Room</button></a>';

                }
                if(!empty($value['merge_room_id'])) {

                    $action .= '<a href="'.BASEURL.'Rsvp/MergeRoom?id='.base64_encode($value['id']).'&merge_room_id='.base64_encode($value['merge_room_id']).'"><button class="btn btn-primary">Edit Merge</button></a>';
                    $action .= '<button class="btn btn-primary" onclick="cancelMerge('.$value['id'].')">Cancel Merge</button>';

                }
                
                

            }

            if($view_permission) {

                 $action .= '<button class="btn btn-primary" onclick="viewDetails('.$value['id'].')">View</button>';


            }
            

            

        	$result['data'][$key]['customer_name'] = implode(',',array_column($customer_name,'customername'));
        	$result['data'][$key]['created_at']    = !empty($value['created_at']) ? date('d-m-Y H:i:s',$value['created_at']) : '-';
            if(!$edit_permission ) {

                $result['data'][$key]['status']        = $this->statusArray[$value['status']];

            }
            if($edit_permission ){
                
                $checked = $value['status'] == '0' ? 'checked' : '';

                $result['data'][$key]['status']        = '<label class="switch s-icons s-outline  s-outline-primary  mb-4 mr-2">
                                            
                                            <input type="checkbox" '.$checked.'>
                                          <span class="slider round" onclick="switchStatus('.$value['id'].','.$value['status'].')"></span>
                                                </label>';

            }

            if(!empty($data['export'])) {

                $result['data'][$key]['status']        = $this->statusArray[$value['status']];

            }

            

            $result['data'][$key]['is_room_table'] = $this->typeArray[$roomDetails['type']];
            $result['data'][$key]['room_table_no'] = $this->typeArray[$roomDetails['type']].'-'.$roomDetails['room_no'];
            $result['data'][$key]['booked_date']   = !empty($value['booked_date']) ? date('d-m-Y',($value['booked_date'])) : '-';
            $result['data'][$key]['created_at']    = !empty($value['created_at']) ? date('d-m-Y H:i:s',($value['created_at'])) : '-';
            if(!empty($value['hour_id'])) {

                $result['data'][$key]['hour_name']     = !empty($hourDetails) ? $hourDetails['name'] : '-';
            

            }
            if(empty($value['hour_id'])) {

                $result['data'][$key]['hour_name']     = 'Custom time';
            

            }

           

           

            $result['data'][$key]['from_time']     = !empty($value['checkin_time']) ? date('h:i A',($value['checkin_time'])) : '-';
            $result['data'][$key]['to_time']       = !empty($value['checkout_time']) ? date('h:i A',($value['checkout_time'])) : '-';

                

            

            $result['data'][$key]['rsvp_type']     = $this->rsvptypeArray[$value['rsvp_type']];
        	$result['data'][$key]['action']        = $action;
            $result['data'][$key]['mummy_name']    = implode(',',json_decode($value['mummy_name'],true));


            $result['data'][$key]['receptionist_no']    = !empty($value['receptionist_no']) ? $value['receptionist_no'] : '-';
            $result['data'][$key]['pr_manager']    = !empty($value['pr_manager']) ? $value['pr_manager'] : '-';
            $result['data'][$key]['brought_in_by']    = !empty($value['brought_in_by']) ? $value['brought_in_by'] : '-';
            $result['data'][$key]['male_count']    = !empty($value['male_count']) ? $value['male_count'] : '-';
            $result['data'][$key]['female_count']    = !empty($value['female_count']) ? $value['female_count'] : '-';

            //check merged with any rsvp

            $is_merged = $this->callsql("SELECT id FROM $this->tableName WHERE merge_room_id='$value[id]'",'value');
            $result['data'][$key]['grey_out']    = $is_merged ? true : false ;
            //$result['data'][$key]['grey_out']    = false ;
            $result['data'][$key]['customer_name'] .= !empty($merged_customer_names) ? ','.implode(',',array_column($merged_customer_names,'customername')):'';





        }



     

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;




    }

    

    public function getDetails($id)
    {


        return $this->callsql("SELECT *  FROM $this->tableName WHERE id='$id'",'row');

    }

    public function getcustomername($id)
    {

    	
    	$customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id IN($id)",'rows');

        if(!empty($customer_name)) {

            return implode(',',array_column($customer_name,'customername'));

        }
        return '';

    }

    public function getAdminname($id)
    {

    	return $this->callsql("SELECT username as adminname FROM user WHERE user_id='$id'",'value');

    }

    public function changeStatus($params)
    {

        $id     = $params['id'];
        $status = $params['status'];
        $updated_by = $this->adminID;
        $updated_at = time();
        $sql = "UPDATE $this->tableName SET status='$status',updated_by='$updated_by',updated_at='$updated_at' WHERE id='$id'";
        $this->query($sql);
        $this->execute();

        $activity = "RSVP status changed to ".$this->statusArray[$status].'.Id-'.$id;
        return $this->adminActivityLog($activity);
   
        


    }

    public function getChangeRoomRsvpList()
    {

        $current_date = date('Y-m-d');
        $current_date = strtotime($current_date);
        $sql = "SELECT id,room_id,user_id,hour_id,booked_date,checkin_time,checkout_time FROM $this->tableName WHERE status='0' AND booked_date>='$current_date'";
        $list = $this->callsql($sql,'rows');
        $result = [];
        foreach($list as $key=>$value)
        {
            $room_details = $this->callsql("SELECT id,room_no FROM room WHERE type='1' AND id='$value[room_id]'",'row');
            $customername = $this->getcustomername($value['user_id']);

            $booked_date  = date('Y-m-d',$value['booked_date']);
            //$hour_details = $this->callsql("SELECT from_time,to_time FROM hour_type WHERE id='$value[hour_id]'",'row');

            $booked_date = $booked_date.' '.date('H:i:s',$value['checkout_time']);;
            $booked_date = strtotime($booked_date);//+$value['checkout_time'];
            $merged = $this->checkisMerged($value['id']);       
            if($room_details && time()<$booked_date){



                $result[] = ['rsvp_id'=>$value['id'],'room_id'=>$room_details['id'],'room_no'=>$room_details['room_no'].'-'.$customername];

            }

        }

        return $result;

    }

    public function getChangeRoomList()
    {

        $current_date = date('Y-m-d');
        $current_date = strtotime($current_date);
        $sql = "SELECT id,room_id,user_id,hour_id,booked_date,checkin_time,checkout_time FROM $this->tableName WHERE status='0' AND booked_date>='$current_date' AND merge_room_id='0'";
        $list = $this->callsql($sql,'rows');
        foreach($list as $key=>$value)
        {
            $room_details = $this->callsql("SELECT id,room_no FROM room WHERE type='1' AND id='$value[room_id]'",'row');
            $customername = $this->getcustomername($value['user_id']);

            $booked_date  = date('Y-m-d',$value['booked_date']);
            //$hour_details = $this->callsql("SELECT from_time,to_time FROM hour_type WHERE id='$value[hour_id]'",'row');

            $booked_date = $booked_date.' '.date('H:i:s',$value['checkout_time']);;
            $booked_date = strtotime($booked_date);//+$value['checkout_time'];
            if($room_details && time()<$booked_date){



                $result[] = ['rsvp_id'=>$value['id'],'room_id'=>$room_details['id'],'room_no'=>$room_details['room_no'].'-'.$customername];

            }

        }

        return $result;

    }

    public function getAvailableRoomList($rsvp_id)
    {

        $rsvp_details = $this->getDetails($rsvp_id);

        $checkin_time = !empty($rsvp_details['checkin_time']) ? $rsvp_details['checkin_time']:'';
        $checkout_time = !empty($rsvp_details['checkout_time']) ? $rsvp_details['checkout_time']:'';
        $booked_date  = $rsvp_details['booked_date'];
        $hour_id      = $rsvp_details['hour_id'];
        $sql  = "SELECT id,room_id,user_id,hour_id,booked_date,checkin_time,checkout_time FROM $this->tableName WHERE status='0' AND NOT(`id` <=> '$rsvp_id') AND booked_date='$booked_date' AND checkin_time='$checkin_time' AND checkout_time='$checkout_time'";
    
        $list = $this->callsql($sql,'rows');


        
        $result = [];
        foreach($list as $key=>$value){

            $room_details = $this->callsql("SELECT id,room_no FROM room WHERE type='1' AND id='$value[room_id]'",'row');
            $customername = $this->getcustomername($value['user_id']);
            $booked_date  = date('Y-m-d',$value['booked_date']);
            //$hour_details = $this->callsql("SELECT from_time,to_time FROM hour_type WHERE id='$value[hour_id]'",'row');

            $booked_date = $booked_date.' '.date('H:i:s',$value['checkout_time']);;
            $booked_date = strtotime($booked_date);//+$value['checkout_time'];
            if($room_details){

                $result[] = ['rsvp_id'=>$value['id'],'room_id'=>$room_details['id'],'room_no'=>$room_details['room_no'].'-'.$customername];

            }



        }

        return $result;





    }

    public function getChangeRoomAvailableRoomList($rsvp_id)
    {

        $rsvp_details = $this->getDetails($rsvp_id);
        $booked_date  = $rsvp_details['booked_date'];
        $checkin_time = !empty($rsvp_details['checkin_time']) ? $rsvp_details['checkin_time']:'';
        $checkout_time = !empty($rsvp_details['checkout_time']) ? $rsvp_details['checkout_time']:'';
        $hour_id      = $rsvp_details['hour_id'];
        $sql  = "SELECT id,room_id,user_id,hour_id,booked_date,checkin_time,checkout_time FROM $this->tableName WHERE status='0' AND NOT(`id` <=> '$rsvp_id') AND booked_date='$booked_date' AND checkin_time='$checkin_time' AND checkout_time='$checkout_time'";
    
        $list = $this->callsql($sql,'rows');
        
        $result   = [];
        $room_ids = [];
        foreach($list as $key=>$value){

            $room_details = $this->callsql("SELECT id,room_no FROM room WHERE type='1' AND id='$value[room_id]'",'row');
            $customername = $this->getcustomername($value['user_id']);
            $booked_date  = date('Y-m-d',$value['booked_date']);
            //$hour_details = $this->callsql("SELECT from_time,to_time FROM hour_type WHERE id='$value[hour_id]'",'row');

            $booked_date = $booked_date.' '.date('H:i:s',$value['checkout_time']);;
            $booked_date = strtotime($booked_date);//+$value['checkout_time'];
            if($room_details){

                array_push($room_ids,$room_details['id']);
                $result[] = ['rsvp_id'=>$value['id'],'room_id'=>$room_details['id'],'room_no'=>$room_details['room_no'].'-'.$customername];

            }

        }

        $where = " WHERE type='1' AND status='0' ";
        array_push($room_ids,$rsvp_details['room_id']);
        if(!empty($room_ids)) {

            $room_ids = implode(',',$room_ids);
            $where .= " AND id NOT IN(".$room_ids.")";

        }


        //get all rfree rooms list
        $extra_room_list = [];
        $free_room_list = $this->callsql("SELECT id,room_no FROM room $where",'rows');
        foreach($free_room_list as $key=>$value){

            //check room used in same time 
            $rsvp_details = $this->callsql("SELECT id FROM $this->tableName WHERE room_id='$value[id]' AND hour_id='$hour_id' AND booked_date='$booked_date'",'rows');
            if(empty($rsvp_details)){

                $result[] = ['rsvp_id'=>'','room_id'=>$value['id'],'room_no'=>$value['room_no']];

            }


        }

     


        return $result;

    }

    public function checkalreadyMerged($params)
    {

        $merge_id = $params['merge_id'];
        $rsvp_id  = $params['rsvp_id'];
        $sql = "SELECT id FROM $this->tableName WHERE merge_room_id='$merge_id' AND status='0' AND id!='$rsvp_id'";
        $count = $this->callsql($sql,'value');
        if(!empty($count)) {

            return true;

        }

        return false;
    }

    public function mergeRoom($params)
    {

        $id         = $params['id'];
        $merge_id   = $params['merge_id'];
        $updated_by = $this->adminID;
        $updated_at = time();
        $sql = "UPDATE $this->tableName SET merge_room_id='$merge_id',updated_at='$updated_at',updated_by='$updated_by' WHERE id='$id'";
        $this->query($sql);
        $this->execute();

        $rsvp_details     = $this->getDetails($id);
        $rsvp_room_name   = $this->callsql("SELECT room_no FROM room WHERE id='$rsvp_details[room_id]'",'value');

        $merge_details    = $this->getDetails($merge_id); 
        $merge_room_name  = $this->callsql("SELECT room_no FROM room WHERE id='$merge_details[room_id]'",'value'); 

        //add history 

        $historyParams = [];
        $historyParams['old_room_id']   = $rsvp_details['room_id'];
        $historyParams['new_room_id']   = $merge_details['room_id'];
        $historyParams['rsvp_id']       = $id;
        $historyParams['merge_rsvp_id'] = $merge_id;
        $historyParams['type']          = '2';
        $historyParams['status']        = '0';
        (new RsvpHistory)->addHistory($historyParams);
        
        $activity = "Merged Room ".$rsvp_room_name .' with '.$merge_room_name.' .Rsvp id-'.$id.'.Merge id-'.$merge_id;
        return $this->adminActivityLog($activity);


    }

    public function cancelMerge($params)
    {

        $id         = $params['id'];
        $details    = $this->getDetails($id);
        $updated_at = time();
        $updated_by = $this->adminID;
        $sql = "UPDATE $this->tableName SET merge_room_id='0',updated_by='$updated_by',updated_at='$updated_at' WHERE id='$id'";
        $this->query($sql);
        $this->execute();


        //update history


        $sql= "UPDATE rsvp_history SET status='1',updated_at='$updated_at',updated_by='$this->adminID',user_type='1' WHERE rsvp_id='$id' AND type='2' AND status='0' ORDER BY id DESC LIMIT 1";
        $this->query($sql);
        $this->execute();

        $activity = "Admin cancelled the merge from id-".$id.',Merge id-'.$details['merge_room_id'];
        return $this->adminActivityLog($activity);


    }

    public function removeMerge($params)
    {

        $id = $params['id'];
        $updated_at = time();
        $sql = "UPDATE $this->tableName SET merge_room_id='0' WHERE id='$id'";
        $this->query($sql);
        $this->execute();

        $sql = "UPDATE $this->tableName SET merge_room_id='0' WHERE merge_room_id='$id'";
        $this->query($sql);
        $this->execute();


        //update history


        $sql= "UPDATE rsvp_history SET status='1',updated_at='$updated_at',updated_by='$this->adminID',user_type='1' WHERE rsvp_id='$id' AND type='2' AND status='0' ORDER BY id DESC LIMIT 1";
        $this->query($sql);
        $this->execute();

        

        $activity = "Removed merge because of RSVP update.Id-".$id;
        return $this->adminActivityLog($activity);

    }

    public function getRsvpDetails($id,$merged_details=false)
    {


        $details = $this->getDetails($id);
       
        $details['customer_name'] = $this->getcustomername($details['user_id']);
        $details['booked_date'] = !empty($details['booked_date']) ? date('d-m-Y',$details['booked_date']) : '-';

        if($details['hour_id']) {

            $hour_details = $this->callsql("SELECT name,from_time,to_time FROM hour_type WHERE id='$details[hour_id]'",'row');
            $details['hour_name']  = $hour_details['name'];
           
            
       

        }

        if(empty($details['hour_id'])) {

            $details['hour_name']  = 'Custom Time';

            

            
        }


        $hour_time             = date('h:i A',($details['checkin_time'])).'-'.date('h:i A',($details['checkout_time']));
        
        
        $room_details = $this->callsql("SELECT room_no,type FROM room WHERE id='$details[room_id]'",'row');
        $details['hour_time']  = $hour_time; 
        $details['room_no']    = $this->typeArray[$room_details['type']].'-'.$room_details['room_no'];
        $details['rsvp_type']  = $this->rsvptypeArray[$details['rsvp_type']];
        $details['mummy_name'] = implode(',',json_decode($details['mummy_name'],true));
        $details['merge_room'] = '-';


        $details['room_type'] = $room_details['type'];
        $contact_no = $this->callsql("SELECT CONCAT(mobile_country_code,'',mobile) as contact_no FROM customer_extra WHERE user_id IN(".$details['user_id'].")",'rows');
        $contact_no = array_column($contact_no, 'contact_no');
        $contact_no = implode(',', $contact_no);
        $details['contact_no'] = $contact_no;

        if($details['merge_room_id']){

            $merged_rsvp_details   = $this->getDetails($details['merge_room_id']);
            $merged_room_details   = $this->callsql("SELECT room_no,type FROM room WHERE id='$merged_rsvp_details[room_id]'",'row');
            $details['merge_room'] = $this->typeArray[$room_details['type']].'-'.$merged_room_details['room_no'];

            if($merged_details) {

                $merge_user_id = $merged_rsvp_details['user_id'];
                $merge_user_id_usernames  = $this->getcustomername($merge_user_id);
                $details['customer_name'] .= ','.$merge_user_id_usernames;

                $contact_no = $this->callsql("SELECT CONCAT(mobile_country_code,'',mobile) as contact_no FROM customer_extra WHERE user_id IN(".$merge_user_id.")",'rows');
                $contact_no = array_column($contact_no, 'contact_no');
                $contact_no = implode(',', $contact_no);
                $details['contact_no'] .= ','.$contact_no;

            }

            

        

        }


       

        
        return $details;



    }

    public function checkisMerged($id)
    {

        $merged = false;
        $sql = "SELECT id FROM $this->tableName WHERE merge_room_id='$id'";
        $merge_room_id = $this->callsql($sql,'value');
        if(!empty($merge_room_id)) {

            $merged = true;

        }
        $merge_room_id = '';


        $sql = "SELECT merge_room_id FROM $this->tableName WHERE id='$id'";
        $merge_room_id = $this->callsql($sql,'value');
        if(!empty($merge_room_id)) {

            $merged = true;

        }

        return $merged;

    }

    

    public function changeRoom($params)
    {
        

        $id      = $params['id'];
        $details = $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');
        $room_id = $params['room_id'];
        $user_id = $params['user_id'];
        $hour_id = $params['hour_id'];
        $checkin_time = !empty($params['checkin_time']) ? $params['checkin_time'] : $details['checkin_time'];
        $checkout_time = !empty($params['checkout_time']) ? $params['checkout_time'] : $details['checkout_time'];
        $updated_at = time();
        $updated_by = $this->adminID;
        $activity = $params['activity'];
        $sql = "UPDATE $this->tableName SET room_id='$room_id',user_id='$user_id',updated_at='$updated_at',updated_by='$updated_by',hour_id='$hour_id',checkin_time='$checkin_time',checkout_time='$checkout_time' WHERE id='$id'";
        $this->query($sql);
        $this->execute();
        return $this->adminActivityLog($activity);



    }


    

    
    public function adminActivityLog($activity){

        $time=time(); $ip=$_SERVER['REMOTE_ADDR']; $admin_id=$this->adminID;
        $stmt= "INSERT INTO admin_activity_log SET admin_id ='$admin_id' , action ='$activity' , createtime= '$time' , createip='$ip' ";

        $this->query($stmt);
        return $this->execute();
        
    }
     
     

    

    

    
}
