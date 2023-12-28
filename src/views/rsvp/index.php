<?php 
use inc\Root;
$this->mainTitle = 'Rsvp';
$this->subTitle  = 'Rsvp List';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$this->statusArray = ['0'=>'Booked','1'=>'Cancelled'];
$this->rsvptypeArray = ['1'=>'Walk in','2'=>'Online'];

$create_permission  = in_array(69,$this->admin_services) || $this->admin_role == '1' ? true : false;


?>
<style type="text/css">
  
  .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

  .div_float{
     float: right;
     margin-right: 10px;
  }

@media(max-width:767px){
    .div_float {
        
        padding: 5px;
        margin-right: 0px;
    }
    .full_width{
     width: 100%;
   }
} 
.form-control{
       height: 42px;
}
.select{
  padding-top: 9px;
}
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>rsvp/Index">

                              <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($customer_name)):?>
                                          <option value="<?=$user_id?>" selected><?=$customer_name?></option>
                                        <?php endif;?>
                                    </select>

                                     
                                  </div>
                                    
                                

                                <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="from_booked_date">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="to_booked_date" value="" placeholder="To Date">
                                  </div>     

                                 <div class="form-group col-sm-2">
                                    <select type="option" name="room_id" class="form-control select" id="room_id">
                                        <option value="">Room/Table List</option>
                                            <?php
                                                foreach ($roomList as $key => $value) {
                                                  echo '<option value="'.$value['id'].'">'.$value['room_no'].'</option>';
                                                }
                                            ?>
                                    </select>
                                </div> 

                                <div class="form-group col-sm-2">
                                    <select type="option" name="hour_id" class="form-control select" id="hour_id">
                                        <option value="">Hour Type</option>
                                            <?php
                                                foreach ($hourList as $key => $value) {
                                                  echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                                }
                                            ?>
                                    </select>
                                </div> 
                                <div class="form-group col-sm-2">
                                    <select type="option" name="rsvp_type" class="form-control select" id="rsvp_type">
                                        <option value="">Rsvp Type</option>
                                            <?php
                                                foreach ($this->rsvptypeArray as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                    </select>  
                                </div>                           
                                <div class="form-group col-sm-2">
                                    <select type="option" name="status" class="form-control select" id="status">
                                        <option value="">Status</option>
                                            <?php
                                                foreach ($this->statusArry as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                    </select>

                                  </div>  
                                  <div class="col-md-5 col-lg-5">
                               
                                <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="" style="color:#fff;cursor: pointer;">Export</a> </span>
                                      
                            </div>

                                <div class="form-group col-sm-2">

                                  <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="Search">



                                </div>
                                <?php if($create_permission){?>
                                <div class="form-group col-sm-3">
                                       <a href="<?=BASEURL?>Rsvp/Create/" class="btn btn-outline-info addbtn mb-2">
                                        Create Rsvp
                                      
                                       </a>
                                </div>
                                <?php }?>


                             
                            </form>
                        </div>

                        <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                      
                            </div>

                            
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                      <th class="sorting_disabled" rowspan="1" colspan="1" >Customer Name</th>
                                      
                                      <!-- <th class="sorting_disabled" rowspan="1" colspan="1">Room/Table</th> -->
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Room No/Table No</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Booking date</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Hour</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Checkin Time</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Checkout Time</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Rsvp Type</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Receptionist No</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">PR Manager</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Brought In By</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Male Count</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Female Count</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Remark</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Mummy Name</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Created Time</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  if(!empty($data['data'])){

                                     foreach($data['data'] as $key => $val){ 
                                        
                                        $style = $val['grey_out'] ? 'style="color:#ddd9d9;"':'';

                                      ?>                                

                                       <tr role="row" class="odd">
                                           <td <?= $style ?>><?=$val['customer_name']?></td>
                                           
                                           <!-- <td><?=$val['is_room_table']?></td> -->
                                           <td <?= $style ?>><?=$val['room_table_no']?></td>
                                           <td <?= $style ?>><?=$val['booked_date']?></td>
                                           <td <?= $style ?>><?=$val['hour_name']?></td>
                                           <td <?= $style ?>><?=$val['from_time']?></td> 
                                           <td <?= $style ?>><?=$val['to_time']?></td> 
                                           <td <?= $style ?>><?=$val['rsvp_type']?></td> 
                                           <td <?= $style ?>><?=$val['receptionist_no']?></td> 
                                           <td <?= $style ?>><?=$val['pr_manager']?></td> 
                                           <td <?= $style ?>><?=$val['brought_in_by']?></td> 
                                           <td <?= $style ?>><?=$val['male_count']?></td> 
                                           <td <?= $style ?>><?=$val['female_count']?></td> 
                                           <td <?= $style ?>><?=$val['remarks']?></td> 
                                           <td <?= $style ?>><?=$val['mummy_name']?></td> 
                                           <td <?= $style ?>><?=$val['status'];?></td>
                                           <td <?= $style ?>><?=$val['created_at'];?></td>
                                           <td <?= $style ?>><?=$val['action'];?></td>
                                                           
                                       </tr>

                              <?php }
                                  }else{
                                     echo '<tr><td colspan="17" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>rsvp/Index">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Details</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal('viewModal')" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" id="viewModalbody">
                

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="closemodal('viewModal')">Close</button>
            </div>
          
        </div>
    </div>
</div>
<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<style>
table {
  display: block;
  overflow-x: auto;
  white-space: nowrap;
}
.greyout{
  color:red;
}




</style>
<script>


var status       = '<?=$status?>';
var datefrom     = '<?=$from_booked_date?>';
var dateto       = '<?=$to_booked_date?>';
var customer_id  = '<?=$user_id?>';
var hour_id      = '<?=$hour_id?>';
var room_id      = '<?=$room_id?>';
var rsvp_type    = '<?=$rsvp_type?>';

 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#RsvpMenu').attr("data-active","true");
    $('#RsvpNav').addClass('show');
    $('#RsvpList').addClass('active');

    // $('#userID').val(userID);
   
    $('#status').val(status);
    $('#user_id').val(customer_id);
    $('#datefrom').val(datefrom);
    $('#dateto').val(dateto);
    $('#hour_id').val(hour_id);
    $('#room_id').val(room_id);
    $('#rsvp_type').val(rsvp_type);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});

