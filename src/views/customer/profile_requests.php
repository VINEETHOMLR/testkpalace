<?php 
use inc\Root;
$this->mainTitle = 'Customer Management';
$statusArr = ['0'=>'Requested','1'=>'Approved','2'=>'Rejected'];

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

.modal-dialog {
    max-width: 37%;
}
.custom-modal .close-icon {
  position: absolute;
  right: 20px;
  top: 20px;
  border: none;
  padding: 0;
  background: transparent;
  line-height: 14px;
}

select{
  height:50px;
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
                        <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Customer/CustomerProfileRequest">
                            <div class="row col-md-12 col-xs-12">
                                <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="uniqueid" name="uniqueid" placeholder="Enter UserID">
                                </div> -->
                                <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="requested_by" name="requested_by" placeholder="Enter Requested By">
                                </div> -->

                                <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="email" name="email" placeholder="Enter email">
                                </div> -->

                                <!-- <div class="form-group col-sm-2">
                                    <input type="text"  class="form-control select" id="mobile_no" name="mobile_no" placeholder="Enter Mobile No">
                                </div> -->



                                <div class="form-group col-sm-3">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id" style="height:50px;">
                                        <option value="">Select Customer</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-3">
                                    <select type="option" name="type" class="form-control" id="type" style="height:50px;">
                                        <option value="">Select Type</option>
                                        <option value='1' <?= $type=='1' ? 'selected':''?>>Email Update</option>
                                        <option value='2' <?= $type=='2' ? 'selected':''?>>Mobile Update</option>
                                        <option value='3' <?= $type=='3' ? 'selected':''?>>Gender Update</option>

                                        <option value='4' <?= $type=='4' ? 'selected':''?>>Surname Update</option>
                                        <option value='5' <?= $type=='5' ? 'selected':''?>>DOB Update</option>
                                        <option value='6' <?= $type=='6' ? 'selected':''?>>Username Update</option>

                                        
                                    </select>
                                </div>
                                
                                <div class="form-group col-sm-2">
                                    <select type="option" name="status" style="height:50px;width: 163px;" class="form-control select" id="status">
                                        <option value="">Select Status</option>
                                        <?php
                                            foreach ($statusArr as $key => $value) {
                                              echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-sm-2">
                                    <input type="submit" class="btn btn-success col-md-12 col-lg-12 div_float" id="search" name="Search" value="Search">
                                </div>
                        </div>

                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-7 col-lg-7">
                            </div>
                        </div>
                    </form>
                    </div>

                    <div  class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div  class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width:100%; " role="grid" aria-describedby="dt_info">
                                <thead >
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >Sl.No</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Requested Data</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Requested By</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Requested Time</th>

                                  <th class="sorting_disabled" rowspan="1" colspan="1">Approved/Rejected By</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Approved/Rejected Time</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                </thead>
                                <tbody>
                                <?php
                                //0- requested 1 - approved 2 - rejected
                                $i=1;
                                $btn = 'text-primary';
                                if(!empty($data['data'])){
                                    foreach($data['data'] as $key => $val): ?>
                                        <?php if($val['status'] =='0'){
                                            $btn = 'text-primary' ;
                                        }
                                        if($val['status'] =='1'){
                                            $btn = 'text-success' ;
                                        }
                                        if($val['status'] =='2'){
                                            $btn = 'text-danger';
                                        }
                                        $checked = (empty($val['status'])) ? 'checked' : '';  ?>

                                       <tr role="row" class="odd">
                                           <td><?=$i?></td>
                                           <td><?=$val['customer_uid'];?></td>

                                           <td><button class="badge outline-badge-primary yellow-btn ml-15 customer_details" type="button" data-toggle="modal" data-target="#customerDetailsmodal"  data-cid="<?=$val['id']?>">Requested Data</button></td>
                                           <td><?=$val['type'];?></td>
                                           <td><?=$val['requested_by'];?></td>
                                           <td><?=date("d-m-Y H:i:s",$val['request_time'])?></td>
                                           <td><?=$val['updated_by'];?></td>
                                           <td><?= $val['updated_time']?></td>
                                           <td><span class="<?=$btn?> yellow-btn ml-15" type="button"><?=$statusArr[$val['status']]?></span></td>

                                       </tr>
                                    <?php $i++; endforeach;
                                }else{
                                    echo '<tr><td colspan="17" class="text-center">No Data Found</td></tr>';
                                }   
                              ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Customer/CustomerProfileRequest">
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

<div class="modal fade custom-modal table-in-modal" id="customerDetailsmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close-icon" data-dismiss="modal" aria-label="Close">
                    <img src="<?=WEB_PATH?>employer_asset/img/material-close.svg" alt="" />
                </button>
                <div class="modal-heading mb-30"><h3>Customer Details</h3></div>
                 
                <div class="custom-table-scroll">
                    <div class="modal-body customer-tableData" id="customer-tableData"></div>
                </div>
                <form class="col-md-12" id="recruitment_pagination" >
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                        </ul>
                    </nav>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4 table-responsive m-t-40" id="downfile" style="display:none;"></div>
<script>

$('#accordionExample').find('li a').attr("data-active","false");
$('#custMenu').attr("data-active","true");
$('#custNav').addClass('show');
$('#customerProfileRequest').addClass('active');

//$(".customer_details").on('click', function(event) {

//$(document).on('click','.customer_details', function(e){

$('.customer_details').click(function(){
    id = $(this).data('cid');
    getCustomerDetails(id);
})


var user_id = '<?= $user_id ?>';
var type    = '<?= $type ?>';


function getCustomerDetails(uid){
    data = new FormData();
    data.append('id', uid);
    $.ajax({
          url: '<?=BASEURL;?>Customer/getCustomerDetails/', 
          dataType: 'text',  
          cache: false,
          contentType: false,
          processData: false,
          data: data,                         
          type: 'post',
          success: function(response){ 
            //alert(response); return false;
            //var newResp = JSON.parse(response);
            $('.customer-tableData').html("");
            $('.customer-tableData').html(response);
            //scrollDown();
        }
    });
}

function approveRequest(cid){
    var remarks = $("#remarks").val();


    swal({
          title:'Are you sure?',
          text: "You want to Approve this customer !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          padding: '2em'
    }).then(function(result) {
        if (result.value) {
           loadingoverlay('info',"Please wait..","loading...");
           $.post('<?= BASEURL ?>Customer/ApproveCustomerProfile/',{cid:cid, remarks:remarks},function(response){ 

                newResp = JSON.parse(response);
                if(newResp['status'] == 'success'){
                    openSuccess(newResp['response'])
                }else{ 
                    loadingoverlay('error','error',newResp['response']);
                }
            });
            return false;
        }
    })

}


function rejectRequest(cid){
    var remarks = $("#remarks").val();

    swal({
          title:'Are you sure?',
          text: "You want to Reject this customer !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          padding: '2em'
    }).then(function(result) {
        if (result.value) {
           loadingoverlay('info',"Please wait..","loading...");
           $.post('<?= BASEURL ?>Customer/RejectCustomerProfile/',{cid:cid, remarks:remarks},function(response){ 

                newResp = JSON.parse(response);
                if(newResp['status'] == 'success'){
                    openSuccess(newResp['response'])
                }else{ 
                    loadingoverlay('error','error',newResp['response']);
                }
            });
            return false;
        }
    })

}

function pageHistory(status,user_id,type,page){
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');   
    $('.pagination').append('<input name="type" value="'+type+'" style="display:none;">');   
    //$('.pagination').append('<input name="mob" value="'+mobile_no+'" style="display:none;">');
    $('.pagination').append('<input name="user_id" value="'+user_id+'" style="display:none;">');
    //$('.pagination').append('<input name="requested_by" value="'+requested_by+'" style="display:none;">');
    //$('.pagination').append('<input name="email" value="'+email+'" style="display:none;">');

    $('#user_pagination').submit();
}

$("#pageJumpBtn").click(function(){
    var status          = "<?=$status;?>";
    var user_id        = "<?=$user_id;?>";
    var type        = "<?=$type;?>";
    
    var page            = $("#pageJump").val();

    pageHistory(status,user_id,page);
});
$('#pageJump').val("<?=$data['curPage'];?>");



</script>