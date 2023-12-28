<?php 
use inc\Root;
$this->mainTitle = 'Leave Settings';
$this->subTitle  = 'Leave Settings List';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$create_permission  = in_array(79,$this->admin_services) || $this->admin_role == '1' ? true : false;


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
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>leave/Index">
                                <div class="col-md-5 col-lg-5"> 
                                    <input type="text" name="name" class="form-control select" id="name" placeholder="Leave Type">
                                </div>

                                <div class="form-group col-sm-4">
                                  <input type="submit" class="btn btn-success" id="search" name="Search" value="Search">
                              </div>  
                                <?php if($create_permission){?>
                                <div class="form-group col-sm-3">
                                       <a href="<?=BASEURL?>Leave/UpdateLeaveType/" class="btn btn-outline-info addbtn mb-2">
                                        Create Leave Type
                                       </a>
                                </div>
                                <?php }?>
                            </form>
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                      <th class="sorting_disabled" rowspan="1" colspan="1" >Leave Type</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Maximum Leave (In Days)</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                  if(!empty($data['data'])){

                                     foreach($data['data'] as $key => $val){ ?>                                

                                       <tr role="row" class="odd">
                                           <td><?=$val['leave_name']?></td>
                                           <td><?=$val['allowed_count']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['action']?></td>
                                                           
                                       </tr>

                              <?php }
                                  }else{
                                     echo '<tr><td colspan="11" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>Leave/Index">
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

<script>


 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#LeaveMenu').attr("data-active","true");
    $('#LeaveNav').addClass('show');
    $('#LeaveList').addClass('active');

    var name        = '<?=$name?>';
    $('#name').val(name);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});


function pageHistory(name,page){

 
    
    $('.pagination').append('<input name="name" value="'+name+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){

    pageHistory(name,$("#pageJump").val());

 });


function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Leave Type !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Leave/DeleteLeavetype/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','Error',newResp['response']);
                    }
                });
                return false;
            }
        })
}

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
            $.post('<?=BASEURL;?>Leave/ChangeLeaveTypeStatus',{'id':id,'status':new_status},function(response){

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
function closemodal(modalid){
     
    $('#'+modalid).modal('toggle');
}
</script>
