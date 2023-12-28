<?php

namespace src\models;

use src\lib\Database;
use inc\Root;
use src\lib\Router;
use src\models\CommonModal;

class RsvpHistory extends Database {

    /**
     * Constructor of the model
     */
     public function __construct($db = 'db') {
        parent::__construct(Root::db());
        $this->tableName = "rsvp_history";
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

    public function addHistory($params)
    {

        $old_room_id   = !empty($params['old_room_id']) ? $params['old_room_id'] : '';
        $new_room_id   = !empty($params['new_room_id']) ? $params['new_room_id'] : '';
        $rsvp_id       = !empty($params['rsvp_id']) ? $params['rsvp_id'] : '';
        $merge_rsvp_id = !empty($params['merge_rsvp_id']) ? $params['merge_rsvp_id'] : '';
        $type          = !empty($params['type']) ? $params['type'] : '';
        $updated_by    = $this->adminID;
        $created_at    = time();
        $updated_at    = time();
        $status        = $params['status'];

        $sql = "INSERT INTO $this->tableName SET old_room_id='$old_room_id',new_room_id='$new_room_id',rsvp_id='$rsvp_id',merge_rsvp_id='$merge_rsvp_id',type='$type',updated_by='$updated_by',created_at='$created_at',updated_at='$updated_at',status='$status',user_type='1'";
        $this->query($sql);
        $this->execute();

    }

    public function getChangeHistory($data)
    {

        $where = " WHERE id!='0' AND status='0' AND type='1'";
        $rsvp_ids = [];
        $rsvp = [];
        $rsvpbooked = [];
        if(!empty($data['user_id'])) {

            $rsvp = $this->callsql("SELECT id FROM rsvp WHERE FIND_IN_SET (".$data['user_id'].",user_id) ",'rows');

            if(!empty($rsvp)) {

                $rsvp = array_column($rsvp,'id');
                //$rsvp = implode(',',$rsvp);
                //$where    .= " AND rsvp_id IN($rsvp_ids)";

            }


        }

        if(!empty($data['from_booked_date']) && !empty($data['to_booked_date'])) {

            $data['from_booked_date'] = date('d-m-Y',strtotime($data['from_booked_date']));

            $from_booked_date = strtotime($data['from_booked_date']);

            $data['to_booked_date'] = date('d-m-Y',strtotime($data['to_booked_date']));
            $to_booked_date = strtotime($data['to_booked_date']);

            $rsvpbooked = $this->callsql("SELECT id FROM rsvp WHERE booked_date BETWEEN '$from_booked_date' AND '$to_booked_date' ",'rows');



            if(!empty($rsvpbooked)) {

                $rsvpbooked = array_column($rsvpbooked,'id');
                //$rsvpbooked = implode(',',$rsvpbooked);
                //$where    .= " AND rsvp_id IN($rsvp_ids)";

            }


        }

        $rsvp_ids = array_merge($rsvp,$rsvpbooked);

        if(!empty($data['user_id']) || (!empty($data['from_booked_date']) && !empty($data['to_booked_date']))) {

            if(!empty($rsvp_ids)) {
                $rsvp_ids = implode(',',$rsvp_ids);
                $where    .= " AND rsvp_id IN($rsvp_ids)";

            }else{

                $result['data'] = array();
                $result['count']   = '0';
                $result['curPage'] = $data['page'];
                $result['perPage'] = $this->perPage;
                return $result;

            }

        }

      

       



        $pagecount = ($data['page'] - 1) * $this->perPage;

        
        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT id,old_room_id,new_room_id,rsvp_id,merge_rsvp_id,type,updated_by,user_type,created_at,updated_at,status  FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }

        $result = ['data' => $this->resultset()];

        foreach ($result['data'] as $key => $value) {

            $rsvpDetails = $this->callsql("SELECT id,booked_date,checkin_time,checkout_time,hour_id FROM rsvp WHERE id='$value[rsvp_id]'",'row');

            
            $user_ids = $this->callsql("SELECT user_id FROM rsvp WHERE id='$value[rsvp_id]'",'value');
            $customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id IN($user_ids)",'rows');

            $roomDetails = $this->callsql("SELECT room_no,type FROM room WHERE id='$value[old_room_id]'",'row'); 
            $newroomDetails = $this->callsql("SELECT room_no,type FROM room WHERE id='$value[new_room_id]'",'row');
            $hourDetails = $this->callsql("SELECT name FROM hour_type WHERE id='$rsvpDetails[hour_id]'",'row');
           

            $result['data'][$key]['customer_name']   = implode(',',array_column($customer_name,'customername'));
            $result['data'][$key]['old_room']        = $this->typeArray[$roomDetails['type']].'-'.$roomDetails['room_no'];

            $result['data'][$key]['new_room']        = $this->typeArray[$newroomDetails['type']].'-'.$newroomDetails['room_no'];
            $result['data'][$key]['booked_date']     = date('d-m-Y',$rsvpDetails['booked_date']); 
            $result['data'][$key]['checkin_time']    = date('h:i A',$rsvpDetails['checkin_time']); 
            $result['data'][$key]['checkout_time']   = date('h:i A',$rsvpDetails['checkout_time']); 
            $result['data'][$key]['hour_name']       = !empty($hourDetails) ? $hourDetails['name'] : '-';
            $result['data'][$key]['updated_at']      = !empty($value['updated_at']) ? date('d-m-Y',$value['updated_at']):'-';
            if($value['user_type'] == '1') {
                
                $updated_by = $this->callsql("SELECT username FROM admin WHERE id='$value[updated_by]'",'value');
               
            }
            if($value['user_type'] == '1') {
                
                $updated_by = $this->callsql("SELECT username FROM user WHERE user_id='$value[updated_by]'",'value');
               
            }

            $result['data'][$key]['updated_by']      = $updated_by;

           

        }

        if($count==0){
            $result['data'] = array();
        }
        $result['count']   = $count;
        $result['curPage'] = $data['page'];
        $result['perPage'] = $this->perPage;
        return $result;


    }

