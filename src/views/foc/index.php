<?php 
use inc\Root;
$this->mainTitle = 'Settings';
$this->subTitle  = 'FOC ';

$this->admin_services = $_SESSION[SITENAME.'_admin_privilages'];
$this->admin_role     = $_SESSION[SITENAME.'_admin_role'];

$add_permission       = in_array(56,$this->admin_services) || $this->admin_role == '1' ? true : false;
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
                            <form id="purchaseform" class="row col-md-12" method="post" action="<?=BASEURL;?>Foc/Index">
                              <!-- <div class="form-group col-sm-2">
                                    <select type="option" name="user_id" class="form-control custom-select" id="user_id">
                                        <option value="">Select Username</option>
                                        <?php if(! empty($s_username)):?>
                                          <option value="<?=$user_id?>" selected><?=$s_username?></option>
                                        <?php endif;?>
                                    </select>
                                  </div> -->
                                  <!-- <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date">
                                  </div>   -->                                
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
                                <div class="form-group col-sm-2">
                                  <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="Search">

                                </div>

                                <?php if($add_permission){?>
                                  
                                <div class="form-group col-sm-3">
                                    <button class="btn btn-outline-info addbtn mb-2" onclick="showAddmodal()" type="button">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Add Remark</button>
                                </div>

                                <?php }?>
                            </form>
                        </div>

                        <!-- <div class="row col-md-12 col-lg-12">
                           
                            <div class="col-md-5 col-lg-5">
                                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;">Yesterday</a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;">Today </a> </span>
                                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;">Last 7 days</a> </span>
                                      
                            </div>
                            
                        </div> -->
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1" >Remark</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th></tr>
                                </thead>
                                <tbody>
                                <?php
                                  if(!empty($data['data'])){

                                     foreach($data['data'] as $key => $val){ ?>                                

                                       <tr role="row" class="odd">
                                           <td><?=$val['remark']?></td>                                          
                                                               
                                           <td><?=$val['status'];?></td>
                                           <td><?=$val['action'];?></td>
                                                           
                                       </tr>

                              <?php }
                                  }else{
                                     echo '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="purchase_pagination" method="post" action="<?=BASEURL;?>Foc/Index">
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Edit Foc Remark</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="closemodal()" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" >
             
                <label>Foc Remark</label>
                <textarea class="form-control" id="foc_reamrk"></textarea>
             

             
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="updateFoc()">Save Changes</button> 
                <button type="button" class="btn btn-danger" onclick="closemodal()">Cancel</button>
            </div>
          
        </div>
    </div>
</div>


<div class="modal fade" id="AddModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" style="line-height: 2.5;"><b>Add Foc Remark</b></h5>
                 <button type="button" class="close" data-dismiss="modal" onclick="addclosemodal()" style="margin-top:3px"  aria-label="Close"> x </button>

            </div>
            <div class="modal-body" >
             
                <label>Foc Remark</label>
                <textarea class="form-control" id="foc_new_reamrk"></textarea>
             

             
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="addFoc()">Save</button> 
                <button type="button" class="btn btn-danger" onclick="addclosemodal()">Cancel</button>
            </div>
          
        </div>
    </div>
</div>

<script>


var status   = '<?=$status?>';
var selectedId = '';
 
$(function () { 

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#settingsMenu').attr("data-active","true");
    $('#settingsNav').addClass('show');
    $('#focList').addClass('active');

    // $('#userID').val(userID);
   
    $('#status').val(status);
        
    $('[data-toggle="tooltip"]').tooltip(); 
    
    $('#search').click(function(){
        $('#purchaseform').submit();
    })
});

function pageHistory(status,page){

 
    
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    
    $('#purchase_pagination').submit();
}



 $("#pageJumpBtn").click(function(){

    pageHistory(status,$("#pageJump").val());
 });



function showEditModal(id)
{
    
    selectedId = id;
    $.ajax({
        url: "<?=BASEURL?>Foc/GetFocEdit",
        type: "post",
        data: {'id':id} ,
        success: function (response) {
          
           var newResp = JSON.parse(response);
           $('#foc_reamrk').val(newResp['remark']);
           $('#editModal').modal('show');
        } 
    });

    

}

function showAddmodal()
{

  

    $('#AddModal').modal('show');  

}

function deleteThis(id)
{
    
    $.ajax({
        url: "<?=BASEURL?>Foc/Delete",
        type: "post",
        data: {'id':id} ,
        success: function (response) {


          
           var newResp = JSON.parse(response);

           if(newResp['status'] == 'success')
           {
               
               openSuccess(newResp['response'],'<?=BASEURL;?>Foc/Index/')  
           }else{
                loadingoverlay('error','error',newResp['response']);
           }
           
           
        } 
    });  

}

function addFoc()
{
    
    $.ajax({
        url: "<?=BASEURL?>Foc/Add",
        type: "post",
        data: {'remark':$('#foc_new_reamrk').val()} ,
        success: function (response) {


          
           var newResp = JSON.parse(response);

           if(newResp['status'] == 'success')
           {
               $('#editModal').modal('hide');
               openSuccess(newResp['response'],'<?=BASEURL;?>Foc/Index/')  
           }else{
                loadingoverlay('error','error',newResp['response']);
           }
           
           
        } 
    });  

}


function updateFoc()
{
    
    $.ajax({
        url: "<?=BASEURL?>Foc/Add",
        type: "post",
        data: {'id':selectedId,'remark':$('#foc_reamrk').val()} ,
        success: function (response) {


          
           var newResp = JSON.parse(response);

           if(newResp['status'] == 'success')
           {
               $('#editModal').modal('hide');
               openSuccess(newResp['response'],'<?=BASEURL;?>Foc/Index/')  
           }else{
                loadingoverlay('error','error',newResp['response']);
           }
           
           
        } 
    });  

}

function switchStatus(id,status){
             
            if(status==1){
                var btn  = "Activate"; 
                var txt = "Are you sure want to proceed ?";
                var swClass ='';
                var changedToStatus = 0 ;
            }else{
                var btn  = "Inactive";
                var txt = "Are you sure want to proceed ?";
                var swClass ='';
                var changedToStatus = 1 ;
            }

            swal({
                 title: btn,
                 text: txt,
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonText: btn,
                 padding: '2em'
                }).then(function(result) {
                    if (result.value) {
                       loadingoverlay('info',"Please wait..","loading...");
                      $.post('<?=BASEURL;?>Foc/ChangeStatus',{'id':id,'status':changedToStatus},function(response){
                            hideoverlay();
                            newResp = JSON.parse(response);
                            if (newResp['status'] == 'success') {
                                openSuccess(newResp['response']);
                            } else {
                                loadingoverlay("error", "Error", newResp['response']);
                            }
                        });
                        return false;
                        
                    }
                    else{
                        location.reload();
                    }
                })
}


 $('#pageJump').val("<?=$data['curPage'];?>");

 function closemodal(){
     $('#foc_reamrk').val('');
     $('#editModal').modal('toggle');
  }
 function addclosemodal(){
     $('#foc_new_reamrk').val('');
     $('#AddModal').modal('toggle');
  }


</script>