$('#user_id').select2({
    placeholder: 'Select Customer',
    tags: false,
    minimumInputLength:1,
    ajax: {
      url: '<?=BASEURL?>LeaveRequest/GetUsers',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        //var userType = $('#userType').val();
        return {
            term: params.term, // search term
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
});



function pageHistory(status,customer_id,datefrom,dateto,room_id,hour_id,rsvp_type,page){

 
    
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+customer_id+'" style="display:none;">');
    $('.pagination').append('<input name="from_booked_date" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="to_booked_date" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="room_id" value="'+room_id+'" style="display:none;">');
    $('.pagination').append('<input name="hour_id" value="'+hour_id+'" style="display:none;">');
    $('.pagination').append('<input name="rsvp_type" value="'+rsvp_type+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){

    pageHistory(status,customer_id,datefrom,dateto,room_id,hour_id,rsvp_type,$("#pageJump").val());

 });


function switchStatus(id,current_status)
{
     
    var new_status = current_status == '0' ? '1' : '0';
    var btn = "Warning";
    var txt = "Are you sure want to change?";
    swal({
        title: btn,
        text: txt,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        padding: '2em'
    }).then(function(result) {
        if (result.value) {
            loadingoverlay('info',"Please wait..","loading...");
            $.post('<?=BASEURL;?>Rsvp/ChangeStatus',{'id':id,'status':new_status},function(response){

                  hideoverlay();


                  newResp = JSON.parse(response);
                  if (newResp['status'] == 'success') {
                      openSuccess(newResp['response']);
                  } else {
                      loadingoverlay("error", "Error", newResp['response']);
                  }
              });
              return false;
        }else{
            location.reload();
        }
  })

  return false;            
    
   

}
$('#pageJump').val("<?=$data['curPage'];?>");
function exportReport(){
    loadingoverlay('info',"Loading","Please Wait");
    $.post('<?=BASEURL?>Rsvp/Export',{'status':status,'user_id':customer_id,'from_booked_date':datefrom,'to_booked_date':dateto,'room_id':room_id,'hour_id':hour_id,'rsvp_type':rsvp_type},
            function(response){
                hideoverlay();
        newResp = JSON.parse(response); 
        if(newResp['status'] == 'success')
        {        
            $('#downfile').html(newResp['response']);
            $('#downloadcsv').click();

        }else{
            loadingoverlay("error","Error","Try Again>");
        }

    });
    return false;
        
}


function cancelMerge(id)
{

    
    var btn = "Warning";
    var txt = "Are you sure want to cancel?";
    swal({
        title: btn,
        text: txt,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        padding: '2em'
    }).then(function(result) {
        if (result.value) {
            loadingoverlay('info',"Please wait..","loading...");
            $.post('<?=BASEURL;?>Rsvp/CancelMerge',{'id':id},function(response){

                  hideoverlay();


                  newResp = JSON.parse(response);
                  if (newResp['status'] == 'success') {
                      openSuccess(newResp['response']);
                  } else {
                      loadingoverlay("error", "Error", newResp['response']);
                  }
              });
              return false;
        }else{
            location.reload();
        }
  })

  return false;     

}

function viewDetails(id)
{

     $('#viewModal').modal('toggle');
     $('#viewModalbody').load('<?=BASEURL?>Rsvp/loadDetails?id='+id);


}
function closemodal(modalid){
     
    $('#'+modalid).modal('toggle');
}








</script>