     public function getmergeHistory($data)
    {

        $where = " WHERE id!='0' AND status='0' AND type='2'";
        $rsvp_ids = [];
        $rsvp = [];
        $rsvpbooked = [];
        if(!empty($data['user_id'])) {

            $rsvp = $this->callsql("SELECT id FROM rsvp WHERE FIND_IN_SET (".$data['user_id'].",user_id) ",'rows');

            if(!empty($rsvp)) {

                $rsvp = array_column($rsvp,'id');
                //$rsvp = implode(',',$rsvp);
                //$where    .= " AND rsvp_id IN($rsvp_ids)";

            }


        }

        if(!empty($data['from_booked_date']) && !empty($data['to_booked_date'])) {

            $data['from_booked_date'] = date('d-m-Y',strtotime($data['from_booked_date']));

            $from_booked_date = strtotime($data['from_booked_date']);

            $data['to_booked_date'] = date('d-m-Y',strtotime($data['to_booked_date']));
            $to_booked_date = strtotime($data['to_booked_date']);

            $rsvpbooked = $this->callsql("SELECT id FROM rsvp WHERE booked_date BETWEEN '$from_booked_date' AND '$to_booked_date' ",'rows');



            if(!empty($rsvpbooked)) {

                $rsvpbooked = array_column($rsvpbooked,'id');
                //$rsvpbooked = implode(',',$rsvpbooked);
                //$where    .= " AND rsvp_id IN($rsvp_ids)";

            }


        }

        $rsvp_ids = array_merge($rsvp,$rsvpbooked);

        if(!empty($data['user_id']) || (!empty($data['from_booked_date']) && !empty($data['to_booked_date']))) {

            if(!empty($rsvp_ids)) {
                $rsvp_ids = implode(',',$rsvp_ids);
                $where    .= " AND rsvp_id IN($rsvp_ids)";

            }else{

                $result['data'] = array();
                $result['count']   = '0';
                $result['curPage'] = $data['page'];
                $result['perPage'] = $this->perPage;
                return $result;

            }

        }

       



        $pagecount = ($data['page'] - 1) * $this->perPage;

        
        $count  = $this->callsql("SELECT count(id) FROM $this->tableName $where",'value');
        if(!empty($data['export'])){
            $this->query("SELECT * FROM $this->tableName $where  ORDER BY id DESC ");
        }else{
            $this->query("SELECT id,old_room_id,new_room_id,rsvp_id,merge_rsvp_id,type,updated_by,user_type,created_at,updated_at,status  FROM $this->tableName $where  ORDER BY id DESC LIMIT $pagecount,$this->perPage");
        }

        $result = ['data' => $this->resultset()];

        foreach ($result['data'] as $key => $value) {

            $rsvpDetails = $this->callsql("SELECT id,booked_date,checkin_time,checkout_time,hour_id FROM rsvp WHERE id='$value[rsvp_id]'",'row');

            
            $user_ids = $this->callsql("SELECT user_id FROM rsvp WHERE id='$value[rsvp_id]'",'value');
            $customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id IN($user_ids)",'rows');

            $roomDetails = $this->callsql("SELECT room_no,type FROM room WHERE id='$value[old_room_id]'",'row'); 
            $newroomDetails = $this->callsql("SELECT room_no,type FROM room WHERE id='$value[new_room_id]'",'row');
            $hourDetails = $this->callsql("SELECT name FROM hour_type WHERE id='$rsvpDetails[hour_id]'",'row');
           

            $result['data'][$key]['customer_name']   = implode(',',array_column($customer_name,'customername'));
            $result['data'][$key]['old_room']        = $this->typeArray[$roomDetails['type']].'-'.$roomDetails['room_no'];

            $result['data'][$key]['new_room']        = $this->typeArray[$newroomDetails['type']].'-'.$newroomDetails['room_no'];
            $result['data'][$key]['booked_date']     = date('d-m-Y',$rsvpDetails['booked_date']); 
            $result['data'][$key]['checkin_time']    = date('h:i A',$rsvpDetails['checkin_time']); 
            $result['data'][$key]['checkout_time']   = date('h:i A',$rsvpDetails['checkout_time']); 
            $result['data'][$key]['hour_name']       = !empty($hourDetails) ? $hourDetails['name'] : '-';

            $merged_customer_name = [];

            $merged_rsvp_details = $this->callsql("SELECT user_id FROM rsvp WHERE id='$value[merge_rsvp_id]'",'row');
            $merged_customer_name = $this->callsql("SELECT username as customername FROM customer WHERE id IN($merged_rsvp_details[user_id])",'rows');
            $result['data'][$key]['customer_name'] .= !empty($merged_customer_name) ? ','.implode(',',array_column($merged_customer_name,'customername')):'';
            $result['data'][$key]['updated_at']      = !empty($value['updated_at']) ? date('d-m-Y',$value['updated_at']):'-';
            if($value['user_type'] == '1') {
                
                $updated_by = $this->callsql("SELECT username FROM admin WHERE id='$value[updated_by]'",'value');
               
            }
            if($value['user_type'] == '1') {
                
                $updated_by = $this->callsql("SELECT username FROM user WHERE user_id='$value[updated_by]'",'value');
               
            }

            $result['data'][$key]['updated_by']      = $updated_by;



           

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

        return $this->callsql("SELECT * FROM $this->tableName WHERE id='$id'",'row');

    }



}